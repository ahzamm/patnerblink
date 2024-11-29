<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\UserStatusInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\RadAcct;
use App\model\Users\Profile;
use App\model\Users\UserUsualIP;
use App\model\Users\UserIPStatus;
use App\model\Users\Radreply;
use App\MyFunctions;
use Validator;
use Session;




class DealerViewTransferController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {}
   public function dealerviewtransfer()
   {
      $status = false;
      $dealer = UserInfo::where('status','dealer')->get();
      return view('users.billing.view_dealer_transfer',compact('status','dealer'));
   }
   public function deletedata()
   {
      $status = false;
      $dealer = UserInfo::where('status','dealer')->get();
      return view('users.delete-data.index',compact('status','dealer'));
   }
public function getSubDealer(Request $request)
{
 return UserInfo::where('dealerid',$request->dealer)->where('status','subdealer')->get();
}
public function dealerviewtransferPost(Request $request)
{
   //
   if(!empty($request->get('trader_data'))){
      $username = $request->get('trader_data');
   }else if(!empty($request->get('dealer_data'))){
      $username = $request->get('dealer_data');
   }else if(!empty($request->get('reseller_data'))){
      $username = $request->get('reseller_data');
   }else{
      return 'Please select user first';
   }
   //
   $status = true;
   $dealer = null;
   $dealerid = null;
   // $dealer = UserInfo::where('status','dealer')->get();
   // $dealerid = $request->dealerid;
   // $username =  UserInfo::where('dealerid',$dealerid)->where('status','dealer')->first('username');
    // dd($username);
   $amountTransactions = AmountTransactions::where(['sender' => $username,'commision' => 'no'])->orderBy('date','desc')->get();
    // dd($amountTransactions);
   return view('users.billing.view_dealer_transfer',compact('status','dealer','dealerid','amountTransactions'));

}
public function viewDealerClosingAmount()
{
   $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
   //
    $whereArray = array();
    //
    if(!empty($manager_id)){
        array_push($whereArray,array('manager_id' , $manager_id));
    }if(!empty($resellerid)){
        array_push($whereArray,array('resellerid' , $resellerid));
    }if(!empty($dealerid)){
        array_push($whereArray,array('dealerid' , $dealerid));
    }if(!empty($sub_dealer_id)){
        array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));  
    }
   //
   $result = array();
   $dealers = UserInfo::where('status','dealer')->where($whereArray)->get();
   foreach ($dealers as $key => $value) {
      $amount = AmountTransactions::where(['receiver' => $value->username,'commision' => 'no'])->sum('amount');
      $cash_amount = AmountTransactions::where(['receiver' => $value->username,'commision' => 'no'])->sum('cash_amount');
      $total = $amount-$cash_amount;
      // $data = array('username' => $value->username, 'amount' => $total);
      $d = ['username' => $value->username,'amount' => $total];
      array_push($result,$d);
   }
   // dd($result);

   
   return view('users.billing.dealer_closing_amount',compact('result'));
}



/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////
/////////////////////////////////////////

public function change_cgn_ip(){
   //
   if(!MyFunctions::check_access('Change CGN IP',Auth::user()->id)){
     abort(404);
  }
  return view('users.change_CGN_IP.index');
   //
}
///////////////////////////////////////////
public function change_cgn_ip_action(request $request){
   //
   $username = $request->get('username');
   //
   if(!MyFunctions::check_access('Change CGN IP',Auth::user()->id)){
     return abort(403, 'Error : Permission Denied.'); 
   }
   if(empty(Auth::user()->username) ){
      return abort(403, 'Error : Session Expired.');   
   }if(empty($username)){
      return abort(403, 'Error : Kindly selected consumer first.');    
   }
   //
   $userInfo = UserInfo::where('username',$username)->where('status','user')->first();
   //
   if(empty($userInfo)){
     return abort(403, 'Error : Invalid or no consumer found.');
  }
  //
  $userStatusInfo = UserStatusInfo::where('username',$username)->first();
   //
  if(strtotime($userStatusInfo->card_expire_on) < strtotime(date('Y-m-d'))){
    return abort(403, 'Error : Consumer has been expired.');
 }
   //
 $userIpStatus = UserIPStatus::where('username',$username)->where('ip','!=',NULL)->first();
 //
 if(empty($userIpStatus)){
    return abort(403, 'Error : No IP Assigned.');
 }
 //
 $oldIP = UserUsualIP::where('ip',$userIpStatus->ip)->first();
   //
 if(empty($oldIP)){
   return abort(403, 'Error : No CGN IP Assigned.');
}
//
$radReply = Radreply::where('username',$username)->where('value',$userIpStatus->ip)->first();
if(empty($radReply)){
   return abort(403, 'Error : IP not found on RadReply');
}  
//
$newIp = UserUsualIP::where(['status' => '0', 'nas' => $oldIP->nas])->first(); 
if(empty($newIp)){
   return abort(403, 'Error : Sorry no free CGN IP available.'); 
}
//
$OLD_IP_ADDR = $oldIP->ip;
$NEW_IP_ADDR = $newIp->ip;
//
// updating new ip address
$userIpStatus->ip = $NEW_IP_ADDR;
$userIpStatus->save();
// updating new ip status 0 to 1
$newIp->status = 1;
$newIp->save();
// updating old ip status 1 to 0
$oldIP->status = 0;
$oldIP->save();
// updating radreply
$radReply->value = $NEW_IP_ADDR;
$radReply->save();
//
$html = 'IP Changed Successfully <br>';
$html .= 'OLD IP : '.$OLD_IP_ADDR.'<br>';
$html .= 'NEW IP : '.$NEW_IP_ADDR.'<br>';
//
return $html;


}




}
?>