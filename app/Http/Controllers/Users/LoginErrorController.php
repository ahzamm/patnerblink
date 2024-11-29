<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadPostauth;
use App\model\Users\UserInfo;
use App\model\Users\userAccess;
// 
class LoginErrorController extends Controller
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
    $error_log = array();

    $dealerid = Auth::user()->dealerid;
    $currentStatus = Auth::user()->status;
    $sub_dealer_id =Auth::user()->sub_dealer_id;
    //
    $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
        //
    $whereRadiusArray = array();
    //
    if(!empty($manager_id)){
        array_push($whereRadiusArray,array('radcheck.manager_id' , $manager_id));
    }if(!empty($resellerid)){
        array_push($whereRadiusArray,array('radcheck.resellerid' , $resellerid));
    }if(!empty($dealerid)){
        array_push($whereRadiusArray,array('radcheck.dealerid' , $dealerid));
    }if(!empty($sub_dealer_id)){   
        array_push($whereRadiusArray,array('radcheck.sub_dealer_id' , $sub_dealer_id));   
    }
    //

    $error_log = RadPostauth::select('radcheck.username')->distinct()->where('reply','Access-Reject')
    ->join('radcheck','radcheck.username','radpostauth.username')->where($whereRadiusArray)->where('radcheck.status','user')
    ->get();

    // dd($online);



 //    foreach ($userDealer as $value) {
 //     $dealerids = $value->dealerid;





 //     $online = RadPostauth::where(['reply'=>'Access-Reject','username' => $value->username])->get();
 //     foreach ($online as $value) {
 //         $error_log[] = $value;
 //     }
 //     $mac = UserInfo::where(['username'=> $value->username])->select('mac_address')->get();
 // }

 return view('users.billing.error_log',[
  'error_log' => $error_log

]);

}
}