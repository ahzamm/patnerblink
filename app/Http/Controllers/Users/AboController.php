<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;
use DateTime;

class AboController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function index()
    {
        return view('users.abo.active_but_offline');
    }

    public function fetchAboUsers(Request $request)
    {
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

        $draw             = $request->input('draw');
        $start            = $request->input('start');
        $length           = $request->input('length');
        $searchValue      = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir         = $request->input('order.0.dir', 'asc');

        $columnMap = [
            0 => 'radcheck.username',
            1 => 'radcheck.username',
            2 => 'radacct.acctstoptime',
            4 => 'radcheck.sub_dealer_id',
        ];

        $orderColumn = $columnMap[$orderColumnIndex] ?? 'radcheck.username';

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

        $query = RadCheck::where('radcheck.status', 'user')
                ->whereIn('radcheck.username', $Users)
                ->where('radcheck.attribute', 'Cleartext-Password')
                ->whereNotIn('radcheck.username', function ($query) {
                    $query->select('radacct.username')->from('radacct')->whereNull('radacct.acctstoptime');
                })
                ->join('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
                ->leftJoin('radacct', 'radcheck.username', '=', 'radacct.username')
                ->whereNotIn('radusergroup.groupname', ['NEW', 'DISABLED', 'EXPIRED', 'TERMINATE'])
                ->select('radcheck.username', 'radacct.acctstoptime')
                ->skip($start)
                ->take($length);

        if ($searchValue) {
            $query->where('radcheck.username', 'like', '%' . $searchValue . '%');
        }

        if ($orderColumn) {
            $query->orderBy($orderColumn, $orderDir);
        }

        $totalRecords         = RadCheck::where('radcheck.status', 'user')->count();
        $totalFilteredRecords = $query->count();
        $users                = $query->get();
        $data                 = [];
        $count                = $start + 1;

        foreach ($users as $user) {
            $lastLoginDetail = RadAcct::where('username', $user->username)
                ->orderBy('radacctid', 'DESC')
                ->first();

            $lastLogin_datetime = 'Not logged in yet';
            $session_time       = '';
            $hourdiff           = '';

            if ($lastLoginDetail) {
                $datetime1 = new DateTime($lastLoginDetail->acctstoptime);
                $datetime2 = new DateTime('now');
                $interval  = $datetime1->diff($datetime2);
                $Day       = $interval->format('%dD');
                if ($Day > 1) {
                    $session_time = $interval->format('%d Days');
                }
                $lastLogin_datetime = $lastLoginDetail->acctstoptime;
                $hourdiff = round((strtotime('now') - strtotime($lastLoginDetail->acctstoptime)) / 3600, 1);
            }

            if ($hourdiff > 24 || empty($lastLoginDetail)) {
                $userDetail    = UserInfo::where('username', $user->username)->first();
                $sub_dealer_id = $userDetail->sub_dealer_id ?? 'My Users';

                $data[] = [
                    'DT_RowId'      => 'row_' . $user->username,
                    'count'         => $count++,
                    'username'      => $user->username,
                    'last_login'    => $lastLogin_datetime == 'Not logged in yet' ? $lastLogin_datetime : date('M d,Y H:i:s', strtotime($lastLogin_datetime)),
                    'session_time'  => $session_time ?: 'N/A',
                    'sub_dealer_id' => $sub_dealer_id,
                    'action'        => '<button class="btn btn-sm btn-primary" onclick="showDetails(\'' . $user->username . '\')"><i class="fa fa-eye"></i> Check Detail</button>',
                ];
            }
        }

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFilteredRecords,
            'data'            => $data,
        ]);
    }
    public function susUserDetails(Request $request)
    {
        $username = $request->username;
        $res = UserInfo::join('user_status_info as ui', 'ui.username', 'user_info.username')->where('ui.username', $username)->select('user_info.permanent_address', 'ui.expire_datetime', 'user_info.name', 'user_info.firstname', 'user_info.lastname', 'user_info.mobilephone')->first();
        return response()->json($res);
    }

    //

    public function mytestfunction()
    {
        $User = RadCheck::where('status', 'user')
            ->where('attribute', 'Cleartext-Password')
            ->whereIn('username', function ($query) {
                $query->select('username')->from('radusergroup');
                // ->whereNotIn('name',['NEW','DISABLED','TERMINATE','EXPIRED']);
            })
            ->select('username')
            ->count();

        dd($User);
    }
}
