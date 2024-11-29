<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\PaymentsTransactions;
use App\model\Users\LedgerReport;
use App\model\Users\UserAmount;
use App\model\Users\UserAccount;
use App\model\Users\UserAmountBillingBrg;
use App\model\Users\StaticipBill;
use App\model\Users\UserAccountAmountTransactionBrg;
use App\model\Users\UserAccountPaymentTransactionBrg;
use App\model\Users\FreezeAccount;
use App\model\Users\userAccess;
use App\model\Users\DealerProfileRate;
use App\model\Users\Profile;
use App\NumbersToWords;
use PDF;
use App\model\Users\Card;
use App\MyFunctions;
use DataTables;


class BillingController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct(){

 }

 public function index($status){

 	switch($status){
 		case "payments" : {
 			return view('users.billing.view_payment');
            // 	$paymentTransactions = PaymentsTransactions::where(['receiver' => Auth::user()->username])->where('type','!=','none_cash')->orderBy('date','desc')->get();
            // 	if(Auth::user()->status =='inhouse'){
            // 		$paymentTransactions = PaymentsTransactions::where(['receiver' => 'logonbroadband'])->orderBy('date','desc')->get();
            // 	}
            //    $userAccount = UserAccount::where(['status' => 'dealer'])->get();
            //     return view('users.billing.view_payment',[
            //     'paymentTransactions' => $paymentTransactions,
            //     'userAccount' => $userAccount
            //     ]);
 		}break;

 		case "transfer" : {

 			$status = Auth::user()->status;
 			$user = Auth::user()->username;
 			$dealerid = Auth::user()->dealerid;
					//  Freez Code Aslam
					// $check ='';
					// $radreply = FreezeAccount::where(['username' => $user])->first();
					// $check = @$radreply['freeze'];

					// if(!empty($radreply)){
					// 	if($check == 'yes'){
					// 		return redirect()->route('users.dashboard');
					// 	}
					// } 



 			if($status == "manager")
 			{
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'reseller', 'manager_id'=> Auth::user()->manager_id])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$packages = '';
 				$amount = 0;
 				$subdealers = '';
 				$no_of_packages = '';
 			}elseif($status == "reseller")
 			{
 				$currentAmount =0;
 				$amount = 0;
 				$subdealers = 0;
 				$packages = 0;
 				$no_of_packages = 0;
 				$userCollection = UserInfo::select('user_info.username','user_info.dealerid')->join('dealer_profile_rate','user_info.dealerid','dealer_profile_rate.dealerid')->where('user_info.status','dealer')->where('user_info.resellerid',Auth::user()->resellerid)->distinct('user_info.username')->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();

 			}elseif($status == "dealer")
 			{
 				$currentAmount =0;
 				$amount = 0;
 				$subdealers = 0;
 				$no_of_packages = 0;

					//  $userCollection = UserInfo::select('username')->where(['status'=> 'subdealer', 'dealerid'=> Auth::user()->dealerid])->get();
 				$userCollection = UserInfo::join('subdealer_profile_rate','user_info.sub_dealer_id','subdealer_profile_rate.sub_dealer_id')->select('user_info.username')->where('user_info.status', 'subdealer')->where('user_info.dealerid', Auth::user()->dealerid)->distinct('user_info.username')->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$no_of_packages = array();
 				$sst = 0.195;
 				$adv_tax = 0.125;
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
 			}elseif($status == "subdealer")
 			{
 				$amount = 0;
 				$subdealers = 0;
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'trader', 'sub_dealer_id'=> Auth::user()->sub_dealer_id])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$packages = '';
 				$no_of_packages = '';
 			}
 			elseif($status == "inhouse" && Auth::user()->dealerid == Null){
 				$amount = 0;
 				$subdealers = 0;
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'dealer', 'resellerid'=> Auth::user()->resellerid])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$packages = '';
 				$no_of_packages = '';
 			}else if($status == "inhouse" && Auth::user()->dealerid != Null && Auth::user()->sub_dealer_id == Null){
 				$currentAmount =0;
 				$amount = 0;

					//  $userCollection = UserInfo::select('username')->where(['status'=> 'subdealer', 'dealerid'=> Auth::user()->dealerid])->get();
 				$userCollection = UserInfo::join('subdealer_profile_rate','user_info.sub_dealer_id','subdealer_profile_rate.sub_dealer_id')->select('user_info.username')->where('user_info.status', 'subdealer')->where('user_info.dealerid', Auth::user()->dealerid)->distinct('user_info.username')->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$packages = '';
 				$no_of_packages = '';
 			}else if($status == "inhouse" && Auth::user()->dealerid != Null && Auth::user()->sub_dealer_id != Null){
 				$currentAmount =0;
 				$amount = 0;

 				$userCollection = UserInfo::select('username')->where(['status'=> 'trader', 'sub_dealer_id'=> Auth::user()->sub_dealer_id])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 				$packages = '';
 				$no_of_packages = '';
 			}

 			if($status == 'inhouse' && Auth::user()->resellerid == NULL){
 				return view('users.billing.manager_support.transfer_amount');	
 			}else{
 				return view('users.billing.transfer_amount',[
 					'managerCollection' => $userCollection,
 					'amountTransactions' => $amountTransactions,
 					'currentAmount' => $currentAmount,
 					'amount' => $amount,
 					'subdealer' => $subdealers,
 					'profiles' => $packages,
 					'no_of_packages' => $no_of_packages
 				]);
 			}


 		}break;
 		case "commision" : {

 			$id = Auth::user()->id;
 			$userId = 'user'.$id;
 			$pModule = array();
 			$cModule = array();
 			$state = array();
 			$userId = 'user'.Auth::user()->id;
 			if(Auth::user()->status =='inhouse'){
 				$loadData = userAccess::select($userId,'parentModule','childModule')->get();
 				if(!empty($loadData)){
 					foreach ($loadData as  $value) {
 						$state[] = $value[$userId];
 						$pModule[] = $value['parentModule'];
 						$cModule[] = $value['childModule'];
 					}

 				}
 				if($state[13] == 0 && $cModule[13] == 'Transfer Amount'){
 					return redirect()->route('users.dashboard');
 				}
 			}

 			$status = Auth::user()->status;
 			$user = Auth::user()->username;
 			$dealerid = Auth::user()->dealerid;
					//  Freez Code Aslam
					// $check ='';
					// $radreply = FreezeAccount::where(['username' => $user])->first();
					// $check = @$radreply['freeze'];

					// if(!empty($radreply)){
					// 	if($check == 'yes'){
					// 		return redirect()->route('users.dashboard');
					// 	}
					// } 
 			if($status == "manager")
 			{
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'reseller', 'manager_id'=> Auth::user()->manager_id])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 			}elseif($status == "reseller")
 			{
 				$currentAmount =0;
 				$userCollection = UserInfo::select('user_info.username','user_info.dealerid')->join('dealer_profile_rate','user_info.dealerid','dealer_profile_rate.dealerid')->where('user_info.status','dealer')->where('user_info.resellerid',Auth::user()->resellerid)->distinct('user_info.username')->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();

 			}elseif($status == "dealer")
 			{
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'subdealer', 'dealerid'=> Auth::user()->dealerid])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();

 			}elseif($status == "inhouse"){
 				$currentAmount =0;
 				$userCollection = UserInfo::select('username')->where(['status'=> 'dealer', 'resellerid'=> Auth::user()->resellerid])->get();
 				$amountTransactions = AmountTransactions::select('*')->where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();

 			}
 			return view('users.billing.commision_amount',[
 				'managerCollection' => $userCollection,
 				'amountTransactions' => $amountTransactions,
 				'currentAmount' => $currentAmount
 			]);
 		}break;

 		case "viewtransfer" : {
				// dd('sad');

                // $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get();
                // if(Auth::user()->status == 'dealer'){
                // 	 $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username ,'commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->username == 'logonbroadband'){
                // 	$amountTransactions = AmountTransactions::where(['sender' => 'logonbroadband','commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->status == 'reseller'){

                //      $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->status == 'inhouse'){
                //     if(Auth::user()->resellerid == "logonbroadband"){
                //      $amountTransactions = AmountTransactions::where(['sender' => 'logonbroadband','commision' => 'no'])->orderBy('date','desc')->get();
                //     }else{
                //        $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get(); 
                //     }
				// }
 			$transferCard = Card::where('dealerid',Auth::user()->dealerid)->select(DB::raw('sub_dealer_id,name,date,count(*) profilecount'))->groupby(['sub_dealer_id',"name","date"])->get();
				// dd($transferCard);

 			return view('users.billing.view_transfer',[

                    // 'amountTransactions' => $amountTransactions,
 				'transferCard' => $transferCard
 			]);
 		}break;
 		case "ipbills" : {
 			if(Auth::user()->status == 'manager'){
 				$static_ip_bills = StaticipBill::where('numberofips','!=',0)->where('rates','!=',0)->get();
 			}else if(Auth::user()->status == 'reseller'){
 				$static_ip_bills = StaticipBill::where(['resellerid' => Auth::user()->resellerid])->where('username','!=',Auth::user()->resellerid)->where('numberofips','!=',0)->where('rates','!=',0)->get();
 			}
 			return view('users.billing.view_ipbills',[

 				'static_ip_bills' => $static_ip_bills
 			]);
 		}break;
 		case "viewcommision" : {
 			if(Auth::user()->status == "reseller"){
 				$amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'yes'])->orderBy('date','desc')->get();
 			}elseif(Auth::user()->status == "manager"){
 				$amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'yes'])->orderBy('date','desc')->get();
 			}elseif(Auth::user()->status == "inhouse"){
 				$amountTransactions = AmountTransactions::where(['sender' => Auth::user()->resellerid,'commision' => 'yes'])->orderBy('date','desc')->get();
					// dd($amountTransactions);
 			}
 			else{
 				$amountTransactions = AmountTransactions::where(['receiver' => Auth::user()->username,'commision' => 'yes'])->orderBy('date','desc')->get();
 			}

 			return view('users.billing.view_commision',[

 				'amountTransactions' => $amountTransactions
 			]);
 		}break;
 		case "nonecash" : {

 			if(Auth::user()->status == "reseller" || Auth::user()->status == "inhouse"){
 				$userCollection = UserInfo::select('user_info.username','user_info.dealerid')->join('dealer_profile_rate','user_info.dealerid','dealer_profile_rate.dealerid')->where('user_info.status','dealer')->where('user_info.resellerid',Auth::user()->resellerid)->distinct('user_info.username')->get();
 			}else{
 				$userCollection = UserInfo::select('username')->where(['status'=> 'reseller', 'manager_id'=> Auth::user()->manager_id])->get();
 			}


 			return view('users.reseller.none_cash',[
 				'userCollection' => $userCollection
 			]);
 		}break;
 		case "viewnonecash" : {

 			$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;

 			$paymentTransactions = PaymentsTransactions::where(['receiver' => Auth::user()->username])->where('type','=','none_cash')->orderBy('date','desc')->get();
 			if(Auth::user()->status =='inhouse'){
 				$paymentTransactions = PaymentsTransactions::where(['receiver' => $resellerid])->where('type','=','none_cash')->orderBy('date','desc')->get();
 			}

 			return view('users.reseller.view_none_cash',[
 				'paymentTransactions' => $paymentTransactions
 			]);
 		}break;
 		case "recieve" : {


 			$status = Auth::user()->status;
 			if($status == "manager")
 			{
 				$userCollection = UserInfo::select('username')->where(['status'=> 'reseller', 'manager_id'=> Auth::user()->manager_id])->get();
 				$paymentTransactions = PaymentsTransactions::where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 			}elseif($status == "reseller")
 			{
            	   // $userCollection = UserInfo::select('username','dealerid')->where(['status'=> 'dealer', 'resellerid'=> Auth::user()->resellerid])->get();

 				$userCollection = UserInfo::select('user_info.username','user_info.dealerid')->join('dealer_profile_rate','user_info.dealerid','dealer_profile_rate.dealerid')->where('user_info.status','dealer')->where('user_info.resellerid',Auth::user()->resellerid)->distinct('user_info.username')->get();

 				$paymentTransactions = PaymentsTransactions::where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 			}elseif($status == "dealer")
 			{
 				$userCollection = UserInfo::select('username')->where(['status'=> 'subdealer', 'dealerid'=> Auth::user()->dealerid])->get();
 				$paymentTransactions = PaymentsTransactions::where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 			}elseif($status == "inhouse")
 			{
 				$userCollection = UserInfo::select('username')->where(['status'=> 'dealer', 'resellerid'=> Auth::user()->resellerid])->get();
 				$paymentTransactions = PaymentsTransactions::where(['sender' => Auth::user()->username])->orderBy('date','desc')->get();
 			}




 			return view('users.billing.recieve_amount',[
 				'userCollection' => $userCollection,
 				'paymentTransactions' => $paymentTransactions
 			]);
 		}break;
 		case "receive_amount" : {

 			$transferCard = '';
 			$status = Auth::user()->status;
 			if($status == "reseller")
 			{
 				$amount = AmountTransactions::where(['receiver' =>Auth::user()->username])->orderby('date','DESC')->get();
 			}elseif($status == "dealer")
 			{
 				$amount = AmountTransactions::where(['receiver' =>Auth::user()->username,'commision' => 'no'])->orderby('date','DESC')->get();
 			}elseif($status=="subdealer") {
 				$amount = AmountTransactions::where(['receiver' =>Auth::user()->username])->get();
 				$transferCard = Card::where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->select(DB::raw('sub_dealer_id,name,date,count(*) profilecount'))->groupby(['sub_dealer_id',"name","date"])->get();
				// dd($transferCard);

 			}elseif($status=="trader") {
 				$amount = AmountTransactions::where(['receiver' =>Auth::user()->username])->get();

 			}elseif($status == "inhouse"){
 				$userReseller = UserInfo::where('status','reseller')->where('resellerid',Auth::user()->resellerid)->first();

 				$amount = AmountTransactions::where(['receiver' =>$userReseller->username])->orderby('date','DESC')->get();
 			}elseif($status == "manager"){


 				$amount = AmountTransactions::where(['receiver' =>Auth::user()->username])->orderby('date','DESC')->get();
 			}




 			return view('users.billing.recieve_amount_summary',[
 				'amount' => $amount,
 				'transferCard' => $transferCard
 			]);
 		}break;
 		default :{
 			return redirect()->route('users.dashboard');
 		}
 	}
 }
 public function serverSideCashReceipt()
 {
		//
 	$resellerid = Auth::user()->resellerid;
		//
 	$paymentTransactions = PaymentsTransactions::where(['receiver' => Auth::user()->username])->where('type','!=','none_cash')->orderBy('date','desc')->get();
 	//
 	if(Auth::user()->status =='inhouse'){
 		$paymentTransactions = PaymentsTransactions::where(['receiver' => $resellerid])->orderBy('date','desc')->get();
 	}
 	//
 	$userAccount = UserAccount::where(['status' => 'dealer'])->get();
 	return Datatables::of($paymentTransactions)
 	->addColumn("senders", function ($row) {
				// $csrf = csrf_token();
				// $html =' <form action="/users/cashreciept" target="_blank" method="POST" style="display:inline">
				// <input type="hidden" name="_token" value="' .$csrf.'">
				// <input type="hidden" name="id" id="username" value="'.$row->id.'">
				// <button type="submit"  class="btn btn-link btn-xs" style="margin:5px;border-radius:7px;">'.$row->sender.'</button></form>';
 		return $row->sender;
 	})->addColumn("reciptBtn", function ($row) {
				//
 		$csrf = csrf_token();
 		$html =' <form action="/users/cashreciept" target="_blank" method="POST" style="display:inline">
 		<input type="hidden" name="_token" value="' .$csrf.'">
 		<input type="hidden" name="id" id="username" value="'.$row->id.'">
 		<button type="submit"  class="btn btn-default btn-xs" style="margin:5px;color:red;border:none"><i class="fa fa-file-pdf-o"></i>Receipt</button></form>';
 		return $html;

 	})->addColumn("paymentTypes", function ($row) {
 		$payment= $row->is_bank==1 ? 'BANK' : 'CASH';
 		$amount =' <div style="color:red">'.$payment.'</div>';

 		return $amount;
 	})->addColumn("bank_name", function ($row) {
 		$bank_name= $row->bank_name==NULL ? 'N/A' : $row->bank_name;
 		return $bank_name;
 	})->setRowAttr([
 		'style' => function($row){
 			return $row->status == 'SAVE' ? 'color: #ff0000;' : 'color:black';
 		}
 	])->editColumn("status1", function ($row) {

 		if(Auth::user()->status == "reseller" || Auth::user()->username == "arif" || Auth::user()->username == "go-arif" ){
 			if($row->status == "POST"){
 				$status = $row->status;
 			}else{
 				// $csrf = csrf_token();
 				// $status =' <form action="/users/save" method="POST" style="display:inline">
 				// <input type="hidden" name="_token" value="' .$csrf.'">
 				// <input type="hidden" name="username" value="' .$row->sender.'">
 				// <input type="hidden" name="amount" value="' .$row->amount.'">
 				// <input type="hidden" name="type" value="' .$row->type.'">
 				// <input type="hidden" name="date" value="' .$row->date.'">
 				// <input type="hidden" name="detail" value="' .$row->detail.'">
 				// <button type="submit"  class="btn btn-link btn-xs" style="margin:5px;border-radius:7px;">'.$row->status.'</button></form>';
 				// return $status;
 				//
 				return '<button type="button" data-id="'.$row->id.'" class="btn btn-info btn-xs saveBtn" style="margin:5px;border-radius:7px;">'.$row->status.'</button>';

 			}
 		}else{
 			if($row->status == "POST")
 				$status = $row->status;
 			else
 				$status = $row->status;

 		}
 		return $status;
 	})
 	->rawColumns([
 		"paymentTypes" => "paymentTypes",
 		"senders" => "senders",
 		"status1" => "status1",
 		"bank_name" => "bank_name",
 		"reciptBtn" => "reciptBtn",
 	])
 	->addIndexColumn()
 	->make(true);
 }
 public function viewTransferServerSide()
 {

 	$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
 	$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
 	$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
 	$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
 	$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
   		//
 	if(empty($resellerid)){
               // $sender = $manager_id;
 		$sender = UserInfo::where('manager_id',Auth::user()->manager_id)->first()->username;
   				//
 	}else if(empty($dealerid)){
 		$sender = $resellerid;
 	}else if(empty($sub_dealer_id)){
 		$sender = $dealerid;
 	}else{
 		$sender = $sub_dealer_id; 
 	}



 	$amountTransactions = AmountTransactions::where(['sender' => $sender,'commision' => 'no'])->orderBy('date','desc')->get();
		 // $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get();

                // if(Auth::user()->status == 'dealer'){
                // 	 $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username ,'commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->username == 'logonbroadband'){
                // 	$amountTransactions = AmountTransactions::where(['sender' => 'logonbroadband','commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->status == 'reseller'){

                //      $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get();
                // }else if(Auth::user()->status == 'inhouse'){

                //     if(Auth::user()->resellerid == "logonbroadband"){
                //      $amountTransactions = AmountTransactions::where(['sender' => 'logonbroadband','commision' => 'no'])->orderBy('date','desc')->get();
                //     }else{
                //        $amountTransactions = AmountTransactions::where(['sender' => Auth::user()->username,'commision' => 'no'])->orderBy('date','desc')->get(); 
                //     }
				// }

 	return Datatables::of($amountTransactions)
 	->addColumn("ta", function ($row) {
 		$ta = $row->amount == 0 ? -$row->cash_amount : $row->amount;

 		return $ta;
 	})->editColumn("action", function ($row) {
 		$status = '-';
 		if(Auth::user()->status == "reseller"){
 			$disabled = $row->amount <= 0 ? 'disabled' : '';
 			$csrf = csrf_token();
 			$status =" <button class='btn btn-xs btn-danger modal-data'
 			data-id='$row->id' data-receiver='$row->receiver' data-date='$row->date' data-amount='$row->amount' 
 			onclick='deleteConfirmModal()' $disabled >Delete</button>";
 			return $status;
 		}
 		return $status;
 	})->rawColumns([
 		"ta" => "ta",
 		"action" => "action",
 	])
 	->addIndexColumn()
 	->make(true);
 }

 public function store(Request $request ,$status)
 {
    		//
 	if(MyFunctions::is_freezed(Auth::user()->username)){
 		Session()->flash("error", "Your panel has been freezed");
 		return back();      
 	}
    		//
 	switch($status){
 		case "payments" : {
 			return view('users.billing.view_payment');
 		}
 		case "transactions" : {
 			return view('users.billing.view_transaction');
 		}
 		case "transfer" : {
 			return $this->storeTransfer($request, $status);
 		}
 		case "commision" : {
 			return $this->storeCommision($request, $status);
 		}

 		case "recieve" : {
 			return $this->storeRecieve($request, $status);
 		}
 		case "amount" : {
 			return view('users.billing.amount_transaction');
 		}
 		case "nonecash" : {
 			return $this->storeNoneCash($request, $status);
 		}
 		default :{
 			return redirect()->route('users.dashboard');
 		}
 	}
 }

