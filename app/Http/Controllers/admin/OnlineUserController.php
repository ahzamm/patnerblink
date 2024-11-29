<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\RadAcct;
use App\model\Users\Profile;
use App\model\Users\ScratchCards;
use DataTables;
use DateTime;
class OnlineUserController extends Controller
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
    case "online" : {
        $online = RadAcct::where(['acctstoptime' => NULL])->paginate(100);
        

        return view('admin.users.online_user'
           ,['online' => $online]);
    }break;
    case "view" : {

      $all=UserInfo::where(['status'=>'user'])->paginate(15);


      
      
      return view('admin.users.view_user',['all'=>$all
  ]);
  }break;
  default :{
    return redirect()->route('admin.dashboard');
}
}

}
public function onlineUsers($status){
	return view('admin.online_user');
}
public function onlinePost()
{
	$arr = array();
		$dealerid = Auth::user()->dealerid;
		$currentStatus = Auth::user()->status;
		$sub_dealer_id = Auth::user()->sub_dealer_id;
		$trader_id = Auth::user()->trader_id;
		if($currentStatus == "support"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radacct','radacct.username','radcheck.username')->where(['radcheck.dealerid' => 'logonhome' ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->where(['radacct.acctstoptime' => NULL])->get(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid']);
	}else if($currentStatus == "subdealer"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radacct','radacct.username','radcheck.username')->where(['radcheck.sub_dealer_id' => $sub_dealer_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->where(['radacct.acctstoptime' => NULL])->get(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid']);
			// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	}else if($currentStatus == "trader"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radacct','radacct.username','radcheck.username')->where(['radcheck.trader_id' => $trader_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->where(['radacct.acctstoptime' => NULL])->get(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid']);
			// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	}elseif($currentStatus =="inhouse" && Auth::user()->sub_dealer_id == ''){
		$userDealer = DB::connection('mysql1')->table('radcheck')->join('radacct','radacct.username','radcheck.username')->where(['radcheck.dealerid' => $dealerid ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->where(['radacct.acctstoptime' => NULL])->get(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid']);
		// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	}elseif($currentStatus =="inhouse" && Auth::user()->sub_dealer_id != ''){
		$userDealer = DB::connection('mysql1')->table('radcheck')->join('radacct','radacct.username','radcheck.username')->where(['radcheck.sub_dealer_id' => $sub_dealer_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->where(['radacct.acctstoptime' => NULL])->get(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid']);
		// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	}

	return Datatables::of($userDealer)->addColumn('sessionTime', function($row){
		$seconds = $row->acctsessiontime;
		$dtF = new DateTime('@0');
		$dtT = new DateTime("@$seconds");
		$onlineTime =  $dtF->diff($dtT)->format('%aDays : %hHrs : %i Mins %s Secs');
		$datetime1=new DateTime($row->acctstarttime);
		$datetime2=new DateTime("now");
		$interval=$datetime1->diff($datetime2);
		$Day=$interval->format('%dD' );
		if($Day>0)
		{
			$html = $interval->format('%dDays : %hHrs : %iMins');
		}
		else
		{
			$html = $interval->format('%hHrs : %iMins : %sSecs');
		}
		return $html;
	})->addColumn('dwUP', function($row){
		$size = $row->acctoutputoctets / 1024;
				if ($size < 1024) {
				$size = number_format($size, 2);
				$size .= ' KB';
				} else {
				if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
				} else if ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
				} else if ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= ' TB';
				} else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
				$size .= ' PB';
				}
				}
				$upload = preg_replace('/.00/', '', $size);

				$size = $row->acctinputoctets / 1024;
				if ($size < 1024) {
				$size = number_format($size, 2);
				$size .= ' KB';
				} else {
				if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
				} else if ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
				} else if ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= ' TB';
				} else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
				$size .= ' PB';
				}
				}
				$down = preg_replace('/.00/', '', $size);

				$html = $upload.'/'.$down;
		return $html;
	})->addColumn('action', function ($row) {
		
		$html = '<button onclick="onlineUserDetail('."'$row->callingstationid'".','."'$row->username'".')" data-toggle="modal" class="btn btn-info btn-xs" style="border-radius:7px;"><i class="fa fa-user"></i> View Details </button> ';
		return $html;
	})->addIndexColumn()->make(true);
}
public function onlineUserDetails(Request $request)
{
	$mac = $request->mac;
	$username = $request->username;
	$dealerid = Auth::user()->dealerid;
	$details = UserInfo::where('username',$username)->select('username','firstname','lastname','dealerid','address','sub_dealer_id')->first();

    $mac = $request->mac;
    // $url='http://cron.lbi.net.pk/mikrotik_api/api.php?mac='.$mac.'&dealerid='.$dealerid;
    $url = 'http://cron.lbi.net.pk/logoncp_cron/dhcp_api/api.php?mac='.$mac.'&dealerid='.$dealerid;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url"); 
//
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);


    // return $result; 
	
	return response()->json(array(
		'result' => $result,
		'details' =>$details
	));
}

