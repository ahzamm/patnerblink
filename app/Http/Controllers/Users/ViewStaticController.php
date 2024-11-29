<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\StaticIPServer;
use App\model\Users\RadAcct;
use App\model\Users\userAccess;
class ViewStaticController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(){
	$status = Auth::user()->status;
	if ($status=="manager") {
		$export_data  = StaticIPServer::orderBy('ipaddress','ASC')->get();
	}
	elseif($status=="reseller"){
		$export_data  = StaticIPServer::where(['resellerid' =>Auth::user()->resellerid])->orderBy('ipaddress','ASC')->get();
	}
	elseif ($status=="dealer") {
		$export_data  = StaticIPServer::where(['dealerid' =>Auth::user()->dealerid])->orderBy('ipaddress','ASC')->get();
	}elseif ($status=="inhouse") {
		if(Auth::user()->dealerid !=''){
			$export_data  = StaticIPServer::where(['dealerid' =>Auth::user()->dealerid])->orderBy('ipaddress','ASC')->get();
		}else{
			$export_data  = StaticIPServer::where(['resellerid' =>Auth::user()->resellerid])->orderBy('ipaddress','ASC')->get();
		}
		
	}
	return view('users.billing.view_static_ip',[
		'export_data'=>$export_data
	]);
}


}