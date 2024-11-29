<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\RaduserGroup;
use App\model\Users\AssignNasType;
use App\model\Users\ScratchCards;
use App\model\Users\StaticIPServer;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\SubdealerProfileRate;
class ApproveController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index($status){
	switch($status){
		case "dealer" : {
			$dealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid')->where(['status' => 'dealer'])->get();
			return view('admin.users.dealer_approve',[
				'dealerCollection' => $dealerCollection
			]);
		}break;
		case "subdealer" : {
			$subdealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid','sub_dealer_id')->where(['status' => 'subdealer'])->get();
			return view('admin.users.subdealer_approve',
				['subdealerCollection' => $subdealerCollection
		]);
		}break;
		case "verification" : {
			$subdealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid','sub_dealer_id')->where(['status' => 'subdealer'])->get();
			return view('admin.users.subdealer_verify',
				['subdealerCollection' => $subdealerCollection
		]);
		}break;
		case "staticip" : {
			$userCollection = StaticIPServer::where(['status' => 'used'])->get();
			return view('admin.users.approve_static_ip',[
				'userCollection' => $userCollection
			]);
		}break;
		default :{
			return redirect()->route('admin.dashboard');
		}
	}
}

public function approveRadUserGroup(Request $request){
	$isChecked = $request->get('isChecked');
	$username = $request->get('username');
	$radUserGroup = RaduserGroup::where(['username' => $username])->first();
	// dd($radUserGroup);
	if($isChecked == 'false'){
		$radUserGroup->groupname = 'DISABLED';
		$radUserGroup->name = 'DISABLED';
		$radUserGroup->save();
		return ['status' => 'success', 'msg' => 'success fully changed'];
	}elseif($isChecked == 'true'){
		$resellerid = UserInfo::select('resellerid')->where('username' , $username)->first()->resellerid;
		$serverType = AssignNasType::where('resellerid',$resellerid)->first();
		if($serverType){
			switch($serverType->nas_type){
				case "juniper": $groupname = 'BE'; break;
				case "mikrotik": $groupname = 'MT'; break;
				default : {
					$groupname = 'BE';
				}
			}
			$groupname .= '-9216k';
			$radUserGroup->groupname = $groupname;
			$radUserGroup->name = 'ghaznavi';
			$radUserGroup->update();
			return ['status' => 'success', 'msg' => 'success fully changed'];
		}else{
			return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
		}
	}

	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}

public function approveStaticIPUser(Request $request){

	$isChecked = $request->get('isChecked');
	$username = $request->get('userid');
	$radUserGroup = StaticIPServer::where(['userid' => $username])->first();

	$static_ip = $radUserGroup->ipaddress;
	if($isChecked == 'false'){
		$radUserGroup->status = 'NEW';
		$radUserGroup->userid = NULL;
		$radUserGroup->save();

		$userUsualIP = UserUsualIP::where(['status' => '0'])->first();
		$local_ip = $userUsualIP->ip;
		$userUsualIP->status = 1;
		$userUsualIP->save();
		$radreply = Radreply::where(['username' => $username])->first();
		$userIPStatus = UserIPStatus::where(['username' => $username])->first();

		$radreply->value = $local_ip;
		$radreply->save();

		$userIPStatus->ip = $local_ip;
		$userIPStatus->type = "usual_ip";
		$userIPStatus->save();


		return ['status' => 'success', 'msg' => 'success fully changed'];
	}elseif($isChecked == 'true'){
		$radreply = Radreply::where(['username' => $username])->first();
		$ip = $radreply->value;
		$userUsualIP = UserUsualIP::where(['ip' => $ip])->first();
		$userIPStatus = UserIPStatus::where(['username' => $username])->first();

		$userUsualIP->status = 0;
		$userUsualIP->save();

		$radreply->value = $static_ip;
		$radreply->save();

		$userIPStatus->ip = $static_ip;
		$userIPStatus->type = "static_ip";
		$userIPStatus->save();

		

		
		
		return ['status' => 'success', 'msg' => 'success fully changed'];
	}

	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];

}

////////////alow vefications

public function approveVerification(Request $request){
	$isChecked = $request->get('isChecked');
	$username = $request->get('sub_dealer_id');
	$subdealerProfileRate = SubdealerProfileRate::where(['sub_dealer_id' => $username])->get();
	if($isChecked == 'false'){
		foreach ($subdealerProfileRate as $value) {
			$value->verify = 'no';
		    $value->save();
		}
		
		return ['status' => 'success', 'msg' => 'success fully changed'];
	}elseif($isChecked == 'true'){
		foreach ($subdealerProfileRate as $value) {
			$value->verify = 'yes';
		    $value->save();
		}
		return ['status' => 'success', 'msg' => 'success fully changed'];
	}

	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}

}