<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;
use App\model\Users\PaymentsTransactions;
use App\model\Users\AmountTransactions;
use App\model\admin\Admin;
use App\model\Users\RadAcct;
use App\model\Users\UserBill;
use App\model\Users\Profile;
use App\model\Users\LedgerReport;
use Illuminate\Support\Facades\Session;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\StaticIp;
use PDF;
use App\model\Users\userAccess;
use App\MyFunctions;
use App\model\Users\UserAmount;



class LedgerReportController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(){
	$id = Auth::user()->id;
	$userId = 'user'.$id;
	$pModule = array();
	$cModule = array();
	$state = array();
	$userId = 'user'.Auth::user()->id;
	if(Auth::user()->status =='inhouse'){


		// $loadData = userAccess::select($userId,'parentModule','childModule')->get();
		// if(!empty($loadData)){
		// 	foreach ($loadData as  $value) {
		// 		$state[] = $value[$userId];
		// 		$pModule[] = $value['parentModule'];
		// 		$cModule[] = $value['childModule'];
		// 	}

		// }
		// if($state[29] == 0 && $cModule[29] == 'Ledger Report'){
		// 	return redirect()->route('users.dashboard');
		// }

		if(!MyFunctions::check_access('Ledger Report',Auth::user()->id)){
			return 'Permission Denied';
		}

	}

	$status = Auth::user()->status;
	if($status == "reseller"){
		$userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
	}elseif($status == "dealer"){
		$userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
	}elseif($status == "manager"){
		$userCollection = UserInfo::select('username')->where(['status' => 'reseller','manager_id' =>Auth::user()->manager_id])->get();
	}elseif($status == 'inhouse'){
		$userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
	}
	return view('users.billing.ledger_report',[
		'userCollection' => $userCollection
	]);


}