public function offlineUserView(){
	return view('admin.users.offline_user');
}
public function offlinePost()
{
	$arr = array();
		$dealerid = Auth::user()->dealerid;
		$currentStatus = Auth::user()->status;
		$sub_dealer_id = Auth::user()->sub_dealer_id;
		$trader_id = Auth::user()->trader_id;
		$data = [];
		if($currentStatus == "support"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['EXPIRED','TERMINATE','NEW','DISABLED'])->where(['radcheck.dealerid' => 'logonhome' ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->get(['radcheck.username']);

			foreach ($userDealer as $key => $value) {
				$query = DB::connection('mysql1')->table('radacct')->where('username',$value->username)->orderby('radacct.radacctid','DESC')->first(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid','radacct.acctstoptime']);
				if(@$query->acctstoptime == NULL){
				}else{
					array_push($data,$query);
				}
			}
			// dd($data);


	}else if($currentStatus == "subdealer"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['EXPIRED','TERMINATE','NEW','DISABLED'])->where(['radcheck.sub_dealer_id' => $sub_dealer_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->get(['radcheck.username']);

			foreach ($userDealer as $key => $value) {
				$query = DB::connection('mysql1')->table('radacct')->where('username',$value->username)->orderby('radacct.radacctid','DESC')->first(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid','radacct.acctstoptime']);
				if(@$query->acctstoptime == NULL){
				}else{
					array_push($data,$query);
				}
			}
	}else if($currentStatus == "trader"){
			$userDealer = DB::connection('mysql1')->table('radcheck')->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['EXPIRED','TERMINATE','NEW','DISABLED'])->where(['radcheck.trader_id' => $trader_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->get(['radcheck.username']);

			foreach ($userDealer as $key => $value) {
				$query = DB::connection('mysql1')->table('radacct')->where('username',$value->username)->orderby('radacct.radacctid','DESC')->first(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid','radacct.acctstoptime']);
				if(@$query->acctstoptime == NULL){
				}else{
					array_push($data,$query);
				}
			}
	}elseif($currentStatus =="inhouse" && Auth::user()->sub_dealer_id == NULL){
		$userDealer = DB::connection('mysql1')->table('radcheck')->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['EXPIRED','TERMINATE','NEW','DISABLED'])->where(['radcheck.dealerid' => $dealerid ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->get(['radcheck.username']);

			foreach ($userDealer as $key => $value) {
				$query = DB::connection('mysql1')->table('radacct')->where('username',$value->username)->orderby('radacct.radacctid','DESC')->first(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid','radacct.acctstoptime']);
				if(@$query->acctstoptime == NULL){
				}else{
					array_push($data,$query);
				}
			}

	}elseif($currentStatus =="inhouse" && Auth::user()->sub_dealer_id != ''){
		$userDealer = DB::connection('mysql1')->table('radcheck')->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['EXPIRED','TERMINATE','NEW','DISABLED'])->where(['radcheck.sub_dealer_id' => $sub_dealer_id ,'radcheck.status' => 'user','radcheck.attribute' => 'Cleartext-Password'])->get(['radcheck.username']);

			foreach ($userDealer as $key => $value) {
				$query = DB::connection('mysql1')->table('radacct')->where('username',$value->username)->orderby('radacct.radacctid','DESC')->first(['radacct.acctsessiontime','radacct.acctstarttime','radacct.framedipaddress','radacct.acctoutputoctets','radacct.acctinputoctets','radacct.username','radacct.callingstationid','radacct.acctstoptime']);
				if(@$query->acctstoptime == NULL){
				}else{
					array_push($data,$query);
				}
			}
	}

	return Datatables::of($data)->addColumn('sessionTime', function($row){
		$seconds = $row->acctsessiontime;
		$dtF = new DateTime('@0');
		$dtT = new DateTime("@$seconds");
		$onlineTime =  $dtF->diff($dtT)->format('%aDays : %hHrs : %i Mins %s Secs');
		$datetime1=new DateTime($row->acctstarttime);
		$datetime2=new DateTime("now");
		$interval=$datetime1->diff($datetime2);
		$Day=$interval->format('%dD' );
		if($Day>0)
		{
			$html = $interval->format('%dDays : %hHrs : %iMins');
		}
		else
		{
			$html = $interval->format('%hHrs : %iMins : %sSecs');
		}
		return $html;
	})->addColumn('dwUP', function($row){
		$size = $row->acctoutputoctets / 1024;
				if ($size < 1024) {
				$size = number_format($size, 2);
				$size .= ' KB';
				} else {
				if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
				} else if ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
				} else if ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= ' TB';
				} else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
				$size .= ' PB';
				}
				}
				$upload = preg_replace('/.00/', '', $size);

				$size = $row->acctinputoctets / 1024;
				if ($size < 1024) {
				$size = number_format($size, 2);
				$size .= ' KB';
				} else {
				if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= ' MB';
				} else if ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= ' GB';
				} else if ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= ' TB';
				} else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
				$size .= ' PB';
				}
				}
				$down = preg_replace('/.00/', '', $size);

				$html = $upload.'/'.$down;
		return $html;
	})->addColumn('action', function ($row) {
		
		$html = '<button onclick="onlineUserDetail('."'$row->callingstationid'".','."'$row->username'".')" data-toggle="modal" class="btn btn-info btn-xs" style="border-radius:7px;"><i class="fa fa-user"></i> View Details </button> ';
		return $html;
	})->addIndexColumn()->make(true);
}
public function offlineUserDetails(Request $request)
{
	$mac = $request->mac;
	$username = $request->username;
	$dealerid = Auth::user()->dealerid;
	$details = UserInfo::where('username',$username)->select('username','firstname','lastname','dealerid','address','sub_dealer_id')->first();

    $mac = $request->mac;
    // $url='http://cron.lbi.net.pk/mikrotik_api/api.php?mac='.$mac.'&dealerid='.$dealerid;
    $url = 'http://cron.lbi.net.pk/logoncp_cron/dhcp_api/api.php?mac='.$mac.'&dealerid='.$dealerid;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url"); 
//
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);


    // return $result; 
	
	return response()->json(array(
		'result' => $result,
		'details' =>$details
	));
}



}
