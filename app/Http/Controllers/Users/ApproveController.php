<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\RaduserGroup;
use App\model\Users\AssignNasType;
use App\model\Users\ScratchCards;
use App\model\Users\StaticIPServer;
use App\model\Users\Radreply;
use App\model\Users\AssignedNas;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\FreezeAccount;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\Domain;
use App\model\Users\ContractorTraderProfile;
use App\model\Users\RadCheck;


class ApproveController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
public function index($status){
	$check = '';
	switch($status){
		case "freeze" : {
			//
			if(Auth::user()->status == 'manager'){
				$dealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid')->where(['status' => 'dealer','manager_id'=> Auth::user()->manager_id])->get();
			}elseif(Auth::user()->status == 'reseller'){
				$dealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid')->where(['status' => 'dealer','resellerid'=> Auth::user()->resellerid])->get();
			}
			//
			return view('users.reseller.freeze_account',[
				'dealerCollection' => $dealerCollection
			]);
		}break;

		////////////////////////
		case "contractor" : {
			$dealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid')->where(['status' => 'dealer'])->get();
			return view('users.approve.dealer_approve',[
				'dealerCollection' => $dealerCollection
			]);
		}break;
		case "trader" : {
			$subdealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid','sub_dealer_id')->where(['status' => 'subdealer'])->get();
			return view('users.approve.subdealer_approve',
				['subdealerCollection' => $subdealerCollection
			]);
		}break;
		case "verification" : {
			$subdealerCollection = UserInfo::select('username','manager_id','resellerid','dealerid','sub_dealer_id')->where(['status' => 'subdealer'])->get();
			return view('users.approve.subdealer_verify',
				['subdealerCollection' => $subdealerCollection
			]);
		}break;
		case "staticip" : {
			$userCollection = StaticIPServer::where(['status' => 'used','resellerid' => Auth::user()->resellerid ])->get();
			return view('users.approve.approve_static_ip',[
				'userCollection' => $userCollection
			]);
		}break;
		////////////////////////
		default :{
			return redirect()->route('Users.dashboard');
		}
	}
}
public function freezeDealers(Request $request){
	$isChecked = $request->get('isChecked');
	$username = $request->get('username');
	$updateDate = date('Y-m-d');
	$userInfo = UserInfo::where(['username' => $username])->first();
	
	if($isChecked == 'false'){
		$radreply = FreezeAccount::where(['username' => $userInfo->username])->first();
		if(empty($radreply)){
			$test = new FreezeAccount();
			$test->dealerid = $userInfo['dealerid'];
			$test->username = $username;
			$test->status = $userInfo['status'];
			$test->freeze = 'no';//no
			$test->freezeDate = $updateDate;
			$test->save();
		}else{
			$freeze = FreezeAccount::where('dealerid',$radreply->dealerid);
			$freeze->update([
				'freeze' => 'no',
				'freezeDate' => $updateDate,
			]);
			$oldfreeze = FreezeAccount::where('old_freeze','!=','')->where('dealerid',$radreply->dealerid)
			->select('username','old_freeze')->get();
			foreach ($oldfreeze as  $value) {
				$freeze = FreezeAccount::where('dealerid',$radreply->dealerid)->where('username',$value->username)->where('old_freeze','no');
				$freeze->update([
					'freeze' => $value->old_freeze,
				]);
			}
		}
		return ['status' => 'success', 'msg' => 'Successfully changed'];
	}elseif($isChecked == 'true'){
		$radreply = FreezeAccount::where(['username' => $userInfo->username])->first();
		if(empty($radreply)){
			$test = new FreezeAccount();
			$test->dealerid = $userInfo['dealerid'];
			$test->username = $username;
			$test->status = $userInfo['status'];
			$test->freeze = 'yes';
			$test->freezeDate = $updateDate;
			$test->save();
		}else{
			$freeze = FreezeAccount::where('dealerid',$radreply->dealerid);
			$freeze->update([
				'freeze' => 'yes',
				'freezeDate' => $updateDate,
			]);
		}
		return ['status' => 'success', 'msg' => 'success fully changed'];
	}
	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}
