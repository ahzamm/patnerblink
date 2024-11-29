<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\UserAmount;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadAcct;
use PDF;
use App\model\Users\userAccess;
use App\model\Users\AccessPermission;
use App\model\Users\Card;
use App\model\Users\UserStatusInfo;
use App\MyFunctions;

//
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
       $currentUser = Auth::user()->status;
       if($currentUser == "manager"){
          $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
      }elseif($currentUser == "reseller"){
          $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
      }elseif($currentUser == "dealer"){
          $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
      }elseif($currentUser == "subdealer"){
          $userCollection = UserInfo::select('username')->where(['username'=> Auth::user()->username ])->get();
      }

      return view('users.billing.amount_transaction',[
        'userCollection' => $userCollection,
        'isSearched' => false
    ]);
  }break;
  case "summary" : {
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
				// if($state[18] == 0 && $cModule[18] == 'Billing Details'){
				//   return redirect()->route('users.dashboard');
				// }
        if(!MyFunctions::check_access('Billing Detail',Auth::user()->id)){
            return 'Permission Denied';
        }
    }
            //   $subcheck = AccessPermission::where('userid',Auth::user()->id)->where('accessid','1')->first();
            //   if(!empty($subcheck)){
            //     $checkAccess = $subcheck["access"];
            //     if($checkAccess == 0){
            //         return redirect()->route('users.dashboard');
            //     }
            //   }

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
}break;

