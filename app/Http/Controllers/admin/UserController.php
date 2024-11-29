<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\RadCheck;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\RaduserGroup;
use App\model\Users\UserStatusInfo;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\Profile;
use App\model\Users\UserExpireLog;
use App\model\Users\UserAmount;
use App\model\Users\Nas;
use App\model\Users\CactiGraph;
use App\model\Users\AssignNasType;
use App\model\Users\StaticIPServer;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\admin\userAccess;
use App\model\Users\SubMenu;
use App\model\Users\UserMenuAccess;

class UserController extends Controller
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
 		case "manager" : {
 			$current = Auth::user()->status;
 			if($current == "super"){
 				$managerCollection = UserInfo::where(['status' => 'manager'])->get();
 			}else{
 				$managerCollection = UserInfo::where(['status' => 'manager','creationby' =>'admin'])->get();
 			}
 			
 			$nas_type = Nas::all();
 			return view('admin.users.view_manager',[
 				'managerCollection' => $managerCollection,
 				'nas_type' => $nas_type
 			]);
 		}break;
 		case "reseller" : {
 			$current = Auth::user()->status;
 			if($current == "super"){
 				$resellerCollection = UserInfo::where(['status' => 'reseller'])->get();
 				$managerIdList = UserInfo::where(['status' => 'manager'])->get(['manager_id']);
 				$nas_type = Nas::all();
 			}else{
 				$resellerCollection = UserInfo::where(['status' => 'reseller','creationby' =>'admin'])->get();
 				$managerIdList = UserInfo::where(['status' => 'manager','creationby' =>'admin'])->get(['manager_id']);
 				$nas_type = Nas::all();
 			}
 			
 			
 			
 			return view('admin.users.view_reseler',[
 				'resellerCollection' => $resellerCollection,
 				'managerIdList' => $managerIdList,
 				'nas_type' => $nas_type
 			]);
 		}break;
 		case "dealer" : {
 			$dealerCollection = UserInfo::where(['status' => 'dealer'])->get();
 			$managerIdList = UserInfo::where(['status' => 'manager','manager_id' =>'logonmanager'])->get(['manager_id']);
 			$resellerIdList = UserInfo::where(['status' => 'reseller','resellerid' => 'logonbroadband'])->get(['resellerid']);
 			$nas_type = Nas::all();
 			return view('admin.users.view_dealer',[
 				'dealerCollection' => $dealerCollection,
 				'managerIdList' => $managerIdList,
 				'resellerIdList' => $resellerIdList,
 				'nas_type' => $nas_type
 			]);
		 }break;
		 case "subdealer" : {
			$subdealerCollection = UserInfo::where(['status' => 'subdealer','resellerid' => 'logonbroadband'])->get();
			$managerIdList = UserInfo::where(['status' => 'manager','manager_id' =>'logonmanager'])->get(['manager_id']);
			$resellerIdList = UserInfo::where(['status' => 'reseller','resellerid' => 'logonbroadband'])->get(['resellerid']);
			$nas_type = Nas::all();
			return view('admin.users.view_sub_dealer',[
				'subdealerCollection' => $subdealerCollection,
				'managerIdList' => $managerIdList,
				'resellerIdList' => $resellerIdList,
				'nas_type' => $nas_type
			]);
		}break;
// 		case "user" : {
// 			$usersCollection = array();
// 			$currentStatus = Auth::user()->status;
// 			if($currentStatus == "administrator"){
// 			$userDealer = UserInfo::leftJoin('user_status_info', function($join) {
// 					$join->on('user_status_info.username', '=', 'user_info.username');
// 			})->where(['user_info.resellerid' => 'logonbroadband' ,'user_info.status' => 'user'])->where(function($q) {
// 					$q->where('user_status_info.expire_datetime','>=',date('Y-m-d H:i:s'));
// 			})->get();
// 			foreach ($userDealer as $value) {

// 				$online = UserStatusInfo::where(['username' => $value->username])->get();
// 				foreach ($online as $value) {
// 						$usersCollection[] = $value;
// 				}
// 		}

// 	}else{
// 			return redirect()->route('admin.dashboard');
// 	}

	

// return view('admin.users.view_user',[
// 		'usersCollection' => $usersCollection,
// 		'userDealer' =>  $userDealer,

// ]);
// }

