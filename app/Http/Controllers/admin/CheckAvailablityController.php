<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;

class CheckAvailablityController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(Request $request){

    if($request->has('username')){
       $username = $request->get('username');
         // 
       $output  = DB::table('user_info')
       ->where('username', '=', $username )
       ->count();
       if($output <= 0){
        echo '<i class="fa fa-check-circle" style="color: green;font-size: 20px;"></i>';
    }else{
        echo '<i class="fa fa-times-circle" style="color: red;font-size: 20px;"></i>';
    }
}

if($request->has('managerid')){
   $managerid = $request->get('managerid');
         // 
   $output  = DB::table('user_info')
   ->where('manager_id', '=', $managerid )
   ->count();
   if($output <= 0){
    echo '<i class="fa fa-check-circle" style="color: green;font-size: 20px;"></i>';
}else{
    echo '<i class="fa fa-times-circle" style="color: red;font-size: 20px;"></i>';
}
}
// 
// reseller

if ($request->has('resellerid')) {

    $resellerid= $request->get('resellerid');
    $output= UserInfo::where('resellerid','=',$resellerid)->count();
    if ($output <= 0) {
      echo '<i class="fa fa-check-circle" style="color: green;font-size: 20px;"></i>';
}else{
    echo '<i class="fa fa-times-circle" style="color: red;font-size: 20px;"></i>';
}
}
// dealer
if ($request->has('dealerid')) {

    $dealerid= $request->get('dealerid');
    $output= UserInfo::where('dealerid','=',$dealerid)->count();
    if ($output <= 0) {
      echo '<i class="fa fa-check-circle" style="color: green;font-size: 20px;"></i>';
}else{
    echo '<i class="fa fa-times-circle" style="color: red;font-size: 20px;"></i>';
}
}



}
}