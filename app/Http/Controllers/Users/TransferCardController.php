<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserAmount;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\Card;
use Illuminate\Support\Str;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\AmountBillingInvoice;

class TransferCardController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
// Not used Now
public function index(){
	$no_of_packages = array();
	$sst = 0.195;
	$adv_tax = 0.150;
	$final_rates = 0;
	$amount = UserAmount::where('username',Auth::user()->username)->first();
	$subdealers = UserInfo::where('dealerid',Auth::user()->dealerid)->where('status','subdealer')->get();
	$packages = Profile::orderby('groupname','ASC')->get();

	$dealerProfileRate = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->select('name','rate')->get();
		foreach ($dealerProfileRate as $key => $value) {
			$rate = $value->rate+1;
			$sstTax = $rate*$sst;
			$advTax = ($rate+$sstTax)*$adv_tax;
			$final_rates = ($rate+$sstTax+$advTax);
			$no_of_packages[$value->name] = floor($amount->amount / $final_rates);
			
		}
		// dd(count($no_of_packages));

	return view('users.billing.transfer_card',
	[
		'amount' => $amount,
		'subdealer' => $subdealers,
		'profiles' => $packages,
		'no_of_packages' => $no_of_packages
	]
);
}
public function fetchProfile(Request $request)
{
	$username = $request->username;
	$data = UserInfo::where('username',$username)->select('dealerid','sub_dealer_id')->first();
	$profiles = SubdealerProfileRate::where('dealerid',$data->dealerid)->where('sub_dealer_id',$data->sub_dealer_id)->orderby('groupname')->get();
	return response()->json($profiles);
}
public function checkCard(Request $request)
{

	$packageName = $request->packagename;
	$pkgCount = $request->no_card;
	$no_of_packages = array();
	$sst = 0.195;
	$adv_tax = 0.150;
	$final_rates = 0;

	foreach ($packageName as $key => $value) {
	$amount = UserAmount::where('username',Auth::user()->username)->first();
	$dealerProfileRate = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->where('groupname',$value)->select('rate')->first();

			$rate = $dealerProfileRate->rate+1;
			$sstTax = $rate*$sst;
			$advTax = ($rate+$sstTax)*$adv_tax;
			$final_rates = ($rate+$sstTax+$advTax);
			$no_of_packages[] = floor($amount->amount / $final_rates);
			foreach ($no_of_packages as $key => $value) {
				if($pkgCount[$key] > $value){
					// dd($pkgCount[$key]);
				return response()->json("exceed");
				}
			}
			
		// dd($no_of_packages);
	}

	return response()->json($no_of_packages);
}
public function transferCard(Request $request)
{
	
	// dd($strfirst.''.time().''.$strlast);
	$packages = $request->packagename;
	$packageCount = $request->no_card;
	$subdealer = $request->subdealername;
	$sbid = UserInfo::where('username',$subdealer)->first();
	// dd($subdealer);
	foreach ($packages as $key => $packageName) {
		
	$name = Profile::where('groupname', $packageName)->select('name')->first()->name;
	// dd($name);

	// Code from amount billing invoice
	$currentUser = Auth::user();
// dd($currentUser);
	// current user reseller
	$currentUserResellerUserName = UserInfo::select('username')->where(['resellerid' => $currentUser->resellerid,'status' => 'reseller'])->first();
	$resellerUserAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
	$resellerAmount = $resellerUserAmount->amount;
	///
	$getGroup = Profile::where(['name'=> $name])->first()->groupname;
	$profileGroupname = $getGroup;
	if($currentUser->status == "dealer"){
	
		$getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>$sbid->sub_dealer_id,'name' => $name])->first();
		// dd($getsubdealerrate);	   
		if(empty($getsubdealerrate))
		 {
		 	return redirect()->route('users.dashboard');
		 }
	}
	// check user has sufficiant amount: user_amount
	$userAmount = UserAmount::where('username', $currentUser->username)->first();
	$amount = $userAmount->amount;
	
	if($currentUser->status == 'dealer'){
		$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'name' => $name])->first();
		$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'name' => $name])->first();
		$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'name' => $name])->first();
		$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $sbid->sub_dealer_id,'name'=>$name])->first();
		$customertax = Profile::where(['name' => $name])->first();
		$taxgroup = $profileRate['taxgroup'];
		$name = $customertax->name;
	}
	
	if($amount >= $profileRate['final_rates']){
		// will recharge the user
		// $this->rechargeUser($request,$profileGroupname,$username,$currentUser);

		
		if($taxgroup == "B"){
		$customersst = $customertax->sstB;
		$customeradv = $customertax->adv_taxB;
		$customercharges = $customertax->chargesB;
		$customerrates = $customertax->final_ratesB;
		$taxname = "CX-V2";
	}else if($taxgroup == "C"){
		
		$customersst = $customertax->sstC;
		$customeradv = $customertax->adv_taxC;
		$customercharges = $customertax->chargesC;
		$customerrates = $customertax->final_ratesC;
		$taxname = "CX-V3";
	}else if($taxgroup == "D"){
		$customersst = $customertax->sstD;
		$customeradv = $customertax->adv_taxD;
		$customercharges = $customertax->chargesD;
		$customerrates = $customertax->final_ratesD;
		$taxname = "CX-V4";

	}else if($taxgroup == "E"){
		$customersst = $customertax->sstE;
		$customeradv = $customertax->adv_taxE;
		$customercharges = $customertax->chargesE;
		$customerrates = $customertax->final_ratesE;
		$taxname = "CX-V5";

	}
	else{
		$customersst = $customertax->sst;
		$customeradv = $customertax->adv_tax;
		$customercharges = $customertax->charges;
		$customerrates = $customertax->final_rates;
		$taxname = "CX-V1";
	}
		
	// dd($profileRate);
			//////////////////////
		// $username = $username;
		// $date =str_replace('-', '', date('Y-m-d'));
		// $userid = $username.$date;
		$m_rate = $m_profileRate['final_rates'];
		$rate = $resellerprofileRate['final_rates'];

			if(Auth::user()->status =="dealer"){
				$dealerrate = $dealerprofileRate['final_rates'];	
				$subdealerrate = $profileRate['final_rates'];
				$s_acc_rate = $profileRate->rate;
				$sst = $profileRate->sst;
				$adv_tax = $profileRate->adv_tax;
				$traderrate = 0;
				
				$t_acc_rate = 0;
				$c_sst = $sst;
				$c_adv = $adv_tax;
				$c_charges = $profileRate['consumer'];
				$c_rates = $customerrates;
		}else{
		$dealerrate = $dealerprofileRate->rate;
		$subdealerrate = $s_profileRate['final_rates'];
		$traderrate = $profileRate['final_rates'];
		$s_acc_rate = $s_profileRate->rate;
		$t_acc_rate = $profileRate->rate;
		$sst = $profileRate->sst;
		$adv_tax = $profileRate->adv_tax;


		$c_sst = $sst;
		$c_adv = $adv_tax;
		$c_charges = $s_profileRate['consumer'];
		$c_rates = $customerrates;

		}
	

		
		$d_r = $dealerprofileRate->rate;
		$r_r = $resellerprofileRate->rate;
		$c_a = $dealerprofileRate->commision;
		$p_r = $d_r - $r_r;
		$profit = $p_r - $c_a;
		
		
		
		$commision = $dealerprofileRate->commision;
		$m_acc_rate = $m_profileRate->rate;
		$r_acc_rate = $resellerprofileRate->rate;
		$d_acc_rate = $dealerprofileRate->rate;
		
		$profit = $profit;
	
		$profile = $profileGroupname;
		$name = $name;
		$taxname = $taxname;
		
		$trader_id = Auth::user()->trader_id;
		$sub_dealer_id = Auth::user()->sub_dealer_id;
		$dealerid = Auth::user()->dealerid;
		$resellerid = Auth::user()->resellerid;
		$manager_id = Auth::user()->manager_id;
		$date = date('Y-m-d');


	$multyply = $d_acc_rate*2 + $sst + $adv_tax;
	$divide = $multyply/2;

	if($subdealerrate ==0){
	  $dividesub=0;
	}else{
	  $multyplysub = $s_acc_rate*2 + $sst + $adv_tax;
	  $dividesub = $multyplysub/2;
	}
	
	$resellermultyply = $r_acc_rate*2 + $sst + $adv_tax;
	$resellerdivide = $resellermultyply/2;

	for ($i=1; $i <= $packageCount[$key]; $i++) { 
		$userAmount = UserAmount::where('username', $currentUser->username)->first();
		$amount = $userAmount->amount;

		$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $sbid->sub_dealer_id,'groupname'=>$profile])->first();

		if($amount >= $profileRate['final_rates']){
		// store actual amount with taxes in card table column final_rate_acc
		$dealerProfileRate = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->where('groupname',$packageName)->select('rate')->first();
			$rate = $dealerProfileRate->rate+1;
			$sstTax = $rate*0.195;
			$advTax = ($rate+$sstTax)*0.150;
			$final_rates = ($rate+$sstTax+$advTax);
			
		$strfirst = Str::random(4);
		$strlast = Str::random(4);
		$card_no = $strfirst.''.time().''.$strlast;
		$data = DB::table('card')->insert([
			"card_no" => $card_no, // Auto Genrate with datetime unique card number
			"status" => 'unused', // Status via card used or not
			"m_rate" => $m_rate,
			"rate" => $resellerdivide,
			"dealerrate" => $divide,
			"subdealerrate" => $dividesub,
			"s_acc_rate" => $s_acc_rate,
			"sst" => $sst,
			"adv_tax" => $adv_tax,
			 "traderrate" => $traderrate,
			"t_acc_rate" => $t_acc_rate,
			"commision" => $commision,
			"m_acc_rate" => $m_acc_rate,
			"r_acc_rate" => $r_acc_rate,
			"d_acc_rate" => $d_acc_rate,
			"c_sst" => $c_sst,
			"c_adv" => $c_adv,
			 "c_charges" => $c_charges,
			"c_rates" => $c_rates,
			"profit" => $profit,
			"profile" => $profile,
			"name" => $name,
			"taxname" => $taxname,
			 "trader_id" => $trader_id,
			 "dealerid" => $dealerid,
			"resellerid" => $resellerid,
			"manager_id" => $manager_id,
			"sub_dealer_id" => $sbid->sub_dealer_id,
			"final_rate_acc" => $final_rates,
			"date" => $date,
			"created_at" => NOW()
		]);

		// in last
		$userAmount->amount -= $profileRate->final_rates-1;
		$userAmount->save();
		}else{
			return response()->json('You have insufficient amount.');
		}
	}
	
}else{
	return response()->json('You have insufficient amount.');
}
}
	return response()->json('Data Submited');
}
// This function Not use at the moment
public function viewTransferCard()
{
	$transferCard = Card::where('dealerid',Auth::user()->dealerid)->select(DB::raw('sub_dealer_id,name,date,count(*) profilecount'))->groupby(['sub_dealer_id',"name","date"])->get();
	// dd($transferCard);
	$totalCount = $transferCard->count();
	return view('users.billing.view_transfer',['transferCard' => $transferCard, 'totalCount' => $totalCount]);
	// users.billing.view_transfer
}
public function BillingDetailView()
{
	$currentUser = Auth::user()->status;
            	if($currentUser == "manager"){
            		$userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
            	}elseif($currentUser == "reseller"){
            		$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
            	}elseif($currentUser == "dealer"){
            		$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
            	}elseif($currentUser == "subdealer"){
            		$userCollection = UserInfo::select('username')->where(['status'=>'trader','sub_dealer_id' => Auth::user()->sub_dealer_id])->get();
            	}elseif($currentUser == 'inhouse'){
            		$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
            	}elseif($currentUser == "trader"){
                    $userCollection = UserInfo::select('username')->where(['username'=> Auth::user()->username ])->get();
                }
              
                return view('users.billing.billing_summary',[
                    'userCollection' => $userCollection,
                    'isSearched' => false,
                   
                ]);
}
public function BillingCardDetail(Request $request)
{

$username = $request->get('username');
$date = $request->get('datetimes');
$date2 = $request->get('date');

$range = explode('-', $date);
$from1 = $range[0];
$to1 = $range[1];
if($date2 == ''){
$from = date("Y-m-d H:i:s", strtotime($from1));

$to = date("Y-m-d H:i:s", strtotime($to1));
}else{
	$ebd=explode('-', $date2);
if($ebd[2] == 10){
$from = date("Y-m-25 12:00:00", strtotime("-1 months", strtotime($date2)));
$to = date($date2.' 12:00:00');
$dueDate= date("Y-m-15");
}else{
	   
$from = date("Y-m-10 12:00:00", strtotime($date2));
$dueDate= date("Y-m-30");
$to = date($date2.' 12:00:00');
}

if($date2 == '2019-04-25'){
$from = date('2019-04-01');
//     $start=date('2019-04-01');
// $end=date('2019-04-25');
}
}



///for all users    

if($username == "all"){
	$selectedUser = UserInfo::where('username',Auth::user()->username)->first();

	$monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('rate');
	
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
	$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid'=>Auth::user()->dealerid])->get();


	
	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
}
if($username == "own"){

	$selectedUser = UserInfo::where('username',Auth::user()->username)->first();
	$monthlyPayableAmountacc = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('dealerrate');
	
	if($from < '2020-06-25 12:00:00' && $to > '2020-06-25 12:00:00'){
		//->where('charge_on','>',date($from))
		$from4 = date('2020-06-25 12:00:00');
	 $monthlyPayableAmountsst = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')->where('charge_on','>',date($from4))->where('charge_on','<',date($to))->with('user_info')->sum('sst');
	
	 $monthlyPayableAmountadv = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')
	//->where('charge_on','>',date($from))
	 ->where('charge_on','>',date($from4))
	->where('charge_on','<',date($to))->with('user_info')->sum('adv_tax');
	$monthlyPayableAmount = $monthlyPayableAmountacc ;

}else if($from >= '2020-06-25 12:00:00' && $to > '2020-06-25 12:00:00'){
	
	 $monthlyPayableAmountsst = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('sst');
	 
	 $monthlyPayableAmountadv = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')
	//->where('charge_on','>',date($from))
	 ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('adv_tax');
	 

	$monthlyPayableAmount = $monthlyPayableAmountacc ;
}else{
	$monthlyPayableAmount = $monthlyPayableAmountacc ;

}
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)->where('billing_type','!=','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

	$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid'=>Auth::user()->dealerid])->get();

	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'from' => $from,
		'to' => $to,
		'own' => true
	]);
}else{
  $selectedUser = UserInfo::where('username',$username)->first();
}

