<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\UserStatusInfo;
use Validator;
use Session;
use App\SendMessage;
use App\model\Users\User_Support;
use App\model\Users\userAccess;
use App\model\Users\RaduserGroup;
use App\model\Users\RadCheck;
use App\model\Users\Radreply;
use App\model\Users\SubdealerAccess;
use App\model\Users\AccessPermission;
use App\model\Users\UserMenuAccess;



class SubdealerManagementController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {
   
 }
public function allow_Access()
{
  
    if(Auth::user()->status == 'dealer'){
        $users = UserInfo::whereIn('status',['subdealer'])->where('dealerid',Auth::user()->dealerid)->orderby('username')->get();
    }
   
    return view('users.subdealerAccess.allowAccess',compact('users'));
}
public function subdealerAccess(Request $request)
{
    $id = $request->id;
    $data = UserInfo::where('id',$id)->first();


    $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$id)->where('sub_menus.'.$data->status,1);
    $userAccesses = $userAccesses->where('flag','cp')->whereIn('sub_menus.id',[24,25,5,6,7]);
    $userAccesses = $userAccesses->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->orderby('sub_menus.menu_id')->get();
    // dd($userAccesses);
    return view('users.subdealerAccess.show',compact('userAccesses'));
}

public function update(Request $request)
{
    $id = $request->id;
    $submenuid = $request->submenuid;
    $access_status = $request->access_status;
    $userAccess = UserMenuAccess::find($id);
    $user_id = $userAccess->user_id; // dealerid and subdealerid from user Menu Access 
    $dealerid = UserInfo::where('id',$user_id)->first()->dealerid; // find dealer id from User info..
    $data = UserInfo::where('dealerid',$dealerid)->whereIn('status',['subdealer','trader'])->select('id')->get(); // subdealer and trader id related to dealerid..
    if($access_status == 0){
    foreach ($data as $key => $userid) {
      $updateAll = UserMenuAccess::where('user_id',$userid->id)->where('sub_menu_id',$submenuid);
      $updateAll->update([
        'status' =>  $access_status
      ]);
    }
}
    // dd($data);
    $userAccess->status = $access_status;
    $userAccess->save();
    return response()->json(['status' => true]);
}

}