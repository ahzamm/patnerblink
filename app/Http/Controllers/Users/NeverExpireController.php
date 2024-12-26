<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Users\RechargeController;
use App\model\Users\UserInfo;
use App\model\Users\FreezeAccount;
use App\model\Users\UserStatusInfo;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\UserVerification;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\UserAmount;
use App\model\Users\AssignedNas;
use App\model\Users\UserUsualIP;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\Profile;
use App\model\Users\RaduserGroup;
use App\model\Users\ExpireUser;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\Tax;
use Illuminate\Support\Facades\Route;
use App\model\Users\Error;
use App\MyFunctions;
use Yajra\DataTables\Facades\DataTables;


class NeverExpireController extends Controller{
    //
    public function index(){
        return view('users.never_expire.never_expire_consumers');
    }


    public function getNeverExpireUsers(Request $request)
    {
        if (!MyFunctions::check_access('Never Expire Consumers', Auth::user()->id)) {
            return 'Permission denied';
        }

        $manager_id = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
        $resellerid = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
        $dealerid = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
        $trader_id = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;

        $whereArray = [];

        if (!empty($manager_id)) {
            array_push($whereArray, ['user_info.manager_id', $manager_id]);
        }
        if (!empty($resellerid)) {
            array_push($whereArray, ['user_info.resellerid', $resellerid]);
        }
        if (!empty($dealerid)) {
            array_push($whereArray, ['user_info.dealerid', $dealerid]);
        }
        if (!empty($sub_dealer_id)) {
            array_push($whereArray, ['user_info.sub_dealer_id', $sub_dealer_id]);
        }

        if ($request->ajax()) {
            $consumers = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->join('never_expire', 'user_status_info.username', '=', 'never_expire.username')
                ->where($whereArray)
                ->where('user_info.status', 'user')
                ->where('never_expire.status', 'enable')
                ->select('never_expire.date', 'never_expire.username', 'user_info.firstname', 'user_info.lastname',
                        'user_info.dealerid', 'user_info.id', 'user_info.sub_dealer_id', 'user_status_info.card_charge_on',
                        'user_status_info.card_expire_on')
                ->orderBy('user_status_info.card_expire_on', 'ASC');

            return DataTables::of($consumers)
                ->addColumn('serial', function ($row) {
                    static $sno = 1;
                    return $sno++;
                })
                ->addColumn('consumer_id', function ($row) {
                    return $row->username;
                })
                ->addColumn('full_name', function ($row) {
                    return $row->firstname . ' ' . $row->lastname;
                })
                ->addColumn('last_charged_date', function ($row) {
                    return date('M d,Y', strtotime($row->card_charge_on));
                })
                ->addColumn('current_expiry_date', function ($row) {
                    return date('M d,Y', strtotime($row->card_expire_on));
                })
                ->addColumn('never_expire_till', function ($row) {
                    return date('M Y', strtotime($row->date));
                })
                ->addColumn('contractor', function ($row) {
                    return $row->dealerid;
                })
                ->addColumn('trader', function ($row) {
                    return empty($row->sub_dealer_id) ? 'N/A' : $row->sub_dealer_id;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/users/users/user/'.$row->id.'" class="btn btn-info mb1 btn-xs" style="margin-right:4px"><i class="fa fa-edit"></i> Edit</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.never_expire.never_expire_consumers');
    }

    //

///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


    public function never_expire_update(request $request){
      //
        if(!MyFunctions::check_access('Never Expire Consumers',Auth::user()->id)){
            return '<span class="badge badge-danger">Permission Denied</span>';
        }
        //
        $username = $request->get('username');
        $status = $request->get('status');
        $month = $request->get('month');
        $date = date('Y-m-t',strtotime($month));
        //
        if($status == 'disable'){
            //
            DB::table('never_expire')->where('username',$username)->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s') ]);
            //
        }else if($status == 'enable' && !empty($month)){
            //
            $exist = DB::table('never_expire')->where('username',$username)->first();
            //
            if($exist){
              DB::table('never_expire')->where('username',$username)->update(['status' => $status, 'updated_at' => date('Y-m-d H:i:s'), 'date' => $date]);
          }else{
           DB::table('never_expire')->insert(['username' => $username ,'status' => $status, 'updated_at' => date('Y-m-d H:i:s'), 'date' => $date]);
       }
            //
       return 'Updated Successfully';
            //
   }
        //

}
    //

 ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

public function get_never_expire_consumers(request $request){
        //
    $today = date('Y-m-d');
    $query = DB::table('never_expire')->where('status','enable')->where('date','>=',$today);
        //
    $ne_consumers = $query->whereIn('username', function($query2) use ($today){
        $query2->select('username')
        ->from('user_status_info')
        ->where('card_expire_on','<=',$today);
    })->get();
        //
        // dd($ne_consumers);
        //
    foreach($ne_consumers as $value){
            // echo $value->username.'<br>';
        $this->recharge_it($request,$value->username);
    }
        //
}

///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

public function recharge_it(request $request,$username){
        //
    $successUser = null;
    $totalWalletDed = 0;
    $forConfirmation = null;
        // if(!MyFunctions::check_access('Single Recharge',Auth::user()->id)){
            // $error_msg = 'Error : Permission denied.';
            // $this->create_error_log($username,$error_msg,$panelUsername);
            // return (403, $error_msg);
        // }
    if(empty($username)){
        return ('Error : Kindly selected consumer first.');
    }
        //
    $panelInfo = UserInfo::where('username',$username)->where('status','user')->first();
        //
    if(empty($panelInfo)){
        return ('Error : No consumer found.');
    }
        //
    if(empty($panelInfo->sub_dealer_id)){
        $panelStatus = 'dealer';
        $panelUsername = $panelInfo->dealerid;
    }else{
        $panelStatus = 'subdealer';
        $panelUsername = $panelInfo->sub_dealer_id;
    }
    //
    $contTradInfo = UserInfo::where('username',$panelUsername)->first();
    if(!MyFunctions::check_access('Never Expire Consumers',$contTradInfo->id)){
        $error_msg = 'Error : Permission Denied.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ($error_msg);
    }
    //
    //
    $freezeAccount = 'no';
    $freeze = FreezeAccount::where('username',$panelUsername)->first();
    if($freeze){
        $freezeAccount = $freeze->freeze;
    }if($freezeAccount == 'yes'){
        $error_msg = 'Error : Your account has been freezed.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ($error_msg);
    }
        //
    $total_wallet_deduction = 0;

        //////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////  CHECKING  ///////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////

    $userInfo = UserInfo::where('username',$username)->where('dealerid',$panelInfo->dealerid)->where('sub_dealer_id',$panelInfo->sub_dealer_id)->first();
            //
    if(empty($userInfo)){
        $error_msg = 'Error : Invalid consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    $errorPkg = array('NEW','DISABLED',NULL);
    if(in_array($userInfo->name,$errorPkg)){
        $error_msg = 'Error : Invalid Profile of consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    if($userInfo->profile_amount <= 0 || empty($userInfo->company_rate)){
        $error_msg = 'Error : Kindly set profile amount first of consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
            //
    $userStatusInfo = UserStatusInfo::where('username',$username)->first();
    if(strtotime($userStatusInfo->card_expire_on) > strtotime(date('Y-m-d'))){
        $error_msg = 'Error : Consumer already charged '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    if($panelStatus == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => $panelInfo->dealerid, 'name' => $userInfo->name])->first();
    }else if($panelStatus == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $panelInfo->sub_dealer_id])->where(['name' => $userInfo->name])->first();
    }
        //
    // $isverify = UserVerification::where('username', $username)->first();
    $isverify = UserVerification::where('username', $username)->where(function ($query) {
        $query->where('cnic','!=',NULL)
        ->orWhere('ntn','!=',NULL);
    });
    $isverify = $isverify->first();

    if(empty($isverify) && $profileRate->verify == 'yes' ){
        $error_msg = 'Error : Kindly verify first '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    if ( ($userInfo->company_rate == 'yes') && ($profileRate->base_price > $userInfo->profile_amount) ) {
        $error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);


    }if ( ($userInfo->company_rate == 'no')  && ($profileRate->base_price_ET > $userInfo->profile_amount) ) {
        $error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
    /////// Show error when rates changed
    if(($userInfo->company_rate == 'yes')  && ($userInfo->profile_amount != $profileRate->base_price)){
        $error_msg = 'Error : Profile rate has been changed, kindly update '.$username.' rate once.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);
    }
        //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo,$panelInfo->dealerid);
        //
    $total_wallet_deduction += $walletDedArray['wallet_deduction'];
        //
    $userAmount = UserAmount::where('username', $panelUsername)->first();
    if($userAmount->amount < $total_wallet_deduction){
        $error_msg = 'Error : Sorry you do not have enough amount in your wallet.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);
    }

        /////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////// RECHARGING /////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////

        //
    $userInfo = UserInfo::where('username',$username)->first();
            //
    if($panelStatus == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => $panelInfo->dealerid, 'name' => $userInfo->name])->first();
    }else if($panelStatus == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $panelInfo->sub_dealer_id])->where(['name' => $userInfo->name])->first();
    }
        //