// 		break;
    case "remove" : {
      
      return view('admin.users.data_remove');
    }break;
 		default :{
 			return redirect()->route('admin.dashboard');
 		}
 	}

 }

 public function store(Request $request, $status)
 {


 	$user = new UserInfo();
 	switch($status){
 		case "manager" : {
                // validation
 			$this->validate($request,[
 				'username' => 'required|unique:user_info',
 				'manager_id' => 'required|unique:user_info',
 				'password'	=> 'required|confirmed'
 			]);
 			$user->username = $request->get('username'); 
 			$user->manager_id = $request->get('manager_id'); 
 			$user->resellerid = ''; 
 			$user->dealerid = ''; 
 			$user->sub_dealer_id = '';

 			$user->password = Hash::make($request->get('password'));


                  //assigned server

 			$server = new AssignNasType();
 			$server->dealerid = '';
 			$server->resellerid = '';
 			$server->manager_id = $request->get('manager_id');
 			$server->sub_dealer_id = '';
 			$server->nas_type = $request->get('nas_type');
 			$server->save();              

 		}break;
 		case "reseller" : {


                // validation

 			$this->validate($request,[
 				'username' => 'required|unique:user_info',
 				'manager_id' => 'required',
 				'resellerid' => 'required|unique:user_info',
 				'password'	=> 'required|confirmed'
 			]);
 			$user->username = $request->get('username'); 
 			$user->manager_id = $request->get('manager_id');
 			$user->resellerid = $request->get('resellerid'); 

 			$user->password = Hash::make($request->get('password')); 
 			$user->dealerid = '';            
 			$user->sub_dealer_id = '';
           $user->profile ='BE-4096k'; // by default profile

                // default amount is zero;
           $userAmount = new UserAmount();
           $userAmount->username = $request->get('username');
           $userAmount->status = 'reseller';
           $userAmount->amount = 0;
           $userAmount->save();

                // saving in redCheck: entry - 1
           $RadCheck = new RadCheck();
           $RadCheck->username = $request->get('username');
           $RadCheck->attribute ='Cleartext-Password';
           $RadCheck->op =':=';
           $RadCheck->value = $request->get('password');
           $RadCheck->dealerid ='';
           $RadCheck->sub_dealer_id ='';
           $RadCheck->svlan ='';
           $RadCheck->save();
                ////

                // saving in redCheck: entry - 2
           $RadCheck = new RadCheck();
           $RadCheck->username = $request->get('username');
           $RadCheck->attribute ='Simultaneous-Use';
           $RadCheck->op =':=';
           $RadCheck->value = '1';
           $RadCheck->dealerid ='';
           $RadCheck->sub_dealer_id ='';
           $RadCheck->svlan ='';
           $RadCheck->save();
                ///
                  // saving in redCheck: entry - 3
           $RadCheck = new RadCheck();
           $RadCheck->username = $request->get('username');
           $RadCheck->attribute ='Calling-Station-Id';
           $RadCheck->op =':=';
           $RadCheck->value = 'NEW';
           $RadCheck->dealerid ='';
           $RadCheck->sub_dealer_id ='';
           $RadCheck->svlan ='';
           $RadCheck->save();
                ///
                  //assigned server

           $server = new AssignNasType();
           $server->dealerid = '';
           $server->resellerid = $request->get('resellerid'); 
           $server->manager_id = $request->get('manager_id');
           $server->sub_dealer_id = '';
           $server->nas_type = $request->get('nas_type');
           $server->save();
                ///////Getting IP from usualIP
           $userusualIPs = UserUsualIP::where(['status' => '0'])->first();
           $ip = $userusualIPs->ip;

                // inserting ip in radreply
           $radreply = new Radreply();
           $radreply->username = $request->get('username');
           $radreply->attribute ='Framed-IP-Address';
           $radreply->op ='=';
           $radreply->value = $ip;
           $radreply->dealerid ='';
           $radreply->resellerid = $request->get('resellerid'); 
           $radreply->sub_dealer_id ='';
           $radreply->save();

                // changing status to 1 of got ip in usualIP
           $userusualIPs->status='1';
           $userusualIPs->save();

                // inserting ip in radusergroup
           $radusergroup = new RaduserGroup();
           $radusergroup->username = $request->get('username');
           $radusergroup->groupname ='DISABLED';
           $radusergroup->priority = '0';
           $radusergroup->save();

                ////inserting into useripstatus

           $useripstatus = new UserIPStatus();
           $useripstatus->username = $request->get('username');
           $useripstatus->ip = $ip;
           $useripstatus->type = 'usual_ip';
           $useripstatus->save();

           // menu access setting 24 Jan 2023 start //

            /*
            $Uname = $request->get("username");
            $accessID = UserInfo::where("username", $Uname)->first();
            $accessID = $accessID->id;
            $subMenu = SubMenu::where("flag", "cp")->get();

            foreach ($subMenu as $key => $submenu) {
                $ac_id = $submenu->id;
                $accessMenu = new UserMenuAccess();
                $accessMenu->user_id = $accessID;
                $accessMenu->sub_menu_id = $submenu->id;
                $sts = 0;
                if (
                    $ac_id == 1 ||
                    $ac_id == 3 ||
                    $ac_id == 6 ||
                    $ac_id == 7 ||
                    $ac_id == 14 ||
                    $ac_id == 15 ||
                    $ac_id == 16 ||
                    $ac_id == 17 ||
                    $ac_id == 18 ||
                    $ac_id == 19 ||
                    $ac_id == 20 ||
                    $ac_id == 21 ||
                    $ac_id == 22 ||
                    $ac_id == 23 ||
                    $ac_id == 24 ||
                    $ac_id == 25 ||
                    $ac_id == 26 ||
                    $ac_id == 27 ||
                    $ac_id == 28 ||
                    $ac_id == 29 ||
                    $ac_id == 30 ||
                    $ac_id == 31 ||
                    $ac_id == 32 ||
                    $ac_id == 33 ||
                    $ac_id == 38 ||
                    $ac_id == 39 ||
                    $ac_id == 40
                ) {
                    $sts = 1;
                } else {
                    $sts = 0;
                }
                $accessMenu->status = $sts;
                $accessMenu->created_at = NOW();
                $accessMenu->save();
            }
        */
          
     // menu access setting 24 Jan 2023  end //

       }break;
       case "dealer" : {

                // validation
       	$this->validate($request,[
       		'username' => 'required|unique:user_info',
       		'manager_id' => 'required',
       		'resellerid' => 'required',
       		'dealerid' => 'required|unique:user_info',
       		'password'	=> 'required|confirmed'
       	]);

       	$user->username = $request->get('username'); 
       	$user->manager_id = $request->get('manager_id');
       	$user->resellerid = $request->get('resellerid');
       	$user->dealerid = $request->get('dealerid');

       	$user->password = Hash::make($request->get('password'));  
       	$user->sub_dealer_id = '';
       	$user->profile ='BE-2048k';

                // default amount is zero;
       	$userAmount = new UserAmount();
       	$userAmount->username = $request->get('username');
       	$userAmount->status = 'dealer';
       	$userAmount->amount = 0;
       	$userAmount->save();

                 //assigned server

       	$server = new AssignNasType();
       	$server->dealerid = $request->get('dealerid');
       	$server->resellerid = $request->get('resellerid'); 
       	$server->manager_id = $request->get('manager_id');
       	$server->sub_dealer_id = '';
       	$server->nas_type = $request->get('nas_type');
       	$server->save();
               // saving in redCheck: entry - 1
       	$RadCheck = new RadCheck();
       	$RadCheck->username = $request->get('username');
       	$RadCheck->attribute ='Cleartext-Password';
       	$RadCheck->op =':=';
       	$RadCheck->value = $request->get('password');
       	$RadCheck->dealerid = $request->get('dealerid');
       	$RadCheck->sub_dealer_id ='';
       	$RadCheck->svlan = $request->get('dealerid');
       	$RadCheck->save();
                ////

                // saving in redCheck: entry - 2
       	$RadCheck = new RadCheck();
       	$RadCheck->username = $request->get('username');
       	$RadCheck->attribute ='Simultaneous-Use';
       	$RadCheck->op =':=';
       	$RadCheck->value = '1';
       	$RadCheck->dealerid = $request->get('dealerid');
       	$RadCheck->sub_dealer_id ='';
       	$RadCheck->svlan = $request->get('dealerid');
       	$RadCheck->save();
                ///

                // saving in redCheck: entry - 3
       	$RadCheck = new RadCheck();
       	$RadCheck->username = $request->get('username');
       	$RadCheck->attribute ='Calling-Station-Id';
       	$RadCheck->op =':=';
       	$RadCheck->value = 'NEW';
       	$RadCheck->dealerid =$request->get('dealerid');
       	$RadCheck->sub_dealer_id ='';
       	$RadCheck->svlan = $request->get('dealerid');
       	$RadCheck->save();
                ///


                ///////Getting IP from usualIP
       	$userusualIPs = UserUsualIP::where(['status' => '0'])->first();
       	$ip = $userusualIPs->ip;

                // inserting ip in radreply
       	$radreply = new Radreply();
       	$radreply->username = $request->get('username');
       	$radreply->attribute ='Framed-IP-Address';
       	$radreply->op ='=';
       	$radreply->value = $ip;
       	$radreply->dealerid = $request->get('dealerid');
       	$radreply->resellerid = $request->get('resellerid'); 
       	$radreply->sub_dealer_id ='';
       	$radreply->save();

                // changing status to 1 of got ip in usualIP
       	$userusualIPs->status='1';
       	$userusualIPs->save();

                // inserting ip in radusergroup
       	$radusergroup = new RaduserGroup();
       	$radusergroup->username = $request->get('username');
       	$radusergroup->groupname ='BE-2048k';
       	$radusergroup->priority = '0';
       	$radusergroup->save();

                ////inserting into useripstatus

       	$useripstatus = new UserIPStatus();
       	$useripstatus->username = $request->get('username');
       	$useripstatus->ip = $ip;
       	$useripstatus->type = 'usual_ip';
       	$useripstatus->save();          

                //cacti graph

       	$graph = new CactiGraph();
       	$graph->user_id =$request->get('username');
       	$graph->graph_no ="0";
       	$graph->save();

                ///cacti graph end  

       }break;
   }
   $user->firstname = $request->get('fname');
   $user->lastname = $request->get('lname');
   $user->address = $request->get('address');
   $user->mobilephone = $request->get('mobile_number');
   $user->homephone = $request->get('land_number');
   $user->nic = $request->get('nic');
   $user->email = $request->get('mail');
   $user->area = $request->get('area');
   $user->creationdate =date('Y-m-d');
   $user->creationby = Auth::user()->status;
   $user->creationbyip = $request->ip();
   $user->disabled = '';
   $user->disabled_old_profile = '';
   $user->disabled_expired = '';
        // $user->disabled_date = date('Y-m-d H:m:s',strtotime('0000-00-00 00:00:00'));
   $user->verified = 0;
   $user->status = $status;
   $user->save();

   //24 jan 2023 //

     $Uname = $request->get("username");
     $accessID = UserInfo::where("username", $Uname)->first();

        if ($accessID->status == "reseller") {
            $accessID = $accessID->id;
            $subMenu = SubMenu::where("flag", "cp")->get();

            //dd($subMenu);

            foreach ($subMenu as $key => $submenu) {
                $ac_id = $submenu->id;
                $accessMenu = new UserMenuAccess();
                $accessMenu->user_id = $accessID;
                $accessMenu->sub_menu_id = $submenu->id;
                $sts = 0;
                if (
                    $ac_id == 1 ||
                    $ac_id == 3 ||
                    $ac_id == 6 ||
                    $ac_id == 7 ||
                    $ac_id == 14 ||
                    $ac_id == 15 ||
                    $ac_id == 16 ||
                    $ac_id == 17 ||
                    $ac_id == 18 ||
                    $ac_id == 19 ||
                    $ac_id == 20 ||
                    $ac_id == 21 ||
                    $ac_id == 22 ||
                    $ac_id == 23 ||
                    $ac_id == 24 ||
                    $ac_id == 25 ||
                    $ac_id == 26 ||
                    $ac_id == 27 ||
                    $ac_id == 28 ||
                    $ac_id == 29 ||
                    $ac_id == 30 ||
                    $ac_id == 31 ||
                    $ac_id == 32 ||
                    $ac_id == 33 ||
                    $ac_id == 38 ||
                    $ac_id == 39 ||
                    $ac_id == 40
                ) {
                    $sts = 1;
                } else {
                    $sts = 0;
                }
                $accessMenu->status = $sts;
                $accessMenu->created_at = NOW();
                $accessMenu->save();
            }
        }

   // 24 jan 2023//
   session()->flash('success',' created success fully.');
   return redirect()->route('admin.user.index',['status' => $status]);
}

