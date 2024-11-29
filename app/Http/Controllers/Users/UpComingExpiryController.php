<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\userAccess;
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
    $status = Auth::user()->status;
    //
    $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
        //
    $whereArray = array();
    //
    if(!empty($manager_id)){
        array_push($whereArray,array('user_info.manager_id' , $manager_id));
    }if(!empty($resellerid)){
        array_push($whereArray,array('user_info.resellerid' , $resellerid));
    }if(!empty($dealerid)){
        array_push($whereArray,array('user_info.dealerid' , $dealerid));
    }if(!empty($sub_dealer_id)){
        array_push($whereArray,array('user_info.sub_dealer_id' , $sub_dealer_id));  
    }
    ////
        $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
    //
        $expiry_users  = DB::table('user_info')
        ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
        ->where('user_status_info.card_expire_on', '>=', date('Y-m-d'))
        ->where('user_status_info.card_expire_on', '<=', $seven_days)
        ->where($whereArray)
        ->where('user_info.status','=','user')
        ->orderBy('user_status_info.card_expire_on','ASC')
        ->get();

    //
    return view('users.billing.upcoming_expiry' , ['expiry_users' => $expiry_users]);

}
}