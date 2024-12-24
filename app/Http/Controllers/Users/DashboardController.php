<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\Profile;
use App\model\Users\ManagerProfileRate;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\TraderProfileRate;
use App\model\Users\RadPostauth;
use App\model\Users\RaduserGroup;
use App\model\Users\UserInfo;
use App\model\Users\UserAmount;
use App\model\Users\UserVerification;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadAcct;
use App\model\Users\Radreply;
use App\model\Users\RadCheck;
use Illuminate\Support\Facades\Auth;
use App\model\Users\UserStatusInfo;
use App\model\Users\Domain;
use App\model\Users\Ticker;
use App\model\Users\AmountBillingInvoice;

class DashboardController extends Controller
{
      /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public $amount = 0;

    public function index(Request $request)
    {
        $cacti_id = 0;
        $status   = Auth::user()->status;
        $id       = Auth::user()->id;

          //
        $profileCollection     = '';
        $currentUser           = '';
        $activeUser            = '';
        $upcoming_expiry_users = null;
        $verified_users        = '';
        $mobVerify             = '';
        $verifyRestricted      = null;
        $onlineUser            = null;
        $diabledUser           = null;
        $invalidLogins         = null;
        $fewonlineUsers        = null;

        $currentUser = UserInfo::find($id);

        if (Auth::user()->status == 'dealer') {
            $checkVerificationRestriction = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])
                ->select('verify')
                ->first();
              //
            if (!empty($checkVerificationRestriction) && $checkVerificationRestriction->verify == 'no') {
                $verifyRestricted = 'Not Restricted';
            }
        } elseif (Auth::user()->status == 'subdealer') {
            $checkVerificationRestriction = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->select('verify')
                ->first();
              //
            if (!empty($checkVerificationRestriction) && $checkVerificationRestriction->verify == 'no') {
                $verifyRestricted = 'Not Restricted';
            }
        }

        $currentdealerid = Auth::user()->manager_id;
          //   $this->CheckNeverExpireDate();

          //   if(strtotime(date('H:i:s')) > strtotime('12:10:00')){
          //     $this->charge_never_expire($currentdealerid);
          // }

        if ($status == 'manager') {
            $currentUser = UserInfo::find($id);
              //$activeUsers = $currentUser->user_status_info;
            $profileCollection = ManagerProfileRate::where(['manager_id' => Auth::user()->manager_id])
                ->orderBy('groupname')
                ->get();
              // for online user
              // $activeUser  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
              // ->where('user_info.manager_id','=',Auth::user()->manager_id)
              // ->where('user_info.status','=','user')
              // ->get();
              //

              // upcoming expire
            $seven_days = date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d'))));
              //
              // $upcoming_expiry_users  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.card_expire_on', '>', NOW())
              // ->where('user_status_info.card_expire_on', '<', $seven_days)
              // ->where('user_info.manager_id','=',Auth::user()->manager_id)
              // ->where('user_info.status','=','user')
              // ->orderBy('user_status_info.card_expire_on','ASC')
              // ->count();
              //
              // $verified_users = 0;
              // $mobVerify = 0;
              //
            $domainDetails = Domain::where('manager_id', Auth::user()->manager_id)->first();
              //
        } elseif ($status == 'reseller') {
            $currentUser = UserInfo::find($id);

              //$activeUsers = $currentUser->user_status_info->count();
            $profileCollection = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])
                ->orderBy('groupname')
                ->get();
              // for online user
              // $activeUser  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
              // ->where('user_info.resellerid','=',Auth::user()->resellerid)
              // ->where('user_info.status','=','user')
              // ->get();
              //

              // upcoming expire
              // $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
              //
              // $upcoming_expiry_users  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.card_expire_on', '>', NOW())
              // ->where('user_status_info.card_expire_on', '<', $seven_days)
              // ->where('user_info.resellerid','=',Auth::user()->resellerid)
              // ->where('user_info.status','=','user')
              // ->orderBy('user_status_info.card_expire_on','ASC')
              // ->count();

              // $verified_users = UserVerification::where(['resellerid' => Auth::user()->resellerid])->count();
              // $mobVerify = UserVerification::where(['resellerid' => Auth::user()->resellerid,'mobile_status' => 1])->count();
              //
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
              //
        } elseif ($status == 'dealer') {
            $currentUser     = UserInfo::find($id);
            $currentdealerid = Auth::user()->dealerid;

              //////// charge never expire users /////////////////
              // if(strtotime(date('H:i:s')) > strtotime('11:10:00')){
              //   $this->charge_never_expire($currentdealerid);
              // }
              //////// charge never expire users /////////////////

              //$activeUsers = $currentUser->user_status_info;
            $profileCollection = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])
                ->orderBy('groupname')
                ->get();
              // for online user
              // $activeUser  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
              // ->where('user_info.dealerid','=',Auth::user()->dealerid)
              // ->where('user_info.status','=','user')
              // ->get();
              //
            $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid, 'status' => 'user'])
                ->select('username')
                ->get();
              //

              //
              // $cacti_id = DB::table('cacti_graph')
              // ->where('user_id','=',Auth::user()->dealerid)
              // ->get();
              // upcoming expire
              // $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
              //
              // $upcoming_expiry_users  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.card_expire_on', '>', NOW())
              // ->where('user_status_info.card_expire_on', '<', $seven_days)
              // ->where('user_info.dealerid','=',Auth::user()->dealerid)
              // ->where('user_info.status','=','user')
              // ->orderBy('user_status_info.card_expire_on','ASC')
              // ->count();

              // $verified_users = UserVerification::where(['dealerid' => Auth::user()->dealerid])->count();
              // $mobVerify = UserVerification::where(['dealerid' => Auth::user()->dealerid,'mobile_status' => 1])->count();
              //
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
              //
        } elseif ($status == 'subdealer') {
            $currentUser = UserInfo::find($id);

              //$activeUsers = $currentUser->user_status_info;
            $profileCollection = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->orderBy('groupname')
                ->get();
              // for online user
              // $activeUser  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
              // ->where('user_info.sub_dealer_id','=',Auth::user()->sub_dealer_id)
              // ->where('user_info.status','=','user')
              // ->get();
              //

              //
              // $cacti_id = DB::table('cacti_graph')
              // ->where('user_id','=',Auth::user()->sub_dealer_id)
              // ->get();

              //
              // upcoming expire
              // $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
              //
              // $upcoming_expiry_users  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.card_expire_on', '>', NOW())
              // ->where('user_status_info.card_expire_on', '<', $seven_days)
              // ->where('user_info.sub_dealer_id','=',Auth::user()->sub_dealer_id)
              // ->where('user_info.status','=','user')
              // ->orderBy('user_status_info.card_expire_on','ASC')
              // ->count();

              // $verified_users = UserVerification::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->count();
              // $mobVerify = UserVerification::where(['sub_dealer_id' => Auth::user()->sub_dealer_id,'mobile_status' => 1])->count();
              //
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
              //
        } elseif ($status == 'trader') {
            $currentUser = UserInfo::find($id);

              //$activeUsers = $currentUser->user_status_info;
            $profileCollection = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->orderBy('groupname')
                ->get();
              // for online user
              // $activeUser  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
              // ->where('user_info.trader_id','=',Auth::user()->trader_id)
              // ->where('user_info.status','=','user')
              // ->get();
              //

              //
              // $cacti_id = DB::table('cacti_graph')
              // ->where('user_id','=',Auth::user()->trader_id)
              // ->get();

              //
              // upcoming expire
              // $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
              //
              // $upcoming_expiry_users  = DB::table('user_info')
              // ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
              // ->where('user_status_info.card_expire_on', '>', NOW())
              // ->where('user_status_info.card_expire_on', '<', $seven_days)
              // ->where('user_info.trader_id','=',Auth::user()->trader_id)
              // ->where('user_info.status','=','user')
              // ->orderBy('user_status_info.card_expire_on','ASC')
              // ->count();

              // $verified_users = UserVerification::where(['trader_id' => Auth::user()->trader_id])->count();
              // $mobVerify = UserVerification::where(['trader_id' => Auth::user()->trader_id,'mobile_status' => 1])->count();
              //
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
              //
        } elseif ($status == 'user') {
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
              //
            $download = RadAcct::select('acctoutputoctets', 'acctstarttime')
                ->where('acctstarttime', '>=', date('Y-m-01 00:00:00'))
                ->where('acctstarttime', '<=', date('Y-m-t 00:00:00'))
                ->where(['username' => Auth::user()->username])
                ->get();
            $upload = RadAcct::select('acctinputoctets', 'acctstarttime')
                ->where('acctstarttime', '>=', date('Y-m-01 00:00:00'))
                ->where('acctstarttime', '<=', date('Y-m-t 00:00:00'))
                ->where(['username' => Auth::user()->username])
                ->get();
            $invoice = AmountBillingInvoice::where('username', Auth::user()->username)
                ->orderBy('date', 'DESC')
                ->take(24)
                ->get();
            $user_status__data = UserStatusInfo::where('username', Auth::user()->username)->first();
              //
            $get__user_data = UserInfo::where('username', Auth::user()->username)->first();

            if ($get__user_data->trader_id != null) {
                $get_parent_data = UserInfo::where('trader_id', $get__user_data->trader_id)
                    ->where('status', 'trader')
                    ->first();
            } elseif ($get__user_data->sub_dealer_id != null) {
                $get_parent_data = UserInfo::where('sub_dealer_id', $get__user_data->sub_dealer_id)
                    ->where('status', 'subdealer')
                    ->first();
            } else {
                $get_parent_data = UserInfo::where('dealerid', $get__user_data->dealerid)
                    ->where('status', 'dealer')
                    ->first();
            }
        } else {
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
        }

          //wallet amount

        $wallet = DB::table('user_amount')
            ->where('user_amount.username', '=', $currentUser->username)
            ->get();

          //graph js api functions

          // $onlineUser = RadAcct::where('acctstoptime',NULL)->orderBy('radacctid')->get();

          // $fewonlineUsers = RadAcct::where('acctstoptime',NULL)->orderBy('radacctid')->take(10)->get();

          //$userStatus = UserStatusInfo::where('card_expire_on', '>', date('Y/m/d'))->count();

        $userStatus = DB::table('user_info')->where('disabled_expired', '!=', 'YES')->count();

          /*
 $activeUser = DB::table('user_info')
   ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
   ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
   ->where('user_info.status','=','user')
   ->get();
    $onlineUserr = RadAcct::select('radacct.username')
   ->join('radreply','radacct.username','=','radreply.username')
   ->where('acctstoptime',null)->where('radreply.manager_id','=',Auth::user()->manager_id)
   ->count();
   */

          //$seven_days=date('Y-m-d', strtotime("+200 days", strtotime(date('Y-m-d'))));
          //$yesterdayonline=  DB::table('user_info')->where('creationdate', '>', NOW())->where('creationdate', '<', $one_day)->count();

          //$upcomingExpire= DB::table('user_info')->where('creationdate', '!=', NOW())->where('creationdate', '<', $seven_days)->count();

          //   $one_day=date('Y-m-d', strtotime("+2 day", strtotime(date('Y-m-d'))));
          //   $totalyesterdayusers= DB::table('user_info')
          //   ->where('user_info.status', '!=','user')
          //   ->where('user_info.creationdate', '!=', $one_day)->count();

          // //dd($totalyesterdayusers);

          //   $totalyesterdaydealers= DB::table('user_info')
          //   ->where('user_info.status', '=','dealer')
          //   ->where('user_info.creationdate', '!=', $one_day)->count();

          //   $totalyesterdaytraders= DB::table('user_info')
          //   ->where('user_info.status', '=','trader')
          //   ->where('user_info.creationdate', '!=', $one_day)->count();

          //   $totalyesterdayactiveusers= DB::table('user_info')
          //   ->where('user_info.status', '=','user')
          //   ->where('user_info.creationdate', '!=', $one_day)->count();

          // talha work

        $authStatus    = Auth::user()->status;
        $manager_id    = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
        $resellerid    = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
        $dealerid      = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
        $trader_id     = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;
          //
          //
        $whereArray = [];
        if (!empty($manager_id)) {
            array_push($whereArray, ['manager_id', $manager_id]);
        }
        if (!empty($resellerid)) {
            array_push($whereArray, ['resellerid', $resellerid]);
        }
        if (!empty($dealerid)) {
            array_push($whereArray, ['dealerid', $dealerid]);
        }
        if (!empty($sub_dealer_id)) {
            array_push($whereArray, ['sub_dealer_id', $sub_dealer_id]);
        }
          //
          // $onlineUser = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereIn('username', function($query){
          //     $query->select('username')
          //     ->from('radacct')
          //     ->where('acctstoptime',NULL);
          // })->get();
          //
        $profileWiseUser = [];

          // if($status != 'inhouse'){

          // $profiles =  DB::table('profiles')->select('name')->get();
          // foreach($profiles as $value){
          //     $userCount = UserInfo::where('status','user')->where($whereArray)->where('name',$value->name)->get()->count();
          //     if($userCount > 0){
          //         array_push($profileWiseUser,[$value->name , $userCount]);
          //     }
          // }

          // }
          ////////////////////////////
        $headline = Ticker::first();

          // $verified_users = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
          //         $query->select('username')
          //         ->from('user_verification')
          //         ->where('cnic','!=','');
          //     })->select('username')->count();
          //
          //
          // $mobVerify = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
          //         $query->select('username')
          //         ->from('user_verification')
          //         ->where('mobile','!=','');
          //     })->select('username')->count();
          //
          //
          // $diabledUser = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
          //         $query->select('username')
          //         ->from('disabled_users')
          //         ->where('status', 'disable');
          //     })->select('username')->count();
          //
          //
          // $invalidLogins = $this->getErrorLog();
          //
        if ($authStatus == 'user') {
            return view('users.dashboard-consumer', [
                'userStatus'        => $userStatus,
                'download'          => $download,
                'upload'            => $upload,
                'invoice'           => $invoice,
                'user_status__data' => $user_status__data,
                'get_parent_data'   => $get_parent_data,
            ]);
        } elseif ($authStatus == 'inhouse') {
            return view('users.dashboard-inhouse', [
                'domainDetails'  => $domainDetails,
                'currentUser'    => $currentUser,
                'invalidLogins'  => $invalidLogins,
                'diabledUser'    => $diabledUser,
                'mobVerify'      => $mobVerify,
                'verified_users' => $verified_users,
            ]);
        } else {
            return view('users.dashboard', [
                'headline' => $headline,
                  // 'totalyesterdayactiveusers'=>$totalyesterdayactiveusers,
                  // 'totalyesterdaytraders'=>$totalyesterdaytraders,
                  // 'totalyesterdaydealers'=>$totalyesterdaydealers,
                  // 'totalyesterdayusers'=>$totalyesterdayusers,
                'fewonlineUser'         => $fewonlineUsers,
                'onlineUser'            => $onlineUser,
                'userStatus'            => $userStatus,
                'wallet'                => $wallet,
                'profileCollection'     => $profileCollection,
                'currentUser'           => $currentUser,
                'activeUser'            => $activeUser,
                'cacti_id'              => $cacti_id,
                'upcoming_expiry_users' => $upcoming_expiry_users,
                'verified_users'        => $verified_users,
                'mobVerify'             => $mobVerify,
                'profileWiseUser'       => $profileWiseUser,
                'domainDetails'         => $domainDetails,
                'verifyRestricted'      => $verifyRestricted,
                'invalidLogins'         => $invalidLogins,
                'diabledUser'           => $diabledUser,
            ]);
        }
    }
    public function getdata()
    {
        $status = Auth::user()->status;
        if ($status == 'manager') {
              // $info_username = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'user'])->select('username')->get();
            $activeUser = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.manager_id', '=', Auth::user()->manager_id)
                ->where('user_info.status', '=', 'user')
                ->get();
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.manager_id', '=', Auth::user()->manager_id)
                ->count();
        } elseif ($status == 'reseller') {
              // $info_username = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'user'])->select('username')->get();
            $activeUser = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.resellerid', '=', Auth::user()->resellerid)
                ->where('user_info.status', '=', 'user')
                ->get();
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->count();
        } elseif ($status == 'dealer') {
              // $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'user'])->select('username')->get();
            $activeUser = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.dealerid', '=', Auth::user()->dealerid)
                ->where('user_info.status', '=', 'user')
                ->get();
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->count();
        } elseif ($status == 'subdealer') {
              // $info_username = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'user'])->select('username')->get();
            $activeUser = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.sub_dealer_id', '=', Auth::user()->sub_dealer_id)
                ->where('user_info.status', '=', 'user')
                ->get();
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->where('radreply.sub_dealer_id', '=', Auth::user()->sub_dealer_id)
                ->count();
        }
          //  $OnlineUser=array();
          //  foreach ($info_username as $value) {
          //   $online = RadAcct::where(['acctstoptime' => NULL, 'username' => $value->username])->get();
          //   foreach ($online as $value) {
          //     $OnlineUser[]=$value;
          //   }

          // }
          //   $totalOnlineUser=count($OnlineUser);

          //  $onlineUserr = RadAcct::select('radacct.username')
          //  ->join('radreply','radacct.username','=','radreply.username')
          //  ->where('acctstoptime',null)->where('radreply.resellerid','=',Auth::user()->resellerid)
          //  ->count();

        $onliness = count($activeUser);
        if ($onlineUserr != 0 && $onliness != 0) {
            $resultOnline = round(($onlineUserr / $onliness) * 100);
        }
        echo json_encode($resultOnline);
    }
      // public function getdata()
      // {
      //   $status = Auth::user()->status;
      //   if($status == 'manager'){
      //   // $info_username = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'user'])->select('username')->get();
      //   $activeUser  = DB::table('user_info')
      //    ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
      //    ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
      //    ->where('user_info.manager_id','=',Auth::user()->manager_id)
      //    ->where('user_info.status','=','user')
      //    ->get();
      //    $onlineUserr = RadAcct::select('radacct.username')
      //    ->join('radcheck','radacct.username','=','radcheck.username')
      //    ->where('acctstoptime',null)->where('radcheck.manager_id','=',Auth::user()->manager_id)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //    ->count();
      //   }else if($status == 'reseller'){
      //     // $info_username = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'user'])->select('username')->get();
      //     $activeUser  = DB::table('user_info')
      //  ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
      //  ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
      //  ->where('user_info.resellerid','=',Auth::user()->resellerid)
      //  ->where('user_info.status','=','user')
      //  ->get();
      //  $onlineUserr = RadAcct::select('radacct.username')
      //    ->join('radcheck','radacct.username','=','radcheck.username')
      //    ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //    ->count();
      //   }else if($status == 'dealer'){
      //     // $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'user'])->select('username')->get();
      //     $activeUser  = DB::table('user_info')
      //  ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
      //  ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
      //  ->where('user_info.dealerid','=',Auth::user()->dealerid)
      //  ->where('user_info.status','=','user')
      //  ->get();
      //  $onlineUserr = RadAcct::select('radacct.username')
      //    ->join('radcheck','radacct.username','=','radcheck.username')
      //    ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //    ->where('radcheck.dealerid','=',Auth::user()->dealerid)
      //    ->count();
      //   }else if($status == 'subdealer'){
      //     // $info_username = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'user'])->select('username')->get();
      //     $activeUser  = DB::table('user_info')
      //  ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
      //  ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
      //  ->where('user_info.sub_dealer_id','=',Auth::user()->sub_dealer_id)
      //  ->where('user_info.status','=','user')
      //  ->get();
      //  $onlineUserr = RadAcct::select('radacct.username')
      //    ->join('radcheck','radacct.username','=','radcheck.username')
      //    ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //    ->where('radcheck.dealerid','=',Auth::user()->dealerid)
      //    ->where('radcheck.sub_dealer_id','=',Auth::user()->sub_dealer_id)
      //    ->count();
      //   }
      //   //  $OnlineUser=array();
      //   //  foreach ($info_username as $value) {
      //   //   $online = RadAcct::where(['acctstoptime' => NULL, 'username' => $value->username])->get();
      //   //   foreach ($online as $value) {
      //   //     $OnlineUser[]=$value;
      //   //   }

      //   // }
      //   //   $totalOnlineUser=count($OnlineUser);

      //   //  $onlineUserr = RadAcct::select('radacct.username')
      //   //  ->join('radreply','radacct.username','=','radreply.username')
      //   //  ->where('acctstoptime',null)->where('radreply.resellerid','=',Auth::user()->resellerid)
      //   //  ->count();

      //      $onliness = count($activeUser);
      //    if($onlineUserr !=0 && $onliness != 0){
      //      $resultOnline = round(($onlineUserr/$onliness)*100);
      //    }
      //     echo json_encode($resultOnline);

      // }
    public function getNumOfOnlineUsers()
    {
        $status = Auth::user()->status;
        if ($status == 'manager') {
              // $info_username = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'user'])->select('username')->get();
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.manager_id', '=', Auth::user()->manager_id)
                ->count();
        } elseif ($status == 'reseller') {
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->count();
              // $info_username = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'user'])->select('username')->get();
        } elseif ($status == 'dealer') {
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->count();
              // $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'user'])->select('username')->get();
        } elseif ($status == 'subdealer') {
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->where('radreply.sub_dealer_id', '=', Auth::user()->sub_dealer_id)
                ->count();
              // $info_username = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'user'])->select('username')->get();
        }
          //  $OnlineUser=array();
          //  foreach ($info_username as $value) {
          //   $online = RadAcct::where(['acctstoptime' => NULL, 'username' => $value->username])->get();
          //   foreach ($online as $value) {
          //     $OnlineUser[]=$value;
          //   }

          // }
          //   $totalOnlineUser=count($OnlineUser);
        echo json_encode($onlineUserr);
    }
      // public function getNumOfOnlineUsers()
      // {
      //   $status = Auth::user()->status;
      //   if($status == 'manager'){
      //   // $info_username = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'user'])->select('username')->get();
      //   $onlineUserr = RadAcct::select('radacct.username')
      //   ->join('radcheck','radacct.username','=','radcheck.username')
      //   ->where('acctstoptime',null)->where('radcheck.manager_id','=',Auth::user()->manager_id)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //   ->count();
      //   }else if($status == 'reseller'){
      //     $onlineUserr = RadAcct::select('radacct.username')
      //    ->join('radcheck','radacct.username','=','radcheck.username')
      //    ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //    ->count();
      //     // $info_username = UserInfo::where(['resellerid' => Auth::user()->resellerid ,'status' => 'user'])->select('username')->get();
      //   }else if($status == 'dealer'){
      //     $onlineUserr = RadAcct::select('radacct.username')
      //     ->join('radcheck','radacct.username','=','radcheck.username')
      //     ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)
      //     ->where('radcheck.dealerid','=',Auth::user()->dealerid)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //     ->count();
      //     // $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'user'])->select('username')->get();
      //   }else if($status == 'subdealer'){
      //     $onlineUserr = RadAcct::select('radacct.username')
      //     ->join('radcheck','radacct.username','=','radcheck.username')
      //     ->where('acctstoptime',null)->where('radcheck.resellerid','=',Auth::user()->resellerid)
      //     ->where('radcheck.dealerid','=',Auth::user()->dealerid)
      //     ->where('radcheck.sub_dealer_id','=',Auth::user()->sub_dealer_id)->where('radcheck.attribute','Cleartext-Password')->where('radcheck.status','user')
      //     ->count();
      //     // $info_username = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'user'])->select('username')->get();
      //   }
      //   //  $OnlineUser=array();
      //   //  foreach ($info_username as $value) {
      //   //   $online = RadAcct::where(['acctstoptime' => NULL, 'username' => $value->username])->get();
      //   //   foreach ($online as $value) {
      //   //     $OnlineUser[]=$value;
      //   //   }

      //   // }
      //   //   $totalOnlineUser=count($OnlineUser);
      //   echo json_encode($onlineUserr);

      // }
    public function getDisabledUser()
    {
        $status = Auth::user()->status;
        if ($status == 'manager') {
            $userDealer = RadCheck::select('radcheck.username')
                ->join('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
                ->where('radusergroup.groupname', 'DISABLED')
                ->where('radcheck.status', 'user')
                ->where('radcheck.manager_id', '=', Auth::user()->manager_id)
                ->distinct('radcheck.username')
                ->get();
              // $userDealer = UserInfo::where(['manager_id' => Auth::user()->manager_id ,'status' => 'user'])->select('username')->get();
        } elseif ($status == 'reseller') {
            $userDealer = RadCheck::select('radcheck.username')
                ->join('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
                ->where('radusergroup.groupname', 'DISABLED')
                ->where('radcheck.status', 'user')
                ->where('radcheck.resellerid', '=', Auth::user()->resellerid)
                ->distinct('radcheck.username')
                ->get();
        } elseif ($status == 'dealer') {
            $userDealer = RadCheck::select('radcheck.username')
                ->join('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
                ->where('radusergroup.groupname', 'DISABLED')
                ->where('radcheck.status', 'user')
                ->where('radcheck.resellerid', '=', Auth::user()->resellerid)
                ->where('radcheck.dealerid', '=', Auth::user()->dealerid)
                ->distinct('radcheck.username')
                ->get();
              // $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'status' => 'user'])->select('username')->get();
        } elseif ($status == 'subdealer') {
            $userDealer = RadCheck::select('radcheck.username')
                ->join('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
                ->where('radusergroup.groupname', 'DISABLED')
                ->where('radcheck.status', 'user')
                ->where('radcheck.resellerid', '=', Auth::user()->resellerid)
                ->where('radcheck.dealerid', '=', Auth::user()->dealerid)
                ->where('radcheck.sub_dealer_id', '=', Auth::user()->sub_dealer_id)
                ->distinct('radcheck.username')
                ->get();
              // $userDealer = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'status' => 'user'])->select('username')->get();
        }

        $userDealer = count($userDealer);
        echo json_encode($userDealer);
    }

    public function getErrorLog()
    {
          //
        $manager_id    = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
        $resellerid    = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
        $dealerid      = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
        $trader_id     = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;
          //;
        $whereRadiusArray = [];
          //
        if (!empty($manager_id)) {
            array_push($whereRadiusArray, ['radcheck.manager_id', $manager_id]);
        }
        if (!empty($resellerid)) {
            array_push($whereRadiusArray, ['radcheck.resellerid', $resellerid]);
        }
        if (!empty($dealerid)) {
            array_push($whereRadiusArray, ['radcheck.dealerid', $dealerid]);
        }
        if (!empty($sub_dealer_id)) {
            array_push($whereRadiusArray, ['radcheck.sub_dealer_id', $sub_dealer_id]);
        }
          //
        $invalidLogin = RadCheck::where('status', 'user')
            ->where('attribute', 'Cleartext-Password')
            ->where($whereRadiusArray)
            ->whereIn('username', function ($query) {
                $query->select('username')->from('radpostauth');
            })
            ->select('username')
            ->get()
            ->toArray();
        return count($invalidLogin);
          //
    }

      ////

    public function charge_never_expire($currentdealerid)
    {
        $getUser = DB::table('user_info')->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')->join('never_expire', 'never_expire.username', 'user_info.username')->where('user_status_info.card_expire_on', '<=', date('Y-m-d'))->where('user_info.status', '=', 'user')->where('user_info.profile', '!=', null)->where('user_info.profile', '!=', 'DISABLED')->where('user_info.profile', '!=', 'EXPIRED')->where('user_info.manager_id', '=', $currentdealerid)->where('user_info.never_expire', '=', 'yes')->where('never_expire.todate', '>=', date('Y-m-d'))->get();
          //
          //

        foreach ($getUser as $value) {
            $username = $value->username;
            $profile  = $value->name;
              // $profile=str_replace('BE-', '', $profile);
              // $profile=str_replace('k', '', $profile);
            $username . $profile;
              //

            $ip   = $_SERVER['REMOTE_ADDR'];
            $data = ['username' => $username, 'profileGroupname' => $profile, 'ipaddress' => $ip];
              // var_dump($data);
            $request = new Request($data);

            $rech_cont = new RechargeController();
            $rech_cont->viewUserRecharge($request);
        }
    }
    public function CheckNeverExpireDate()
    {
        $getExpiredDate = DB::table('user_info')->join('never_expire', 'never_expire.username', 'user_info.username')->where('user_info.status', '=', 'user')->where('user_info.never_expire', '=', 'yes')->where('never_expire.todate', '<', date('Y-m-d'))->get();

        foreach ($getExpiredDate as $key => $value) {
            $username = $value->username;
            $user     = UserInfo::where('username', $username);
            $user->update([
                'never_expire' => null,
            ]);
        }
    }

      // }
      ////
}