        //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo,$panelInfo->dealerid);
    $wallet_deduction = $walletDedArray['wallet_deduction'];
        //
    if($wallet_deduction <= 0){
        $error_msg = 'Error : Something went wrong. Deduction amount is 0 '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    $userAmount = UserAmount::where('username', $panelUsername)->first();
    if($userAmount->amount < $wallet_deduction){
        $error_msg = 'Error : Sorry you do not have enough amount remaining in your wallet.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    $assignedNas = AssignedNas::where(["id" => $panelInfo->dealerid])->where('nas','!=',NULL)->first();
    if(empty($assignedNas)){
        $error_msg = 'Error : No NAS assigned.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);
    }
    $userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->first();
        //
    if(empty($userUsualIp)){
        $error_msg = 'Error : Sorry no CGN IP available.';
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    $checkIPinRadreply = Radreply::where('value',$userUsualIp->ip)->count();
    if($checkIPinRadreply > 0){
        $error_msg = "IP already in use ".$userUsualIp->ip;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ( $error_msg);

    }
        //
    $uniqueID = $username . str_replace('-', '', date('Y-m-d'));
    $billExist = AmountBillingInvoice::where('userid',$uniqueID)->first();
    if($billExist){
        $error_msg = 'Error : Bill of same month already exist of consumer '.$username;
        $this->create_error_log($username,$error_msg,$panelUsername);
        return ($error_msg);
    }
        //
    $this->lets_recharge_it_now($request,$userInfo->name,$username,$wallet_deduction);

        ////////////
        ////
        // $url='https://api-radius.logon.com.pk/kick/user-dc-api.php?username='.$username;
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "$url");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $result = curl_exec($ch);
        ////////////

    return 'Recharged Successfully';

        ////////////

}

    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////


public function lets_recharge_it_now(Request $request,$profileName,$username,$wallet_deduction){
        ///
        //
    $panelInfo = UserInfo::where('username',$username)->where('status','user')->first();
        //
    if(empty($panelInfo->sub_dealer_id)){
        $panelStatus = 'dealer';
        $currentUser = UserInfo::where('username',$panelInfo->dealerid)->first();
    }else{
        $panelStatus = 'subdealer';
        $currentUser = UserInfo::where('username',$panelInfo->sub_dealer_id)->first();
    }
        //
        ////////// WALLET DEDUCTION ////////////////
    $userAmount = UserAmount::where('username', $currentUser->username)->first();
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
    if( $currentUser->status == "dealer"){
        $profileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid, 'name' => $userInfo->name])->first();
        $s_acc_rate = 0;
        $inv_subdealerrate = 0;
        $ct_margin = 0;
        $subdealer_ppm = 0;

    }else if( $currentUser->status == "subdealer"){
        $profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id])->where(['name' => $userInfo->name])->first();
        $s_acc_rate = $profileRate->base_price_ET;
        $inv_subdealerrate = $profileRate->base_price;
        $ct_margin = $profileRate->margin;
        $subdealer_ppm = $profileRate->partner_profit_margin;
    }

    //
    $dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid, 'name' => $profileName])->first();
    $resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileName])->first();
    $m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileName])->first();
    //
    $commision = $dealerprofileRate->commision;
    $m_acc_rate = $m_profileRate->rate;
    $r_acc_rate = $resellerprofileRate->rate;
    $d_acc_rate = $dealerprofileRate->rate;
    $profit = $d_acc_rate - $r_acc_rate - $commision;
    //
    $walletDedArray = $this->check_deduction_amount($profileRate,$userInfo,$panelInfo->dealerid);

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
    $insertAmountBilling->recharge_from = 'neverExpire';
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


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

