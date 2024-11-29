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
use App\model\Users\exceedData;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;
use App\model\Users\Profile;
use Validator;
use Session;
use App\SendMessage;
use App\model\Users\Radreply;
use Eihror\Compress\Compress;


class liveUserController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {}
 public function live(){

    $online = '';

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
    $online = RadAcct::select('radacct.acctstarttime','radacct.username','radacct.framedipaddress')
        ->join('radcheck','radacct.username','radcheck.username')
        ->where('radcheck.attribute','Cleartext-Password')
        ->where('radcheck.status','user')
        ->where($whereRadiusArray)
        ->where(['radacct.acctstoptime' => NULL])->orderBy('radacct.acctstarttime','DESC')
        ->take(20)->get();

     return json_encode($online);
 } 
 
 ////////////////////////////////////////////////////////
public function exceedData(){

    $dealerid = Auth::user()->dealerid;
    $subdealerid = Auth::user()->sub_dealer_id;
    $exceed = UserInfo::where('dealerid',$dealerid)
    ->where('sub_dealer_id',$subdealerid)
    ->where('qt_used','!=','')
    ->where('qt_total','!=','')
    ->where('qt_used','>','qt_total')
    ->get()->take(30);
    
    foreach ($exceed as $key => $value) {
        $profile = str_replace("BE-","",$value['profile']);
        $profile = str_replace("k","",$profile);
        $profiles =  Profile::select('name')->where('groupname',$profile)->first();
        $dt = round($value['qt_used']/1073741824);
       ?>
        <tr>
        <td class='text-primary'><b><?=$value['username'];?></b></td>
        <td style='color: #f00;'><?=$profiles['name'];?></td><td><?= $dt." GB"; ?></td>
        </tr>
       <?php
      
    }
}
}
?>