///////commision////////////////////////////////////
 public function storeCommision(Request $request, $status){

 	$transferAmount = $request->get('amount');
 	$usernameReciever = $request->get('username');
 	$comment = $request->get('comment');

 	$userReciever = UserInfo::where(['username' =>$usernameReciever])->first();
 	$userRecieverStatus = $userReciever->status;
 	$usernameSender = Auth::user()->username;

		//	
 	$mystatus = Auth::user()->status;
 	if($mystatus == "manager")
 	{

				// store amount
 		$amountUser = UserAmount::where('username',$usernameReciever)->first();
 		$limit = $amountUser->credit_limit;
 		$transferSum =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('amount');

 		$x = $limit - $transferSum;


 		if($amountUser){
 			$lastRemainingAmount = $amountUser->amount;
 			$amountUser->amount += $transferAmount;
 			$amountUser->status = $userRecieverStatus;
 			$amountUser->save();
 		}else{
 			$lastRemainingAmount = 0.0;
 			$amountUser = new UserAmount();
 			$amountUser->username = $usernameReciever;
 			$amountUser->amount = $transferAmount;
 			$amountUser->status = $userRecieverStatus;
 			$amountUser->save();
 		} 

				// amount transaction
 		$transactionAmount = new AmountTransactions();
 		$transactionAmount->receiver = $usernameReciever;
				$transactionAmount->sender = $usernameSender; //username will be store
				$transactionAmount->amount = '0';
				$transactionAmount->last_remaining_amount = $lastRemainingAmount;
				$transactionAmount->date = Date('Y-m-d H:i:s');
				$transactionAmount->action_by_user = $usernameSender; // here sender is admin
				$transactionAmount->action_ip = $request->ip();
				$transactionAmount->comments = $comment;
				$transactionAmount->commision = 'yes';
				$transactionAmount->com_amount = $transferAmount;
				$transactionAmount->save();

				// entry in userAccountAmountTransactionBrg
				$userAccountAmountTransactionBrg = new UserAccountAmountTransactionBrg();
				$userAccountAmountTransactionBrg->amount_transaction_id = $transactionAmount->id;
				
				// tranfered amount: wiil be added as credir in user account
				$userAccount = UserAccount::where('username',$usernameReciever)->first();

				if($userAccount){
					//checking if transfer amount is greater then last debit
					// user account exists
					$lastDebit = $userAccount->debit;
					$lastCredit = $userAccount->credit;
					$adjustAmmount = $lastDebit - $transferAmount;
					if($adjustAmmount > 0){
						$userAccount->debit = $adjustAmmount;
						$userAccount->credit = 0;
					}elseif($adjustAmmount < 0){
						$userAccount->credit = abs($adjustAmmount);
						$userAccount->debit = 0;
					}elseif($adjustAmmount == 0){
						$userAccount->credit += 0;
						$userAccount->debit = 0;
					}
					
					$userAccount->action_by = $usernameSender; // here sender is admin
					$userAccount->action_ip = $request->ip();
					$userAccount->action_at = Date('Y-m-d H:i:s');
					$userAccount->save();

					// last debit amount will be added in userAccountAmountTransactionBrg
					$userAccountAmountTransactionBrg->last_remaining_debit = $lastDebit;
					$userAccountAmountTransactionBrg->last_remaining_credit = $lastCredit;
					$userAccountAmountTransactionBrg->save();
				}else{
					// create new account for user
					$userAccount = new UserAccount();
					$userAccount->username = $usernameReciever;
					$userAccount->status = $userRecieverStatus;
					// the transfer amount will be credited into userAccount
					$userAccount->credit = $transferAmount;
					$userAccount->action_by = $usernameSender; // here sender is admin
					$userAccount->action_ip = $request->ip();
					$userAccount->action_at = Date('Y-m-d H:i:s');
					$userAccount->save();
					
					// last debit amount will be added in userAccountAmountTransactionBrg: that will be 0 here
					$userAccountAmountTransactionBrg->last_remaining_debit = 0.0;
					$userAccountAmountTransactionBrg->last_remaining_credit = 0.0;
					$userAccountAmountTransactionBrg->save();
				}
				session()->flash('success',' You have successfully transfered.');
				return redirect()->route('users.billing.index',['status' => 'commision']);






			}

			if($mystatus != "manager"){
				if($mystatus == 'inhouse'){
					$data = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->
					select('username')->first();
					$checkamount = UserAmount::where('username',$data->username)->first();
					$totalAmount = $checkamount->amount;
				}else{
					$checkamount = UserAmount::where('username',$usernameSender)->first();
					$totalAmount = $checkamount->amount;
				}

				if($mystatus == "reseller" || $mystatus == 'inhouse'){
					// store amount
					$amountUser = UserAmount::where('username',$usernameReciever)->first();
					$limit = $amountUser->credit_limit;
					$transferSum =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('amount');

					$x = $limit - $transferSum;
				}else{
					$x= $transferAmount;
					$limit = $transferAmount;
				}
			}
			
			if($transferAmount <= $totalAmount  && $mystatus != "manager"){
				


				$RemainAmount = $totalAmount - $transferAmount;

				$checkamount->amount = $RemainAmount;
				$checkamount->save();

					// store amount
				$amountUser = UserAmount::where('username',$usernameReciever)->first();
				if($amountUser){
					$lastRemainingAmount = $amountUser->amount;
					$amountUser->amount += $transferAmount;
					$amountUser->status	= $userRecieverStatus;
					$amountUser->save();
				}else{
					$lastRemainingAmount = 0.0;
					$amountUser = new UserAmount();
					$amountUser->username = $usernameReciever;
					$amountUser->amount = $transferAmount;
					$amountUser->status	= $userRecieverStatus;
					$amountUser->save();
				} 

					// amount transaction
				$transactionAmount = new AmountTransactions();
				$transactionAmount->receiver = $usernameReciever;
				if($mystatus == 'inhouse'){
						$transactionAmount->sender = $data->username; //username will be store
					}else{
						$transactionAmount->sender = $usernameSender; //username will be store
					}
					
					$transactionAmount->amount = 0;
					$transactionAmount->cash_amount = 0;
					$transactionAmount->last_remaining_amount = $lastRemainingAmount;
					$transactionAmount->date = Date('Y-m-d H:i:s');
					if($mystatus == 'inhouse'){
						$transactionAmount->action_by_user = Auth::user()->username; // here sender is admin

					}else{
						$transactionAmount->action_by_user = $usernameSender; // here sender is admin
					}
					$transactionAmount->action_ip = $request->ip();
					$transactionAmount->comments = $comment;
					$transactionAmount->commision = 'yes';
					$transactionAmount->com_amount = $transferAmount;
					$transactionAmount->save();

					// entry in userAccountAmountTransactionBrg
					$userAccountAmountTransactionBrg = new UserAccountAmountTransactionBrg();
					$userAccountAmountTransactionBrg->amount_transaction_id = $transactionAmount->id;
					
					// tranfered amount: wiil be added as credir in user account
					$userAccount = UserAccount::where('username',$usernameReciever)->first();

					if($userAccount){
						//checking if transfer amount is greater then last debit
						// user account exists
						$lastDebit = $userAccount->debit;
						$lastCredit = $userAccount->credit;
						$adjustAmmount = $lastDebit - $transferAmount;
						if($adjustAmmount > 0){
							$userAccount->debit = $adjustAmmount;
							$userAccount->credit = 0;
						}elseif($adjustAmmount < 0){
							$userAccount->credit = abs($adjustAmmount);
							$userAccount->debit = 0;
						}elseif($adjustAmmount == 0){
							$userAccount->credit += 0;
							$userAccount->debit = 0;
						}
						if($mystatus == 'inhouse'){
							$userAccount->action_by = Auth::user()->username; // here sender is admin

						}else{
							$userAccount->action_by = $usernameSender; // here sender is admin

						}	
						

						$userAccount->action_ip = $request->ip();
						$userAccount->action_at = Date('Y-m-d H:i:s');
						$userAccount->save();

						// last debit amount will be added in userAccountAmountTransactionBrg
						$userAccountAmountTransactionBrg->last_remaining_debit = $lastDebit;
						$userAccountAmountTransactionBrg->last_remaining_credit = $lastCredit;
						$userAccountAmountTransactionBrg->save();
					}else{
						// create new account for user
						$userAccount = new UserAccount();
						$userAccount->username = $usernameReciever;
						$userAccount->status = $userRecieverStatus;
						// the transfer amount will be credited into userAccount
						$userAccount->credit = $transferAmount;
						if($mystatus == 'inhouse'){
							$userAccount->action_by = Auth::user()->username; // here sender is admin

						}else{
							$userAccount->action_by = $usernameSender; // here sender is admin

						}
						$userAccount->action_ip = $request->ip();
						$userAccount->action_at = Date('Y-m-d H:i:s');
						$userAccount->save();
						
						// last debit amount will be added in userAccountAmountTransactionBrg: that will be 0 here
						$userAccountAmountTransactionBrg->last_remaining_debit = 0.0;
						$userAccountAmountTransactionBrg->last_remaining_credit = 0.0;
						$userAccountAmountTransactionBrg->save();
					}
					session()->flash('success',' You have successfully transfered.');
					return redirect()->route('users.billing.index',['status' => 'commision']);

				}else{
					session()->flash('error',' You have insufficient amount.');
					return redirect()->route('users.billing.index',['status' => 'commision']);
				}
			}
