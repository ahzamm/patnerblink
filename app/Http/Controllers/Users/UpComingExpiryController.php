<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\userAccess;
use Yajra\DataTables\DataTables;
class UpComingExpiryController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(){
    return view('users.billing.upcoming_expiry');
}

public function getUpcomingExpiryData(Request $request)
{
    $manager_id = Auth::user()->manager_id ?? null;
    $resellerid = Auth::user()->resellerid ?? null;
    $dealerid = Auth::user()->dealerid ?? $request->contractor;
    $sub_dealer_id = Auth::user()->sub_dealer_id ?? $request->trader;
    $searchFilter = $request->searchFilter;
    $dateFilter = $request->dateFilter;

    $whereArray = [];
    if ($manager_id) {
        // Explicitly specify the table name to avoid ambiguity
        $whereArray[] = ['user_info.manager_id', $manager_id]; // Updated
    }
    if ($resellerid) {
        $whereArray[] = ['user_info.resellerid', $resellerid]; // Updated
    }
    if ($dealerid) {
        $whereArray[] = ['user_info.dealerid', $dealerid]; // Updated
    }
    if ($sub_dealer_id) {
        $whereArray[] = ['user_info.sub_dealer_id', $sub_dealer_id]; // Updated
    }

    $seven_days = date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
    $expiry_users = DB::table('user_info')
        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
        ->where('user_status_info.card_expire_on', '>=', date('Y-m-d'))
        ->where('user_status_info.card_expire_on', '<=', $seven_days)
        ->where($whereArray)
        ->when(!empty($searchFilter), function ($query) use ($searchFilter) {
            $query->where(function ($subQuery) use ($searchFilter) {
                $subQuery->where('user_info.username', 'LIKE', '%' . $searchFilter . '%')
                    ->orWhere('user_info.firstname', 'LIKE', '%' . $searchFilter . '%')
                    ->orWhere('user_info.lastname', 'LIKE', '%' . $searchFilter . '%')
                    ->orWhere('user_info.address', 'LIKE', '%' . $searchFilter . '%');
            });
        })
        ->where('user_info.status', '=', 'user')
        ->orderBy('user_status_info.card_expire_on', 'ASC')
        ->select([
            DB::raw('ROW_NUMBER() OVER(ORDER BY user_status_info.card_expire_on ASC) AS serial'),
            'user_info.username',
            DB::raw('CONCAT(user_info.firstname, " ", user_info.lastname) AS fullname'),
            'user_info.mobilephone',
            'user_info.address',
            DB::raw('DATEDIFF(user_status_info.card_expire_on, CURDATE()) AS remaining_days'),
            'user_info.name AS profile',
            'user_status_info.card_charge_on',
            'user_status_info.card_expire_on',
            'user_info.dealerid',
            DB::raw('IFNULL(user_info.sub_dealer_id, "N/A") AS sub_dealer_id')
        ]);

    return DataTables::of($expiry_users)->make(true);
}
}