public function edit($status,$id){
	switch($status){
		case "manager" : {
			$manager = UserInfo::find($id);
			$profileList = Profile::orderBy('groupname')->get();
                $assignedProfileRates = ManagerProfileRate::where('manager_id',$manager->manager_id)->get(); // assign
                $assignedProfileNameList = array();
                foreach ($assignedProfileRates as $profileRate) {
                	$assignedProfileNameList[] = ucfirst($profileRate->name);
                }
                
                return view('admin.users.update_manager',[
                	'id' => $id,
                	'manager' => $manager,
                	'profileList' => $profileList,

                	'assignedProfileRates' => $assignedProfileRates,
                	'assignedProfileNameList' => $assignedProfileNameList
                ]);
            }break;
            case "reseller" : {
            	$reseller = UserInfo::find($id);
            	$profileList = Profile::orderBy('groupname')->get();
                $assignedProfileRates = $reseller->reseller_profile_rate; // assign
                $assignedProfileNameList = array();
                foreach ($assignedProfileRates as $profileRate) {
                	$assignedProfileNameList[] = ucfirst($profileRate->profile->name);
                }

                $userAmount = UserAmount::where(['username'=> $reseller->username])->first();
                return view('admin.users.update_reseller',[
                	'id' => $id,
                	'reseller' => $reseller,
                	'profileList' => $profileList,


                	'assignedProfileRates' => $assignedProfileRates,
                	'assignedProfileNameList' => $assignedProfileNameList,
                	'userAmount' => $userAmount
                ]);

            }break;
            case "dealer" : {
            	$dealer = UserInfo::find($id);
            	$profileList = Profile::orderBy('groupname')->get();
                $assignedProfileRates = $dealer->dealer_profile_rates; // assign
                $graph1 = CactiGraph::where(['user_id'=>$dealer->username])->first();
                $assignedProfileNameList = array();
                foreach ($assignedProfileRates as $profileRate) {
                	$assignedProfileNameList[] = ucfirst($profileRate->profile->name);
                }
                $userAmount = UserAmount::where(['username'=> $dealer->username])->first();
                return view('admin.users.update_dealer',[
                	'id' => $id,
                	'dealer' => $dealer,
                	'profileList' => $profileList,
                	'assignedProfileRates' => $assignedProfileRates,
                	'assignedProfileNameList' => $assignedProfileNameList,
                	'graph1'=> $graph1,
                	'userAmount' => $userAmount
                ]);
			}break;
			case "subdealer" : {
            	$subdealer = UserInfo::find($id);
            	$profileList = Profile::orderBy('groupname')->get();
                $assignedProfileRates = $subdealer->subdealer_profile_rates; // assign
                $graph1 = CactiGraph::where(['user_id'=>$subdealer->username])->first();
                $assignedProfileNameList = array();
                foreach ($assignedProfileRates as $profileRate) {
                	$assignedProfileNameList[] = ucfirst($profileRate->profile->name);
                }
                $userAmount = UserAmount::where(['username'=> $subdealer->username])->first();
                return view('admin.users.update_dealer',[
                	'id' => $id,
                	'dealer' => $subdealer,
                	'profileList' => $profileList,
                	'assignedProfileRates' => $assignedProfileRates,
                	'assignedProfileNameList' => $assignedProfileNameList,
                	'graph1'=> $graph1,
                	'userAmount' => $userAmount
                ]);
            }break;
            case "user" : {


            	$user = UserInfo::find($id);

            	return view('admin.users.update_users',[
            		'id' => $id,
            		'user' => $user

            	]);
            }break;
            default :{
            	return redirect()->route('admin.dashboard');
            }
        }


    }