////////////////////////////////////////////////////
			public function storeTransfer(Request $request, $status){

			//
				if(MyFunctions::is_freezed(Auth::user()->username)){
					Session()->flash("error", "Your panel has been freezed");
					return back();      
				}
			//
				$transferAmount = $request->get('amount');

				$usernameReciever = $request->get('username');
				$comment = $request->get('comment');

				$userReciever = UserInfo::where(['username' =>$usernameReciever])->first();
				$userRecieverStatus = $userReciever->status;
				$usernameSender = Auth::user()->username;
			//
				if(Auth::user()->status == 'manager'){
					$userAmount = UserAmount::where([
						"username" => $usernameReciever
					])->first();
					$discount = $userAmount->discount;
				}else{
					$userAmount = UserAmount::where([
						"username" => Auth::user()->resellerid
					])->first();
					$discount = $userAmount->discount;
				}
            //
				$transferAmount = $transferAmount / $discount;
			//	
				$mystatus = Auth::user()->status;
				if($mystatus == "manager")
				{

				// store amount
					$amountUser = UserAmount::where('username',$usernameReciever)->first();
					$limit = $amountUser->credit_limit;
					$transferSum =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('amount');
					$transfercash =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('cash_amount');
					$totalTransfer = $transferSum - $transfercash;

					$x = $limit - $totalTransfer;
					if($transferAmount <= $limit && $transferAmount <= $x ){

						if($amountUser){
							$lastRemainingAmount = $amountUser->amount;
							$amountUser->amount += $transferAmount;
							$amountUser->status = $userRecieverStatus;
							$amountUser->save();
						}else{
							$lastRemainingAmount = 0.0;
							$amountUser = new UserAmount();
							$amountUser->username = $usernameReciever;
							$amountUser->amount = $transferAmount;
							$amountUser->status = $userRecieverStatus;
							$amountUser->save();
						} 

				// amount transaction
						$transactionAmount = new AmountTransactions();
						$transactionAmount->receiver = $usernameReciever;
				$transactionAmount->sender = $usernameSender; //username will be store
				$transactionAmount->amount = $transferAmount;
				$transactionAmount->last_remaining_amount = $lastRemainingAmount;
				$transactionAmount->date = Date('Y-m-d H:i:s');
				$transactionAmount->action_by_user = $usernameSender; // here sender is admin
				$transactionAmount->action_ip = $request->ip();
				$transactionAmount->comments = $comment;
				$transactionAmount->commision = 'no';
				$transactionAmount->type = 'transfer';
				$transactionAmount->save();

				// entry in userAccountAmountTransactionBrg
				$userAccountAmountTransactionBrg = new UserAccountAmountTransactionBrg();
				$userAccountAmountTransactionBrg->amount_transaction_id = $transactionAmount->id;
				
				// tranfered amount: wiil be added as credir in user account
				$userAccount = UserAccount::where('username',$usernameReciever)->first();

				if($userAccount){
					//checking if transfer amount is greater then last debit
					// user account exists
					$lastDebit = $userAccount->debit;
					$lastCredit = $userAccount->credit;
					$adjustAmmount = $lastDebit - $transferAmount;
					if($adjustAmmount > 0){
						$userAccount->debit = $adjustAmmount;
						$userAccount->credit = 0;
					}elseif($adjustAmmount < 0){
						$userAccount->credit = abs($adjustAmmount);
						$userAccount->debit = 0;
					}elseif($adjustAmmount == 0){
						$userAccount->credit += 0;
						$userAccount->debit = 0;
					}
					
					$userAccount->action_by = $usernameSender; // here sender is admin
					$userAccount->action_ip = $request->ip();
					$userAccount->action_at = Date('Y-m-d H:i:s');
					$userAccount->save();

					// last debit amount will be added in userAccountAmountTransactionBrg
					$userAccountAmountTransactionBrg->last_remaining_debit = $lastDebit;
					$userAccountAmountTransactionBrg->last_remaining_credit = $lastCredit;
					$userAccountAmountTransactionBrg->save();
				}else{
					// create new account for user
					$userAccount = new UserAccount();
					$userAccount->username = $usernameReciever;
					$userAccount->status = $userRecieverStatus;
					// the transfer amount will be credited into userAccount
					$userAccount->credit = $transferAmount;
					$userAccount->action_by = $usernameSender; // here sender is admin
					$userAccount->action_ip = $request->ip();
					$userAccount->action_at = Date('Y-m-d H:i:s');
					$userAccount->save();
					
					// last debit amount will be added in userAccountAmountTransactionBrg: that will be 0 here
					$userAccountAmountTransactionBrg->last_remaining_debit = 0.0;
					$userAccountAmountTransactionBrg->last_remaining_credit = 0.0;
					$userAccountAmountTransactionBrg->save();
				}
				session()->flash('success',' You have successfully transfered.');
				return redirect()->route('users.billing.index',['status' => 'transfer']);

			}else{
				session()->flash('error',' Transfer Amount is greater then their limit.');
				return redirect()->route('users.billing.index',['status' => 'transfer']);
			}


		}

		if($mystatus != "manager")
			{				if($mystatus == 'inhouse'){
				$data = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->
				select('username')->first();
				$checkamount = UserAmount::where('username',$data->username)->first();
				$totalAmount = $checkamount->amount;
			}else{
				$checkamount = UserAmount::where('username',$usernameSender)->first();
				$totalAmount = $checkamount->amount;
			}
			
			if($mystatus == "reseller" || $mystatus == 'inhouse'){
					// store amount
				$amountUser = UserAmount::where('username',$usernameReciever)->first();
				$limit = $amountUser->credit_limit;
				$transferSum =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('amount');
				$transfercash =AmountTransactions::where(['receiver' =>$usernameReciever])->sum('cash_amount');
				$totalTransfer = $transferSum - $transfercash;

				$x = $limit - $totalTransfer;
			}else{
				$x= $transferAmount;
				$limit = $transferAmount;
			}
		}

		if($transferAmount <= $totalAmount  && $mystatus != "manager"){
			if($transferAmount <= $x && $transferAmount <= $limit){


				$RemainAmount = $totalAmount - $transferAmount;

				$checkamount->amount = $RemainAmount;
				$checkamount->save();

					// store amount
				$amountUser = UserAmount::where('username',$usernameReciever)->first();
				if($amountUser){
					$lastRemainingAmount = $amountUser->amount;
					$amountUser->amount += $transferAmount;
					$amountUser->status	= $userRecieverStatus;
					$amountUser->save();
				}else{
					$lastRemainingAmount = 0.0;
					$amountUser = new UserAmount();
					$amountUser->username = $usernameReciever;
					$amountUser->amount = $transferAmount;
					$amountUser->status	= $userRecieverStatus;
					$amountUser->save();
				} 

					// amount transaction
				$transactionAmount = new AmountTransactions();
				$transactionAmount->receiver = $usernameReciever;
				if($mystatus == 'inhouse'){
						$transactionAmount->sender = $data->username; //username will be store
					}else{
						$transactionAmount->sender = $usernameSender; //username will be store
					}
					
					$transactionAmount->amount = $transferAmount;
					$transactionAmount->cash_amount = 0;
					$transactionAmount->last_remaining_amount = $lastRemainingAmount;
					$transactionAmount->date = Date('Y-m-d H:i:s');
					if($mystatus == 'inhouse'){
						$transactionAmount->action_by_user = Auth::user()->username; // here sender is admin

					}else{
						$transactionAmount->action_by_user = $usernameSender; // here sender is admin
					}
					$transactionAmount->action_ip = $request->ip();
					$transactionAmount->comments = $comment;
					$transactionAmount->commision = 'no';
					$transactionAmount->type = 'transfer';
					$transactionAmount->save();

					// entry in userAccountAmountTransactionBrg
					$userAccountAmountTransactionBrg = new UserAccountAmountTransactionBrg();
					$userAccountAmountTransactionBrg->amount_transaction_id = $transactionAmount->id;
					
					// tranfered amount: wiil be added as credir in user account
					$userAccount = UserAccount::where('username',$usernameReciever)->first();

					if($userAccount){
						//checking if transfer amount is greater then last debit
						// user account exists
						$lastDebit = $userAccount->debit;
						$lastCredit = $userAccount->credit;
						$adjustAmmount = $lastDebit - $transferAmount;
						if($adjustAmmount > 0){
							$userAccount->debit = $adjustAmmount;
							$userAccount->credit = 0;
						}elseif($adjustAmmount < 0){
							$userAccount->credit = abs($adjustAmmount);
							$userAccount->debit = 0;
						}elseif($adjustAmmount == 0){
							$userAccount->credit += 0;
							$userAccount->debit = 0;
						}
						if($mystatus == 'inhouse'){
							$userAccount->action_by = Auth::user()->username; // here sender is admin

						}else{
							$userAccount->action_by = $usernameSender; // here sender is admin

						}	
						

						$userAccount->action_ip = $request->ip();
						$userAccount->action_at = Date('Y-m-d H:i:s');
						$userAccount->save();

						// last debit amount will be added in userAccountAmountTransactionBrg
						$userAccountAmountTransactionBrg->last_remaining_debit = $lastDebit;
						$userAccountAmountTransactionBrg->last_remaining_credit = $lastCredit;
						$userAccountAmountTransactionBrg->save();
					}else{
						// create new account for user
						$userAccount = new UserAccount();
						$userAccount->username = $usernameReciever;
						$userAccount->status = $userRecieverStatus;
						// the transfer amount will be credited into userAccount
						$userAccount->credit = $transferAmount;
						if($mystatus == 'inhouse'){
							$userAccount->action_by = Auth::user()->username; // here sender is admin

						}else{
							$userAccount->action_by = $usernameSender; // here sender is admin

						}
						$userAccount->action_ip = $request->ip();
						$userAccount->action_at = Date('Y-m-d H:i:s');
						$userAccount->save();
						
						// last debit amount will be added in userAccountAmountTransactionBrg: that will be 0 here
						$userAccountAmountTransactionBrg->last_remaining_debit = 0.0;
						$userAccountAmountTransactionBrg->last_remaining_credit = 0.0;
						$userAccountAmountTransactionBrg->save();
					}
					session()->flash('success',' You have successfully transfered.');
					return redirect()->route('users.billing.index',['status' => 'transfer']);
				}else{
					session()->flash('error',' Transfer Amount is greater then their limit.');
					return redirect()->route('users.billing.index',['status' => 'transfer']);
				}
			}else{
				session()->flash('error',' You have insufficient amount.');
				return redirect()->route('users.billing.index',['status' => 'transfer']);
			}
		}


		private function storeRecieve(Request $request, $status){
		// Cash Recieved Amount
        // data from request[sender(admin),reciever(user)]
			$recieveAmount = $request->get('amount');
			$usernameReciever = Auth::user()->username;
			$usernameSender = $request->get('username');
			$usernameSender1 = UserInfo::where(['username' => $usernameSender])->first();
			$senderStatus = $usernameSender1->status;

			$paidBy = $request->get('paidBy');
			$comment = $request->get('comment');
			$cash_amount = $request->get('cash_amount');
			$paymentType = $request->get('paymentType');
			if($paymentType == 'bank'){
				$bankName = $request->get("bankname");
			}
            //
			if(Auth::user()->status == 'manager'){
				$userAmount = UserAmount::where([
					"username" => $usernameSender
				])->first();
				$discount = $userAmount->discount;
			}else{
				$userAmount = UserAmount::where([
					"username" => Auth::user()->resellerid
				])->first();
				$discount = $userAmount->discount;
			}
            //
			$recieveAmount = $recieveAmount / $discount;
        //

        // adjust amount: discount will be added from recieved amount
			$adjustedAmount = $recieveAmount ;




        // recieveAmount: wiil be added as debit in user account but credit will be 0 if not then dredit will be adjusted first
			$userAccount = UserAccount::where('username',$usernameSender)->first();

			if($userAccount){
            // user account exists
				$lastCredit = $userAccount->credit;
				$lastDebit = $userAccount->debit;
				$adjustedAmount -= $lastCredit;
				if($adjustedAmount > 0){
					$userAccount->debit += $adjustedAmount;
					$userAccount->credit = 0;
				}elseif($adjustedAmount < 0){
                $userAccount->credit = abs($adjustedAmount); // change -123456 to 123456
                $userAccount->debit = 0;
            }elseif($adjustedAmount == 0){
            	$userAccount->credit = 0;
            	$userAccount->debit += 0;
            }
            $userAccount->status = $senderStatus;
            $userAccount->action_by = $usernameReciever; // here  is admin
            $userAccount->action_ip = $request->ip();
            $userAccount->action_at = Date('Y-m-d H:i:s');
            $userAccount->save();
        }else{
        	$lastCredit = 0.0;
        	$lastDebit = 0.0;
            // create new account for user
        	$userAccount = new UserAccount();
        	$userAccount->username = $usernameSender;
        	$userAccount->status = $senderStatus;
        	$userAccount->debit = $adjustedAmount;
            $userAccount->action_by = $usernameReciever; // here is admin
            $userAccount->action_ip = $request->ip();
            $userAccount->action_at = Date('Y-m-d H:i:s');
            $userAccount->save();
        }

            //amount
        if(Auth::user()->status =="manager" || Auth::user()->status =="reseller" || Auth::user()->status == 'inhouse'){
        	if(Auth::user()->status == 'inhouse'){
        		$data = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->
        		select('username')->first();
////////////////////////////////////////////////////////////////////////////////////////

        		if($cash_amount == "yes"){
        			$totalTransactionamount = AmountTransactions::where('receiver',$usernameSender)->where('commision','!=','yes')->sum('amount');
        			$totalTransactionrevert = AmountTransactions::where('receiver',$usernameSender)->sum('cash_amount');
        			$totalTransaction = $totalTransactionamount - $totalTransactionrevert;
        			if($totalTransaction >= $recieveAmount ){

        				$transactionAmount = new AmountTransactions();
        				if(Auth::user()->status == 'inhouse'){
        					$transactionAmount->sender = $data->username; 
            // admin

        				}else{
        					$transactionAmount->sender = $usernameReciever; 
        // admin
        				}

                $transactionAmount->receiver = $usernameSender; //username will be store
                $transactionAmount->amount = 0;
                $transactionAmount->cash_amount = $recieveAmount;
                $transactionAmount->last_remaining_amount = 0;
                $transactionAmount->date = Date('Y-m-d H:i:s');
                if(Auth::user()->status == 'inhouse'){
            $transactionAmount->action_by_user = $data->username; // admin

        }else{
        $transactionAmount->action_by_user = $usernameReciever; // admin
    }

    $transactionAmount->action_ip = $request->ip();
    $transactionAmount->comments = $comment;
    $transactionAmount->commision = 'no';
    $transactionAmount->com_amount = 0;
    $transactionAmount->type = 'reversal';
    $transactionAmount->save();

                                // payment transation
    $paymentsTransaction = new PaymentsTransactions();
    $paymentsTransaction->sender = $usernameSender;
    if(Auth::user()->status == 'inhouse'){
            $paymentsTransaction->receiver = $data->username; // admin

        }else{
        $paymentsTransaction->receiver = $usernameReciever; // admin
    }
    $paymentsTransaction->amount = $recieveAmount;

        $paymentsTransaction->current_credit = $userAccount->credit; // 
        $paymentsTransaction->current_debit = $userAccount->debit; // 
        $paymentsTransaction->is_cash = ($paymentType == 'chash') ? 1 : 0;
        $paymentsTransaction->is_bank = ($paymentType == 'bank') ? 1 : 0;
        if($paymentType == 'bank'){
        	$paymentsTransaction->bank_name = $bankName;
        }
        $paymentsTransaction->paid_by = $paidBy;
        $paymentsTransaction->detail = $comment;
        $paymentsTransaction->date = Date('Y-m-d H:i:s');
        $paymentsTransaction->action_by_admin = $usernameReciever; // here is admin
        $paymentsTransaction->action_ip = $request->ip();
        if($cash_amount == "yes"){
        	$paymentsTransaction->type = "reversal";
        }else{
        	$paymentsTransaction->type = "recieve";
        }
        
        $paymentsTransaction->status = 'SAVE';
        $paymentsTransaction->save();

        session()->flash('success',' Successfully Payment Recieved .');
        return redirect()->route('users.billing.index',['status' => 'recieve']);

    }else{

    	session()->flash('error',' Your Transfer is less then you recieve .');
    	return redirect()->route('users.billing.index',['status' => 'recieve']);

    }
}



                    // payment transation
$paymentsTransaction = new PaymentsTransactions();
$paymentsTransaction->sender = $usernameSender;
if(Auth::user()->status == 'inhouse'){
            $paymentsTransaction->receiver = $data->username; // admin

        }else{
        $paymentsTransaction->receiver = $usernameReciever; // admin
    }
    $paymentsTransaction->amount = $recieveAmount;

        $paymentsTransaction->current_credit = $userAccount->credit; // 
        $paymentsTransaction->current_debit = $userAccount->debit; // 
        $paymentsTransaction->is_cash = ($paymentType == 'chash') ? 1 : 0;
        $paymentsTransaction->is_bank = ($paymentType == 'bank') ? 1 : 0;
        if($paymentType == 'cheque'){
        	$paymentsTransaction->check_number = $chequeNo;
        	$paymentsTransaction->bank_name = $bankName;
        }
        if($paymentType == 'bank'){
        	$bankName = $request->get("bankname");
        	$paymentsTransaction->bank_name = $bankName;

        }
        $paymentsTransaction->paid_by = $paidBy;
        $paymentsTransaction->detail = $comment;
        $paymentsTransaction->date = Date('Y-m-d H:i:s');
        $paymentsTransaction->action_by_admin = $usernameReciever; // here is admin
        $paymentsTransaction->action_ip = $request->ip();
        if($cash_amount == "yes"){
        	$paymentsTransaction->type = "reversal";
        }else{
        	$paymentsTransaction->type = "recieve";
        }
        
        $paymentsTransaction->status = 'SAVE';
        $paymentsTransaction->save();


        

        session()->flash('success',' Successfully Payment Recieved .');
        return redirect()->route('users.billing.index',['status' => 'recieve']);




                ///////////////////////

    }
    if($cash_amount == "yes"){
                // amount transaction

    	$totalTransactionamount = AmountTransactions::where('receiver',$usernameSender)->where('commision','!=','yes')->sum('amount');
    	$totalTransactionrevert = AmountTransactions::where('receiver',$usernameSender)->sum('cash_amount');
    	$totalTransaction = $totalTransactionamount - $totalTransactionrevert;
    	if($totalTransaction >= $recieveAmount ){
    		$transactionAmount = new AmountTransactions();
    		if(Auth::user()->status == 'inhouse'){
            $transactionAmount->sender = $data->username; // admin

        }else{
        $transactionAmount->sender = $usernameReciever; // admin
    }

                $transactionAmount->receiver = $usernameSender; //username will be store
                $transactionAmount->amount = 0;
                $transactionAmount->cash_amount = $recieveAmount;
                $transactionAmount->last_remaining_amount = 0;
                $transactionAmount->date = Date('Y-m-d H:i:s');
                if(Auth::user()->status == 'inhouse'){
            $transactionAmount->action_by_user = $data->username; // admin

        }else{
        $transactionAmount->action_by_user = $usernameReciever; // admin
    }

    $transactionAmount->action_ip = $request->ip();
    $transactionAmount->comments = $comment;
    $transactionAmount->commision = 'no';
    $transactionAmount->com_amount = 0;
    $transactionAmount->type = 'reversal';
    $transactionAmount->save();

}else{

	session()->flash('error',' Your Transfer is less then you recieve .');
	return redirect()->route('users.billing.index',['status' => 'recieve']);

}

        //

}

if($cash_amount == "yes"){
	$amountUser = UserAmount::where('username',$usernameSender)->first();
	if($amountUser){
		$lastRemainingAmount = $amountUser->amount;
		$amountUser->amount -= $recieveAmount;
		$amountUser->status = $senderStatus;
		$amountUser->save();
		$lastRemainingAmount = $amountUser->amount;
		$amountUser->amount += $recieveAmount;
		$amountUser->status = $senderStatus;
		$amountUser->save();
	}else{
		$lastRemainingAmount = 0.0;
		$amountUser = new UserAmount();
		$amountUser->username = $usernameSender;
		$amountUser->amount = $recieveAmount;
		$amountUser->status = $senderStatus;
		$amountUser->save();
	} 

}else{
	$amountUser = UserAmount::where('username',$usernameSender)->first();
	if($amountUser){
		$lastRemainingAmount = $amountUser->amount;
		$amountUser->amount += $recieveAmount;
		$amountUser->status = $senderStatus;
		$amountUser->save();
	}else{
		$lastRemainingAmount = 0.0;
		$amountUser = new UserAmount();
		$amountUser->username = $usernameSender;
		$amountUser->amount = $recieveAmount;
		$amountUser->status = $senderStatus;
		$amountUser->save();
	} 
}
        //
}
        // payment transation
$paymentsTransaction = new PaymentsTransactions();
$paymentsTransaction->sender = $usernameSender;
if(Auth::user()->status == 'inhouse'){
            $paymentsTransaction->receiver = $data->username; // admin

        }else{
        $paymentsTransaction->receiver = $usernameReciever; // admin
    }
    $paymentsTransaction->amount = $recieveAmount;

        $paymentsTransaction->current_credit = $userAccount->credit; // 
        $paymentsTransaction->current_debit = $userAccount->debit; // 
        $paymentsTransaction->is_cash = ($paymentType == 'chash') ? 1 : 0;
        $paymentsTransaction->is_bank = ($paymentType == 'bank') ? 1 : 0;
        if($paymentType == 'cheque'){
        	$paymentsTransaction->check_number = $chequeNo;
        	$paymentsTransaction->bank_name = $bankName;
        }
        if($paymentType == 'bank'){
        	$bankName = $request->get("bankname");
        	$paymentsTransaction->bank_name = $bankName;

        }
        $paymentsTransaction->paid_by = $paidBy;
        $paymentsTransaction->detail = $comment;
        $paymentsTransaction->date = Date('Y-m-d H:i:s');
        $paymentsTransaction->action_by_admin = $usernameReciever; // here is admin
        $paymentsTransaction->action_ip = $request->ip();
        $paymentsTransaction->type = "recieve";
        $paymentsTransaction->status = 'POST';
        $paymentsTransaction->save();

        
        // Ledger Report
        $ledgerReport = new LedgerReport();
        $ledgerReport->username = $usernameSender;
        $ledgerReport->debit = 0; // admin
        $ledgerReport->credit = $recieveAmount;
        $ledgerReport->detail = $comment;
        $ledgerReport->date = Date('Y-m-d');
        

        $ledgerReport->save();
        // userAccountPaymentTansactionBrg: storing last status of userAccount
        $userAccountPaymentTransactionBrg = new UserAccountPaymentTransactionBrg();
        $userAccountPaymentTransactionBrg->payment_transaction_id = $paymentsTransaction->id;
        $userAccountPaymentTransactionBrg->last_remaining_credit = $lastCredit;
        $userAccountPaymentTransactionBrg->last_remaining_debit = $lastDebit;
        $userAccountPaymentTransactionBrg->save();
        session()->flash('success',' Successfully Payment Recieved .');
        return redirect()->route('users.billing.index',['status' => 'recieve']);
    }

    public function postReceive(Request $request){
		$id = $request->get('id');
    	$ptDetail = PaymentsTransactions::where('id',$id)->first();
		// 
    	$recieveAmount = $ptDetail->amount;
    	$date = $ptDetail->date;
    	$date2 = date('Y-m-d',strtotime($date));
    	$usernameReciever = Auth::user()->username;
    	$usernameSender = $ptDetail->sender;
    	// 
    	$usernameSender1 = UserInfo::where(['username' => $usernameSender])->first();
    	$senderStatus = $usernameSender1['status'];
    	// 
    	$paidBy = $ptDetail->paidBy;
    	// 
    	$comment = $ptDetail->detail;
    	// 
    	$cash_amount = $ptDetail->type;
    	// 
    	$paymentType = $ptDetail->paymentType;
    	if($paymentType == 'cheque'){
    		// 
    		$bankName = $ptDetail->bankname;
    		// 
    		$chequeNo = $ptDetail->checkNo;
    	}
		// adjust amount: discount will be added from recieved amount
    	$adjustedAmount = $recieveAmount ;
		// recieveAmount: wiil be added as debit in user account but credit will be 0 if not then dredit will be adjusted first
    	$userAccount = UserAccount::where('username',$usernameSender)->first();
    	if($userAccount){
			// user account exists
    		$lastCredit = $userAccount->credit;
    		$lastDebit = $userAccount->debit;
    		$adjustedAmount -= $lastCredit;
    		if($adjustedAmount > 0){
    			$userAccount->debit += $adjustedAmount;
    			$userAccount->credit = 0;
    		}elseif($adjustedAmount < 0){
				$userAccount->credit = abs($adjustedAmount); // change -123456 to 123456
				$userAccount->debit = 0;
			}elseif($adjustedAmount == 0){
				$userAccount->credit = 0;
				$userAccount->debit += 0;
			}
			$userAccount->status = $senderStatus;
			$userAccount->action_by = $usernameReciever; // here  is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d H:i:s');
			$userAccount->save();
		}else{
			$lastCredit = 0.0;
			$lastDebit = 0.0;
			// create new account for user
			$userAccount = new UserAccount();
			$userAccount->username = $usernameSender;
			$userAccount->status = $senderStatus;
			$userAccount->debit = $adjustedAmount;
			$userAccount->action_by = $usernameReciever; // here is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d H:i:s');
			$userAccount->save();
		}
			//amount
		if(Auth::user()->status =="manager" || Auth::user()->status =="reseller" || Auth::user()->status == 'inhouse'){
			// if(Auth::user()->status == 'inhouse'){}
				if($cash_amount == "reversal"){
					////////////
					$amountUser = UserAmount::where('username',$usernameSender)->first();
					if($amountUser){
						$lastRemainingAmount = $amountUser->amount;
						$amountUser->amount -= $recieveAmount;
						$amountUser->status	= $senderStatus;
						$amountUser->save();
						$lastRemainingAmount = $amountUser->amount;
						$amountUser->amount += $recieveAmount;
						$amountUser->status	= $senderStatus;
						$amountUser->save();
					}else{
						$lastRemainingAmount = 0.0;
						$amountUser = new UserAmount();
						$amountUser->username = $usernameSender;
						$amountUser->amount = $recieveAmount;
						$amountUser->status	= $senderStatus;
						$amountUser->save();
					} 
				   // payment transation
					$paymentsTransaction = PaymentsTransactions::where('sender',$usernameSender)->where('type','reversal')->where('date',$date)->first();
					$paymentsTransaction['status'] = "POST";
					$paymentsTransaction['type'] = "recieve";
		// $paymentsTransaction->posted_by = Auth::user()->username;
					$paymentsTransaction->save();
		// Ledger Report
					$ledgerReport = new LedgerReport();
					$ledgerReport->username = $usernameSender;
		$ledgerReport->debit = 0; // admin
		$ledgerReport->credit = $recieveAmount;
		$ledgerReport->detail = $comment;
		$ledgerReport->date = $date2;
		$ledgerReport->save();
	}else{
		$amountUser = UserAmount::where('username',$usernameSender)->first();
		if($amountUser){
			$lastRemainingAmount = $amountUser['amount'];
			$amountUser->amount += $recieveAmount;
			$amountUser->status	= $senderStatus;
			$amountUser->save();
		}else{
			$lastRemainingAmount = 0.0;
			$amountUser = new UserAmount();
			$amountUser->username = $usernameSender;
			$amountUser->amount = $recieveAmount;
			$amountUser->status	= $senderStatus;
			$amountUser->save();
		} 
						   // payment transation
		$paymentsTransaction = PaymentsTransactions::where('sender',$usernameSender)->where('type','recieve')->where('date',$date)->first();
		$paymentsTransaction['status'] = "POST";
		$paymentsTransaction['type'] = "recieve";
		$paymentsTransaction->save();
		// Ledger Report
		$ledgerReport = new LedgerReport();
		$ledgerReport->username = $usernameSender;
		$ledgerReport->debit = 0; // admin
		$ledgerReport->credit = $recieveAmount;
		$ledgerReport->detail = $comment;
		$ledgerReport->date = Date('Y-m-d');
		$ledgerReport->save();
		// userAccountPaymentTansactionBrg: storing last status of userAccount
		$userAccountPaymentTransactionBrg = new UserAccountPaymentTransactionBrg();
		$userAccountPaymentTransactionBrg->payment_transaction_id = $paymentsTransaction->id;
		$userAccountPaymentTransactionBrg->last_remaining_credit = $lastCredit;
		$userAccountPaymentTransactionBrg->last_remaining_debit = $lastDebit;
		// $userAccountPaymentTransactionBrg->posted_by = Auht::user()->username;
		$userAccountPaymentTransactionBrg->save();
	}
}
return redirect()->back();
}

