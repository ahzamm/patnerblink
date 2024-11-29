<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UpComingExpiry extends Controller
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
    $status = Auth::user()->status;
    if($status=='dealer'){
//
        $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
//
        $expiry_users  = DB::table('user_info')
        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
        ->where('user_status_info.card_expire_on', '>', NOW())
        ->where('user_status_info.card_expire_on', '<', $seven_days)
        ->where('user_info.dealerid','=',Auth::user()->dealerid)
        ->where('user_info.status','=','user')
        ->orderBy('user_status_info.card_expire_on','ASC')
        ->get();
    }else if($status=='subdealer'){
        $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
//
        $expiry_users  = DB::table('user_info')
        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
        ->where('user_status_info.card_expire_on', '>', NOW())
        ->where('user_status_info.card_expire_on', '<', $seven_days)
        ->where('user_info.sub_dealer_id','=',Auth::user()->sub_dealer_id)
        ->where('user_info.status','=','user')
        ->orderBy('user_status_info.card_expire_on','ASC')
        ->get();
    }
//
    return view('users.billing.upcoming_expiry' , ['expiry_users' => $expiry_users]);

}
}