/////// show profile////////
    public function show(Request $request, $status){
    	$id = $request->get('id');
    	if($id){
    		switch($status){
    			case "manager" : {
    				$manager = UserInfo::find($id);
    				if($manager){
    					if ($manager->status == 'manager'){
    						return view('admin.users.user_detail',[
    							'user' => $manager
    						]);
    					}else{
                                abort(404); // data not found
                                // return redirect()->route('admin.dashboard');
                            }
                        }else{
                            abort(404); // data not found
                        }
                    }break;
                    case "reseller" : {
                    	$reseller = UserInfo::find($id);

                    	$userstatusinfo = UserStatusInfo::where(["username" => $reseller->username])->first();
                    	$userexpirelog = UserExpireLog::where(["username" => $reseller->username])->first();
                    	$userProfileRates = $reseller->reseller_profile_rate;
                    	$userRadCheck = RadCheck::where(["username" => $reseller->username, "attribute" => "Cleartext-Password"])->first();
                    	if ($reseller->status == 'reseller'){
                    		return view('admin.users.user_detail',[
                    			'user' => $reseller,
                    			'userRedCheck' => $userRadCheck,
                    			'userProfileRates' => $userProfileRates,
                    			'userstatusinfo' => $userstatusinfo,
                    			'userexpirelog' => $userexpirelog
                    		]);
                    	}else{
                    		return redirect()->route('admin.dashboard');
                    	}

                    }break;
                    case "dealer" : {
                    	$dealer = UserInfo::find($id);
                    	$userstatusinfo = UserStatusInfo::where(["username" => $dealer->username])->first();
                    	$userexpirelog = UserExpireLog::where(["username" => $dealer->username])->first();


                    	$userProfileRates = $dealer->dealer_profile_rates;
                    	$userRadCheck = RadCheck::where(["username" => $dealer->username, "attribute" => "Cleartext-Password"])->first();
                    	if ($dealer->status == 'dealer'){
                    		return view('admin.users.user_detail',[
                    			'user' => $dealer,
                    			'userRedCheck' => $userRadCheck,
                    			'userProfileRates' => $userProfileRates,
                    			'userstatusinfo' => $userstatusinfo,
                    			'userexpirelog' => $userexpirelog
                    		]);

                    	}else{
                    		return redirect()->route('admin.dashboard');

                    	}
                    }break;
					case "subdealer" : {
                    	$subdealer = UserInfo::find($id);
                    	$userstatusinfo = UserStatusInfo::where(["username" => $subdealer->username])->first();
                    	$userexpirelog = UserExpireLog::where(["username" => $subdealer->username])->first();


                    	$userProfileRates = $subdealer->dealer_profile_rates;
                    	$userRadCheck = RadCheck::where(["username" => $subdealer->username, "attribute" => "Cleartext-Password"])->first();
                    	if ($subdealer->status == 'subdealer'){
                    		return view('admin.users.user_detail',[
                    			'user' => $subdealer,
                    			'userRedCheck' => $userRadCheck,
                    			'userProfileRates' => $userProfileRates,
                    			'userstatusinfo' => $userstatusinfo,
                    			'userexpirelog' => $userexpirelog
                    		]);

                    	}else{
                    		return redirect()->route('admin.dashboard');

                    	}
                    }break;
                    case 'user': {


                    	$user = UserInfo::find($id);
                    	$userRadCheck = RadCheck::where(["username" => $user->username, "attribute" => "Cleartext-Password"])->first();
                    	$userstatusinfo = UserStatusInfo::where(["username" => $user->username])->first();
                    	$userexpirelog = UserExpireLog::where(["username" => $user->username])->first();
                    	$package= $user->profile;

                    	$package = str_replace('BE-', '', $package);
                    	$package = str_replace('k', '', $package);
                    	$profile = Profile::where(['groupname'=>$package])->first();

                      $cur_pro = RaduserGroup::select('name')->where(["username" => $user->username])->first();
                      $package = str_replace('BE-', '', $cur_pro->groupname);
                      $package = str_replace('k', '', $package);
                      // $cur_pro = Profile::where(['groupname'=>$package])->first();

                    	if ($user->status == 'user'){
                    		return view('admin.users.user_detail',[
                    			'id' => $id,
                    			'user' => $user,
                    			'userRedCheck' => $userRadCheck,
                    			'userstatusinfo' => $userstatusinfo,
                    			'userexpirelog' => $userexpirelog,
                    			'profile' =>$profile,
                          'cur_profile' => $cur_pro

                    		]);
                    	}else {

                    		return redirect()->route('admin.dashboard');
                    	}



                    }

                    break;

                     default :{
                    	return redirect()->route('admin.dashboard');
                    }
                }
            }
            else{
            	return redirect()->route('admin.dashboard');
            }
        }







