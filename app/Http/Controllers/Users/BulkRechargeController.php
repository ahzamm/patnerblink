<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\model\Users\UserInfo;
use App\model\Users\AssignedNas;
use App\model\Users\StaticIPServer;
use App\model\Users\StaticIp;
use App\model\Users\UserStatusInfo;
use App\model\Users\FreezeAccount;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\Tax;
use App\model\Users\UserAmount;
use App\model\Users\ExpireUser;
use App\model\Users\RaduserGroup;
use App\model\Users\Profile;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\UserVerification;
use App\model\Users\Error;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\RechargeController;
use Session;
use Validator;
use App\MyFunctions;
use Throwable;

class BulkRechargeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
    }
    //
    public function index(){
        ///////////////////// check access ///////////////////////////////////
     if(!MyFunctions::check_access('Bulk Recharge',Auth::user()->id)){
        abort(404);
    }
    return view('users.BulkRecharge.index');
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function show_consumer(){
    //
    $sevendays = date('Y-m-d',strtotime(date('Y-m-d') . "-7 days"));
    //
    $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
    $whereArray = array();
        //
    array_push($whereArray,array('user_info.manager_id' , $manager_id));
    array_push($whereArray,array('user_info.resellerid' , $resellerid));
    array_push($whereArray,array('user_info.dealerid' , $dealerid));
    array_push($whereArray,array('user_info.sub_dealer_id' , $sub_dealer_id));   
    //
    if(Auth::user()->status == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->first();
    }else if(Auth::user()->status == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
    }
    //
    $query = DB::table('user_info')
    ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
    ->select('user_info.username','user_info.dealerid','user_info.firstname','user_info.lastname','user_info.profile','user_info.address','user_info.name', 'user_status_info.card_expire_on', 'user_info.mobilephone')
    ->where($whereArray)
    ->where('user_status_info.expire_datetime', '<', DATE('Y-m-d H:i:s'))
    ->where('user_status_info.card_expire_on', '>=', $sevendays)
    ->where('user_info.profile' , '!=','DISABLED')
    ->where('user_info.profile' , '!=','NEW')
    ->where('user_info.name' , '!=','DISABLED')
    ->where('user_info.status','=','user')
    ->where('user_info.profile_amount','>',0);
    //
    if($profileRate->verify == 'yes'){
        $query->join('user_verification', 'user_verification.username', '=', 'user_info.username');
        // $query->where('user_verification.cnic','!=',NULL);
        $query->where(function ($wherequery) {
            $wherequery->where('cnic','!=',NULL)
            ->orWhere('ntn','!=',NULL);
        });
    }
    //
    $userList = $query->get();



        //
    foreach($userList as $user){
        ?>
        <tr>
            <td><input type="checkbox" class="from-control" name="dataList[]" value="<?= $user->username; ?>" id="checkthis" style="width:25px;height:25px"></td>
            <td class="td__profileName"><?= $user->username; ?></td>
            <td><?= $user->firstname.' '.$user->lastname?></td>
            <td><?= $user->address; ?></td>
            <td><?= $user->mobilephone; ?></td>
            <td style="color:darkgreen;font-weight:bold"><?= $user->name; ?></td>
            <td style="color:red"><?= date('M d,Y', strtotime($user->card_expire_on)) ; ?></td>
        </tr>
        <?php
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function recharge(request $request){
        //
    $successUser = null;
    $totalWalletDed = 0;
    $forConfirmation = null;
    $dataList = $request->get('dataList');
    $level = $request->get('level');
        //
    if(!MyFunctions::check_access('Bulk Recharge',Auth::user()->id)){
        return abort(403, 'Error : Permission denied.');
    }
    if(empty(Auth::user()->username) ){
        return abort(403, 'Error : Session Expired.');   
    }if(empty($dataList)){
        return abort(403, 'Error : Kindly selected consumer first.');    
    }if(count($dataList) > 10){
        return abort(403, 'Error : Sorry you can not select more than 10 consumer at a time.');    
    }
        //
    $panelUsername = Auth::user()->username;
    $panelStatus = Auth::user()->status;
        //
    $freezeAccount = 'no';
    $freeze = FreezeAccount::where('username',$panelUsername)->first();
    if($freeze){
        $freezeAccount = $freeze->freeze;
    }if($freezeAccount == 'yes'){
        return abort(403, 'Error : Your account has been freezed.');  
    }
        //
    $total_wallet_deduction = 0;
        ///////////////////////  CHECKING  ///////////////////////////////////////////////////
    foreach($dataList as $username){
        $userInfo = UserInfo::where('username',$username)->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->first();
            //
        if(empty($userInfo)){
            $error_msg = 'Error : Invalid consumer '.$username;
            $this->create_error_log($username,$error_msg);
            return abort(403, $error_msg);
            break;
        }
            //
        $errorPkg = array('NEW','DISABLED',NULL);
        if(in_array($userInfo->name,$errorPkg)){
            $error_msg = 'Error : Invalid Profile of consumer '.$username;
            $this->create_error_log($username,$error_msg);
            return abort(403, $error_msg);
            break;
        }
            //
        if($userInfo->profile_amount <= 0 || empty($userInfo->company_rate)){
            $error_msg = 'Error : Kindly set profile amount first of consumer '.$username;
            $this->create_error_log($username,$error_msg);
            return abort(403, $error_msg);
            break;
        }
            //
        $userStatusInfo = UserStatusInfo::where('username',$username)->first();
        if(strtotime($userStatusInfo->card_expire_on) > strtotime(date('Y-m-d'))){
            $error_msg = 'Error : Consumer already charged '.$username;
            $this->create_error_log($username,$error_msg);
            return abort(403, $error_msg);
            break; 
        }
        
            //
        if($panelStatus == "dealer"){
            $profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
        }else if($panelStatus == "subdealer"){
            $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
        }
        //
        $isverify = UserVerification::where('username', $username)->where(function ($query) {
            $query->where('cnic','!=',NULL)
            ->orWhere('ntn','!=',NULL);
        });
        $isverify = $isverify->first();
        // 
        if(empty($isverify) && $profileRate->verify == 'yes' ){
          $error_msg = 'Error : Kindly verify first '.$username;
          $this->create_error_log($username,$error_msg);
          return abort(403, $error_msg);
          break;  
      }
            //
      if ( ($userInfo->company_rate == 'yes') && ($profileRate->base_price > $userInfo->profile_amount) ) {
        $error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
        $this->create_error_log($username,$error_msg);
        return abort(403, $error_msg);
        break;

    }if ( ($userInfo->company_rate == 'no')  && ($profileRate->base_price_ET > $userInfo->profile_amount) ) {
        $error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
        $this->create_error_log($username,$error_msg);
        return abort(403, $error_msg);
        break;
    }
    //
    /////// Show error when rates changed
    if(($userInfo->company_rate == 'yes')  && ($userInfo->profile_amount != $profileRate->base_price)){ 
        $error_msg = 'Error : Profile rate has been changed, kindly update '.$username.' rate once.';
        $this->create_error_log($username,$error_msg);
        return abort(403, $error_msg);
        break;
    }
        //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);
    $total_wallet_deduction += $walletDedArray['wallet_deduction'];
            //
}
$userAmount = UserAmount::where('username', $panelUsername)->first();
if($userAmount->amount < $total_wallet_deduction){
    return abort(403, 'Error : Sorry you do not have enough amount in your wallet.');  
}
        /////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////// RECHARGING /////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////
foreach($dataList as $username){
            //
    $userInfo = UserInfo::where('username',$username)->first();
            //
    if($panelStatus == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
    }else if($panelStatus == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
    }
    //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);
    $wallet_deduction = $walletDedArray['wallet_deduction'];
    //
    if($wallet_deduction <= 0){
        return abort(403, 'Error : Something went wrong. Kindly check consumer '.$username); 
        break; 
    }
    //
    $userAmount = UserAmount::where('username', $panelUsername)->first();
    if($userAmount->amount < $wallet_deduction){
        return abort(403, 'Error : Sorry you do not have enough amount remaining in your wallet.'); 
        break; 
    }
    //
    $assignedNas = AssignedNas::where(["id" => Auth::user()->dealerid])->where('nas','!=',NULL)->first();
    if(empty($assignedNas)){
        return abort(403, 'Error : No NAS assigned.'); 

    }
    $userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->first();
        // 
    if(empty($userUsualIp)){
        return abort(403, 'Error : Sorry no CGN IP available.'); 
        break; 
    }
    //
    if($level == 'confirmed'){
        //
        $uniqueID = $username . str_replace('-', '', date('Y-m-d'));
        $billExist = AmountBillingInvoice::where('userid',$uniqueID)->first();
        if($billExist){
            return abort(403, 'Error : Bill of same month already exist of consumer '.$username); 
            break;  
        }
        //
        $this->recharge_it($request,$userInfo->name,$username,$wallet_deduction);
        //////// USER KICK API ///////
        ////////////
        ////
     // $url='https://api-radius.logon.com.pk/kick/user-dc-api.php?username='.$username;
     // $ch = curl_init();
     // curl_setopt($ch, CURLOPT_URL, "$url"); 
     // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     // $result = curl_exec($ch);
        MyFunctions::kick_it($username);
        ////////////
        //////////////
            //
    }else{
            //
        $forConfirmation .= '<tr>
        <input type="hidden" name="dataList[]" value="'.$username.'">
        <td>'.$username.'</td>
        <td>'.$userInfo->name.'</td>
        <td>'.$wallet_deduction.'</td>
        </tr>';
            //
        $totalWalletDed += $wallet_deduction; 
    }

        //
}

        ////////////
if($level == 'confirmed'){
    return 'Recharged Successfully';
}else{
    $forConfirmation .= '<tr>
    <td colspan="2"><b>Total Wallet Deduction</b></td>
    <td><b>'.$totalWalletDed.'</b></td>
    </tr>';
    return $forConfirmation;
}

        ////////////

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public function check_deduction_amount($profileRate,$userInfo){
      //
   $get_taxt_data = Tax::first();
      //
   $sst_rate_per = $profileRate->sstpercentage;
   $adv_rate_per = $profileRate->advpercentage;
   $taxRev_fll = $taxRev_cvas = $taxRev_tip = $userInfo->profile_amount;
        //
   if($userInfo->company_rate == 'yes'){
         //
    if($get_taxt_data->fll_sst == 'yes'){
        $taxRev_fll = $userInfo->profile_amount / ($sst_rate_per+1);
    }if($get_taxt_data->cvas_sst == 'yes'){
        $taxRev_cvas = $userInfo->profile_amount / ($sst_rate_per+1);
    }if($get_taxt_data->tip_sst == 'yes'){
        $taxRev_tip = $userInfo->profile_amount / ($sst_rate_per+1);
    }
        //
    if($get_taxt_data->fll_adv == 'yes'){
        $taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
    }if($get_taxt_data->cvas_adv == 'yes'){
        $taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
    }if($get_taxt_data->tip_adv == 'yes'){
        $taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
    }
}
        //
$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
                        //
$total_charges = $fll_charges + $cvas_charges + $tip_charges;
$profit_margin_dealer = $userInfo->profile_amount - $profileRate->base_price_ET;
                        //
$fll_sst = $cvas_sst = $tip_sst = 0;
$fll_adv = $cvas_adv = $tip_adv = 0;
                        //
if($get_taxt_data->fll_sst == 'yes'){
    $fll_sst = $fll_charges * $sst_rate_per;
}if($get_taxt_data->cvas_sst == 'yes'){
    $cvas_sst = $cvas_charges * $sst_rate_per;
}if($get_taxt_data->tip_sst == 'yes'){
    $tip_sst = $tip_charges * $sst_rate_per;
}
                        //
$sst_u = $fll_sst + $cvas_sst + $tip_sst;
                        //
if($get_taxt_data->fll_adv == 'yes'){
    $fll_adv = ($fll_charges + $fll_sst) * $adv_rate_per;
}if($get_taxt_data->cvas_adv == 'yes'){
    $cvas_adv = ($cvas_charges + $cvas_sst) * $adv_rate_per;
}if($get_taxt_data->tip_adv == 'yes'){
    $tip_adv = ($tip_charges + $tip_sst) * $adv_rate_per;
}
    // 
$adv_tax_u = $fll_adv + $cvas_adv + $tip_adv;
    //
$filer_tax_rate = 0;
$filer = 0;
$check_dealerid = Auth::user()->dealerid;
$get_dealer_info = UserInfo::where(['dealerid'=>$check_dealerid,'status'=>'dealer'])->first();
    //
if ( $userInfo->profile_amount > $profileRate->base_price_ET ) {
                            //
    $check_filer = $get_dealer_info->is_filer;
    $check = $check_filer;
    if ($check === 'filer') {
        $filer = $get_taxt_data->filer_tax;

    } elseif($check === 'non filer'){
        $filer = $get_taxt_data->non_file_tax;
    }
    else{
        $filer = 0;
    }
}
$filer_tax_rate = $profit_margin_dealer * $filer;
$wallet_deduction = $total_charges + $sst_u + $adv_tax_u + $filer_tax_rate; 
if($userInfo->company_rate != 'yes'){
    $wallet_deduction = $wallet_deduction - $profit_margin_dealer;
    $wallet_deduction = $wallet_deduction / 2 ;
}
    //
$returnArray = array(
    'wallet_deduction' => $wallet_deduction,
    'get_taxt_data' => $get_taxt_data,
    'sst_u' => $sst_u,
    'adv_tax_u' => $adv_tax_u,
    'filer' => $filer,
    'fll_charges' => $fll_charges,
    'cvas_charges' => $cvas_charges,
    'tip_charges' => $tip_charges,
);
return $returnArray;
    //
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


public function recharge_it(Request $request,$profileName,$username,$wallet_deduction){
    ///
    $currentUser = Auth::user();
    ////////// WALLET DEDUCTION ////////////////
    $userAmount = UserAmount::where('username', $currentUser->username)->lockForUpdate()->first();
    $wallet_bef_charge = $userAmount->amount;
    $userAmount->amount -= $wallet_deduction;
    $userAmount->save();
    //
    $cur_date=date('Y-m-d');
    //
    $charge = strtotime(date('H:i:s'));
        //
    if($charge > strtotime('11:59:59') ){
    // 
        $time=strtotime($cur_date);
        $exp_date = date("Y-m-d", strtotime("+1 month", $time));
    //
    }else{
    // 
        $time=strtotime($cur_date);
        $final = strtotime(date("Y-m-d", strtotime("+1 month", $time)));
        $exp_date = date("Y-m-d", strtotime("-1 day", $final));
    //
    }
    /*** User Usual IP: get ip where status is 0: un assigned IP(s)  ****/
    $radReply = Radreply::where('username',$username)->first();
    $userIpStatus = UserIPStatus::where('username',$username)->first();
    $assignedNas = AssignedNas::where(["id" => $currentUser->dealerid])->first();
    $userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->lockForUpdate()->first();
    if($radReply){
            // 
        $userIpStatus = UserIPStatus::where('username',$username)->first();
            // ip will not null in user_ip_status
        if(!$userIpStatus){
            $userIpStatus = new UserIPStatus();
            $userIpStatus->username = $username;
            $userIpStatus->type = 'usual_ip';
        }

    }else{
        // 
        if(!$userIpStatus){
            $userIpStatus = new UserIPStatus();
            $userIpStatus->username = $username;
        }
        //
        // lock the IP: assigned
        $userUsualIp->status = 1; 
        //
        $ip = $userUsualIp->ip;
        //
        $userIpStatus->ip = $ip;
        $userIpStatus->type = 'usual_ip';
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
            $radReply->manager_id = $currentUser->manager_id;
        }elseif($currentUser->status == 'subdealer'){
            $radReply->dealerid = $currentUser->dealerid;
            $radReply->resellerid = $currentUser->resellerid;
            $radReply->sub_dealer_id = $currentUser->sub_dealer_id;
            $radReply->manager_id = $currentUser->manager_id;
        }else{
            $radReply->dealerid = $currentUser->dealerid;
            $radReply->resellerid = $currentUser->resellerid;
            $radReply->sub_dealer_id = $currentUser->sub_dealer_id;
            $radReply->trader_id = $currentUser->trader_id;
            $radReply->manager_id = $currentUser->manager_id;
        }
            //
    }
        //
    $changeprofile ='';
    $userdata = Profile::where('name',$profileName)->first();
    $name = $userdata['name'];
    $code = $userdata['code'];
    $profileGroupName = $userdata['groupname'];
    $userdata->save();
    //
    $profileGroupName = $profileGroupName;
    //
    $raduserGroup = RaduserGroup::where('username' , $username)->first();
    $raduserGroup->groupname = $profileGroupName;
    $raduserGroup->name = $name;
    /******/
    //updating status info
    $userStatusInfo =  UserStatusInfo::where('username' , $username)->first();
    $userStatusInfo->card_charge_on = $cur_date;
    $userStatusInfo->card_expire_on = $exp_date;
    $userStatusInfo->expire_datetime = $exp_date.' 12:00:00';
    $userStatusInfo->card_charge_by = $currentUser->username; 
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

    //
    $userInfo = UserInfo::where('username',$username)->first();
    //
    if( Auth::user()->status == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
        $s_acc_rate = 0;
        $inv_subdealerrate = 0;
        $ct_margin = 0;
        $subdealer_ppm = 0;

    }else if( Auth::user()->status == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
        $s_acc_rate = $profileRate->base_price_ET;
        $inv_subdealerrate = $profileRate->base_price;
        $ct_margin = $profileRate->margin;
        $subdealer_ppm = $profileRate->partner_profit_margin;
    }

    //
    $dealerprofileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileName])->first();
    $resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileName])->first();
    $m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileName])->first();
    //
    $commision = $dealerprofileRate->commision;
    $m_acc_rate = $m_profileRate->rate;
    $r_acc_rate = $resellerprofileRate->rate;
    $d_acc_rate = $dealerprofileRate->rate;
    $profit = $d_acc_rate - $r_acc_rate - $commision;
    //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);

    ////////////////////////////////////////
    $uniqueID = $username . str_replace('-', '', date('Y-m-d'));
    //
    $insertAmountBilling = new AmountBillingInvoice();
    $insertAmountBilling->username = $username;
    $insertAmountBilling->userid = $uniqueID;
    $insertAmountBilling->m_rate = $m_acc_rate;
    $insertAmountBilling->r_rate = $r_acc_rate;
    $insertAmountBilling->rate = $d_acc_rate;
    $insertAmountBilling->dealerrate = $dealerprofileRate->base_price;
    $insertAmountBilling->subdealerrate = $inv_subdealerrate;
    $insertAmountBilling->s_acc_rate = $s_acc_rate;
    $insertAmountBilling->adv_percentage = $profileRate->advpercentage;
    $insertAmountBilling->sst = $walletDedArray['sst_u'];
    $insertAmountBilling->adv_tax = $walletDedArray['adv_tax_u'];
    $insertAmountBilling->sst_percentage = $profileRate->sstpercentage;
    $insertAmountBilling->traderrate = 0;
    $insertAmountBilling->t_acc_rate = 0;
    $insertAmountBilling->commision = $dealerprofileRate->commision;
    $insertAmountBilling->r_commission = $resellerprofileRate->commission;
    $insertAmountBilling->margin = $ct_margin;
    $insertAmountBilling->m_acc_rate = $m_acc_rate;
    $insertAmountBilling->r_acc_rate = $r_acc_rate;
    $insertAmountBilling->d_acc_rate = $d_acc_rate;
    $insertAmountBilling->c_sst = $profileRate->sst;
    $insertAmountBilling->c_adv = $profileRate->adv_tax;
    $insertAmountBilling->c_charges = $profileRate->consumer;
    $insertAmountBilling->c_rates = 0;
    $insertAmountBilling->profit = $profit;
    $insertAmountBilling->receipt = 'logon';
    $insertAmountBilling->receipt_num = 0;
    $insertAmountBilling->profile = $profileGroupName;
    $insertAmountBilling->name = $name;
    $insertAmountBilling->taxname = NULL;
    $insertAmountBilling->trader_id = $currentUser->trader_id;
    $insertAmountBilling->dealerid = $currentUser->dealerid;
    $insertAmountBilling->resellerid = $currentUser->resellerid;
    $insertAmountBilling->manager_id = $currentUser->manager_id;
    $insertAmountBilling->sub_dealer_id = $currentUser->sub_dealer_id;
    $insertAmountBilling->date = date('Y-m-d');
    $insertAmountBilling->charge_on = date('Y-m-d H:i:s');
    $insertAmountBilling->dasti_amount = 0;
    $insertAmountBilling->billing_type = 'amount';
    $insertAmountBilling->card_no = NULL;
    $insertAmountBilling->company_rate = $userInfo->company_rate;
    $insertAmountBilling->wallet_amount = $wallet_bef_charge;
                            //
    $insertAmountBilling->filer_tax = $walletDedArray['filer'];
    $insertAmountBilling->cvas_tax = $walletDedArray['get_taxt_data']['cvas_tax'];
    $insertAmountBilling->tip_tax = $walletDedArray['get_taxt_data']['tip_tax'];
    $insertAmountBilling->fll_tax = $walletDedArray['get_taxt_data']['fll_tax'];
    $insertAmountBilling->static_ip_amount = $userInfo->static_ip_amount;
                            //
    $insertAmountBilling->fll_charges = $walletDedArray['fll_charges'];
    $insertAmountBilling->cvas_charges  = $walletDedArray['cvas_charges'];
    $insertAmountBilling->tip_charges = $walletDedArray['tip_charges'];
    $insertAmountBilling->wallet_deduction = $wallet_deduction;
    $insertAmountBilling->dealer_gross_amount = $walletDedArray['fll_charges'] + $walletDedArray['cvas_charges'] + $walletDedArray['tip_charges'];
    //
    $insertAmountBilling->recharge_from = 'bulk';
    $insertAmountBilling->nas = $assignedNas->nas;

    /////////// profit 
    if($resellerprofileRate->allow_auto_profit == 'yes'){
        //
        $rechargeControlller = new RechargeController();
        //
        $autoProfitData = $rechargeControlller->calculate_auto_profit($resellerprofileRate,$dealerprofileRate,$profileRate,$userInfo->company_rate);
        $insertAmountBilling->reseller_profit = $autoProfitData['reseller_profit'];
        if(Auth::user()->status == "subdealer"){
            $insertAmountBilling->dealer_profit = $autoProfitData['dealer_profit'];
        }
    }
        ////

    $insertAmountBilling->save();

        ////
    return true;
}

