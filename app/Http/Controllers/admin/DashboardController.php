<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\model\Users\Profile;
use App\model\Users\UserInfo;
use App\model\Users\RadAcct;
use App\model\Users\UserStatusInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\RaduserGroup;
use App\model\Users\Ticker;
class DashboardController extends Controller
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
        // 
     // $seven_days=date('Y-m-d', strtotime("+7 days", strtotime(date('Y-m-d'))));
        // 
     // $onlineUser = RadAcct::where('acctstoptime',NULL)->orderBy('radacctid')->get(); 
     // $profileCollection = Profile::orderBy('name')->get();
     // $userStatus = UserStatusInfo::where('card_expire_on', '>', date('Y/m/d'))->count();
     // $upcomingExpire= UserStatusInfo::where('card_expire_on', '>', NOW())->where('card_expire_on', '<', $seven_days)->count();
     // $disable_user= RaduserGroup::where('groupname', '=', 'DISABLED')->count();

     $activeUser  = 0;
     $disable_user = 0; 
     $onlineUser = 0 ;
     $onlinePercentage = 0;
    //  if(Auth::user()->status == "super"){
    //      $activeUser  = UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count();
    //  }else if(Auth::user()->status == "admin"){
    //     $activeUser  = DB::table('user_info')
    //     ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
    //     ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
    //     ->where('user_info.resellerid','=','logonbroadband')
    //     ->where('user_info.status','=','user')
    //     ->count();
    // }else if(Auth::user()->status == "administrator"){
    //     $activeUser  = DB::table('user_info')
    //     ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
    //     ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
    //     ->where('user_info.resellerid','=','logonbroadband')
    //     ->where('user_info.status','=','user')
    //     ->count();
    // }
    // else if(Auth::user()->status == "support" || Auth::user()->status == "noc" || Auth::user()->status == "engineering" || Auth::user()->status == "IT"){
    //     $activeUser  = UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count();
    // }
    // else if(Auth::user()->status == "account"){
    //     $activeUser  = UserStatusInfo::where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))->count();
    // }

    // $onlinePercentage = ($activeUser == 0) ? 0 : number_format((count($onlineUser)/$activeUser)*100); 
    //
    // $profileWiseUser = array();
    // $profiles =  DB::table('profiles')->select('name')->get();
    // foreach($profiles as $value){
    //     $userCount = UserInfo::where('status','user')->where('name',$value->name)->get()->count();
    //     if($userCount > 0){
    //         array_push($profileWiseUser,[$value->name , $userCount]);
    //     }
    // }



    // //////////// SYSTEM USAGE GRAPH DATA ////////////////////
    // $data['diskfree'] = round(disk_free_space(".") / 1000000000);
    // $data['disktotal'] = round(disk_total_space(".") / 1000000000);
    // $data['diskused'] = round($data['disktotal'] - $data['diskfree']);

    // $data['freePer'] = round(($data['diskfree']/$data['disktotal'])*100);
    // $data['usedPer'] = round(($data['diskused']/$data['disktotal'])*100);
    //         //
    //         // 
    // $free = shell_exec('free');
    // $free = (string)trim($free);
    // $free_arr = explode("\n", $free);
    // $mem = explode(" ", $free_arr[1]);
    //         $mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
    //         $mem = array_merge($mem); // puts arrays back to [0],[1],[2] after filter removes nulls
            
    //         // print_r($mem); echo '<hr>';
    //         $memtotal = round($mem[1] / 1000000,2);
    //         $memused = round($mem[2] / 1000000,2);
    //         $memfree = round($mem[3] / 1000000,2);
            
    //         $membuffer = round($mem[5] / 1000000,2);
    //         $memcached = round($mem[6] / 1000000,2);
            
    //         $memusage = round(($memused - $memcached - $membuffer)/$memtotal*100,2);
            
    //         $data['memtotal'] = round($mem[1] / 1000000,2);
    //         $data['memused'] = round($mem[2] / 1000000,2);
    //         $data['memfree'] = round($mem[3] / 1000000,2);
    //         $data['membuffer'] = round($mem[5] / 1000000,2);
    //         $data['memcached'] = round($mem[6] / 1000000,2);
            
    //         $data['memusage'] = round(($memused - $memcached - $membuffer)/$memtotal*100,2);

    //         // Server Load time
    //         $start_time = microtime(TRUE);
    //         $end_time = microtime(TRUE);
    //         $time_taken = $end_time - $start_time;
    //         $data['total_time'] = round($time_taken,4);

    //         // CPU Load
    //         $load = sys_getloadavg();
    //         $cupload = $load[0];
    //         $data['cpuload'] = $load[0];
    //         $data['totalLoad'] = 100 - $cupload;
    //         /////////////////////////////////////////////////////////////////////

    return view('admin.dashboards' , [
        // 'profileCollection' => $profileCollection ,
        'onlineUser' => $onlineUser,
        // 'userStatus' => $userStatus,
        // 'upcomingExpire' => $upcomingExpire,
        'disable_user' => $disable_user,
        'activeUser' => $activeUser,
        'onlinePercentage' => $onlinePercentage,
        // 'profileWiseUser' => $profileWiseUser,
        // 'sysGraphData' => $data,
    ]);
}

public function test()
{
   return "done";
}

public function create_headline()
{
    $ticker = Ticker::first();

    return view('admin.tickers.create',compact('ticker'));
}
public function store_headline(Request $request)
{
   
$get_ticker = Ticker::first();

    $update =
         [
            'english_content' => $request['english_content'],
            'urdu_content' => $request['urdu_content'],
            'announcement_content'=>$request['announcement_content'],
            'creation_by' => Auth::user()->username,
         ];
   
    Ticker::where('id',$get_ticker->id)->update($update);
    return redirect()->route('admin.headline')->with('success','Headline Updated Successfully');
}


}
