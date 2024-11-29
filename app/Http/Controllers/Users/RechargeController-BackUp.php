<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\UserStatusInfo;
use App\model\Users\RadCheck;
use App\model\Users\DealerProfileRate;
use App\model\Users\DealerFUP;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\TraderProfileRate;
use App\model\Users\AmountUserRechargeLog;
use App\model\Users\AmountBillingInvoice;
use App\model\Users\RaduserGroup;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\UserAmount;
use App\model\Users\AssignNasType;
use App\model\Users\AssignedNas;
use App\model\Users\Profile;
use App\model\Users\Nas;
use App\model\Users\ExpireUser;
use App\model\Users\UserVerification;
use App\model\Users\UserAmountBillingBrg;
use App\model\Users\FreezeAccount;
use App\model\Users\HalfBillingInvoice;
use App\model\Users\Card;
use App\model\Users\Tax;
use App\model\Users\Domain;
use App\model\Users\Cirprofile;
use Illuminate\Support\Facades\Route;
use App\model\Users\Error;
use Session;


class RechargeController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 
 public function __construct()
 {

 }


 public function index($status)
 {
 	$user = Auth::user();
 	$check ='';
 	$freeze_account = FreezeAccount::where(['username' => $user->username])->first();
 	$check = @$freeze_account['freeze'];
 	// if(!empty($freeze_account)){
 	// 	if($check == 'yes'){
 	// 		return redirect()->route('users.dashboard');
 	// 	}
 	// }
 	switch ($status) {
 		case 'recharge':{
 			$profileRates = collect();
 			$userList = collect();
 			if($user->status == 'dealer'){
 				$profileRates = $user->dealer_profile_rates;
 				$userList = $user->dealer_expired_users()->get();
 				$rechargeUsers = AmountBillingInvoice::where(['dealerid' => $user->dealerid , 'date' => date('Y/m/d') , 'recharge_from' => 'single' ])->get();
 				$currentAmount = UserAmount::where(['username' => $user->username])->first();
 			}elseif($user->status == 'subdealer'){					
 				$profileRates = $user->subdealer_profile_rates;
 				$userList = $user->sub_dealer_expired_users()->get();
 				$rechargeUsers = AmountBillingInvoice::where(['sub_dealer_id' => $user->sub_dealer_id , 'date' => date('Y/m/d')])->get();
 				$currentAmount = UserAmount::where(['username' => $user->username])->first();
 			}elseif($user->status == 'trader'){					
 				$profileRates = $user->trader_profile_rates;
 				$userList = $user->trader_expired_users()->get();
 				$rechargeUsers = AmountBillingInvoice::where(['sub_dealer_id' => $user->sub_dealer_id , 'date' => date('Y/m/d')])->get();
 				$currentAmount = UserAmount::where(['username' => $user->username])->first();
 			}
 			return view('users.dealer.recharge',[
 				'userList' => $userList,
 				'profileRates' => $profileRates,
 				'rechargeUsers' =>$rechargeUsers,
 			]);	
 		}
 		break;
 		case 'bulk':
 		$user = Auth::user();
 		$profileRates = collect();
 		$userList = collect();
 		if($user->status == 'dealer'){
 			$profileRates = $user->dealer_profile_rates;
					  // $userList = $user->dealer_expired_users()->get();
						// dd('dealer');
 			$userList = UserInfo::leftJoin('user_status_info', function($join) {
 				$join->on('user_status_info.username', '=', 'user_info.username');
 			})->where(['user_info.dealerid' => $user->dealerid ,'user_info.status' => 'user','user_info.sub_dealer_id' => NULL])->where('user_info.profile' , '!=','DISABLED')->where('user_info.name' , '!=','DISABLED')->where(function($q) {
 				$q->where('user_status_info.card_expire_on','>=',date('Y-m-d',strtotime(date('Y-m-d').' -1 months')));
 			})->select('user_info.username','user_info.dealerid','user_info.firstname','user_info.lastname','user_info.profile','user_info.address','user_info.name')->get();
 			$currentAmount = UserAmount::where(['username' => $user->username])->first();
 		}else{	
						// dd('sub');				
 			$profileRates = $user->subdealer_profile_rates;
 			$userList = UserInfo::leftJoin('user_status_info', function($join) {
 				$join->on('user_status_info.username', '=', 'user_info.username');
 			})->where(['user_info.sub_dealer_id' => $user->sub_dealer_id ,'user_info.status' => 'user'])->where('user_info.profile' , '!=','DISABLED')->where('user_info.name' , '!=','DISABLED')->where(function($q) {
 				$q->where('user_status_info.card_expire_on','>=',date('Y-m-d',strtotime(date('Y-m-d').' -1 months')));
 			})->select('user_info.username','user_info.sub_dealer_id','user_info.firstname','user_info.lastname','user_info.profile','user_info.address','user_info.name')->get();
						//$user->sub_dealer_expired_users()->get();
 			$currentAmount = UserAmount::where(['username' => $user->username])->first();
 		}
 		return view('users.dealer.bulk_recharge',[
 			'userList' => $userList,
 		]);
 		break;
 		default:
 		return redirect()->route('users.dashboard');
 		break;
 	}
 }
 public function bulkRecharge(Request $request)
 {
 	$dataList = $request->get('dataList');
		// dd($dataList);	

 	$count = 0;
 	foreach($dataList as $data){

 		$d = explode(',',$data);
 		$username = $d[0];
 		$profileGroupname = $d[1];
			// dd($profileGroupname);
////////////////////////////////////////////////////////////////////////////////////////////////////////////

//  dd('New');
 		$f_nic = '';
 		$nic = '';
 		$b_nic = '';
 		$ntn = '';
 		$passport = '';
 		$overseas = '';
 		$uname = '';
 		$profileRate = '';
 		$resellerprofileRate = '';
	   ////verification allow
 		$checkDealeralow='';
 		$checkalowverification='';
 		$verification='';
 		$alowdealer='';
 		$checkDealeralow = UserInfo::where('username',$username)->first();
 		$alowdealer = $checkDealeralow['dealerid'];
 		$alowsubdealer = $checkDealeralow['sub_dealer_id'];
 		if(empty($alowsubdealer)){

 			$checkalowverification = DealerProfileRate::where('dealerid',$alowdealer)->first();
 		}else{

 			$checkalowverification = SubdealerProfileRate::where('sub_dealer_id',$alowsubdealer)->first();
 		}
 		$verification = @$checkalowverification['verify'];

 		if($verification == 'no'){
 			$f_nic = 'done';
 			$nic = 'done';
 			$b_nic = 'done';
 			$uname = $username;
 			$ntn ='done';
 			$passport = 'done';
 			$overseas = 'done';
 		}else{
 			$isverify = UserVerification::where('username',$username)->select('username','cnic','nic_front','nic_back','ntn','intern_passport','overseas')->first();

 			$f_nic = $isverify['nic_front'];
 			$nic = $isverify['cnic'];
 			$b_nic = $isverify['nic_back'];
 			$uname = $isverify['username'];
 			$ntn =$isverify['ntn'];
 			$passport = $isverify['intern_passport'];
 			$overseas = $isverify['overseas'];
 		}

 		if(($username == $uname) && ($nic != '' || $ntn !='' || $overseas != '' || $passport != '' )&& ($f_nic != '' && $b_nic != '')){

 			$Checkingexpiry = UserStatusInfo::where(['username'=>$username])->first();
 			$expire = $Checkingexpiry['card_expire_on'];
 			$cur_date=date('Y-m-d');
 			if($expire > $cur_date){
 				session()->flash('error',' Your user Already charge.');
 				return redirect()->route('users.user.index1',['status' => 'user']);
 			}else{
 				$getGroup = Profile::where(['name'=> $profileGroupname])->first();
// $profileGroupname = $getGroup->name;
// 			dd($profileGroupname);
//.................................................................................................................//
//  New Card Charge code start from here.......
 				$checkIsSubdealerUser = UserInfo::where('username',$username)->select('username','dealerid','sub_dealer_id')->first();
 				$billingType = DealerProfileRate::where('dealerid',$checkIsSubdealerUser->dealerid)->select('billing_type')->first();
// dd($billingType->billing_type);
 				if($checkIsSubdealerUser->sub_dealer_id != NULL && $billingType->billing_type == 'card'){

 					$getGroup = Profile::where(['name'=> $profileGroupname])->first();
 					$profileGroupname = $getGroup->name;
				// dd($profileGroupname);
 					$currentUser = Auth::user();
 					$check ='';
 					$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
 					$check = @$freeze_account['freeze'];

 					if(!empty($freeze_account)){
 						if($check == 'yes'){
 							return redirect()->route('users.dashboard');
 						}
 					} 
 					$card = Card::where('sub_dealer_id',Auth::user()->sub_dealer_id)->where('status','unused')->where('name',$profileGroupname)->first();
 					if($card != null){

 						$card_nos = $card->card_no;
 						$status = $card->status;
 						$m_rate = $card->m_rate;
 						$rate = $card->rate;
 						$dealerrate = $card->dealerrate;
 						$subdealerrate = $card->subdealerrate;
 						$s_acc_rate = $card->s_acc_rate;
 						$sst = $card->sst;
 						$adv_tax = $card->adv_tax;
 						$traderrate = $card->traderrate;
 						$t_acc_rate = $card->t_acc_rate;
 						$commision = $card->commision;
 						$m_acc_rate = $card->m_acc_rate;
 						$r_acc_rate = $card->r_acc_rate;
 						$d_acc_rate = $card->d_acc_rate;
 						$c_sst = $card->c_sst;
 						$c_adv = $card->c_adv;
 						$c_charges = $card->c_charges;
 						$c_rates = $card->c_rates;
 						$profit = $card->profit;
 						$profile = $card->profile;
 						$name = $card->name;
 						$taxname = $card->taxname;
 						$charge_on = date('Y-m-d H:i:s');
 						$trader_id = $card->trader_id;
 						$dealerid = $card->dealerid;
 						$resellerid = $card->resellerid;
 						$sub_dealer_id = $card->sub_dealer_id;
 						$manager_id = $card->manager_id;
 						$date = date('Y-m-d');
 						$billing_type = 'card';
	  // will recharge the user
 						$this->rechargeUser($request,$name,$username,$currentUser);
		  //////////////////////
 						$username = $username;
 						$date =str_replace('-', '', date('Y-m-d'));
 						$userid = $username.$date;

 						if($sst == 0){
 							$receipt = 0;
 						}else{
 							$receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
 							$num = @$receipt_last->receipt_num;
 							$receipt = $num ? $num : 1;
 						}
   //    $profit = $profit;
 						$receipt1 = $currentUser->receipt;
 						$receipt_num = $receipt;
 						$charge_on = date('Y-m-d H:i:s');
 						$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'groupname' => $profile])->first();
 						$dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
 						AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,'logon',$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,$billing_type,$card_nos);
	   //update Card Table while card used
 						$card->status = 'used';
 						$card->save();
 					}else{
 						session()->flash('error',' No profile available.');
 						return redirect()->route('users.single',$checkDealeralow->id);
 					}
 					if(Auth::user()->status == "dealer"){
 						$activity = "view Account Recharged by".Auth::user()->dealerid;
 					}elseif(Auth::user()->status == "subdealer"){
 						$activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
 					}
 					else{
 						$activity = "view Account Recharged by".Auth::user()->trader_id;
 					}
 					$amountUserRechargeLog = new AmountUserRechargeLog();
 					$amountUserRechargeLog->dealerid = Auth::user()->dealerid;
 					$amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
 					$amountUserRechargeLog->trader_id = Auth::user()->trader_id;
 					$amountUserRechargeLog->username = $username;
 					$amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
 					$amountUserRechargeLog->activity = $activity;
	  // $amountUserRechargeLog->error =
 					$amountUserRechargeLog->ipaddress = $request->ip();
 					$amountUserRechargeLog->profile = $profile;
 					$amountUserRechargeLog->save();
	  // amount before recharge
 					$userAmountBillingBrg = new UserAmountBillingBrg();
 					$userAmountBillingBrg->billing_invoice_id = 1;
 					$userAmountBillingBrg->last_remaining_amount =NULL;
 					$userAmountBillingBrg->save();

	//   session()->flash('success',' Successfully Recharged..........');
	//   return redirect()->route('users.user.index1',['status' => 'user']);
//.......................................................................................................................//
 				}else{
	//

 					$currentUser = Auth::user();
 					$check ='';
 					$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
 					$check = @$freeze_account['freeze'];

 					if(!empty($freeze_account)){
 						if($check == 'yes'){
 							return redirect()->route('users.dashboard');
 						}
 					} 
//  dd($profileGroupname);
 					$getsubdealerrate='';
 					$getdealerrate = '';
 					if($currentUser->status == "subdealer")
 					{
 						$getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'name' => $profileGroupname])->first();
 						if(empty($getsubdealerrate))
 						{
 							return redirect()->route('users.dashboard');
 						}
 					}elseif($currentUser->status == "dealer"){
 						$getdealerrate = DealerProfileRate::where(['dealerid'=>Auth::user()->dealerid,'name' => $profileGroupname])->first();

 						if(empty($getdealerrate))
 						{
 							return redirect()->route('users.dashboard');
 						}
 					}elseif($currentUser->status == "trader"){
 						$getdealerrate = SubdealerProfileRate::where(['sub_dealer_id'=>Auth::user()->sub_dealer_id,'name' => $profileGroupname])->first();

 						if(empty($getdealerrate))
 						{
 							return redirect()->route('users.dashboard');
 						}
 					}
 // current user reseller
 					$currentUserResellerUserName = UserInfo::select('username')->where(['resellerid' => $currentUser->resellerid,'status' => 'reseller'])->first();
 					$resellerUserAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
 					$resellerAmount = $resellerUserAmount->amount;
 ///

 // check user has sufficiant amount: user_amount
 					$userAmount = UserAmount::where('username', $currentUser->username)->first();
 					$amount = $userAmount->amount;

 					if($currentUser->status == 'dealer'){
 						$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'name' => $profileGroupname])->first();
 						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'name' => $profileGroupname])->first();
 						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'name' => $profileGroupname])->first();
 						$profileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'name' => $profileGroupname])->first();
 						$customertax = Profile::where(['name' => $profileGroupname])->first();
 						$taxgroup = $dealerprofileRate['taxgroup'];
 						$name = $customertax->name;
 					}elseif($currentUser->status == 'subdealer'){
 						$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'name' => $profileGroupname])->first();
 						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'name' => $profileGroupname])->first();
 						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'name' => $profileGroupname])->first();
 						$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'name'=>$profileGroupname])->first();
 						$customertax = Profile::where(['name' => $profileGroupname])->first();
 						$taxgroup = $profileRate['taxgroup'];
 						$name = $customertax->name;
 					}elseif($currentUser->status == 'trader'){
 						$dealerprofileRate = DealerProfileRate::where(['dealerid' => $currentUser->dealerid,'name' => $profileGroupname])->first();
 						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid,'name' => $profileGroupname])->first();
 						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id,'name' => $profileGroupname])->first();
 						$s_profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'name'=>$profileGroupname])->first();
 						$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id,'name'=>$profileGroupname])->first();
 						$customertax = Profile::where(['name' => $profileGroupname])->first();
 						$taxgroup = $profileRate['taxgroup'];
 						$name = $customertax->name;
 					}

 					if($amount >= $profileRate['final_rates']){
	// will recharge the user
 						$this->rechargeUser($request,$profileGroupname,$username,$currentUser);
 						// if($taxgroup == "B"){
 						// 	$customersst = $customertax->sstB;
 						// 	$customeradv = $customertax->adv_taxB;
 						// 	$customercharges = $customertax->chargesB;
 						// 	$customerrates = $customertax->final_ratesB;
 						// 	$taxname = "CX-V2";
 						// }else if($taxgroup == "C"){
 						// 	$customersst = $customertax->sstC;
 						// 	$customeradv = $customertax->adv_taxC;
 						// 	$customercharges = $customertax->chargesC;
 						// 	$customerrates = $customertax->final_ratesC;
 						// 	$taxname = "CX-V3";
 						// }else if($taxgroup == "D"){
 						// 	$customersst = $customertax->sstD;
 						// 	$customeradv = $customertax->adv_taxD;
 						// 	$customercharges = $customertax->chargesD;
 						// 	$customerrates = $customertax->final_ratesD;
 						// 	$taxname = "CX-V4";
 						// }else if($taxgroup == "E"){
 						// 	$customersst = $customertax->sstE;
 						// 	$customeradv = $customertax->adv_taxE;
 						// 	$customercharges = $customertax->chargesE;
 						// 	$customerrates = $customertax->final_ratesE;
 						// 	$taxname = "CX-V5";
 						// }
 						// else{
 						// 	$customersst = $customertax->sst;
 						// 	$customeradv = $customertax->adv_tax;
 						// 	$customercharges = $customertax->charges;
 						// 	$customerrates = $customertax->final_rates;
 						// 	$taxname = "CX-V1";
 						// }

 						$customersst = 0;
 						$customeradv = 0;
 						$customercharges = 0;
 						$customerrates = 0;
 						$taxname = null;
		//////////////////////
 						$username = $username;
 						$date =str_replace('-', '', date('Y-m-d'));
 						$userid = $username.$date;
 						$m_rate = $m_profileRate['final_rates'];
 						$rate = $resellerprofileRate['final_rates'];

 						if(Auth::user()->status =="subdealer"){
 							$dealerrate = $dealerprofileRate['final_rates'];	
 							$subdealerrate = $profileRate['final_rates'];
 							$s_acc_rate = $profileRate->rate;
 							$sst = $profileRate->sst;
 							$adv_tax = $profileRate->adv_tax;
 							$traderrate = 0;

 							$t_acc_rate = 0;
 							$c_sst = $sst;
 							$c_adv = $adv_tax;
 							$c_charges = $profileRate['consumer'];
 							$c_rates = $customerrates;

 						}elseif(Auth::user()->status =="dealer"){
 							$dealerrate = $dealerprofileRate['final_rates'];
 							$subdealerrate=0;
 							$s_acc_rate = 0;
 							$traderrate = 0;

 							$t_acc_rate = 0;

 							$sst = $dealerprofileRate->sst;
 							$adv_tax = $dealerprofileRate->adv_tax;
 							$c_sst = $sst;
 							$c_adv = $adv_tax;
 							$c_charges = $dealerprofileRate['consumer'];
 							$c_rates = $customerrates;
 						}else{
 							$dealerrate = $dealerprofileRate->rate;
 							$subdealerrate = $s_profileRate['final_rates'];
 							$traderrate = $profileRate['final_rates'];
 							$s_acc_rate = $s_profileRate->rate;
 							$t_acc_rate = $profileRate->rate;
 							$sst = $profileRate->sst;
 							$adv_tax = $profileRate->adv_tax;

 							$c_sst = $sst;
 							$c_adv = $adv_tax;
 							$c_charges = $s_profileRate['consumer'];
 							$c_rates = $customerrates;
 						}

 						if($dealerprofileRate->sst == 0){
 							$receipt = 0;
 						}else{
 							$receipt_last = AmountBillingInvoice::where('receipt_num','!=','0')->select('receipt_num')->orderBy('receipt_num','DESC')->limit(1)->first();
 							$num = @$receipt_last->receipt_num;
 							$receipt = $num ? $num : 1;

 							$receipt = $num +1;
 						}
 						$d_r = $dealerprofileRate->rate;
 						$r_r = $resellerprofileRate->rate;
 						$c_a = $dealerprofileRate->commision;
 						$p_r = $d_r - $r_r;
 						$profit = $p_r - $c_a;
 						$commision = $dealerprofileRate->commision;
 						$m_acc_rate = $m_profileRate->rate;
 						$r_acc_rate = $resellerprofileRate->rate;
 						$d_acc_rate = $dealerprofileRate->rate;
 						$profit = $profit;
 						$receipt1 = $currentUser->receipt;
 						$receipt_num = $receipt;
	// $profile = $profileGroupname;
 						$profile = Profile::where('name',$profileGroupname)->first();
 						$profile = $profile->groupname;
 						$name = $name;
 						$taxname = $taxname;
 						$charge_on = date('Y-m-d H:i:s');
 						$trader_id = Auth::user()->trader_id;
 						$sub_dealer_id = Auth::user()->sub_dealer_id;
 						$dealerid = Auth::user()->dealerid;
 						$resellerid = Auth::user()->resellerid;
 						$manager_id = Auth::user()->manager_id;
 						$date = date('Y-m-d');
 						$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid,'name' => $profileGroupname])->first();
 						$dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];
 						AmountBillingInvoice::amountBilling($username,$userid,$m_rate,$rate,$dealerrate,$subdealerrate,$s_acc_rate,$sst,$adv_tax,$traderrate,$t_acc_rate,$commision,$m_acc_rate,$r_acc_rate,$d_acc_rate,$c_sst,$c_adv,$c_charges,$c_rates,$profit,'logon',$receipt_num,$profile,$name,$dasti_amount,$taxname,$charge_on,$trader_id,$dealerid,$resellerid,$sub_dealer_id,$manager_id,$date,'amount',Null);
 /////////////////////////
 						if(Auth::user()->status == "dealer"){
 							$activity = "view Account Recharged by".Auth::user()->dealerid;
 						}elseif(Auth::user()->status == "subdealer"){
 							$activity = "view Account Recharged by".Auth::user()->sub_dealer_id;
 						}
 						else{
 							$activity = "view Account Recharged by".Auth::user()->trader_id;
 						}

 						$amountUserRechargeLog = new AmountUserRechargeLog();
 						$amountUserRechargeLog->dealerid = Auth::user()->dealerid;
 						$amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
 						$amountUserRechargeLog->trader_id = Auth::user()->trader_id;
 						$amountUserRechargeLog->username = $username;
 						$amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
 						$amountUserRechargeLog->activity = $activity;
	// $amountUserRechargeLog->error =
 						$amountUserRechargeLog->ipaddress = $request->ip();
 						$profile = Profile::where('name',$profileGroupname)->first();
 						$amountUserRechargeLog->profile = $profile->groupname;
 						$amountUserRechargeLog->save();
	// amount before recharge
 						$userAmountBillingBrg = new UserAmountBillingBrg();
 						$userAmountBillingBrg->billing_invoice_id = 1;
 						$userAmountBillingBrg->last_remaining_amount =$amount;
 						$userAmountBillingBrg->save();
	// in last
 						$userAmount = UserAmount::where('username', $currentUser->username)->first();
 						$userAmount->amount -= $profileRate->final_rates;
 						$userAmount->save();

 					}else{
 						session()->flash('error',' You have insufficient amount.');
 						return redirect()->route('users.user.index1',['status' => 'user']);
 					}
 				}
