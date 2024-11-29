<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\RadAcct;
use App\model\Users\UserInfo;
use App\model\Users\RaduserGroup;
use App\model\Users\RadCheck;
use App\model\Users\AmountBillingInvoice;
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
		->join('amount_billing_invoice',function($join){
			$join->on("user_info.username","=","amount_billing_invoice.username")
                ->on("user_status_info.card_charge_on","=","amount_billing_invoice.date"); 
            })
		->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
		// ->where('user_status_info.card_charge_on', '>=', DATE('2019-09-25'))
		// ->where('amount_billing_invoice.charge_on', '>=', DATE('2019-09-25 11:59:59'))
		->where('user_info.manager_id','=', $managerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.card_expire_on','ASC')
		->get();
    //
		$filename = $managerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	}else{
 	// 
		$export_data  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		->join('amount_billing_invoice',function($join){
			$join->on("user_info.username","=","amount_billing_invoice.username")
                ->on("user_status_info.card_charge_on","=","amount_billing_invoice.date"); 
            })
		->where('user_status_info.expire_datetime', '>', DATE('Y-m-d 11:59:59'))
		// ->where('user_status_info.card_charge_on', '>=', DATE('2019-09-25'))
		// ->where('amount_billing_invoice.charge_on', '>=', DATE('2019-09-25 11:59:59'))
		->where('user_info.manager_id','=', $managerid)
		->where('user_info.resellerid','=', $resellerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.card_expire_on','ASC')
		->get();
    //
		$filename = $resellerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	}



	$delimiter = ",";
	// $filename = "Export.csv";
    //create a file pointer
	$f = fopen('php://memory', 'w');
    //set column headers
	$fields = array('Serial#','PPPoE (ID)','Consumer Name','Address','CNIC Number','Mobile Number','Internet Profile','Internet Profile Rates','Sindh Sales Tax','Advance Income Tax','Final Rate','Receipt','Internet Profile Name','Reseller (ID)','Contractor (ID)','Trader (ID)','Consumer Registration Date','Consumer Activation Date','Current Expire Date','Last Expire Date','Upload Data Usage','  Download Data Usage','Total Data Usage');
	fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
	$num=1;
	$from=date('Y-m-1 00:00:00');
	$to=date('Y-m-t 23:59:59');

	foreach ($export_data as $value) {
        // 
		$expire_log = UserExpireLog::where(['username' => $value->username])->first();
        // 
		$totalDownload=RadAcct::where(['username' => $value->username])->where('acctstarttime','>=',DATE($from))->where('acctstarttime','<=',DATE($to))->sum('acctoutputoctets') ;
		$totalDownload=$this->ByteSize($totalDownload);
		//
		$totalupload=RadAcct::where(['username' => $value->username])->where('acctstarttime','>=',DATE($from))->where('acctstarttime','<=',DATE($to))->sum('acctinputoctets');
		$totalupload=$this->ByteSize($totalupload);
        //
		$profile = RaduserGroup::select('groupname')->where(['username' => $value->username])->first(); 
		//
		$chargeTime=AmountBillingInvoice::select('charge_on','taxname','m_acc_rate','receipt','c_charges','c_sst','c_adv')->where(['username' => $value->username])->orderBy('id','DESC')->first(); 
        // 
		$data1=$num;
		$date2=$value->username;
		$data3=$value->firstname." ".$value->lastname;
		$data4=$value->address;
		// $data5=$value->email;
		$data6=$value->nic;
		// $data7=$value->homephone;
		$data8=$value->mobilephone;

		$data9=$profile->groupname;
		if($data9 == 'DISABLED'){
			$data9=$value->profile;
		}
		$data9=str_replace('BE-', '', $data9);
		$data9=str_replace('k', '', $data9);
		if(is_numeric($data9)){
			$data9=($data9/1024).'Mb';
		}
		

		$data10=$value->resellerid;
		$data11=$value->dealerid;
		$data12=$value->sub_dealer_id;
		$data13=$value->creationdate;
		// $data14=$value->card_charge_on;
		$data14=$chargeTime['charge_on'];
		$data15=$value->card_expire_on;
		// 
		if($expire_log){ 
			$data16=$expire_log->expired_on;
		}else{
			$data16='';
		}
		$data17=$chargeTime['taxname'];

		$receipt=$chargeTime['receipt'];
		if($receipt == 'cyber'){
			$data18=$chargeTime['c_charges'];
			$final_sst = $chargeTime['c_sst'];
			$adv = $chargeTime['c_adv'];
			$totalRate = $data18+$final_sst+$adv;
			
		}else{
			
			//
			$data18=$chargeTime['m_acc_rate'];
		$sst =0.195;
		$final = $data18 * $sst;
		$final_sst = round($final);
		$totalRate = $data18+$final_sst;
		$adv =0;

		$receipt = 'logon';
		}
		$data19 = (int)$value->qt_used;
		$data19 = round((($data19)/1073741824)) .' GB';
        // 
		$lineData = array($data1,$date2,$data3,$data4,$data6,$data8,$data9,$data18,$final_sst,$adv,$totalRate,$receipt,$data17,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$totalupload,$totalDownload,$data19);
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
public function index_OLD($managerid,$resellerid){

	if($resellerid=='noreseller'){
 	// 
		$export_data  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		// ->where('user_status_info.expire_datetime', '>=', DATE('2019-01-01 11:59:59'))
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
		// ->where('user_status_info.expire_datetime', '>=', DATE('2018-01-01 11:59:59'))
		->where('user_info.manager_id','=', $managerid)
		->where('user_info.resellerid','=', $resellerid)
		->where('user_info.status','=','user')
		->orderBy('user_status_info.expire_datetime','ASC')
		->get();
    //
		$filename = $resellerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";
	}



	$delimiter = ",";
	// $filename = "Export.csv";
    //create a file pointer
	$f = fopen('php://memory', 'w');
    //set column headers
	$fields = array('Serial#','Consumer Name','PPPoE (ID)','Package','Internet Profile Name','Address','Consumer status','Mobile Number','MAC Address','CNIC Number','Registration Date','Last Expire Date','Activation Date','Reseller (ID)','Contractor (ID)','Trader (ID)','Receipt');
	fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
	$num=1;
	$from=date('Y-m-01 00:00:00');
	$to=date('Y-m-t 23:59:59');

	foreach ($export_data as $value) {
		// dd(date('Y-m-d'));
		// dd(date($value->card_expire_on,strtotime('y-m-d')));
        // 
		$expire_log = UserExpireLog::where(['username' => $value->username])->first();
        // 
		// $totalDownload=RadAcct::where(['username' => $value->username])->where('acctstarttime','>=',DATE($from))->where('acctstarttime','<=',DATE($to))->sum('acctoutputoctets') ;
		// $totalDownload=$this->ByteSize($totalDownload);
		//
		// $totalupload=RadAcct::where(['username' => $value->username])->where('acctstarttime','>=',DATE($from))->where('acctstarttime','<=',DATE($to))->sum('acctinputoctets');
		// $totalupload=$this->ByteSize($totalupload);
        //
		// $profile = RaduserGroup::select('groupname')->where(['username' => $value->username])->first(); 
		$mac = RadCheck::where('username',$value->username)->where('attribute','Calling-Station-Id')->first();
		// 
		$data1=$num;
		$data2=$value->firstname." ".$value->lastname;
		$data3=$value->username;
		$data4=$value->profile;
		$data5=$value->address;
		if($value->card_expire_on >= date('Y-m-d'))
		{
			$status = 'active';
		}else{
			$status = 'inactive';
		}
		$data6=$status;
		// $data7=$value->homephone;
		$data7=$value->mobilephone;
		if(!empty($mac->value))
		$data8=$mac->value;
		else
		$data8='NEW';
		$data9=$value->nic;
		$data10=$value->creationdate;
		$data11=''; 
		if($expire_log){ 
			$data11=$expire_log->expired_on;
		}
		$data12=$value->card_charge_on;
		
		$data13=$value->resellerid;
		$data14=$value->dealerid;
		$data15=$value->sub_dealer_id;
		$data16=$value->receipt;
		$data17=$value->name;
		//
		

        // 
		$lineData = array($data1,$data2,$data3,$data4,$data17,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16);
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


public function downloadDealer($managerid,$dealerid){

 	// 
	$export_data  = DB::table('user_info')
	->where('manager_id','=', $managerid)
	->where('dealerid','=', $dealerid)
	->where('status','=','user')
	->get();
    //
	$filename = $dealerid."-Consumer-Information-".date('Y-m-d H:i:s').".csv";

	$delimiter = ",";
	// $filename = "Export.csv";
    //create a file pointer
	$f = fopen('php://memory', 'w');
    //set column headers
	$fields = array('Serial#','PPPoE (ID)','Consumer Name','Address','Email Address','CNIC Number','Phone Number','Mobile Number','Internet Profile','Reseller (ID)','Contractor (ID)','Trader (ID)','Registration Date');
	fputcsv($f, $fields, $delimiter);
    //output each row of the data, format line as csv and write to file pointe
	$num=1;
	foreach ($export_data as $value) {
        // 
		$expire_log = UserExpireLog::where(['username' => $value->username])->first();
        // 
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
		// 
        // 
		$lineData = array($data1,$date2,$data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13);
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

public function ByteSize($bytes)
{
	$size = $bytes / 1024;
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
	$size = preg_replace('/.00/', '', $size);
	return $size;
}

}