public function check_deduction_amount($profileRate,$userInfo,$dealerid){
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

        /////// for never expire
    $check_dealerid = $dealerid;
        //

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


    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////

public function create_error_log($consumer,$msg,$username){
        //
    $data = array(
        'username' => $username,
        'message' => $msg,
        'route_name' => Route::currentRouteName(),
        'route_action' => Route::currentRouteAction(),
        'trace' => null,
        'consumer' => $consumer,
    );
    // dd($data);
    Error::create($data);
    //
}

///////////////////////////////////
public function error_logs(request $request){
    //
    $date = $request->get('date');
    //
    $username = Auth::user()->username;
    //
    $errorLogs = DB::table('error_log')->where('username',$username)->where('route_name','users.get_never_expire_consumers')->where('consumer','!=', NULL)->whereDate('created_on','=',$date)->get();
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


//////////////////////////////////////////
//////////////////////////////////////////
//////////////////////////////////////////
//////////////////////////////////////////

public function get_modal_content(request $request){
    //
    $username = $request->get('username');
    $id = Auth::user()->id;
    $user = UserInfo::where('username',$username)->first();

    $nevereExpireInfo = DB::table('never_expire')->where('username',$user->username)->first();
    $expireInfo = DB::table('user_status_info')->where('username',$user->username)->first();
    //
    $nevereExpireStatus = NULL;
    $nevereExpireDate = NULL;
    $nevereExpireLastUpdatDate = NULL;
    $neverExpireBtn = 'show';
    //
    $panelOf = $user->dealerid;
    if(!empty($user->sub_dealer_id)){
        $panelOf = $user->sub_dealer_id;
    }
    //
    if(!empty($nevereExpireInfo)){
        $nevereExpireStatus = $nevereExpireInfo->status;
        $nevereExpireDate = $nevereExpireInfo->date;
        $nevereExpireLastUpdatDate = $nevereExpireInfo->updated_at;

    }
    $invalidProfile = array('NEW','DISABLED','EXPIRED','TERMINATE');
    //
    if(in_array($user->name, $invalidProfile)){
        return abort(403, 'Error : Invalid or no profile selected');
    }if(!MyFunctions::check_access('Never Expire Consumers',Auth::user()->id)){
        return abort(403, 'Error : Access denied');
    }if($panelOf != Auth::user()->username){
        return abort(403, 'Error : Access denied');
    }


    ?>
    <div class="d-flex" style="display: flex; column-gap:15px;flex-wrap:wrap">

        <div>
          <label  class="form-label">Function (ON / OFF)</label>
          <label class='toggle-label' style="margin-left: 0">
            <input type='checkbox' id="expire_cb" <?= ($nevereExpireStatus == 'enable') ? 'checked' : 'unchecked';?> data-username="<?= $user->username;?>"/>
            <span class='back'>
              <span class='toggle'></span>
              <span class='label on'>Enable</span>
              <span class='label off'>Disable</span>
          </span>
      </label>
  </div>
  <div id="nePopUpInfodiv" class="form-group position-relative <?= ($nevereExpireStatus == 'enable') ? 'visible' : 'hide';?> " style="flex-grow: 1" >
      <label  class="form-label">Select Month</label>
      <input type="text" id="month_input" value="<?= $nevereExpireDate;?>" name="never_expire_month" class="form-control month-input">
      <span style="font-size: 12px">Last Update: <strong> <?= date('d M,Y H:i:s',strtotime($nevereExpireLastUpdatDate));?></strong></span>
      <div style="display: flex; align-items:center; justify-content:space-between;position:absolute;left: -140px;
      width: 118%;bottom:-54px;">
      <p class="mb-0" style="font-size: 12px;">Consumer (ID): <strong><?= $username;?></strong> <span style="white-space:nowrap">Expiry Date: <strong class="blink"><?= date('d M,Y',strtotime($expireInfo->card_expire_on))?></strong></span></p>
      <button type="button" onclick="saveNeverExpireMonth();" class="btn btn-primary btn-sm" id="">Apply</button>
  </div>
</div>

</div>
<div style="display: flex; align-items:center; justify-content:flex-end">
    <div style="white-space:nowrap">
      <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
  </div>
</div>

<?php
}


}
?>
