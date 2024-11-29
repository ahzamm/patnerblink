<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\UserInfo;
use App\model\Users\AssignedNas;
use App\model\Users\RaduserGroup;
use App\model\Users\Nas;
use Illuminate\Support\Facades\DB;
use App\model\Users\Radreply;
use Validator;



class MigrationController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {

 }
//
 public function index(){
  $resellers = UserInfo::where('status','reseller')->groupBy('resellerid')->get('resellerid');
  $nas = Nas::all();
  //
  return view('admin.Migration.index',compact('resellers','nas'));
}
//
public function store(Request $request)
{
   $reseller = $request->get('reseller');
   $dealer = $request->get('dealer');
   $nas = $request->get('nas');
   //
   if(empty($reseller) || empty($dealer) || empty($nas) ){
      return abort(403, 'Error : Please select all required fields');  
   }
   //
   $current_ip_info  = DB::table('user_info')
   ->where('resellerid', $reseller)
   ->where('dealerid', $dealer)
   ->select('username')
   ->pluck('username');
   //
   $radReplyUsers = Radreply::select('username','value')->whereIn('username',$current_ip_info)->where('value','!=',NULL)->get();
   //
   $current_ip_count = count($radReplyUsers);
   //
   $migrate_ip_info = DB::table('user_usual_ips')->where('status', '0')->where('nas', $nas)->get();
   //
   if(count($migrate_ip_info) < $current_ip_count){
      return abort(403, 'Not enough IP available from NAS '.$nas);
   }
   //
   /////// giving new IPs & free current IPs ///////////    
   foreach($radReplyUsers as $curr_ip_info_value){
      //
      $current_IP = $curr_ip_info_value->value;
      $username = $curr_ip_info_value->username;
      // free OLD IP
      DB::table('user_usual_ips')->where('ip',$current_IP)->update([ 'status' => 0 ]);
      // get New IP
      $new_ip_info = DB::table('user_usual_ips')->where('status', '0')->where('nas', $nas)->lockForUpdate()->first();
      // assign New IP in ip status
      DB::table('user_ip_status')->where('username',$username)->update([ 'ip' => $new_ip_info->ip ]);
      // assign New IP in ip radreply
      // 
      $radreply = Radreply::where('username',$username)->first();
      $radreply->value = $new_ip_info->ip;
      $radreply->save();
      // consume New IP
      DB::table('user_usual_ips')->where('ip',$new_ip_info->ip)->update([ 'status' => 1 ]);
      //
   }
   return 'Successfully Migrated '.$current_ip_count.' users.';
   //
}
///////////////////////////////////////////////////////////////////////////////////
public function store_OLD(Request $request)
{
   $reseller = $request->get('reseller');
   $dealer = $request->get('dealer');
   $nas = $request->get('nas');
   //
   if(empty($reseller) || empty($dealer) || empty($nas) ){
      return abort(403, 'Error : Please select all required fields');  
   }
   //
   $current_ip_info  = DB::table('user_info')
   ->join('user_ip_status', 'user_ip_status.username', '=', 'user_info.username')
   ->join('user_usual_ips', 'user_ip_status.ip', '=', 'user_usual_ips.ip')
   ->where('user_info.resellerid', $reseller)
   ->where('user_info.dealerid', $dealer)
   ->where('user_ip_status.ip','!=', NULL)
   ->get();
   //
   $current_ip_count = count($current_ip_info);
   //
   $migrate_ip_info = DB::table('user_usual_ips')->where('status', '0')->where('nas', $nas)->get();
   //
   if(count($migrate_ip_info) < $current_ip_count){
      return abort(403, 'Not enough IP available from NAS '.$nas);
   }
   //
   /////// giving new IPs & free current IPs ///////////    
   foreach($current_ip_info as $curr_ip_info_value){
      //
      $current_IP = $curr_ip_info_value->ip;
      $username = $curr_ip_info_value->username;
      // free OLD IP
      DB::table('user_usual_ips')->where('ip',$current_IP)->update([ 'status' => 0 ]);
      // get New IP
      $new_ip_info = DB::table('user_usual_ips')->where('status', '0')->where('nas', $nas)->lockForUpdate()->first();
      // assign New IP in ip status
      DB::table('user_ip_status')->where('username',$username)->update([ 'ip' => $new_ip_info->ip ]);
      // assign New IP in ip radreply
      // 
      $radreply = Radreply::where('username',$username)->first();
      $radreply->value = $new_ip_info->ip;
      $radreply->save();
      // consume New IP
      DB::table('user_usual_ips')->where('ip',$new_ip_info->ip)->update([ 'status' => 1 ]);
      //
   }
   return 'Successfully Migrated '.$current_ip_count.' users.';
   //
}
//////////////////////////////////////////////////////////////////////////////////
//
public function nas(Request $request)
{
   
   $data = AssignedNas::where('id',$request->reseller_nas)->first();
   if($data){
      return  $data->nas;
   }else{
      return 'No NAS assigned';
   }
   

}
public function destroy(Request $request)
{
   $id =$request->get('id');

}
////////////////////////

public function getprofile(Request $request){
   //
   $profiles = array();
   $optionHtml = '';
   $dealerid = $request->dealer;
   //
   $user_info = DB::table('user_info')->where('dealerid', $dealerid)->where('status', 'user')->where('name','!=','')->where('name','!=','NEW')->select('name')->get()->toArray();
   $dealer_profile = DB::table('dealer_profile_rate')->where('dealerid', $dealerid)->select('name')->get();
   //
   foreach($dealer_profile as $valueDealerProfile){
      $optionHtml .= '<option>'.$valueDealerProfile->name.'</option>';
   }
   //
   foreach($user_info as $value){
      array_push($profiles,$value->name);
   }
   $profilesGroup = array_unique($profiles);
   $consumerCount = array_count_values($profiles);
   //
   foreach($profilesGroup as $pkg){ ?>
      <tr>
         <td><input type="hidden" name="currentProfile[]" value="<?= $pkg;?>"><?= $pkg;?></td>
         <td><?= $consumerCount[$pkg];?></td>
         <td><select name="migrateTo[]" class="form-control"><?= $optionHtml;?></select></td>
      </tr>
   <?php }
}



public function migrate_groupname(Request $request){
   //
   $resellerid = $request->reseller;
   $dealerid = $request->dealer;
   $currentProfile = $request->currentProfile;
   $migrateTo = $request->migrateTo;
   //
   foreach($currentProfile as $key => $currentValue){
      /// update user info
      $dealer_profile = DB::table('dealer_profile_rate')->where('dealerid', $dealerid)->where('name', $migrateTo[$key])->first();
      //
      $getUsername  = DB::table('user_info')
      ->where('resellerid', $resellerid)
      ->where('dealerid', $dealerid)
      ->where('name', $currentProfile[$key])
      ->where('status','user')
      ->get();
      //
      foreach($getUsername as $userValue){
         // 
         $raduserGroup = RaduserGroup::where('username' , $userValue->username)->where('name', $currentProfile[$key])->first();
         if($raduserGroup){
            $raduserGroup->groupname = $dealer_profile->groupname;
            $raduserGroup->name = $dealer_profile->name;
            $raduserGroup->save();
         }
      }
      // 
      DB::table('user_info')->where('resellerid', $resellerid)->where('dealerid', $dealerid)->where('status', 'user')->where('name', $currentProfile[$key])->update([ 'name' => $dealer_profile->name, 'profile' => $dealer_profile->groupname ]);
   }
   //
   return 'Migrated Successfully';

}


}