public function pdf(Request $request)
{
	$username = $request->get('username');
	$date = $request->get('date');
	$range = explode('-', $date);
	$from1 = $range[0];
	$to1 = $range[1];

	$from1=date('Y-m-d' ,strtotime($from1));
	$openingdate = date("Y-m-d", strtotime("-1 day", strtotime($from1)));
	$to1=date('Y-m-d' ,strtotime($to1));

	$status = Auth::user()->status;
	if($status == "reseller" || $status == 'inhouse'){

		if($username == "All"){
			$data = UserInfo::select('username')->where('status','=','dealer')->where('resellerid','=',Auth::user()->resellerid)->get();
			$pdf= PDF::loadView('users.billing.ledge_PDF2',[
				'data' => $data,
				'fromDate' => $from1,
				'toDate' => $to1
			]);
			return $pdf->stream($username.'.pdf');
		}else{
			$data = UserInfo::Where(['username' => $username])->first();
			$sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');
			$sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');
			$paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->orderBy('date','asc')->get();
		}
	}elseif($status == "dealer")
	{
		$data = UserInfo::Where(['username' => $username])->first();
		$sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');
		$sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');
		$paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->orderBy('date','asc')->get();
	}elseif($status == "manager")
	{
		$data = UserInfo::Where(['username' => $username])->first();
		$sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');
		$sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');
		$paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->orderBy('date','asc')->get();
	}
	$pdf= PDF::loadView('users.billing.ledge_PDF',[
		'data' => $data,
		'paymentTranssaction' => $paymentTranssaction,
		'sumCredit' => $sumCredit,
		'sumDebit' => $sumDebit,
		'from1' => $from1,
		'to1' =>$to1
	]);
	return $pdf->stream('ledgerReport-'.$username.'-'.date('Y-m-d H:i:s').'.pdf');
	// exit();
}
public function transferpdf(Request $request)
{
	$username = $request->get('username');
	$date = $request->get('datetimes');
	$range = explode('-', $date);
	$from1 = $range[0];
	$to1 = $range[1];

	$from1=date('Y-m-d H:i:s' ,strtotime($from1));
	$openingdate = date("Y-m-d", strtotime("-1 day", strtotime($from1)));
	$to1=date('Y-m-d H:i:s' ,strtotime($to1));

	$status = Auth::user()->status;
	if($status == "reseller" || $status == 'inhouse'){

		if($username == "All"){
			$data = UserInfo::select('username')->where('status','=','dealer')->where('resellerid','=',Auth::user()->resellerid)->get();

			$pdf= PDF::loadView('users.billing.ledge_PDF2',[
				'data' => $data,
				
				
				
				
			]);


			return $pdf->stream($username.'.pdf');


		}else{
			$data = UserInfo::Where(['username' => $username])->first();

			$sumCredit = AmountTransactions::where('receiver','=',$username)->where('date','<',DATE($from1))->sum('cash_amount');

			$sumDebit = AmountTransactions::where('receiver','=',$username)->where('date','<',DATE($from1))->sum('amount');

			$paymentTranssaction = AmountTransactions::where('receiver','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->orderBy('date','asc')->get();
		}

		


	}elseif($status == "dealer")
	{
		$data = UserInfo::Where(['username' => $username])->first();

		$sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');

		$sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');

		$paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->orderBy('date','asc')->get();
	}

	$pdf= PDF::loadView('users.billing.transfer_PDF',[
		'data' => $data,
		'paymentTranssaction' => $paymentTranssaction,
		'sumCredit' => $sumCredit,
		'sumDebit' => $sumDebit,
		'from1' => $from1,
		'to1' =>$to1
		
		
	]);


	return $pdf->stream($username.'.pdf');

}



public function billingpdf(Request $request)
{
	// billing summary report
	$bildate = $request->get('date');
	$status = Auth::user()->status;
	if(!empty($request->get('own'))){
		$username = Auth::user()->username;
	}else if(!empty($request->get('trader_data'))){
		$username = $request->get('trader_data');
	}else if(!empty($request->get('dealer_data'))){
		$username = $request->get('dealer_data');
	}else if(!empty($request->get('reseller_data'))){
		$username = $request->get('reseller_data');
	}else{
		return 'Please select user first';
	}
	//
	// $bildate = '2023-08-10';
	$ebd=explode('-', $bildate);
	if($ebd[2] == 10){
		$from = date("Y-m-25 12:00:00", strtotime("-1 months", strtotime($bildate)));
		$to = date("Y-m-10 12:00:00", strtotime($bildate));
		$dueDate= date("Y-m-15");
	}else{
		$from = date("Y-m-10 12:00:00", strtotime($bildate));
		$to = date("Y-m-25 12:00:00", strtotime($bildate));
		$dueDate= date("Y-m-30");
	}
	//
	//
	$rows = array();
	$count = array();
	$total = 0;
        //
	$selectedUser = UserInfo::where('username', $username)->first();
        //
	if ($selectedUser->status == 'manager'){
		$monthlyBillingEntries = AmountBillingInvoice::where('manager_id', $selectedUser->manager_id)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'reseller'){
		$monthlyBillingEntries = AmountBillingInvoice::where('resellerid', $selectedUser->resellerid)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'dealer'){
		$monthlyBillingEntries = AmountBillingInvoice::where('dealerid', $selectedUser->dealerid)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'subdealer'){
		$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id', $selectedUser->sub_dealer_id)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}
	//
	foreach($monthlyBillingEntries as $key => $entry) {
		//
		if($entry['company_rate'] == 'yes'){

			if($selectedUser->status == 'manager'){
				$wallet = $entry['m_rate'];
			}else if($selectedUser->status == 'reseller'){
				$wallet = $entry['r_rate'];
			}else if($selectedUser->status == 'dealer'){
				// $wallet = $entry['dealerrate'];
				if(empty($entry['sub_dealer_id'])){
					$wallet = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
				}else{
					// $wallet = $entry['dealer_gross_amount'] + $entry['sst'] + $entry['adv_tax'];
					$wallet = $entry['wallet_deduction'] - $entry['margin'];
				}
			}else if($selectedUser->status == 'subdealer'){
				// $wallet = $entry['subdealerrate'];
					$wallet = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
			}

		}else{

			if($selectedUser->status == 'manager'){
				$wallet = $entry['m_acc_rate'];
			}else if($selectedUser->status == 'reseller'){
				$wallet = $entry['r_acc_rate'];
			}else if($selectedUser->status == 'dealer'){
				$wallet = ($entry['d_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2;
			}else if($selectedUser->status == 'subdealer'){
				$wallet = ($entry['s_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2; 
			}
		//
		}
			$rows[$key] = $entry['name'];
			$count[$entry['name']]['amount'][$key] = $wallet;
			$total = $total + $wallet;

	}
	//
	$staticIp = DB::table('static_ips_bill')->where('date',$bildate)->where('username',$username)->first();
	//
	$pdf= PDF::loadView('users.billing.billingReport_PDF',[
		'user_data' => $selectedUser,
		'bildate' => $bildate,
		'dueDate' =>$dueDate,
		'rows' => array_unique($rows),
		'count' => $count,
		'total' => $total,
		'staticIp' => $staticIp,
	]);
	return $pdf->stream('BillingSummary-'.$username.'-'.date('Y-m-d H:i:s').'.pdf');
	// 
	}

	public function invoicepdf(Request $request)
	{

				// 
		$username = $request->get('username');
		$bildate = $request->get('date');
		$status = Auth::user()->status;


		$ebd=explode('-', $bildate);
		if($ebd[2] == 10){
			$lastbilldate = date("Y-m-25", strtotime("-1 months", strtotime($bildate)));
			$dueDate= date("Y-m-15");
		}else{

			$lastbilldate = date("Y-m-10", strtotime($bildate));
			$dueDate= date("Y-m-30");
		}

		if($bildate == '2019-04-25'){
			$lastbilldate = date('2019-04-01');
//     $start=date('2019-04-01');
// $end=date('2019-04-25');
		}

		if($status == "reseller" || $status == 'inhouse')
		{

			$user_data = UserInfo::where([ 'username' => $username])->first();
			$dealerid = $user_data->dealerid;
			$sub_dealer_id = '';

			$details = AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
				DATE($lastbilldate.' 12:00:00'))->select('name','dealerrate','d_acc_rate','c_rates','sst','adv_tax','profile')->distinct('name')->get();
			if($ebd[2] == 10){


				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}





		}


						//return $total;



		$pdf= PDF::loadView('users.billing.invoiceReport_PDF',[
			'user_data' => $user_data,
			'details' => $details,
			'bildate' => $bildate,
			'lastbilldate' => $lastbilldate,
			'dealerid' =>$dealerid,
			'sub_dealer_id' => $sub_dealer_id,
			'dueDate' =>$dueDate,
			'num_ips' => $num_ips,
			'ip_rate' => $ip_rate,
			'totalIPamount' => $totalIPamount,
		]);

		return $pdf->stream($username.'.pdf');




	}

	public function customerBill($username,$date)
	{

		$sub_dealer_id = Auth::user()->sub_dealer_id;
		$dealerid = Auth::user()->dealerid;


		if(Auth::user()->status == "dealer"){
			$checkUser = UserInfo::where(['username'=> $username,'dealerid'=>$dealerid])->first();	
			$checkdealer = UserInfo::where(['username' => Auth::user()->username])->first();
	 // $checkReceipt = $checkdealer->receipt;
			if($checkUser){

				$dealername = Auth::user()->firstname;
				$dealernum = Auth::user()->mobilephone;

				$profile = $checkUser->profile;
				$profile = str_replace('BE-', '', $profile);
				$profile = str_replace('k', '', $profile);
				$groupname = Profile::where(['groupname'=>$profile])->first();

				$customerCheck =AmountBillingInvoice::where(['username' => $username, 'date'=>$date])->first();
				$punchtax = $customerCheck->sst;
				$receipt_num = $customerCheck->receipt_num;
				$checkReceipt = $customerCheck->receipt;

			}else{
				return redirect()->route('users.dashboard');
			}
		}else{
			$checkUser = UserInfo::where(['username'=> $username,'sub_dealer_id'=>$sub_dealer_id])->first();

			$checkdealer = UserInfo::where(['username' => Auth::user()->username])->first();
	 // $checkReceipt = $checkdealer->receipt;
			if($checkUser){
				$dealername = Auth::user()->firstname;
				$dealernum = Auth::user()->mobilephone;

				$profile = $checkUser->profile;
				$profile = str_replace('BE-', '', $profile);
				$profile = str_replace('k', '', $profile);
				$groupname = Profile::where(['groupname'=>$profile])->first();

				$customerCheck =AmountBillingInvoice::where(['username' => $username, 'date'=>$date])->first();
				$punchtax = $customerCheck->sst;
				$receipt_num = $customerCheck->receipt_num;
				$checkReceipt = $customerCheck->receipt;
			}else{
				return redirect()->route('users.dashboard');
			}
		}

		if(empty($checkUser)){
			return redirect()->route('users.dashboard');
		}




		if($punchtax == 0){
			$sst = 0;
			$adv_tax = 0;
			$charges = 0;
			$invoice = 0;
			$fn = '';
			$ln = '';
			$add = '';
			$nic = '';
			$mobilephone = '';
			$name = '';


			$charge_date = '';


		}else {
			$sst = $customerCheck->c_sst;
			$adv_tax = $customerCheck->c_adv;
			$charges = $customerCheck->c_charges;
			$invoice = $customerCheck->c_rates;
			$fn = $checkUser->firstname;
			$ln = $checkUser->lastname;
			$add = $checkUser->address;
			$nic = $checkUser->nic;
			$mobilephone = $checkUser->mobilephone;
			$name = $customerCheck->name;


			$charge_date = $customerCheck->date;
		}

		if($checkReceipt == "logon"){
			$pdf= PDF::loadView('users.billing.customer_bill_PDF',[
				'fn' => $fn,
				'ln' => $ln,
				'add' => $add,
				'nic' => $nic,
				'mobilephone' => $mobilephone,
				'username' => $username,
				'sst' => $sst,
				'adv_tax' => $adv_tax,
				'name' => $name,

				'charges' => $charges,
				'invoice' => $invoice,
				'charge_date' => $charge_date,
				'dealername' => $dealername,
				'dealernum' => $dealernum,
				'customerCheck' => $customerCheck,
				'receipt_num'  => $receipt_num


			]);
		}else{
			$pdf= PDF::loadView('users.billing.cyber_customer_bill_PDF',[
				'fn' => $fn,
				'ln' => $ln,
				'add' => $add,
				'nic' => $nic,
				'mobilephone' => $mobilephone,
				'username' => $username,
				'sst' => $sst,
				'adv_tax' => $adv_tax,
				'name' => $name,

				'charges' => $charges,
				'invoice' => $invoice,
				'charge_date' => $charge_date,
				'dealername' => $dealername,
				'dealernum' => $dealernum,
				'customerCheck' => $customerCheck,
				'receipt_num'  => $receipt_num


			]);
		}


		$pdf->setPaper('a4', 'landscape')->setWarnings(false)->output();




		return $pdf->stream('.pdf');

	}



	public function reportSummerypdf1(Request $request)
	{

						 // 
		$username = $request->get('username');
		$bildate = $request->get('date');
		$range = explode('-', $bildate);
		$from1 = $range[0];
		$to1 = $range[1];

		$from1=date('Y-m-d' ,strtotime($from1));

		$to1=date('Y-m-d' ,strtotime($to1));
		$status = Auth::user()->status;

		$ebd =0;



		if($status == "manager"){
			$total = array();
			$user_data = UserInfo::where([ 'username' => $username])->first();
			$resellerid = $user_data->resellerid;
			$monthlyBillingEntries = ResellerProfileRate::where(['resellerid' => $resellerid])->orderBy('groupname')->get();
			$monthlyBillingEntries1 = ManagerProfileRate::where(['manager_id' => Auth::user()->manager_id])->orderBy('groupname')->get();

	 //static ip

			if($ebd == 10){


				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}

			foreach($monthlyBillingEntries as $data){
				$groupname = $data->groupname;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('resellerid' , $resellerid)
				->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->where('profile','=',$groupname)->count();

				$total[] = $rate*$lite;



			}
			$total_amount = AmountBillingInvoice::where('resellerid' , $resellerid)
			->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->sum('r_acc_rate');

			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
			$userCollection1 = UserInfo::where('status', 'reseller')->where('resellerid','=',$resellerid)->first();

		}elseif($status == "reseller"){
			$total = array();
			$user_data = UserInfo::where([ 'username' => $username])->first();
			$dealerid = $user_data->dealerid;
			$monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->get();
			$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

	//static ip

			if($ebd[2] == 10){

				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}






			foreach($monthlyBillingEntries as $data){
				$groupname = $data->name;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('dealerid' , $dealerid)
				->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->where('name','=',$groupname)->count();

				$total[] = $lite;



			}
						// return $total;
			$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->sum('d_acc_rate');
			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
			$userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();
		}elseif($status == "dealer"){

			$selectUser = $request->get('username');
			if($selectUser == "own"){
				$selectUserData =  UserInfo::where([ 'username' => Auth::user()->username,'status' => 'dealer'])->first();

				$username = $selectUserData->username;
		///static ip

			//static ip

				if($bildate == date("Y-m-10")){

					$static_function = $this->staticIp($username);
					$static_function = explode("^", $static_function);



					$totalIPamount = $static_function[0];
					$ip_rate = $static_function[1];
					$num_ips = $static_function[2];

				}else{
					$totalIPamount = 0;
					$ip_rate = 0;
					$num_ips = 0;
				}
				$total = array();

				$dealerid = $selectUserData->dealerid;
				$monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
				$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

				foreach($monthlyBillingEntries as $data){
					$groupname = $data->groupname;
					$rate = $data->rate;


					$lite = AmountBillingInvoice::where('dealerid' , $dealerid)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
						DATE($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

					$total[] = $lite;



				}


						// return $total;
				$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
				->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
					DATE($lastbilldate.' 12:00:00'))->sum('dealerrate');

				$final_amount = $totalIPamount + $total_amount;

				$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
				$userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();




			}else{



							/////subdealer work

			//static ip

				if($bildate == date("Y-m-10")){

					$static_function = $this->staticIp($username);
					$static_function = explode("^", $static_function);



					$totalIPamount = $static_function[0];
					$ip_rate = $static_function[1];
					$num_ips = $static_function[2];

				}else{
					$totalIPamount = 0;
					$ip_rate = 0;
					$num_ips = 0;
				}

				$total = array();
				$user_data = UserInfo::where([ 'username' => $username,'dealerid' => Auth::user()->dealerid])->first();
				$sub_dealer_id = $user_data->sub_dealer_id;
				$monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
				$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

				foreach($monthlyBillingEntries as $data){
					$groupname = $data->groupname;
					$rate = $data->rate;


					$lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
					->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
						DATE($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

					$total[] = $lite;



				}
						// return $total;
				$total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
				->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
					DATE($lastbilldate.' 12:00:00'))->sum('subdealerrate');

				$final_amount = $totalIPamount + $total_amount;

				$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
				$userCollection1 = UserInfo::where('status', 'subdealer')->where('sub_dealer_id','=',$sub_dealer_id)->first();

			}

		}elseif($status == "subdealer"){


			$total = array();
			$user_data = UserInfo::where([ 'username' => $username,'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
			$sub_dealer_id = $user_data->sub_dealer_id;
			$monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
			$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

	//static ip

			if($bildate == date("Y-m-10")){

				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}

			foreach($monthlyBillingEntries as $data){
				$groupname = $data->groupname;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
				->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
					DATE($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

				$total[] = $lite;



			}
						// return $total;
			$total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
			->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
				DATE($lastbilldate.' 12:00:00'))->sum('subdealerrate');

			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','sub_dealer_id' => Auth::user()->sub_dealer_id ])->get();
			$userCollection1 = UserInfo::where('username', $username)->where('sub_dealer_id','=',$sub_dealer_id)->first();

		}


						//return $total;



		$pdf= PDF::loadView('users.billing.summeryReport_PDF',[
			'isSearched' => true,
			'total' => $total,
			'userCollection' => $userCollection,
			'total_amount' => $total_amount,
			'monthlyBillingEntries' => $monthlyBillingEntries,
			'userCollection1' => $userCollection1,
			'num_ips' => $num_ips,
			'ip_rate' => $ip_rate,
			'totalIPamount' => $totalIPamount,
			'final_amount' => $final_amount,
			'from1'=> $from1,

			'to1' => $to1


		]);

		return $pdf->stream($username.'.pdf');





	}

////////



	public function dealerSummerypdf1(Request $request)
	{
		/// Contractor Trader Margin Summary

		$whereArray = array();
        // //
		$trader = $request->get('trader_data');
		$dealer = $request->get('dealer_data');
		$reseller = $request->get('reseller_data');
		$manager = Auth::user()->manager_id;
        //
		if($trader != 'all'){
			array_push($whereArray,array('sub_dealer_id' , $trader));
			$username = $trader;
		}else if($dealer != 'all'){
			array_push($whereArray,array('dealerid' , $dealer));
			$username = $dealer;
		}else if($reseller != 'all'){
			array_push($whereArray,array('resellerid' , $reseller));
			$username = $reseller;
		}else if($reseller == 'all' && $dealer == 'all' && $trader == 'all'){
			array_push($whereArray,array('manager_id' , $manager));
			$username = 'manager';
		}
        //
		$rows = array();
		$row = array();
		$total = 0;
		$netProfitMargin = 0;
		$date = $request->get('date');
		$count = array();
		//
		$ebd=explode('-', $date);
		if($ebd[2] == 10){
			$from = date("Y-m-25 12:00:00", strtotime("-1 months", strtotime($date)));
			$to = date("Y-m-10 12:00:00", strtotime($date));
			$dueDate= date("Y-m-15");
		}else{
			$from = date("Y-m-10 12:00:00", strtotime($date));
			$to = date("Y-m-25 12:00:00", strtotime($date));
			$dueDate= date("Y-m-30");
		}
        //
        //
		$selectedUser = UserInfo::where('username', $username)->first();
        //
		$monthlyBillingEntries = AmountBillingInvoice::where($whereArray)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       // 
    //
		foreach($monthlyBillingEntries as $key => $entry) {

			$rows[$key]['username'] = $entry->username;
			$rows[$key]['name'] = $entry['name'];
			$rows[$key]['resellerid'] = $entry->resellerid;
			$rows[$key]['dealerid'] = $entry->dealerid;
			$rows[$key]['sub_dealer_id'] = $entry->sub_dealer_id;
			$rows[$key]['creationDate'] = $entry->user_info->creationdate;
			$rows[$key]['chargeOn'] = $entry->charge_on;
			$rows[$key]['profileAmount'] = $entry->user_info['profile_amount'];
			$rows[$key]['rate'] = $entry['rate'];
			$rows[$key]['filerTax'] = $entry['filer_tax'];
			$rows[$key]['date'] = $entry['date'];
			$rows[$key]['static_ip_amount'] = $entry['static_ip_amount'];
			$rows[$key]['sst'] = $entry['sst'];
			$rows[$key]['adv_tax'] = $entry['adv_tax'];
        //
			if($entry['company_rate'] == 'yes'){
            //
				$rows[$key]['manager_rate'] = $entry['m_rate'];
				$rows[$key]['reseller_rate'] = $entry['r_rate'];
           // $rows[$key]['dealer_rate'] = $entry['dealerrate'];
				if(empty($entry['sub_dealer_id'])){
					$rows[$key]['dealer_rate'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
				}else{
					$rows[$key]['dealer_rate'] = $entry['dealer_gross_amount'] + $entry['sst'] + $entry['adv_tax'];
				}
            //
           // $rows[$key]['subdealer_rate'] = $entry['subdealerrate'];
				$rows[$key]['subdealer_rate'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
             //
			}else{
            //
				$rows[$key]['manager_rate'] = $entry['m_acc_rate'];
				$rows[$key]['reseller_rate'] = $entry['r_acc_rate'];
				$rows[$key]['dealer_rate'] = ($entry['d_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2;
				$rows[$key]['subdealer_rate'] = ($entry['s_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2; 
        //
			}

			$rows[$key]['profitMargin'] = $entry['margin'];
    // }
			$netProfitMargin = $netProfitMargin + $rows[$key]['profitMargin'];
			//
			$row[$key] = $entry['name'];
			$count[$entry['name']]['amount'][$key] = $entry['margin'];
			$total = $total + $entry['margin'];

		}
    	//
		if(!empty($request->get('download')))
		{
			$this->billingSummary_csv($rows,$netPayable);
		}
    	//
		$pdf= PDF::loadView('users.billing.dealersummeryReport_PDF',[
			'isSearched' => true,
			'user_data' => $selectedUser,
			'monthlyBillingEntries' => $monthlyBillingEntries,
			'rows' => $rows,
			'netProfitMargin' => $netProfitMargin,
			'bildate' => $date,
			'dueDate' => $dueDate,
			'rows' => array_unique($row),
			'count' => $count,
			'total' => $total,

		]);
		return $pdf->stream($username.'.pdf');

	}

public function staticIp($username)
{
	$staticip = StaticIp::where(['username'=> $username])->first();
	if($staticip){
		$num_ips = $staticip->numberofips;
		$ip_rate = $staticip->rates;

		$totalIPamount = $ip_rate*$num_ips;
	}else{
		$num_ips = 0;
		$ip_rate = 0;

		$totalIPamount = $ip_rate*$num_ips;
	}

	$static_ip_value = $totalIPamount."^".$ip_rate."^".$num_ips;
	return  $static_ip_value;
}

public function reportSummerypdf(Request $request)
{

             // 
	$username = $request->get('username');
	$bildate = $request->get('datetimes');
	$date2 = $request->get('date');
	$range = explode('-', $bildate);
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
	$status = Auth::user()->status;

	$ebd =0;



	if($status == "manager"){
		$total = array();
		$user_data = UserInfo::where([ 'username' => $username])->first();
		$resellerid = $user_data->resellerid;
		$monthlyBillingEntries = ResellerProfileRate::where(['resellerid' => $resellerid])->orderBy('groupname')->get();
		$monthlyBillingEntries1 = ManagerProfileRate::where(['manager_id' => Auth::user()->manager_id])->orderBy('groupname')->get();

   //static ip

		if($ebd == 10){


			$static_function = $this->staticIp($username);
			$static_function = explode("^", $static_function);



			$totalIPamount = $static_function[0];
			$ip_rate = $static_function[1];
			$num_ips = $static_function[2];

		}else{
			$totalIPamount = 0;
			$ip_rate = 0;
			$num_ips = 0;
		}

		foreach($monthlyBillingEntries as $data){
			$groupname = $data->groupname;
			$rate = $data->rate;


			$lite = AmountBillingInvoice::where('resellerid' , $resellerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

			$total[] = $rate*$lite;

			

		}
		$total_amount = AmountBillingInvoice::where('resellerid' , $resellerid)
		->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('r_acc_rate');

		$final_amount = $totalIPamount + $total_amount;

		$userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
		$userCollection1 = UserInfo::where('status', 'reseller')->where('resellerid','=',$resellerid)->first();
		
	}elseif($status == "reseller" || $status == 'inhouse'){
		$total = array();
		if($username =="all"){
 	// $user_data = Auth::user()->username;
			$user_data = UserInfo::where([ 'username' => Auth::user()->username])->first();
			$resellerid = $user_data->resellerid;
 //$monthlyBillingEntries1 = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
			$monthlyBillingEntries = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

  //static ip

			if($bildate == date("Y-m-10")){

				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}
			

			



			foreach($monthlyBillingEntries as $data){
				$groupname = $data->groupname;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('resellerid' , $resellerid)
				->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

				$total[] = $lite;

				

			}

			$monthlyBillingEntries2 = AmountBillingInvoice::where(['resellerid' => $resellerid])->orderBy('profile')->get();
            // return $total;
			$total_amount = AmountBillingInvoice::where('resellerid' , $resellerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('d_acc_rate');
			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'reseller','resellerid' => Auth::user()->resellerid ])->get();
			$userCollection1 = UserInfo::where('status', 'reseller')->where('resellerid','=',$resellerid)->first();


			$pdf= PDF::loadView('users.billing.summeryReport_PDF',[
				'isSearched' => true,
				'total' => $total,
				'userCollection' => $userCollection,
				'total_amount' => $total_amount,
				'monthlyBillingEntries' => $monthlyBillingEntries,
				'monthlyBillingEntries2' => $monthlyBillingEntries2,
				'userCollection1' => $userCollection1,
				'num_ips' => $num_ips,
				'ip_rate' => $ip_rate,
				'totalIPamount' => $totalIPamount,
				'final_amount' => $final_amount,
				'from'=> $from,
				
				'to' => $to

				
			]);

			return $pdf->stream($username.'.pdf');




		}
		$user_data = UserInfo::where([ 'username' => $username])->first();
		$dealerid = $user_data->dealerid;
		$monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
		$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

  //static ip

		if($ebd[2] == 10){

			$static_function = $this->staticIp($username);
			$static_function = explode("^", $static_function);



			$totalIPamount = $static_function[0];
			$ip_rate = $static_function[1];
			$num_ips = $static_function[2];

		}else{
			$totalIPamount = 0;
			$ip_rate = 0;
			$num_ips = 0;
		}
		

		



		foreach($monthlyBillingEntries as $data){
			$groupname = $data->groupname;
			$rate = $data->rate;


			$lite = AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

			$total[] = $lite;

			

		}

		$monthlyBillingEntries2 = AmountBillingInvoice::where(['dealerid' => $dealerid])->orderBy('profile')->get();
            // return $total;
		$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
		->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('d_acc_rate');
		$final_amount = $totalIPamount + $total_amount;

		$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
		$userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();
	}elseif($status == "dealer"){

		$selectUser = $request->get('username');
		if($selectUser == "own"){
			$selectUserData =  UserInfo::where([ 'username' => Auth::user()->username,'status' => 'dealer'])->first();

			$username = $selectUserData->username;
    ///static ip

      //static ip

			if($bildate == date("Y-m-10")){

				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}
			$total = array();
			
			$dealerid = $selectUserData->dealerid;
			$monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
			$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

			foreach($monthlyBillingEntries as $data){
				$groupname = $data->groupname;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('dealerid' , $dealerid)
				->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

				$total[] = $lite;

				

			}

			
            // return $total;
			$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('dealerrate');

			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
			$userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();

			


		}else{

			

              /////subdealer work

      //static ip

			if($bildate == date("Y-m-10")){

				$static_function = $this->staticIp($username);
				$static_function = explode("^", $static_function);



				$totalIPamount = $static_function[0];
				$ip_rate = $static_function[1];
				$num_ips = $static_function[2];

			}else{
				$totalIPamount = 0;
				$ip_rate = 0;
				$num_ips = 0;
			}

			$total = array();
			$user_data = UserInfo::where([ 'username' => $username,'dealerid' => Auth::user()->dealerid])->first();
			$sub_dealer_id = $user_data->sub_dealer_id;
			$monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
			$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

			foreach($monthlyBillingEntries as $data){
				$groupname = $data->groupname;
				$rate = $data->rate;


				$lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
				->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

				$total[] = $lite;

				

			}
            // return $total;
			$total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('subdealerrate');

			$final_amount = $totalIPamount + $total_amount;

			$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
			$userCollection1 = UserInfo::where('status', 'subdealer')->where('sub_dealer_id','=',$sub_dealer_id)->first();

		}

	}elseif($status == "subdealer"){


		$total = array();
		$user_data = UserInfo::where([ 'username' => $username,'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
		$sub_dealer_id = $user_data->sub_dealer_id;
		$monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
		$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

  //static ip

		if($bildate == date("Y-m-10")){

			$static_function = $this->staticIp($username);
			$static_function = explode("^", $static_function);



			$totalIPamount = $static_function[0];
			$ip_rate = $static_function[1];
			$num_ips = $static_function[2];

		}else{
			$totalIPamount = 0;
			$ip_rate = 0;
			$num_ips = 0;
		}

		foreach($monthlyBillingEntries as $data){
			$groupname = $data->groupname;
			$rate = $data->rate;


			$lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('profile','=',$groupname)->count();

			$total[] = $lite;

			

		}
            // return $total;
		$total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
		->where('charge_on','>',date($from))->where('charge_on','<',date($to))->sum('subdealerrate');

		$final_amount = $totalIPamount + $total_amount;

		$userCollection = UserInfo::select('username')->where(['status'=>'subdealer','sub_dealer_id' => Auth::user()->sub_dealer_id ])->get();
		$userCollection1 = UserInfo::where('username', $username)->where('sub_dealer_id','=',$sub_dealer_id)->first();

	}


            //return $total;



	$pdf= PDF::loadView('users.billing.summeryReport_PDF',[
		'isSearched' => true,
		'total' => $total,
		'userCollection' => $userCollection,
		'total_amount' => $total_amount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'monthlyBillingEntries2' => $monthlyBillingEntries2,
		'userCollection1' => $userCollection1,
		'num_ips' => $num_ips,
		'ip_rate' => $ip_rate,
		'totalIPamount' => $totalIPamount,
		'final_amount' => $final_amount,
		'from'=> $from,
		
		'to' => $to

		
	]);

	return $pdf->stream($username.'.pdf');


}

///////commision
public function commisionSummerypdf(Request $request)
{

	// billing summary report
	$bildate = $request->get('date');
	$status = Auth::user()->status;
	if(!empty($request->get('own'))){
		$username = Auth::user()->username;
	}else if(!empty($request->get('trader_data'))){
		$username = $request->get('trader_data');
	}else if(!empty($request->get('dealer_data'))){
		$username = $request->get('dealer_data');
	}else if(!empty($request->get('reseller_data'))){
		$username = $request->get('reseller_data');
	}else{
		return 'Please select user first';
	}
	//
	// $bildate = '2023-08-10';
	$ebd=explode('-', $bildate);
	if($ebd[2] == 10){
		$from = date("Y-m-25 12:00:00", strtotime("-1 months", strtotime($bildate)));
		$to = date("Y-m-10 12:00:00", strtotime($bildate));
		$dueDate= date("Y-m-15");
	}else{
		$from = date("Y-m-10 12:00:00", strtotime($bildate));
		$to = date("Y-m-25 12:00:00", strtotime($bildate));
		$dueDate= date("Y-m-30");
	}
	//
	$rows = array();
	$total = 0;
	$count = array();
        //
	$selectedUser = UserInfo::where('username', $username)->first();
        //
	if ($selectedUser->status == 'manager'){
		$monthlyBillingEntries = AmountBillingInvoice::where('manager_id', $selectedUser->manager_id)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'reseller'){
		$monthlyBillingEntries = AmountBillingInvoice::where('resellerid', $selectedUser->resellerid)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'dealer'){
		$monthlyBillingEntries = AmountBillingInvoice::where('dealerid', $selectedUser->dealerid)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}else if ($selectedUser->status == 'subdealer'){
		$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id', $selectedUser->sub_dealer_id)
		->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
	}
	//
	foreach($monthlyBillingEntries as $key => $entry) {
		//
			if($selectedUser->status == 'reseller'){
				$comm = $entry['r_commission'];
			}else if($selectedUser->status == 'dealer'){
				$comm = $entry['commision'];
			}
			//
			$rows[$key] = $entry['name'];
			$count[$entry['name']]['amount'][$key] = $comm;
			$total = $total + $comm;

	}
	//
	$pdf= PDF::loadView('users.billing.commisionReport_PDF',[
		'user_data' => $selectedUser,
		'bildate' => $bildate,
		'dueDate' =>$dueDate,
		'rows' => array_unique($rows),
		'count' => $count,
		'total' => $total,
	]);
	return $pdf->stream($username.'.pdf');
	// 
	
}

/////



public function profitSummerypdf(Request $request)
{

						 // 
	$username = $request->get('username');
	$bildate = $request->get('datetimes');
	$range = explode('-', $bildate);
	$from1 = $range[0];
	$to1 = $range[1];

	$from1=date('Y-m-d H:i:s' ,strtotime($from1));
	
	$to1=date('Y-m-d H:i:s' ,strtotime($to1));
	$status = Auth::user()->status;

	$ebd =0;




	if($status == "reseller"){
		$total = array();
		$user_data = UserInfo::where([ 'username' => $username])->first();
		$dealerid = $user_data->dealerid;
		$monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
		$monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

	//static ip

		if($ebd[2] == 10){

			$static_function = $this->staticIp($username);
			$static_function = explode("^", $static_function);



			$totalIPamount = $static_function[0];
			$ip_rate = $static_function[1];
			$num_ips = $static_function[2];

		}else{
			$totalIPamount = 0;
			$ip_rate = 0;
			$num_ips = 0;
		}
		

		



		foreach($monthlyBillingEntries as $data){
			$groupname = $data->groupname;
			$rate = $data->rate;


			$lite = AmountBillingInvoice::where('dealerid' , $dealerid)
			->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->where('profile','=',$groupname)->count();

			$total[] = $lite;

			

		}

		$monthlyBillingEntries2 = AmountBillingInvoice::where(['resellerid' => Auth::user()->resellerid])->get();
						// return $total;
		$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
		->where('charge_on','>=',DATE($from1))->where('charge_on','<=',DATE($to1))->sum('d_acc_rate');
		$final_amount = $totalIPamount + $total_amount;

		$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
		$userCollection1 = UserInfo::where('status', 'reseller')->where('resellerid','=',Auth::user()->resellerid)->first();
		$dealerCollection = UserInfo::where('status', 'dealer')->limit(80)->get();
	}



						//return $total;



	$pdf= PDF::loadView('users.billing.profitReport_PDF',[
		'isSearched' => true,
		'total' => $total,
		'userCollection' => $userCollection,
		'total_amount' => $total_amount,
		'monthlyBillingEntries' => $monthlyBillingEntries,
		'monthlyBillingEntries1' => $monthlyBillingEntries1,
		'userCollection1' => $userCollection1,
		'num_ips' => $num_ips,
		'ip_rate' => $ip_rate,
		'totalIPamount' => $totalIPamount,
		'dealerCollection' => $dealerCollection,
		'final_amount' => $final_amount,
		'from1'=> $from1,
		
		'to1' => $to1

		
	]);

	$pdf->setPaper('a4', 'landscape')->setWarnings(false)->output();
	return $pdf->stream($username.'.pdf');


}


////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////


public function dealer_matching()
	{
		$id = Auth::user()->id;
		$userId = 'user' . $id;
		$pModule = array();
		$cModule = array();
		$state = array();
		$userId = 'user' . Auth::user()->id;
		// if (Auth::user()->status == 'inhouse') {
			if (!MyFunctions::check_access('Contractor Balance Matching', Auth::user()->id)) {
				return 'Permission Denied';
			}
		// }

		$status = Auth::user()->status;
		if ($status == "reseller") {
			$userCollection = UserInfo::select('username')->where('status', 'dealer')->where('resellerid',Auth::user()->resellerid)->get();
		} elseif ($status == "dealer") {
			$userCollection = UserInfo::select('username')->where(['status' => 'subdealer', 'dealerid' => Auth::user()->dealerid])->get();
		} elseif ($status == "manager") {
			$userCollection = UserInfo::select('username')->where(['status' => 'reseller', 'manager_id' => Auth::user()->manager_id])->get();
		} elseif ($status == 'inhouse') {
			$userCollection = UserInfo::select('username')->where(['status' => 'dealer', 'resellerid' => Auth::user()->resellerid])->get();
		}
		return view('users.dealer-matching.ledger_report', [
			'userCollection' => $userCollection
		]);
	}

	public function dealer_matching_pdf(Request $request)
	{
		$username = $request->get('username');
		$date = Date('Y:m:d', strtotime('+1 days'));

		$status = Auth::user()->status;
		if ($status == "reseller" || $status == 'inhouse') {

			if ($username == "All") {
				$data = UserInfo::select('username')->where('status', '=', 'dealer')->where('resellerid', '=', Auth::user()->resellerid)->get();
				$pdf = PDF::loadView('users.billing.ledge_PDF2', [
					'data' => $data,
					'fromDate' => $date,
					// 'toDate' => $to1
				]);
				return $pdf->stream($username . '.pdf');
			} else {
				$data = UserInfo::Where(['username' => $username])->first();
				$sumCredit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('credit');
				$sumDebit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('debit');
				$paymentTranssaction = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->orderBy('date', 'asc')->get();
			}
		} elseif ($status == "dealer") {
			$data = UserInfo::Where(['username' => $username])->first();
			$sumCredit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('credit');
			$sumDebit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('debit');
			$paymentTranssaction = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->orderBy('date', 'asc')->get();
		} elseif ($status == "manager") {
			$data = UserInfo::Where(['username' => $username])->first();
			$sumCredit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('credit');
			$sumDebit = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->sum('debit');
			$paymentTranssaction = LedgerReport::where('username', '=', $username)->where('date', '<=', $date)->orderBy('date', 'asc')->get();
		}
		$dealer_Amount = UserAmount::where('username', $username)->first();
		// $sub_dealer_Amount = UserAmount::where('username', $username)->where('status', 'subdealer')->sum('amount');

		$data_user = UserInfo::Where('dealerid', $username)->where('status', 'subdealer')->get(['username']);
		$sub_dealer_Amount = UserAmount::whereIn('username', $data_user)->where('status', 'subdealer')->sum('amount');

		$amount__Balance_Dealer = array();
		$dealers = UserInfo::where('username', $username)->where('status', 'dealer')->get();


		$result = array();
		$dealers = UserInfo::where('status', 'dealer')->where('dealerid', $username)->get();
		foreach ($dealers as $key => $value) {
			$amount = AmountTransactions::where(['receiver' => $value->username, 'commision' => 'no'])->sum('amount');
			$cash_amount = AmountTransactions::where(['receiver' => $value->username, 'commision' => 'no'])->sum('cash_amount');
			$total = $amount - $cash_amount;
			// $data = array('username' => $value->username, 'amount' => $total);
			$d = ['username' => $value->username, 'amount' => $total];
			array_push($result, $d);
		}



		$rows = array();
		$netPayable = 0;
		$netProfitMargin = 0;
		$date = $request->get('datetimes');
//
if(date('d') >= 10 && date('d') < 25){
	$startFrom = date("Y-m-10 12:00:00");
}elseif(date('d') >= 25 && date('d') <= 31){
	$startFrom = date("Y-m-25 12:00:00");
}elseif(date('d') < 10){
	$startFrom = date("Y-m-25 12:00:00",strtotime("-1 Months"));
} 
//	
$invoices = AmountBillingInvoice::where('dealerid', $username)->where('charge_on', '>', $startFrom)->where('charge_on', '<', now())->get();
    //
// 

		foreach($invoices as $key => $entry) {

			$rows[$key]['username'] = $entry->username;
			$rows[$key]['name'] = $entry['name'];
			$rows[$key]['resellerid'] = $entry->resellerid;
			$rows[$key]['dealerid'] = $entry->dealerid;
			$rows[$key]['sub_dealer_id'] = $entry->sub_dealer_id;
			$rows[$key]['creationDate'] = $entry->user_info->creationdate;
			$rows[$key]['chargeOn'] = $entry->charge_on;
			$rows[$key]['profileAmount'] = $entry->user_info['profile_amount'];
			$rows[$key]['rate'] = $entry['rate'];
			$rows[$key]['filerTax'] = $entry['filer_tax'];
			$rows[$key]['date'] = $entry['date'];
			$rows[$key]['static_ip_amount'] = $entry['static_ip_amount'];
			$rows[$key]['sst'] = $entry['sst'];
			$rows[$key]['adv_tax'] = $entry['adv_tax'];
			$rows[$key]['company_rate'] = $entry['company_rate'];
			$rows[$key]['margin'] = $entry['margin'];
				//
			if($entry['company_rate'] == 'yes'){
		
				if($data->status == 'manager'){
				 $rows[$key]['wallet'] = $entry['m_rate'];
			 }else if($data->status == 'reseller'){
				 $rows[$key]['wallet'] = $entry['r_rate'];
			 }else if($data->status == 'dealer'){
					 // $rows[$key]['wallet'] = $entry['dealerrate'];
				if(empty($entry['sub_dealer_id'])){
					$rows[$key]['wallet'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
				}else{
						// $rows[$key]['wallet'] = $entry['dealer_gross_amount'] + $entry['sst'] + $entry['adv_tax'];
					$rows[$key]['wallet'] = $entry['wallet_deduction'] - $entry['margin'];
				}
					//
			}else if($data->status == 'subdealer'){
					 // $rows[$key]['wallet'] = $entry['subdealerrate'];
			 $rows[$key]['wallet'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
		 }
		
		}else{
		
			if($data->status == 'manager'){
			 $rows[$key]['wallet'] = $entry['m_acc_rate'];
		 }else if($data->status == 'reseller'){
			 $rows[$key]['wallet'] = $entry['r_acc_rate'];
		 }else if($data->status == 'dealer'){
			 $rows[$key]['wallet'] = ($entry['d_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2;
		 }else if($data->status == 'subdealer'){
			$rows[$key]['wallet'] = ($entry['s_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2; 
		}
		
		}
		
		$netPayable = $netPayable + $rows[$key]['wallet'];

		$netProfitMargin = $netProfitMargin + $rows[$key]['margin'];
		
		}


// dd($netPayable);
		$pdf = PDF::loadView('users.dealer-matching.ledger_PDF', [
			'data' => $data,
			'paymentTranssaction' => $paymentTranssaction,
			'sumCredit' => $sumCredit,
			'sumDebit' => $sumDebit,
			'from1' => $date,
			'dealer_Amount' => $dealer_Amount,
			'sub_dealer_Amount' => $sub_dealer_Amount,
			'amount__Balance_Dealer' => $result[0],
			'netPayable'=>$netPayable,
			'netProfitMargin' => $netProfitMargin,
		]);

		return $pdf->stream($username.'.pdf');
		// exit();
	}





}