/////// end profile///////
        public function update(Request $request, $status,$id){
        	switch($status){
        		case "manager" : {
        			$manager = UserInfo::find($id);
        			// $manager->password = Hash::make($request->get('password')); 
        			$manager->firstname = $request->get('fname');
        			$manager->lastname = $request->get('lname');
        			$manager->address = $request->get('address');
        			$manager->mobilephone = $request->get('mobile_number');
        			$manager->homephone = $request->get('land_number');
        			$manager->nic = $request->get('nic');
        			$manager->email = $request->get('mail');
        			$manager->area = $request->get('area');
        			$manager->save();
                    //
                    if ($request->hasFile('cnic_front')) {
                        $mgr_cnic_front = $request->file('cnic_front');
                        $mgr_cnic_front_name = $request->get("manager_id"). '-cnic_front.jpg';
                        $mgr_cnic_front->move(public_path('Manager-NIC/'), $mgr_cnic_front_name);
                    }
                    if ($request->hasFile('cnic_back')) {
                        $mgr_cnic_back = $request->file('cnic_back');
                        $mgr_cnic_back_name = $request->get("manager_id"). '-cnic_back.jpg';
                        $mgr_cnic_back->move(public_path('Manager-NIC/'), $mgr_cnic_back_name);
                    }
    //             delete existing profile rates of manager
        			$dlt = ManagerProfileRate::where(['manager_id' => $manager->manager_id])->delete();
                // getting assigned profile rates
        			$profileList = Profile::all();
        			foreach($profileList as $profile){
        				$profileName = ucfirst($profile->name);
        				if($request->has(''.$profileName)){
                        $profileRate = $request->get(''.$profileName); // will get rate form request
                        $groupName = $profile->groupname;
                        $name = $profile->name;
                        $manager_id = $manager->manager_id;
                        
                        // save into manager profile rate.
                        $managerProfileRate =  new ManagerProfileRate();
                        $managerProfileRate->groupname = $groupName;
                        $managerProfileRate->name = $name;
                        $managerProfileRate->manager_id = $manager_id;
                        $managerProfileRate->ip_rates = 0;
                        $managerProfileRate->billing_type = 'amount';
                        $managerProfileRate->rate = $profileRate;
                        $managerProfileRate->save();
                    }
                }
                session()->flash('success','Manager success fully updated.');
                return redirect()->route('admin.user.index',['status' => $status]);
            }break;
            case "reseller" : {
            	$reseller = UserInfo::find($id);


            	$reseller->firstname = $request->get('fname');
            	$reseller->lastname = $request->get('lname');
            	$reseller->address = $request->get('address');
            	$reseller->mobilephone = $request->get('mobile_number');
            	$reseller->homephone = $request->get('land_number');
            	$reseller->nic = $request->get('nic');
            	$reseller->email = $request->get('mail');
            	$reseller->area = $request->get('area');
            	$reseller->save();

			// StaticIPServer
			  $ipassign = $request->get('ipassign');
			  $ipType = $request->get('ip_type');
            	if($ipassign == 'assign'){
					$staticIP = new StaticIp();
                
            		$numip= $request->get('noofip');
					$iptype=$request->get('ip_type');
				
					$thisreseller = $request->get('resellerid');
					$thisusername = $reseller->username;
					$ips = StaticIPServer::where(['resellerid' => $thisreseller, 'type' => 'static'])->count();

					$userID = StaticIp::all();
					$userV = '';
					foreach( $userID as $value)
					$v = $value->username;
					if($v == $thisusername)
					 $userV = $v;
            		for ($i=0; $i < $numip ; $i++) { 
                        // 
            			$serverip= StaticIPServer::where(['type' => $iptype])->whereNull('dealerid')->whereNull('resellerid')->where(['status' => 'NEW'])->first();
                        // 
                        $ip=$serverip->ipaddress;  			
         // 
            			DB::table('static_ips_server')
            			->where('ipaddress', $ip)
						->update(array('resellerid' => $thisreseller));
						
						if($thisusername == $userV )
                        {
                         // return "Aslam";
                         if($ipType == 'static'){
                        DB::table('static_ips')
                        ->where('username',  $thisusername)
                        ->update(array('numberofips' => $ips+1));
                         }
                        }else{
                        //return "Not";
                        $staticIP->username = $reseller->username;
                        $staticIP->numberofips = $request->get('noofip');
                      
                        $staticIP->rates = 0;
                        $staticIP->save();
                        }

            		}
            	}else if($ipassign == 'remove'){

            		$numip= $request->get('noofip');
                $iptype=$request->get('ip_type');
			
				$thisreseller = $request->get('resellerid');
				$thisusername = $reseller->username;
				$ips = StaticIPServer::where(['resellerid' => $thisreseller,'type' => 'static'])->count();


                for ($i=0; $i < $numip ; $i++) { 
                        // 
                  $serverip= StaticIPServer::where(['type' => $iptype])->whereNull('dealerid')->where(['resellerid' => $thisreseller])->where(['status' => 'NEW'])->first();
                        // 
                       
                        $ip=$serverip->ipaddress;
                                
         // 
                  DB::table('static_ips_server')
                  ->where('ipaddress', $ip)
                  ->update(array('resellerid' => NULL));
					// Aslam Code Here for update No of Ips
					if($ipType == 'static'){
						DB::table('static_ips')
						->where('username',  $thisusername)
						->update(array('numberofips' => $ips-1));
					}
                }

            	}




                //end 


				// delete existing profile rates of resller
            	$dlt = ResellerProfileRate::where(['resellerid' => $reseller->resellerid])->delete();
                // getting assigned profile rates
            	$profileList = Profile::all();
            	foreach($profileList as $profile){
            		$profileName = ucfirst($profile->name);
            		if($request->has(''.$profileName)){
                        $profileRate = $request->get(''.$profileName); // will get rate form request
                        $groupName = $profile->groupname;
                        $resellerId = $reseller->resellerid;
                        
                        // save into reseller profile rate.
                        $resellerProfileRate =  new ResellerProfileRate();
                        $resellerProfileRate->groupname = $groupName;
                        $resellerProfileRate->resellerid = $resellerId;
                        $resellerProfileRate->sst = 0;
                        $resellerProfileRate->adv_tax = 0;
                        $resellerProfileRate->ip_rates = 0;
                        $resellerProfileRate->billing_type = 'amount';
                       
                        $resellerProfileRate->rate = $profileRate;
                        $resellerProfileRate->final_rates = $profileRate;
                        $resellerProfileRate->save();
                    }
                }

                session()->flash('success','Reseller success fully updated.');
                return redirect()->route('admin.user.index',['status' => $status]);
            }break;
            case "dealer" : {

            	$dealer = UserInfo::find($id);


            	$dealer->firstname = $request->get('fname');
            	$dealer->lastname = $request->get('lname');
            	$dealer->address = $request->get('address');
            	$dealer->mobilephone = $request->get('mobile_number');
            	$dealer->homephone = $request->get('land_number');
            	$dealer->nic = $request->get('nic');
            	$dealer->email = $request->get('mail');
            	$dealer->area = $request->get('area');
            	$dealer->save();



                    // catigraph
            	CactiGraph::where(['user_id'=>$dealer->username])->delete();
            	$newarray=array();
            	$mydata=$request->get('graph');
            	for ($i=0; $i < sizeof($mydata) ; $i++) { 

            		if ($mydata[$i] != NULL) {
            			$graph1 = new CactiGraph();
            			$graph1->user_id =$request->get('username');
            			$graph1->graph_no =$mydata[$i];
            			$graph1->save();

            		}
            	}



             // end

              // StaticIPServer
        //     	if($request->has('ipassign')){
        //     		$numip= $request->get('noofip');
        //     		$iptype=$request->get('ip_type');
        //     		$thisreseller=$request->get('resellerid');
        //     		$thisdealer=$request->get('dealerid');

        //     		for ($i=0; $i < $numip ; $i++) { 

        //                 // 
        //     			$serverip= StaticIPServer::select('ipaddress')->where(['resellerid' => $thisreseller,'dealerid' => NULL ,'status' => 'NEW','type' => $iptype])->first();
        //                 // 
        //     			$ip=$serverip->ipaddress;

        //  // 
        //     			DB::table('static_ips_server')
        //     			->where('ipaddress', $ip)
        //     			->update(array('dealerid' => $thisdealer));

        //     		}
		//     	}
		//StaticIP Server Aslam Code
		$ipassign = $request->get('ipassign');
		$ipType = $request->get('ip_type');
		if($ipassign == 'assign'){
		  
		  $staticIP = new StaticIp();

		  $numip = $request->get('noofip');
		  $iptype = $request->get('ip_type');
		  $thisdealer = $dealer->dealerid;
		  $thisreseller = $request->get('resellerid');
		  $thisusername = $dealer->username;
		  $ips = StaticIPServer::where(['dealerid' => $thisdealer,'type' => 'static'])->count();
		  
		  $userID = StaticIp::all();
		  $userV = '';
		  foreach( $userID as $value)
		  $v = $value->username;
		  if($v == $thisusername)
			//return "yes";
		   $userV = $v;
			//else return "not";
		  //return $userID;
		  
			for ($i=0; $i < $numip ; $i++) { 
			//
			$serverip= StaticIPServer::where(['type' => $iptype])->whereNull('dealerid')->where(['resellerid' => $thisreseller])->where(['status' => 'NEW'])->first();
			// 
			$ip=$serverip->ipaddress;       
// 
			DB::table('static_ips_server')
			->where('ipaddress', $ip)
			->update(array('dealerid' => $thisdealer));
		   
			if($thisusername == $userV )
			{
			 // return "Aslam";
			 if($ipType == 'static'){
			DB::table('static_ips')
			->where('username',  $thisusername)
			->update(array('numberofips' => $ips+1));
			 }
			}else{
			//return "Not";
			$staticIP->username = $dealer->username;
			$staticIP->numberofips = $request->get('noofip');
		  
			$staticIP->rates = $request->input('rates');
			$staticIP->save();
			}


		  }
		  
		}else if($ipassign == 'remove'){


		  $numip = $request->get('noofip');
		  $iptype = $request->get('ip_type');
		  $thisdealer = $dealer->dealerid;
		  $thisreseller = $request->get('resellerid');
		  $thisusername = $dealer->username;
		  $ips = StaticIPServer::where(['dealerid' => $thisdealer,'type' => 'static'])->count();
		 
		  
		  for ($i=0; $i < $numip ; $i++) { 
			//
			$serverip= StaticIPServer::where(['type' => $iptype])->where(['dealerid' => $thisdealer])->where(['resellerid' => $thisreseller])->where(['status' => 'NEW'])->first();
			// 
			$ip=$serverip->ipaddress;       
// 
			DB::table('static_ips_server')
			->where('ipaddress', $ip)
			->update(array('dealerid' => NULL));
			//                
			// Aslam Code Here for update No of Ips
			if($ipType == 'static'){
			DB::table('static_ips')
			->where('username',  $thisusername)
			->update(array('numberofips' => $ips-1));
			}
		  }
		}
		// Aslam Code End
                // delete existing profile rates of resller
            	$dlt = DealerProfileRate::where(['dealerid' => $dealer->dealerid])->delete();
                // getting assigned profile rates
            	$profileList = Profile::all();
            	foreach($profileList as $profile){
            		$profileName = ucfirst($profile->name);
            		if($request->has(''.$profileName)){
                        $profileRate = $request->get(''.$profileName); // will get rate form request
                        $groupName = $profile->groupname;
                        $dealerid = $dealer->dealerid;
                        
                        // save into reseller profile rate.
                        $dealerProfileRate =  new DealerProfileRate();
                        $dealerProfileRate->groupname = $groupName;
                        $dealerProfileRate->dealerid = $dealer->dealerid;
                        $dealerProfileRate->rate = $profileRate;
                        $dealerProfileRate->ip_rates = 0;
                        $dealerProfileRate->show_sub_dealer = '';
                        $dealerProfileRate->billing_type = 'amount';
                        $dealerProfileRate->verify = 0;
                        
                        $dealerProfileRate->save();
                    }
                }
                session()->flash('success','Dealer success fully updated.');
                return redirect()->route('admin.user.index',['status' => $status]);
            }break;
            default :{
            	return redirect()->route('admin.dashboard');
            }
        }
    }



              // ////////
              public function clearMac(Request $request){

                $username = $request->get('clearmac');
                $userid = $request->get('userid');

                $user = UserInfo::where(["username" => $username, "status" => "user"])->first();

                $userRadCheck = RadCheck::where(["username" => $user->username, "attribute" => "Cleartext-Password"])->first();
                $userstatusinfo = UserStatusInfo::where(["username" => $user->username])->first();
                $userexpirelog = UserExpireLog::where(["username" => $user->username])->first();
                $package= $user->profile;

                $package = str_replace('BE-', '', $package);
                $package = str_replace('k', '', $package);
                $profile = Profile::where(['groupname'=>$package])->first();

                $clearMac=RadCheck::where(["username" => $username, "attribute" => "Calling-Station-Id"])->first();
                $clearMac->value='NEW';
                $clearMac->save();


                return redirect()->route('admin.user.show',['status' => 'user','id' => $userid]);



              }


               public function dataremove(Request $request){
                $username =$request->username;
               $nic =$request->nic;
               $mobile =$request->mobile;
               $image =$request->image;
               if($nic =="on"){
                  $removedata = UserVerification::where(['username'=>$username])->first();
                  $removedata->cnic =NULL;
                  
                  $removedata->save();
                  
               }
               if($mobile =="on"){
                $removedata = UserVerification::where(['username'=>$username])->first();
                  $removedata->mobile =NULL;
                  $removedata->mobile_status =NULL;
                  $removedata->verificationCode =NULL;
                  
                  $removedata->save();
               }
               if($image =="on"){
                $removedata = UserVerification::where(['username'=>$username])->first();
                 $removedata->nic_front =NULL;
                  $removedata->nic_back =NULL;
                  
                  $removedata->save();
               }
               return redirect()->route('admin.user.index',['status' => 'remove']);
              }


} 
