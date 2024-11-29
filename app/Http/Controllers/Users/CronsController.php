<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\UserStatusInfo;
use App\model\Users\RaduserGroup;

class CronsController extends Controller
{
	public function index(){
		$is_profile = array();
		$data = UserStatusInfo::where('expire_datetime','<=',date('Y-m-d 12:00:00'))->where('expire_datetime','>=',date('Y-m-01 12:00:00'))->cursor('username');
		foreach ($data as $key => $value) {
			$groupData = RaduserGroup::where('username',$value->username)->first();
			if(!empty($groupData) && $groupData->groupname != 'EXPIRED' && $groupData->groupname != 'DISABLED' && $groupData->groupname != 'TERMINATE'){
				array_push($is_profile,['groupname' => $groupData->groupname,'username' => $value->username]);
			}
		}
		return view('users.crons.index',compact('is_profile'));
	}
}