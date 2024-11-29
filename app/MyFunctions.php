<?php

namespace App;
use DB;
use App\model\Users\UserMenuAccess;
use App\model\Users\SubMenu;
use App\model\Users\FreezeAccount;

class MyFunctions
{
    public static function check_access($submenu,$user_id)
    {
        $access_count = UserMenuAccess::join('sub_menus','user_menu_accesses.sub_menu_id','=','sub_menus.id')->where('user_menu_accesses.status',1)->where('user_id',$user_id)->where('sub_menus.submenu',$submenu)->count();
        if($access_count > 0){
            return true;
        }else{
            return false;
        }
    }
    //
    public static function is_freezed($username){
        $freeze_account = FreezeAccount::where(['username' => $username])->where('freeze','yes')->first();
        if(empty($freeze_account)){
            return false;
        }else{
            return true;
        }
    }
    //
    public static function kick_it($username){
        //
        if($username){
            //
            $url='https://api-radius.logon.com.pk/kick/user-dc-api.php?username='.$username;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "$url"); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            //
        }
        return true;
        //
    }
}

///////// HOW TO USE //////////////
/// Add use App\MyFunctions; on namespace
/// use this function MyFunctions::check_access('Dashboard',1));
/// it will return true or false