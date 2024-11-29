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
use App\model\Users\DataAgreement;
use App\model\admin\Useragreement;
use App\model\Users\Brand;

use Carbon\Carbon;



use PDF;
class UserReportController extends Controller
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
		//$userCollection = UserInfo::select('username')->where(['status' => 'manager'])->get();
	}elseif($status == "dealer"){
		//$userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
	}
	
	$userCollection="";
	
  /*return view('admin.report.report_PDF',[
   'userCollection' => $userCollection
 ]);
 */
 
 $data="";
 $username="user";
 
 $pdf= PDF::loadView('admin.contractor_agreement.report_PDF',[
 	'data' => $data
 ]);


 return $pdf->stream($username.'.pdf');


}

public function user_form(){	
	$status = Auth::user()->status;
	$user_data = UserInfo::where('status','dealer')->get();
	$data_agreement = DataAgreement::first(['content_name']);
	$get_brands = Brand::all();
	// $userCollection="";
	return view('admin.contractor_agreement.user_form',['data_agreement' => $data_agreement,'user_data'=>$user_data,'get_brands'=>
		$get_brands]);
}

public function user_form_post(Request $request){

	$user_data = UserInfo::where('status','dealer')->where('dealerid',$request['dealer_name'])->first();
	if(!empty($user_data))
	{
	$get_company = $request->get('company_name');
	$get_company_logo = Brand::where('reseller_id',$get_company)->first();

	$user = new Useragreement();
  	$user->company_name = $request->get('company_name');
	$user->cnic = $user_data->nic;
	$user->dealer_name = $user_data->dealerid;
	$user->dealer_area = $user_data->area;
	$user->content_name = $request->get('content_name');
	$user->company_date= $user_data->creationdate;
	$user->contractor_name= $user_data->firstname.' '.$user_data->lastname;
	$user->contractor_cnic = $user_data->nic;
	$user->contractor_mobile = $user_data->mobilephone;
	$user->behalf_name = $request->get('behalf_name');
	$user->behalf_designation = $request->get('behalf_designation');
	$user->company_logo = $get_company_logo->image;
	$user->save();
	}

   // $userId = $user->id;
 
	$username="user";
 
	 $pdf= PDF::loadView('admin.contractor_agreement.report_PDF',[

	  'company_name' => $request->get('company_name'),
	  'cnic' => $user_data->nic,
	  'dealer_name' => $user_data->dealerid,
	  'dealer_area' => $user_data->area,
	  'company_date' => $user_data->creationdate,
	  'contractor_name' => $user_data->firstname.' '.$user_data->lastname,
	  'contractor_cnic' => $user_data->nic,
	  'contractor_mobile' => $user_data->mobilephone,
	  'behalf_name' => $request->get('behalf_name'),
	  'behalf_designation' => $request->get('behalf_designation'),
	  'company_logo' => $get_company_logo->image,
	  'content_name' => $request->get('content_name'),
	  'created_at'=>Carbon::now()->timestamp
	  
	]);

	return $pdf->stream($username.'.pdf');
	

}

public function getuseragreements(){
	
	$useragreement = new Useragreement();
	
	//$useragreement_data = $useragreement::orderByDesc('id')->get();
	
	$useragreement_data = DB::select('SELECT * FROM Useragreement ORDER BY id DESC ');


	return view('admin.contractor_agreement.view_useragreements',[
		'useragreements' => $useragreement_data
	]);
	
}

public function getuseragreements_view(Request $request){

	
	
	$id = $request->id;
	$useragreements = new Useragreement();
	$useragreement = $useragreements::find($id);
	$username="user";

	$pdf= PDF::loadView('admin.contractor_agreement.report_PDF',[
		'company_name' => $useragreement->company_name,
		'cnic' => $useragreement->cnic,
		'dealer_name' => $useragreement->dealer_name,
		'dealer_area' => $useragreement->dealer_area,
		'company_date' => $useragreement->company_date,
		'contractor_name' => $useragreement->contractor_name,
		'contractor_cnic' => $useragreement->contractor_cnic,
		'contractor_mobile' => $useragreement->contractor_mobile,
		'behalf_name' => $useragreement->behalf_name,
		'behalf_designation' => $useragreement->behalf_designation,
		'content_name' =>$useragreement->content_name,
		'company_logo' => $useragreement->company_logo

	]);

	$filename = strtoupper($useragreement->dealer_name.'-Contractor-Agreement');
	return $pdf->stream($filename.'.pdf');


}



public function edit(Request $request,$id) {

		$useragreements = new Useragreement();
		$useragreement = $useragreements::find($id);
		$user_data = UserInfo::where('status','dealer')->get();
		$get_brands = Brand::all();
	  return view('admin.contractor_agreement.update_agreement',[
	  	'user_data' => $user_data,
	       'id' => $id,
	       'get_brands'=> $get_brands,
				'company_name' => $useragreement->company_name,
				'cnic' => $useragreement->cnic,
				'dealer_name' => $useragreement->dealer_name,
				'dealer_area' => $useragreement->dealer_area,
				'company_date' => $useragreement->company_date,
				'contractor_name' => $useragreement->contractor_name,
				'contractor_cnic' => $useragreement->contractor_cnic,
				'contractor_mobile' => $useragreement->contractor_mobile,
				'behalf_name' => $useragreement->behalf_name,
				'behalf_designation' => $useragreement->behalf_designation,
				 'content_name' => $useragreement->content_name,
				'company_logo' => $useragreement->company_logo
                	
               ]);
			   
			  

    }

 public function update(Request $request,$id){
	 
	 	$useragreements = new Useragreement();
		$useragreement = $useragreements::find($id);
		$get_company=$request->get('company_name');
		$get_company_logo = Brand::where('brand_name',$get_company)->first();

	  $useragreement->company_name = $request->get('company_name');
		// $useragreement->cnic = $request->get('cnic');
		// $useragreement->dealer_name = $request->get('dealer_name');
		// $useragreement->dealer_area = $request->get('dealer_area');
		// $useragreement->company_date = $request->get('company_date');
		// $useragreement->contractor_name = $request->get('contractor_name');
		// $useragreement->contractor_cnic = $request->get('contractor_cnic');
		// $useragreement->contractor_mobile = $request->get('contractor_mobile');
		$useragreement->behalf_name = $request->get('behalf_name');
		$useragreement->behalf_designation = $request->get('behalf_designation');
		$useragreement->company_logo = $get_company_logo->image;
		$useragreement->content_name = $request->get('content_name');
		$useragreement->updated_at =Carbon::now()->timestamp;
	
		$useragreement->save();
		
		  session()->flash('success','Useragreement successfully updated.');		  
		  return redirect()->route('admin.users.getuseragreements');


 }

  public function destroy($id){
	 
	
   $useragreement = Useragreement::find($id)->delete();
 
   
	 session()->flash('success','Useragreement successfully Delete.');		  
	 return redirect()->route('admin.users.getuseragreements');


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