<?php
namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\Session;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\UserStatusInfo;
use App\model\Users\Domain;
use App\model\Users\BankImage;
use Carbon\Carbon;
use PDF;
class InvoiceController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}

public function pdf_viewer($username,$charge){
	//
	///////////////////////////////////////////////////////////////////////////////
	$show_invoice = 0;
	$subdealer_inv_status = 0;
	$dealer_inv_status = 0;
        //
	if(!empty(Auth::user()->resellerid)){
	$reseller_inv_status = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->first()->allow_invoice;
	} if(!empty(Auth::user()->dealerid)){
	$dealer_inv_status = UserInfo::where('dealerid',Auth::user()->dealerid)->where('status','dealer')->first()->allow_invoice;
	} if(!empty(Auth::user()->sub_dealer_id)){
		$subdealer_inv_status = UserInfo::where('sub_dealer_id',Auth::user()->sub_dealer_id)->where('status','subdealer')->first()->allow_invoice;
	}
    //
	if(Auth::user()->status == 'manager'){
		$show_invoice = 1;
	}else if(Auth::user()->status == 'reseller'){
		if($reseller_inv_status == 1){
			$show_invoice = 1;
		}
	}else if(Auth::user()->status == 'dealer'){
		if($reseller_inv_status == 1 && $dealer_inv_status == 1){
			$show_invoice = 1;
		}
	}else if(Auth::user()->status == 'subdealer'){
		if($reseller_inv_status == 1 && $dealer_inv_status == 1 && $subdealer_inv_status == 1){
			$show_invoice = 1;
		}
	}else if(Auth::user()->status == 'user'){
		$show_invoice = 1;
	}
	//
	$diff = (strtotime($charge) - strtotime(date('2023-06-20')));
	$diff = (round($diff / 86400));
	//
	if($show_invoice == 0 || $diff <= 0){
		// return 'Permission denied';
		// session()->flash('error', 'Permission denied');
		// return back();
		return 'Permission denied';
	}
	//////////////////////////////////////////////////////////////////////////////////
	//
	$get_invoice_data = AmountBillingInvoice::where(['username'=>$username,'date'=>$charge])->latest('charge_on')->first();
	if(empty($get_invoice_data)){
		// session()->flash('error', 'This User Invoice Not found..');
		// return redirect()->route('users.charge.index',['status' => 'recharge']);
		// return 'No invoice available';
		// session()->flash('error', 'No invoice available');
		// return back();
		return 'No invoice available';
	}
	//
	$dontAllow = array('reseller','dealer','subdealer','trader');
	$loginStatus = Auth::user()->status;
	if($get_invoice_data->company_rate == 'yes' &&  in_array($loginStatus, $dontAllow) ){
		// session()->flash('error', 'Sorry ! No invoice available on id charged on company rate.');
		// return 'Sorry ! No invoice available on id charged on company rate.';
		// return back();
		return 'Sorry ! No invoice available on id charged on company rate.';
	}
	//
	$get_user_data = UserInfo::where('username',$get_invoice_data->username)->first();
	$get_date = $get_invoice_data->date;
	$year = date('M-Y', strtotime($get_date));
	$get_user_statusinfo = UserStatusInfo::where('username',$username)->latest('username')->first();
	$charge_on =  date('d-M-Y', strtotime($get_user_statusinfo->card_charge_on));
	$expire_on = date('d-M-Y', strtotime($get_user_statusinfo->card_expire_on));

	$month = date('d-M-Y', strtotime($get_date));
	$l_month = date('t-M-Y', strtotime($get_date));
	$get_date = $get_invoice_data->date;
	$due_date = date('Y-m-d', strtotime($get_date . " + 5 day"));
	$get_profile_rate = '';
	
	if(($get_invoice_data->sst == 0.00 && $get_invoice_data->sst_percentage == 0.00)||($get_invoice_data->sst == 0.00 || $get_invoice_data->sst_percentage == 0))
	{	
		$get_profile_rate = $get_invoice_data->sst_percentage = 0.00;
	}
	else{
		$get_profile_rate = $get_invoice_data->sst / $get_invoice_data->sst_percentage;
	}
	$get_domain_data = Domain::where('resellerid',$get_invoice_data->resellerid)->first();
	$get_image = '';
	if(file_exists(public_path('/img/invoice_banner/'.$get_invoice_data->resellerid.'-'.'inv-banner.jpg'))){
		$get_image = asset('/img/invoice_banner/'.$get_invoice_data->resellerid.'-'.'inv-banner.jpg');
	}else{
		$get_image = asset('/img/invoice_banner/default.png');
	}
	$total_amount = $get_profile_rate + $get_invoice_data->sst + $get_invoice_data->adv_tax;
	$static_ip = $get_invoice_data->static_ip_amount;
	$total_amount_after_tax = $get_profile_rate * $get_invoice_data->fll_tax + $get_profile_rate * $get_invoice_data->cvas_tax + $get_profile_rate * $get_invoice_data->tip_tax + $static_ip;
	$grand_total = $total_amount_after_tax +  $get_invoice_data->sst + $get_invoice_data->adv_tax;
	//
	$get_bank_image = BankImage::where('status',1)->get();
	//
	$pdf= PDF::loadView('users.invoice.view-invoice',compact('get_domain_data','get_image','get_user_data','l_month','get_profile_rate','get_invoice_data','total_amount','month','year','due_date','static_ip','total_amount_after_tax','grand_total','charge_on','expire_on','get_bank_image'));

	return $pdf->stream($get_invoice_data->id.'.pdf');

}

/////////////////////////////////

public function invoice_error(){
	return view('users.invoice.invoice-error');
}


}