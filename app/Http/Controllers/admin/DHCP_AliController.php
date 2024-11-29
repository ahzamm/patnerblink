<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\Dhcp_server;
use App\model\Users\Dhcp_dealer_server;
use App\model\Users\CactiGraph;




class DHCP_AliController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
public function index(){
	$dealer = UserInfo::where('status','dealer')->where('resellerid','logonbroadband')->get();
	$dhcp_server = Dhcp_server::all();
	$dhcp_table = Dhcp_dealer_server::where('server_id','!=','0')->get();
	$cacti = CactiGraph::where('graph_no','!=',0)->get();

		
	return view('admin.DHCPAli.view_Ali_DHCP',
	[
		'dhcp_server' => $dhcp_server,
		'dealer'=> $dealer,
		'cacti' => $cacti,
		'dhcp_table' => $dhcp_table
	]);
}
public function loadSubDealer(Request $request)
{
	// dd($request->username);
	$subdealer = UserInfo::where('status','subdealer')->where('dealerid',$request->username)->get();
	return response()->json($subdealer);
}
public function postData(Request $request)
{

	$dhcp_serverip = $request->dhcp_serverip;
	$dealers = $request->dealers;
	$graph_no = $request->graph;
	$subdealers = $request->subdealers;

	if(empty($graph_no) && empty($dhcp_serverip)){
		
		session()->flash('error','Please Select Graph No OR DHCP Server..');
		return redirect()->route('admin.dhcpAssign');
	}

	if(!empty($graph_no)){
		if(!empty($subdealers)){
			// dd('subdealer');
			CactiGraph::where(['user_id'=>$subdealers])->delete();
			$graph1 = new CactiGraph();
			$graph1->user_id =$subdealers;
			$graph1->graph_no =$graph_no;
			$graph1->save();
		}else{
			// dd('dealer');
			$username = UserInfo::where('status','dealer')->where('dealerid',$dealers)->first()->username;
			CactiGraph::where(['user_id'=>$username])->delete();
			$graph1 = new CactiGraph();
			$graph1->user_id =$username;
			$graph1->graph_no =$graph_no;
			$graph1->save();
		}
		
	}

	if(!empty($dhcp_serverip)){
		// dd('DHCP');
	$servers = explode(' ', $dhcp_serverip);
	$serverid = $servers[0];
	$servername = $servers[1];
	$dhcp_server = Dhcp_dealer_server::where('dealerid',$dealers)->first();
	
	if($dhcp_server == null && empty($dhcp_server)){
	 $newdhcpEntry = new Dhcp_dealer_server();
	 $newdhcpEntry->dealerid = $dealers;
	 $newdhcpEntry->server_id = $serverid;
  
	 $newdhcpEntry->save();
	}else{
	 $dhcp_server->server_id = $serverid;
	 $dhcp_server->dealerid = $dealers;
	 $dhcp_server->save();
	}
}
		session()->flash('success','User has been successfully Updated');
		return redirect()->route('admin.dhcpAssign');
}
}