if($selectedUser->status == 'dealer'){
	// report summary
	// $monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
	// ->whereBetween('date',[$from,$to])->with('user_info')->sum('dealerrate');

	$monthlyPayableAmountacc = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('d_acc_rate');

	 $monthlyPayableAmountsst = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('sst'); $monthlyPayableAmountadv = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('adv_tax');

	$monthlyPayableAmount = $monthlyPayableAmountacc + $monthlyPayableAmountsst+$monthlyPayableAmountadv;
	
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
	$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid'=>Auth::user()->resellerid])->get();
	

	 if($from < date("2019-12-10 12:00:00")){
	 $monthlyBillingEntries =array();
	 $monthlyPayableAmount =0;
	   }


	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
	
}elseif($selectedUser->status == 'subdealer'){
		$monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('subdealerrate');
	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
		  if(Auth::user()->status == "dealer"){
		$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
	   }else{
		$userCollection = UserInfo::select('username')->where(['status' =>'trader','sub_dealer_id' => Auth::user()->sub_dealer_id])->get();
	   }
	
	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);     
}elseif ($selectedUser->status == 'reseller') {
	$monthlyPayableAmount = AmountBillingInvoice::where(['resellerid' => $selectedUser->resellerid])
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('rate');


	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

	$userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id'=>Auth::user()->manager_id])->get();

	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
}elseif ($selectedUser->status == 'manager') {
	$monthlyPayableAmount = AmountBillingInvoice::where(['manager_id' => $selectedUser->manager_id])
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('m_rate');


	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

	$userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id'=>Auth::user()->manager_id])->get();

	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
}
elseif ($selectedUser->username == Auth::user()->username) {

	if($selectedUser->status == "subdealer"){
		
		$monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('subdealerrate');
	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();   
	$userCollection = UserInfo::select('username')->where(['status' =>'trader','sub_dealer_id' => Auth::user()->sub_dealer_id])->get();
}else{
   $monthlyPayableAmount = AmountBillingInvoice::where('trader_id' , $selectedUser->trader_id)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('traderrate');
	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('trader_id' , $selectedUser->trader_id)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();   
	$userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
   
}
	
}else{
	 $monthlyPayableAmount = AmountBillingInvoice::where('trader_id' , $selectedUser->trader_id)
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('traderrate');
	
	// report summary
	// monthly Billing Report
	$monthlyBillingEntries = AmountBillingInvoice::where('trader_id' , $selectedUser->trader_id)->where('billing_type','card')
	->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();   
	$userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
	return view('users.billing.billing_summary',[
		'userCollection' => $userCollection,
		'isSearched' => true,
		'selectedUser' => $selectedUser,
		'monthlyPayableAmount' => $monthlyPayableAmount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'own' => false
	]);
}
// return $monthlyBillingEntries;
}
}