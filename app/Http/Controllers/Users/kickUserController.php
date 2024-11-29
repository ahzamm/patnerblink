<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\MyFunctions;

class kickUserController extends Controller
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
 	if(!MyFunctions::check_access('Kick Consumer',Auth::user()->id)){
        return redirect()->route("users.dashboard");      
    }
    //
    $kick_log = DB::table('kick_consumer_log')->orderBy('datetime','DESC')->take(500)->get();
    //
 	return view('users.kickUserView.kickUserView',['kick_log' => $kick_log]);
 }
 //
 public function kickit(Request $request){
 	//
 	$username = $request->username;
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
 		array_push($whereArray,array('manager_id' , $manager_id));
 	}if(!empty($resellerid)){
 		array_push($whereArray,array('resellerid' , $resellerid));
 	}if(!empty($dealerid)){
 		array_push($whereArray,array('dealerid' , $dealerid));
 	}if(!empty($sub_dealer_id)){
 		array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));   
 	}
 	//
 	$validateUser  = DB::table('user_info')
 	->where($whereArray)
 	->where('username',$username)
 	->count();
       //
 	if($validateUser <= 0){
 		return response('Invalid Username',404);
 	}else{
 	//
 	    // $url='https://api-radius.logon.com.pk/kick/user-dc-api.php?username='.$username;
           //    $ch = curl_init();
           //    curl_setopt($ch, CURLOPT_URL, "$url"); 
           //    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           //    $result = curl_exec($ch);
              MyFunctions::kick_it($username);
		//
		DB::table('kick_consumer_log')->insert(['username' => $username, 'action_by' => Auth::user()->id]);
 		// 
 		return response('<p style="color:green;text-align:center;font-size:18px;margin-top:20px">Successfully Disconnect this Consumer (ID) over Broadband Remote Access Servers (BRAS)</p>');
 	}
 }

}
