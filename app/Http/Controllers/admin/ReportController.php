<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\UserAmount;
use App\model\Users\UserAccount;
use App\model\Users\ScratchCards;
use App\model\Users\AmountBillingInvoice;

use App\model\Users\AmountTransactions;
class ReportController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

     public function index($status){
         switch($status){
            case "amount" : {
            	$userCollection = UserInfo::select('username')->where('status', 'dealer')->get();
                return view('admin.users.amount_transaction',[
					'userCollection' => $userCollection,
					'isSearched' => false
				]);
            }break;
            case "summary" : {
            	$current = Auth::user()->status;
            	if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
				
                return view('admin.users.billing_summary',[
					'userCollection' => $userCollection,
					'isSearched' => false
				]);
            }break;
            case "advance" : {
            	$current = Auth::user()->status;
            	if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
                return view('admin.users.advance_summary',[
					'userCollection' => $userCollection,
					'isSearched' => false
				]);
            }break;
            case "invoice" : {
            	$current = Auth::user()->status;
            	if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
                return view('admin.users.invoice_summary',[
					'userCollection' => $userCollection,
					'isSearched' => false
				]);
            }break;
            case "history" : {
            	$userCollection = UserAmount::where('status', 'reseller')->get();
                return view('admin.users.amount_history',[
                	'userCollection' => $userCollection
                ]);
            }break;
            case "reportsummary" : {
            	if(Auth::user()->status == "super"){
            		$userCollection = UserInfo::select('manager_id')->where('status', 'manager')->get();
            	}else{
            		return redirect()->route('admin.dashboard');
            	}
            	
                return view('admin.users.report_summary',[
                	'userCollection'=>$userCollection,
                	'isSearched' => false


                ]);
            }break;
            case "viewaccount" : {
            	$userCollection = UserAccount::where('status', 'reseller')->get();
                return view('admin.users.view_account',[
                	'userCollection' => $userCollection
                ]);
            }break;

                  case "billingwithtax" : {
            	$current = Auth::user()->status;
            	if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
                return view('admin.users.billing_with_tax',[
					'userCollection' => $userCollection,
					'isSearched' => false
				]);
            }break;
            default :{
                return redirect()->route('admin.dashboard');
            }
        }
    }
	
	public function generateBillingReport(Request $request){
		// read from request
		$username = $request->get('username');
		$date = $request->get('date');

		$selectedUser = UserInfo::where('username',$username)->first();
		
		
		if($selectedUser->status == 'dealer'){
			// report summary
			$monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->sum('dealerrate');
			
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->get();
			$userCollection = UserInfo::select('username')->where('status', 'dealer')->get();
			return view('admin.users.amount_transaction',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);
			
		}elseif($selectedUser->status == 'subdealer'){
			
				$monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->sum('subdealerrate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->get();	
			$userCollection = UserInfo::select('username')->where('status', 'subdealer')->get();
			return view('admin.users.amount_transaction',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);		
		}elseif ($selectedUser->status == 'reseller') {
			$monthlyPayableAmount = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->sum('rate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
			->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->get();	
			$userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
			return view('admin.users.amount_transaction',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);
		}
		return $monthlyBillingEntries;
	}

	public function billingSummary(Request $request)
	{
		$current = Auth::user()->status;
		$username = $request->get('username');
		$date = $request->get('datetimes');
		$range = explode('-', $date);
		$from1 = $range[0];
		$to1 = $range[1];

		$from = date("Y-m-d H:i:s", strtotime($from1));
		$to = date("Y-m-d H:i:s", strtotime($to1));
		

		$selectedUser = UserInfo::where('username',$username)->first();
		
		
		if($selectedUser->status == 'dealer'){
			// report summary
			$monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
			->whereBetween('date',[$from,$to])->with('user_info')->sum('dealerrate');
			
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
			->whereBetween('date',[$from,$to])->with('user_info')->get();
			$userCollection = UserInfo::select('username')->where('status', 'dealer')->get();
			return view('admin.users.billing_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);
			
		}elseif($selectedUser->status == 'subdealer'){
				$monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
			->whereBetween('date',[$from,$to])->with('user_info')->sum('subdealerrate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
			->whereBetween('date',[$from,$to])->with('user_info')->get();	
			$userCollection = UserInfo::select('username')->where('status', 'subdealer')->get();
			return view('admin.users.billing_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);		
		}elseif($selectedUser->status == 'manager'){
				$monthlyPayableAmount = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>=',date($from))->where('charge_on','<=',date($to))->with('user_info')->sum('m_acc_rate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();	
			if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
			return view('admin.users.billing_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);		
		}
		elseif ($selectedUser->status == 'reseller') {
			$monthlyPayableAmount = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
			->whereBetween('date',[$from,$to])->with('user_info')->sum('rate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
			->whereBetween('date',[$from,$to])->with('user_info')->get();	
			$userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
			return view('admin.users.billing_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);
		}
		return $monthlyBillingEntries;
	}

	public function advanceSummary(Request $request)
	{
		$current = Auth::user()->status;
		$username = $request->get('username');
		$date = $request->get('datetimes');
		$range = explode('-', $date);
		$from1 = $range[0];
		$to1 = $range[1];
		$receiptType = $request->receipttype;
		$from = date("Y-m-d H:i:s", strtotime($from1));
		$to = date("Y-m-d H:i:s", strtotime($to1));
		

		$selectedUser = UserInfo::where('username',$username)->first();
		
		
		
				$monthlyPayableAmount = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->sum('m_rate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->get();	
			if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
			return view('admin.users.advance_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);		
		
		
		return $monthlyBillingEntries;
	}


	public function invoiceSummary(Request $request)
	{
		
		$current = Auth::user()->status;
		$username = $request->get('username');
		$date = $request->get('datetimes');
		$range = explode('-', $date);
		$from1 = $range[0];
		$to1 = $range[1];
		$receiptType = $request->receipttype;

		$from = date("Y-m-d H:i:s", strtotime($from1));
		$to = date("Y-m-d H:i:s", strtotime($to1));
		

		$selectedUser = UserInfo::where('username',$username)->first();
		
		
		
				$monthlyPayableAmount = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->sum('m_rate');
			
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->get();	
			if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
			return view('admin.users.invoice_summary',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries
			]);		
		
		
		return $monthlyBillingEntries;
	}


	
  public function loadUser(Request $request){
			$status = $request->get("status");
			$userCollection = UserInfo::select('username')->where('status', $status)->get();
		return $userCollection;
	}
	public function billing_with_tax(Request $request)
	{
		
		$current = Auth::user()->status;
		$username = $request->get('username');
		$date = $request->get('datetimes');
		$range = explode('-', $date);
		$from1 = $range[0];
		$to1 = $range[1];
		$receiptType = $request->receipttype;
		$from = date("Y-m-d H:i:s", strtotime($from1));
		$to = date("Y-m-d H:i:s", strtotime($to1));

		$selectedUser = UserInfo::where('username',$username)->first();
		
				$monthlyPayableAmount = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->sum('m_rate');
			// report summary
			// monthly Billing Report
			$monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
			->where('charge_on','>',date($from))->where('charge_on','<',date($to))->where('receipt',$receiptType)->with('user_info')->get();	
			if($current == "super"){
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->get();
            	}else{
            		$userCollection = UserInfo::select('username')->where('status', 'manager')->where('creationby', 'admin')->get();
            	}
			return view('admin.users.billing_with_tax',[
				'userCollection' => $userCollection,
				'isSearched' => true,
				'selectedUser' => $selectedUser,
				'monthlyPayableAmount' => $monthlyPayableAmount,
				'monthlyBillingEntries' => $monthlyBillingEntries,
				'receipt' => $receiptType
			]);		
		
		
		return $monthlyBillingEntries;
	}

}
