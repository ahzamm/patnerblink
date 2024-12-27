<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadPostauth;
use App\model\Users\RadCheck;
use App\model\Users\UserInfo;
use App\model\Users\userAccess;
use Yajra\DataTables\Facades\DataTables;

class LoginErrorController extends Controller
{
    public function index()
    {
        return view('users.billing.error_log');
    }

    public function getErrorLogs(Request $request)
    {
        if ($request->ajax()) {
            $manager_id = Auth::user()->manager_id ?? null;
            $resellerid = Auth::user()->resellerid ?? null;
            $dealerid = Auth::user()->dealerid ?? $request->contractor;
            $sub_dealer_id = Auth::user()->sub_dealer_id ?? $request->trader;
            $searchFilter = $request->searchFilter;
            $dateFilter = $request->dateFilter;

            $whereArray = [];
            if ($manager_id) {
                $whereArray[] = ['user_info.manager_id', $manager_id];
            }
            if ($resellerid) {
                $whereArray[] = ['user_info.resellerid', $resellerid];
            }
            if ($dealerid) {
                $whereArray[] = ['user_info.dealerid', $dealerid];
            }
            if ($sub_dealer_id) {
                $whereArray[] = ['user_info.sub_dealer_id', $sub_dealer_id];
            }

            $Users = UserInfo::where($whereArray)
                ->when(!empty($searchFilter), function ($query) use ($searchFilter) {
                    $query->where(function ($subQuery) use ($searchFilter) {
                        $subQuery->where('username', 'LIKE', '%' . $searchFilter . '%')
                            ->orWhere('user_info.firstname', 'LIKE', '%' . $searchFilter . '%')
                            ->orWhere('user_info.lastname', 'LIKE', '%' . $searchFilter . '%')
                            ->orWhere('user_info.address', 'LIKE', '%' . $searchFilter . '%');
                    });
                })
                ->where('status','user')
                ->pluck('username')->toArray();

            $query = RadPostauth::select('radcheck.username')
                    ->whereIn('radpostauth.username', $Users)
                    ->distinct()->where('reply', 'Access-Reject')
                    ->join('radcheck', 'radcheck.username', '=', 'radpostauth.username');

            return DataTables::of($query)
                ->addColumn('serial', function ($row) {
                    static $sno = 1;
                    return $sno++;
                })
                ->addColumn('mobile_number', function ($row) {
                    $user_data = UserInfo::where(['status' => 'user', 'username' => $row->username])->first();
                    return $user_data->mobilephone ?? 'N/A';
                })
                ->addColumn('assigned_mac', function ($row) {
                    $radcheckmac = RadCheck::where(['username' => $row->username, 'attribute' => 'Calling-Station-Id'])->first();
                    return $radcheckmac ? $radcheckmac->value : 'N/A';
                })
                ->addColumn('requesting_mac', function ($row) {
                    $radacctmac = RadPostauth::where(['username' => $row->username])
                        ->orderBy('id', 'DESC')
                        ->first();
                    return $radacctmac ? $radacctmac->mac : 'Not Yet Login';
                })
                ->addColumn('valid_reason', function ($row) {
                    $radacctmac = RadPostauth::where(['username' => $row->username])
                        ->orderBy('id', 'DESC')
                        ->first();
                    return $radacctmac->rejectreason ?? 'N/A';
                })
                ->addColumn('attempted_password', function ($row) {
                    $radacctmac = RadPostauth::where(['username' => $row->username])
                        ->orderBy('id', 'DESC')
                        ->first();
                    return $radacctmac->pass ?? 'N/A';
                })
                ->addColumn('login_attempt', function ($row) {
                    return RadPostauth::where(['reply' => 'Access-Reject', 'username' => $row->username])->count();
                })
                ->addColumn('login_time', function ($row) {
                    $radacctmac = RadPostauth::where(['username' => $row->username])
                        ->orderBy('id', 'DESC')
                        ->first();
                    return $radacctmac ? date('M d, Y H:i:s', strtotime($radacctmac->authdate)) : 'N/A';
                })
                ->addColumn('actions', function ($row) {
                    $user_data = UserInfo::where(['status' => 'user', 'username' => $row->username])->first();
                    $mac = RadCheck::where(['username' => $user_data->username, 'attribute' => 'Calling-Station-Id'])->first();
                    $actions = '<a href="' .  '" class="btn btn-xs btn-info"><i class="fa fa-edit"></i> Edit</a>';
                    $actions .=
                        '<div class="dropdown action-dropdown">
                                <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                <div class="dropdown-menu action-dropdown_menu">
                                    <ul>
                                        <li class="dropdown-item">
                                            <a href="' .

                        '"><i class="la la-edit"></i> Edit</a>
                                        </li>
                                        <hr style="margin-top: 0">
                                        <li class="dropdown-item">';

                    if ($mac && $mac->value != 'NEW') {
                        $actions .=
                            '<form action="' .
                            route('users.billing.user_detail') .
                            '" method="POST">' .
                            csrf_field() .
                            '
                                    <input type="hidden" name="clearmac" value="' .
                            $user_data->username .
                            '">
                                    <input type="hidden" name="userid" value="' .
                            $user_data->id .
                            '">
                                    <button type="submit" class="btn btn-xs btn-warning">Clear Mac Address</button>
                                </form>';
                    }

                    $actions .=
                        '</li>
                            <li class="dropdown-item">
                                <button class="userpaswd btn btn-xs btn-primary" onclick="change_pass(\'' .
                        $user_data->id .
                        '\', \'' .
                        $user_data->username .
                        '\')"> Change Password</button>
                            </li>
                        </ul>
                    </div>
                </div>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('users.billing.error_log');
    }
}
