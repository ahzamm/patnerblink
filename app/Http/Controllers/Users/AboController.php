<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;



class AboController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
public function index(){
	$status = Auth::user()->status;
	//
	$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
	$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
	$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
	$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
	$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
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

	$users = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereNotIn('radcheck.username', function($query){
		$query->select('radacct.username')
		->from('radacct')
		->where('radacct.acctstoptime',NULL);
	})
	->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['NEW','DISABLED','EXPIRED','TERMINATE'])
	->select('radcheck.username')->get();
    //
	return view('users.abo.active_but_offline',compact('users'));
}
public function susUserDetails(Request $request)
{
	$username = $request->username;
	$res = UserInfo::join('user_status_info as ui','ui.username','user_info.username')->where('ui.username',$username)->select('user_info.permanent_address','ui.expire_datetime','user_info.name','user_info.firstname','user_info.lastname','user_info.mobilephone')->first();
	return response()->json($res);
}

//

public function mytestfunction(){


	$User = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->whereIn('username', function($query){
        $query->select('username')
        ->from('radusergroup');
        // ->whereNotIn('name',['NEW','DISABLED','TERMINATE','EXPIRED']);
    })->select('username')->count();

    dd($User);


} 


}