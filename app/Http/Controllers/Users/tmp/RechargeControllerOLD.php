<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadCheck;
use App\model\Users\DealerProfileRate;
use App\model\Users\DealerFUP;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\TraderProfileRate;
use App\model\Users\AmountUserRechargeLog;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\RaduserGroup;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\UserAmount;
use App\model\Users\AssignNasType;
use App\model\Users\Profile;
use App\model\Users\Nas;
use App\model\Users\ExpireUser;
use App\model\Users\UserVerification;
use App\model\Users\UserAmountBillingBrg;
use App\model\Users\FreezeAccount;
use App\model\Users\HalfBillingInvoice;
use App\model\Users\Card;
use App\model\Users\Cirprofile;


class RechargeController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 
    public function __construct()
    {

    }


    public function index($status)
    {
		$user = Auth::user();
		$check ='';
		$freeze_account = FreezeAccount::where(['username' => $user->username])->first();
		$check = $freeze_account['freeze'];
		if(!empty($freeze_account)){
			if($check == 'yes'){
				return redirect()->route('users.dashboard');
			}
		}
        switch ($status) {
            case 'recharge':{
				$profileRates = collect();
				$userList = collect();
				if($user->status == 'dealer'){
					  $profileRates = $user->dealer_profile_rates;
					  $userList = $user->dealer_expired_users()->get();
					  $rechargeUsers = AmountBillingInvoice::where(['dealerid' => $user->dealerid , 'date' => date('Y/m/d')])->get();
				      $currentAmount = UserAmount::where(['username' => $user->username])->first();
					}elseif($user->status == 'subdealer'){					
						$profileRates = $user->subdealer_profile_rates;
						$userList = $user->sub_dealer_expired_users()->get();
						$rechargeUsers = AmountBillingInvoice::where(['sub_dealer_id' => $user->sub_dealer_id , 'date' => date('Y/m/d')])->get();
					    $currentAmount = UserAmount::where(['username' => $user->username])->first();
					}elseif($user->status == 'trader'){					
						$profileRates = $user->trader_profile_rates;
						$userList = $user->trader_expired_users()->get();
						$rechargeUsers = AmountBillingInvoice::where(['sub_dealer_id' => $user->sub_dealer_id , 'date' => date('Y/m/d')])->get();
					    $currentAmount = UserAmount::where(['username' => $user->username])->first();
					}
				return view('users.dealer.recharge',[
					'userList' => $userList,
					'profileRates' => $profileRates,
					'rechargeUsers' =>$rechargeUsers,
				]);	
			}
                break;
            case 'bulk':
				$user = Auth::user();
					$profileRates = collect();
					$userList = collect();
					if($user->status == 'dealer'){
					  $profileRates = $user->dealer_profile_rates;
					  // $userList = $user->dealer_expired_users()->get();
						// dd('dealer');
					  $userList = UserInfo::leftJoin('user_status_info', function($join) {
						$join->on('user_status_info.username', '=', 'user_info.username');
				})->where(['user_info.dealerid' => $user->dealerid ,'user_info.status' => 'user','user_info.sub_dealer_id' => NULL])->where('user_info.profile' , '!=','DISABLED')->where('user_info.name' , '!=','DISABLED')->where(function($q) {
						$q->where('user_status_info.card_expire_on','>=',date('Y-m-d',strtotime(date('Y-m-d').' -1 months')));
				})->select('user_info.username','user_info.dealerid','user_info.firstname','user_info.lastname','user_info.profile','user_info.address')->get();
					  $currentAmount = UserAmount::where(['username' => $user->username])->first();
					}else{	
						// dd('sub');				
						$profileRates = $user->subdealer_profile_rates;
						$userList = UserInfo::leftJoin('user_status_info', function($join) {
						$join->on('user_status_info.username', '=', 'user_info.username');
				})->where(['user_info.sub_dealer_id' => $user->sub_dealer_id ,'user_info.status' => 'user'])->where('user_info.profile' , '!=','DISABLED')->where('user_info.name' , '!=','DISABLED')->where(function($q) {
						$q->where('user_status_info.card_expire_on','>=',date('Y-m-d',strtotime(date('Y-m-d').' -1 months')));
				})->select('user_info.username','user_info.sub_dealer_id','user_info.firstname','user_info.lastname','user_info.profile','user_info.address')->get();
						//$user->sub_dealer_expired_users()->get();
						$currentAmount = UserAmount::where(['username' => $user->username])->first();
					}
                return view('users.dealer.bulk_recharge',[
					'userList' => $userList,
				]);
				break;
            default:
               return redirect()->route('users.dashboard');
                break;
        }
    }
	  public function bulkRecharge(Request $request)
    {
		$dataList = $request->get('dataList');
		// dd($dataList);	
		
$count = 0;
		foreach($dataList as $data){
			
			$d = explode(',',$data);
			$username = $d[0];
			$profileGroupname = $d[1];
			// dd($profileGroupname);
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//  dd('New');
$f_nic = '';
$nic = '';
$b_nic = '';
$ntn = '';
$passport = '';
$overseas = '';
$uname = '';
$profileRate = '';
$resellerprofileRate = '';
	   ////verification allow
$checkDealeralow='';
$checkalowverification='';
$verification='';
$alowdealer='';
$checkDealeralow = UserInfo::where('username',$username)->first();
$alowdealer = $checkDealeralow['dealerid'];
$alowsubdealer = $checkDealeralow['sub_dealer_id'];
if(empty($alowsubdealer)){

$checkalowverification = DealerProfileRate::where('dealerid',$alowdealer)->first();
}else{

$checkalowverification = SubdealerProfileRate::where('sub_dealer_id',$alowsubdealer)->first();
}
$verification = $checkalowverification['verify'];

if($verification == 'no'){
		   $f_nic = 'done';
		   $nic = 'done';
		   $b_nic = 'done';
		   $uname = $username;
		   $ntn ='done';
		   $passport = 'done';
		   $overseas = 'done';
}else{
	$isverify = UserVerification::where('username',$username)->select('username','cnic','nic_front','nic_back','ntn','intern_passport','overseas')->first();
	   
		   $f_nic = $isverify['nic_front'];
		   $nic = $isverify['cnic'];
		   $b_nic = $isverify['nic_back'];
		   $uname = $isverify['username'];
		   $ntn =$isverify['ntn'];
		   $passport = $isverify['intern_passport'];
		   $overseas = $isverify['overseas'];
}
		
if(($username == $uname) && ($nic != '' || $ntn !='' || $overseas != '' || $passport != '' )&& ($f_nic != '' && $b_nic != '')){

$Checkingexpiry = UserStatusInfo::where(['username'=>$username])->first();
$expire = $Checkingexpiry['card_expire_on'];
$cur_date=date('Y-m-d');
if($expire > $cur_date){
	 session()->flash('error',' Your user Already charge.');
   return redirect()->route('users.user.index1',['status' => 'user']);
}else{
$getGroup = Profile::where(['groupname'=> $profileGroupname])->first();
// $profileGroupname = $getGroup->name;
// 			dd($profileGroupname);
//.................................................................................................................//
//  New Card Charge code start from here.......
$checkIsSubdealerUser = UserInfo::where('username',$username)->select('username','dealerid','sub_dealer_id')->first();
$billingType = DealerProfileRate::where('dealerid',$checkIsSubdealerUser->dealerid)->select('billing_type')->first();
// dd($billingType->billing_type);
if($checkIsSubdealerUser->sub_dealer_id != NULL && $billingType->billing_type == 'card'){

	$getGroup = Profile::where(['groupname'=> $profileGroupname])->first();
	$profileGroupname = $getGroup->name;
				// dd($profileGroupname);
   $currentUser = Auth::user();
   $check ='';
$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
$check = $freeze_account['freeze'];

if(!empty($freeze_account)){
	if($check == 'yes'){
		return redirect()->route('users.dashboard');
	}
} 
   $card = Card::where('sub_dealer_id',Auth::user()->sub_dealer_id)->where('status','unused')->where('name',$profileGroupname)->first();
   if($card != null){
	
   $card_nos = $card->card_no;
   $status = $card->status;
   $m_rate = $card->m_rate;
   $rate = $card->rate;
   $dealerrate = $card->dealerrate;
   $subdealerrate = $card->subdealerrate;
   $s_acc_rate = $card->s_acc_rate;
   $sst = $card->sst;
   $adv_tax = $card->adv_tax;
   $traderrate = $card->traderrate;
   $t_acc_rate = $card->t_acc_rate;
   $commision = $card->commision;
   $m_acc_rate = $card->m_acc_rate;
   $r_acc_rate = $card->r_acc_rate;
   $d_acc_rate = $card->d_acc_rate;
   $c_sst = $card->c_sst;
   $c_adv = $card->c_adv;
   $c_charges = $card->c_charges;
   $c_rates = $card->c_rates;
   $profit = $card->profit;
   $profile = $card->profile;
   $name = $card->name;
   $taxname = $card->taxname;
   $charge_on = date('Y-m-d H:i:s');
   $trader_id = $card->trader_id;
   $dealerid = $card->dealerid;
   $resellerid = $card->resellerid;
   $sub_dealer_id = $card->sub_dealer_id;
   $manager_id = $card->manager_id;
   $date = date('Y-m-d');
   $billing_type = 'card';
	  // will recharge the user
	  $this->rechargeUser($request,$profile,$username,$currentUser);
		  //////////////////////
	  $username = $username;
	  $date =str_replace('-', '', date('Y-m-d'));
	  $userid = $username.$date;
		  
	  if($sst == 0){
		  $receipt = 0;
	  }else{
		  $receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
		  $num = $receipt_last->receipt_num;
		  $receipt = $num +1;
	  }
   //    $profit = $profit;
	  $receipt1 = $currentUser->receipt;
	  $receipt_num = $receipt;
	  $charge_on = date('Y-m-d H:i:s');
	  $dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'groupname' => $profile])->first();
	  $dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
	  AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,$receipt1,$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,$billing_type,$card_nos);
	   //update Card Table while card used
	   $card->status = 'used';
	   $card->save();
   }else{
	   session()->flash('error',' No profile available.');
	   return redirect()->route('users.single',$checkDealeralow->id);
   }
	  if(Auth::user()->status == "dealer"){
		  $activity = "view Account Recharged by".Auth::user()->dealerid;
	  }elseif(Auth::user()->status == "subdealer"){
		  $activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
	  }
	  else{
		  $activity = "view Account Recharged by".Auth::user()->trader_id;
	  }
	  $amountUserRechargeLog = new AmountUserRechargeLog();
	  $amountUserRechargeLog->dealerid = Auth::user()->dealerid;
	  $amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
	  $amountUserRechargeLog->trader_id = Auth::user()->trader_id;
	  $amountUserRechargeLog->username = $username;
	  $amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
	  $amountUserRechargeLog->activity = $activity;
	  // $amountUserRechargeLog->error =
	  $amountUserRechargeLog->ipaddress = $request->ip();
	  $amountUserRechargeLog->profile = $profile;
	  $amountUserRechargeLog->save();
	  // amount before recharge
	  $userAmountBillingBrg = new UserAmountBillingBrg();
	  $userAmountBillingBrg->billing_invoice_id = 1;
	  $userAmountBillingBrg->last_remaining_amount =NULL;
	  $userAmountBillingBrg->save();

	//   session()->flash('success',' Successfully Recharged..........');
	//   return redirect()->route('users.user.index1',['status' => 'user']);
//.......................................................................................................................//
}else{
	//
 
 $currentUser = Auth::user();
 $check ='';
 $freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
 $check = $freeze_account['freeze'];
 
 if(!empty($freeze_account)){
  if($check == 'yes'){
	  return redirect()->route('users.dashboard');
  }
 } 
 
 $getsubdealerrate='';
 $getdealerrate = '';
 if($currentUser->status == "subdealer")
 {
	  $getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'groupname' => $profileGroupname])->first();
	   if(empty($getsubdealerrate))
 {
	 return redirect()->route('users.dashboard');
 }
 }elseif($currentUser->status == "dealer"){
	 $getdealerrate = DealerProfileRate::where(['dealerid'=>Auth::user()->dealerid,'groupname' => $profileGroupname])->first();
 
	   if(empty($getdealerrate))
 {
	 return redirect()->route('users.dashboard');
 }
 }elseif($currentUser->status == "trader"){
	 $getdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'groupname' => $profileGroupname])->first();
 
	   if(empty($getdealerrate))
 {
	 return redirect()->route('users.dashboard');
 }
 }
 // current user reseller
 $currentUserResellerUserName = UserInfo::select('username')->where(['resellerid' => $currentUser->resellerid,'status' => 'reseller'])->first();
 $resellerUserAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
 $resellerAmount = $resellerUserAmount->amount;
 ///
 
 // check user has sufficiant amount: user_amount
 $userAmount = UserAmount::where('username', $currentUser->username)->first();
 $amount = $userAmount->amount;
 
 if($currentUser->status == 'dealer'){
	$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
	$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
	$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
	$profileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
	$customertax = Profile::where(['groupname' => $profileGroupname])->first();
	$taxgroup = $dealerprofileRate['taxgroup'];
	$name = $customertax->name;
 }elseif($currentUser->status == 'subdealer'){
	$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
	$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
		$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
	$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
	$customertax = Profile::where(['groupname' => $profileGroupname])->first();
	$taxgroup = $profileRate['taxgroup'];
		$name = $customertax->name;
 }elseif($currentUser->status == 'trader'){
	$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
	$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
		$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
	$s_profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
	$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
	$customertax = Profile::where(['groupname' => $profileGroupname])->first();
	$taxgroup = $profileRate['taxgroup'];
		$name = $customertax->name;
 }
 
 if($amount >= $profileRate['final_rates']){
	// will recharge the user
	$this->rechargeUser($request,$profileGroupname,$username,$currentUser);
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
		//////////////////////
	$username = $username;
	$date =str_replace('-', '', date('Y-m-d'));
	$userid = $username.$date;
	$m_rate = $m_profileRate['final_rates'];
	$rate = $resellerprofileRate['final_rates'];
 
		if(Auth::user()->status =="subdealer"){
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
		
	}elseif(Auth::user()->status =="dealer"){
	$dealerrate = $dealerprofileRate['final_rates'];
	$subdealerrate=0;
	$s_acc_rate = 0;
	$traderrate = 0;
	
	$t_acc_rate = 0;
 
	$sst = $dealerprofileRate->sst;
	$adv_tax = $dealerprofileRate->adv_tax;
	$c_sst = $sst;
	$c_adv = $adv_tax;
	$c_charges = $dealerprofileRate['consumer'];
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
 
	if($dealerprofileRate->sst == 0){
		$receipt = 0;
	}else{
		$receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
		$num = $receipt_last->receipt_num;
 
		$receipt = $num +1;
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
	$receipt1 = $currentUser->receipt;
	$receipt_num = $receipt;
	$profile = $profileGroupname;
	$name = $name;
	$taxname = $taxname;
	$charge_on = date('Y-m-d H:i:s');
	$trader_id = Auth::user()->trader_id;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$dealerid = Auth::user()->dealerid;
	$resellerid = Auth::user()->resellerid;
	$manager_id = Auth::user()->manager_id;
	$date = date('Y-m-d');
	$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'groupname' => $profileGroupname])->first();
	$dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
	AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,$receipt1,$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,'amount',Null);
 /////////////////////////
	if(Auth::user()->status == "dealer"){
		$activity = "view Account Recharged by".Auth::user()->dealerid;
	}elseif(Auth::user()->status == "subdealer"){
		$activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
	}
	else{
		$activity = "view Account Recharged by".Auth::user()->trader_id;
	}
 
	$amountUserRechargeLog = new AmountUserRechargeLog();
	$amountUserRechargeLog->dealerid = Auth::user()->dealerid;
	$amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
	$amountUserRechargeLog->trader_id = Auth::user()->trader_id;
	$amountUserRechargeLog->username = $username;
	$amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
	$amountUserRechargeLog->activity = $activity;
	// $amountUserRechargeLog->error =
	$amountUserRechargeLog->ipaddress = $request->ip();
	$amountUserRechargeLog->profile = $profileGroupname;
	$amountUserRechargeLog->save();
	// amount before recharge
	$userAmountBillingBrg = new UserAmountBillingBrg();
	$userAmountBillingBrg->billing_invoice_id = 1;
	$userAmountBillingBrg->last_remaining_amount =$amount;
	$userAmountBillingBrg->save();
	// in last
	$userAmount->amount -= $profileRate->final_rates;
	$userAmount->save();
  
 }else{
	session()->flash('error',' You have insufficient amount.');
	return redirect()->route('users.user.index1',['status' => 'user']);
 }
 }
