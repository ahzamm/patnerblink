<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\model\Users\LoginAudit;
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
use Yajra\DataTables\Facades\DataTables;

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
        $cacti_id              = 0;
        $status                = Auth::user()->status;
        $id                    = Auth::user()->id;
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

            if (!empty($checkVerificationRestriction) && $checkVerificationRestriction->verify == 'no') {
                $verifyRestricted = 'Not Restricted';
            }
        } elseif (Auth::user()->status == 'subdealer') {
            $checkVerificationRestriction = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->select('verify')
                ->first();

            if (!empty($checkVerificationRestriction) && $checkVerificationRestriction->verify == 'no') {
                $verifyRestricted = 'Not Restricted';
            }
        }

        $currentdealerid = Auth::user()->manager_id;

        if ($status == 'manager') {
            $currentUser = UserInfo::find($id);

            $profileCollection = ManagerProfileRate::where(['manager_id' => Auth::user()->manager_id])
                ->orderBy('groupname')
                ->get();

            $seven_days = date('Y-m-d', strtotime('+7 days', strtotime(date('Y-m-d'))));

            $domainDetails = Domain::where('manager_id', Auth::user()->manager_id)->first();
        } elseif ($status == 'reseller') {
            $currentUser = UserInfo::find($id);

            $profileCollection = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])
                ->orderBy('groupname')
                ->get();

            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
        } elseif ($status == 'dealer') {
            $currentUser     = UserInfo::find($id);
            $currentdealerid = Auth::user()->dealerid;

            $profileCollection = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])
                ->orderBy('groupname')
                ->get();

            $info_username = UserInfo::where(['dealerid' => Auth::user()->dealerid, 'status' => 'user'])
                ->select('username')
                ->get();

            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
        } elseif ($status == 'subdealer') {
            $currentUser = UserInfo::find($id);

            $profileCollection = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->orderBy('groupname')
                ->get();

            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
        } elseif ($status == 'trader') {
            $currentUser = UserInfo::find($id);

            $profileCollection = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])
                ->orderBy('groupname')
                ->get();

            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();
        } elseif ($status == 'user') {
            $domainDetails = Domain::where('resellerid', Auth::user()->resellerid)->first();

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

        $wallet = DB::table('user_amount')
            ->where('user_amount.username', '=', $currentUser->username)
            ->get();

        $userStatus = DB::table('user_info')->where('disabled_expired', '!=', 'YES')->count();

        $authStatus    = Auth::user()->status;
        $manager_id    = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
        $resellerid    = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
        $dealerid      = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
        $trader_id     = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;

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
        $profileWiseUser = [];
        $headline        = Ticker::first();

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
                'headline'              => $headline,
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

        $onliness = count($activeUser);
        if ($onlineUserr != 0 && $onliness != 0) {
            $resultOnline = round(($onlineUserr / $onliness) * 100);
        }
        echo json_encode($resultOnline);
    }

    public function getNumOfOnlineUsers()
    {
        $status = Auth::user()->status;
        if ($status == 'manager') {
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
        } elseif ($status == 'dealer') {
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->count();
        } elseif ($status == 'subdealer') {
            $onlineUserr = RadAcct::select('radacct.username')
                ->join('radreply', 'radacct.username', '=', 'radreply.username')
                ->where('acctstoptime', null)
                ->where('radreply.resellerid', '=', Auth::user()->resellerid)
                ->where('radreply.dealerid', '=', Auth::user()->dealerid)
                ->where('radreply.sub_dealer_id', '=', Auth::user()->sub_dealer_id)
                ->count();
        }

        echo json_encode($onlineUserr);
    }

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
        }

        $userDealer = count($userDealer);
        echo json_encode($userDealer);
    }

    public function getErrorLog()
    {
        $manager_id    = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
        $resellerid    = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
        $dealerid      = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
        $trader_id     = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;

        $whereRadiusArray = [];

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
    }

    public function charge_never_expire($currentdealerid)
    {
        $getUser = DB::table('user_info')->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')->join('never_expire', 'never_expire.username', 'user_info.username')->where('user_status_info.card_expire_on', '<=', date('Y-m-d'))->where('user_info.status', '=', 'user')->where('user_info.profile', '!=', null)->where('user_info.profile', '!=', 'DISABLED')->where('user_info.profile', '!=', 'EXPIRED')->where('user_info.manager_id', '=', $currentdealerid)->where('user_info.never_expire', '=', 'yes')->where('never_expire.todate', '>=', date('Y-m-d'))->get();

        foreach ($getUser as $value) {
            $username = $value->username;
            $profile  = $value->name;

            $username . $profile;

            $ip   = $_SERVER['REMOTE_ADDR'];
            $data = ['username' => $username, 'profileGroupname' => $profile, 'ipaddress' => $ip];

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

    public function getLoginLogs(Request $request)
    {
        $manager_id    = Auth::user()->manager_id ?? null;
        $resellerid    = Auth::user()->resellerid ?? null;
        $dealerid      = Auth::user()->dealerid ?? $request->contractor;
        $sub_dealer_id = Auth::user()->sub_dealer_id ?? $request->trader;

        $whereArray = [];
        if ($manager_id) {
            $whereArray[] = ['manager_id', '=', $manager_id];
        }
        if ($resellerid) {
            $whereArray[] = ['resellerid', '=', $resellerid];
        }
        if ($dealerid) {
            $whereArray[] = ['dealerid', '=', $dealerid];
        }
        if ($sub_dealer_id) {
            $whereArray[] = ['sub_dealer_id', '=', $sub_dealer_id];
        }

        $query = LoginAudit::join('user_info', 'login_audit.username', '=', 'user_info.username')
            ->where($whereArray)
            ->where('user_info.status', 'user')
            ->select(
                'login_audit.username',
                'login_audit.login_time',
                'login_audit.status',
                'login_audit.ip',
                'login_audit.platform',
                'login_audit.os'
            )
            ->orderBy('login_audit.login_time', 'desc');

        return DataTables::of($query)
            ->editColumn('login_time', function ($log) {
                return \Carbon\Carbon::parse($log->login_time)->format('Y-m-d H:i:s');
            })
            ->make(true);
    }
}
