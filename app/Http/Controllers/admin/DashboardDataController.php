<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Ticker;
use App\model\Users\UserInfo;
use App\model\Users\ProfileMargins;
use App\model\Users\Profile;
use Illuminate\Support\Facades\DB;
use App\model\Users\RadCheck;
use App\model\Users\RadAcct;
use App\model\Users\RaduserGroup;
use App\model\Users\UserStatusInfo;


class DashboardDataController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function where_info(){
   //
   $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
   $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
   $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
   $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
   $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
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
 //
 return $whereArray;
 //
}
//
public function where_radcheck(){
   //
   $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
   $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
   $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
   $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
   $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
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
 return $whereRadiusArray;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function index(){
  return view('users.dashboardtest');
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function total_consumer(){
   //
   return $totalSubs = DB::table('user_info')->where('status' , 'user')->count();
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
//
public function active_consumer(){
   //
   $activeUser  = UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))->count();
   //
   return $activeUser;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
//
public function online_consumer(){
   //
   $onlineUser = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->whereIn('username', function($query){
    $query->select('username')
    ->from('radacct')
    ->where('acctstoptime',NULL);
 })->count();
   //
   $onlinePercentage = ($this->active_consumer() == 0) ? 0 : number_format(($onlineUser/$this->active_consumer() )*100);
   //
   $data['count'] = $onlineUser;
   $data['percentage'] = $onlinePercentage;
   return $data;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function upcoming_expire_consumer(){
   //
   $whereArray  = $this->where_info();
   $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
   $upcoming_expiry_users  = DB::table('user_info')
   ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
   ->where('user_status_info.card_expire_on', '>', NOW())
   ->where('user_status_info.card_expire_on', '<', $seven_days)
   ->where($whereArray)
   ->where('user_info.status','=','user')
   ->count();
   //
   return $upcoming_expiry_users;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function verified_mobile(){
   //
   $whereArray  = $this->where_info();
   $mobVerify = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
    $query->select('username')
    ->from('user_verification')
    ->where('mobile','!=','');
 })->select('username')->count();
   //
   return $mobVerify;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function verified_cnic(){
   //
   $whereArray  = $this->where_info();
   $verified_users = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
    $query->select('username')
    ->from('user_verification')
    ->where('cnic','!=','');
 })->select('username')->count();
   //
   return $verified_users;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function invalid_login(){
   //
   $whereArray  = $this->where_radcheck();
   $invalidLogin = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereIn('username', function($query){
    $query->select('username')
    ->from('radpostauth');
 })->select('username')->get()->toArray();
   //
   return count($invalidLogin);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////
public function disabled_consumer(){
   //
   $diabledUser= RaduserGroup::where('groupname', '=', 'DISABLED')->count();
   //
   return $diabledUser;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
public function reseller_count(){
 $count = DB::table('user_info')->where('status' , 'reseller')->count();
 return $count;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
public function contractor_count(){
 $count = DB::table('user_info')->where('status' , 'dealer')->count();
 return $count;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
public function trader_count(){
 $count = DB::table('user_info')->where('status' , 'subdealer')->count();
 return $count;
}
/////////////////////////////////////////////////////////////////////////////////////////////////
public function subtrader_count(){
 $whereArray  = $this->where_info();
 $count = DB::table('user_info')->where('status' , 'trader')->where($whereArray)->count();
 return $count;
}
//
public function offline_consumer(){
   $activeCount = $this->active_consumer();
   $onlinCount = $this->online_consumer();
   //
   return $offline = $activeCount - $onlinCount['count'];
}
//
public function expired_consumer(){
   $whereArray  = $this->where_info();
   $expired_users  = DB::table('user_info')
   ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
   ->where('user_status_info.card_expire_on', '>', '2000-01-01')
   ->where('user_status_info.card_expire_on', '<', date('Y-m-d'))
   ->where($whereArray)
   ->where('user_info.status','=','user')
   ->count();
   return $expired_users;
}
//
public function suspicious_consumer(){
   $suspiciousUserCount = 0;
   $whereArray  = $this->where_radcheck();
   //
   $suspiciousUser = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereNotIn('radcheck.username', function($query){
    $query->select('radacct.username')
    ->from('radacct')
    ->where('radacct.acctstoptime',NULL);
 })
   ->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['NEW','DISABLED','EXPIRED','TERMINATE'])
   ->select('radcheck.username')->get();
    //
   $now = date('Y-m-d H:i:s');
    //
   foreach($suspiciousUser as $sus){
    $hourdiff = '';
    $lastLoginDetail = RadAcct::where('username',$sus->username)->orderBy('radacctid','DESC')->first();
    if($lastLoginDetail){
      $hourdiff = round((strtotime($now) - strtotime($lastLoginDetail->acctstoptime))/3600, 1);
   }
        //
   if(empty($hourdiff) || $hourdiff > 24){
      $suspiciousUserCount++;
   }
}
//
return $suspiciousUserCount;
}
/////////////////////////

public function profile_wise_user_count_graph(){

   $data['profile'] = array();
   $data['count'] = array();

   $profiles =  DB::table('profiles')->select('name')->get();
   foreach($profiles as $value){
      // 
      $userCount  = DB::table('user_info')
      ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
      ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
      ->where('user_info.name',$value->name)
      ->where('user_info.status','=','user')
      ->count();

      if($userCount > 0){
         // 
       array_push($data['profile'],$value->name);
       array_push($data['count'],$userCount);

    }
 }
    //
 return json_encode($data);

}
////////////////////////////////

public function system_graph(){

    //////////// SYSTEM USAGE GRAPH DATA ////////////////////
  $data['diskfree'] = round(disk_free_space(".") / 1000000000);
  $data['disktotal'] = round(disk_total_space(".") / 1000000000);
  $data['diskused'] = round($data['disktotal'] - $data['diskfree']);

  $data['freePer'] = round(($data['diskfree']/$data['disktotal'])*100);
  $data['usedPer'] = round(($data['diskused']/$data['disktotal'])*100);
            //
            // 
  $free = shell_exec('free');
  $free = (string)trim($free);
  $free_arr = explode("\n", $free);
  $mem = explode(" ", $free_arr[1]);
            $mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
            $mem = array_merge($mem); // puts arrays back to [0],[1],[2] after filter removes nulls
            
            // print_r($mem); echo '<hr>';
            $memtotal = round($mem[1] / 1000000,2);
            $memused = round($mem[2] / 1000000,2);
            $memfree = round($mem[3] / 1000000,2);
            
            $membuffer = round($mem[5] / 1000000,2);
            $memcached = round($mem[6] / 1000000,2);
            
            $memusage = round(($memused - $memcached - $membuffer)/$memtotal*100,2);
            
            $data['memtotal'] = round($mem[1] / 1000000,2);
            $data['memused'] = round($mem[2] / 1000000,2);
            $data['memfree'] = round($mem[3] / 1000000,2);
            $data['membuffer'] = round($mem[5] / 1000000,2);
            $data['memcached'] = round($mem[6] / 1000000,2);
            
            $data['memusage'] = round(($memused - $memcached - $membuffer)/$memtotal*100,2);

            // Server Load time
            $start_time = microtime(TRUE);
            $end_time = microtime(TRUE);
            $time_taken = $end_time - $start_time;
            $data['total_time'] = round($time_taken,4);

            // CPU Load
            $load = sys_getloadavg();
            $cupload = $load[0];
            $data['cpuload'] = $load[0];
            $data['totalLoad'] = 100 - $cupload;
            /////////////////////////////////////////////////////////////////////
    //
            return json_encode($data);

         }








      }
   ?>