//  New Card Charge code End here.......
}
}else{
session()->flash('success',' Please Verify Your Cnic then  Recharged.');
   return redirect()->route('users.user.index1',['status' => 'user']);
}
$count++;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
		session()->flash('success',' Total '.$count.' User  Successfully Recharged ');
		return redirect()->route('users.charge.index',['status' => 'bulk']);
	}
	public function single($id)
	{
		$current = Auth::user()->status;
		if($current =="dealer"){
			$user =UserInfo::find($id);
			// dd($user->sub_dealer_id);
			if($user->sub_dealer_id != NULL){
				session()->flash('error',' Subdealer user not charge from Dealer Panel.');
				return redirect()->route('users.user.index1',['status' => 'expire']);
			}
		    $checkUser = $user->username;
		    // 
		    $dealerUser = UserInfo::where(['username' => $checkUser ,'dealerid'=>Auth::user()->dealerid,'sub_dealer_id'=> null])->first();
		    // $dealerUser = UserInfo::where('username',$checkUser)->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id', '')->orWhere('sub_dealer_id', NULL)->first();
		    
		    // dd($dealerUser);
		    $username = $dealerUser->username;
		    $profile = $dealerUser->name;
			// dd($profile);
		    $profile = str_replace('BE-', '', $profile);
		    $profile = str_replace('k', '', $profile);

		    $profilename = Profile::where(['name'=>$profile])->first();
		    $name = $profilename->name;


		}elseif($current =="subdealer"){
			$user =UserInfo::find($id);
		    $checkUser = $user->username;

		    $subdealerUser = UserInfo::where(['username' => $checkUser ,'sub_dealer_id'=> Auth::user()->sub_dealer_id])->first();
		    $username = $subdealerUser->username;
		    $profile = $subdealerUser->name;
		    $profile = str_replace('BE-', '', $profile);
		    $profile = str_replace('k', '', $profile);

		    $profilename = Profile::where(['name'=>$profile])->first();
		    $name = $profilename->name;
		}else{
			$user =UserInfo::find($id);
		    $checkUser = $user->username;

		    $subdealerUser = UserInfo::where(['username' => $checkUser ,'trader_id'=> Auth::user()->trader_id])->first();
		    $username = $subdealerUser->username;
		    $profile = $subdealerUser->name;
		    $profile = str_replace('BE-', '', $profile);
		    $profile = str_replace('k', '', $profile);

		    $profilename = Profile::where(['name'=>$profile])->first();
		    $name = $profilename->name;
		}
		return view('users.dealer.single_recharge',[
			'username' => $username,
			'name' => $name

		]);
		
	}


	


	public function viewUserRecharge(Request $request)
	{
		$username = $request->get('username');
		$name = $request->get('profileGroupname');
	    // dd($name);
		$f_nic = '';
	   $nic = '';
	   $b_nic = '';
	   $ntn = '';
	   $passport = '';
	   $overseas = '';
	   $uname = '';
	   $profileRate = '';
	   $resellerprofileRate = '';
			   ////verification allow
	   $checkDealeralow='';
		$checkalowverification='';
		$verification='';
		$alowdealer='';
	   $checkDealeralow = UserInfo::where('username',$username)->first();
	   $alowdealer = $checkDealeralow['dealerid'];
	   $alowsubdealer = $checkDealeralow['sub_dealer_id'];
	   if(empty($alowsubdealer)){

	   $checkalowverification = DealerProfileRate::where('dealerid',$alowdealer)->first();
   }else{

	   $checkalowverification = SubdealerProfileRate::where('sub_dealer_id',$alowsubdealer)->first();
   }
	   $verification = $checkalowverification['verify'];

	   if($verification == 'no'){
				   $f_nic = 'done';
				   $nic = 'done';
				   $b_nic = 'done';
				   $uname = $username;
				   $ntn ='done';
				   $passport = 'done';
				   $overseas = 'done';
	   }else{
			$isverify = UserVerification::where('username',$username)->select('username','cnic','nic_front','nic_back','ntn','intern_passport','overseas')->first();
			   
				   $f_nic = $isverify['nic_front'];
				   $nic = $isverify['cnic'];
				   $b_nic = $isverify['nic_back'];
				   $uname = $isverify['username'];
				   $ntn =$isverify['ntn'];
				   $passport = $isverify['intern_passport'];
				   $overseas = $isverify['overseas'];
	   }

	   ////
   
				
	   if(($username == $uname) && ($nic != '' || $ntn !='' || $overseas != '' || $passport != '' )&& ($f_nic != '' && $b_nic != '')){

		$Checkingexpiry = UserStatusInfo::where(['username'=>$username])->first();
		$expire = $Checkingexpiry['card_expire_on'];
		$cur_date=date('Y-m-d');
		if($expire > $cur_date){
			 session()->flash('error',' Your user Already charge.');
		   return redirect()->route('users.user.index1',['status' => 'user']);
		}else{
		$getGroup = Profile::where(['name'=> $name])->first();
		// dd($getGroup);
		$profileGroupname = $getGroup->groupname;

		
//.................................................................................................................//
	   //  New Card Charge code start from here.......
	   $checkIsSubdealerUser = UserInfo::where('username',$username)->select('username','dealerid','sub_dealer_id')->first();
	   $billingType = DealerProfileRate::where('dealerid',$checkIsSubdealerUser->dealerid)->select('billing_type')->first();
		if($checkIsSubdealerUser->sub_dealer_id != NULL && $billingType->billing_type == 'card'){
		//    $currentUser = Auth::user();
		$currentDealer = UserInfo::where(['username' => $username])->first();

		$rechargersubDealer = $currentDealer->sub_dealer_id;
		$rechargertrader = $currentDealer->trader_id;
		$rechargerDealer = $currentDealer->dealerid;

		// current logged in user
		$currentUser = UserInfo::where(['sub_dealer_id' => $rechargersubDealer , 'status' => 'subdealer'])->first(); 
		
		if(empty($currentUser)){
			$currentUser1 = UserInfo::where(['dealerid' => $rechargerDealer , 'status' => 'dealer'])->first();
			if(empty($currentUser1)){
				$currentUser = UserInfo::where(['trader_id' => $rechargertrader , 'status' => 'trader'])->first();
			}else{
				$currentUser = UserInfo::where(['dealerid' => $rechargerDealer , 'status' => 'dealer'])->first();
			}


		}
		   $check ='';
		$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
		$check = $freeze_account['freeze'];

		if(!empty($freeze_account)){
			if($check == 'yes'){
				return redirect()->route('users.dashboard');
			}
		} 

		   $card = Card::where('sub_dealer_id',$currentUser->sub_dealer_id)->where('status','unused')->where('name',$name)->first();
		   if($card != null){
		   $card_nos = $card->card_no;
		   $status = $card->status;
		   $m_rate = $card->m_rate;
		   $rate = $card->rate;
		   $dealerrate = $card->dealerrate;
		   $subdealerrate = $card->subdealerrate;
		   $s_acc_rate = $card->s_acc_rate;
		   $sst = $card->sst;
		   $adv_tax = $card->adv_tax;
		   $traderrate = $card->traderrate;
		   $t_acc_rate = $card->t_acc_rate;
		   $commision = $card->commision;
		   $m_acc_rate = $card->m_acc_rate;
		   $r_acc_rate = $card->r_acc_rate;
		   $d_acc_rate = $card->d_acc_rate;
		   $c_sst = $card->c_sst;
		   $c_adv = $card->c_adv;
		   $c_charges = $card->c_charges;
		   $c_rates = $card->c_rates;
		   $profit = $card->profit;
		   $profile = $card->profile;
		   $name = $card->name;
		   $taxname = $card->taxname;
		   $charge_on = date('Y-m-d H:i:s');
		   $trader_id = $card->trader_id;
		   $dealerid = $card->dealerid;
		   $resellerid = $card->resellerid;
		   $sub_dealer_id = $card->sub_dealer_id;
		   $manager_id = $card->manager_id;
		   $date = date('Y-m-d');
		   $billing_type = 'card';
		//    dd($profile);
			  // will recharge the user
			  $this->rechargeUser($request,$profile,$username,$currentUser);
				  //////////////////////
			  $username = $username;
			  $date =str_replace('-', '', date('Y-m-d'));
			  $userid = $username.$date;
				  
			  if($sst == 0){
				  $receipt = 0;
			  }else{
				  $receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
				  $num = $receipt_last->receipt_num;
				  $receipt = $num +1;
			  }
		   //    $profit = $profit;
			  $receipt1 = $currentUser->receipt;
			  $receipt_num = $receipt;
			  $charge_on = date('Y-m-d H:i:s');
			  $dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'groupname' => $profile])->first();
			  $dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
			//   dd($card_nos);
			//   AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,$receipt1,$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,$billing_type,$card_nos);
			 
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

			  $insertAmountBilling = new AmountBillingInvoice();
			  $insertAmountBilling->username = $username;
			  $insertAmountBilling->userid = $userid;
			  $insertAmountBilling->m_rate = $m_rate;
			  $insertAmountBilling->rate = $resellerdivide;
			  $insertAmountBilling->dealerrate = $divide;
			  $insertAmountBilling->subdealerrate = $dividesub;
			  $insertAmountBilling->s_acc_rate = $s_acc_rate;
			  $insertAmountBilling->sst = $sst;
			  $insertAmountBilling->adv_tax = $adv_tax;
			  $insertAmountBilling->traderrate = $traderrate;
			  $insertAmountBilling->t_acc_rate = $t_acc_rate;
			  $insertAmountBilling->commision = $commision;
			  $insertAmountBilling->m_acc_rate = $m_acc_rate;
			  $insertAmountBilling->r_acc_rate = $r_acc_rate;
			  $insertAmountBilling->d_acc_rate = $d_acc_rate;
           	  $insertAmountBilling->c_sst = $c_sst;
			  $insertAmountBilling->c_adv = $c_adv;
			  $insertAmountBilling->c_charges = $c_charges;
			  $insertAmountBilling->c_rates = $c_rates;
			  $insertAmountBilling->profit = $profit;
			  $insertAmountBilling->receipt = $receipt;
			  $insertAmountBilling->receipt_num = $receipt_num;
			  $insertAmountBilling->profile = $profile;
			  $insertAmountBilling->name = $name;
			  $insertAmountBilling->taxname = $taxname;
			  $insertAmountBilling->trader_id = $trader_id;
			  $insertAmountBilling->dealerid = $dealerid;
			  $insertAmountBilling->resellerid = $resellerid;
			  $insertAmountBilling->manager_id = $manager_id;
			  $insertAmountBilling->sub_dealer_id = $sub_dealer_id;
			  $insertAmountBilling->date = $date;
			  $insertAmountBilling->charge_on = $charge_on;
			  $insertAmountBilling->dasti_amount = $dasti_amount;
			  $insertAmountBilling->billing_type = 'card';
			  $insertAmountBilling->card_no = $card_nos;
			  $insertAmountBilling->save();
			   //update Card Table while card used
			   $card->status = 'used';
			   $card->save();
		   }else{
			   session()->flash('error',' No profile available.');
			   return redirect()->route('users.single',$checkDealeralow->id);

		   }
		  /////////////////////////
			  if(Auth::user()->status == "dealer"){
				  $activity = "view Account Recharged by".Auth::user()->dealerid;
			  }elseif(Auth::user()->status == "subdealer"){
				  $activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
			  }
			  else{
				  $activity = "view Account Recharged by".Auth::user()->trader_id;
			  }
			  $amountUserRechargeLog = new AmountUserRechargeLog();
			  $amountUserRechargeLog->dealerid = Auth::user()->dealerid;
			  $amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
			  $amountUserRechargeLog->trader_id = Auth::user()->trader_id;
			  $amountUserRechargeLog->username = $username;
			  $amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
			  $amountUserRechargeLog->activity = $activity;
			  // $amountUserRechargeLog->error =
			  $amountUserRechargeLog->ipaddress = $request->ip();
			  $amountUserRechargeLog->profile = $profile;
			  $amountUserRechargeLog->save();
			  // amount before recharge
			  $userAmountBillingBrg = new UserAmountBillingBrg();
			  $userAmountBillingBrg->billing_invoice_id = 1;
			  $userAmountBillingBrg->last_remaining_amount =NULL;
			  $userAmountBillingBrg->save();

			  session()->flash('success',' Successfully Recharged.');
			  return redirect()->route('users.user.index1',['status' => 'user']);
//.......................................................................................................................//
		}else{
			// dd($username);
		$currentDealer = UserInfo::where(['username' => $username])->first();

		$rechargersubDealer = $currentDealer->sub_dealer_id;
		$rechargertrader = $currentDealer->trader_id;
		$rechargerDealer = $currentDealer->dealerid;

		// current logged in user
		$currentUser = UserInfo::where(['sub_dealer_id' => $rechargersubDealer , 'status' => 'subdealer'])->first(); 
		
		if(empty($currentUser)){
			$currentUser1 = UserInfo::where(['dealerid' => $rechargerDealer , 'status' => 'dealer'])->first();
			if(empty($currentUser1)){
				$currentUser = UserInfo::where(['trader_id' => $rechargertrader , 'status' => 'trader'])->first();
			}else{
				$currentUser = UserInfo::where(['dealerid' => $rechargerDealer , 'status' => 'dealer'])->first();
			}


		}
		// dd($currentUser->username);
		   //
		//    $currentUser = UserInfo::where('username',$username)->first();
		// $currentUser = Auth::user();
		// 

		   $check ='';
		$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
		$check = $freeze_account['freeze'];

		if(!empty($freeze_account)){
			if($check == 'yes'){
				// dd($freeze_account);
				return redirect()->route('users.dashboard');
			}
		} 

		   $getsubdealerrate='';
		   $getdealerrate = '';
		   if($currentUser->status == "subdealer")
		   {
			
				$getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'groupname' => $profileGroupname])->first();
				 if(empty($getsubdealerrate))
		   {
			   return redirect()->route('users.dashboard');
		   }
		   }elseif($currentUser->status == "dealer"){
			   $getdealerrate = DealerProfileRate::where(['dealerid'=>Auth::user()->dealerid,'groupname' => $profileGroupname])->first();
  
				 if(empty($getdealerrate))
		   {
			   return redirect()->route('users.dashboard');
		   }
		   }elseif($currentUser->status == "trader"){
			   $getdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'groupname' => $profileGroupname])->first();
  
				 if(empty($getdealerrate))
		   {
			   return redirect()->route('users.dashboard');
		   }
		   }
  
		  // current user reseller
		  $currentUserResellerUserName = UserInfo::select('username')->where(['resellerid' => $currentUser->resellerid,'status' => 'reseller'])->first();
		  $resellerUserAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
		  $resellerAmount = $resellerUserAmount->amount;
		  ///
		  
		  // check user has sufficiant amount: user_amount
		  $userAmount = UserAmount::where('username', $currentUser->username)->first();
		  $amount = $userAmount->amount;
		  
		  if($currentUser->status == 'dealer'){
			  $dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
			  $resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
			  $m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
			  $profileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
			  $customertax = Profile::where(['groupname' => $profileGroupname])->first();
			  $taxgroup = $dealerprofileRate['taxgroup'];
			  $name = $customertax->name;
		  }elseif($currentUser->status == 'subdealer'){
			  $dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
			  $resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
				  $m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
			  $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
			  $customertax = Profile::where(['groupname' => $profileGroupname])->first();
			  $taxgroup = $profileRate['taxgroup'];
				  $name = $customertax->name;
		  }elseif($currentUser->status == 'trader'){
			  $dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'groupname' => $profileGroupname])->first();
			  $resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'groupname' => $profileGroupname])->first();
				  $m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'groupname' => $profileGroupname])->first();
			  $s_profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
			  $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'groupname'=>$profileGroupname])->first();
			  $customertax = Profile::where(['groupname' => $profileGroupname])->first();
			  $taxgroup = $profileRate['taxgroup'];
				  $name = $customertax->name;
		  }
		  
		  if($amount >= $profileRate['final_rates']){
			  // will recharge the user
			  $this->rechargeUser($request,$profileGroupname,$username,$currentUser);
  
			  
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
			  
			  
				  //////////////////////
			  $username = $username;
			  $date =str_replace('-', '', date('Y-m-d'));
			  $userid = $username.$date;
			  $m_rate = $m_profileRate['final_rates'];
			  $rate = $resellerprofileRate['final_rates'];
  
				  if($currentUser->status =="subdealer"){
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
				  
			  }elseif($currentUser->status =="dealer"){
			  $dealerrate = $dealerprofileRate['final_rates'];
			  $subdealerrate=0;
			  $s_acc_rate = 0;
			  $traderrate = 0;
			  
			  $t_acc_rate = 0;
  
			  $sst = $dealerprofileRate->sst;
			  $adv_tax = $dealerprofileRate->adv_tax;
			  $c_sst = $sst;
			  $c_adv = $adv_tax;
			  $c_charges = $dealerprofileRate['consumer'];
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
		  
	  
			  if($dealerprofileRate->sst == 0){
				  $receipt = 0;
			  }else{
				  $receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
				  $num = $receipt_last->receipt_num;
  
				  $receipt = $num +1;
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
			  $receipt1 = $currentUser->receipt;
			  $receipt_num = $receipt;
			  $profile = $profileGroupname;
			  $name = $name;
			  $taxname = $taxname;
			  $charge_on = date('Y-m-d H:i:s');
			  $datahandle = UserInfo::where('username',$username)->first();
			  $trader_id = $datahandle->trader_id;
			  $sub_dealer_id = $datahandle->sub_dealer_id;
			  $dealerid = $datahandle->dealerid;
			  $resellerid = $datahandle->resellerid;
			  $manager_id = $datahandle->manager_id;
			  $date = date('Y-m-d');

			  $dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'groupname' => $profileGroupname])->first();
			  $dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
			  
			//   AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,$receipt1,$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,'amount',Null);

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

			  $insertAmountBilling = new AmountBillingInvoice();
			  $insertAmountBilling->username = $username;
			  $insertAmountBilling->userid = $userid;
			  $insertAmountBilling->m_rate = $m_rate;
			  $insertAmountBilling->rate = $resellerdivide;
			  $insertAmountBilling->dealerrate = $divide;
			  $insertAmountBilling->subdealerrate = $dividesub;
			  $insertAmountBilling->s_acc_rate = $s_acc_rate;
			  $insertAmountBilling->sst = $sst;
			  $insertAmountBilling->adv_tax = $adv_tax;
			  $insertAmountBilling->traderrate = $traderrate;
			  $insertAmountBilling->t_acc_rate = $t_acc_rate;
			  $insertAmountBilling->commision = $commision;
			  $insertAmountBilling->m_acc_rate = $m_acc_rate;
			  $insertAmountBilling->r_acc_rate = $r_acc_rate;
			  $insertAmountBilling->d_acc_rate = $d_acc_rate;
           	  $insertAmountBilling->c_sst = $c_sst;
			  $insertAmountBilling->c_adv = $c_adv;
			  $insertAmountBilling->c_charges = $c_charges;
			  $insertAmountBilling->c_rates = $c_rates;
			  $insertAmountBilling->profit = $profit;
			  $insertAmountBilling->receipt = $receipt;
			  $insertAmountBilling->receipt_num = $receipt_num;
			  $insertAmountBilling->profile = $profile;
			  $insertAmountBilling->name = $name;
			  $insertAmountBilling->taxname = $taxname;
			  $insertAmountBilling->trader_id = $trader_id;
			  $insertAmountBilling->dealerid = $dealerid;
			  $insertAmountBilling->resellerid = $resellerid;
			  $insertAmountBilling->manager_id = $manager_id;
			  $insertAmountBilling->sub_dealer_id = $sub_dealer_id;
			  $insertAmountBilling->date = $date;
			  $insertAmountBilling->charge_on = $charge_on;
			  $insertAmountBilling->dasti_amount = $dasti_amount;
			  $insertAmountBilling->billing_type = 'amount';
			  $insertAmountBilling->card_no = NULL;
			  $insertAmountBilling->save();
		  /////////////////////////
			// in last
			$userAmount->amount -= $profileRate->final_rates;
			$userAmount->save();
			  
			  if(Auth::user()->status == "dealer"){
				  $activity = "view Account Recharged by".Auth::user()->dealerid;
			  }elseif(Auth::user()->status == "subdealer"){
				  $activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
			  }
			  else{
				  $activity = "view Account Recharged by".Auth::user()->trader_id;
			  }
  
			  $amountUserRechargeLog = new AmountUserRechargeLog();
			  $amountUserRechargeLog->dealerid = Auth::user()->dealerid;
			  $amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
			  $amountUserRechargeLog->trader_id = Auth::user()->trader_id;
			  $amountUserRechargeLog->username = $username;
			  $amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
  
			  $amountUserRechargeLog->activity = $activity;
			  // $amountUserRechargeLog->error =
			  $amountUserRechargeLog->ipaddress = $request->ip();
			  $amountUserRechargeLog->profile = $profileGroupname;
			  $amountUserRechargeLog->save();
  
  
			  // amount before recharge
			  $userAmountBillingBrg = new UserAmountBillingBrg();
			  $userAmountBillingBrg->billing_invoice_id = 1;
			  $userAmountBillingBrg->last_remaining_amount =$amount;
			  $userAmountBillingBrg->save();
			  
			  
			  
			  
			  session()->flash('success',' Successfully Recharged.');
			  return redirect()->route('users.user.index1',['status' => 'user']);
		  }else{
			  session()->flash('error',' You have insufficient amount.');
			  return redirect()->route('users.user.index1',['status' => 'user']);
		  }
		}


	   //  New Card Charge code End here.......
		

		
	   }
   }else{
	   session()->flash('success',' Please Verify Your Cnic then  Recharged.');
		   return redirect()->route('users.user.index1',['status' => 'user']);
   }
	   
		
	}




	// recharge method_exist
	public function rechargeUser(Request $request,$profileGroupName,$username,$currentUser){
		// calulation for exepiry date[currentDay-1 of next month]
		$days=date('t');
		$days=$days-1;
		// $exp_date=date('Y-m-d', strtotime("+".$days." days"));
		$cur_date=date('Y-m-d');
		///


		$cur_date=date('Y-m-d');


		// $expiry='2019-07-03';


// $charge = strtotime('11:58:59');
$charge = strtotime(date('H:i:s'));


if($charge > strtotime('11:59:59') ){
	// echo 'From Current Date <br>';
	$time=strtotime($cur_date);
	$exp_date = date("Y-m-d", strtotime("+1 month", $time));
	// echo 'Next Expiry is '.$final;

}else{
	// echo 'From Previous Date <br>';
	$time=strtotime($cur_date);
	$final = strtotime(date("Y-m-d", strtotime("+1 month", $time)));
	$exp_date = date("Y-m-d", strtotime("-1 day", $final));
	// echo 'Next Expiry is '.$final;

}
	

		/*** User Usual IP: get ip where status is 0: un assigned IP(s)  ****/
		
		// radreply: check enter or not
		$radReply = Radreply::where('username',$username)->first();
		$userIpStatus = UserIPStatus::where('username',$username)->first();
		$userUsualIp = UserUsualIP::where(['status' => '0'])->first();
		if($radReply){
			// dont need to change any thing
			
			// user_ip_status
			//$userIpStatus = UserIPStatus::where('username',$username)->first();
			// ip will not null in user_ip_status
			
			
		}else{
			// user_ip_status
			
			// if user has not entery in user ip status
			if(!$userIpStatus){
				$userIpStatus = new UserIPStatus();
				$userIpStatus->username = $username;
			}
			
			// ip will be null: take new ip from UserUsualIP: update userUsualIP and new entry of user in rad reply with new ip
			 // getting unasigned ip
			$userUsualIp->status = 1; // lock the IP: assigned
			
			$ip = $userUsualIp->ip;
			
			//saving/updating userIpStatus with new IP
			$userIpStatus->ip = $ip;
			$userIpStatus->type = 'usual_ip';
			$userIpStatus->save();
			//
			
			// new entry in radreply
			$radReply = new Radreply();
			$radReply->username = $username;
			$radReply->attribute = 'Framed-IP-Address';
			$radReply->op = '=';
			$radReply->value = $ip;
			if($currentUser->status == 'dealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
			}elseif($currentUser->status == 'subdealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
			}else{
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
				$radReply->trader_id = $currentUser->trader_id;
			}
			//
		}

		
		
		/********/
		
		/*** updating data in RaduserGroup  ****/
			if($currentUser->status == 'dealer'){
				$serverType = AssignNasType::where('dealerid',$currentUser->dealerid)->first();
			}elseif($currentUser->status == 'subdealer'){
				$serverType = AssignNasType::where('sub_dealer_id',$currentUser->sub_dealer_id)->first();				
			}else{
				$serverType = AssignNasType::where('trader_id',$currentUser->trader_id)->first();				
			}
			switch($serverType->nas_type){
				case "juniper": $groupname = 'BE'; break;
				case "mikrotik": $groupname = 'MT'; break;
				default : {
						$groupname = 'BE';
					}
			}
			$changeprofile ='';
			$userdata = Profile::where('groupname',$profileGroupName)->first();
			$name = $userdata['name'];
			$code = $userdata['code'];
			$userdata->save();

			/// if you want to enable fup then just uncomment this line
			// $userdata1 = UserInfo::where('username',$username)->first();
			// $changeprofile = $userdata1['taxprofile'];
			// $fupdata = DealerFUP::where('dealerid',$currentUser->dealerid)->where('groupname',$profileGroupName)->first();
			// $fup = $fupdata['datalimit'];
			// $userdata1->qt_total = $fup;
			$code = $code != 'cyber' ? '-'.$code : '';
			$profileGroupName = $groupname.'-'.$profileGroupName.'k'.$code;
			
			// $profileGroupName = $groupname.'-'.$profileGroupName.'k';
			
			$raduserGroup = RaduserGroup::where('username' , $username)->first();
			$raduserGroup->groupname = $profileGroupName;
			$raduserGroup->name = $name;
		/******/
		
		//updating status info
		$userStatusInfo =  UserStatusInfo::where('username' , $username)->first();
		$userStatusInfo->card_charge_on = $cur_date;
		$userStatusInfo->card_expire_on = $exp_date;
		$userStatusInfo->expire_datetime = $exp_date.' 12:00:00';
		$userStatusInfo->card_charge_by = $currentUser->username; // current login user
		$userStatusInfo->card_charge_by_ip = $request->ip();

		//updateting expired user
		$expire_user = ExpireUser::where('username' , $username)->first();
		$expire_user->status = "charge";
		$expire_user->last_update = date('Y-m-d H:i:s');
		DB::transaction(function() use ($userIpStatus, $radReply,$raduserGroup,$userUsualIp,$userStatusInfo,$expire_user) {
    	   $radReply->save();
    	   $userIpStatus->save();
    	   $raduserGroup->save();
    	   $userUsualIp->save();
    	   $userStatusInfo->save();
    	   $expire_user->save();
    /*
     * insert new record for question category
     */
			});
		// IF CIR Profile Assign Do This....... 
		$cirUser = Cirprofile::where('username',$username)->first();
		if(!empty($cirUser) && $cirUser->status == 1){
			 //update profile radius
			 $updateProfile = RaduserGroup::where('username',$username);
			 $updateProfile->update([
				 'groupname' => $profileGroupName.'-p',
				 'name' => 'pure-'.$name
			 ]);
		}
		////
		$url='http://192.168.100.99/api/index.php?username='.$username;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
	}
	/// ajax calls
	public function profileWiseUsers (Request $request){
		$profile = $request->get('profileGroupName');
		$profileGroupName = Profile::where('name',$profile)->first()->groupname;
		// dd($profileGroupName);
		$currentUser = Auth::user();
	//	return $currentUser;
		if($currentUser->status == 'dealer'){
			return $currentUser->dealer_profile_wise_expired_users($profileGroupName)->get();
		}elseif($currentUser->status == 'subdealer'){
			return $currentUser->sub_dealer_profile_wise_expired_users($profileGroupName)->get();			
		}elseif($currentUser->status == 'trader'){
			return $currentUser->trader_profile_wise_expired_users($profileGroupName)->get();			
		}
	}

	
}
