<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;
use App\model\Users\PaymentsTransactions;
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
use PDF;
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

	$status = Auth::user()->status;
	if($status == "super"){
		$userCollection = UserInfo::select('username')->where(['status' => 'manager'])->get();
	}elseif($status == "dealer"){
		$userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
	}
  return view('admin.users.report_summary',[
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
  if($status == "reseller"){

   $data = UserInfo::Where(['username' => $username])->first();

   $sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');

   $sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');

   $paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->get();


 }elseif($status == "dealer")
 {
  $data = UserInfo::Where(['username' => $username])->first();

  $sumCredit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('credit');

  $sumDebit = LedgerReport::where('username','=',$username)->where('date','<',DATE($from1))->sum('debit');

  $paymentTranssaction = LedgerReport::where('username','=',$username)->where('date','>=',DATE($from1))->where('date','<=',DATE($to1))->get();
}

$pdf= PDF::loadView('users.billing.ledge_PDF',[
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

  $username = $request->get('username');
  $bildate = $request->get('date');

  $ebd=explode('-', $bildate);
  if($ebd[2] == 10){
    $lastbilldate = date("Y-m-25", strtotime("-1 months", strtotime($bildate)));
    $dueDate= date("Y-m-15");
  }else{
               
   $lastbilldate = date("Y-m-10", strtotime($bildate));
   $dueDate= date("Y-m-30");
 }

  $status = Auth::user()->status;
$profileList = Profile::orderBy('groupname')->get();
$dealerList = UserInfo::select('dealerid')->where(['status'=> 'dealer'])->limit(85)->get();

$pdf= PDF::loadView('admin.users.billingReport_PDF',[
'profileList' => $profileList,
'dealerList' => $dealerList,
'bildate' => $bildate,
'lastbilldate'=> $lastbilldate,
'username' => $username
]);
$pdf->setPaper('a4', 'landscape')->setWarnings(false)->output();

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

public function customerBill($username,$date)
{

	 $sub_dealer_id = Auth::user()->sub_dealer_id;
   $dealerid = Auth::user()->dealerid;
  //  dd($date);


	 if(Auth::user()->status == "administrator"){
	 $checkUser = UserInfo::where(['username'=> $username])->first();	
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
	return redirect()->route('admin.dashboard');
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
	return redirect()->route('admin.dashboard');
}
}
	 
	 if(empty($checkUser)){
		return redirect()->route('admin.dashboard');
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
		$pdf= PDF::loadView('admin.users.customer_bill_PDF',[
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
		$pdf= PDF::loadView('admin.users.cyber_customer_bill_PDF',[
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

}