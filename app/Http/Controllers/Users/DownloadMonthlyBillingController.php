<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\AmountBillingInvoice;
use Response;



class DownloadMonthlyBillingController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
public function index()
{
	return view('users.billing.monthly_billing_download');
}

public function downloadExcel(Request $request)
{
	
	$date = $request->date;
	$day = date('d',strtotime($date));
	if($day == 10){
		$from = date('Y-m-25 12:00:00', strtotime(date($date)." -1 month"));
		$to = date('Y-m-10 12:00:00', strtotime(date($date)));
		// dd($from);
	}else{
		$from = date('Y-m-10 12:00:00', strtotime(date($date)));
		$to = date('Y-m-25 12:00:00', strtotime(date($date)));
		// dd($to);
	}
	
	$data = AmountBillingInvoice::whereBetween('charge_on',[$from,$to])->get();
	$delimiter = ",";
		$f = fopen('php://memory', 'w');
		
		
		//////////////////////////////////////////////////////
		$lineData = array('id', 'username', 'userid', 'rate', 'm_rate', 'r_rate', 'dealerrate', 'subdealerrate', 'traderrate', 'sst', 'sst_percentage', 'adv_tax', 'adv_percentage', 'r_commission', 'commision', 'margin', 'm_acc_rate', 'r_acc_rate', 'd_acc_rate', 's_acc_rate', 't_acc_rate', 'profit', 'c_sst', 'c_adv', 'c_charges', 'c_rates', 'receipt', 'receipt_num', 'profile', 'name', 'dasti_amount', 'taxname', 'charge_on', 'sub_dealer_id', 'trader_id', 'dealerid', 'resellerid', 'manager_id', 'date', 'billing_type', 'card_no', 'filer_tax', 'fll_tax', 'cvas_tax', 'tip_tax', 'fll_charges', 'cvas_charges', 'tip_charges', 'dealer_gross_amount', 'static_ip_amount', 'wallet_deduction', 'recharge_from', 'company_rate');
		
		fputcsv($f, $lineData, $delimiter);
		// dd($data);
		foreach ($data as $key => $value) {
			$lineData = array($value->id, $value->username, $value->userid, $value->rate, $value->m_rate, $value->r_rate, $value->dealerrate, $value->subdealerrate, $value->traderrate, $value->sst, $value->sst_percentage, $value->adv_tax, $value->adv_percentage, $value->r_commission, $value->commision, $value->margin, $value->m_acc_rate, $value->r_acc_rate, $value->d_acc_rate, $value->s_acc_rate, $value->t_acc_rate, $value->profit, $value->c_sst, $value->c_adv, $value->c_charges, $value->c_rates, $value->receipt, $value->receipt_num, $value->profile, $value->name, $value->dasti_amount, $value->taxname, $value->charge_on, $value->sub_dealer_id, $value->trader_id, $value->dealerid, $value->resellerid, $value->manager_id, $value->date, $value->billing_type, $value->card_no, $value->filer_tax, $value->fll_tax, $value->cvas_tax, $value->tip_tax, $value->fll_charges, $value->cvas_charges, $value->tip_charges, $value->dealer_gross_amount, $value->static_ip_amount, $value->wallet_deduction, $value->recharge_from, $value->company_rate);
			// dd($lineData);
			fputcsv($f, $lineData, $delimiter);
		}
		/////////////////////////////////////////////////////
		$filename=$date.' Billing.csv';
		fseek($f, 0);
			//set headers to download file rather than displayed
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="' . $filename . '";');
			//output all remaining data on a file pointer
		fpassthru($f);
		exit;
}

}