//  New Card Charge code End here.......
 			}
 		}else{
 			session()->flash('success',' Please Verify Your Cnic then  Recharged.');
 			return redirect()->route('users.user.index1',['status' => 'user']);
 		}
 		$count++;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
 	}
 	session()->flash('success',' Total '.$count.' User  Successfully Recharged ');
 	return redirect()->route('users.charge.index',['status' => 'bulk']);
 }
 public function single($id)
 {
 	$current = Auth::user()->status;
 	$data['chargeBy'] = Auth::user()->username;
 	$data['walletAmount'] = UserAmount::where('username',$data['chargeBy'])->first()->amount;
 	$data['info'] = UserInfo::find($id);
 	//
 	if($current =="dealer"){
 		$user =UserInfo::find($id);
			// dd($user->sub_dealer_id);
 		if($user->sub_dealer_id != NULL){
 			session()->flash('error','Sorry this is not your consumer');
 			return back();
 		}
 		
 		$checkUser = $user->username;
		    // 
 		$dealerUser = UserInfo::where(['username' => $checkUser ,'dealerid'=>Auth::user()->dealerid,'sub_dealer_id'=> null])->first();
		 // 
 		$username = $dealerUser->username;
 		$profile = $dealerUser->name;
		// 
 		$profilename = Profile::where('name',$profile)->first();
		// 
 		if(empty($profilename)){
 			session()->flash('error','Please select profile first');
 			return back();
 		}
		//
 		$name = $profilename->name;
		//
 		$profileData = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->where(['name' => $profile])->first(); 

 	}elseif($current =="subdealer"){
 		$user =UserInfo::find($id);
 		$checkUser = $user->username;
 		//
 		$subdealerUser = UserInfo::where(['username' => $checkUser ,'sub_dealer_id'=> Auth::user()->sub_dealer_id])->first();
 		$username = $subdealerUser->username;
 		$profile = $subdealerUser->name;
		//
 		$profilename = Profile::where(['name'=>$profile])->first();
 		$name = $profilename->name;
 		//
 		$profileData = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $profile])->first();

 	}else{
 		$user =UserInfo::find($id);
 		$checkUser = $user->username;

 		$subdealerUser = UserInfo::where(['username' => $checkUser ,'trader_id'=> Auth::user()->trader_id])->first();
 		$username = $subdealerUser->username;
 		$profile = $subdealerUser->name;
		    // $profile = str_replace('BE-', '', $profile);
		    // $profile = str_replace('k', '', $profile);

 		$profilename = Profile::where(['name'=>$profile])->first();
 		$name = $profilename->name;
 	}
 	//
 	if(empty($profileData)){
 		session()->flash('error','Please select profile first');
 		return back();
 	}
 	//
 	$taxData = Tax::first();
 	//
 	$taxRev_fll = $taxRev_cvas = $taxRev_tip = $data['info']->profile_amount;
 	//
 	if($data['info']->company_rate == 'yes'){
 	//
 		if($taxData->fll_sst == 'yes'){
 			$taxRev_fll = $data['info']->profile_amount / ($profileData->sstpercentage+1);
 		}if($taxData->cvas_sst == 'yes'){
 			$taxRev_cvas = $data['info']->profile_amount / ($profileData->sstpercentage+1);
 		}if($taxData->tip_sst == 'yes'){
 			$taxRev_tip = $data['info']->profile_amount / ($profileData->sstpercentage+1);
 		}
 	//
 		if($taxData->fll_adv == 'yes'){
 			$taxRev_fll = $taxRev_fll / ($profileData->advpercentage+1);
 		}if($taxData->cvas_adv == 'yes'){
 			$taxRev_cvas = $taxRev_cvas / ($profileData->advpercentage+1);
 		}if($taxData->tip_adv == 'yes'){
 			$taxRev_tip = $taxRev_tip / ($profileData->advpercentage+1);
 		}
 	//
 	}
 	//
 	$fll_charges = $taxRev_fll * $taxData->fll_tax;
 	$cvas_charges = $taxRev_cvas * $taxData->cvas_tax;
 	$tip_charges = $taxRev_tip * $taxData->tip_tax;
 	//
 	$total_charges = $fll_charges + $cvas_charges + $tip_charges;

 	//
 	$data['consumerPrice'] = $data['info']->profile_amount;
 	$data['margin'] = $data['consumerPrice'] - $profileData->base_price_ET;
 	//
 	$fll_sst = $cvas_sst = $tip_sst = 0;
 	$fll_adv = $cvas_adv = $tip_adv = 0;
 	//
 	if($taxData->fll_sst == 'yes'){
 		$fll_sst = $fll_charges * $profileData->sstpercentage;
 	}if($taxData->cvas_sst == 'yes'){
 		$cvas_sst = $cvas_charges * $profileData->sstpercentage;
 	}if($taxData->tip_sst == 'yes'){
 		$tip_sst = $tip_charges * $profileData->sstpercentage;
 	}
 	//
 	$data['sst'] = $fll_sst + $cvas_sst + $tip_sst;

 	//
 	if($taxData->fll_adv == 'yes'){
 		$fll_adv = ($fll_charges + $fll_sst) * $profileData->advpercentage;
 	}if($taxData->cvas_adv == 'yes'){
 		$cvas_adv = ($cvas_charges + $cvas_sst) * $profileData->advpercentage;
 	}if($taxData->tip_adv == 'yes'){
 		$tip_adv = ($tip_charges + $tip_sst) * $profileData->advpercentage;
 	}
 	// 
 	$data['adv'] = $fll_adv + $cvas_adv + $tip_adv;

 	$infoData = UserInfo::where(['dealerid' => Auth::user()->dealerid])->where(['status' => 'dealer'])->first();
 	
 	$data['taxStatus'] = $infoData->is_filer;
 	//
 	if($data['margin'] > 0){
 		if($data['taxStatus'] == 'filer'){
 			$data['tax'] = $data['margin'] * $taxData->filer_tax;
 		}elseif($data['taxStatus'] == 'non filer'){
 			$data['tax'] = $data['margin'] * $taxData->non_file_tax;
 		}else{
 			$data['tax'] = 0;
 		}

 	}else{
 		$data['tax'] = 0 ;
 	}
 	$data['staticIPAmnt'] = $data['info']->static_ip_amount;
 	$data['total'] = $total_charges + $data['sst'] + $data['adv'] + $data['tax']; // + $data['staticIPAmnt']; static removed by irfan bhai from deduction
 	// $data['total'] = $data['total'] - $data['margin'];
 	$data['wallet_deduction'] = $data['total'];
 	//
 	if($data['info']->company_rate == 'no'){
 		$data['total'] = $data['total'] - $data['margin'];
 		// $data['wallet_deduction'] = $data['total'];
 		$data['wallet_deduction'] = $data['total'] / 2 ;

 	}
 	//
 	$data['wallet_after'] = $data['walletAmount'] - $data['wallet_deduction'];
 	$data['profileRate'] = $total_charges;
 	//
 	//
 	return view('users.dealer.single_recharge',[
 		'username' => $username,
 		'name' => $name,
 		'data' => $data,

 	]);

 }

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 public function viewUserRecharge(Request $request)
 {

 	$username = $request->get('username');
 	$name = $request->get('profileGroupname');

 	$f_nic = '';
 	$nic = '';
 	$b_nic = '';
 	$ntn = '';
 	$passport = '';
 	$overseas = '';
 	$uname = '';
 	$profileRate = '';
 	$resellerprofileRate = '';
		////verification allow
 	$checkDealeralow = '';
 	$checkalowverification = '';
 	$verification = '';
 	$alowdealer = '';
 	$checkDealeralow = UserInfo::where('username', $username)->first();
 	$alowdealer = $checkDealeralow['dealerid'];
 	$alowsubdealer = $checkDealeralow['sub_dealer_id'];
 	if (empty($alowsubdealer)) {

 		$checkalowverification = DealerProfileRate::where('dealerid', $alowdealer)->first();
 	} else {

 		$checkalowverification = SubdealerProfileRate::where('sub_dealer_id', $alowsubdealer)->first();
 	}
 	$verification = $checkalowverification['verify'];

 	if ($verification == 'no') {
 		$f_nic = 'done';
 		$nic = 'done';
 		$b_nic = 'done';
 		$uname = $username;
 		$ntn = 'done';
 		$passport = 'done';
 		$overseas = 'done';
 	} else {
 		$isverify = UserVerification::where('username', $username)->select('username', 'cnic', 'nic_front', 'nic_back', 'ntn', 'intern_passport', 'overseas')->first();

 		if (empty($isverify)) {
 			Session()->flash('error', ' Please Verified first.');
				// return redirect()->route('users.user.index1', ['status' => 'terminate']);
 			return back();
 		} else {
 			$f_nic = $isverify['nic_front'];
 			$nic = $isverify['cnic'];
 			$b_nic = $isverify['nic_back'];
 			$uname = $isverify['username'];
 			$ntn = $isverify['ntn'];
 			$passport = $isverify['intern_passport'];
 			$overseas = $isverify['overseas'];
 		}

 	}

 	if (($username == $uname) && ($nic != '' || $ntn != '' || $overseas != '' || $passport != '') && ($f_nic != '' && $b_nic != '')) {
 		$Checkingexpiry = UserStatusInfo::where(['username' => $username])->first();

 		if (!empty($Checkingexpiry)) {
 			$expire = $Checkingexpiry['card_expire_on'];
 			$cur_date = date('Y-m-d');
 		}


 		if ($expire > $cur_date) {
 			session()->flash('error', ' Your user Already charge.');
				// return redirect()->route('users.user.index1', ['status' => 'user']);
 			return back();
 		} else {

 			$getGroup = Profile::where(['name' => $name])->first();
				// dd($getGroup);
 			$profileGroupname = $getGroup->name;
				//  New Card Charge code start from here.......
 			$checkIsSubdealerUser = UserInfo::where('username', $username)->select('username', 'dealerid', 'sub_dealer_id')->first();
				// dd($checkIsSubdealerUser->dealerid);
 			$billingType = DealerProfileRate::where('dealerid', $checkIsSubdealerUser->dealerid)->select('billing_type')->first();

 			if ($checkIsSubdealerUser->sub_dealer_id != NULL && $billingType->billing_type == 'card') {


 				$currentDealer = UserInfo::where(['username' => $username])->first();

 				$rechargersubDealer = $currentDealer->sub_dealer_id;
 				$rechargertrader = $currentDealer->trader_id;
 				$rechargerDealer = $currentDealer->dealerid;

					// current logged in user
 				$currentUser = UserInfo::where(['sub_dealer_id' => $rechargersubDealer, 'status' => 'subdealer'])->first();

 				if (empty($currentUser)) {
 					$currentUser1 = UserInfo::where(['dealerid' => $rechargerDealer, 'status' => 'dealer'])->first();
 					if (empty($currentUser1)) {
 						$currentUser = UserInfo::where(['trader_id' => $rechargertrader, 'status' => 'trader'])->first();
 					} else {
 						$currentUser = UserInfo::where(['dealerid' => $rechargerDealer, 'status' => 'dealer'])->first();
 					}
 				} else {
 				}

 				$check = '';
 				$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
 				$check = @$freeze_account['freeze'];
 				if (!empty($freeze_account)) {
 					if ($check == 'yes') {
 						// return redirect()->route('users.dashboard');
 						session()->flash('error', 'Error ! your panel has been freezed');
 						return back();
 					}
					} //(!empty($freeze_account)
					else {
					} //(!empty($freeze_account)
					$card = Card::where('sub_dealer_id', $currentUser->sub_dealer_id)->where('status', 'unused')->where('name', $name)->first();

					if ($card != null) {

						$card_nos = $card->card_no;
						$status = $card->status;
						$m_rate = $card->m_rate;
						$rate = $card->rate;
						$dealerrate = $card->dealerrate;
						$subdealerrate = $card->subdealerrate;
						$s_acc_rate = $card->s_acc_rate;
						$sst = $card->sst;
						$adv_tax = $card->adv_tax;
						$traderrate = $card->traderrate;
						$t_acc_rate = $card->t_acc_rate;
						$commision = $card->commision;
						$m_acc_rate = $card->m_acc_rate;
						$r_acc_rate = $card->r_acc_rate;
						$d_acc_rate = $card->d_acc_rate;
						$c_sst = $card->c_sst;
						$c_adv = $card->c_adv;
						$c_charges = $card->c_charges;
						$c_rates = $card->c_rates;
						$profit = $card->profit;
						$profile = $card->profile;
						$name = $card->name;
						$taxname = $card->taxname;
						$charge_on = date('Y-m-d H:i:s');
						$trader_id = $card->trader_id;
						$dealerid = $card->dealerid;
						$resellerid = $card->resellerid;
						$sub_dealer_id = $card->sub_dealer_id;
						$manager_id = $card->manager_id;
						$date = date('Y-m-d');
						$billing_type = 'card';
						// $currentUser = '';
						// $currentDealer = '';
						// $profileRate = '';
						// will recharge the user
						$this->rechargeUser($request, $name, $username, $currentUser);
						$username = $username;
						$date = str_replace('-', '', date('Y-m-d'));
						$userid = $username . $date;

						if ($sst == 0) {
							$receipt = 0;
						} else {
							$receipt_last = AmountBillingInvoice::where('receipt_num', '!=', '0')->select('receipt_num')->orderBy('receipt_num', 'DESC')->limit(1)->first();
							$num = @$receipt_last->receipt_num;
							$receipt = $num ? $num : 1;
						}

						$receipt1 = $currentUser->receipt;
						$receipt_num = $receipt;
						$charge_on = date('Y-m-d H:i:s');
						$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid, 'groupname' => $profile])->first();
						// dd($dasti_amountFromDealerproRate);
						$dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];


						$multyply = $d_acc_rate * 2 + $sst + $adv_tax;
						$divide = $multyply / 2;
						if ($subdealerrate == 0) {
							$dividesub = 0;
						} else {
							$multyplysub = $s_acc_rate * 2 + $sst + $adv_tax;
							$dividesub = $multyplysub / 2;
						}
						$resellermultyply = $r_acc_rate * 2 + $sst + $adv_tax;
						$resellerdivide = $resellermultyply / 2;

						try {
							$insertAmountBilling = new AmountBillingInvoice();
							$insertAmountBilling->username = $username;
							$insertAmountBilling->userid = $userid;
							$insertAmountBilling->m_rate = $m_rate;
							$insertAmountBilling->rate = $resellerdivide;
							$insertAmountBilling->dealerrate = $divide;
							$insertAmountBilling->subdealerrate = $dividesub;
							$insertAmountBilling->s_acc_rate = $s_acc_rate;
							$insertAmountBilling->sst = $sst;
							$insertAmountBilling->adv_tax = $adv_tax;
							$insertAmountBilling->traderrate = $traderrate;
							$insertAmountBilling->t_acc_rate = $t_acc_rate;
							$insertAmountBilling->commision = $commision;
							$insertAmountBilling->m_acc_rate = $m_acc_rate;
							$insertAmountBilling->r_acc_rate = $r_acc_rate;
							$insertAmountBilling->d_acc_rate = $d_acc_rate;
							$insertAmountBilling->c_sst = $c_sst;
							$insertAmountBilling->c_adv = $c_adv;
							$insertAmountBilling->c_charges = $c_charges;
							$insertAmountBilling->c_rates = $c_rates;
							$insertAmountBilling->profit = $profit;
							$insertAmountBilling->receipt = 'logon';
							$insertAmountBilling->receipt_num = $receipt_num;
							$insertAmountBilling->profile = $profile;
							$insertAmountBilling->name = $name;
							$insertAmountBilling->taxname = $taxname;
							$insertAmountBilling->trader_id = $trader_id;
							$insertAmountBilling->dealerid = $dealerid;
							$insertAmountBilling->resellerid = $resellerid;
							$insertAmountBilling->manager_id = $manager_id;
							$insertAmountBilling->sub_dealer_id = $sub_dealer_id;
							$insertAmountBilling->date = $date;
							$insertAmountBilling->charge_on = $charge_on;
							$insertAmountBilling->dasti_amount = $dasti_amount;
							$insertAmountBilling->billing_type = 'card';
							$insertAmountBilling->card_no = $card_nos;
							$insertAmountBilling->save();
							//update Card Table while card used
							$card->status = 'used';
							$card->save();
							DB::commit();

						} catch (Exception $e) {
							DB::rollback();
							return $e->getMessage();
						}

					} //card check
					else {
						session()->flash('error', ' No profile available.');
						// return redirect()->route('users.single', $checkDealeralow->id);
						return back();
					} // check card
					if (Auth::user()->status == "dealer") {
						$activity = "view Account Recharged by" . Auth::user()->dealerid;
					} elseif (Auth::user()->status == "subdealer") {
						$activity = "view Account Recharged by" . Auth::user()->sub_dealer_id;
					} else {
						$activity = "view Account Recharged by" . Auth::user()->trader_id;
					}
					try {
						$amountUserRechargeLog = new AmountUserRechargeLog();
						$amountUserRechargeLog->dealerid = Auth::user()->dealerid;
						$amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
						$amountUserRechargeLog->trader_id = Auth::user()->trader_id;
						$amountUserRechargeLog->username = $username;
						$amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');
						$amountUserRechargeLog->activity = $activity;
						// $amountUserRechargeLog->error =
						$amountUserRechargeLog->ipaddress = $request->ip();
						$amountUserRechargeLog->profile = $profile;
						$amountUserRechargeLog->save();
						// amount before recharge
						$userAmountBillingBrg = new UserAmountBillingBrg();
						$userAmountBillingBrg->billing_invoice_id = 1;
						$userAmountBillingBrg->last_remaining_amount = NULL;
						$userAmountBillingBrg->save();
						DB::commit();
						session()->flash('success', ' Successfully Recharged.');
						return redirect()->route('users.user.index1', ['status' => 'user']);
					} catch (Exception $e) {
						DB::rollback();
						return $e->getMessage();
					}
				} //($checkIsSubdealerUser->sub_dealer_id != NULL && $billingType->billing_type == 'card')
				else {
					$currentDealer = UserInfo::where(['username' => $username])->first();
					// dd($currentDealer);
					$rechargersubDealer = $currentDealer->sub_dealer_id;
					$rechargertrader = $currentDealer->trader_id;
					$rechargerDealer = $currentDealer->dealerid;
					if (Auth::user()->status == 'subdealer') {
						// current logged in user
						$currentUser = UserInfo::where(['sub_dealer_id' => $rechargersubDealer, 'status' => 'subdealer'])->first();

					} elseif (Auth::user()->status == 'trader') {
						$currentUser = UserInfo::where(['trader_id' => $rechargertrader, 'status' => 'trader'])->first();

						// dd($currentUser,$rechargertrader);
					}
					// current logged in user


					if (empty($currentUser)) {
						$currentUser1 = UserInfo::where(['dealerid' => $rechargerDealer, 'status' => 'dealer'])->first();
						if (empty($currentUser1)) {
							$currentUser = UserInfo::where(['trader_id' => $rechargertrader, 'status' => 'trader'])->first();
						} else {
							$currentUser = UserInfo::where(['dealerid' => $rechargerDealer, 'status' => 'dealer'])->first();
						}
					}
					// dd($currentUser);
					$check = '';
					$freeze_account = FreezeAccount::where(['username' => $currentUser->username])->first();
					$check = @$freeze_account['freeze'];

					if (!empty($freeze_account)) {
						if ($check == 'yes') {
							// dd($freeze_account);
							// return redirect()->route('users.dashboard');
							session()->flash('error', 'Error ! your panel has been freezed');
							return back();
						}
					}

					$getsubdealerrate = '';
					$dealerprofileRate = '';
					$profile_rate = '';
					$profileRate = '';
					$currentUserResellerUserName = '';
					$customertax = Profile::where(['name' => $profileGroupname])->first();
					$name = $customertax->name;
					if ($currentUser->status == "dealer") {
						$dealerprofileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileGroupname])->first();
						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileGroupname])->first();
						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileGroupname])->first();
						$taxgroup = $dealerprofileRate['taxgroup'];
						$profileRate = $dealerprofileRate;
						$profile_rate = $dealerprofileRate->base_price_ET;
						if (empty($dealerprofileRate)) {
							return redirect()->route('users.dashboard');
						}
						$currentUserResellerUserName = UserInfo::select('username')->where(['dealerid' => Auth::user()->dealerid, 'status' => 'dealer'])->first();
						// dd($currentUserResellerUserName, 'dealer');
					} elseif ($currentUser->status == "subdealer") {
						// get Sub-Dealer Data

						$dealerprofileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileGroupname])->first();
						$getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id' => $currentUser->sub_dealer_id, 'name' => $profileGroupname, 'dealerid' => $rechargerDealer])->first();
						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileGroupname])->first();
						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileGroupname])->first();
						$taxgroup = $dealerprofileRate['taxgroup'];
						$profile_rate = $getsubdealerrate->rate;
						$profileRate = $getsubdealerrate;
						if (empty($getsubdealerrate)) {
							return redirect()->route('users.dashboard');
						}

						$currentUserResellerUserName = UserInfo::select('username')->where(['sub_dealer_id' => Auth::user()->sub_dealer_id, 'status' => 'subdealer'])->first();
						// dd($currentUserResellerUserName, 'subdealer',Auth::user()->sub_dealer_id);
					} elseif ($currentUser->status == "trader") {
						$dealerprofileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileGroupname])->first();
						$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileGroupname])->first();
						$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileGroupname])->first();
						$taxgroup = $dealerprofileRate['taxgroup'];

						$getsubdealerrate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id, 'name' => $profileGroupname])->first();
						$profileRate = $getsubdealerrate;
						if (empty($dealerprofileRate)) {
							return redirect()->route('users.dashboard');
						}
						$currentUserResellerUserName = UserInfo::select('username')->where(['trader_id' => Auth::user()->trader_id, 'status' => 'trader'])->first();
						// dd($currentUserResellerUserName, 'tradef', Auth::user()->trader_id);
					}
					// dd($currentUserResellerUserName,$username,$currentUser->status );
					// $currentUserResellerUserName = UserInfo::select('username')->where(['resellerid' => $currentUser->resellerid,'status' => 'reseller'])->first();
					$resellerUserAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
					$resellerAmount = $resellerUserAmount->amount;



					// // check user has sufficiant amount: user_amount
					$userAmount = UserAmount::where('username', $currentUser->username)->first();
					
					$amount = $userAmount->amount;

					//

					if ($amount >= $profileRate['rate']) {

						$user_data = UserInfo::where('username', $username)->first();	
						$get_user_info = $user_data;
						//
						if($currentUser->status == "dealer"){
							$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileGroupname])->first();
						}else if($currentUser->status == "subdealer"){
							$dasti_amountFromDealerproRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $profileGroupname])->first();
						}
						//
						$profit_margin_dealer = $get_user_info->profile_amount - $profile_rate;
						$base_price_user = $get_user_info->profile_amount;
						//
						if($get_user_info->profile_amount <= 0)
						{
							session()->flash('error', 'Please set profile amount first');
							// return redirect()->route('users.user.index1', ['status' => 'user']);
							return back();
						}
						//
						if ( ($get_user_info->company_rate == 'yes') && ($dasti_amountFromDealerproRate->base_price > $base_price_user) ) {
							session()->flash('error', 'Consumer profile rate must be greater than dealer Rate.');
							// return redirect()->route('users.user.index1', ['status' => 'user']);
							return back();
						}if ( ($get_user_info->company_rate == 'no')  && ($dasti_amountFromDealerproRate->base_price_ET > $base_price_user) ) {
							session()->flash('error', 'Consumer profile rate must be greater than dealer Rate.');
							// return redirect()->route('users.user.index1', ['status' => 'user']);
							return back();
						}
						//

					///////////////////////// check wallet deduction ///////////////////////
						//
						$get_taxt_data = Tax::first();
 						//
						$sst_rate_per = $dasti_amountFromDealerproRate->sstpercentage;
						$adv_rate_per = $dasti_amountFromDealerproRate->advpercentage;
						$taxRev_fll = $taxRev_cvas = $taxRev_tip = $get_user_info->profile_amount;
 						//
						if($get_user_info->company_rate == 'yes'){
 						//
							if($get_taxt_data->fll_sst == 'yes'){
								$taxRev_fll = $get_user_info->profile_amount / ($sst_rate_per+1);
							}if($get_taxt_data->cvas_sst == 'yes'){
								$taxRev_cvas = $get_user_info->profile_amount / ($sst_rate_per+1);
							}if($get_taxt_data->tip_sst == 'yes'){
								$taxRev_tip = $get_user_info->profile_amount / ($sst_rate_per+1);
							}
 							//
							if($get_taxt_data->fll_adv == 'yes'){
								$taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
							}if($get_taxt_data->cvas_adv == 'yes'){
								$taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
							}if($get_taxt_data->tip_adv == 'yes'){
								$taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
							}
 						//
						}
 						//
						$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
						$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
						$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
 						//
						$total_charges = $fll_charges + $cvas_charges + $tip_charges;
						$profit_margin_dealer = $get_user_info->profile_amount - $dasti_amountFromDealerproRate->base_price_ET;
						//
						$fll_sst = $cvas_sst = $tip_sst = 0;
						$fll_adv = $cvas_adv = $tip_adv = 0;
 						//
						if($get_taxt_data->fll_sst == 'yes'){
							$fll_sst = $fll_charges * $sst_rate_per;
						}if($get_taxt_data->cvas_sst == 'yes'){
							$cvas_sst = $cvas_charges * $sst_rate_per;
						}if($get_taxt_data->tip_sst == 'yes'){
							$tip_sst = $tip_charges * $sst_rate_per;
						}
 						//
						$sst_u = $fll_sst + $cvas_sst + $tip_sst;
 						//
						if($get_taxt_data->fll_adv == 'yes'){
							$fll_adv = ($fll_charges + $fll_sst) * $adv_rate_per;
						}if($get_taxt_data->cvas_adv == 'yes'){
							$cvas_adv = ($cvas_charges + $cvas_sst) * $adv_rate_per;
						}if($get_taxt_data->tip_adv == 'yes'){
							$tip_adv = ($tip_charges + $tip_sst) * $adv_rate_per;
						}
 						// 
						$adv_tax_u = $fll_adv + $cvas_adv + $tip_adv;
						//
						
						$filer_tax_rate = 0;
						$filer = 0;
						$check_dealerid = Auth::user()->dealerid;
						$get_dealer_info = UserInfo::where(['dealerid'=>$check_dealerid,'status'=>'dealer'])->first();

						$static_ip = $get_user_info->static_ip_amount;
						if ($base_price_user > $profile_rate) {
							//
							$check_filer = $get_dealer_info->is_filer;
							$check = $check_filer;
							if ($check === 'filer') {
								$filer = $get_taxt_data->filer_tax;

							} elseif($check === 'non filer'){
								$filer = $get_taxt_data->non_file_tax;
							}
							else{
								$filer = 0;
							}
						}
						$filer_tax_rate = $profit_margin_dealer * $filer;
						$wallet_deduction = $total_charges + $sst_u + $adv_tax_u + $filer_tax_rate; ///// + $get_user_info->static_ip_amount; static ip removed by irfan bhai
						if($get_user_info->company_rate == 'no'){
							$wallet_deduction = $wallet_deduction - $profit_margin_dealer;
 							// 
							$wallet_deduction = $wallet_deduction / 2 ;
						}
						//
						if($amount < $wallet_deduction)
						{
							session()->flash('error', ' You have insufficient amount.');
							// return redirect()->route('users.user.index1', ['status' => 'user']);
							return back();
						}
						///////////////////////// check wallet deduction END ///////////////////////

						$assignedNas = AssignedNas::where(["id" => $currentUser->dealerid])->first();
						$userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->first();


						if (empty($userUsualIp)) {
							session()->flash('error', ' Please Check Nas.');
							// return redirect()->route('users.user.index1', ['status' => 'user']);
							return back();
						}


						// will recharge the user
						// $this->rechargeUser($request, $profileGroupname, $username, $currentUser);

						// if ($taxgroup == "B") {
						// 	$customersst = $customertax->sstB;
						// 	$customeradv = $customertax->adv_taxB;
						// 	$customercharges = $customertax->chargesB;
						// 	$customerrates = $customertax->final_ratesB;
						// 	$taxname = "CX-V2";
						// } else if ($taxgroup == "C") {

						// 	$customersst = $customertax->sstC;
						// 	$customeradv = $customertax->adv_taxC;
						// 	$customercharges = $customertax->chargesC;
						// 	$customerrates = $customertax->final_ratesC;
						// 	$taxname = "CX-V3";
						// } else if ($taxgroup == "D") {
						// 	$customersst = $customertax->sstD;
						// 	$customeradv = $customertax->adv_taxD;
						// 	$customercharges = $customertax->chargesD;
						// 	$customerrates = $customertax->final_ratesD;
						// 	$taxname = "CX-V4";

						// } else if ($taxgroup == "E") {
						// 	$customersst = $customertax->sstE;
						// 	$customeradv = $customertax->adv_taxE;
						// 	$customercharges = $customertax->chargesE;
						// 	$customerrates = $customertax->final_ratesE;
						// 	$taxname = "CX-V5";

						// } else {
						// 	$customersst = $customertax->sst;
						// 	$customeradv = $customertax->adv_tax;
						// 	$customercharges = $customertax->charges;
						// 	$customerrates = $customertax->final_rates;
						// 	$taxname = "CX-V1";
						// }


						$customersst = 0;
						$customeradv = 0;
						$customercharges = 0;
						$customerrates = 0;
						$taxname = null;

						$username = $username;
						$date = str_replace('-', '', date('Y-m-d'));
						$userid = $username . $date;
						$m_rate = $m_profileRate['final_rates'];
						$rate = $resellerprofileRate['final_rates'];

						if ($currentUser->status == "subdealer" || $currentUser->status == "trader") {
							$dealerrate = $dealerprofileRate['final_rates'];
							$subdealerrate = $profileRate['final_rates'];
							$s_acc_rate = $profileRate->rate;
							$sst = $profileRate->sst;
							$adv_tax = $profileRate->adv_tax;
							$traderrate = 0;
							$t_acc_rate = 0;
							$c_sst = $sst;
							$c_adv = $adv_tax;
							$c_charges = $profileRate['consumer'];
							$c_rates = $customerrates;
						} elseif ($currentUser->status == "dealer") {
							$dealerrate = $dealerprofileRate['final_rates'];
							$subdealerrate = 0;
							$s_acc_rate = 0;
							$traderrate = 0;
							$t_acc_rate = 0;
							$sst = $dealerprofileRate->sst;
							$adv_tax = $dealerprofileRate->adv_tax;
							$c_sst = $sst;
							$c_adv = $adv_tax;
							$c_charges = $dealerprofileRate['consumer'];
							$c_rates = $customerrates;
						} else {
							$dealerrate = $dealerprofileRate->rate;
							$subdealerrate = $s_profileRate['final_rates'];
							$traderrate = $profileRate['final_rates'];
							$s_acc_rate = $s_profileRate->rate;
							$t_acc_rate = $profileRate->rate;
							$sst = $profileRate->sst;
							$adv_tax = $profileRate->adv_tax;
							$c_sst = $sst;
							$c_adv = $adv_tax;
							$c_charges = $s_profileRate['consumer'];
							$c_rates = $customerrates;

						}
						if ($dealerprofileRate->sst == 0) {
							$receipt = 0;
						} else {
							$receipt_last = AmountBillingInvoice::where('receipt_num', '!=', '0')->select('receipt_num')->orderBy('receipt_num', 'DESC')->limit(1)->first();
							$num = @$receipt_last->receipt_num;
							$receipt = $num ? $num : 1;
						}
						$d_r = $dealerprofileRate->rate;
						$r_r = $resellerprofileRate->rate;
						$c_a = $dealerprofileRate->commision;
						$p_r = $d_r - $r_r;
						$profit = $p_r - $c_a;

						$commision = $dealerprofileRate->commision;
						$m_acc_rate = $m_profileRate->rate;
						$r_acc_rate = $resellerprofileRate->rate;
						$d_acc_rate = $dealerprofileRate->rate;

						$profit = $profit;
						$receipt1 = $currentUser->receipt;
						$receipt_num = $receipt;
						$profile = Profile::where('name', $profileGroupname)->first();
						$profile = $profile->groupname;
						$name = $name;
						$taxname = $taxname;
						$charge_on = date('Y-m-d H:i:s');
						$user_data = UserInfo::where('username', $username)->first();

						$trader_id = $user_data->trader_id;
						$sub_dealer_id = $user_data->sub_dealer_id;
						$dealerid = $user_data->dealerid;
						$resellerid = $user_data->resellerid;
						$manager_id = $user_data->manager_id;
						$date = date('Y-m-d');
						//
						/////////////////////////////////////////////////////////////
						////////////////  DEALER GROSS AMOUNT   /////////////////////
						$dealerRate = DealerProfileRate::where(['dealerid' => $dealerid, 'name' => $profileGroupname])->first();
						//
						$sst_rate_per = $dealerRate->sstpercentage;
						$adv_rate_per = $dealerRate->advpercentage;
						//
						$taxRev_fll = $taxRev_cvas = $taxRev_tip = $dealerRate->base_price;
						if($get_taxt_data->fll_sst == 'yes'){
							$taxRev_fll = $dealerRate->base_price / ($sst_rate_per+1);
						}if($get_taxt_data->cvas_sst == 'yes'){
							$taxRev_cvas = $dealerRate->base_price / ($sst_rate_per+1);
						}if($get_taxt_data->tip_sst == 'yes'){
							$taxRev_tip = $dealerRate->base_price / ($sst_rate_per+1);
						}
						//
						if($get_taxt_data->fll_adv == 'yes'){
							$taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
						}if($get_taxt_data->cvas_adv == 'yes'){
							$taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
						}if($get_taxt_data->tip_adv == 'yes'){
							$taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
						}
						//
						$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
						$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
						$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
 	//
						$dealer_gross_amount = $fll_charges + $cvas_charges + $tip_charges;
						////////////////////////////////////
						//////////////////////////////////////////
						$subdealer_ppm = 0;
						//
						if($currentUser->status == "dealer"){
							$dasti_amountFromDealerproRate = DealerProfileRate::where(['dealerid' => $dealerid, 'name' => $profileGroupname])->first();
							//
							$inv_dealerrate = $dasti_amountFromDealerproRate->base_price;
							$inv_subdealerrate = 0;
							//
							$d_acc_rate = $dasti_amountFromDealerproRate->base_price_ET;
							$s_acc_rate = 0;
							//
							$d_commission = $dasti_amountFromDealerproRate->commision;
							$r_commission = $resellerprofileRate->commission;
							$ct_margin = 0;
							$ppm = $dasti_amountFromDealerproRate->partner_profit_margin;
							//
						}else if($currentUser->status == "subdealer"){
							//
							$Dealer_pro_Rate = DealerProfileRate::where(['dealerid' => $dealerid, 'name' => $profileGroupname])->first();
							//
							$dasti_amountFromDealerproRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $profileGroupname])->first();
							//
							$inv_dealerrate = $Dealer_pro_Rate->base_price;
							$inv_subdealerrate = $dasti_amountFromDealerproRate->base_price;
							//
							$d_acc_rate = $Dealer_pro_Rate->base_price_ET;
							$s_acc_rate = $dasti_amountFromDealerproRate->base_price_ET;
							//
							$d_commission = $Dealer_pro_Rate->commision;
							$r_commission = $resellerprofileRate->commission;
							$ct_margin = $dasti_amountFromDealerproRate->margin;
							$subdealer_ppm = $dasti_amountFromDealerproRate->partner_profit_margin;
						}


						$sst_rate_per = $dasti_amountFromDealerproRate->sstpercentage;
						$adv_rate_per = $dasti_amountFromDealerproRate->advpercentage;
						$taxRev_fll = $taxRev_cvas = $taxRev_tip = $get_user_info->profile_amount;
 	//
						if($get_user_info->company_rate == 'yes'){
 	//
							if($get_taxt_data->fll_sst == 'yes'){
								$taxRev_fll = $get_user_info->profile_amount / ($sst_rate_per+1);
							}if($get_taxt_data->cvas_sst == 'yes'){
								$taxRev_cvas = $get_user_info->profile_amount / ($sst_rate_per+1);
							}if($get_taxt_data->tip_sst == 'yes'){
								$taxRev_tip = $get_user_info->profile_amount / ($sst_rate_per+1);
							}
 	//
							if($get_taxt_data->fll_adv == 'yes'){
								$taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
							}if($get_taxt_data->cvas_adv == 'yes'){
								$taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
							}if($get_taxt_data->tip_adv == 'yes'){
								$taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
							}
 	//
						}
 	//
						$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
						$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
						$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
 	//
						$total_charges = $fll_charges + $cvas_charges + $tip_charges;
						$profit_margin_dealer = $get_user_info->profile_amount - $dasti_amountFromDealerproRate->base_price_ET;
	//
						$fll_sst = $cvas_sst = $tip_sst = 0;
						$fll_adv = $cvas_adv = $tip_adv = 0;
 	//
						if($get_taxt_data->fll_sst == 'yes'){
							$fll_sst = $fll_charges * $sst_rate_per;
						}if($get_taxt_data->cvas_sst == 'yes'){
							$cvas_sst = $cvas_charges * $sst_rate_per;
						}if($get_taxt_data->tip_sst == 'yes'){
							$tip_sst = $tip_charges * $sst_rate_per;
						}
 	//
						$sst_u = $fll_sst + $cvas_sst + $tip_sst;
 	//
						if($get_taxt_data->fll_adv == 'yes'){
							$fll_adv = ($fll_charges + $fll_sst) * $adv_rate_per;
						}if($get_taxt_data->cvas_adv == 'yes'){
							$cvas_adv = ($cvas_charges + $cvas_sst) * $adv_rate_per;
						}if($get_taxt_data->tip_adv == 'yes'){
							$tip_adv = ($tip_charges + $tip_sst) * $adv_rate_per;
						}
 	// 
						$adv_tax_u = $fll_adv + $cvas_adv + $tip_adv;

						//
						$dasti_amount = $dasti_amountFromDealerproRate['dasti_amount'];

						$filer_tax_rate = 0;
						$filer = 0;
						$check_dealerid = Auth::user()->dealerid;
						$get_dealer_info = UserInfo::where(['dealerid'=>$check_dealerid,'status'=>'dealer'])->first();

						$static_ip = $get_user_info->static_ip_amount;
						if ($base_price_user > $profile_rate) {
							//
							$check_filer = $get_dealer_info->is_filer;
							$check = $check_filer;
							if ($check === 'filer') {
								$filer = $get_taxt_data->filer_tax;

							} elseif($check === 'non filer'){
								$filer = $get_taxt_data->non_file_tax;
							}
							else{
								$filer = 0;
							}
						}
						$filer_tax_rate = $profit_margin_dealer * $filer;
						$wallet_deduction = $total_charges + $sst_u + $adv_tax_u + $filer_tax_rate; /// + $get_user_info->static_ip_amount; removed by irfan bhai
						if($get_user_info->company_rate == 'no'){
							$wallet_deduction = $wallet_deduction - $profit_margin_dealer;
 							// 
							$wallet_deduction = $wallet_deduction / 2 ;
						}


						// 
						$multyply = $d_acc_rate * 2 + $sst + $adv_tax;
						$divide = $multyply / 2;
						if ($subdealerrate == 0) {
							$dividesub = 0;
						} else {
							$multyplysub = $s_acc_rate * 2 + $sst + $adv_tax;
							$dividesub = $multyplysub / 2;
						}
						$resellermultyply = $r_acc_rate * 2 + $sst + $adv_tax;
						$resellerdivide = $resellermultyply / 2;


						try {
							//
							////////// WALLET DEDUCTION ////////////////
							$userAmount = UserAmount::where('username', $currentUserResellerUserName->username)->first();
							$userAmount->amount -= $wallet_deduction;
							$userAmount->save();
							///////////////////////////////////////////
							//
							$this->rechargeUser($request, $profileGroupname, $username, $currentUser);
							//
							$insertAmountBilling = new AmountBillingInvoice();
							$insertAmountBilling->username = $username;
							$insertAmountBilling->userid = $userid;
							$insertAmountBilling->m_rate = $m_acc_rate;
							$insertAmountBilling->r_rate = $r_acc_rate;
							$insertAmountBilling->rate = $d_acc_rate;
							$insertAmountBilling->dealerrate = $inv_dealerrate;
							$insertAmountBilling->subdealerrate = $inv_subdealerrate;
							$insertAmountBilling->s_acc_rate = $s_acc_rate;
							$insertAmountBilling->adv_percentage = $adv_rate_per;
							$insertAmountBilling->sst = $sst_u;
							$insertAmountBilling->adv_tax = $adv_tax_u;
							$insertAmountBilling->sst_percentage = $sst_rate_per;
							$insertAmountBilling->traderrate = $traderrate;
							$insertAmountBilling->t_acc_rate = $t_acc_rate;
							$insertAmountBilling->commision = $d_commission;
							$insertAmountBilling->r_commission = $r_commission;
							$insertAmountBilling->margin = $ct_margin;
							$insertAmountBilling->m_acc_rate = $m_acc_rate;
							$insertAmountBilling->r_acc_rate = $r_acc_rate;
							$insertAmountBilling->d_acc_rate = $d_acc_rate;
							$insertAmountBilling->c_sst = $c_sst;
							$insertAmountBilling->c_adv = $c_adv;
							$insertAmountBilling->c_charges = $c_charges;
							$insertAmountBilling->c_rates = $c_rates;
							$insertAmountBilling->profit = $profit;
							$insertAmountBilling->receipt = 'logon';
							$insertAmountBilling->receipt_num = $receipt_num;
							$insertAmountBilling->profile = $profile;
							$insertAmountBilling->name = $name;
							$insertAmountBilling->taxname = $taxname;
							$insertAmountBilling->trader_id = $trader_id;
							$insertAmountBilling->dealerid = $dealerid;
							$insertAmountBilling->resellerid = $resellerid;
							$insertAmountBilling->manager_id = $manager_id;
							$insertAmountBilling->sub_dealer_id = $sub_dealer_id;
							$insertAmountBilling->date = $date;
							$insertAmountBilling->charge_on = $charge_on;
							$insertAmountBilling->dasti_amount = $dasti_amount;
							$insertAmountBilling->billing_type = 'amount';
							$insertAmountBilling->card_no = NULL;
							$insertAmountBilling->company_rate = $user_data->company_rate;
							$insertAmountBilling->wallet_amount = $amount;
							//
							$insertAmountBilling->filer_tax = $filer;
							$insertAmountBilling->cvas_tax = $get_taxt_data->cvas_tax;
							$insertAmountBilling->tip_tax = $get_taxt_data->tip_tax;
							$insertAmountBilling->fll_tax = $get_taxt_data->fll_tax;
							$insertAmountBilling->static_ip_amount = $get_user_info->static_ip_amount;
							//
							$insertAmountBilling->fll_charges = $fll_charges;
							$insertAmountBilling->cvas_charges  = $cvas_charges;
							$insertAmountBilling->tip_charges = $tip_charges;
							$insertAmountBilling->wallet_deduction = $wallet_deduction;
							$insertAmountBilling->dealer_gross_amount = $dealer_gross_amount;
							//
							// $insertAmountBilling->ppm_reseller = $resellerprofileRate->partner_profit_margin;
							// $insertAmountBilling->ppm_dealer = $dealerprofileRate->partner_profit_margin;
							// $insertAmountBilling->ppm_subdealer = $subdealer_ppm;
							$insertAmountBilling->save();
							/////////////////////////
							// in last
							

							if (Auth::user()->status == "dealer") {
								$activity = "view Account Recharged by" . Auth::user()->dealerid;
							} elseif (Auth::user()->status == "subdealer") {
								$activity = "view Account Recharged by" . Auth::user()->sub_dealer_id;
							} else {
								$activity = "view Account Recharged by" . Auth::user()->trader_id;
							}


							//Recharge log
							$amountUserRechargeLog = new AmountUserRechargeLog();
							$amountUserRechargeLog->dealerid = Auth::user()->dealerid;
							$amountUserRechargeLog->sub_dealer_id = Auth::user()->sub_dealer_id;
							$amountUserRechargeLog->trader_id = Auth::user()->trader_id;
							$amountUserRechargeLog->username = $username;
							$amountUserRechargeLog->timestamp = date('Y-m-d H:i:s');

							$amountUserRechargeLog->activity = $activity;
							// $amountUserRechargeLog->error =
							$amountUserRechargeLog->ipaddress = $request->ip();
							$profile = Profile::where('name', $profileGroupname)->first();
							$amountUserRechargeLog->profile = $profile->groupname;
							$amountUserRechargeLog->save();

							// amount before recharge
							$userAmountBillingBrg = new UserAmountBillingBrg();
							$userAmountBillingBrg->billing_invoice_id = 1;
							$userAmountBillingBrg->last_remaining_amount = $amount;
							$userAmountBillingBrg->save();



							session()->flash('success', ' Successfully Rechargedddd.');
							return redirect()->route('users.user.index1', ['status' => 'expire']);

						} catch (Exception $e) {
							DB::rollback();
							return $e->getMessage();
						}

					} //final rates
					else {
						// 
						session()->flash('error', ' You have insufficient amount.');
						// return redirect()->route('users.user.index1', ['status' => 'user']);
						return back();
					}


				} //



			} //


		} //
		else {

			session()->flash('error', 'Consumer verification error.');
			return back();
		} //



	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////


	// recharge method_exist
	public function rechargeUser(Request $request,$profileGroupName,$username,$currentUser){
		// calulation for exepiry date[currentDay-1 of next month]
		$days=date('t');
		$days=$days-1;
		// $exp_date=date('Y-m-d', strtotime("+".$days." days"));
		$cur_date=date('Y-m-d');
		///


		$cur_date=date('Y-m-d');


		// $expiry='2019-07-03';


// $charge = strtotime('11:58:59');
		$charge = strtotime(date('H:i:s'));


		if($charge > strtotime('11:59:59') ){
	// echo 'From Current Date <br>';
			$time=strtotime($cur_date);
			$exp_date = date("Y-m-d", strtotime("+1 month", $time));
	// echo 'Next Expiry is '.$final;

		}else{
	// echo 'From Previous Date <br>';
			$time=strtotime($cur_date);
			$final = strtotime(date("Y-m-d", strtotime("+1 month", $time)));
			$exp_date = date("Y-m-d", strtotime("-1 day", $final));
	// echo 'Next Expiry is '.$final;

		}


		/*** User Usual IP: get ip where status is 0: un assigned IP(s)  ****/
		
		// radreply: check enter or not
		$radReply = Radreply::where('username',$username)->first();
		$userIpStatus = UserIPStatus::where('username',$username)->first();
		$assignedNas = AssignedNas::where(["id" => $currentUser->dealerid])->first();
		$userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->first();
		if($radReply){
			// dont need to change any thing
			
			// user_ip_status
			$userIpStatus = UserIPStatus::where('username',$username)->first();
			// ip will not null in user_ip_status
			if(!$userIpStatus){
				$userIpStatus = new UserIPStatus();
				$userIpStatus->username = $username;
				$userIpStatus->type = 'usual_ip';
			}
			
		}else{
			// user_ip_status
			
			// if user has not entery in user ip status
			if(!$userIpStatus){
				$userIpStatus = new UserIPStatus();
				$userIpStatus->username = $username;
			}
			
			// ip will be null: take new ip from UserUsualIP: update userUsualIP and new entry of user in rad reply with new ip
			 // getting unasigned ip
			$userUsualIp->status = 1; // lock the IP: assigned
			
			$ip = $userUsualIp->ip;
			
			//saving/updating userIpStatus with new IP
			$userIpStatus->ip = $ip;
			$userIpStatus->type = 'usual_ip';
			// $userIpStatus->save();
			//
			
			// new entry in radreply
			$radReply = new Radreply();
			$radReply->username = $username;
			$radReply->attribute = 'Framed-IP-Address';
			$radReply->op = '=';
			$radReply->value = $ip;
			if($currentUser->status == 'dealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->manager_id = $currentUser->manager_id;
			}elseif($currentUser->status == 'subdealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
				$radReply->manager_id = $currentUser->manager_id;
			}else{
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
				$radReply->trader_id = $currentUser->trader_id;
				$radReply->manager_id = $currentUser->manager_id;
			}
			//
		}

		
		
		/********/
		
		/*** updating data in RaduserGroup  ****/
		
			// if($currentUser->status == 'dealer'){
			// 	$serverType = AssignNasType::where('dealerid',$currentUser->dealerid)->first();
			// }elseif($currentUser->status == 'subdealer'){
			// 	$serverType = AssignNasType::where('sub_dealer_id',$currentUser->sub_dealer_id)->first();				
			// }else{
			// 	$serverType = AssignNasType::where('trader_id',$currentUser->trader_id)->first();				
			// }

			// switch($serverType->nas_type){
			// 	case "juniper": $groupname = 'BE'; break;
			// 	case "mikrotik": $groupname = 'MT'; break;
			// 	default : {
			// 			$groupname = 'BE';
			// 		}
			// }

		$changeprofile ='';
		$userdata = Profile::where('name',$profileGroupName)->first();
		$name = $userdata['name'];
		$code = $userdata['code'];
		$profileGroupName = $userdata['groupname'];
		$userdata->save();

			/// if you want to enable fup then just uncomment this line
			// $userdata1 = UserInfo::where('username',$username)->first();
			// $changeprofile = $userdata1['taxprofile'];
			// $fupdata = DealerFUP::where('dealerid',$currentUser->dealerid)->where('groupname',$profileGroupName)->first();
			// $fup = $fupdata['datalimit'];
			// $userdata1->qt_total = $fup;
		$code = $code != 'cyber' ? '-'.$code : '';
			// $profileGroupName = $groupname.'-'.$profileGroupName.'k'.$code; // updated below



			///// talha code /////////
		$domainInfo = Domain::where('resellerid',$currentUser->resellerid)->first();
		$code = $domainInfo['package_name'];
			// $profileGroupName = $code.'-'.($profileGroupName/1024).'mb';
		$profileGroupName = $profileGroupName;

			// $profileGroupName = $groupname.'-'.$profileGroupName.'k';

		$raduserGroup = RaduserGroup::where('username' , $username)->first();
		$raduserGroup->groupname = $profileGroupName;
		$raduserGroup->name = $name;
		/******/
		
		//updating status info
		$userStatusInfo =  UserStatusInfo::where('username' , $username)->first();
		$userStatusInfo->card_charge_on = $cur_date;
		$userStatusInfo->card_expire_on = $exp_date;
		$userStatusInfo->expire_datetime = $exp_date.' 12:00:00';
		$userStatusInfo->card_charge_by = $currentUser->username; // current login user
		$userStatusInfo->card_charge_by_ip = $request->ip();

		//updateting expired user
		$expire_user = ExpireUser::where('username' , $username)->first();
		$expire_user->status = "charge";
		$expire_user->last_update = date('Y-m-d H:i:s');
		DB::transaction(function() use ($userIpStatus, $radReply,$raduserGroup,$userUsualIp,$userStatusInfo,$expire_user) {
			$radReply->save();
			$userIpStatus->save();
			$raduserGroup->save();
			$userUsualIp->save();
			$userStatusInfo->save();
			$expire_user->save();
    /*
     * insert new record for question category
     */
});
		// IF CIR Profile Assign Do This....... 
		$cirUser = Cirprofile::where('username',$username)->first();
		if(!empty($cirUser) && $cirUser->status == 1){
			 //update profile radius
			$updateProfile = RaduserGroup::where('username',$username);
			$updateProfile->update([
				'groupname' => $profileGroupName.'-p',
				'name' => 'pure-'.$name
			]);
		}
		////
		$url='https://api-radius.logon.com.pk/kick/user-dc-api.php?username='.$username;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "$url"); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
	}
	/// ajax calls
	public function profileWiseUsers (Request $request){
		//
		$profile = $request->get('profileGroupName');
		$profileGroupName = Profile::where('name',$profile)->first()->groupname;
		//
		$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
		$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
		$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
		$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
		$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
   		 //
		$whereArray = array();
    	//
		if(!empty($manager_id)){
			array_push($whereArray,array('user_info.manager_id' , $manager_id));
		}if(!empty($resellerid)){
			array_push($whereArray,array('user_info.resellerid' , $resellerid));
		}if(!empty($dealerid)){
			array_push($whereArray,array('user_info.dealerid' , $dealerid));
		}if(!empty($sub_dealer_id)){
			array_push($whereArray,array('user_info.sub_dealer_id' , $sub_dealer_id));    
		}
		//
		if(Auth::user()->status == 'dealer'){
			array_push($whereArray,array('user_info.sub_dealer_id' , NULL));	
		}
		//
		$expired_users  = DB::table('user_info')
		->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
		// ->where('user_status_info.card_expire_on', '>', '2000-01-01')
		->where('user_status_info.card_expire_on', '<', date('Y-m-d'))
		->where($whereArray)
		->where('user_info.status','user')
		->where('user_info.name',$profile)
		->select('user_info.username')
		->get();
		//
		return $expired_users;
		
	}
	/////////////////
	/////////////////
	/////////////////



	public function recharge_it(request $request){
        //
		$successUser = null;
		$totalWalletDed = 0;
		$forConfirmation = null;
		$username = $request->get('username');
        // $dataList = $request->get('username');
		$level = $request->get('level');
        //
		if(empty(Auth::user()->username) ){
			return abort(403, 'Error : Session Expired.');   
		}if(empty($username)){
			return abort(403, 'Error : Kindly selected consumer first.');    
		}
        //
		$panelUsername = Auth::user()->username;
		$panelStatus = Auth::user()->status;
        //
		$freezeAccount = 'no';
		$freeze = FreezeAccount::where('username',$panelUsername)->first();
		if($freeze){
			$freezeAccount = $freeze->freeze;
		}if($freezeAccount == 'yes'){
			return abort(403, 'Error : Your account has been freezed.');  
		}
        //
		$total_wallet_deduction = 0;

		//////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////  CHECKING  ///////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////

		$userInfo = UserInfo::where('username',$username)->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->first();
            //
		if(empty($userInfo)){
			$error_msg = 'Error : Invalid consumer '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);
			
		}
            //
		$errorPkg = array('NEW','DISABLED',NULL);
		if(in_array($userInfo->name,$errorPkg)){
			$error_msg = 'Error : Invalid Profile of consumer '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);
			
		}
            //
		if($userInfo->profile_amount <= 0){
			$error_msg = 'Error : Kindly set profile amount first of consumer '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);
			
		}
            //
		$userStatusInfo = UserStatusInfo::where('username',$username)->first();
		if(strtotime($userStatusInfo->card_expire_on) > strtotime(date('Y-m-d'))){
			$error_msg = 'Error : Consumer already charged '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);

		}
        //
		if($panelStatus == "dealer"){
			$profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
		}else if($panelStatus == "subdealer"){
			$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
		}
		   //
		$isverify = UserVerification::where('username', $username)->first();
		if(empty($isverify) && $profileRate->verify == 'yes'){
			$error_msg = 'Error : Kindly verify first '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);

		}
         //
		if ( ($userInfo->company_rate == 'yes') && ($profileRate->base_price > $userInfo->profile_amount) ) {
			$error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);
			

		}if ( ($userInfo->company_rate == 'no')  && ($profileRate->base_price_ET > $userInfo->profile_amount) ) {
			$error_msg = 'Error : Consumer profile rate must be greater than dealer rate of consumer '.$username;
			$this->create_error_log($username,$error_msg);
			return abort(403, $error_msg);
			
		}
            //
		$walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);
		$total_wallet_deduction += $walletDedArray['wallet_deduction'];
            //


		$userAmount = UserAmount::where('username', $panelUsername)->first();
		if($userAmount->amount < $total_wallet_deduction){
			return abort(403, 'Error : Sorry you do not have enough amount in your wallet.');  
		}
        /////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////// RECHARGING /////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////////
        //
		$userInfo = UserInfo::where('username',$username)->first();
            //
		if($panelStatus == "dealer"){
			$profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
		}else if($panelStatus == "subdealer"){
			$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
		}
            //
		$walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);
		$wallet_deduction = $walletDedArray['wallet_deduction'];
        //
		if($wallet_deduction <= 0){
			return abort(403, 'Error : Something went wrong. Kindly check consumer '.$username);  
		}
		//
		$userAmount = UserAmount::where('username', $panelUsername)->first();
		if($userAmount->amount < $wallet_deduction){
			return abort(403, 'Error : Sorry you do not have enough amount remaining in your wallet.'); 
			
		}
        //
		$assignedNas = AssignedNas::where(["id" => Auth::user()->dealerid])->where('nas','!=',NULL)->first();
		if(empty($assignedNas)){
			return abort(403, 'Error : No NAS assigned.'); 

		}
		$userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->first();
        // 
		if(empty($userUsualIp)){
			return abort(403, 'Error : Sorry no CGN IP available.'); 

		}
        //
		$checkIPinRadreply = Radreply::where('value',$userUsualIp->ip)->count();
		if($checkIPinRadreply > 0){
			$message = "IP already in use ".$userUsualIp->ip;
			return abort(403, $message); 
			
		}
        //
        //
		$this->lets_recharge_it_now($request,$userInfo->name,$username,$wallet_deduction);
		
        ////////////
		
		return 'Recharged Successfully';
		
        ////////////

	}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function check_deduction_amount($profileRate,$userInfo){
      //
		$get_taxt_data = Tax::first();
      //
		$sst_rate_per = $profileRate->sstpercentage;
		$adv_rate_per = $profileRate->advpercentage;
		$taxRev_fll = $taxRev_cvas = $taxRev_tip = $userInfo->profile_amount;
        //
		if($userInfo->company_rate == 'yes'){
         //
			if($get_taxt_data->fll_sst == 'yes'){
				$taxRev_fll = $userInfo->profile_amount / ($sst_rate_per+1);
			}if($get_taxt_data->cvas_sst == 'yes'){
				$taxRev_cvas = $userInfo->profile_amount / ($sst_rate_per+1);
			}if($get_taxt_data->tip_sst == 'yes'){
				$taxRev_tip = $userInfo->profile_amount / ($sst_rate_per+1);
			}
        //
			if($get_taxt_data->fll_adv == 'yes'){
				$taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
			}if($get_taxt_data->cvas_adv == 'yes'){
				$taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
			}if($get_taxt_data->tip_adv == 'yes'){
				$taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
			}
		}
        //
		$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
		$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
		$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
                        //
		$total_charges = $fll_charges + $cvas_charges + $tip_charges;
		$profit_margin_dealer = $userInfo->profile_amount - $profileRate->base_price_ET;
                        //
		$fll_sst = $cvas_sst = $tip_sst = 0;
		$fll_adv = $cvas_adv = $tip_adv = 0;
                        //
		if($get_taxt_data->fll_sst == 'yes'){
			$fll_sst = $fll_charges * $sst_rate_per;
		}if($get_taxt_data->cvas_sst == 'yes'){
			$cvas_sst = $cvas_charges * $sst_rate_per;
		}if($get_taxt_data->tip_sst == 'yes'){
			$tip_sst = $tip_charges * $sst_rate_per;
		}
                        //
		$sst_u = $fll_sst + $cvas_sst + $tip_sst;
                        //
		if($get_taxt_data->fll_adv == 'yes'){
			$fll_adv = ($fll_charges + $fll_sst) * $adv_rate_per;
		}if($get_taxt_data->cvas_adv == 'yes'){
			$cvas_adv = ($cvas_charges + $cvas_sst) * $adv_rate_per;
		}if($get_taxt_data->tip_adv == 'yes'){
			$tip_adv = ($tip_charges + $tip_sst) * $adv_rate_per;
		}
    // 
		$adv_tax_u = $fll_adv + $cvas_adv + $tip_adv;
    //
		$filer_tax_rate = 0;
		$filer = 0;
		$check_dealerid = Auth::user()->dealerid;
		$get_dealer_info = UserInfo::where(['dealerid'=>$check_dealerid,'status'=>'dealer'])->first();
    //
		if ( $userInfo->profile_amount > $profileRate->base_price_ET ) {
                            //
			$check_filer = $get_dealer_info->is_filer;
			$check = $check_filer;
			if ($check === 'filer') {
				$filer = $get_taxt_data->filer_tax;

			} elseif($check === 'non filer'){
				$filer = $get_taxt_data->non_file_tax;
			}
			else{
				$filer = 0;
			}
		}
		$filer_tax_rate = $profit_margin_dealer * $filer;
		$wallet_deduction = $total_charges + $sst_u + $adv_tax_u + $filer_tax_rate; 
		if($userInfo->company_rate != 'yes'){
			$wallet_deduction = $wallet_deduction - $profit_margin_dealer;
			$wallet_deduction = $wallet_deduction / 2 ;
		}
    //
		$returnArray = array(
			'wallet_deduction' => $wallet_deduction,
			'get_taxt_data' => $get_taxt_data,
			'sst_u' => $sst_u,
			'adv_tax_u' => $adv_tax_u,
			'filer' => $filer,
			'fll_charges' => $fll_charges,
			'cvas_charges' => $cvas_charges,
			'tip_charges' => $tip_charges,
		);
		return $returnArray;
    //
	}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	/////////////////////////
	public function create_error_log($consumer,$msg){
    //
		$username = NULL;
		if (Auth::user()) {
			$username = Auth::user()->username;
		}

		$data = array(
			'username' => $username, 
			'message' => $msg, 
			'route_name' => Route::currentRouteName(),
			'route_action' => Route::currentRouteAction(),
			'trace' => null,       
			'consumer' => $consumer,       
		);

		Error::create($data);
    //
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	public function lets_recharge_it_now(Request $request,$profileName,$username,$wallet_deduction){
    ///
		$currentUser = Auth::user();
    ////////// WALLET DEDUCTION ////////////////
		$userAmount = UserAmount::where('username', $currentUser->username)->first();
		$wallet_bef_charge = $userAmount->amount;
		$userAmount->amount -= $wallet_deduction;
		$userAmount->save();
    //
		$cur_date=date('Y-m-d');
    //
		$charge = strtotime(date('H:i:s'));
        //
		if($charge > strtotime('11:59:59') ){
    // 
			$time=strtotime($cur_date);
			$exp_date = date("Y-m-d", strtotime("+1 month", $time));
    //
		}else{
    // 
			$time=strtotime($cur_date);
			$final = strtotime(date("Y-m-d", strtotime("+1 month", $time)));
			$exp_date = date("Y-m-d", strtotime("-1 day", $final));
    //
		}
		/*** User Usual IP: get ip where status is 0: un assigned IP(s)  ****/
		$radReply = Radreply::where('username',$username)->first();
		$userIpStatus = UserIPStatus::where('username',$username)->first();
		$assignedNas = AssignedNas::where(["id" => $currentUser->dealerid])->first();
		$userUsualIp = UserUsualIP::where(['status' => '0', 'nas' => $assignedNas->nas])->lockForUpdate()->first();
		if($radReply){
            // 
			$userIpStatus = UserIPStatus::where('username',$username)->first();
            // ip will not null in user_ip_status
			if(!$userIpStatus){
				$userIpStatus = new UserIPStatus();
				$userIpStatus->username = $username;
				$userIpStatus->type = 'usual_ip';
			}

		}else{
        // 
			if(!$userIpStatus){
				$userIpStatus = new UserIPStatus();
				$userIpStatus->username = $username;
			}
        //
        // lock the IP: assigned
			$userUsualIp->status = 1; 
        //
			$ip = $userUsualIp->ip;
        //
			$userIpStatus->ip = $ip;
			$userIpStatus->type = 'usual_ip';
        //
        // new entry in radreply
			$radReply = new Radreply();
			$radReply->username = $username;
			$radReply->attribute = 'Framed-IP-Address';
			$radReply->op = '=';
			$radReply->value = $ip;
			if($currentUser->status == 'dealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->manager_id = $currentUser->manager_id;
			}elseif($currentUser->status == 'subdealer'){
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
				$radReply->manager_id = $currentUser->manager_id;
			}else{
				$radReply->dealerid = $currentUser->dealerid;
				$radReply->resellerid = $currentUser->resellerid;
				$radReply->sub_dealer_id = $currentUser->sub_dealer_id;
				$radReply->trader_id = $currentUser->trader_id;
				$radReply->manager_id = $currentUser->manager_id;
			}
            //
		}
        //
		$changeprofile ='';
		$userdata = Profile::where('name',$profileName)->first();
		$name = $userdata['name'];
		$code = $userdata['code'];
		$profileGroupName = $userdata['groupname'];
		$userdata->save();
    //
		$profileGroupName = $profileGroupName;
    //
		$raduserGroup = RaduserGroup::where('username' , $username)->first();
		$raduserGroup->groupname = $profileGroupName;
		$raduserGroup->name = $name;
		/******/
    //updating status info
		$userStatusInfo =  UserStatusInfo::where('username' , $username)->first();
		$userStatusInfo->card_charge_on = $cur_date;
		$userStatusInfo->card_expire_on = $exp_date;
		$userStatusInfo->expire_datetime = $exp_date.' 12:00:00';
		$userStatusInfo->card_charge_by = $currentUser->username; 
		$userStatusInfo->card_charge_by_ip = $request->ip();
    //updateting expired user
		$expire_user = ExpireUser::where('username' , $username)->first();
		$expire_user->status = "charge";
		$expire_user->last_update = date('Y-m-d H:i:s');
		DB::transaction(function() use ($userIpStatus, $radReply,$raduserGroup,$userUsualIp,$userStatusInfo,$expire_user) {
			$radReply->save();
			$userIpStatus->save();
			$raduserGroup->save();
			$userUsualIp->save();
			$userStatusInfo->save();
			$expire_user->save();
    /*
     * insert new record for question category
     */
});

    //
		$userInfo = UserInfo::where('username',$username)->first();
    //
		if( Auth::user()->status == "dealer"){
			$profileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $userInfo->name])->first();
			$s_acc_rate = 0;
			$inv_subdealerrate = 0;
			$ct_margin = 0;
			$subdealer_ppm = 0;

		}else if( Auth::user()->status == "subdealer"){
			$profileRate = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $userInfo->name])->first();
			$s_acc_rate = $profileRate->base_price_ET;
			$inv_subdealerrate = $profileRate->base_price;
			$ct_margin = $profileRate->margin;
			$subdealer_ppm = $profileRate->partner_profit_margin;
		}

    //
		$dealerprofileRate = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $profileName])->first();
		$resellerprofileRate = ResellerProfileRate::where(['resellerid' => $currentUser->resellerid, 'name' => $profileName])->first();
		$m_profileRate = ManagerProfileRate::where(['manager_id' => $currentUser->manager_id, 'name' => $profileName])->first();
    //
		$commision = $dealerprofileRate->commision;
		$m_acc_rate = $m_profileRate->rate;
		$r_acc_rate = $resellerprofileRate->rate;
		$d_acc_rate = $dealerprofileRate->rate;
		$profit = $d_acc_rate - $r_acc_rate - $commision;
    //
		$walletDedArray = $this->check_deduction_amount($profileRate,$userInfo);

    ////////////////////////////////////////
		$uniqueID = $username . str_replace('-', '', date('Y-m-d'));
    //
		$insertAmountBilling = new AmountBillingInvoice();
		$insertAmountBilling->username = $username;
		$insertAmountBilling->userid = $uniqueID;
		$insertAmountBilling->m_rate = $m_acc_rate;
		$insertAmountBilling->r_rate = $r_acc_rate;
		$insertAmountBilling->rate = $d_acc_rate;
		$insertAmountBilling->dealerrate = $dealerprofileRate->base_price;
		$insertAmountBilling->subdealerrate = $inv_subdealerrate;
		$insertAmountBilling->s_acc_rate = $s_acc_rate;
		$insertAmountBilling->adv_percentage = $profileRate->advpercentage;
		$insertAmountBilling->sst = $walletDedArray['sst_u'];
		$insertAmountBilling->adv_tax = $walletDedArray['adv_tax_u'];
		$insertAmountBilling->sst_percentage = $profileRate->sstpercentage;
		$insertAmountBilling->traderrate = 0;
		$insertAmountBilling->t_acc_rate = 0;
		$insertAmountBilling->commision = $dealerprofileRate->commision;
		$insertAmountBilling->r_commission = $resellerprofileRate->commission;
		$insertAmountBilling->margin = $ct_margin;
		$insertAmountBilling->m_acc_rate = $m_acc_rate;
		$insertAmountBilling->r_acc_rate = $r_acc_rate;
		$insertAmountBilling->d_acc_rate = $d_acc_rate;
		$insertAmountBilling->c_sst = $profileRate->sst;
		$insertAmountBilling->c_adv = $profileRate->adv_tax;
		$insertAmountBilling->c_charges = $profileRate->consumer;
		$insertAmountBilling->c_rates = 0;
		$insertAmountBilling->profit = $profit;
		$insertAmountBilling->receipt = 'logon';
		$insertAmountBilling->receipt_num = 0;
		$insertAmountBilling->profile = $profileGroupName;
		$insertAmountBilling->name = $name;
		$insertAmountBilling->taxname = NULL;
		$insertAmountBilling->trader_id = $currentUser->trader_id;
		$insertAmountBilling->dealerid = $currentUser->dealerid;
		$insertAmountBilling->resellerid = $currentUser->resellerid;
		$insertAmountBilling->manager_id = $currentUser->manager_id;
		$insertAmountBilling->sub_dealer_id = $currentUser->sub_dealer_id;
		$insertAmountBilling->date = date('Y-m-d');
		$insertAmountBilling->charge_on = date('Y-m-d H:i:s');
		$insertAmountBilling->dasti_amount = 0;
		$insertAmountBilling->billing_type = 'amount';
		$insertAmountBilling->card_no = NULL;
		$insertAmountBilling->company_rate = $userInfo->company_rate;
		$insertAmountBilling->wallet_amount = $wallet_bef_charge;
                            //
		$insertAmountBilling->filer_tax = $walletDedArray['filer'];
		$insertAmountBilling->cvas_tax = $walletDedArray['get_taxt_data']['cvas_tax'];
		$insertAmountBilling->tip_tax = $walletDedArray['get_taxt_data']['tip_tax'];
		$insertAmountBilling->fll_tax = $walletDedArray['get_taxt_data']['fll_tax'];
		$insertAmountBilling->static_ip_amount = $userInfo->static_ip_amount;
                            //
		$insertAmountBilling->fll_charges = $walletDedArray['fll_charges'];
		$insertAmountBilling->cvas_charges  = $walletDedArray['cvas_charges'];
		$insertAmountBilling->tip_charges = $walletDedArray['tip_charges'];
		$insertAmountBilling->wallet_deduction = $wallet_deduction;
		$insertAmountBilling->dealer_gross_amount = $walletDedArray['fll_charges'] + $walletDedArray['cvas_charges'] + $walletDedArray['tip_charges'];
                            //
		$insertAmountBilling->ppm_reseller = $resellerprofileRate->partner_profit_margin;
		$insertAmountBilling->ppm_dealer = $dealerprofileRate->partner_profit_margin;
		$insertAmountBilling->ppm_subdealer = $subdealer_ppm;
		$insertAmountBilling->recharge_from = 'single';
		$insertAmountBilling->save();

        ////
		return true;
	}
	
}