public function showDealer(Request $request){
	$parentFreez = FreezeAccount::where('resellerid','!=','')->where('dealerid','')->where('status','reseller')
	->where('resellerid',Auth::user()->resellerid)->first();
	$parentFreez=null;

	if(!empty($parentFreez))
	{
		if($parentFreez['freeze'] == 'yes'){
			return 'parentFreezed';
		}
	}
	$id = $request->id;
	$subdealerid = '';
	$subdealerName = UserInfo::where('id',$id)->first();
	$username = $subdealerName['username'];
	$checkFreeze="";
	$checkFreeze = FreezeAccount::where('username',$username)->first();
	if(!empty($checkFreeze))
	{
		$check =  $checkFreeze['freeze'];
		if($check == 'yes'){
			return $checkFreeze;
		}elseif($check == 'no'){
			return $checkFreeze;
		}
	}
	return $subdealerName;
}

public function showTrader(Request $request){

	$parentFreez = FreezeAccount::where('dealerid','!=','')->where('subdealerid','')->where('status','reseller')
	->where('dealerid',Auth::user()->dealerid)->first();
	$parentFreez=null;

	if(!empty($parentFreez))
	{
		if($parentFreez['freeze'] == 'yes'){
			return 'parentFreezed';
		}
	}
	$id = $request->id;
	$subdealerid = '';
	$subdealerName = UserInfo::where('id',$id)->first();
	$username = $subdealerName['username'];
	$checkFreeze="";
	$checkFreeze = FreezeAccount::where('username',$username)->first();
	if(!empty($checkFreeze))
	{
		$check =  $checkFreeze['freeze'];
		if($check == 'yes'){
			return $checkFreeze;
		}elseif($check == 'no'){
			return $checkFreeze;
		}
	}
	return $subdealerName;
}