//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////
//////////////////////////////////////

public function recharge_logs(request $request){
    //
    $date = $request->get('date');
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
   //
    $whereArray = array();
    //
    if(!empty($dealerid)){
       array_push($whereArray,array('dealerid' , $dealerid));
   }if(!empty($sub_dealer_id)){
       array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));   
   }
    //
   $rechargeUsers = AmountBillingInvoice::where(['date' => $date , 'recharge_from' => 'bulk'])->where($whereArray)->get();  
   //
   foreach($rechargeUsers as $key => $data){
    //
    $userProfile = Profile::where('groupname',$data->profile)->first()->name;
    $userStatus = UserStatusInfo::where('username',$data->username)->first();
    //
    ?>
    <tr>
        <td><?= $key+1;?></td>
        <td><?= $data->username;?></td>
        <td><?= $userProfile;?></td>
        <td><?= date('M d,Y',strtotime($userStatus->card_expire_on));?></td>
        <td><?= date('M d,Y H:i:s',strtotime($data->charge_on));?></td>
        
    </tr>
    <?php
}
}
/////////////////////////
/////////////////////////
/////////////////////////
/////////////////////////
public function create_error_log($consumer,$msg){
    //
    $username = NULL;
    if (Auth::user()) {
        $username = Auth::user()->username;
    }

    $data = array(
        'username' => $username, 
        'message' => $msg, 
        'route_name' => Route::currentRouteName(),
        'route_action' => Route::currentRouteAction(),
        'trace' => null,       
        'consumer' => $consumer,       
    );

    Error::create($data);
    //
}

//////////////////////////
/////////////////////////
///////////////////////////





public function error_logs(){
    //
    $username = Auth::user()->username;
   //
    //
    $errorLogs = DB::table('error_log')->where('username',$username)->where('route_name','users.bulk_recharge.action')->where('consumer','!=', NULL)->get();  
   //
    foreach($errorLogs as $key => $data){
    //
        ?>
        <tr>
            <td><?= $key+1;?></td>
            <td><?= $data->consumer;?></td>
            <td><?= date('M d,Y H:i:s',strtotime($data->created_on));?></td>
            <td><?= $data->message;?></td>

        </tr>
        <?php
    }
}


}