case "detail" : {

    $currentUser = Auth::user()->status;
    if($currentUser == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($currentUser == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }elseif($currentUser == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
    }elseif($currentUser == "subdealer"){
        $userCollection = UserInfo::select('username')->where(['username'=> Auth::user()->username ])->get();
    }elseif($currentUser == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }


    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => false,

    ]);
}break;
case "transferreport" : {
    $userCollection='';

    $status = Auth::user()->status;
    if($status == "manager"){
        $userCollection = UserInfo::select('username')->where(['status' => 'manager','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($status == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }elseif($status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
    }elseif($status == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }

    return view('users.billing.transfer_report',[
        'userCollection' => $userCollection
    ]);
}break;
case "dealerdetail" : {

    $currentUser = Auth::user()->status;
    if($currentUser == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($currentUser == "reseller" || $currentUser =='inhouse'){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }elseif($currentUser == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','dealerid' => Auth::user()->dealerid ])->get();
    }elseif($currentUser == "subdealer"){
        return redirect()->route('users.dashboard');
    }

     $resellerprofileRate = ResellerProfileRate::where('resellerid' ,Auth::user()->resellerid)->first();
    //
    /////////// profit 
    if($resellerprofileRate->allow_auto_profit == 'yes'){
        return view('users.billing.reseller_dealer_profit_details',[
            'isSearched' => false,
        ]);  
    }else{
        return view('users.billing.dealer_subdealer_details',[
            'userCollection' => $userCollection,
            'isSearched' => false,
        ]);
    }

}break; 
case "margin" : {

    $currentUser = Auth::user()->status;
    if($currentUser == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($currentUser == "reseller" || $currentUser =='inhouse'){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }elseif($currentUser == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
    }elseif($currentUser == "subdealer"){
        return redirect()->route('users.dashboard');
    }


    return view('users.billing.margin_report',[
        'userCollection' => $userCollection,
        'isSearched' => false,

    ]);
}break;
case "reportsummary" : {

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
				// if($state[28] == 0 && $cModule[28] == 'Billing Summary'){
				//   return redirect()->route('users.dashboard');
				// }

        if(!MyFunctions::check_access('Billing Summary',Auth::user()->id)){
            return 'Permission Denied';
        }

    }
            //   $subcheck = AccessPermission::where('userid',Auth::user()->id)->where('accessid','2')->first();
            //   if(!empty($subcheck)){
            //     $checkAccess = $subcheck["access"];
            //     if($checkAccess == 0){
            //         return redirect()->route('users.dashboard');
            //     }
            //   }

    $currentUser = Auth::user()->status;
    if($currentUser == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($currentUser == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }elseif($currentUser == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
    }elseif($currentUser == "subdealer"){
        $userCollection = UserInfo::select('username')->where(['status'=> 'subdealer','sub_dealer_id' => Auth::user()->sub_dealer_id ])->get();

    }elseif($currentUser == "trader"){
        $userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
    }elseif($currentUser == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
    }


    return view('users.billing.report_summary',[
        'userCollection' => $userCollection,
        'isSearched' => false,

    ]);
}break;
case "reportsummarydetails" : {

    $status = Auth::user()->status;
    $userCollection='';
    if($status == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($status == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }elseif($status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
    }elseif($status == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }
    return view('users.billing.report_details_summery',[
     'userCollection' => $userCollection
 ]);

}break;
case "commisionsummarydetails" : {

    $status = Auth::user()->status;
    $userCollection='';
    if($status == "manager"){
        $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    }elseif($status == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }elseif($status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
    }elseif($status == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }
    return view('users.billing.commision_details_summery',[
     'userCollection' => $userCollection
 ]);

}break;
case "advance" : {

    $userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
    return view('users.billing.advance_summary',[
        'userCollection' => $userCollection,
        'isSearched' => false
    ]);
}break;
case "invoice" : {

    $userCollection = UserInfo::select('username')->where('status', 'dealer')->where('receipt', 'logon')->get();
    return view('users.billing.invoice_summary',[
        'userCollection' => $userCollection,
        'isSearched' => false
    ]);
}break;
case "dealersummarydetails" : {

  $userCollection='';
  $status = Auth::user()->status;
  if($status == "reseller" || $status == "inhouse"){
    $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
}elseif($status == "dealer"){
    $userCollection = UserInfo::select('username')->where(['status' => 'dealer','dealerid' =>Auth::user()->dealerid])->get();
}elseif($status == "manager"){
    $userCollection = UserInfo::select('username')->where(['status' => 'reseller','manager_id' =>Auth::user()->manager_id])->get();
}else{
    return redirect()->route('users.dashboard'); 
}
return view('users.billing.dealer_details_summery',[
 'userCollection' => $userCollection
]);

}break;
case "invoice" : {

    $status = Auth::user()->status;
    if($status == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }elseif($status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
    }
    return view('users.billing.invoice_summary',[
     'userCollection' => $userCollection
 ]);

}break;
case "profitsummary" : {

    $status = Auth::user()->status;
    if($status == "reseller"){
        $userCollection = UserInfo::select('username')->where(['status' => 'reseller','resellerid' =>Auth::user()->resellerid])->get();
    }elseif($status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' =>Auth::user()->dealerid])->get();
    }elseif($status == 'inhouse'){
        $userCollection = UserInfo::select('username')->where(['status' => 'dealer','resellerid' =>Auth::user()->resellerid])->get();
    }
    return view('users.billing.profit_summery',[
     'userCollection' => $userCollection
 ]);

}break;
case "history" : {
    $id = Auth::user()->id;
    $userId = 'user'.$id;
    $pModule = array();
    $cModule = array();
    $state = array();
    $userId = 'user'.Auth::user()->id;
    if(Auth::user()->status =='inhouse'){

                // $loadData = userAccess::select($userId,'parentModule','childModule')->get();
                // if(!empty($loadData)){
                //     foreach ($loadData as  $value) {
                //     $state[] = $value[$userId];
                //     $pModule[] = $value['parentModule'];
                //     $cModule[] = $value['childModule'];
                //     }

                // }
                // if($state[19] == 0 && $cModule[19] == 'Current Balance'){
                //     return redirect()->route('users.dashboard');
                // }
        if(!MyFunctions::check_access('Current Balance',Auth::user()->id)){
            return 'Permission Denied';
        }

    }

    $userCollection = array();

               // $dealerid = Auth::user()->dealerid;
    $currentStatus = Auth::user()->status;
    if($currentStatus == "reseller"){
        $userDealer = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'dealer'])->select('username','dealerid')->get();
    }elseif($currentStatus == "manager"){
        $userDealer = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'reseller'])->select('username')->get();
    }elseif($currentStatus == "dealer" ){
        $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'subdealer'])->select('username','dealerid','sub_dealer_id')->get();
    }elseif($currentStatus == "subdealer" ){
        $userDealer = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'trader'])->select('username','dealerid','sub_dealer_id')->get();
    }elseif($currentStatus == 'inhouse'){
        $userDealer = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'dealer'])->select('username','dealerid','sub_dealer_id')->get();
    }
    foreach ($userDealer as $value) {

        if($currentStatus == "reseller"){
            $online = UserAmount::where(['username' => $value->username,'status' => 'dealer'])->get();
        }elseif($currentStatus == "dealer"){
            $billingType = DealerProfileRate::where('dealerid',$value->dealerid)->first();
            if($billingType->billing_type == 'card'){
                $online = Card::where('dealerid',$value->dealerid)->where('sub_dealer_id',$value->sub_dealer_id)->where('status','unused')->select(DB::raw('sub_dealer_id,sum(dealerrate) rates'))->groupby(['sub_dealer_id'])->get();
                        // dd($rates);
            }else{
                $online = UserAmount::where(['username' => $value->username,'status' => 'subdealer'])->get();
            }
        }elseif($currentStatus == "manager"){
            $online = UserAmount::where(['username' => $value->username,'status' => 'reseller'])->get();
        }elseif($currentStatus == 'inhouse'){
            $online = UserAmount::where(['username' => $value->username,'status' => 'dealer'])->get();
        }

        foreach ($online as $value) {
         $userCollection[] = $value;
     }
 }



 return view('users.billing.amount_history',[
    'userCollection' => $userCollection
]);
}break;
default :{
    return redirect()->route('users.dashboard');
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
        $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
        return view('users.billing.amount_transaction',[
            'userCollection' => $userCollection,
            'isSearched' => true,
            'selectedUser' => $selectedUser,
            'monthlyPayableAmount' => $monthlyPayableAmount,
            'monthlyBillingEntries' => $monthlyBillingEntries
        ]);

    }
    elseif($selectedUser->status == 'subdealer'){
            // report summary


            // report summary
        $monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
        ->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->sum('subdealerrate');
            // monthly Billing Report
        $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
        ->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->get();

        if(Auth::user()->status == "subdealer"){
            $userCollection = UserInfo::select('username')->where(['username'=> Auth::user()->username ])->get();

        }else{
            $userCollection = UserInfo::select('username')->where(['status' => 'subdealer','dealerid' => Auth::user()->dealerid])->get();
        }

        return view('users.billing.amount_transaction',[
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
     $userCollection = UserInfo::select('username')->where(['status' => 'reseller','manager_id' => Auth::user()->manager_id])->get();
     return view('users.billing.amount_transaction',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);
 }elseif ($selectedUser->username == Auth::user()->username) {
     $monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')
     ->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->sum('rate');

			// report summary
			// monthly Billing Report
     $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')
     ->where([['date', '<=', $date],['date', '>=', date('Y-m-d',strtotime($date.' -1 months'))]])->with('user_info')->get();	
     $userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
     return view('users.billing.amount_transaction',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);
 }
       // return $monthlyBillingEntries;
}



    ////////////////////////////////
public function billingSummary(Request $request)
{    
    //
    if(!empty($request->get('trader_data'))){
        $username = $request->get('trader_data');
    }else if(!empty($request->get('dealer_data'))){
        $username = $request->get('dealer_data');
    }else if(!empty($request->get('reseller_data'))){
        $username = $request->get('reseller_data');
    }else{
        $username = Auth::user()->username;
    }

    //
    $rows = array();
    $netPayable = 0;
    $date = $request->get('datetimes');
        //
    $range = explode('-', $date);
    $from = date("Y-m-d H:i:s", strtotime($range[0]));
    $to = date("Y-m-d H:i:s", strtotime($range[1]));
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
   if(!empty($request->get('own')) && (Auth::user()->status == 'dealer') ){

    $monthlyBillingEntries = AmountBillingInvoice::where('dealerid', $selectedUser->dealerid)->where('sub_dealer_id', NULL)
    ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get();   
}
//
foreach($monthlyBillingEntries as $key => $entry) {

    $rows[$key]['username'] = $entry->username;
    $rows[$key]['name'] = $entry['name'];
    $rows[$key]['fullname'] = $entry->user_info->firstname.' '.$entry->user_info->lastname;
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
        //
    if($entry['company_rate'] == 'yes'){

        if($selectedUser->status == 'manager'){
         $rows[$key]['wallet'] = $entry['m_rate'];
     }else if($selectedUser->status == 'reseller'){
         $rows[$key]['wallet'] = $entry['r_rate'];
     }else if($selectedUser->status == 'dealer'){
             // $rows[$key]['wallet'] = $entry['dealerrate'];
        if(empty($entry['sub_dealer_id'])){
            $rows[$key]['wallet'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
        }else{
                // $rows[$key]['wallet'] = $entry['dealer_gross_amount'] + $entry['sst'] + $entry['adv_tax'];
            $rows[$key]['wallet'] = $entry['wallet_deduction'] - $entry['margin'];
        }
            //
    }else if($selectedUser->status == 'subdealer'){
             // $rows[$key]['wallet'] = $entry['subdealerrate'];
     $rows[$key]['wallet'] = $entry['fll_charges'] + $entry['cvas_charges'] + $entry['tip_charges'] + $entry['sst'] + $entry['adv_tax'];
 }

}else{

    if($selectedUser->status == 'manager'){
     $rows[$key]['wallet'] = $entry['m_acc_rate'];
 }else if($selectedUser->status == 'reseller'){
     $rows[$key]['wallet'] = $entry['r_acc_rate'];
 }else if($selectedUser->status == 'dealer'){
     $rows[$key]['wallet'] = ($entry['d_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2;
 }else if($selectedUser->status == 'subdealer'){
    $rows[$key]['wallet'] = ($entry['s_acc_rate'] + $entry['sst'] + $entry['adv_tax']) / 2; 
}

}

$netPayable = $netPayable + $rows[$key]['wallet'];

}
    //
if(!empty($request->get('download')))
{
   $this->billingSummary_csv($rows,$netPayable);
}
    //
return view('users.billing.billing_summary', [
    'isSearched' => true,
    'selectedUser' => $selectedUser,
    'monthlyBillingEntries' => $monthlyBillingEntries,
    'rows' => $rows,
    'netPayable' => $netPayable,
]);

}
 /////////////////////////////////
 /////////////////////////////////////////

public function billingSummary_csv($rows, $netPayable)
{
    $filename = Auth::user()->username . "(Billing-Details-" . date('d-M-Y') . "[" . date('h:i:A') . "]" . ".csv";
    $delimiter = ",";
    $f = fopen('php://memory', 'w');
    $fields = array('S.No#', 'Consumer ID','Reseller (ID)', 'Contractor (ID)', 'Trader (ID)', 'Internet Profile', 'Service Billing (Start Date & Time)', 'Service Billing (End Date)', 'Wallet Deduction Amount (PKR)');
    fputcsv($f, $fields, $delimiter);
    $num = 1;
    foreach ($rows as $row) {
            //
        $margin_dealer = (($row['profileAmount']) - $row['rate']) * $row['filerTax'];
            //
        $card_expire = 0;
        $user_expire = UserStatusInfo::where('username', $row['username'])->first();
        $card_expire = $user_expire['card_expire_on'];
        $data8 = date("Y-M-d", strtotime($card_expire));
        $data7 = date("Y-M-d H:i:s", strtotime($row['chargeOn']));
        $lineData = array($num, $row['username'],$row['resellerid'], $row['dealerid'], $row['sub_dealer_id'], $row['name'], $data7, $data8, $row['wallet']);
        fputcsv($f, $lineData, $delimiter);
        $num++;
    }
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    fpassthru($f);
    exit;
}

    /////////////////////////////////

public function reportSummary(Request $request){
        // 
    $username = $request->get('username');
    $bildate = $request->get('date');
    $status = Auth::user()->status;

    $bildate= date('2019-05-10');

    $ebd=explode('-', $bildate);
    if($ebd[2] == 10){
       return $lastbilldate = date("Y-m-25", strtotime("-1 months", strtotime($bildate)));
   }else{
               // $lastbilldate = date("Y-m-10", strtotime("-15 days", strtotime($bildate)));
       $lastbilldate = date("Y-m-10", strtotime($bildate));
   }
             // $lastbilldate= date('2019-04-25');


   if($status == "manager"){
    $total = array();
    $user_data = UserInfo::where([ 'username' => $username])->first();
    $resellerid = $user_data->resellerid;
    $monthlyBillingEntries = ResellerProfileRate::where(['resellerid' => $resellerid])->orderBy('groupname')->get();
    $monthlyBillingEntries1 = ManagerProfileRate::where(['manager_id' => Auth::user()->manager_id])->orderBy('groupname')->get();

    foreach($monthlyBillingEntries as $data){
        $groupname = $data->groupname;
        $rate = $data->rate;


        $lite = AmountBillingInvoice::where('resellerid' , $resellerid)
        ->where('DATE(charge_on)','<',DATE($bildate.' 12:00:00'))->where('DATE(charge_on)','>',DATE($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

        $total[] = $rate*$lite;



    }
    $total_amount = AmountBillingInvoice::where('resellerid' , $resellerid)
    ->where('DATE(charge_on)','<',DATE($bildate.' 12:00:00'))->where('DATE(charge_on)','>',DATE($lastbilldate.' 12:00:00'))->sum('rate');

    $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id' => Auth::user()->manager_id ])->get();
    $userCollection1 = UserInfo::where('status', 'reseller')->where('resellerid','=',$resellerid)->first();

}elseif($status == "reseller"){
   $total = array();
   $user_data = UserInfo::where([ 'username' => $username])->first();
   $dealerid = $user_data->dealerid;
   $monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
   $monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

   foreach($monthlyBillingEntries as $data){
    $groupname = $data->groupname;
    $rate = $data->rate;


    $lite = AmountBillingInvoice::where('dealerid' , $dealerid)
    ->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>',
        DATE($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

    $total[] = $lite;



}
            // return $total;
$total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>',
    DATE($lastbilldate.' 12:00:00'))->sum('dealerrate');

$userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
$userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();
}elseif($status == "dealer"){

    $selectUser = $request->get('username');
    if($selectUser == "own"){
        $selectUserData =  UserInfo::where([ 'username' => Auth::user()->username,'status' => 'dealer'])->first();
        $total = array();

        $dealerid = $selectUserData->dealerid;
        $monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->orderBy('groupname')->get();
        $monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

        foreach($monthlyBillingEntries as $data){
            $groupname = $data->groupname;
            $rate = $data->rate;


            $lite = AmountBillingInvoice::where('dealerid' , $dealerid)
            ->where('charge_on','<',date($bildate.' 12:00:00'))->where('charge_on','>',
                date($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

            $total[] = $lite;

            

        }
            // return $total;
        $total_amount = AmountBillingInvoice::where('dealerid' , $dealerid)
        ->where('charge_on','<',date($bildate.'12:00:00'))->where('charge_on','>',
            date($lastbilldate.' 12:00:00'))->sum('dealerrate');

        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
        $userCollection1 = UserInfo::where('status', 'dealer')->where('dealerid','=',$dealerid)->first();




    }else{



              /////subdealer work

        $total = array();
        $user_data = UserInfo::where([ 'username' => $username,'dealerid' => Auth::user()->dealerid])->first();
        $sub_dealer_id = $user_data->sub_dealer_id;
        $monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
        $monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

        foreach($monthlyBillingEntries as $data){
            $groupname = $data->groupname;
            $rate = $data->rate;


            $lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
            ->where('charge_on','<',date($bildate.' 12:00:00'))->where('charge_on','>',
                date($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

            $total[] = $lite;

            

        }
            // return $total;
        $total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
        ->where('charge_on','<',date($bildate.' 12:00:00'))->where('charge_on','>',
            date($lastbilldate.' 12:00:00'))->sum('subdealerrate');

        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
        $userCollection1 = UserInfo::where('status', 'subdealer')->where('sub_dealer_id','=',$sub_dealer_id)->first();

    }

}elseif($status == "subdealer"){


    $total = array();
    $user_data = UserInfo::where([ 'username' => $username,'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
    $sub_dealer_id = $user_data->sub_dealer_id;
    $monthlyBillingEntries = SubdealerProfileRate::where(['sub_dealer_id' => $sub_dealer_id])->orderBy('groupname')->get();
    $monthlyBillingEntries1 = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->orderBy('groupname')->get();

    foreach($monthlyBillingEntries as $data){
        $groupname = $data->groupname;
        $rate = $data->rate;


        $lite = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
        ->where('charge_on','<',date($bildate.' 12:00:00'))->where('charge_on','>',
            date($lastbilldate.' 12:00:00'))->where('profile','=',$groupname)->count();

        $total[] = $lite;



    }
            // return $total;
    $total_amount = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
    ->where('charge_on','<',date($bildate.' 12:00:00'))->where('charge_on','>',
        date($lastbilldate.' 12:00:00'))->sum('subdealerrate');

    $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','sub_dealer_id' => Auth::user()->sub_dealer_id ])->get();
    $userCollection1 = UserInfo::where('username', $username)->where('sub_dealer_id','=',$sub_dealer_id)->first();

}






            //return $total;







return view('users.billing.report_summary',[
    'isSearched' => true,
    'total' => $total,
    'userCollection' => $userCollection,
    'total_amount' => $total_amount,
    'monthlyBillingEntries' => $monthlyBillingEntries,
    'userCollection1' => $userCollection1

]);

}


///////
    // dealer detail
public function marginReport(Request $request)
{
    $username = $request->get('username');
    $subname = $request->get('subname');
    $trname = $request->get('trname');
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

    }
}


$status = Auth::user()->status;
if($status == "manager"){

    $selectedUser = UserInfo::where('username',$username)->first();


    $selectedUser = UserInfo::where('username',$username)->first();
    $monthlyPayableAmount = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('rate');
    $monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();


    $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id'=>Auth::user()->manager_id])->get();

    return view('users.billing.margin_report',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);

}elseif($status == "reseller"){

    $selectedUser = UserInfo::where('username',$username)->first();


    $selectedUser = UserInfo::where('username',$username)->first();
    $monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('dealerrate');
    $monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();


    $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid'=>Auth::user()->resellerid])->get();

    return view('users.billing.margin_report',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);

}elseif($status == "dealer"){
    $selectedUser = UserInfo::where('username',$username)->first();

    $selectedUser = UserInfo::where('username',$username)->first();
    $monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('dealerrate');
    $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

    $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid'=>Auth::user()->dealerid])->get();
    return view('users.billing.margin_report',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);



}
}




// dealer detail
public function dealersubdealerdetails(Request $request)
{
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
    $netProfitMargin = 0;
    $date = $request->get('datetimes');
        //
    $range = explode('-', $date);
    $from = date("Y-m-d H:i:s", strtotime($range[0]));
    $to = date("Y-m-d H:i:s", strtotime($range[1]));
        //
    $selectedUser = UserInfo::where('username', $username)->first();
        //
    $monthlyBillingEntries = AmountBillingInvoice::where($whereArray)
    ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       //  if ($selectedUser->status == 'manager'){
       //     $monthlyBillingEntries = AmountBillingInvoice::where('manager_id', $selectedUser->manager_id)
       //     ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       // }else if ($selectedUser->status == 'reseller'){
       //     $monthlyBillingEntries = AmountBillingInvoice::where('resellerid', $selectedUser->resellerid)
       //     ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       // }else if ($selectedUser->status == 'dealer'){
       //     $monthlyBillingEntries = AmountBillingInvoice::where('dealerid', $selectedUser->dealerid)
       //     ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       // }else if ($selectedUser->status == 'subdealer'){
       //     $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id', $selectedUser->sub_dealer_id)
       //     ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
       // }
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

    //    if($selectedUser->status == 'reseller'){
    //     $rows[$key]['profitMargin'] = $rows[$key]['reseller_rate'] - $rows[$key]['manager_rate'];
    // }else if($selectedUser->status == 'dealer'){
    //     $rows[$key]['profitMargin'] = $rows[$key]['dealer_rate'] - $rows[$key]['reseller_rate'];
    // }else if($selectedUser->status == 'subdealer'){
    //     $rows[$key]['profitMargin'] = $rows[$key]['subdealer_rate'] - $rows[$key]['dealer_rate'];
    // }

    // if($entry['company_rate'] == 'no'){ ///// profit margin x2 when consumer rate
    //     $rows[$key]['profitMargin'] = $rows[$key]['profitMargin'] * 2;
    // }

 $netProfitMargin = $netProfitMargin + $rows[$key]['profitMargin'];

}

    //
 //
if (!empty($request->get('download'))) {
    $this->margin_details($rows, $netProfitMargin);
}
//
//
return view('users.billing.dealer_subdealer_details', [
    'isSearched' => true,
    'selectedUser' => $selectedUser,
    'monthlyBillingEntries' => $monthlyBillingEntries,
    'rows' => $rows,
    'netProfitMargin' => $netProfitMargin,
]);


}


/////////////////////////////////////////////////////
public function reseller_dealer_profit_detail(Request $request)
{
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
    $resellerNetProfit = $dealerNetProfit = 0;
    $date = $request->get('datetimes');
        //
    $range = explode('-', $date);
    $from = date("Y-m-d H:i:s", strtotime($range[0]));
    $to = date("Y-m-d H:i:s", strtotime($range[1]));
        //
    $selectedUser = UserInfo::where('username', $username)->first();
        //
    $monthlyBillingEntries = AmountBillingInvoice::where($whereArray)
    ->where('charge_on', '>', date($from))->where('charge_on', '<', date($to))->with('user_info')->get(); 
    //
    foreach($monthlyBillingEntries as $key => $entry) {

        $rows[$key]['username'] = $entry->username;
        $rows[$key]['name'] = $entry['name'];
        $rows[$key]['resellerid'] = $entry->resellerid;
        $rows[$key]['dealerid'] = $entry->dealerid;
        $rows[$key]['sub_dealer_id'] = $entry->sub_dealer_id;
        $rows[$key]['chargeOn'] = $entry->charge_on;
        $rows[$key]['reseller_profit'] = $entry['reseller_profit'];
        $rows[$key]['dealer_profit'] = $entry['dealer_profit'];
        //
        $resellerNetProfit +=  $entry['reseller_profit'];
        $dealerNetProfit += $entry['dealer_profit']; 

    }
 //
    if (!empty($request->get('download'))) {
        $this->margin_details($rows, $netProfitMargin);
    }
//
//
    return view('users.billing.reseller_dealer_profit_details', [
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyBillingEntries' => $monthlyBillingEntries,
        'rows' => $rows,
        'resellerNetProfit' => $resellerNetProfit,
        'dealerNetProfit' => $dealerNetProfit,
    ]);


}
////////////////////////////////////////////////////


////
public function billingpdf(Request $request)
{
        // 
    $username = $request->get('username');
    $date = $request->get('date');
    $range = explode('-', $date);
    $from1 = $range[0];
    $to1 = $range[1];

    $from = date("Y-m-d", strtotime($from1));
    $to = date("Y-m-d", strtotime($to1));

    $userCollection1 = UserInfo::where(['username' => $username])->first();

    $dealerid = $userCollection1->dealerid;
    $resellerid = $userCollection1->resellerid;

    $monthlyBillingEntries = DealerProfileRate::where(['dealerid' => $dealerid])->get();


    //    return $total_amount1 = AmountBillingInvoice::where('dealerid' , $dealerid)
    // ->where('date','>=',DATE($from))->where('date','<=',
    //   DATE($to))->where('profile','=','2048')->sum('d_acc_rate');

    // return    $total_amount2 = AmountBillingInvoice::where('resellerid' , $resellerid)
    // ->where('date','>=',DATE($from))->where('date','<=',
    //   DATE($to))->where('profile','=','2048')->sum('r_acc_rate');



    $pdf= PDF::loadView('users.billing.summeryReport_PDF',[

      'monthlyBillingEntries' => $monthlyBillingEntries,
      'userCollection1' => $userCollection1,
      'resellerid' => $resellerid,

      'from'=> $from,
      'to'=> $to



  ]);

    return $pdf->stream($username.'.pdf');




}

// Report detail
public function reportdetails(Request $request)
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

if($username == "own"){
    $selectedUser = UserInfo::where('username',Auth::user()->username)->first();

    $monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('dealerrate');

            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , Auth::user()->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
    $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid'=>Auth::user()->dealerid])->get();

    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);


}else{
  $selectedUser = UserInfo::where('username',$username)->first();
}



if($selectedUser->status == 'dealer'){
            // report summary
    $monthlyPayableAmount = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('dealerrate');

            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('dealerid' , $selectedUser->dealerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
    $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid'=>Auth::user()->resellerid])->get();

    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);

}elseif($selectedUser->status == 'subdealer'){
    $monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('subdealerrate');

            // report summary
            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();
    if(Auth::user()->status == "dealer"){
        $userCollection = UserInfo::select('username')->where(['status'=>'subdealer','dealerid' => Auth::user()->dealerid ])->get();
    }else{
        $userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
    }

    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);     
}elseif ($selectedUser->status == 'reseller') {
    $monthlyPayableAmount = AmountBillingInvoice::where(['resellerid' => $selectedUser->resellerid])
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('rate');



            // report summary
            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

    $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id'=>Auth::user()->manager_id])->get();

    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);
}elseif ($selectedUser->status == 'manager') {
    $monthlyPayableAmount = AmountBillingInvoice::where(['manager_id' => $selectedUser->manager_id])
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('m_rate');



            // report summary
            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get();

    $userCollection = UserInfo::select('username')->where(['status'=>'reseller','manager_id'=>Auth::user()->manager_id])->get();

    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);
}
elseif ($selectedUser->username == Auth::user()->username) {
    $monthlyPayableAmount = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->sum('subdealerrate');

            // report summary
            // monthly Billing Report
    $monthlyBillingEntries = AmountBillingInvoice::where('sub_dealer_id' , $selectedUser->sub_dealer_id,'dealerid','')
    ->where('charge_on','>',date($from))->where('charge_on','<',date($to))->with('user_info')->get(); 
    $userCollection = UserInfo::select('username')->where(['username' => Auth::user()->username])->get();
    return view('users.billing.report_details',[
        'userCollection' => $userCollection,
        'isSearched' => true,
        'selectedUser' => $selectedUser,
        'monthlyPayableAmount' => $monthlyPayableAmount,
        'monthlyBillingEntries' => $monthlyBillingEntries
    ]);
}
        //return $monthlyBillingEntries;
}

public function advanceSummary(Request $request)
{
    $username = $request->get('username');
    $date = $request->get('date');
    $range = explode('-', $date);
    $from1 = $range[0];
    $to1 = $range[1];

    $from = date("Y-m-d", strtotime($from1));
    $to = date("Y-m-d", strtotime($to1));


    $selectedUser = UserInfo::where('username',$username)->first();



            //  $monthlyPayableAmount = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
            // ->where('date','>',date($from))->where('date','<',date($to))->with('user_info')->sum('m_rate');

    $monthlyPayableAmount = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
    ->whereBetween('date',[$from,$to])->with('user_info')->sum('rate');

            // report summary
            // monthly Billing Report
            // $monthlyBillingEntries = AmountBillingInvoice::where('manager_id' , $selectedUser->manager_id)
            // ->where('date','>',date($from))->where('date','<',date($to))->with('user_info')->get();  

    $monthlyBillingEntries = AmountBillingInvoice::where('resellerid' , $selectedUser->resellerid)
    ->whereBetween('date',[$from,$to])->with('user_info')->get();   

    $userCollection = UserInfo::select('username')->where('status', 'reseller')->get();
    return view('users.billing.advance_summary',[
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





}else if($status == "dealer")
{
    $selectUser = $request->get('username');
    if($selectUser == "own"){

      $user_data =  UserInfo::where([ 'username' => Auth::user()->username,'status' => 'dealer'])->first();
      $sub_dealer_id = '';
      $dealerid = $user_data->dealerid;
      $details = AmountBillingInvoice::where('dealerid' , $dealerid)
      ->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
        DATE($lastbilldate.' 12:00:00'))->select('name','dealerrate','d_acc_rate','sst','adv_tax','profile')->distinct('name')->get();
      if($ebd[2] == 10){


         $static_function = $this->staticIp(Auth::user()->username);
         $static_function = explode("^", $static_function);



         $totalIPamount = $static_function[0];
         $ip_rate = $static_function[1];
         $num_ips = $static_function[2];

     }else{
      $totalIPamount = 0;
      $ip_rate = 0;
      $num_ips = 0;
  }


}else{
  $user_data = UserInfo::where([ 'username' => $username,'dealerid' => Auth::user()->dealerid])->first();
  $sub_dealer_id = $user_data->sub_dealer_id;
  $dealerid = $user_data->dealerid;

  $details = AmountBillingInvoice::where('sub_dealer_id' , $sub_dealer_id)
  ->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
    DATE($lastbilldate.' 12:00:00'))->select('name','subdealerrate','s_acc_rate','sst','adv_tax','profile')->distinct('name')->get();


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

}else  if($status == "manager" )
{
    $user_data = UserInfo::where([ 'username' => $username])->first();
    $dealerid = $user_data->resellerid;
    $sub_dealer_id = '';

    $details = AmountBillingInvoice::where('resellerid' , $dealerid)
    ->where('charge_on','<',DATE($bildate.' 12:00:00'))->where('charge_on','>=',
        DATE($lastbilldate.' 12:00:00'))->select('name','rate','r_acc_rate','sst','adv_tax','profile')->distinct('name')->get();
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


public function loadUser(Request $request){
    $status = $request->get("status");
    $userCollection = UserInfo::select('username')->where('status', $status)->get();
    return $userCollection;
}

public function loadSubDealer(Request $request){
    $username = $request->username;
    $dealers = UserInfo::where('username',$username)->first();
    $dealerid = $dealers['dealerid'];
    $data = UserInfo::where('dealerid',$dealerid)->where('status','subdealer')->select('sub_dealer_id')->get();
    return $data;

}
public function loadDealer(Request $request){
    $username = $request->username;
    $data = UserInfo::where('resellerid',$username)->where('status','dealer')->select('dealerid')->get();
    return $data;

}
public function loadTrader(Request $request){
    $username = $request->username;
    $data = UserInfo::where('sub_dealer_id',$username)->where('status','trader')->select('trader_id')->get();
    return $data;

}

public function usersreport(Request $request){

    $date = $request->get('date');

    $range = explode('-', $date);
    $from1 = $range[0];
    $to1 = $range[1];
    $from1=date('Y-m-d' ,strtotime($from1));
    $to1=date('Y-m-d' ,strtotime($to1));





    // 
    $export_data  =  AmountBillingInvoice::where('resellerid' ,'logonbroadband')
    ->whereBetween('date',[$from1,$to1])->with('user_info')->get();  
    //
    $filename = "logonbroadband-UserSheet.csv";

    // 
    



    $delimiter = ",";
    // $filename = "Export.csv";
    //create a file pointer
    $f = fopen('php://memory', 'w');
    //set column headers
    $fields = array('S.No#','Username','Reseller-Rate','Manager-Rate','Dealerrate','Subdealerrate','SST','Adv_tax','Commision','M_acc_rate','R_acc_rate','D_acc_rate','S_acc_rate','Profit','C_sst','C_adv','C_charges','C_rates','Receipt','Receipt_num','Profile','Name','Taxname','Charge_on','sub_dealer_id','dealerid','resellerid','manager_id','date');
    fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
    $num=1;
    foreach ($export_data as $value) {
        // 

        // 
        $data1=$num;
        $date2=$value->username;
        $data3=$value->rate;
        $data4=$value->m_rate;
        $data5=$value->dealerrate;
        $data6=$value->subdealerrate;
        $data7=$value->sst;
        $data8=$value->adv_tax;
        $data9=$value->commision;
        $data10=$value->m_acc_rate;
        $data11=$value->r_acc_rate;
        $data12=$value->d_acc_rate;
        $data13=$value->s_acc_rate;
        $data14=$value->profit;
        $data15=$value->c_sst;
        // 
        
        $data16=$value->c_adv;
        $data17=$value->c_charges;
        $data18=$value->c_rates;
        $data19=$value->receipt;
        $data20=$value->receipt_num;
        $data21=$value->profile;
        $data22=$value->name;
        $data23=$value->taxname;
        $data24=$value->charge_on;
        $data25=$value->sub_dealer_id;
        $data26=$value->dealerid;
        $data27=$value->resellerid;
        $data28=$value->manager_id;
        $data29=$value->date;


        // 
        $lineData = array($data1,$date2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$data17,$data18,$data19,$data20,$data21,$data22,$data23,$data24,$data25,$data26,$data27,$data28,$data29);
        fputcsv($f, $lineData, $delimiter);
        // 
        $num++;
    }
    // last row or Summary
    //move back to beginning of file
    fseek($f, 0);
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
    fpassthru($f);
// }
    exit;






// 
// index function end 

}


public function margin_details($rows, $netProfitMargin)
{
    $delimiter = ",";
    $f = fopen('php://memory', 'w');
    $fields = array('S.No#', 'Consumer ID', 'Reseller (ID)', 'Contractor (ID)', 'Trader (ID)', 'Internet Profile','Profit Margin');
    fputcsv($f, $fields, $delimiter);
    $num = 1;
    foreach ($rows as $row) {
        $margin_dealer =  $row['profitMargin'];
        $data12 = strval($margin_dealer);
        $lineData = array($num, $row['username'], $row['resellerid'], $row['dealerid'], $row['sub_dealer_id'], $row['name'],$data12);
        fputcsv($f, $lineData, $delimiter);
        $num++;
    }
    $filename = "CONTRACTOR & TRADER MARGIN REPORT.csv";
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    fpassthru($f);
    exit;
}

    ///


public function current_balance_download_csv(Request $request)
{
    $id = Auth::user()->id;
    $userId = 'user' . $id;
    $pModule = array();
    $cModule = array();
    $state = array();
    $userId = 'user' . Auth::user()->id;
    if (Auth::user()->status == 'inhouse') {
        if (!MyFunctions::check_access('Current Balance', Auth::user()->id)) {
            return 'Permission Denied';
        }
    }

    $userCollection = array();
    $currentStatus = Auth::user()->status;
    if ($currentStatus == "reseller") {
        $userDealer = UserInfo::where(['resellerid' => Auth::user()->resellerid, 'status' => 'dealer'])->select('username', 'dealerid')->get();
    } elseif ($currentStatus == "manager") {
        $userDealer = UserInfo::where(['manager_id' => Auth::user()->manager_id, 'status' => 'reseller'])->select('username')->get();
    } elseif ($currentStatus == "dealer") {
        $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid, 'status' => 'subdealer'])->select('username', 'dealerid', 'sub_dealer_id')->get();
    } elseif ($currentStatus == "subdealer") {
        $userDealer = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id, 'status' => 'trader'])->select('username', 'dealerid', 'sub_dealer_id')->get();
    } elseif ($currentStatus == 'inhouse') {
        $userDealer = UserInfo::where(['resellerid' => Auth::user()->resellerid, 'status' => 'dealer'])->select('username', 'dealerid', 'sub_dealer_id')->get();
    }
    foreach ($userDealer as $value) {

        if ($currentStatus == "reseller") {
            $online = UserAmount::where(['username' => $value->username, 'status' => 'dealer'])->get();
        } elseif ($currentStatus == "dealer") {
            $billingType = DealerProfileRate::where('dealerid', $value->dealerid)->first();
            if ($billingType->billing_type == 'card') {
                $online = Card::where('dealerid', $value->dealerid)->where('sub_dealer_id', $value->sub_dealer_id)->where('status', 'unused')->select(DB::raw('sub_dealer_id,sum(dealerrate) rates'))->groupby(['sub_dealer_id'])->get();
                    // dd($rates);
            } else {
                $online = UserAmount::where(['username' => $value->username, 'status' => 'subdealer'])->get();
            }
        } elseif ($currentStatus == "manager") {
            $online = UserAmount::where(['username' => $value->username, 'status' => 'reseller'])->get();
        } elseif ($currentStatus == 'inhouse') {
            $online = UserAmount::where(['username' => $value->username, 'status' => 'dealer'])->get();
        }

        foreach ($online as $value) {

            $userCollection[] = $value;
        }
    }





    $filename = "current-balance-amount.csv";
    $delimiter = ",";
    $f = fopen('php://memory', 'w');

    $fields = array('S.No#', 'Account (ID)', 'Status', 'Balance Amount (PKR)');
    fputcsv($f, $fields, $delimiter);

    $num = 1;

    foreach ($userCollection as $value) {
        $data1 = $num;
        $data2 = $value->username;
        $data3 = $value->status;
        $data4 = number_format($value->amount);

        $lineData = array($data1, $data2, $data3, $data4);

        fputcsv($f, $lineData, $delimiter);
        $num++;
    }

    fseek($f, 0);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    fpassthru($f);

    exit;
}



public function bill_register(){
    if(!MyFunctions::check_access('Bill Register',Auth::user()->id)){
        return 'Permission Denied';
    }
    return view('users.billing.bill_register');
}
//
public function bill_register_action(request $request){
    //  
    $verified = $request->get('verified');
    $date = $request->get('datetimes');
    $billing_price = $request->get('billing_price');
    //
    $range = explode('-', $date);
    $from = date('Y-m-d',strtotime($range[0]));
    $to = date('Y-m-d',strtotime($range[1])); 
    //
    $whereArray = array();
    // //
    $trader = $request->get('trader_data');
    $dealer = $request->get('dealer_data');
    $reseller = $request->get('reseller_data');
    //
    if(!empty($request->get('trader_data'))){
        array_push($whereArray,array('sub_dealer_id' , $trader));
    }else if(!empty($request->get('dealer_data'))){
        array_push($whereArray,array('dealerid' , $dealer));
    }else if(!empty($request->get('reseller_data'))){
        array_push($whereArray,array('resellerid' , $reseller));
    }
    //
    $filename = Auth::user()->username . "(Bill-Register-" . date('d-M-Y') . "[" . date('h:i:A') . "]" . ".csv";
    $delimiter = ",";
    $f = fopen('php://memory', 'w');
    $fields = array('S.No#', 'Date', 'Sales Tax Inv.#', 'Billing Inv.#', 'Customer Name & Address', 'CNIC/NTN', 'Location', 'Package', 'TIP', 'FLL', 'CVAS', 'Total Charges(SST)', 'S.S.Tax', 'Total Charges(ADV)', 'Adv.I.Tax', 'Total TAX.', 'Total Amt', 'Contractor ID', 'Trader ID', 'Cust ID');
    fputcsv($f, $fields, $delimiter);
    $num = 1;
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if( !empty($request->get('trader_data'))  ){
        $traderArray = $request->get('trader_data');
    //////////////////////////////////
        if($verified == 'Verified'){
//
            if($billing_price == 'all'){
//    
                $rows = AmountBillingInvoice::whereIn('sub_dealer_id',$traderArray)->where('date','>=',$from)->where('date','<=',$to)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();
//
            }else{
    //
                $rows = AmountBillingInvoice::whereIn('sub_dealer_id',$traderArray)->where('date','>=',$from)->where('date','<=',$to)->where('company_rate',$billing_price)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();   
    //
            }

        }else{
        //
            if($billing_price == 'all'){
    //
                $query1 = AmountBillingInvoice::whereIn('sub_dealer_id',$traderArray)->where('date','>=',$from)->where('date','<=',$to);
                //
               $rows = $query1->whereIn('username', function($query2){
                    $query2->select('username')
                    ->from('user_info')
                    ->where('pta_reported',1);
                })->get();
    //
            }else{
        //
                $query1 = AmountBillingInvoice::whereIn('sub_dealer_id',$traderArray)->where('date','>=',$from)->where('company_rate',$billing_price)->where('date','<=',$to);
                 //
               $rows = $query1->whereIn('username', function($query2){
                $query2->select('username')
                ->from('user_info')
                ->where('pta_reported',1);
            })->get();
        //
            }

        }
///////////////////////////////
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else if( !empty($request->get('dealer_data'))  ){
        $dealerArray = $request->get('dealer_data');
    //////////////////////////////////
        if($verified == 'Verified'){
//
            if($billing_price == 'all'){
//    
                $rows = AmountBillingInvoice::whereIn('dealerid',$dealerArray)->where('date','>=',$from)->where('date','<=',$to)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();
//
            }else{
    //
                $rows = AmountBillingInvoice::whereIn('dealerid',$dealerArray)->where('date','>=',$from)->where('date','<=',$to)->where('company_rate',$billing_price)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();   
    //
            }

        }else{
        //
            if($billing_price == 'all'){
    //
                $query1 = AmountBillingInvoice::whereIn('dealerid',$dealerArray)->where('date','>=',$from)->where('date','<=',$to);
                //
               $rows = $query1->whereIn('username', function($query2){
                $query2->select('username')
                ->from('user_info')
                ->where('pta_reported',1);
            })->get();
    //
            }else{
        //
                $query1 = AmountBillingInvoice::whereIn('dealerid',$dealerArray)->where('date','>=',$from)->where('company_rate',$billing_price)->where('date','<=',$to);
                //
                $rows = $query1->whereIn('username', function($query2){
                    $query2->select('username')
                    ->from('user_info')
                    ->where('pta_reported',1);
                })->get();
        //
            }

        }
///////////////////////////////
        
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }else if( !empty($request->get('reseller_data'))  ){
        $resellerArray = $request->get('reseller_data');
    //////////////////////////////////
        if($verified == 'Verified'){
//
            if($billing_price == 'all'){
//    
                $rows = AmountBillingInvoice::whereIn('resellerid',$resellerArray)->where('date','>=',$from)->where('date','<=',$to)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();
//
            }else{
    //
                $rows = AmountBillingInvoice::whereIn('resellerid',$resellerArray)->where('date','>=',$from)->where('date','<=',$to)->where('company_rate',$billing_price)->whereIn('username', function($query){
                 $query->select('username')
                 ->from('user_verification')
                 ->where('cnic','!=',NULL);
             })->get();   
    //
            }

        }else{
        //
            if($billing_price == 'all'){
    //
                $query1 = AmountBillingInvoice::whereIn('resellerid',$resellerArray)->where('date','>=',$from)->where('date','<=',$to);
                //
                $rows = $query1->whereIn('username', function($query2){
                    $query2->select('username')
                    ->from('user_info')
                    ->where('pta_reported',1);
                })->get();
    //
            }else{
        //
                $query1 = AmountBillingInvoice::whereIn('resellerid',$resellerArray)->where('date','>=',$from)->where('company_rate',$billing_price)->where('date','<=',$to);
                //
                $rows = $query1->whereIn('username', function($query2){
                    $query2->select('username')
                    ->from('user_info')
                    ->where('pta_reported',1);
                })->get();
        //
            }

        }
///////////////////////////////
    }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    foreach ($rows as $row) {
//
        // $info = UserInfo::where('username',$row['username'])->where('pta_reported',1)->first();
        $info = UserInfo::where('username',$row['username'])->first();
        if($info){
            $nameNaddress = $info->firstname.' '.$info->lastname.' ('.$info->address.')';
            $totalChargesSST =  $row['tip_charges'] +  $row['fll_charges'] + $row['cvas_charges'];
            $totaltaxes =  $row['adv_tax'] + $row['sst'];
            $pkg = explode('-',$row['profile']);
    // $user = (empty($row['sub_dealer_id'])) ? $row['dealerid'] : $row['sub_dealer_id'];
            $totalChargesADV = $row['fll_charges'] + $row['cvas_charges'];
            $totalChargesADV = $totalChargesADV + ($totalChargesADV * $row['sst_percentage']);
            $totalAmount = $totaltaxes + $totalChargesSST;
//    
            $lineData = array($num, $row['date'], '', '', $nameNaddress, $info->nic, 'Location', $pkg[1], $row['tip_charges'], $row['fll_charges'], $row['cvas_charges'], $totalChargesSST, $row['sst'], $totalChargesADV, $row['adv_tax'], $totaltaxes, $totalAmount, $row['dealerid'], $row['sub_dealer_id'], $row['username']);
            fputcsv($f, $lineData, $delimiter);
            $num++;
        }
    }
    fseek($f, 0);
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    fpassthru($f);
    exit;
}


}