////////////////////////////////
public function recieptReceive(Request $request){
		// data from request[sender(admin),reciever(user)]
			// $recieveAmount = $request->get('amount');
			//  $date = $request->get('date');
			// $date2 = date('Y-m-d',strtotime($date));
			// $usernameSender = $request->get('username');
			// $paidBy = $request->get('paidBy');
	$id = $request->get('id');

	$paymentsTransaction = PaymentsTransactions::where('id',$id)->first();
	$currentlogin = $paymentsTransaction['receiver'];
	$usernameSender = $paymentsTransaction['sender'];
	$recieveAmount = $paymentsTransaction['amount'];
	$paidBy = $paymentsTransaction['paid_by'];
	$date = $paymentsTransaction['date'];
	$inwords = new NumbersToWords();
	$test = $inwords->convert($recieveAmount);
	$test = str_replace(',','',$test);
	$pdf= PDF::loadView('users.billing.cash_reciept_PDF',[
		'usernameSender' => $usernameSender,
		'recieveAmount' => $recieveAmount,
		'paidBy' => $paidBy,
		'currentlogin' => $currentlogin,
		'id' => $id,
		'test' => $test,
		'date' => $date

	]);
	return $pdf->stream($usernameSender.'.pdf');	
}
	/////////////////////////////


