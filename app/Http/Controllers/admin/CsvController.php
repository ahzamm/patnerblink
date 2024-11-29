<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\model\Users\City;
use App\model\Users\UserInfo;
use App\model\Users\ExpireUser;
use App\model\Users\UserStatusInfo;
use App\model\Users\RaduserGroup;
use App\model\Users\RadCheck;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\UserExpireLog;
use App\model\Users\Profile;
use App\model\Users\Domain;
use App\model\Users\CsvFileLogs;
use App\model\Users\AssignedNas;
use App\model\Users\UserVerification;
use Validator;
use DateTime;
define('DATE_FORMAT', 'Y-m-d');
use Illuminate\Http\Request;
use Ramsey\Uuid\Builder\FallbackBuilder;

class CsvController extends Controller
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
		 	$manager_data = userInfo::where('status','manager')->get(["manager_id", "id"]);
		 	$logs = CsvFileLogs::where('action','Create')->orderBy('id','DESC')->get();
		 	return view('admin.Csv.index',compact('manager_data','logs'));
		 }
		 public function get_reseller(Request $request){
		 	$data['reseller'] = userInfo::where('manager_id',$request->manager_id)
		 	->where('status','reseller')->get(["resellerid", "id"]);
		 	return response()->json($data);
		 }
		 public function get_dealer(Request $request){

		 	$data['dealer'] = userInfo::where('resellerid',$request->reseller_id)
		 	->where('status','dealer')->get(["dealerid", "id"]);
		 	return response()->json($data);
		 }
		 public function get_trader(Request $request){
		 	$data['subdealer'] = userInfo::where('dealerid',$request->dealer_id)->where('status','subdealer')->get(["sub_dealer_id", "id"]);
		 	return response()->json($data);
		 }


		 public function upload_file(Request $request)
		 {
		 	$request->validate(
		 		[
		 			'file'          => 'required|mimes:csv,txt',
		 			'dealer_data'   => 'required',
		 			'reseller_data' => 'required',
		 			'manager_data'  => 'required',
		 		],
		 		['file.required' => 'Please Upload a CSV File..', 'file.mimes' => 'Only CSV or TXT files are allowed.']
		 	);
        //
		 	$get_city            = userInfo::where('status', 'reseller')->first('city');
		 	$_SESSION['message'] = null;
        //
		 	if ($request->file('file')) {
		 		$errors    = [];
		 		$file_name = $_FILES['file']['name'];
		 		$handle    = fopen($_FILES['file']['tmp_name'], 'r');
		 		$ext       = pathinfo($file_name, PATHINFO_EXTENSION);
            //
		 		if ($ext == 'csv') {
		 			$data      = fgetcsv($handle);
		 			$index     = 0;
		 			$allData   = [];
		 			$usernames = [];
		 			$addresses = [];
                //
		 			if (count($data) !== 7) {
		 				return back()->withErrors(['Invalid columns in the file. Please ensure it contains exactly 7 columns.']);
		 			}
                //
		 			while ($fileop = fgetcsv($handle)) {
		 				$fileop = array_map('trim', $fileop);
                    //
		 				$allData[$index] = $fileop;
		 				$index++;
                    //
		 				for ($i = 0; $i < count($fileop); $i++) {
		 					if (strlen($fileop[$i]) < 1) {
		 						if (in_array($i, [0, 1, 2, 3])) {
		 							$errors[] = 'Line ' . $index+1 . ': Required field is empty.';
		 						}
		 					}
                        //
		 					if ($i == 0) {
		 						$username    = $fileop[$i];
		 						$usernames[] = $username;

		 						$existingUserCount = UserInfo::where('username', $username)->count();
		 						if ($existingUserCount > 0) {
		 							$errors[] = 'Line ' . $index+1 . ': Duplicate username found in database.';
		 						}
		 					}
                        //
		 					if ($i == 4) {
		 						$address     = preg_replace('/[^A-Za-z0-9\-]/', ' ', $fileop[$i]);
		 						$address     = str_replace(["'", '`'], '', $address);
		 						$addresses[] = $address;
		 					}
		 				}
		 			}
                //
		 			if (count(array_diff_assoc($usernames, array_unique($usernames))) > 0) {
		 				$errors[] = 'Duplicate usernames found in the uploaded file.';
		 			}
                //
		 			if (!empty($errors)) {
		 				return back()->withErrors(['errors' => $errors]);
		 			}
                //
		 			\DB::beginTransaction();
		 			try {
		 				$today = date('Y-m-d');
		 				$ip    = $this->get_client_ip();

		 				foreach ($allData as $key => $data) {
		 					$username      = str_replace(["'", '`', '@', '#', '$', '%', '^', '&', '*', '!'], '', trim($data[0]));
		 					$password      = trim($data[1]);
		 					$fname         = str_replace(["'", '`'], '', trim($data[2]));
		 					$lname         = str_replace(["'", '`'], '', trim($data[3]));
		 					$address       = stripslashes($addresses[$key]);
		 					$nic           = htmlspecialchars(str_replace(["'", '`', '@', '#', '$', '%', '^', '&', '*', '!'], '', trim($data[5])));
		 					$mobile        = htmlspecialchars(str_replace(["'", '`', '@', '#', '$', '%', '^', '&', '*', '!'], '', trim($data[6])));
		 					$sub_dealer_id = $request->input('trader_data') ?: null;
		 					$dealerid      = $request->input('dealer_data');
		 					$resellerid    = $request->input('reseller_data');
		 					$managerid     = $request->input('manager_data');
		 					$city          = $get_city->city ?? null;
		 					$receipt       = 'cyber';
		 					$hash          = Hash::make($username);
		 					$get_domain_id = Domain::where('resellerid', $resellerid)->first();
                        //
		 					$userInfo                    = new UserInfo();
		 					$userInfo->username          = $username;
		 					$userInfo->domainID          = $get_domain_id->id;
		 					$userInfo->password          = $hash;
		 					$userInfo->firstname         = $fname;
		 					$userInfo->lastname          = $lname;
		 					$userInfo->email             = 'name@email.com';
		 					$userInfo->nic               = $nic;
		 					$userInfo->mobilephone       = $mobile;
		 					$userInfo->address           = $address;
		 					$userInfo->city              = $city;
		 					$userInfo->permanent_address = $address;
		 					$userInfo->creationdate      = $today;
		 					$userInfo->creationby        = 'admin';
		 					$userInfo->creationbyip      = $ip;
		 					$userInfo->sub_dealer_id     = $sub_dealer_id;
		 					$userInfo->dealerid          = $dealerid;
		 					$userInfo->resellerid        = $resellerid;
		 					$userInfo->manager_id        = $managerid;
		 					$userInfo->status            = 'user';
		 					$userInfo->profile           = 'NEW';
		 					$userInfo->name              = 'NEW';
		 					$userInfo->area              = '';
		 					$userInfo->verified          = 0;
		 					$userInfo->receipt           = $receipt;
		 					$userInfo->save();
                        //
		 					$userStatusInfo                  = new UserStatusInfo();
		 					$userStatusInfo->username        = $username;
		 					$userStatusInfo->card_charge_on  = '1990-03-03';
		 					$userStatusInfo->card_expire_on  = '1990-03-03';
		 					$userStatusInfo->expire_datetime = '1990-03-03';
		 					$userStatusInfo->save();
                        //
		 					$attributes = [
		 						['attribute' => 'Cleartext-Password', 'value' => $password],
		 						['attribute' => 'Simultaneous-Use', 'value' => '1'],
		 						['attribute' => 'Calling-Station-Id', 'value' => 'NEW'],
		 					];
                        //
		 					foreach ($attributes as $attr) {
		 						RadCheck::create([
		 							'username'      => $username,
		 							'attribute'     => $attr['attribute'],
		 							'op'            => ':=',
		 							'value'         => $attr['value'],
		 							'dealerid'      => $dealerid,
		 							'sub_dealer_id' => $sub_dealer_id,
		 							'resellerid'    => $resellerid,
		 							'manager_id'    => $managerid,
		 							'status'        => 'user',
		 						]);
		 					}
                       //
		 					$radUserGroup            = new RaduserGroup();
		 					$radUserGroup->username  = $username;
		 					$radUserGroup->groupname = 'NEW';
		 					$radUserGroup->priority  = 0;
		 					$radUserGroup->name      = 'NEW';
		 					$radUserGroup->save();
                        // 			 
		 					$now=date('Y-m-d H:i:s');
							// 
		 					$expired_user = new ExpireUser();
		 					$expired_user->username = $username;
		 					$expired_user->status ='expire';
		 					$expired_user->last_update = $now;
		 					$expired_user->save();

		 				}
                    //
		 				$csv_logs              = new CsvFileLogs();
		 				$csv_logs->date        = date('Y-m-d');
		 				$csv_logs->time        = date('H:i:s');
		 				$csv_logs->created_by  = \Auth::user()->username;
		 				$csv_logs->manager_id  = $managerid;
		 				$csv_logs->reseller_id = $resellerid;
		 				$csv_logs->dealer_id   = $dealerid;
		 				$csv_logs->trader_id   = $sub_dealer_id;
		 				$csv_logs->action      = 'Create';
		 				$csv_logs->count       = count($allData);
		 				$csv_logs->save();
                    //
		 				\DB::commit();
		 				return back()->with('success', 'Users imported successfully.');
		 			} catch (\Exception $e) {
		 				\DB::rollback();
		 				return back()->withErrors(['errors' => 'An error occurred during import: ' . $e->getMessage()]);
		 			}
		 		} else {
		 			return back()->withErrors(['Invalid file format. Please upload only CSV file format.']);
		 		}
		 	}
		 }

		 /////////////////// REPLACE BY NEW FUNCTION ///////////
		 public function upload_file_OLD_REPALCED(Request $request){
		 	$validator = $request->validate([
		 		'file' => 'required|mimes:csv,txt',
		 		'dealer_data'=>'required','reseller_data'=>'required','manager_data'=>'required'],
		 		['file.required' => 'Please Upload a CSV File..',
		 		'file.mimes' => 'Only Required CSV File.. ']);

		   // DB::beginTransaction();
		   // try {
		 	$get_city = userInfo::where('status','reseller')->first('city');

		 	$_SESSION["message"] = NULL;
		 	$_SESSION["uploadError"] = NULL;
		 	if($request->file('file')){
		 		$errors= [];
		 		$file_name = $_FILES['file']['name'];
		 		$handle = fopen($_FILES['file']['tmp_name'],"r");
		 		$ext = pathinfo($file_name, PATHINFO_EXTENSION);

		 		if($ext == 'csv'){
		 			$data = fgetcsv($handle);
		 			$index = 0;
		 			$allData = [];
		 			$usernames = [];
		 			$addresses = [];
		 			$duplicatUser = NULL;

		 			if(count($data) == "7"){

		 				//
		 				// while ($value = $data) {
		 				// 	$info = UserInfo::where('username',$value[0])->count();
		 				// 	if($info > 0) {
		 				// 		$duplicatUser .=  $value[0].' , ';
		 				// 	}
		 				// }
		 				// //
		 				// if(!empty($duplicatUser)){
		 				// 	return back()->with('error','Duplicate Users '.$duplicatUser);
		 				// }
		 				//

		 				while ($fileop = fgetcsv($handle)) {


		 					$allData[$index] = $fileop;
		 					$index++;
		 					for ($i=0; $i < count($fileop); $i++) {

		 						if (strlen(trim($fileop[$i])) < 1) {
		 							if ($i == 0 || $i == 1 || $i == 2 || $i == 3 ) {
		 								$message = "Cell cannot be empty Kindly check line no. ".($index+1);
		 								return back()->with('error',$message);
		 							}
		 						}

		 						if ($i == 0) {
		 							$username = trim($fileop[$i]);
		 							array_push($usernames, $username);
		 							$info = UserInfo::where('username',$username)->count();

		 							if($info > 0) {

		 								$message = "Duplicate user Kindly check line no. ".($index+1);
		 								return back()->with('error',$message);
		 							}
		 						}

		 						if ($i == 4) {
		 							$address = preg_replace('/[^A-Za-z0-9\-]/', ' ', trim($fileop[$i]));
		 							$a=array("'","`");
		 							$address = trim($address);
		 							$address=str_replace($a, '', $address);
		 							array_push($addresses, $address);
		 						}


		 					}
		 				}

		 				if (count(array_diff_assoc($usernames, array_unique($usernames))) > 0) {
		 					$message = "Duplicate users in sheet";
		 					return back()->with('error',$message);
		 				}

		 				if ($_SESSION["uploadError"] == NULL) {
		 					$today = date('Y-m-d');
		 					$ip = $this->get_client_ip();
		 					foreach ($allData as $key => $data) {

		 						$a=array("'","`");
		 						$b=array("'","`","@","#","$","%","^","&","*","!");

		 						$username = trim($data[0]);
		 						$username = str_replace($b, '', $username);
		 						$password = trim($data[1]);
		 						$fname = trim(str_replace($a,'',$data[2]));
		 						$lname = trim(str_replace($a,'',$data[3]));
		 						$address = str_replace($a,'',$addresses[$key]);
		 						$address = stripslashes($address);
		 						$nic = htmlspecialchars(trim(str_replace($b,'',$data[5])));
		 						$mobile = htmlspecialchars(trim( str_replace($b,'',$data[6]) ));
		 						$sub_dealer_id = isset($request['trader_data']) ? $request['trader_data']: 'NULL' ;
		 						$dealerid = isset($request['dealer_data']) ? $request['dealer_data']: 'NULL' ;
		 						$resellerid = isset($request['reseller_data']) ? $request['reseller_data']: 'NULL' ;
		 						$managerid = isset($request['manager_data']) ? $request['manager_data']: 'NULL' ;
		 						$city = isset($get_city->city) ? $get_city->city: 'NULL' ;

		 						// $onLogon = array('advancedbroadband','corporate','decafe','himalaya','logonhome','lucky7','newera','zarb');
		 						// if (in_array($dealerid, $onLogon)){
		 						// 	$receipt = "logon";
		 						// }else{
		 						// 	$receipt = "cyber";
		 						// }
		 						$receipt = "cyber";

		 						// $hash = password_hash($username, PASSWORD_DEFAULT);
		 						$hash = Hash::make($username);
		 						$get_domain_id = Domain::where('resellerid',$resellerid)->first();

								//
		 						if($sub_dealer_id == '' || empty($sub_dealer_id) || $sub_dealer_id == " "){
		 							$userInfo = new UserInfo();

		 							$userInfo->username = $username;
		 							$userInfo->domainID = $get_domain_id->id;
		 							$userInfo->password = $hash;
		 							$userInfo->firstname = $fname;
		 							$userInfo->lastname  = $lname;
		 							$userInfo->email = 'name@email.com';
		 							$userInfo->nic = $nic;
		 							$userInfo->mobilephone = $mobile;
		 							$userInfo->address = $address;
		 							$userInfo->city = $city;
		 							$userInfo->permanent_address = $address;
		 							$userInfo->creationdate = $today;
		 							$userInfo->creationby = 'admin';
		 							$userInfo->creationbyip = $ip;
		 							$userInfo->dealerid = $dealerid;
		 							$userInfo->resellerid = $resellerid;
		 							$userInfo->manager_id = $managerid;
		 							$userInfo->status = 'user';
		 							$userInfo->profile = 'NEW';
		 							$userInfo->name = 'NEW';
		 							$userInfo->area = '';
		 							$userInfo->verified = 0;
		 							$userInfo->receipt = $receipt;
		 							$userInfo->save();
								// 
		 						}

		 						else{
		 							$userInfo = new UserInfo();
		 							$userInfo->username = $username;
		 							$userInfo->domainID=$get_domain_id->id;
		 							$userInfo->password = $hash;
		 							$userInfo->firstname = $fname;
		 							$userInfo->lastname  = $lname;
		 							$userInfo->email = 'name@email.com';
		 							$userInfo->nic = $nic;
		 							$userInfo->mobilephone = $mobile;
		 							$userInfo->address = $address;
		 							$userInfo->city = $city;
		 							$userInfo->permanent_address = $address;
		 							$userInfo->creationdate = $today;
		 							$userInfo->creationby = 'admin';
		 							$userInfo->creationbyip = $ip;
		 							$userInfo->sub_dealer_id = $sub_dealer_id;
		 							$userInfo->dealerid = $dealerid;
		 							$userInfo->resellerid = $resellerid;
		 							$userInfo->manager_id = $managerid;
		 							$userInfo->status = 'user';
		 							$userInfo->profile = 'NEW';
		 							$userInfo->name = 'NEW';
		 							$userInfo->area = '';
		 							$userInfo->verified = 0;
		 							$userInfo->receipt = $receipt;
		 							$userInfo->save();
		 							\DB::commit();
		 						}
								// 

		 						if($userInfo){
									// 
		 							$userStatusInfo = new UserStatusInfo();
		 							$userStatusInfo->username = $username;
		 							$userStatusInfo->card_charge_on ='1990-03-03';
		 							$userStatusInfo->card_expire_on ='1990-03-03';
		 							$userStatusInfo->expire_datetime = '1990-03-03';
		 							$userStatusInfo->save();
									// 					
		 							$radCheck = new RadCheck();
		 							$radCheck->username = $username;
		 							$radCheck->attribute ='Cleartext-Password';
		 							$radCheck->op =':=';
		 							$radCheck->value = $password;
		 							$radCheck->dealerid = $dealerid;
		 							$radCheck->sub_dealer_id =$sub_dealer_id;
		 							$radCheck->svlan ='';
		 							$radCheck->resellerid = $resellerid;
		 							$radCheck->manager_id =$managerid;
		 							$radCheck->status ='user';
		 							$radCheck->save();

		 							$radCheck = new RadCheck();
		 							$radCheck->username = $username;
		 							$radCheck->attribute ='Simultaneous-Use';
		 							$radCheck->op =':=';
		 							$radCheck->value = '1';
		 							$radCheck->dealerid = $dealerid;
		 							$radCheck->sub_dealer_id =$sub_dealer_id;
		 							$radCheck->svlan ='';
		 							$radCheck->resellerid = $resellerid;
		 							$radCheck->manager_id =$managerid;
		 							$radCheck->status ='user';
		 							$radCheck->save();

		 							$radCheck = new RadCheck();
		 							$radCheck->username = $username;
		 							$radCheck->attribute ='Calling-Station-Id';
		 							$radCheck->op =':=';
		 							$radCheck->value = 'NEW';
		 							$radCheck->dealerid = $dealerid;
		 							$radCheck->sub_dealer_id =$sub_dealer_id;
		 							$radCheck->svlan ='';
		 							$radCheck->resellerid = $resellerid;
		 							$radCheck->manager_id =$managerid;
		 							$radCheck->status ='user';
		 							$radCheck->save();
									// 
		 							$groupname = 'NEW';
						  			// 
		 							$radUserGroup = new RaduserGroup();
		 							$radUserGroup->username = $username;
		 							$radUserGroup->groupname =$groupname;
		 							$radUserGroup->priority = 0;
		 							$radUserGroup->name = 'NEW';
		 							$radUserGroup->save();
		 							\DB::commit();

									// 			 
		 							$now=date('Y-m-d H:i:s');
									// 
		 							$expired_user = new ExpireUser();
		 							$expired_user->username = $username;
		 							$expired_user->status ='expire';
		 							$expired_user->last_update = $now;
		 							$expired_user->save();
		 							\DB::commit();
								// 


		 						}

		 					}
						// try{
		 					$csv_logs = new CsvFileLogs();
		 					$csv_logs->date = date(DATE_FORMAT);
		 					$csv_logs->time=date('H:i:s');
		 					$csv_logs->created_by = \Auth::user()->username;
		 					$csv_logs->manager_id = $managerid;
		 					$csv_logs->reseller_id = $resellerid;
		 					$csv_logs->dealer_id = $dealerid;
		 					$csv_logs->trader_id = $sub_dealer_id;
		 					$csv_logs->action = 'Create';
		 					$csv_logs->count = count($allData);
		 					$csv_logs->save();
							// 
		 					$message =  'Users imported successfully';
		 					return back()->with('success',$message);   
		 				} 

		 			} else{
		 				$message  = 'Invalid columns';
		 				return back()->with('error',$message);
		 			}

			} // end count
			else{
				$message = 'Invalid file format.Please upload only CSV file format.';
				return back()->with('error',$message);
			}
			
		} // file request
		// }catch (\Exception $e) {
		// 	  \DB::rollback();
		// 	  $res = ['error' => $e->getMessage()];
		// 	  }
	}
	public function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	public function expired_user_import(){
		$manager_data = userInfo::where('status','manager')
		->get(["manager_id", "id"]);
		$logs = CsvFileLogs::where('action','Active')->orderBy('id','DESC')->get();

		return view('admin.Csv.expired-user',compact('manager_data','logs'));
	}

	public function validateDate($date,  $format = 'Y-m-d')
	{

		$d = DateTime::createFromFormat($format, $date);

		return $d && $d->format($format) === $date;
	}

	public function expired_user(Request $request){

		$validator = $request->validate([
			'file' => 'required|mimes:csv,txt','dealer_data'=>'required','reseller_data'=>'required','manager_data'=>'required'],
			['file.required' => 'Please Upload a CSV File..',
			'file.mimes' => 'Only Required CSV File.. ']);
		DB::beginTransaction();
		try {
			$get_city = userInfo::where('status','dealer')->where('dealerid',$request['dealer_data'])->first('city');
			$_SESSION["message"] = NULL;
			$_SESSION["uploadError"] = NULL;
			if(isset($_FILES['file'])){
				$errors= [];
				$file_name = $_FILES['file']['name'];
				$handle = fopen($_FILES['file']['tmp_name'],"r");
				$ext = pathinfo($file_name, PATHINFO_EXTENSION);

				if($ext == 'csv'){
					$data = fgetcsv($handle);
					$index = 0;
					$allData = [];
					$usernames = [];
					if(count($data) == "3"){

						while ($fileop = fgetcsv($handle)) {

							$allData[$index] = $fileop;
							$index++;
							for ($i=0; $i < count($fileop); $i++) {





								if (strlen(trim($fileop[$i])) < 1) {

									if ($i == 0 || $i == 1 || $i == 2  ) {

										$message = "Cell cannot be empty Kindly check line no. ".($index+1);
										return back()->with('error',$message);
									}
								}

								if ($i == 0) {
									$username = trim($fileop[$i]);
									array_push($usernames, $username);
									//
									

									$checkExpireDate = UserStatusInfo::where('username',$username)->first();
									if($checkExpireDate){
										if(strtotime($checkExpireDate->card_expire_on) > strtotime(date('Y-m-d')) ){
											$message = "Consumer already charged, check line no#. ".($index+1);
											return back()->with('error',$message);
										}}


									}

									if ($i == 1) {


										$checkDateFormat = $this->validateDate(trim($fileop[$i]), DATE_FORMAT);

										if($checkDateFormat === false) {
											$message = "Incorrect date format on Expiry date Kindly check line no. ".($index+1);
											return back()->with('error',$message);
										}



									}


									if ($i == 2) {

									// $check_activation = RaduserGroup::where('username',$username)->where('name','!=','NEW')->where('groupname','!=','NEW')->get();

									// if(count($check_activation) > 0)
									// {

									// 	$message = "User Already Charged!.. Kindly check line no. ".($index+1);
									// 	return back()->with('error',$message);
									// }

										$inputPackage = trim($fileop[$i]);
										$get_pkg = Profile::where('name',$inputPackage)->get(['groupname'])->toArray();

										if(empty($get_pkg)) {
											$message = "Incorrect Package Kindly check line no. ".($index+1);
											return back()->with('error',$message);
										}


									}
								}		
							}

						//
							if (count(array_diff_assoc($usernames, array_unique($usernames))) > 0) {
								$message = "Duplicate users in sheet";
								return back()->with('error',$message);
							}
						//
							$get_nas = AssignedNas::where('id',$request['dealer_data'])->where('nas','!=',NULL)->first();
							if (empty($get_nas)) {
								$message = "No NAS has assigned to dealer".$request['dealer_data'];
								return back()->with('error',$message);
							}
						//
							$usual_ip_count = UserUsualIP::where('status','=','0')->where('city',$get_city->city)->where('nas',$get_nas->nas)->count();
							if($usual_ip_count < count(array_unique($usernames)) ){
								$message = "Not enough CGN IPs available.";
								return back()->with('error',$message);
							}
						//
							$usual_ip_check = UserUsualIP::where('status','=','0')->where('city',$get_city->city)->where('nas',$get_nas->nas)->first();
						//
							$checkIPinRadreply = Radreply::where('value',$usual_ip_check->ip)->count();
							if($checkIPinRadreply > 0){
								$message = "IP already in use ".$usual_ip_check->ip;
								return back()->with('error',$message);
							}
						//

						//
							if ($_SESSION["uploadError"] == NULL) {

								$card_charge_by_ip = $this->get_client_ip();
								$priority='0';

								foreach ($allData as $key => $data) {
									$current_date = date(DATE_FORMAT);
									$expiry_date = $data[1];

									$diff = abs(strtotime($expiry_date) - strtotime($current_date)); 
									$days    = abs($diff)/86400;


									$username = trim($data[0]);
									$card_charge_on = date(DATE_FORMAT);
									$card_expire_on = $data[1];

									$card_charge_by = \Auth()->user()->username;
									$name = trim($data[2]);
									$sub_dealer_id = isset($request['trader_data']) ? $request['trader_data']: NULL ;
									$dealerid = isset($request['dealer_data']) ? $request['dealer_data']: NULL ;
									$resellerid = isset($request['reseller_data']) ? $request['reseller_data']: NULL ;
									$managerid = isset($request['manager_data']) ? $request['manager_data']: NULL ;
									$city = isset($get_city->city) ? $get_city->city: NULL ;
									$expiredatetime = $card_expire_on.' 12:00:00';
								//
									$check1 = UserStatusInfo::where('username',$username)->get();
									if(count($check1) > 0) {
										$query1 = [
											'card_charge_on' => $card_charge_on,
											'card_expire_on' => $card_expire_on,
											'card_charge_by' => $card_charge_by,
											'expire_datetime' => $expiredatetime,
											'card_charge_by_ip' => $card_charge_by_ip
										];
										UserStatusInfo::where('username',$username)->update($query1);



										$get_nas = AssignedNas::where('id',$dealerid)->first();
									//
										$usual_ip_check = UserUsualIP::where('status','=','0')->where('city',$city)->where('nas',$get_nas->nas)->first();
										$ip = $usual_ip_check->ip;
									//
									// $checkIPinRadreply = Radreply::where('value',$ip)->count();
									//


										$group = Profile::where('name',$name)->first(['groupname']);

										$checkradreply = UserExpireLog::where('username',$username)->get()->count();
										if($checkradreply < 1)
										{

											$user_expiry_log = new UserExpireLog();
											$user_expiry_log->username = $username;
											$user_expiry_log->profile_on_expiry = $group->groupname;
											$user_expiry_log->expired_on = $card_expire_on;
											$user_expiry_log->log_date = $card_charge_on;
											$user_expiry_log->save();

										}
									//
									// $existInIPStatus = UserIPStatus::where('username',$username)->first();
									// //
									// if($existInIPStatus){
									// 	//
									// 	$existInIPStatus->ip = $ip;
									// 	$existInIPStatus->type = 'usual_ip';
									// 	$existInIPStatus->save();
									// 	//
									// }else{
									// 	//
									// 	$user_ip = new UserIPStatus(); 
									// 	$user_ip->username = $username;
									// 	$user_ip->ip = $ip;
									// 	$user_ip->type = 'usual_ip';
									// 	$user_ip->save();
									// }
									//
										$existInRadreply = Radreply::where('username',$username)->where('value','!=',NULL)->first();
									//
										if($existInRadreply){
										//
										// $existInRadreply->attribute = 'Framed-IP-Address';
										// $existInRadreply->op ='=';
										// $existInRadreply->value = $ip;
										// $existInRadreply->dealerid = $dealerid;
										// $existInRadreply->resellerid = $resellerid;
										// $existInRadreply->sub_dealer_id = $sub_dealer_id;
										// $existInRadreply->manager_id = $managerid;
										// $existInRadreply->save();
										//	
										}else{
										//
											$rad_reply_data = new Radreply();
											$rad_reply_data->username = $username;
											$rad_reply_data->attribute = 'Framed-IP-Address';
											$rad_reply_data->op ='=';
											$rad_reply_data->value=$ip;
											$rad_reply_data->dealerid=$dealerid;
											$rad_reply_data->resellerid=$resellerid;
											$rad_reply_data->sub_dealer_id = $sub_dealer_id;
											$rad_reply_data->manager_id =$managerid;
											$rad_reply_data->save();
										//
											$existInIPStatus = UserIPStatus::where('username',$username)->first();
									//
											if($existInIPStatus){
										//
												$existInIPStatus->ip = $ip;
												$existInIPStatus->type = 'usual_ip';
												$existInIPStatus->save();
										//
											}else{
										//
												$user_ip = new UserIPStatus(); 
												$user_ip->username = $username;
												$user_ip->ip = $ip;
												$user_ip->type = 'usual_ip';
												$user_ip->save();
											}
											//
											$update_usual_ip = UserUsualIP::where('ip',$ip)->update(['status' => '1']);
											//
										}


										$groupname = Profile::where('name',$name)->first();

										$rad_data = ['groupname' => $groupname->groupname,'name' => $name];
										RaduserGroup::where('username',$username)->update($rad_data);
										$query3 = ['profile' => $groupname->groupname,'name' => $name];
										UserInfo::where('username',$username)->update($query3);

										$now = date('Y-m-d H:i:s');

										$expired_users = ['status' => 'charge','last_update' => $now];
										ExpireUser::where('username',$username)->update($expired_users);
									}

								}
								try{
									$csv_logs = new CsvFileLogs();
									$csv_logs->date = date(DATE_FORMAT);
									$csv_logs->time=date('H:i:s');
									$csv_logs->created_by = \Auth::user()->username;
									$csv_logs->manager_id = $managerid;
									$csv_logs->reseller_id = $resellerid;
									$csv_logs->dealer_id = $dealerid;
									$csv_logs->trader_id = $sub_dealer_id;
									$csv_logs->action = 'Active';
									$csv_logs->count = count($allData);
									$csv_logs->save();
									\DB::commit();
								}
								catch (\Exception $e) {
									\DB::rollback();
									$res = ['error' => $e->getMessage()];
								}
								DB::commit();
								$message =  'User expiry updated successfully';
								return back()->with('success',$message);
							}

						} else{
							$message = 'Invalid columns';
							return back()->with('error',$message);
						}

					} else{
						$message = 'Invalid file format.Please upload only CSV file format.';
						return back()->with('error',$message);
					}
				}
			}catch (\Exception $e) {
				DB::rollback();
				$res = ['error' => $e->getMessage()];
			//
				return back()->with('error',$res);
			  } // end catch
		} //end expired_user Function
		


		//////////////////////////////////////////////////

		public function shift_user(Request $request)
		{
			$manager_data = userInfo::where('status', 'manager')->get(["manager_id", "id"]);
			return view('admin.Csv.shift-dealer', compact('manager_data'));
		}

		public function shift_user_store(Request $request)
		{
			$validator = $request->validate([
				'file' => 'required|mimes:csv,txt', 'manager_data' => 'required'],
				['file.required' => 'Please Upload a CSV File..',
				'file.mimes' => 'Only Required CSV File.. ']);

			DB::beginTransaction();
			try {
				$_SESSION["message"] = NULL;
				$_SESSION["uploadError"] = NULL;
				if (isset($_FILES['file'])) {
					$errors = null;
					$file_name = $_FILES['file']['name'];
					$handle = fopen($_FILES['file']['tmp_name'], "r");
					$ext = pathinfo($file_name, PATHINFO_EXTENSION);

					if ($ext == 'csv') {
						$data = fgetcsv($handle);
						$index = 0;
						$allData = [];
						$usernames = [];

						if (count($data) == "1") {
							while ($fileop = fgetcsv($handle)) {

								$allData[$index] = $fileop;
								$index++;
								for ($i = 0; $i < count($fileop); $i++) {

									if (strlen(trim($fileop[$i])) < 1) {
										if ($i == 0 ) {
											$errors = "Cell cannot be empty Kindly check line no. " . ($index + 1);
											return back()->with('error', $errors);
										}
									}
									if ($i == 0) {
										$username = trim($fileop[$i]);
										array_push($usernames, $username);
									}



								}
							}

							/*check duplicate users*/
							if (count(array_diff_assoc($usernames, array_unique($usernames))) > 0) {
								$errors = "Duplicate users in sheet" . ($index + 1);
								return back()->with('error', $errors);
							}

							$get_user = UserInfo::where('username', $usernames)->first(['dealerid', 'sub_dealer_id', 'manager_id', 'id']);
							if (empty($get_user)) {
								$errors = "No user exists." . ($index + 1);
								return back()->with('error', $errors);
							}
							/*check errors not exist*/
							if ($errors == NULL) {
								foreach ($allData as $key => $data) {
									$username = trim($data[0]);
									// $sub_from = isset($get_user->sub_dealer_id) ? $get_user->sub_dealer_id : '';
									// $dealeridFrom = isset($get_user->dealerid) ? $get_user->dealerid : '';

									$reseller_to = $request['reseller_data'];
									$dealerid_to = $request['dealer_data'];
									$trader_to =  (empty($request['trader_data'])) ? NULL : $request['trader_data'];

									/*update Data*/
									$update_data = ['resellerid' => $reseller_to, 'dealerid' => $dealerid_to, 'sub_dealer_id' => $trader_to];

									$user_info = UserInfo::where('username', $username)->where('status', 'user')->update($update_data);
									if($user_info){
										$user_verification = UserVerification::where('username', $username)->update($update_data);
										$radcheck = RadCheck::where('username', $username)->update($update_data);
										$radreply = Radreply::where('username', $username)->update($update_data);
									}
								}
								\DB::commit();
								$message = 'User Shifted successfully';
								return back()->with('success', $message);
							}
						} else {
							$errors = 'Invalid columns';
							return back()->with('error', $errors);
						}

					} else {
						$errors = 'Invalid file format.Please upload only CSV file format.';
						return back()->with('error', $errors);
					}
				}
			} catch (\Exception $e) {
				\DB::rollback();
				return $e->getMessage();
			}
		}






		public function download_active_user_sheet(request $request){
			$id = $request->get('id');
			$status = $request->get('status');
			//
			$delimiter = ",";
			$f = fopen('php://memory', 'w');
			//
			$fields = array('Username','Firstname','Lastname','Dealer ID','Sub dealer ID','Package','Card Charge On','Card Expire On','Password','Address','Mobile Number', 'NIC#');
			fputcsv($f, $fields, $delimiter);
			//
			$today = date('Y-m-d');
			//////////
			if($status == 'dealer'){
				//
				$query  = DB::table('user_info as a')
				->join('user_status_info as b', 'b.username', '=', 'a.username')
				->where('b.expire_datetime', '>', DATE('Y-m-d H:i:s'))
				->where('a.dealerid',$id)
				->where('b.card_expire_on','>=', $today)
				->get();

			}else{
				// 
				$query  = DB::table('user_info as a')
				->join('user_status_info as b', 'b.username', '=', 'a.username')
				->where('b.expire_datetime', '>', DATE('Y-m-d H:i:s'))
				->where('a.sub_dealer_id',$id)
				->where('b.card_expire_on','>=', $today)
				->get();
			}
			////////
			foreach($query as $row){
		//
				$username = $row->username;
				$package = RaduserGroup::where('username',$username)->first();
				$password = RadCheck::where('username',$username)->where('attribute','Cleartext-Password')->first();

		//
				$lineData = array($row->username,$row->firstname,$row->lastname,$row->dealerid,$row->sub_dealer_id,$package->groupname,$row->card_charge_on,$row->card_expire_on,$password->value,$row->permanent_address,$row->mobilephone,$row->nic);
				fputcsv($f, $lineData, $delimiter);
	//
			}
			////////

			$filename= $id.'-active-user-sheet.csv';
			fseek($f, 0);
			//set headers to download file rather than displayed
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename="' . $filename . '";');
			//output all remaining data on a file pointer
			fpassthru($f);
			exit;
		}


	}
