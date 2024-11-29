<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;

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
 	return view('admin.kickUserView.kickUserView');
 }
 //
 public function kickit(Request $request){
 	$username = $request->username;
 	$url='http://192.168.100.103/api/index.php?username='.$username;
 		//
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url"); 
//
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		echo $username.' Successfully Kicked out';

 }
}