public function ipBill(Request $request){
		// data from request[sender(admin),reciever(user)]
	$recieveAmount = $request->get('amount');
	$date = $request->get('date');
	$date2 = date('Y-m-d',strtotime($date));

		//	$usernameReciever = Admin::where('status','super')->first()->username; // get  username of superadmin



	$username = $request->get('username');


	$userAmount = UserAmount::where('username',$username)->first();

	$amount = $userAmount['amount'];
	if($amount >= $recieveAmount){
		$newBalance = $amount - $recieveAmount;
		$userAmount->amount = $newBalance;
		$userAmount->save();
		$staticipBill = StaticipBill::where('username',$username)->where('date',$date)->first();
		$staticipBill->status = "paid";
		$staticipBill->save();
		return "paid";

	}else{
		return "not able";
	}


	






	

}




	////////////////////////////////////

private function storeNoneCash(Request $request, $status){
	if(Auth::user()->status == 'inhouse'){
		$data = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->
		select('username')->first();
	}
		// data from request[sender(admin),reciever(user)]
	$recieveAmount = $request->get('amount');
	$userAmount = UserAmount::where([
		"username" => Auth::user()->resellerid
	])->first();
			//
	if($userAmount){
		$discount = $userAmount->discount;
	}else{
		$discount = 1;
	}

            //
	$recieveAmount = $recieveAmount / $discount;

		//	$usernameReciever = Admin::where('status','super')->first()->username; // get username of superadmin


	$usernameReciever = Auth::user()->username;
	$usernameSender = $request->get('username');
	$usernameSender1 = UserInfo::where(['username' => $usernameSender])->first();
	$senderStatus = $usernameSender1->status;

	$paidBy = $request->get('paidBy');
	$comment = $request->get('comment');
	$paymentType = $request->get('paymentType');
	if($paymentType == 'cheque'){
		$bankName = $request->get("bankname");
		$chequeNo = $request->get("checkNo");
	}

		//

		// adjust amount: discount will be added from recieved amount
	$adjustedAmount = $recieveAmount ;

		// recieveAmount: wiil be added as debit in user account but credit will be 0 if not then dredit will be adjusted first
	$userAccount = UserAccount::where('username',$usernameSender)->first();

	if($userAccount){
			// user account exists
		$lastCredit = $userAccount->credit;
		$lastDebit = $userAccount->debit;
		$adjustedAmount -= $lastCredit;
		if($adjustedAmount > 0){
			$userAccount->debit += $adjustedAmount;
			$userAccount->credit = 0;
		}elseif($adjustedAmount < 0){
				$userAccount->credit = abs($adjustedAmount); // change -123456 to 123456
				$userAccount->debit = 0;
			}elseif($adjustedAmount == 0){
				$userAccount->credit = 0;
				$userAccount->debit += 0;
			}
			$userAccount->status = $senderStatus;
			$userAccount->action_by = $usernameReciever; // here  is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d H:i:s');
			$userAccount->save();
		}else{
			$lastCredit = 0.0;
			$lastDebit = 0.0;
			// create new account for user
			$userAccount = new UserAccount();
			$userAccount->username = $usernameSender;
			$userAccount->status = $senderStatus;
			$userAccount->debit = $adjustedAmount;
			$userAccount->action_by = $usernameReciever; // here is admin
			$userAccount->action_ip = $request->ip();
			$userAccount->action_at = Date('Y-m-d H:i:s');
			$userAccount->save();
		}

		//amount
		$amountUser = UserAmount::where('username',$usernameSender)->first();
		if($amountUser){
			$lastRemainingAmount = $amountUser->amount;
			$amountUser->amount += $recieveAmount;
			$amountUser->status	= $senderStatus;
			$amountUser->save();
		}else{
			$lastRemainingAmount = 0.0;
			$amountUser = new UserAmount();
			$amountUser->username = $usernameSender;
			$amountUser->amount = $recieveAmount;
			$amountUser->status	= $senderStatus;
			$amountUser->save();
		} 
		//
		
		// payment transation
		$paymentsTransaction = new PaymentsTransactions();
		$paymentsTransaction->sender = $usernameSender;
		if(Auth::user()->status == 'inhouse'){
			$paymentsTransaction->receiver = $data->username; // admin

		}else{
		$paymentsTransaction->receiver = $usernameReciever; // admin
	}
	$paymentsTransaction->amount = $recieveAmount;

		$paymentsTransaction->current_credit = $userAccount->credit; // 
		$paymentsTransaction->current_debit = $userAccount->debit; // 
		$paymentsTransaction->is_cash = ($paymentType == 'chash') ? 1 : 0;
		$paymentsTransaction->is_bank = ($paymentType == 'bank') ? 1 : 0;
		if($paymentType == 'cheque'){
			$paymentsTransaction->check_number = $chequeNo;
			$paymentsTransaction->bank_name = $bankName;
		}
		$paymentsTransaction->paid_by = $paidBy;
		$paymentsTransaction->detail = $comment;
		$paymentsTransaction->date = Date('Y-m-d H:i:s');
		$paymentsTransaction->action_by_admin = $usernameReciever; // here is admin
		$paymentsTransaction->action_ip = $request->ip();
		$paymentsTransaction->type = "none_cash";
		$paymentsTransaction->save();


		// Ledger Report
		$ledgerReport = new LedgerReport();
		$ledgerReport->username = $usernameSender;
		$ledgerReport->debit = 0; // admin
		$ledgerReport->credit = $recieveAmount;
		$ledgerReport->detail = $comment;
		$ledgerReport->date = Date('Y-m-d');

		$ledgerReport->save();
		
		// userAccountPaymentTansactionBrg: storing last status of userAccount
		$userAccountPaymentTransactionBrg = new UserAccountPaymentTransactionBrg();
		$userAccountPaymentTransactionBrg->payment_transaction_id = $paymentsTransaction->id;
		$userAccountPaymentTransactionBrg->last_remaining_credit = $lastCredit;
		$userAccountPaymentTransactionBrg->last_remaining_debit = $lastDebit;
		$userAccountPaymentTransactionBrg->save();
		session()->flash('success',' Successfully Payment Recieved .');
		return redirect()->route('users.billing.index',['status' => 'nonecash']);
	}

	public function CheckAmount(Request $request){
		$username = $request->get("username");

		$transferAmountSum = AmountTransactions::where('receiver',$username)->sum('amount');

		$transferAmountSumReversal = AmountTransactions::where('receiver',$username)->sum('cash_amount');

		$totalAmount = $transferAmountSum - $transferAmountSumReversal;

		if($totalAmount <= 0){
			return "unblock";
		}else{
			return "block";
		}
	}




	/*
	* load the list of users: their status wise
	* return the list[username]: of selected user status
	*/
	public function loadUser(Request $request){
		$status = $request->get("status");
		$userCollection = UserInfo::select('username')->where('status', $status)->get();
		return $userCollection;
	}
	
}
