<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\UserInfo;
use App\model\Users\UserMenuAccess;
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
    public function show(Request $request)
    {
        $id = $request->id;
        $data = UserInfo::where('id',$id)->first();
        
        if(Auth::user()->status == 'reseller' || (Auth::user()->status == 'inhouse' && Auth::user()->dealerid == NULL)){
            
        $userAccesses = UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_menu_accesses.user_id',$id)->where('sub_menus.'.$data->status,1);
            if(Auth::user()->username == 'logonbroadband'){
                $userAccesses = $userAccesses->whereIn('flag',['cp','x2']);
            }else{
                $userAccesses = $userAccesses->where('flag','cp');
                // dd($userAccesses);
            }
            $userAccesses = $userAccesses->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->orderby('sub_menus.menu_id')->get();
        // dd($userAccesses);
        }else{
            $userAccess = UserMenuAccess::where('user_id',Auth::id())->where('status',1)->select('sub_menu_id')->get()->toArray();
            $userAccesses =  UserMenuAccess::join('sub_menus','sub_menus.id','=','user_menu_accesses.sub_menu_id')->where('user_id',$id)->whereIn('sub_menu_id',$userAccess)->select('user_menu_accesses.id','user_menu_accesses.status','sub_menus.submenu','sub_menus.id as sbID','sub_menus.menu_id')->orderby('sub_menus.menu_id')->get();
        }
        //
        $parentAllow = UserMenuAccess::where('user_id',Auth::user()->id)->where('status',1)->get()->toArray();
        $parentAllow = array_column($parentAllow, 'sub_menu_id');
        //
        return view('users.usermenuaccess.show',compact('userAccesses','parentAllow'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $submenuid = $request->submenuid;
        $access_status = $request->access_status;
        $userAccess = UserMenuAccess::find($id);
        $user_id = $userAccess->user_id; // dealerid and subdealerid from user Menu Access 
        $userDetail = UserInfo::where('id',$user_id)->first(); // find dealer id from User info..

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
}
