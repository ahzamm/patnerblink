<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;

use App\model\Users\RadAcct;
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

$export_data  = DB::table('static_ips_server')
->orderBy('ipaddress','ASC')
->get();


	return view('admin.users.view_static_ip',[
		'export_data'=>$export_data
	]);
}
}