<?php
namespace App\Http\Controllers\admin;
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
public function index($status){
	switch ($status) {
		case "support":{
			$support= Admin::where(['status'=>'support'])->get();

			
			return view('admin.users.view_support',[
				'support'=>$support
			]);
		}
		break;
		
		default:{
			return redirect()->route('admin.dashboard');
		}
		break;
	}
	
}
public function store(Request $request, $status){
	$user= new Admin();
	switch($status) {
		case "support":{
			$this->validate($request,[
				'username'=>'required|unique:admins',
				'password'=>'required|confirmed'
			]);
			$user->username=$request->get('username');
			$user->firstname=$request->get('fname');
			$user->lastname=$request->get('lname');
			$user->email=$request->get('mail');
			$user->status='support';
			$user->mobilephone=$request->get('mobile_number');
			$user->homephone=$request->get('land_number');
			$user->nic=$request->get('nic');
			$user->address=$request->get('address');
			$user->password=Hash::make($request->get('password'));

		}break;
	}
	$user->save();
	session()->flash('success',' created success fully.');
	return redirect()->route('admin.management.support.index',['status' => $status]);
}

public function DBOUser(){

dd('hello');
	$allusers=RadAcct::leftJoin('radusergroup', function($join) {
		$join->on('radacct.username', '=', 'radusergroup.username');
	})->where([['radacct.acctstoptime','=',Null],['radusergroup.groupname','=','EXPIRED']])->get(['radacct.username','radacct.acctstarttime']);


	return view('admin.users.DBO_user',[
		'allusers'=>$allusers
	]);
}


public function expiry()
{
	$date=date('Y-m-d');
	$expry= UserStatusInfo::where('card_expire_on','<=',date('Y-m-d',strtotime($date.' -1 months')))->where('card_expire_on', '!=' ,'0000-00-00')->paginate(20);

	return view('admin.users.expired_user',['expry'=>$expry]);
}

}
