<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\UserInfo;
use App\model\Users\RadAcct;
class DownloadSheetController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}


public function index($managerid,$resellerid){

	if($resellerid=='noreseller'){
 	// 
		$export_data  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
		->where('user_info.manager_id','=', $managerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.expire_datetime','ASC')
		->get();
    //
		$filename = $managerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	}else{
 	// 
		$export_data  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
		->where('user_info.manager_id','=', $managerid)
		->where('user_info.resellerid','=', $resellerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.expire_datetime','ASC')
		->get();

    //
		$filename = $resellerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	}
	//
	$delimiter = ",";
	// $filename = "Export.csv";
    //create a file pointer
	$f = fopen('php://memory', 'w');
    //set column headers
	$fields = array('Serial#','PPPoE (ID)','Consumer Name','Address','Email Address','CNIC Number','Phone Number','Mobile Number','Internet Profile','Reseller (ID)','Contractor (ID)','Trader (ID)','Consumer Registration Date','Consumer Activation Date','Consumer Current Expire Date','Consumer Last Expire Date','Total Data Upload','Total Data Download');
	fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
	$num=1;
	foreach ($export_data as $value) {
        // 
		$expire_log = UserExpireLog::where(['username' => $value->username])->first();
        // 
		$totalDownload = RadAcct::where(['username' => $value->username])->sum('acctoutputoctets');
		$totalupload = RadAcct::where(['username' => $value->username])->sum('acctinputoctets');
        // 
		$data1=$num;
		$date2=$value->username;
		$data3=$value->firstname." ".$value->lastname;
		$data4=$value->address;
		$data5=$value->email;
		$data6=$value->nic;
		$data7=$value->homephone;
		$data8=$value->mobilephone;
		$data9=$value->profile;
		$data10=$value->resellerid;
		$data11=$value->dealerid;
		$data12=$value->sub_dealer_id;
		$data13=$value->creationdate;
		$data14=$value->card_charge_on;
		$data15=$value->card_expire_on;
		// 
		$data16= null;
		if($expire_log){ 
			$data16=$expire_log->expired_on;
		}

        // 
		$lineData = array($data1,$date2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$totalupload,$totalDownload);
		fputcsv($f, $lineData, $delimiter);
        // 
		$num++;
	}
    // last row or Summary
    //move back to beginning of file
	fseek($f, 0);
    //set headers to download file rather than displayed
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
	fpassthru($f);
// }
	exit;
// 
// index function end 
}




public function download_all_users($resellerid,$dealerid){

	
 	// 
		$export_data  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		// ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
		->where('user_info.dealerid','=', $dealerid)
		->where('user_info.resellerid','=', $resellerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.expire_datetime','ASC')
		->get();

    //
		$filename = $dealerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	
	//
	$delimiter = ",";
	// $filename = "Export.csv";
    //create a file pointer
	$f = fopen('php://memory', 'w');
    //set column headers
	$fields = array('Serial#','PPPoE (ID)','Consumer Name','Address','Email Address','CNIC Number','Phone Number','Mobile Number','Internet Profile','Reseller (ID)','Contractor (ID)','Trader (ID)','Consumer Registration Date','Consumer Activation Date','Consumer Current Expire Date','Consumer Last Expire Date','Total Data Upload','Total Data Download');
	fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
	$num=1;
	foreach ($export_data as $value) {
        // 
		$expire_log = UserExpireLog::where(['username' => $value->username])->first();
        // 
		$totalDownload = RadAcct::where(['username' => $value->username])->sum('acctoutputoctets');
		$totalupload = RadAcct::where(['username' => $value->username])->sum('acctinputoctets');
        // 
		$data1=$num;
		$date2=$value->username;
		$data3=$value->firstname." ".$value->lastname;
		$data4=$value->address;
		$data5=$value->email;
		$data6=$value->nic;
		$data7=$value->homephone;
		$data8=$value->mobilephone;
		$data9=$value->profile;
		$data10=$value->resellerid;
		$data11=$value->dealerid;
		$data12=$value->sub_dealer_id;
		$data13=$value->creationdate;
		$data14=$value->card_charge_on;
		$data15=$value->card_expire_on;
		// 
		$data16= null;
		if($expire_log){ 
			$data16=$expire_log->expired_on;
		}

        // 
		$lineData = array($data1,$date2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$totalupload,$totalDownload);
		fputcsv($f, $lineData, $delimiter);
        // 
		$num++;
	}
    // last row or Summary
    //move back to beginning of file
	fseek($f, 0);
    //set headers to download file rather than displayed
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
    //output all remaining data on a file pointer
	fpassthru($f);
// }
	exit;
// 
// index function end 
}




}