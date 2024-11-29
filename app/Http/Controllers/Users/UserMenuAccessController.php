<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\UserInfo;
use App\model\Users\UserMenuAccess;
use App\model\Users\SubMenu;
use App\MyFunctions;
use Auth;
// use App\model\Users\ActionLog;

class UserMenuAccessController extends Controller
{
    public function index()
    {
        if(Auth::user()->status == 'manager'){
            $users = UserInfo::whereIn('status',['reseller'])->orderby('username')->get();
        }
        if(Auth::user()->status == 'reseller' || Auth::user()->status == 'inhouse'){
            $users = UserInfo::whereIn('status',['dealer'])->where('resellerid',Auth::user()->resellerid)->orderby('username')->get();
        }
        if(Auth::user()->status == 'dealer'){
            $users = UserInfo::whereIn('status',['subdealer'])->where('dealerid',Auth::user()->dealerid)->orderby('username')->get();
        }
        if(Auth::user()->status == 'subdealer'){
            $users = UserInfo::whereIn('status',['trader'])->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->orderby('username')->get();
        }
        return view('users.usermenuaccess.index',compact('users'));
    }
    // ///////////////////////////////////////////////

    public function update(Request $request)
    {
        if(MyFunctions::is_freezed(Auth::user()->username)){
            return 'Panel has been freezed';      
        }
        //
        $userid = $request->userid;  // user id
        $id = $request->id; // acccess id
        $submenuid = $request->submenuid;  // sub menu id
        $access_status = $request->access_status; // 0 or 1
        $userAccess = UserMenuAccess::find($id); // access detail
        $user_id = $userAccess->user_id; // dealerid and subdealerid from user Menu Access 
        $userDetail = UserInfo::where('id',$userid)->first(); // find dealer id from User info..

        $userStatus = Auth::user()->status;
        if($userStatus == 'manager'){
            if($access_status == 0){
                if($userDetail->status == 'reseller'){
                    $data = UserInfo::where('resellerid',$userDetail->resellerid)->whereIn('status',['reseller','dealer','subdealer','trader','inhouse'])->select('id')->get(); // subdealer and trader id related to dealerid..
                    foreach ($data as $key => $value) {
                      $updateAll = UserMenuAccess::where('user_id',$value->id)->where('sub_menu_id',$submenuid);
                      $updateAll->update([
                        'status' =>  0
                    ]);
                  }
              }else if($userDetail->status == 'inhouse'){
                $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
                $updateAccess->update([
                    'status' =>  0
                ]);
            }
            
        }else if($access_status == 1){
            $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
            $updateAccess->update([
                'status' =>  1
            ]);
        }
    }else if($userStatus == 'reseller'){
        if($access_status == 0){
            if($userDetail->status == 'dealer'){
                    $data = UserInfo::where('dealerid',$userDetail->dealerid)->whereIn('status',['dealer','subdealer','trader','inhouse'])->select('id')->get(); // subdealer and trader id related to dealerid..
                    foreach ($data as $key => $value) {
                      $updateAll = UserMenuAccess::where('user_id',$value->id)->where('sub_menu_id',$submenuid);
                      $updateAll->update([
                        'status' =>  0
                    ]);
                  }
              }else if($userDetail->status == 'subdealer'){
                    $data = UserInfo::where('sub_dealer_id',$userDetail->sub_dealer_id)->whereIn('status',['subdealer','trader','inhouse'])->select('id')->get(); // subdealer and trader id related to dealerid..
                    foreach ($data as $key => $value) {
                      $updateAll = UserMenuAccess::where('user_id',$value->id)->where('sub_menu_id',$submenuid);
                      $updateAll->update([
                        'status' =>  0
                    ]);
                  }
              }else if($userDetail->status == 'inhouse'){
                $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
                $updateAccess->update([
                    'status' =>  0
                ]);
            }
        }else if($access_status == 1){
            $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
            $updateAccess->update([
                'status' =>  1
            ]);
        }
    }else if($userStatus == 'dealer'){
        if($access_status == 0){
            if($userDetail->status == 'subdealer'){
                    $data = UserInfo::where('sub_dealer_id',$userDetail->sub_dealer_id)->whereIn('status',['subdealer','trader','inhouse'])->select('id')->get(); // subdealer and trader id related to dealerid..
                    foreach ($data as $key => $value) {
                      $updateAll = UserMenuAccess::where('user_id',$value->id)->where('sub_menu_id',$submenuid);
                      $updateAll->update([
                        'status' =>  0
                    ]);
                  }
              }else if($userDetail->status == 'inhouse'){
                $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
                $updateAccess->update([
                    'status' =>  0
                ]);
            }
        }else if($access_status == 1){
            $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
            $updateAccess->update([
                'status' =>  1
            ]);
        }
    }else if($userStatus == 'subdealer'){
        if($access_status == 0){
         if($userDetail->status == 'trader'){
                    $data = UserInfo::where('trader_id',$userDetail->trader_id)->whereIn('status',['trader','inhouse'])->select('id')->get(); // subdealer and trader id related to dealerid..
                    foreach ($data as $key => $value) {
                      $updateAll = UserMenuAccess::where('user_id',$value->id)->where('sub_menu_id',$submenuid);
                      $updateAll->update([
                        'status' =>  0
                    ]);
                  }
              }else if($userDetail->status == 'inhouse'){
                $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
                $updateAccess->update([
                    'status' =>  0
                ]);
            }
        }else if($access_status == 1){
            $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
            $updateAccess->update([
                'status' =>  1
            ]);
        }
    }else if($userStatus == 'trader'){
        if($access_status == 0){
         if($userDetail->status == 'inhouse'){
            $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
            $updateAccess->update([
                'status' =>  0
            ]);
        }
    }else if($access_status == 1){
        $updateAccess = UserMenuAccess::where('user_id',$userDetail->id)->where('sub_menu_id',$submenuid);
        $updateAccess->update([
            'status' =>  1
        ]);
    }
}
return response()->json(['status' => true]);
}



public function show_old(Request $request){
    

    $status = Auth::user()->status;
    $id = $request->id;
            //
    if($status == 'manager'){
                //
        $userInfo =  UserInfo::where('status','manager')->where('manager_id',Auth::user()->manager_id)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show all menus
                //
            }if($status == 'reseller'){
                //
                $userInfo =  UserInfo::where('status','reseller')->where('resellerid',Auth::user()->resellerid)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to reseller
                //
            }if($status == 'dealer'){
                //
                $userInfo =  UserInfo::where('status','dealer')->where('dealerid',Auth::user()->dealerid)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to dealer
                //
            }if($status == 'trader'){
                //
                $userInfo =  UserInfo::where('status','trader')->where('trader_id',Auth::user()->trader_id)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to trader
                //
            }
            //
            $allow = UserMenuAccess::where('user_id',$id)->where('status',1)->get()->toArray();
            $allow = array_column($allow, 'sub_menu_id');
            //
            return view('users.usermenuaccess.show',compact('userAccesses','allow','id'));
        }