public function freezeTrader(Request $request){

	$username = $request->get('username');
	$check_status = $request->get('check');
	$manager_id ='';
	$dealerid= '';
	$subdealerid = '';
	$resellerid = '';
	$freeze_by='';
	$freezed='';
	$status= '';$status_old_freeze ='';
	$updateDate = date('Y-m-d-H-i-s');
	$user_status = Auth::user()->status;
	if($user_status == 'reseller')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('freeze','yes')->wherein('freezed_by',['manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}
	}elseif($user_status =='dealer')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('freeze','yes')->wherein('freezed_by',['reseller','manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}
	}
	elseif($user_status == 'subdealer')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('freeze','yes')->wherein('freezed_by',['dealer','reseller','manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}

	}

	$userInfo_data = UserInfo::where(['sub_dealer_id' => $username])->where('status','subdealer')->where('status','!=','user')->get();
	foreach ($userInfo_data as $userInfo) {
		$username = $userInfo['username'];
		$manager_id = $userInfo['manager_id'];
		$resellerid = $userInfo['resellerid'];
		$dealerid = $userInfo['dealerid'];
		$subdealerid = $userInfo['sub_dealer_id'];
		$status = $userInfo['status'];

		$freeze_Account = FreezeAccount::where(['username' => $username])->first();
		if(!empty($freeze_Account))
		{
			if($check_status == 'true')//activate
			{
				if($freeze_Account['freezed_by'] == '')
				{
					$freezed= 'yes';
					$status_old_freeze = 'no';
					$freeze_by= Auth::user()->status;
					
				}
				elseif($freeze_Account['freezed_by'] != '' && $freeze_Account['freeze'] == 'yes'){
					$freezed= 'yes';
					$status_old_freeze = 'yes';
					$freeze_by= $freeze_Account['freezed_by'] ;
				}
				
				
				
			}
			elseif($freeze_Account['freezed_by'] != Auth::user()->status && $freeze_Account['freeze'] == 'yes'){
				$freezed= 'yes';
				$freeze_by=$freeze_Account['freezed_by'] ;
				$status_old_freeze='yes';
			}
			else{
				
				$freezed= 'no';
				$freeze_by='';
				$status_old_freeze='no';
			}
			
		}
		else{
			$freezed= 'yes';
			$status_old_freeze = 'no';
			$freeze_by= Auth::user()->status;
		}
		

		$freezeData = [
			'username' => $username,
			'manager_id' => $manager_id,
			'dealerid' => $dealerid,
			'resellerid' => $resellerid,
			'subdealerid' => $subdealerid,
			'status' => $status,
			'freeze'=>$freezed,
			'freezeDate' => $updateDate,
			'old_freeze'=>$status_old_freeze,
			'freezed_by'=>$freeze_by,
		];
		
		
		if (empty($freeze_Account)) {
			FreezeAccount::create($freezeData);
		}else {

			$freeze_Account->update($freezeData);
			
		}
		

	///// update radusergroup
		$raduserGroup = RaduserGroup::where('username' , $username)->first();
		if($raduserGroup){
			if($freezed == 'yes'){
				$raduserGroup->groupname = 'DISABLED';
				$raduserGroup->name = 'DISABLED';
			}else{
				$raduserGroup->groupname = 'TRADER';
				$raduserGroup->name = 'TRADER';	
			}
			$raduserGroup->save();
		}

	}
	//
	$status= 'subdealer';
	return redirect()->route('users.user.index1',['status' => $status]);
	

	// return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}

public function ShowResellers(Request $request){

	$parentFreez = FreezeAccount::where('resellerid','!=','')->where('dealerid','')->where('status','reseller')
	->where('resellerid',Auth::user()->resellerid)->first();
	$parentFreez=null;

	if(!empty($parentFreez))
	{
		if($parentFreez['freeze'] == 'yes'){
			return 'parentFreezed';
		}
	}
	$id = $request->id;
	$subdealerid = '';
	$subdealerName = UserInfo::where('id',$id)->first();
	$username = $subdealerName['username'];
	$checkFreeze="";
	$checkFreeze = FreezeAccount::where('username',$username)->first();
	if(!empty($checkFreeze))
	{
		$check =  $checkFreeze['freeze'];
		if($check == 'yes'){
			return $checkFreeze;
		}elseif($check == 'no'){
			return $checkFreeze;
		}
	}
	return $subdealerName;
}


public function freezeResellers(Request $request){
	$username = $request->get('username');
	$check_status = $request->get('check');
	$manager_id ='';
	$dealerid= '';
	$subdealerid = '';
	$resellerid = '';
	$freeze_by='';
	$freezed='';
	$status= '';$status_old_freeze ='';
	$updateDate = date('Y-m-d-H-i-s');

	$user_status = Auth::user()->status;
	if($user_status == 'reseller')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('old_freeze','yes')->where('freeze','yes')->where('freezed_by',['manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}
	}elseif($user_status =='dealer')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('old_freeze','no')->where('freeze','yes')->where('freezed_by',['reseller','manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}
	}
	elseif($user_status == 'subdealer')
	{
		$check_freeze_Account = FreezeAccount::where(['username' => $username])->where('old_freeze','no')->where('freeze','yes')->where('freezed_by',['dealer','reseller','manager'])->first();
		if(!empty($check_freeze_Account)){
			Session()->flash("error",'Opps! You Dont have permission');
			return back();

		}

	}

	$userInfo_data = UserInfo::where(['dealerid' => $username])->where('status','!=','reseller')->where('status','!=','user')->get();
	foreach ($userInfo_data as $userInfo) {
		$username = $userInfo['username'];
		$manager_id = $userInfo['manager_id'];
		$reseller_id = $userInfo['resellerid'];
		$dealerid = $userInfo['dealerid'];
		$subdealerid = $userInfo['sub_dealer_id'];
		$status = $userInfo['status'];


		$freeze_Account = FreezeAccount::where(['username' => $username])->first();
		if(!empty($freeze_Account))
		{
			if($check_status == 'true')
			{
				if($freeze_Account['freezed_by'] == '')
				{
					$freezed= 'yes';
					$status_old_freeze = 'no';
					$freeze_by= Auth::user()->status;
					
				}
				elseif($freeze_Account['freezed_by'] != '' && $freeze_Account['freeze'] == 'yes'){
					$freezed= 'yes';
					$status_old_freeze = 'yes';
					$freeze_by= $freeze_Account['freezed_by'] ;
				}
				
				
				
			}
			elseif($freeze_Account['freezed_by'] != Auth::user()->status && $freeze_Account['freeze'] == 'yes'){
				$freezed= 'yes';
				$freeze_by=$freeze_Account['freezed_by'] ;
				$status_old_freeze='yes';
			}
			else{
				
				$freezed= 'no';
				$freeze_by='';
				$status_old_freeze='no';
			}
			
		}
		else{
			$freezed= 'yes';
			$status_old_freeze = 'no';
			$freeze_by= Auth::user()->status;
		}
		

		$freezeData = [
			'username' => $username,
			'manager_id' => $manager_id,
			'dealerid' => $dealerid,
			'resellerid' => $resellerid,
			'subdealerid' => $subdealerid,
			'status' => $status,
			'freeze'=>$freezed,
			'freezeDate' => $updateDate,
			'old_freeze'=>$status_old_freeze,
			'freezed_by'=>$freeze_by,
		];
		
		
		if (empty($freeze_Account)) {
			FreezeAccount::create($freezeData);
		}else {
			$freeze_Account->update($freezeData);
		}


		///// update radusergroup
		$raduserGroup = RaduserGroup::where('username' , $username)->first();
		if($raduserGroup){
			if($freezed == 'yes'){
				$raduserGroup->groupname = 'DISABLED';
				$raduserGroup->name = 'DISABLED';
			}else{
				//
				$domain = Domain::where('resellerid',$reseller_id)->first();
				//
				if($status == 'dealer'){
					$raduserGroup->groupname = $domain->contractor_profile;
					$raduserGroup->name = $domain->contractor_profile;
				}else{
					$raduserGroup->groupname = $domain->trader_profile;
					$raduserGroup->name = $domain->trader_profile;	
				}
			}
			$raduserGroup->save();
		}

		
	}
	$status= 'dealer';
	return redirect()->route('users.user.index1',['status' => $status]);
	

	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}



public function showSubDealer(Request $request){

	$parentFreez = FreezeAccount::where('dealerid','!=','')->where('subdealerid','')->where('status','dealer')
	->where('dealerid',Auth::user()->dealerid)->first();
	$parentFreez=null;

	if(!empty($parentFreez))
	{
		if($parentFreez['freeze'] == 'yes'){
			return 'parentFreezed';
		}
	}
	$id = $request->id;
	$subdealerid = '';
	$subdealerName = UserInfo::where('id',$id)->first();
	$username = $subdealerName['username'];
	$checkFreeze="";
	$checkFreeze = FreezeAccount::where('username',$username)->first();
	if(!empty($checkFreeze))
	{
		$check =  $checkFreeze['freeze'];
		if($check == 'yes'){
			return $checkFreeze;
		}elseif($check == 'no'){
			return $checkFreeze;
		}
	}
	return $subdealerName;
}


public function freezeSubDealers(Request $request){

	$username = $request->get('username');
	$check_status = $request->get('check');
	$manager_id ='';
	$dealerid= '';
	$subdealerid = '';
	$resellerid = '';
	$freeze_by='';
	$freezed='';
	$status= '';$status_old_freeze ='';
	$updateDate = date('Y-m-d-H-i-s');

	$userInfo_data = UserInfo::where(['resellerid' => $username])->where('status','!=','user')->get();

	foreach ($userInfo_data as $userInfo) {
		$username = $userInfo['username'];
		$manager_id = $userInfo['manager_id'];
		$resellerid = $userInfo['resellerid'];
		$dealerid = $userInfo['dealerid'];
		$subdealerid = $userInfo['sub_dealer_id'];
		$status = $userInfo['status'];
		$freeze_Account = FreezeAccount::where(['username' => $username])->first();
		if(!empty($freeze_Account))
		{
	if($check_status == 'true')//activate
	{
		if($freeze_Account['freezed_by'] == '')
		{
			$freezed= 'yes';
			$status_old_freeze = 'no';
			$freeze_by= Auth::user()->status;

		}
		elseif($freeze_Account['freezed_by'] != '' && $freeze_Account['freeze'] == 'yes'){
			$freezed= 'yes';
			$status_old_freeze = 'yes';
			$freeze_by= $freeze_Account['freezed_by'] ;
		}
		

		
	}
	elseif($freeze_Account['freezed_by'] != Auth::user()->status && $freeze_Account['freeze'] == 'yes'){
		$freezed= 'yes';
		$freeze_by=$freeze_Account['freezed_by'] ;
		$status_old_freeze='yes';
	}
	else{
		
		$freezed= 'no';
		$freeze_by='';
		$status_old_freeze='no';
	}

}
else{
	$freezed= 'yes';
	$status_old_freeze = 'no';
	$freeze_by= Auth::user()->status;
}

$freezeData = [
	'username' => $username,
	'manager_id' => $manager_id,
	'dealerid' => $dealerid,
	'resellerid' => $resellerid,
	'subdealerid' => $subdealerid,
	'status' => $status,
	'freeze'=>$freezed,
	'freezeDate' => $updateDate,
	'old_freeze'=>$status_old_freeze,
	'freezed_by'=>$freeze_by,
];


if (empty($freeze_Account)) {
	FreezeAccount::create($freezeData);
}else {

	$freeze_Account->update($freezeData);
	
}



///// update radusergroup
$raduserGroup = RaduserGroup::where('username' , $username)->first();
if($raduserGroup){
	if($freezed == 'yes'){
		$raduserGroup->groupname = 'DISABLED';
		$raduserGroup->name = 'DISABLED';
	}else{
		if($status == 'dealer'){
			$raduserGroup->groupname = 'CONTRACTOR';
			$raduserGroup->name = 'CONTRACTOR';
		}elseif($status == 'subdealer'){
			$raduserGroup->groupname = 'TRADER';
			$raduserGroup->name = 'TRADER';	
		}
	}
	$raduserGroup->save();
}	


}
$status= 'reseller';
return redirect()->route('users.user.index1',['status' => $status]);


return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////

public function approveStaticIPUser(Request $request){

	$isChecked = $request->get('isChecked');
	$username = $request->get('userid');
	$radUserGroup = StaticIPServer::where(['userid' => $username])->first();
	$currentUser = UserInfo::where(['username' => $username])->select('dealerid')->first();

	$static_ip = $radUserGroup->ipaddress;
	if($isChecked == 'false'){
		//
		$assignedNas = AssignedNas::where(["id" => $currentUser->dealerid])->first();
		//
		$userUsualIP = UserUsualIP::where(['status' => '0','nas' => $assignedNas->nas])->first();
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
		//
		$radUserGroup->status = 'NEW';
		$radUserGroup->userid = NULL;
		$radUserGroup->save();


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

///////////////////////////////////////////////////////////////////////////////////////////////////////

public function approveRadUserGroup(Request $request){
	$isChecked = $request->get('isChecked');
	$username = $request->get('username');
	//
	$info = UserInfo::where(['username' => $username])->select('status','resellerid','dealerid')->first();
	$domain = Domain::where('resellerid',$info->resellerid )->first();
	//
	$contractor_profile = $trader_profile = 'DISABLED';
	$assignedNas = AssignedNas::where("id", $info->dealerid)->first();
	if($assignedNas){
		$CTprof = ContractorTraderProfile::where('nas_shortname',$assignedNas->nas)->first(); 
		$contractor_profile =  $CTprof->contractor_profile;
		$trader_profile =  $CTprof->trader_profile;
	}
	//
	$radUserGroup = RaduserGroup::where(['username' => $username])->first();
	// dd($radUserGroup);
	if($isChecked == 'false'){
		//
		$radUserGroup->groupname = 'DISABLED';
		$radUserGroup->name = 'DISABLED';
		$radUserGroup->save();
		return ['status' => 'success', 'msg' => 'success fully changed'];
		//
	}elseif($isChecked == 'true'){
		// 
		if($info->status == 'dealer'){
			//
			$radUserGroup->groupname = $contractor_profile;
			$radUserGroup->name = $contractor_profile;
			//
		}elseif($info->status == 'subdealer'){
			//
			$radUserGroup->groupname = $trader_profile;
			$radUserGroup->name = $trader_profile;
			//
		}
		$radUserGroup->update();
		return ['status' => 'success', 'msg' => 'success fully changed'];
		// 
	}

	return ['status' => 'erorr', 'msg' => 'Opps! Something went wrong.'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////


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


/////////////////////////////////////////////////////////

public function bind_unbind_mac(Request $request){
	$username = $request->get('username');

	$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
	$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
	$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
	$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
	$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
   //
	$whereArray = array();
	//
	if(!empty($manager_id)){
		array_push($whereArray,array('user_info.manager_id' , $manager_id));
	}if(!empty($resellerid)){
		array_push($whereArray,array('user_info.resellerid' , $resellerid));
	}if(!empty($dealerid)){
		array_push($whereArray,array('user_info.dealerid' , $dealerid));
	}if(!empty($sub_dealer_id)){
		array_push($whereArray,array('user_info.sub_dealer_id' , $sub_dealer_id));   
	}
	//
	$userInfo = UserInfo::where('username',$username)->where($whereArray)->first();
	$dealerInfo = UserInfo::where('dealerid',$userInfo->dealerid)->where('status','dealer')->where('bind_mac',1)->select('bind_mac')->first();
	//
	if($userInfo && $dealerInfo){
		
		if($userInfo->bind_mac == '1'){

			$userInfo->bind_mac = '0';
			$userInfo->save();
			//
			$userRadCheck = RadCheck::where([
				"username" => $username,
				"attribute" => "Calling-Station-Id",
			])->first();
			//
			$userRadCheck->value = 'NEW';
			$userRadCheck->save();
			//
			return 'MAC Add (<span style="color:darkgreen">Bind</span>)';
			
		}else{
			$userInfo->bind_mac = '1';
			$userInfo->save();
			return 'MAC Add (<span style="color:red">Unbind</span>)';
		}
		
	}	
}




}