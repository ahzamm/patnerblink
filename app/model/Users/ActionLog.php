<?php

namespace App\model\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class ActionLog extends Model
{
    public static function userActivityLog($class,$func,$other,$session_user)
   {
       $data = DB::table('action_logs')->insert([
           "date" => date('Y-m-d'),
           "time" => date('H:i:s'),
           "user" => Auth::user()->username,
           "session_user" => $session_user,
           "class" => class_basename($class),
           "method" => $func,
           "others" => $other
       ]);
       return $data;
   }
}