    // public function show(Request $request){
        public function show($id){
            //
            if(!MyFunctions::check_access('Access Management',Auth::user()->id)) {
                return 'Access Denied';
            }if(MyFunctions::is_freezed(Auth::user()->username)){
                return 'Account has been freezed';      
            }
            //
            $status = Auth::user()->status;
            //
            if($status == 'manager'){
                $userInfo = UserInfo::where('id',$id)->where('manager_id',Auth::user()->manager_id)->first();
                if(empty($userInfo)){
                    return 'Invalid id';
                }
                $username = $userInfo->username;
                $userStatus = $userInfo->status;
                //
                $userInfo =  UserInfo::where('status','manager')->where('manager_id',Auth::user()->manager_id)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show all menus
                //
            }if($status == 'reseller'){
                $userInfo = UserInfo::where('id',$id)->where('resellerid',Auth::user()->resellerid)->first();
                if(empty($userInfo)){
                    return 'Invalid id';
                }
                $username = $userInfo->username;
                $userStatus = $userInfo->status;
                //
                $userInfo =  UserInfo::where('status','reseller')->where('resellerid',Auth::user()->resellerid)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to reseller
                //
            }if($status == 'dealer'){
                $userInfo = UserInfo::where('id',$id)->where('dealerid',Auth::user()->dealerid)->first();
                if(empty($userInfo)){
                    return 'Invalid id';
                }
                $username = $userInfo->username;
                $userStatus = $userInfo->status;
                //
                $userInfo =  UserInfo::where('status','dealer')->where('dealerid',Auth::user()->dealerid)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to dealer
                //
            }if($status == 'subdealer'){

                $userInfo = UserInfo::where('id',$id)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->first();
                if(empty($userInfo)){
                    return 'Invalid id';
                }
                $username = $userInfo->username;
                $userStatus = $userInfo->status;
                //
                $userInfo =  UserInfo::where('status','subdealer')->where('sub_dealer_id',Auth::user()->sub_dealer_id)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to subdealer
                //
            }if($status == 'trader'){
                $userInfo = UserInfo::where('id',$id)->where('trader_id',Auth::user()->trader_id)->first();
                if(empty($userInfo)){
                    return 'Invalid id';
                }
                $username = $userInfo->username;
                $userStatus = $userInfo->status;
                //
                $userInfo =  UserInfo::where('status','trader')->where('trader_id',Auth::user()->trader_id)->first();
                $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$userInfo->id)->where('user_menu_accesses.status',1)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->get(); /// show menus that allow to trader
                //
            }
            //
            $allow = UserMenuAccess::where('user_id',$id)->where('status',1)->get()->toArray();
            $allow = array_column($allow, 'sub_menu_id');
            //
            return view('users.usermenuaccess.access',compact('userAccesses','allow','id','username','userStatus'));
        }
    }
