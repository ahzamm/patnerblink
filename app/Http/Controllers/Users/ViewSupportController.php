<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;
use App\model\admin\Admin;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;



class ViewSupportController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}

public function disabled_but_online(){
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
        array_push($whereArray,array('radcheck.manager_id' , $manager_id));
    }if(!empty($resellerid)){
        array_push($whereArray,array('radcheck.resellerid' , $resellerid));
    }if(!empty($dealerid)){
        array_push($whereArray,array('radcheck.dealerid' , $dealerid));
    }if(!empty($sub_dealer_id)){
        array_push($whereArray,array('radcheck.sub_dealer_id' , $sub_dealer_id)); 
    }
    //
    $allusers = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereIn('radcheck.username', function($query){
        $query->select('radacct.username')
        ->from('radacct')
        ->where('radacct.acctstoptime',NULL);
    })
    ->join('radusergroup','radcheck.username','radusergroup.username')->whereIn('radusergroup.groupname',['DISABLED'])
    ->select('radcheck.username')->get();
    //
    //
	return view('users.userPanelView.DBO_user',[
		'allusers'=>$allusers
	]);
}



}
