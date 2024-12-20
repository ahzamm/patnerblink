<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\RadCheck;
use App\model\Users\RadAcct;
use App\model\Users\DealerProfileRate;
use App\model\Users\DealerFUP;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\TraderProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\RaduserGroup;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserStatusInfo;
use App\model\Users\UserUsualIP;
use App\model\Users\UserAmount;
use App\model\Users\Profile;
use App\model\Users\CactiGraph;
use App\model\Users\Nas;
use App\model\Users\AssignedNas;
use App\model\Users\AssignNasType;
use App\model\Users\UserExpireLog;
use App\model\Users\ExpireUser;
use App\model\Users\StaticIPServer;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\DisabledUser;
use App\model\Users\Dhcp_server;
use App\model\Users\Dhcp_dealer_server;
use App\model\Users\PartnerThemesUser;

use Session;
use App\model\Users\FreezeAccount;
use App\model\Users\ChangePlan;
use App\model\Users\userAccess;
use App\model\Users\ProfileMargins;
use PDF;
use App\model\Users\NeverExpire;
use App\model\Users\SubMenu;
use App\model\Users\UserMenuAccess;
use DataTables;
use DateTime;
use App\model\Users\Cirprofile;
use App\model\Users\Domain;
use App\model\Users\ContractorTraderProfile;
use App\MyFunctions;


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



public function index1($status, Request $request)
{

	$manager_id = Auth::user()->manager_id;
	$resellerid = Auth::user()->resellerid;
	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$trader_id = Auth::user()->trader_id;
	$numShow = $request->get("countFilter");
	if ($numShow == "") {
		$numShow = 10;
	}

	switch ($status) {
		case "reseller":
		if (
			Auth::user()->status == "manager" &&
			Auth::user()->manager_id == $manager_id
		) {
			$resellerCollection = UserInfo::where([
				"status" => "reseller",
				"manager_id" => $manager_id,
			])->get();
		} else {
			return redirect()->route("users.dashboard");
		}

		return view("users.manager.view_reseler", [
			"resellerCollection" => $resellerCollection,
		]);
		break;
		case "dealer":
		$resellerid = Auth::user()->resellerid;
        //
        if(empty($resellerid)){
            $dealerCollection = UserInfo::where([
                "status" => "dealer",
                "manager_id" => Auth::user()->manager_id,
            ])->get();
        }else{
          $dealerCollection = UserInfo::where([
           "status" => "dealer",
           "resellerid" => $resellerid,
       ])->get();
      }
      //
      $assignedNas = AssignedNas::where(["id" => Auth::user()->resellerid ])->get();
      //
      return view("users.reseller.view_dealer", [
       "dealerCollection" => $dealerCollection,
       "assignedNas" => $assignedNas,
   ]);
      break;
      case "plan":
      $dealerid = Auth::user()->dealerid;
      if (
       Auth::user()->status == "dealer" &&
       Auth::user()->dealerid == $dealerid
   ) {
       $subdealerCollection = UserInfo::where([
        "status" => "subdealer",
        "dealerid" => Auth::user()->dealerid,
    ])->get();
   } else {
       return redirect()->route("users.dashboard");
   }

   return view("users.billing.changeplan", [
       "subdealerCollection" => $subdealerCollection,
   ]);
   break;

   case "subdealer":
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
$subdealerCollection = UserInfo::where("status","subdealer")->where($whereArray)->get();
    //
if (Auth::user()->status == "reseller" || Auth::user()->status == "inhouse" ) {
        //
   return view("users.reseller.view_sub_dealer", [ "subdealerCollection" => $subdealerCollection ]);

} elseif (Auth::user()->status == "dealer") {
        //
   return view("users.dealer.view_sub_dealer", [ "subdealerCollection" => $subdealerCollection ]);
} else {
        //
   return redirect()->route("users.dashboard");
}
break;




case "trader":
if (Auth::user()->status == "subdealer") {
   $traderCollection = UserInfo::where([
    "status" => "trader",
    "sub_dealer_id" => Auth::user()->sub_dealer_id,
])->get();
   return view("users.sub_dealer.view_trader", [
    "traderCollection" => $traderCollection,
]);
} else {
   return redirect()->route("users.dashboard");
}
break;
//uzair customers  =user
case "user":
     ///////////////////// check access ///////////////////////////////////
if(!MyFunctions::check_access('Active Consumers',Auth::user()->id)){
    abort(404);
}
     //////////////////////////////////////////////////////
return view("users.dealer.view_users");
break;
case "online":
     ///////////////////// check access ///////////////////////////////////
if(!MyFunctions::check_access('Online Consumers',Auth::user()->id)){
    abort(404);
}
     //////////////////////////////////////////////////////
return view("users.dealer.online_user");
$arr = [];
$dealerid = Auth::user()->dealerid;
$currentStatus = Auth::user()->status;
$sub_dealer_id = Auth::user()->sub_dealer_id;
if ($currentStatus == "dealer") {
// $userDealer = UserInfo::where(['dealerid' => $dealerid ,'status' => 'user','attribute' => 'Cleartext-Password'])->select('username','dealerid')->get();
} elseif ($currentStatus == "subdealer") {
   $userDealer = UserInfo::where([
    "sub_dealer_id" => Auth::user()->sub_dealer_id,
    "status" => "user",
])
   ->select("username", "sub_dealer_id")
   ->get();
} elseif ($currentStatus == "trader") {
   $userDealer = UserInfo::where([
    "trader_id" => Auth::user()->trader_id,
    "status" => "user",
])
   ->select("username", "sub_dealer_id")
   ->get();
} elseif (
   $currentStatus == "inhouse" &&
   $sub_dealer_id == Auth::user()->sub_dealer_id
) {
   $userDealer = UserInfo::where([
    "sub_dealer_id" => Auth::user()->sub_dealer_id,
    "status" => "user",
])
   ->select("username", "sub_dealer_id")
   ->get();
} elseif ($currentStatus == "inhouse") {
   $userDealer = UserInfo::where("dealerid", $dealerid)
   ->where("status", "user")
   ->select("username", "dealerid")
   ->get();
}
foreach ($userDealer as $value) {
   $dealerids = $value->dealerid;
   $online = RadAcct::where([
    "acctstoptime" => null,
    "username" => $value->username,
])->get();
   foreach ($online as $value) {
    $arr[] = $value;
}
}
//   $num = count($arr);
return view("users.dealer.online_user", [
   "arr" => $arr,
   "dealerids" => $dealerids,
]);
break;
case "expire":
    ///////////////////// check access ///////////////////////////////////
if(!MyFunctions::check_access('Expired Consumers',Auth::user()->id)){
    abort(404);
}
     //////////////////////////////////////////////////////
return view("users.billing.expired_user");

break;
case "disabled":
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
$disabledUser  = DB::table('user_info')
->join('disabled_users', 'disabled_users.username', '=', 'user_info.username')
->select('user_info.id','disabled_users.username','disabled_users.last_update','disabled_users.updated_by')
->where('disabled_users.status', 'disable')
->where($whereArray)
->where('user_info.status','=','user')
->get();
//
return view("users.dealer.disabled_user",['disabledUser' => $disabledUser]);


break;
case "terminate":
    ///////////////////// check access ///////////////////////////////////
if(!MyFunctions::check_access('New & Terminate',Auth::user()->id)){
    abort(404);
}
     //////////////////////////////////////////////////////
return view("users.billing.terminated_user");
break;
    //
case "remove":
    ///////////////////// check access ///////////////////////////////////
if(!MyFunctions::check_access('Reset (CNIC) Verification',Auth::user()->id)){
    abort(404);
}
     //////////////////////////////////////////////////////
$reset_log = DB::table('verification_reset_log')->orderBy('datetime','DESC')->take(500)->orderBy('id', 'DESC')->get();
return view('users.Verifications.data_remove',['reset_log' => $reset_log]);
break;
    //
default:
return redirect()->route("users.dashboard");
}
}
public function viewCustomerServerSideUser(Request $request)
{

    $dealerid = Auth::user()->dealerid;
    $sub_dealer_id = Auth::user()->sub_dealer_id;
    $status = Auth::user()->status;

    $dealerid = Auth::user()->dealerid;
    $sub_dealer_id = Auth::user()->sub_dealer_id;
    $trader_id = Auth::user()->trader_id;
    $status = Auth::user()->status;
    $date = date("Y-m-d");
    $usersCollection = [];
    $currentStatus = Auth::user()->status;

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
    $dealerHaveRightsOfMACBind = UserInfo::where('dealerid',Auth::user()->dealerid)->where('status','dealer')->where('bind_mac',1)->select('bind_mac')->first();
    //
    $userDealer = UserInfo::Join("user_status_info", function ($join) {
        $join->on(
            "user_status_info.username",
            "=",
            "user_info.username"
        );
    })
    ->leftJoin("user_verification", function ($join) {
        $join->on(
            "user_verification.username",
            "=",
            "user_info.username"
        );
    })
    ->where($whereArray)
    ->where("user_info.status","user")
    ->where(function ($q) {
        $q->where(
            "user_status_info.expire_datetime",
            ">=",
            date("Y-m-d H:i:s")
        );
    })
    ->get([
        "user_info.dealerid",
        "user_info.username",
        "user_info.profile",
        "user_info.firstname",
        "user_info.lastname",
        "user_info.address",
        "user_info.name",
        "user_info.sub_dealer_id",
        "user_info.trader_id",
        "user_info.id",
        "user_info.bind_mac",
        "user_status_info.card_expire_on",
                          // billing work
        "user_status_info.card_charge_on",
                          // end
        "user_verification.cnic",
        "mobile_status",
        "never_expire",
    ]);
    // }

    $sno = 1;
    $pro_groupname = "";

    return Datatables::of($userDealer)
    ->addColumn("action", function ($row) use ($dealerHaveRightsOfMACBind) {
        $html =
        '<a href="/users/user/user?id=' .
        $row->id .
        '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View</a> ';
        $html .=
        '<a href="/users/users/user/' .
        $row->id .
        '" class="btn btn-info mb1 btn-xs" style="margin-right:4px"><i class="fa fa-edit"></i> Edit</a>';
        $html .=
        '<div class="dropdown action-dropdown"><button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button><div class="dropdown-menu action-dropdown_menu"><ul><li class="dropdown-item"><a href="/users/user/user?id=' .$row->id .'"><i class="la la-eye"></i> View</a> </li><li class="dropdown-item"><a href="/users/users/user/' .$row->id .'"><i class="la la-edit"></i> Edit</a></li><hr style="margin-top:0">';
        if ($row->profile == "DISABLED") {
         $html .= '<li class="dropdown-item"><a href="#" disabled><i class="fa fa-ban"></i>  DISABLED</a></li>';
     }
     $csrf = csrf_token();
     if (@$row->cnic != "" || @$row->cnic != null) {
        $html .=
        '<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> CNIC <span style="color:darkgreen">(Verified)</span></a></li>';
    } else {
     $html .=
     '<li class="dropdown-item"><form action="/users/nicVerify" method="POST" style="display:inline">
     <input type="hidden" name="_token" value="' .
     $csrf .
     '">
     <input type="hidden" name="username" id="username" value="' .
     $row->username .
     '">
     <button type="submit"><i class="las la-exclamation-triangle"></i> CNIC <span style="color:red">(Not verified)</span></button>
     </form></li>';
 }
 if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
    $html .=
    '<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> Mobile <span style="color:darkgreen">(Verified)</span></a></li>';
} else {
    $html .=
    '<li class="dropdown-item"><form action="/users/smsverify" method="POST" style="display:inline">
    <input type="hidden" name="_token" value="' .
    $csrf .
    '">
    <input type="hidden" name="username" id="username" value="' .
    $row->username .
    '">
    <button type="submit"><i class="las la-exclamation-triangle"></i> MOBILE <span style="color:red">(Not verified)</span></button>
    </form></li>';
                    // $html .= '<a href="#" class="btn btn-danger btn-xs" style="border-radius:7px;"><i class="fa fa-close"></i>Mobile</a>';
}

if($dealerHaveRightsOfMACBind){
    $bind_status  = ($row->bind_mac == 0) ? 'MAC Add (<span style="color:darkgreen">Bind</span>)'  : 'MAC Add (<span style="color:red">Unbind</span>)' ;
    $html .= '<li class="dropdown-item"><a href="#" class="bindmacclass" data-username="'.$row->username.'"><i class="la la-server"></i> '.$bind_status.'  </a></li>';
}

if(MyFunctions::check_access('Never Expire Consumers',Auth::user()->id) ){
    $html .= '<li class="dropdown-item">
    <a href="#" class="nexpmodal" data-username="'.$row->username.'"><i class="la la-exclamation"></i> Never Expire</a>
    </li>';
}

$html .= '</ul></div></div>';
        // if ($row->profile == "DISABLED") {
        //     $html .=
        //     '<a href="#" class="btn btn-danger btn-xs  mb1 bg-olive btn-xs" disabled><i class="fa fa-ban"></i>  DISABLED</a>';
        // }
                // elseif($row->expire_datetime > date('Y-m-d 12:00:00') && $row->never_expire != "yes"){
                // $html .= '<a href="#" class="btn btn-success btn-xs mb1 bg-olive btn-xs " disabled style="border-radius:7px;"><i class="fa fa-recycle"></i> Recharge</a>';
                // }
        // elseif ($row->never_expire == "yes") {
        //     $html .=
        //     '<a href="#" class="btn btn-secondary btn-xs mb1 bg-olive btn-xs" disabled> <i class="fa fa-history"></i> never expire</a>';
        // }
                // else{
                // $html .= '<a href="/users/single/'.$row->id.'" class="btn btn-success btn-xs mb1 bg-olive btn-xs" disabled style="border-radius:7px;"><i class="fa fa-recycle"></i> Recharge</a>';
                // }
return $html;
})->setRowAttr([
    'style' => function ($row) {
        return $row->profile == 'DISABLED' ? 'background-color: #e3c6c6 !important;' : '';
    }
])
->addColumn("fullname", function ($row) {
    return $row->firstname . " " . $row->lastname;
})
->addColumn("usernames", function ($row) {
    return $row->username;
})
->addColumn("subdealerid", function ($row) {
    if ($row->sub_dealer_id) {
        return $row->sub_dealer_id;
    } else {
        return "My Users";
    }
})
->editColumn("action_delete", function ($row) {
    $csrf = csrf_token();
    if (@$row->cnic != "" || @$row->cnic != null) {
        $html =
        '<a href="#" class="btn btn-success btn-xs" style="margin:5px;" disabled><i class="fa fa-check"></i> CNIC</a>';
    } else {
                    // $html = "<a href='#' onclick=nicVerify('".$row->username."') class='btn btn-danger btn-xs' style='margin:5px;border-radius:7px;'><i class='fa fa-close'></i>CNIC</a>";
        $html =
        ' <form action="/users/nicVerify" method="POST" style="display:inline">
        <input type="hidden" name="_token" value="' .
        $csrf .
        '">
        <input type="hidden" name="username" id="username" value="' .
        $row->username .
        '">
        <button type="submit"  class="btn btn-danger btn-xs" style="margin:5px;"><i class="las la-exclamation-triangle"></i> CNIC</button>
        </form>';
    }
    if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
        $html .=
        '<a href="#" class="btn btn-info btn-xs"  disabled><i class="fa fa-check"></i> Mobile</a>';
    } else {
        $html .=
        '<form action="/users/smsverify" method="POST" style="display:inline">
        <input type="hidden" name="_token" value="' .
        $csrf .
        '">
        <input type="hidden" name="username" id="username" value="' .
        $row->username .
        '">
        <button type="submit"  class="btn btn-danger btn-xs"><i class="las la-exclamation-triangle"></i> MOBILE</button>
        </form>';
                    // $html .= '<a href="#" class="btn btn-danger btn-xs" style="border-radius:7px;"><i class="fa fa-close"></i>Mobile</a>';
    }

    //     $get_url = url('users/bill/view/'.$row->id);
    //             $html .=   ' <form action="'.$get_url.'" method="get" style="display:inline"><button type="submit"  class="btn btn-default btn-xs"><a href="'.$get_url.'"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red"></i></a>
    //    </button></form>';
    return $html;
})

      // billing work
->editColumn("invoice", function ($row) {
    $get_url = url('users/bill/view/'.$row->username.'/'.$row->card_charge_on);
    $get_invoice = '';
    $show_invoice = 0;
        //
    $reseller_inv_status = UserInfo::where('resellerid',Auth::user()->resellerid)->first()->allow_invoice;
    $dealer_inv_status = UserInfo::where('dealerid',Auth::user()->dealerid)->first()->allow_invoice;
    $subdealer_inv_status = UserInfo::where('sub_dealer_id',Auth::user()->sub_dealer_id)->first()->allow_invoice;
        //
    if(Auth::user()->status == 'dealer'){
        if($reseller_inv_status == 1 && $dealer_inv_status == 1){
            $show_invoice = 1;
        }
    }else if(Auth::user()->status == 'subdealer'){
        if($reseller_inv_status == 1 && $dealer_inv_status == 1 && $subdealer_inv_status == 1){
            $show_invoice = 1;
        }
    }
        //
    $diff = (strtotime($row->card_charge_on) - strtotime(date('2023-06-20')));
    $diff = (round($diff / 86400));
        //
    if($diff > 0 &&  $show_invoice == 1 ){
        $get_invoice = '<form action="'.$get_url.'" method="get" style="display:inline"><button type="submit"  class="btn btn-default btn-xs" style="color:red;border:none"><a href="'.$get_url.'" style="color:red" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Invoice</a>
        </button></form>';
    }else{
        $get_invoice = 'Not available';
    }
    return $get_invoice;
})

->addColumn("card_expire_on_col", function ($row) {

    return date('M d,Y',strtotime($row->card_expire_on));
})


->rawColumns([
    "action_delete" => "action_delete","invoice"=> "invoice", "action" => "action", "card_expire_on_col" => "card_expire_on_col"
])
                  // end
->addIndexColumn()
->make(true);

}
public function expireServerSideUser(Request $request)
{
	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$status = Auth::user()->status;

	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$trader_id = Auth::user()->trader_id;
	$status = Auth::user()->status;

	$date = date("Y-m-d");
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
	//
    $allusers = UserInfo::Join("user_status_info", function ($join) {
     $join->on(
        "user_status_info.username",
        "=",
        "user_info.username"
    );
 })
    ->leftJoin("user_verification", function ($join) {
     $join->on(
        "user_verification.username",
        "=",
        "user_info.username"
    );
 })
    ->where($whereArray)
    ->where("user_info.status","user")
    ->where([
     [
        "user_status_info.card_expire_on",
        ">=",
        date("Y-m-d", strtotime("-7 day")),
    ],
])
    ->where(
     "user_status_info.expire_datetime",
     "<=",
     date("Y-m-d H:i:s")
 )
    ->get([
     "user_info.dealerid",
     "user_info.username",
     "user_info.profile",
     "user_info.firstname",
     "user_info.lastname",
     "user_info.address",
     "user_info.name",
     "user_info.sub_dealer_id",
     "user_info.trader_id",
     "user_info.id",
     "user_info.status",
     "user_info.mobilephone",
     "user_status_info.card_expire_on",
     "user_verification.cnic",
     "mobile_status",

 ]);
//



    $sno = 1;
    $pro_groupname = "";
    $allData = [];
    foreach ($allusers as $data) {
      $allData[] = $data;
// dd($allData);
  }

  return Datatables::of($allData)
  ->addColumn("action", function ($row) {
      $html =
      '<a href="/users/user/user?id=' .
      $row->id .
      '" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i>View</a> ';
		//

      if ($row->profile == "DISABLED") {
         $html .=
         '<a href="#" class="btn btn-danger btn-xs disabled mb1 bg-olive btn-xs" style="margin-right:4px"><i class="fa fa-ban"></i> DISABLED</a>';
     } else {
        if(Auth::user()->status == 'dealer' || Auth::user()->status == 'subdealer' ){
           $html .=
           '<a href="/users/single/' .
           $row->id .
           '" class="btn btn-success btn-xs mb1 btn-xs" style="margin-right: 4px;"><i class="fa fa-recycle"></i>Recharge</a>';
       }
   }
     //
   if(Auth::user()->status != 'inhouse'){

       $html .=
       '<div class="dropdown action-dropdown"><button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button><div class="dropdown-menu action-dropdown_menu"><ul><li class="dropdown-item"><a href="/users/user/user?id=' .$row->id .'"><i class="la la-eye"></i> View</a> </li><li class="dropdown-item"><a href="/users/users/user/' .$row->id .'"><i class="la la-edit"></i> Edit</a></li><hr style="margin-top:0">';


       $csrf = csrf_token();
       if (@$row->cnic != "" || @$row->cnic != null) {
           $html .=
           '<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> CNIC <span style="color:darkgreen">(Verified)</span></a></li>';
       } else {
           $html .=
           '<li class="dropdown-item"><form action="/users/nicVerify" method="POST" style="display:inline">
           <input type="hidden" name="_token" value="' .
           $csrf .
           '">
           <input type="hidden" name="username" id="username" value="' .
           $row->username .
           '">
           <button type="submit"><i class="las la-exclamation-triangle"></i> CNIC <span style="color:red">(Not verified)</span></button>
           </form></li>';
       }
       if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
           $html .=
           '<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> Mobile <span style="color:darkgreen">(Verified)</span></a></li>';
       } else {
           $html .=
           '<li class="dropdown-item"><form action="/users/smsverify" method="POST" style="display:inline">
           <input type="hidden" name="_token" value="' .
           $csrf .
           '">
           <input type="hidden" name="username" id="username" value="' .
           $row->username .
           '">
           <button type="submit"><i class="las la-exclamation-triangle"></i> MOBILE <span style="color:red">(Not verified)</span></button>
           </form></li>';
       }
       //
       if(MyFunctions::check_access('Never Expire Consumers',Auth::user()->id) ){
        $html .= '<li class="dropdown-item">
        <a href="#" class="nexpmodal" data-username="'.$row->username.'"><i class="la la-exclamation"></i> Never Expire</a>
        </li>';
    }
       //
    $html .= '</ul></div></div>';
     //
}
return $html;
})
  ->addColumn("fullname", function ($row) {
      return $row->firstname . " " . $row->lastname;
  })
  ->addColumn("subdealerid", function ($row) {
     return (empty($row->sub_dealer_id)) ? 'N/A': $row->sub_dealer_id;
        // return $row->sub_dealer_id;
 })
  ->addColumn("expireDate", function ($row) {
    return date('M d,Y',strtotime($row->card_expire_on));
})
  ->editColumn("action_delete", function ($row) {
      $csrf = csrf_token();
      if (@$row->cnic != "" || @$row->cnic != null) {
         $html =
         '<a href="#" class="btn btn-success btn-xs" style="margin:5px;" disabled><i class="fa fa-check"></i>CNIC</a>';
     } else {
// $html = "<a href='#' onclick=nicVerify('".$row->username."') class='btn btn-danger btn-xs' style='margin:5px;border-radius:7px;'><i class='fa fa-close'></i>CNIC</a>";
         $html =
         ' <form action="/users/nicVerify" method="POST" style="display:inline">
         <input type="hidden" name="_token" value="' .
         $csrf .
         '">
         <input type="hidden" name="username" id="username" value="' .
         $row->username .
         '">
         <button type="submit"  class="btn btn-danger btn-xs"><i class="las la-exclamation-triangle"></i>CNIC</button>
         </form>';
     }
     if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
         $html .=
         '<a href="#" class="btn btn-info btn-xs" disabled><i class="fa fa-check"></i>Mobile</a>';
     } else {
         $html .=
         '<form action="/users/smsverify" method="POST" style="display:inline">
         <input type="hidden" name="_token" value="' .
         $csrf .
         '">
         <input type="hidden" name="username" id="username" value="' .
         $row->username .
         '">
         <button type="submit"  class="btn btn-danger btn-xs"><i class="las la-exclamation-triangle"></i>MOBILE</button>
         </form>';
// $html .= '<a href="#" class="btn btn-danger btn-xs" style="border-radius:7px;"><i class="fa fa-close"></i>Mobile</a>';
     }
     return $html;
 })
  ->rawColumns([
      "action_delete" => "action_delete",
      "action" => "action",
  ])
  ->addIndexColumn()
  ->make(true);
}
public function terminateServerSideUser(Request $request)
{
// -----------------------------------------------------------------------------
	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$status = Auth::user()->status;
	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$trader_id = Auth::user()->trader_id;
	$status = Auth::user()->status;
	$date = date("Y-m-d");
	if ($status == "dealer") {
		$allusers = UserInfo::Join("user_status_info", function ($join) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->leftJoin("user_verification", function ($join) {
			$join->on(
				"user_verification.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.dealerid", "=", $dealerid)
		->where("user_info.status", "=", "user")
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime("-7 day")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.name",
			"user_info.sub_dealer_id",
			"user_info.trader_id",
			"user_info.id",
			"user_status_info.card_expire_on",
			"user_verification.cnic",
			"mobile_status",
		]);
	} elseif ($status == "subdealer") {
		$allusers = UserInfo::Join("user_status_info", function ($join) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->leftJoin("user_verification", function ($join) {
			$join->on(
				"user_verification.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.status", "=", "user")
		->where("user_info.sub_dealer_id", "=", $sub_dealer_id)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime("-7 day")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.name",
			"user_info.sub_dealer_id",
			"user_info.trader_id",
			"user_info.id",
			"user_status_info.card_expire_on",
			"user_verification.cnic",
			"mobile_status",
		]);
	} elseif ($status == "trader") {
		$allusers = UserInfo::Join("user_status_info", function ($join) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->leftJoin("user_verification", function ($join) {
			$join->on(
				"user_verification.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.status", "=", "user")
		->where("user_info.trader_id", "=", $trader_id)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime("-7 day")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.name",
			"user_info.sub_dealer_id",
			"user_info.trader_id",
			"user_info.id",
			"user_status_info.card_expire_on",
			"user_verification.cnic",
			"mobile_status",
		]);
	} elseif ($status == "inhouse" && $sub_dealer_id != "") {
		$allusers = UserInfo::Join("user_status_info", function ($join) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->leftJoin("user_verification", function ($join) {
			$join->on(
				"user_verification.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.status", "=", "user")
		->where("user_info.sub_dealer_id", "=", $sub_dealer_id)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime("-7 day")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.name",
			"user_info.sub_dealer_id",
			"user_info.trader_id",
			"user_info.id",
			"user_status_info.card_expire_on",
			"user_verification.cnic",
			"mobile_status",
		]);
	} elseif ($status == "inhouse" && $dealerid == Auth::user()->dealerid) {
		$allusers = UserInfo::Join("user_status_info", function ($join) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->leftJoin("user_verification", function ($join) {
			$join->on(
				"user_verification.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.status", "=", "user")
		->where("user_info.dealerid", "=", $dealerid)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime("-7 day")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.name",
			"user_info.sub_dealer_id",
			"user_info.trader_id",
			"user_info.id",
			"user_status_info.card_expire_on",
			"user_verification.cnic",
			"mobile_status",
		]);
	}
	$sno = 1;
	$pro_groupname = "";
	$allData = [];
	foreach ($allusers as $data) {
		$allData[] = $data;
// dd($allData);
	}

	return Datatables::of($allData)
	->addColumn("action", function ($row) {
		$html =
		'<a href="/users/user/user?id=' .
		$row->id .
		'" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View</a> ';
		// $html .=
		// '<a href="/users/users/user/' .
		// $row->id .
		// '" class="btn btn-info mb1 bg-olive btn-xs"><i class="fa fa-edit"></i> Edit</a>';
		if ($row->profile == "DISABLED") {
			$html .=
			'<a href="#" class="btn btn-danger btn-xs disabled mb1 bg-olive btn-xs" style="margin-right:4px"><i class="fa fa-ban"></i> DISABLED</a>';
		} else {
			$html .=
			'<a href="/users/single/' .
			$row->id .
			'" class="btn btn-success btn-xs mb1 bg-olive btn-xs" style="margin-right:4px"><i class="fa fa-recycle"></i> Recharge</a>';
		}
		$html .=
		'<div class="dropdown action-dropdown"><button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button><div class="dropdown-menu action-dropdown_menu"><ul><li class="dropdown-item"><a href="/users/user/user?id=' .$row->id .'"><i class="la la-eye"></i> View</a> </li><li class="dropdown-item"><a href="/users/users/user/' .$row->id .'"><i class="la la-edit"></i> Edit</a></li><hr style="margin-top:0">';

		$csrf = csrf_token();
		if (@$row->cnic != "" || @$row->cnic != null) {
			$html .=
			'<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> CNIC <span style="color:darkgreen">(Verified)</span></a></li>';
		} else {
			$html .=
			'<li class="dropdown-item"> <form action="/users/nicVerify" method="POST" style="display:inline">
			<input type="hidden" name="_token" value="' .
			$csrf .
			'">
			<input type="hidden" name="username" id="username" value="' .
			$row->username .
			'">
			<button type="submit"><i class="las la-exclamation-triangle"></i> CNIC <span style="color:red">(Not verified)</span></button>
			</form></li>';
		}
		if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
			$html .=
			'<li class="dropdown-item"><a href="#" disabled><i class="la la-check"></i> Mobile <span style="color:darkgreen">(Verified)</span></a>';
		} else {
			$html .=
			'<li class="dropdown-item"><form action="/users/smsverify" method="POST" style="display:inline">
			<input type="hidden" name="_token" value="' .
			$csrf .
			'">
			<input type="hidden" name="username" id="username" value="' .
			$row->username .
			'">
			<button type="submit"> <i class="las la-exclamation-triangle"></i> MOBILE <span style="color:red">(Not verified)</span></button>
			</form></li>';
		}
        //
        if(MyFunctions::check_access('Never Expire Consumers',Auth::user()->id) ){
            $html .= '<li class="dropdown-item">
            <a href="#" class="nexpmodal" data-username="'.$row->username.'"><i class="la la-exclamation"></i> Never Expire</a>
            </li>';
        }
        //
        $html .= '</ul></div></div>';
        return $html;
    })
    ->addColumn("new_expire", function ($row) {
        //
        if(strtotime(date($row->card_expire_on)) < strtotime(date('2000-01-01')) ){
            return 'NEW';
        }else{
            return date('M d,Y',strtotime($row->card_expire_on));
        }
    })
    ->addColumn("fullname", function ($row) {
      return $row->firstname . " " . $row->lastname;
  })
    ->addColumn("subdealerid", function ($row) {
        return (empty($row->sub_dealer_id)) ? 'N/A' : $row->sub_dealer_id ;
    })
    ->editColumn("action_delete", function ($row) {
      $csrf = csrf_token();
      if (@$row->cnic != "" || @$row->cnic != null) {
         $html =
         '<a href="#" class="btn btn-success btn-xs" style="margin:5px;" disabled><i class="fa fa-check"></i>  CNIC</a>';
     } else {
// $html = "<a href='#' onclick=nicVerify('".$row->username."') class='btn btn-danger btn-xs' style='margin:5px;border-radius:7px;'><i class='fa fa-close'></i>CNIC</a>";
         $html =
         ' <form action="/users/nicVerify" method="POST" style="display:inline">
         <input type="hidden" name="_token" value="' .
         $csrf .
         '">
         <input type="hidden" name="username" id="username" value="' .
         $row->username .
         '">
         <button type="submit"  class="btn btn-danger btn-xs"><i class="las la-exclamation-triangle"></i> CNIC</button>
         </form>';
     }
     if (@$row->mobile_status != 0 || @$row->mobile_status != null) {
         $html .=
         '<a href="#" class="btn btn-info btn-xs" disabled><i class="fa fa-check"></i> Mobile</a>';
     } else {
         $html .=
         '<form action="/users/smsverify" method="POST" style="display:inline">
         <input type="hidden" name="_token" value="' .
         $csrf .
         '">
         <input type="hidden" name="username" id="username" value="' .
         $row->username .
         '">
         <button type="submit"  class="btn btn-danger btn-xs"><i class="las la-exclamation-triangle"></i> MOBILE</button>
         </form>';
// $html .= '<a href="#" class="btn btn-danger btn-xs" style="border-radius:7px;"><i class="fa fa-close"></i>Mobile</a>';
     }
     return $html;
 })
    ->rawColumns([
      "action_delete" => "action_delete",
      "action" => "action",
  ])
    ->addIndexColumn()
    ->make(true);
}

public function verifyUser($username)
{
	$currentStatus = Auth::user()->status;
	if ($currentStatus == "dealer") {
		$userDealer = UserInfo::where([
			"dealerid" => Auth::user()->dealerid,
			"username" => $username,
		])
		->select(
			"id",
			"username",
			"dealerid",
			"nic",
			"sub_dealer_id",
			"resellerid",
			"status"
		)
		->first();
	} elseif ($currentStatus == "subdealer") {
		$userDealer = UserInfo::where([
			"sub_dealer_id" => Auth::user()->sub_dealer_id,
			"username" => $username,
		])
		->select(
			"id",
			"username",
			"dealerid",
			"nic",
			"sub_dealer_id",
			"resellerid",
			"status"
		)
		->first();
	} else {
		$userDealer = UserInfo::where([
			"trader_id" => Auth::user()->trader_id,
			"username" => $username,
		])
		->select(
			"id",
			"username",
			"dealerid",
			"nic",
			"sub_dealer_id",
			"resellerid",
			"status",
			"trader_id"
		)
		->first();
	}
	return view("users.dealer.Aslamview_users", [
		"userDealer" => $userDealer,
	]);
}

public function verifySms($username)
{
	$currentStatus = Auth::user()->status;
	if ($currentStatus == "dealer") {
		$userDealer = UserInfo::where([
			"dealerid" => Auth::user()->dealerid,
			"username" => $username,
		])
		->select("id", "username", "dealerid", "mobilephone", "status")
		->first();
	} else {
		$userDealer = UserInfo::where([
			"sub_dealer_id" => Auth::user()->sub_dealer_id,
			"username" => $username,
		])
		->select(
			"id",
			"username",
			"sub_dealer_id",
			"mobilephone",
			"status"
		)
		->first();
	}
	return view("users.dealer.smsView", [
		"userDealer" => $userDealer,
	]);
}

//Aslam Work
public function epiredUser($status)
{
	$dealerid = Auth::user()->dealerid;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$status = Auth::user()->status;

	$date = date("Y-m-d");
//
	if ($status == "dealer") {
		$allusers = UserInfo::leftJoin("user_status_info", function (
			$join
		) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.dealerid", "=", $dealerid)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime($date . " -1 months")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.id",
			"user_status_info.card_expire_on",
		]);
	} else {
		$allusers = UserInfo::leftJoin("user_status_info", function (
			$join
		) {
			$join->on(
				"user_status_info.username",
				"=",
				"user_info.username"
			);
		})
		->where("user_info.sub_dealer_id", "=", $sub_dealer_id)
		->where([
			[
				"user_status_info.card_expire_on",
				"<",
				date("Y-m-d", strtotime($date . " -1 months")),
			],
			["user_status_info.card_expire_on", "!=", "1990-01-01"],
		])
		->get([
			"user_info.dealerid",
			"user_info.username",
			"user_info.profile",
			"user_info.firstname",
			"user_info.lastname",
			"user_info.address",
			"user_info.id",
			"user_status_info.card_expire_on",
		]);
	}

	return view("users.billing.expired_user", [
		"allusers" => $allusers,
	]);
}

//////////////
public function onlineUsers(){
    if(!MyFunctions::check_access('Online Consumers',Auth::user()->id)){
        abort(404);
    }
    return view("users.dealer.Online_User.online_user");
}
////////////

// public function onlineUsers_DELETED($status)
// {
// 	$arr = [];
// 	$dealerids = [];
// 	// $dealerid = Auth::user()->dealerid;
// 	$currentStatus = Auth::user()->status;
// 	// $sub_dealer_id = Auth::user()->sub_dealer_id;
//     //
//     $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
//     $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
//     $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
//     $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
//     $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
//         //
//         //
//     $whereArray = array();
//     //
//     if(!empty($manager_id)){
//         array_push($whereArray,array('manager_id' , $manager_id));
//     }if(!empty($resellerid)){
//         array_push($whereArray,array('resellerid' , $resellerid));
//     }if(!empty($dealerid)){
//         array_push($whereArray,array('dealerid' , $dealerid));
//     }if(!empty($sub_dealer_id)){
//         array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));
//     }
//     //
//     $userDealer = UserInfo::where($whereArray)->select("username", "dealerid")->get();
//     $dhcp_server = Dhcp_dealer_server::where(["dealerid" => $dealerid])->first();

// 	// if ($currentStatus == "dealer") {
// 	// 	$userDealer = UserInfo::where(["dealerid" => $dealerid])
// 	// 	->select("username", "dealerid")
// 	// 	->get();
// 	// 	$dhcp_server = Dhcp_dealer_server::where([
// 	// 		"dealerid" => $dealerid,
// 	// 	])->first();
// 	// } elseif ($currentStatus == "subdealer") {
// 	// 	$userDealer = UserInfo::where([
// 	// 		"sub_dealer_id" => Auth::user()->sub_dealer_id,
// 	// 		"status" => "user",
// 	// 	])
// 	// 	->select("username", "sub_dealer_id", "dealerid")
// 	// 	->get();
// 	// 	$dhcp_server = Dhcp_dealer_server::where([
// 	// 		"dealerid" => $dealerid,
// 	// 	])->first();
// 	// } elseif ($currentStatus == "trader") {
// 	// 	$userDealer = UserInfo::where([
// 	// 		"trader_id" => Auth::user()->trader_id,
// 	// 		"status" => "user",
// 	// 	])
// 	// 	->select("username", "sub_dealer_id", "dealerid")
// 	// 	->get();
// 	// 	$dhcp_server = Dhcp_dealer_server::where([
// 	// 		"dealerid" => $dealerid,
// 	// 	])->first();
// 	// } elseif (
// 	// 	$currentStatus == "inhouse" &&
// 	// 	Auth::user()->sub_dealer_id == ""
// 	// ) {
// 	// 	$userDealer = UserInfo::where([
// 	// 		"dealerid" => $dealerid,
// 	// 		"status" => "user",
// 	// 	])
// 	// 	->select("username", "dealerid")
// 	// 	->get();
// 	// 	$dhcp_server = Dhcp_dealer_server::where([
// 	// 		"dealerid" => $dealerid,
// 	// 	])->first();
// 	// } elseif (
// 	// 	$currentStatus == "inhouse" &&
// 	// 	Auth::user()->sub_dealer_id != ""
// 	// ) {
// 	// 	$userDealer = UserInfo::where([
// 	// 		"sub_dealer_id" => Auth::user()->sub_dealer_id,
// 	// 		"status" => "user",
// 	// 	])
// 	// 	->select("username", "sub_dealer_id","dealerid")
// 	// 	->get();
// 	// 	$dhcp_server = Dhcp_dealer_server::where([
// 	// 		"dealerid" => $dealerid,
// 	// 	])->first();
// 	// }



//     foreach ($userDealer as $value) {
//       $dealerids = $value->dealerid;
//       $online = RadAcct::where([
//        "acctstoptime" => null,
//        "username" => $value->username,
//    ])->orderBy('acctstarttime','DESC')->get();
//       foreach ($online as $value) {
//        $arr[] = $value;
//    }
// }



// $num = count($arr);
// return view("users.dealer.online_user", [
//   "arr" => $arr,
//   "dealerids" => $dealerids,
//   "dhcp_server" => $dhcp_server,
//   "nn" => $num,
// ]);
// }


// Offline
public function offlineUserView()
{
	$arr = [];
	$dealerids = [];
	// $dealerid = Auth::user()->dealerid;
	$currentStatus = Auth::user()->status;
	// $sub_dealer_id = Auth::user()->sub_dealer_id;
    //
    $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
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
    $userDealer = UserInfo::join(
       "user_status_info",
       "user_status_info.username",
       "user_info.username"
   )
    ->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
    ->where($whereArray)
    ->where("user_info.status","user")
    ->select("user_info.username", "user_info.dealerid")
    ->get();
    $dhcp_server = Dhcp_dealer_server::where([
       "dealerid" => $dealerid,
   ])->first();
    //
	// if ($currentStatus == "dealer") {
	// 	$userDealer = UserInfo::join(
	// 		"user_status_info",
	// 		"user_status_info.username",
	// 		"user_info.username"
	// 	)
	// 	->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
	// 	->where(["dealerid" => $dealerid, "status" => "user"])
	// 	->select("user_info.username", "dealerid")
	// 	->get();
	// 	$dhcp_server = Dhcp_dealer_server::where([
	// 		"dealerid" => $dealerid,
	// 	])->first();
	// } elseif ($currentStatus == "subdealer") {
	// 	$userDealer = UserInfo::join(
	// 		"user_status_info",
	// 		"user_status_info.username",
	// 		"user_info.username"
	// 	)
	// 	->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
	// 	->where([
	// 		"sub_dealer_id" => Auth::user()->sub_dealer_id,
	// 		"status" => "user",
	// 	])
	// 	->select("user_info.username", "sub_dealer_id")
	// 	->get();
	// 	$dhcp_server = Dhcp_dealer_server::where([
	// 		"dealerid" => $dealerid,
	// 	])->first();
	// } elseif ($currentStatus == "trader") {
	// 	$userDealer = UserInfo::join(
	// 		"user_status_info",
	// 		"user_status_info.username",
	// 		"user_info.username"
	// 	)
	// 	->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
	// 	->where([
	// 		"trader_id" => Auth::user()->trader_id,
	// 		"status" => "user",
	// 	])
	// 	->select("user_info.username", "sub_dealer_id")
	// 	->get();
	// 	$dhcp_server = Dhcp_dealer_server::where([
	// 		"dealerid" => $dealerid,
	// 	])->first();
	// } elseif (
	// 	$currentStatus == "inhouse" &&
	// 	Auth::user()->sub_dealer_id == ""
	// ) {
	// 	$userDealer = UserInfo::join(
	// 		"user_status_info",
	// 		"user_status_info.username",
	// 		"user_info.username"
	// 	)
	// 	->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
	// 	->where(["dealerid" => $dealerid, "status" => "user"])
	// 	->select("user_info.username", "dealerid")
	// 	->get();
	// 	$dhcp_server = Dhcp_dealer_server::where([
	// 		"dealerid" => $dealerid,
	// 	])->first();
	// } elseif (
	// 	$currentStatus == "inhouse" &&
	// 	Auth::user()->sub_dealer_id != ""
	// ) {
	// 	$userDealer = UserInfo::join(
	// 		"user_status_info",
	// 		"user_status_info.username",
	// 		"user_info.username"
	// 	)
	// 	->where("user_status_info.card_expire_on", ">", date("Y-m-d"))
	// 	->where([
	// 		"sub_dealer_id" => Auth::user()->sub_dealer_id,
	// 		"status" => "user",
	// 	])
	// 	->select("user_info.username", "sub_dealer_id")
	// 	->get();
	// 	$dhcp_server = Dhcp_dealer_server::where([
	// 		"dealerid" => $dealerid,
	// 	])->first();
	// }

    foreach ($userDealer as $value) {
      $dealerids = $value->dealerid;
      // $offline = RadAcct::where("username", $value->username)
      // ->orderby("radacct.radacctid", "DESC")
      // ->first();


      $isOffline = RadAcct::where("username", $value->username)->whereNotIn('username', function($query){
        $query->select('username')
        ->from('radacct')
        ->where('acctstoptime',NULL);
    })->orderby("radacctid", "DESC")->first();

      if($isOffline){
        $arr[] = $isOffline;
    }

// dd($offline);
   //    if (@$offline->acctstoptime == null) {
   //    } else {
   //     $arr[] = $offline;
   // }
}
return view("users.dealer.offline_user", [
  "arr" => $arr,
  "dealerids" => $dealerids,
  "dhcp_server" => $dhcp_server,
]);
}
// public function onlineUsers($status){
// 	return view('users.dealer.online_user');
// }
public function onlinePost()
{
	$arr = [];
	$dealerid = Auth::user()->dealerid;
	$currentStatus = Auth::user()->status;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$trader_id = Auth::user()->trader_id;
	if ($currentStatus == "dealer") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join("radacct", "radacct.username", "radcheck.username")
		->where([
			"radcheck.dealerid" => $dealerid,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->where(["radacct.acctstoptime" => null])
		->get([
			"radacct.acctsessiontime",
			"radacct.acctstarttime",
			"radacct.framedipaddress",
			"radacct.acctoutputoctets",
			"radacct.acctinputoctets",
			"radacct.username",
			"radacct.callingstationid",
		]);
	} elseif ($currentStatus == "subdealer") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join("radacct", "radacct.username", "radcheck.username")
		->where([
			"radcheck.sub_dealer_id" => $sub_dealer_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->where(["radacct.acctstoptime" => null])
		->get([
			"radacct.acctsessiontime",
			"radacct.acctstarttime",
			"radacct.framedipaddress",
			"radacct.acctoutputoctets",
			"radacct.acctinputoctets",
			"radacct.username",
			"radacct.callingstationid",
		]);
// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	} elseif ($currentStatus == "trader") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join("radacct", "radacct.username", "radcheck.username")
		->where([
			"radcheck.trader_id" => $trader_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->where(["radacct.acctstoptime" => null])
		->get([
			"radacct.acctsessiontime",
			"radacct.acctstarttime",
			"radacct.framedipaddress",
			"radacct.acctoutputoctets",
			"radacct.acctinputoctets",
			"radacct.username",
			"radacct.callingstationid",
		]);
// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	} elseif (
		$currentStatus == "inhouse" &&
		Auth::user()->sub_dealer_id == ""
	) {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join("radacct", "radacct.username", "radcheck.username")
		->where([
			"radcheck.dealerid" => $dealerid,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->where(["radacct.acctstoptime" => null])
		->get([
			"radacct.acctsessiontime",
			"radacct.acctstarttime",
			"radacct.framedipaddress",
			"radacct.acctoutputoctets",
			"radacct.acctinputoctets",
			"radacct.username",
			"radacct.callingstationid",
		]);
// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	} elseif (
		$currentStatus == "inhouse" &&
		Auth::user()->sub_dealer_id != ""
	) {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join("radacct", "radacct.username", "radcheck.username")
		->where([
			"radcheck.sub_dealer_id" => $sub_dealer_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->where(["radacct.acctstoptime" => null])
		->get([
			"radacct.acctsessiontime",
			"radacct.acctstarttime",
			"radacct.framedipaddress",
			"radacct.acctoutputoctets",
			"radacct.acctinputoctets",
			"radacct.username",
			"radacct.callingstationid",
		]);
// $dhcp_server = Dhcp_dealer_server::where(['dealerid' => $dealerid])->first();
	}

	return Datatables::of($userDealer)
	->addColumn("sessionTime", function ($row) {
		$seconds = $row->acctsessiontime;
		$dtF = new DateTime("@0");
		$dtT = new DateTime("@$seconds");
		$onlineTime = $dtF
		->diff($dtT)
		->format("%aDays : %hHrs : %i Mins %s Secs");
		$datetime1 = new DateTime($row->acctstarttime);
		$datetime2 = new DateTime("now");
		$interval = $datetime1->diff($datetime2);
		$Day = $interval->format("%dD");
		if ($Day > 0) {
			$html = $interval->format("%dDays : %hHrs : %iMins");
		} else {
			$html = $interval->format("%hHrs : %iMins : %sSecs");
		}
		return $html;
	})
	->addColumn("dwUP", function ($row) {
		$size = $row->acctoutputoctets / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= " KB";
		} else {
			if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= " MB";
			} elseif ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= " GB";
			} elseif ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= " TB";
			} elseif ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format(
					$size / 1024 / 1024 / 1024 / 1024,
					2
				);
				$size .= " PB";
			}
		}
		$upload = preg_replace("/.00/", "", $size);

		$size = $row->acctinputoctets / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= " KB";
		} else {
			if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= " MB";
			} elseif ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= " GB";
			} elseif ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= " TB";
			} elseif ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format(
					$size / 1024 / 1024 / 1024 / 1024,
					2
				);
				$size .= " PB";
			}
		}
		$down = preg_replace("/.00/", "", $size);

		$html = $upload . "/" . $down;
		return $html;
	})
	->addColumn("action", function ($row) {
		$html =
		'<button onclick="onlineUserDetail(' .
		"'$row->callingstationid'" .
		"," .
		"'$row->username'" .
		')" data-toggle="modal" class="btn btn-info btn-xs" style="border-radius:7px;"><i class="fa fa-eye"></i> View Details </button> ';
		return $html;
	})
	->addIndexColumn()
	->make(true);
}
public function onlineUserDetails(Request $request)
{
	$mac = $request->mac;
	$username = $request->username;
	$dealerid = Auth::user()->dealerid;
	$details = UserInfo::where("username", $username)
	->select(
		"username",
		"firstname",
		"lastname",
		"dealerid",
		"address",
		"sub_dealer_id"
	)
	->first();

	$mac = $request->mac;
	$url='http://cron.lbi.net.pk/mikrotik_api/api.php?mac='.$mac.'&dealerid='.$dealerid;
	$url =
	"http://cron.lbi.net.pk/logoncp_cron/dhcp_api/api.php?mac=" .
	$mac .
	"&dealerid=" .
	$dealerid;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$url");
//
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);

// return $result;

	return response()->json([
		"result" => $result,
		"details" => $details,
	]);
}

// public function offlineUserView(){
// 	return view('users.dealer.offline_user');
// }
public function offlinePost()
{
	$arr = [];
	$dealerid = Auth::user()->dealerid;
	$currentStatus = Auth::user()->status;
	$sub_dealer_id = Auth::user()->sub_dealer_id;
	$trader_id = Auth::user()->trader_id;
	$data = [];
	if ($currentStatus == "dealer") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join(
			"radusergroup",
			"radcheck.username",
			"radusergroup.username"
		)
		->whereNotIn("radusergroup.groupname", [
			"EXPIRED",
			"TERMINATE",
			"NEW",
			"DISABLED",
		])
		->where([
			"radcheck.dealerid" => $dealerid,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->get(["radcheck.username"]);

		foreach ($userDealer as $key => $value) {
			$query = DB::connection("mysql1")
			->table("radacct")
			->where("username", $value->username)
			->orderby("radacct.radacctid", "DESC")
			->first([
				"radacct.acctsessiontime",
				"radacct.acctstarttime",
				"radacct.framedipaddress",
				"radacct.acctoutputoctets",
				"radacct.acctinputoctets",
				"radacct.username",
				"radacct.callingstationid",
				"radacct.acctstoptime",
			]);
			if (@$query->acctstoptime == null) {
			} else {
				array_push($data, $query);
			}
		}
// dd($data);
	} elseif ($currentStatus == "subdealer") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join(
			"radusergroup",
			"radcheck.username",
			"radusergroup.username"
		)
		->whereNotIn("radusergroup.groupname", [
			"EXPIRED",
			"TERMINATE",
			"NEW",
			"DISABLED",
		])
		->where([
			"radcheck.sub_dealer_id" => $sub_dealer_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->get(["radcheck.username"]);

		foreach ($userDealer as $key => $value) {
			$query = DB::connection("mysql1")
			->table("radacct")
			->where("username", $value->username)
			->orderby("radacct.radacctid", "DESC")
			->first([
				"radacct.acctsessiontime",
				"radacct.acctstarttime",
				"radacct.framedipaddress",
				"radacct.acctoutputoctets",
				"radacct.acctinputoctets",
				"radacct.username",
				"radacct.callingstationid",
				"radacct.acctstoptime",
			]);
			if (@$query->acctstoptime == null) {
			} else {
				array_push($data, $query);
			}
		}
	} elseif ($currentStatus == "trader") {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join(
			"radusergroup",
			"radcheck.username",
			"radusergroup.username"
		)
		->whereNotIn("radusergroup.groupname", [
			"EXPIRED",
			"TERMINATE",
			"NEW",
			"DISABLED",
		])
		->where([
			"radcheck.trader_id" => $trader_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->get(["radcheck.username"]);

		foreach ($userDealer as $key => $value) {
			$query = DB::connection("mysql1")
			->table("radacct")
			->where("username", $value->username)
			->orderby("radacct.radacctid", "DESC")
			->first([
				"radacct.acctsessiontime",
				"radacct.acctstarttime",
				"radacct.framedipaddress",
				"radacct.acctoutputoctets",
				"radacct.acctinputoctets",
				"radacct.username",
				"radacct.callingstationid",
				"radacct.acctstoptime",
			]);
			if (@$query->acctstoptime == null) {
			} else {
				array_push($data, $query);
			}
		}
	} elseif (
		$currentStatus == "inhouse" &&
		Auth::user()->sub_dealer_id == null
	) {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join(
			"radusergroup",
			"radcheck.username",
			"radusergroup.username"
		)
		->whereNotIn("radusergroup.groupname", [
			"EXPIRED",
			"TERMINATE",
			"NEW",
			"DISABLED",
		])
		->where([
			"radcheck.dealerid" => $dealerid,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->get(["radcheck.username"]);

		foreach ($userDealer as $key => $value) {
			$query = DB::connection("mysql1")
			->table("radacct")
			->where("username", $value->username)
			->orderby("radacct.radacctid", "DESC")
			->first([
				"radacct.acctsessiontime",
				"radacct.acctstarttime",
				"radacct.framedipaddress",
				"radacct.acctoutputoctets",
				"radacct.acctinputoctets",
				"radacct.username",
				"radacct.callingstationid",
				"radacct.acctstoptime",
			]);
			if (@$query->acctstoptime == null) {
			} else {
				array_push($data, $query);
			}
		}
	} elseif (
		$currentStatus == "inhouse" &&
		Auth::user()->sub_dealer_id != ""
	) {
		$userDealer = DB::connection("mysql1")
		->table("radcheck")
		->join(
			"radusergroup",
			"radcheck.username",
			"radusergroup.username"
		)
		->whereNotIn("radusergroup.groupname", [
			"EXPIRED",
			"TERMINATE",
			"NEW",
			"DISABLED",
		])
		->where([
			"radcheck.sub_dealer_id" => $sub_dealer_id,
			"radcheck.status" => "user",
			"radcheck.attribute" => "Cleartext-Password",
		])
		->get(["radcheck.username"]);

		foreach ($userDealer as $key => $value) {
			$query = DB::connection("mysql1")
			->table("radacct")
			->where("username", $value->username)
			->orderby("radacct.radacctid", "DESC")
			->first([
				"radacct.acctsessiontime",
				"radacct.acctstarttime",
				"radacct.framedipaddress",
				"radacct.acctoutputoctets",
				"radacct.acctinputoctets",
				"radacct.username",
				"radacct.callingstationid",
				"radacct.acctstoptime",
			]);
			if (@$query->acctstoptime == null) {
			} else {
				array_push($data, $query);
			}
		}
	}

	return Datatables::of($data)
	->addColumn("sessionTime", function ($row) {
		$seconds = $row->acctsessiontime;
		$dtF = new DateTime("@0");
		$dtT = new DateTime("@$seconds");
		$onlineTime = $dtF
		->diff($dtT)
		->format("%aDays : %hHrs : %i Mins %s Secs");
		$datetime1 = new DateTime($row->acctstarttime);
		$datetime2 = new DateTime("now");
		$interval = $datetime1->diff($datetime2);
		$Day = $interval->format("%dD");
		if ($Day > 0) {
			$html = $interval->format("%dDays : %hHrs : %iMins");
		} else {
			$html = $interval->format("%hHrs : %iMins : %sSecs");
		}
		return $html;
	})
	->addColumn("dwUP", function ($row) {
		$size = $row->acctoutputoctets / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= " KB";
		} else {
			if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= " MB";
			} elseif ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= " GB";
			} elseif ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= " TB";
			} elseif ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format(
					$size / 1024 / 1024 / 1024 / 1024,
					2
				);
				$size .= " PB";
			}
		}
		$upload = preg_replace("/.00/", "", $size);

		$size = $row->acctinputoctets / 1024;
		if ($size < 1024) {
			$size = number_format($size, 2);
			$size .= " KB";
		} else {
			if ($size / 1024 < 1024) {
				$size = number_format($size / 1024, 2);
				$size .= " MB";
			} elseif ($size / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024, 2);
				$size .= " GB";
			} elseif ($size / 1024 / 1024 / 1024 < 1024) {
				$size = number_format($size / 1024 / 1024 / 1024, 2);
				$size .= " TB";
			} elseif ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
				$size = number_format(
					$size / 1024 / 1024 / 1024 / 1024,
					2
				);
				$size .= " PB";
			}
		}
		$down = preg_replace("/.00/", "", $size);

		$html = $upload . "/" . $down;
		return $html;
	})
	->addColumn("action", function ($row) {
		$html =
		'<button onclick="onlineUserDetail(' .
		"'$row->callingstationid'" .
		"," .
		"'$row->username'" .
		')" data-toggle="modal" class="btn btn-info btn-xs" style="border-radius:7px;"><i class="fa fa-eye"></i> View Details </button> ';
		return $html;
	})
	->addIndexColumn()
	->make(true);
}
public function offlineUserDetails(Request $request)
{
	$mac = $request->mac;
	$username = $request->username;
	$dealerid = Auth::user()->dealerid;
	$details = UserInfo::where("username", $username)
	->select(
		"username",
		"firstname",
		"lastname",
		"dealerid",
		"address",
		"sub_dealer_id"
	)
	->first();

	$mac = $request->mac;
	$url='http://cron.lbi.net.pk/mikrotik_api/api.php?mac='.$mac.'&dealerid='.$dealerid;
	$url =
	"http://cron.lbi.net.pk/logoncp_cron/dhcp_api/api.php?mac=" .
	$mac .
	"&dealerid=" .
	$dealerid;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "$url");
//
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);

// return $result;

	return response()->json([
		"result" => $result,
		"details" => $details,
	]);
}


public function disableUserView()
{
    return view("users.dealer.disable_user");
}
public function disableUserViewdatatable(Request $request)
{
        // Initialize variables
    $arr = [];
    $whereArray = [];
    $dealerids = [];
    $currentStatus = Auth::user()->status;
    $manager_id = Auth::user()->manager_id ?? null;
    $resellerid = Auth::user()->resellerid ?? null;
    $dealerid = Auth::user()->dealerid ?? null;
    $sub_dealer_id = Auth::user()->sub_dealer_id ?? null;
    $trader_id = Auth::user()->trader_id ?? null;

        // Handle DataTable parameters
        $start = $request->input('start', 0); // Get the start index for pagination (default to 0 if not provided)
        $length = $request->input('length', 10); // Get the number of records per page (default to 10 if not provided)
        $search = optional($request->input('search'))['value'] ?? ''; // Safely get search value or default to empty string

        // Apply filters based on current user status
        if ($manager_id) {
            array_push($whereArray, ['user_info.manager_id', $manager_id]);
        }
        if ($resellerid) {
            array_push($whereArray, ['user_info.resellerid', $resellerid]);
        }
        if ($dealerid) {
            array_push($whereArray, ['user_info.dealerid', $dealerid]);
        }
        if ($sub_dealer_id) {
            array_push($whereArray, ['user_info.sub_dealer_id', $sub_dealer_id]);
        }

        // Start building the query
        $userQuery = UserInfo::where($whereArray)
        ->where('status', 'user')
        ->whereIn('username', function($query){
            $query->select('username')
            ->from('disabled_users')
            ->where('status', 'disable');
        })
        ->select('username');

        // Apply search filter if provided
        if ($search) {
            $userQuery->where(function($query) use ($search) {
                $query->where('user_info.username', 'like', "%$search%")
                ->orWhere('user_info.firstname', 'like', "%$search%")
                ->orWhere('user_info.lastname', 'like', "%$search%");
            });
        }

        // Get total records before pagination
        $totalRecords = $userQuery->count();

        // Apply pagination
        $users = $userQuery->skip($start)->take($length)->get();

        $data = [];
        $count = $request->input('start') + 1;
        foreach ($users as $user) {
            $userDetail = UserInfo::join('disabled_users', 'disabled_users.username', '=', 'user_info.username')
            ->where('user_info.username', $user->username)
            ->select(
                'user_info.id',
                'user_info.username',
                'user_info.firstname',
                'user_info.lastname',
                'user_info.address',
                'user_info.mobilephone',
                'user_info.sub_dealer_id',
                'user_info.dealerid',
                'disabled_users.updated_by'
            )
            ->first();

            $fname = $userDetail->firstname;
            $lname = $userDetail->lastname;
            $mobile = $userDetail->mobilephone;
            $address = $userDetail->address;
            $disable_by = $userDetail->updated_by;

            // Handle disable_by logic
            $by = '';
            if ($disable_by == 'dealer') {
                $by = $userDetail->dealerid;
                $disable_by = "Contractor";
            } elseif ($disable_by == 'reseller') {
                $by = $userDetail->resellerid;
                $disable_by = "Reseller";
            } elseif ($disable_by == 'subdealer') {
                $by = $userDetail->sub_dealer_id;
                $disable_by = "Trader";
            }

            //
            $userExpire = UserStatusInfo::where('username', $user->username)->first();
            $expire_date = $userExpire['expire_datetime'];
            $cur_date = date('Y-m-d H:i:s');
            $color = ($expire_date < $cur_date) ? 'red' : 'black';
            $csrf = csrf_token();
            $data[] = [
                'serial_number' => $count++,
                'username' => $user->username,
                'fname' => $fname,
                'lname' => $lname,
                'address' => $address,
                'mobile' => $mobile,
                'mobile' =>'<a class="text-dark" href="tel:'.$mobile.'"><i class="fa fa-phone"></i> '.$mobile.'</a>',
                'disable_by' => $by . '</br><small>'. $disable_by.'</small>',
                'sub_dealer_id' => ($userDetail->sub_dealer_id) ? $userDetail->sub_dealer_id.'</br><small>Trader</small>' : $userDetail->dealerid.'</br><small>Contractor</small>' ,
                'color' => $color,
                'action' => ($currentStatus == 'dealer' || $currentStatus == 'subdealer') ? '<form action="/users/enableuser" method="POST" >
                <input type="hidden" name="_token" value="'.$csrf .'">
                <input type="hidden" name="username" value="'.$userDetail->username.'" >
                <input type="hidden" name="userid" value="'.$userDetail->id.'">
                <button type="submit" id="disableBtn" value="enable" onclick="return confirmEnable();" class=" mb1 bg-olive btn-sm btn btn-success" style="border-radius:5px;">Enable</button>
                </form>' : 'N/A',
            ];
        }

        // Return response for DataTable
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }
    public function enableuser(Request $request)
    {
        $username = $request->get("username");
        $userid = $request->get("userid");
        $getprofile = RaduserGroup::select("groupname")
        ->where(["username" => $username])
        ->first();
        $profile = $getprofile->groupname;
        if ($profile == "DISABLED") {
            $userInfo = UserInfo::where(["username" => $username])->first();
            $oldprofile = $userInfo->disabled_old_profile;
            $userInfo->profile = $oldprofile;
            $userInfo->disabled_old_profile = null;
            $userInfo->save();
            $radusergroup = RaduserGroup::where([
                "username" => $username,
            ])->first();
            $package = $oldprofile;
            $radusergroup->groupname = $package;
            $radusergroup->save();
            $disabled_user = DisabledUser::where([
                "username" => $username,
            ])->first();
            $disabled_user->status = "enable";
            $disabled_user->updated_by = Auth::user()->status;
            $disabled_user->last_update = date("Y-m-d H:i:s");
            $disabled_user->save();
        }
        $url = "https://api-radius.logon.com.pk/kick/user-dc-api.php?username=" . $username;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        return redirect()->route("users.disableUserView", [
            "status" => "user",
            "id" => $userid,
        ]);
    }


    public function mobvarifiedUserView($status)
    {
        return view("users.dealer.mobvarified_user");
    }
    public function mobVerifiedUserDataTable(Request $request)
    {
        $whereArray = [];
        $manager_id = Auth::user()->manager_id ?? null;
        $resellerid = Auth::user()->resellerid ?? null;
        $dealerid = Auth::user()->dealerid ?? null;
        $sub_dealer_id = Auth::user()->sub_dealer_id ?? null;

        // Build dynamic query conditions
        if ($manager_id) array_push($whereArray, ['user_info.manager_id', $manager_id]);
        if ($resellerid) array_push($whereArray, ['user_info.resellerid', $resellerid]);
        if ($dealerid) array_push($whereArray, ['user_info.dealerid', $dealerid]);
        if ($sub_dealer_id) array_push($whereArray, ['user_info.sub_dealer_id', $sub_dealer_id]);

        // Initialize the query
        $query = UserInfo::where($whereArray)
        ->where('status', 'user')
        ->whereIn('user_info.username', function ($subQuery) {
            $subQuery->select('username')
            ->from('user_verification');
        })
        ->join('user_verification', 'user_verification.username', '=', 'user_info.username');

        // Apply mobile verification filter
        if ($request->has('service_filter') && $request->input('service_filter') !== '') {
            if ($request->input('service_filter') === 'Verified') {
                $query->whereNotNull('user_verification.mobile');
            } elseif ($request->input('service_filter') === 'Not Verified') {
                $query->whereNull('user_verification.mobile');
            }
        }

        // Get total records before filtering
        $totalRecords = $query->count();

        // Apply pagination
        $users = $query->select(
            'user_info.username',
            'user_info.firstname',
            'user_info.lastname',
            'user_info.address',
            'user_info.sub_dealer_id',
            'user_info.dealerid',
            'user_info.creationdate',
            'user_verification.mobile',
            'user_verification.cnic'
        )
        ->offset($request->input('start'))
        ->limit($request->input('length'))
        ->get();

        // Prepare data for DataTable
        $data = [];
        $count = $request->input('start') + 1;

        foreach ($users as $user) {
            $status = $user->mobile ? 'Verified' : 'Not Verified';
            // $sub_dealer_id = $user->sub_dealer_id ?: $user->dealerid;
            $user->mobile = $user->mobile ?? 'N/A';
            $user->cnic = $user->cnic ?? 'N/A';

            $data[] = [
                'serial_number' => $count++,
                'username' => $user->username,
                'fullname' => $user->firstname . ' ' . $user->lastname,
                'address' => $user->address,
                'mobile' => '<a class="text-dark" href="tel:'.$user->mobile.'"><i class="fa fa-phone"></i> '.$user->mobile.'</a>',
                'cnic' => $user->cnic,
                'sub_dealer_id' => $user->dealerid.' <br><small>'.$user->sub_dealer_id.'</small>',
                'status' => $status,
                'status_sort' => $user->mobile ? 1 : 0, // Sorting by verification status
                'action' => $this->generateActionButtonmob($user->username, $status),
                'creationdate' => date('M d,Y',strtotime($user->creationdate)),
            ];
        }

        // Return response
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // Adjusted by filters
            'data' => $data
        ]);
    }
    // Helper method to generate action button
    private function generateActionButtonmob($username, $status)
    {
        if ($status === 'Not Verified') {
            if(Auth::user()->status == 'reseller'){
                return  '<span style="color:red; font-weight:bold"><i class="las la-exclamation-triangle" ></i> <span class="">' . $status . '</span>' .'</span>';
            }
            else{
                return '<form action="/users/smsverify" method="POST" style="display:inline">' .
                csrf_field() .
                '<input type="hidden" name="username" value="' . $username . '">' .
                '<button type="submit" class="btn-status not-verified">' .
                '<i class="las la-exclamation-triangle"></i> <span>' . $status . '</span>' .
                '</button>' .
                '</form>';
            }
        }
        return '<span class="status verified">' .
        '<i class="la la-check"></i> MOBILE (' . $status . ')' .
        '</span>';
    }

    public function cnicvarifiedUserView($status)
    {
        return view("users.dealer.cnicvarified_user");
    }
    public function cnicVerifiedUserDataTable(Request $request)
    {
        $whereArray = [];
        $manager_id = Auth::user()->manager_id ?? null;
        $resellerid = Auth::user()->resellerid ?? null;
        $dealerid = Auth::user()->dealerid ?? null;
        $sub_dealer_id = Auth::user()->sub_dealer_id ?? null;

        // Build dynamic query conditions
        if ($manager_id) array_push($whereArray, ['user_info.manager_id', $manager_id]);
        if ($resellerid) array_push($whereArray, ['user_info.resellerid', $resellerid]);
        if ($dealerid) array_push($whereArray, ['user_info.dealerid', $dealerid]);
        if ($sub_dealer_id) array_push($whereArray, ['user_info.sub_dealer_id', $sub_dealer_id]);

        // Initialize the query
        $query = UserInfo::where($whereArray)
        ->where('status', 'user')
        ->whereIn('user_info.username', function ($subQuery) {
            $subQuery->select('username')
            ->from('user_verification');
        })
        ->join('user_verification', 'user_verification.username', '=', 'user_info.username');

        // Apply CNIC verification filter
        if ($request->has('service_filter') && $request->input('service_filter') !== '') {
            if ($request->input('service_filter') === 'Verified') {
                $query->whereNotNull('user_verification.cnic');
            } elseif ($request->input('service_filter') === 'Not Verified') {
                $query->whereNull('user_verification.cnic');
            }
        }

        // Get total records before filtering
        $totalRecords = $query->count();

        // Apply pagination
        $users = $query->select(
            'user_info.username',
            'user_info.firstname',
            'user_info.lastname',
            'user_info.address',
            'user_info.sub_dealer_id',
            'user_info.dealerid',
            'user_info.creationdate',
            'user_verification.mobile',
            'user_verification.cnic'
        )
        ->offset($request->input('start'))
        ->limit($request->input('length'))
        ->get();

        // Prepare data for DataTable
        $data = [];
        $count = $request->input('start') + 1;

        foreach ($users as $user) {
            $cnic_status = $user->cnic ? 'Verified' : 'Not Verified';
            // $sub_dealer_id = $user->sub_dealer_id ?: $user->dealerid;
            // $user->mobile = $user->mobile ?? 'N/A';
            $user->mobile = $user->mobile ? '<a class="text-dark" href="tel:'.$user->mobile.'"><i class="fa fa-phone"></i> '.$user->mobile.'</a>' : 'N/A';
            $user->cnic = $user->cnic ?? 'N/A';
            //
            $data[] = [
                'serial_number' => $count++,
                'username' => $user->username,
                'fullname' => $user->firstname . ' ' . $user->lastname,
                'address' => $user->address,
                'mobile' => $user->mobile,
                'cnic' => $user->cnic,
                'sub_dealer_id' => $user->dealerid.' <br><small>'.$user->sub_dealer_id.'</small>',
                'status' => $cnic_status,
                'status_sort' => $user->cnic ? 1 : 0, // Sorting by CNIC verification
                'action' => $this->generateActionButtoncnic($user->username, $cnic_status),
                'creationdate' => date('M d,Y',strtotime($user->creationdate)),
            ];
        }

        // Return response
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords, // Adjusted by filters
            'data' => $data
        ]);
    }
    // Helper method to generate action button
    private function generateActionButtoncnic($username, $cnic_status)
    {
        $buttons = '';
        // CNIC verification button
        if ($cnic_status === 'Not Verified') {
            if(Auth::user()->status == 'reseller'){
                return  '<span style="color:red; font-weight:bold"><i class="las la-exclamation-triangle" ></i> <span class="">' . $cnic_status . '</span>' .'</span>';
            }
            else{

                $buttons .= '<form action="/users/nicVerify" method="POST" style="display:inline">' .
                csrf_field() .
                '<input type="hidden" name="username" value="' . $username . '">' .
                '<button type="submit" class="btn-status not-verified">' .
                '<i class="las la-exclamation-triangle"></i> <span> ' . $cnic_status .'</span>' .
                '</button>' .
                '</form>';
            }
        } else {
            $buttons .= '<span class="status verified">' .
            '<i class="la la-check"></i> ' . $cnic_status .
            '</span>';
        }

        return $buttons;
    }


    public function ByteSize($bytes)
    {
     $size = $bytes / 1024;
     if ($size < 1024) {
      $size = number_format($size, 2);
      $size .= " KB";
  } else {
      if ($size / 1024 < 1024) {
       $size = number_format($size / 1024, 2);
       $size .= " MB";
   } elseif ($size / 1024 / 1024 < 1024) {
       $size = number_format($size / 1024 / 1024, 2);
       $size .= " GB";
   } elseif ($size / 1024 / 1024 / 1024 < 1024) {
       $size = number_format($size / 1024 / 1024 / 1024, 2);
       $size .= " TB";
   } elseif ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
       $size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
       $size .= " PB";
   }
}
$size = preg_replace("/.00/", "", $size);
return $size;
}

public function store(Request $request, $status)
{

    if(MyFunctions::is_freezed(Auth::user()->username)){
        Session()->flash("error", "Your panel has been freezed");
        return back();
    }


    switch ($status) {
      case "reseller":

//partner themes 30 Jan 2023 anwaar

      $user_theme = new PartnerThemesUser();
      $user_theme->username = $request->get("resellerid");
      $user_theme->color = $request->get("theme_color");
      $user_theme->login_alignment = $request->get("login_alignment");
      $user_theme->save();
// validation
      $this->validate($request, [
         "username" => "required|unique:user_info",
         "manager_id" => "required",
         "theme_color" => "required",
         "fname" => "required",
         "lname" => "required",
         "address" => "required",
         "mobile_number" => "required",
         "land_number" => "required",
         "nic" => "required",
         "mail" => "required",
         "area" => "required",
         "username" => "required",
         "city" => "required",
         "domain" => "required",
         "packageName" => "required",
         "slogan" => "required",
         "logo" => "required",
         "bgImage" => "required",
         "theme_color" => "required",
         "login_alignment" => "required",
         "mheading" => "required",
         "resellerid" => "required|unique:user_info",
         "password" => "required|confirmed",

     ]);
      if($request->hasFile('logo')){
         $logo = $request->file('logo');
         $image_name = $logo->getClientOriginalName();
         $logo->move(public_path('/Login/images'),$image_name);

     }
     if($request->hasFile('bgImage')){
         $bgImage = $request->file('bgImage');
         $image_name = $bgImage->getClientOriginalName();
         $bgImage->move(public_path('/Login/images'),$image_name);

     }


     /* admin module end */


     /* original entries 27 jan 20223 */

// Domain Table Entry
     $domain = new Domain();
     $domain->domainname = $request->domain;
     $domain->resellerid = $request->resellerid;
     $domain->package_name = $request->packageName;
     $domain->logo = $request->logo;
     $domain->slogan = $request->slogan;
     $domain->powerdBy = $request->powerby;
     $domain->main_heading = $request->mheading;
     $domain->bg_image = $request->bgImage;
     $domain->theme_color = '60deg,#'.$request->color1.',#'.$request->color2;
     $domain->save();
     $domainID = $domain->id;

     $user = new UserInfo();

     $user->active = 1;
     $user->username = $request->get("username");
     $user->domainID = $domainID;
     $user->manager_id = $request->get("manager_id");
     $user->resellerid = $request->get("resellerid");
     $user->password = Hash::make($request->get("password"));
     $user->dealerid = null;
     $user->sub_dealer_id = null;
     $user->trader_id = null;
     $user->profile = "CONTRACTOR";
$user->name = "CONTRACTOR"; // by default profile
// default amount is zero;
$userAmount = new UserAmount();
$userAmount->username = $request->get("username");
$userAmount->status = "reseller";
$userAmount->amount = 0;
$userAmount->credit_limit = 0;

//assigned server
$nas_type1 = AssignNasType::where([
	"manager_id" => Auth::user()->manager_id,
])->first();
$assignas = $nas_type1->nas_type;
$server = new AssignNasType();
$server->dealerid = "";
$server->resellerid = $request->get("resellerid");
$server->manager_id = $request->get("manager_id");
$server->sub_dealer_id = null;
$server->trader_id = null;
$server->nas_type = $assignas;
$server->save();
// saving in redCheck: entry - 1
$RadCheck = new RadCheck();
$RadCheck->username = $request->get("username");
$RadCheck->attribute = "Cleartext-Password";
$RadCheck->op = ":=";
$RadCheck->value = $request->get("password");
$RadCheck->resellerid = $request->get("resellerid");
$RadCheck->manager_id = $request->get("manager_id");
$RadCheck->status = "reseller";
$RadCheck->dealerid = null;
$RadCheck->sub_dealer_id = null;
$RadCheck->trader_id = null;
$RadCheck->svlan = null;

////
// saving in redCheck: entry - 2
$RadCheck2 = new RadCheck();
$RadCheck2->username = $request->get("username");
$RadCheck2->attribute = "Simultaneous-Use";
$RadCheck2->op = ":=";
$RadCheck2->value = "1";
$RadCheck2->resellerid = $request->get("resellerid");
$RadCheck2->manager_id = $request->get("manager_id");
$RadCheck2->status = "reseller";
$RadCheck2->dealerid = null;
$RadCheck2->sub_dealer_id = null;
$RadCheck2->trader_id = null;
$RadCheck2->svlan = null;

///
// saving in redCheck: entry - 3
$RadCheck3 = new RadCheck();
$RadCheck3->username = $request->get("username");
$RadCheck3->attribute = "Calling-Station-Id";
$RadCheck3->op = ":=";
$RadCheck3->value = "NEW";
$RadCheck3->resellerid = $request->get("resellerid");
$RadCheck3->manager_id = $request->get("manager_id");
$RadCheck3->status = "reseller";
$RadCheck3->dealerid = null;
$RadCheck3->sub_dealer_id = null;
$RadCheck3->trader_id = null;
$RadCheck3->svlan = null;

///

///////Getting IP from usualIP

$userusualIPs = UserUsualIP::where(["status" => "0"])->first();

$ip = $userusualIPs->ip; // 23 jan original



$radreply = new Radreply();
$radreply->username = $request->get("username");
$radreply->attribute = "Framed-IP-Address";
$radreply->op = "=";
$radreply->value = $ip;
$radreply->dealerid = null;
$radreply->resellerid = $request->get("resellerid");
$radreply->sub_dealer_id = null;
$radreply->manager_id = $request->get("manager_id");
$radreply->trader_id = null;


if(!empty($userusualIPs->status)){
	$userusualIPs->status = "1";
}

$radusergroup = new RaduserGroup();
$radusergroup->username = $request->get("username");
$radusergroup->groupname = "CONTRACTOR";
$radusergroup->name = "CONTRACTOR";
$radusergroup->priority = "0";


$useripstatus = new UserIPStatus();
$useripstatus->username = $request->get("username");
$useripstatus->ip = $ip;
$useripstatus->type = "usual_ip";

DB::transaction(function () use (
	$useripstatus,
	$radusergroup,
	$userusualIPs,
	$radreply,
	$RadCheck,
	$RadCheck2,
	$RadCheck3,
	$userAmount
) {
	$radreply->save();
	$useripstatus->save();
	$radusergroup->save();
	$userusualIPs->save();
	$RadCheck->save();
	$RadCheck2->save();
	$RadCheck3->save();
	$userAmount->save();

});

break;
case "dealer":
$active = 0;
// validation
$this->validate($request, [
	"username" => "required|unique:user_info",
	"manager_id" => "required",
	"resellerid" => "required",
	"dealerid" => "required|unique:user_info",
	"password" => "required|confirmed",
    "dealer_nas" => "required",
]);
//
$contractor_profile = 'NEW';
$domainManagement = Domain::where('resellerid',$request->get("resellerid"))->first();
//
if(!empty($request->get("dealer_nas"))){
    $CTprof = ContractorTraderProfile::where('nas_shortname',$request->get("dealer_nas"))->first();
    if($CTprof){
        $contractor_profile =  $CTprof->contractor_profile;
    }
}
//
$user = new UserInfo();
//
$user->username = $request->get("username");
$user->manager_id = $request->get("manager_id");
$user->resellerid = $request->get("resellerid");
$user->dealerid = $request->get("dealerid");
$user->password = Hash::make($request->get("password"));
$user->sub_dealer_id = null;
$user->trader_id = null;
$user->profile = $contractor_profile;
$user->name = $contractor_profile;
$user->domainID = $domainManagement->id;
$user->state = $request->get("state");
// default amount is zero;
$userAmount = new UserAmount();
$userAmount->username = $request->get("username");
$userAmount->status = "dealer";
$userAmount->amount = 0;
$userAmount->credit_limit = 0;

//
if ($request->hasFile('cnic_front')) {
    $cnic_front = $request->file('cnic_front');
    $cnic_front_name = $request->get("dealerid") . '-cnic_front.jpg';
    $cnic_front->move(public_path('Dealer-NIC/'), $cnic_front_name);
}
if ($request->hasFile('cnic_back')) {
    $cnic_back  = $request->file('cnic_back');
    $cnic_back_name = $request->get("dealerid") . '-cnic_back.jpg';
    $cnic_back->move(public_path('Dealer-NIC/'), $cnic_back_name);
}
//

$assignas = $request->get("dealer_nas");

//
$assignedNas = new AssignedNas();
$assignedNas->id = $request->get("dealerid");
$assignedNas->nas = $request->get("dealer_nas");
$assignedNas->save();

// saving in redCheck: entry - 1
$RadCheck = new RadCheck();
$RadCheck->username = $request->get("username");
$RadCheck->attribute = "Cleartext-Password";
$RadCheck->op = ":=";
$RadCheck->value = $request->get("password");
$RadCheck->resellerid = $request->get("resellerid");
$RadCheck->manager_id = $request->get("manager_id");
$RadCheck->status = "dealer";
$RadCheck->dealerid = $request->get("dealerid");
$RadCheck->sub_dealer_id = null;
$RadCheck->trader_id = null;
$RadCheck->svlan = $request->get("dealerid");

////
// saving in redCheck: entry - 2
$RadCheck2 = new RadCheck();
$RadCheck2->username = $request->get("username");
$RadCheck2->attribute = "Simultaneous-Use";
$RadCheck2->op = ":=";
$RadCheck2->value = "1";
$RadCheck2->resellerid = $request->get("resellerid");
$RadCheck2->manager_id = $request->get("manager_id");
$RadCheck2->status = "dealer";
$RadCheck2->dealerid = $request->get("dealerid");
$RadCheck2->sub_dealer_id = null;
$RadCheck2->trader_id = null;
$RadCheck2->svlan = $request->get("dealerid");

///
// saving in redCheck: entry - 3
$RadCheck3 = new RadCheck();
$RadCheck3->username = $request->get("username");
$RadCheck3->attribute = "Calling-Station-Id";
$RadCheck3->op = ":=";
$RadCheck3->value = "NEW";
$RadCheck3->resellerid = $request->get("resellerid");
$RadCheck3->manager_id = $request->get("manager_id");
$RadCheck3->status = "dealer";
$RadCheck3->dealerid = $request->get("dealerid");
$RadCheck3->sub_dealer_id = null;
$RadCheck3->trader_id = null;
$RadCheck3->svlan = $request->get("dealerid");

///

///

///////Getting IP from usualIP
$userusualIPs = UserUsualIP::where(["status" => "0"])->where(["nas" => $assignas])->first();
$ip = $userusualIPs->ip;

// inserting ip in radreply
$radreply = new Radreply();
$radreply->username = $request->get("username");
$radreply->attribute = "Framed-IP-Address";
$radreply->op = "=";
$radreply->value = $ip;
$radreply->dealerid = $request->get("dealerid");
$radreply->resellerid = $request->get("resellerid");
$radreply->sub_dealer_id = null;
$radreply->manager_id = $request->get("manager_id");
$radreply->trader_id = null;

// changing status to 1 of got ip in usualIP
$userusualIPs->status = "1";

// inserting ip in radusergroup
$radusergroup = new RaduserGroup();
$radusergroup->username = $request->get("username");
$radusergroup->groupname = $contractor_profile;
$radusergroup->name = $contractor_profile;
$radusergroup->priority = "0";

////inserting into useripstatus

$useripstatus = new UserIPStatus();
$useripstatus->username = $request->get("username");
$useripstatus->ip = $ip;
$useripstatus->type = "usual_ip";

//cacti graph

$graph = new CactiGraph();
$graph->user_id = $request->get("username");
$graph->graph_no = "0";

///cacti graph end

DB::transaction(function () use (
	$useripstatus,
	$radusergroup,
	$userusualIPs,
	$radreply,
	$RadCheck,
	$RadCheck2,
	$RadCheck3,
	$graph,
	$userAmount
) {
	$radreply->save();
	$useripstatus->save();
	$radusergroup->save();
	$userusualIPs->save();
	$RadCheck->save();
	$RadCheck2->save();
	$RadCheck3->save();
	$graph->save();
	$userAmount->save();

/*
* insert new record for question category
*/
});
break;
case "subdealer":
$active = 0;
// validation
$this->validate($request, [
	"username" => "required|unique:user_info",
	"manager_id" => "required",
	"resellerid" => "required",
	"dealerid" => "required",
	"sub_dealer_id" => "required|unique:user_info",
	"password" => "required|confirmed",
]);
//
$domainManagement = Domain::where('resellerid',$request->get("resellerid"))->first();
//
$user = new UserInfo();

$user->username = $request->get("username");
$user->manager_id = $request->get("manager_id");
$user->resellerid = $request->get("resellerid");
$user->dealerid = $request->get("dealerid");
$user->password = Hash::make($request->get("password"));
$user->sub_dealer_id = $request->get("sub_dealer_id");
$user->trader_id = null;
$user->profile = $domainManagement->trader_profile;
$user->name = $domainManagement->trader_profile;
$user->domainID = $domainManagement->id;
// default amount is zero;
$userAmount = new UserAmount();
$userAmount->username = $request->get("username");
$userAmount->status = "subdealer";
$userAmount->amount = 0;
$userAmount->credit_limit = 0;

//assigned server

$nas_type1 = AssignNasType::where([
	"manager_id" => Auth::user()->manager_id,
])->first();

$assignas = $nas_type1->nas_type;

$server = new AssignNasType();
$server->dealerid = $request->get("dealerid");
$server->resellerid = $request->get("resellerid");
$server->manager_id = $request->get("manager_id");
$server->sub_dealer_id = $request->get("sub_dealer_id");
$server->trader_id = null;
$server->nas_type = $assignas;
$server->save();

// saving in redCheck: entry - 1
$RadCheck = new RadCheck();
$RadCheck->username = $request->get("username");
$RadCheck->attribute = "Cleartext-Password";
$RadCheck->op = ":=";
$RadCheck->value = $request->get("password");
$RadCheck->resellerid = $request->get("resellerid");
$RadCheck->manager_id = $request->get("manager_id");
$RadCheck->status = "subdealer";
$RadCheck->dealerid = $request->get("dealerid");
$RadCheck->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck->trader_id = null;
$RadCheck->svlan = $request->get("dealerid");

////
// saving in redCheck: entry - 2
$RadCheck2 = new RadCheck();
$RadCheck2->username = $request->get("username");
$RadCheck2->attribute = "Simultaneous-Use";
$RadCheck2->op = ":=";
$RadCheck2->value = "1";
$RadCheck2->resellerid = $request->get("resellerid");
$RadCheck2->manager_id = $request->get("manager_id");
$RadCheck2->status = "subdealer";
$RadCheck2->dealerid = $request->get("dealerid");
$RadCheck2->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck2->trader_id = null;
$RadCheck2->svlan = $request->get("dealerid");

///
// saving in redCheck: entry - 3
$RadCheck3 = new RadCheck();
$RadCheck3->username = $request->get("username");
$RadCheck3->attribute = "Calling-Station-Id";
$RadCheck3->op = ":=";
$RadCheck3->value = "NEW";
$RadCheck3->resellerid = $request->get("resellerid");
$RadCheck3->manager_id = $request->get("manager_id");
$RadCheck3->status = "subdealer";
$RadCheck3->dealerid = $request->get("dealerid");
$RadCheck3->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck3->trader_id = null;
$RadCheck3->svlan = $request->get("dealerid");

///////Getting IP from usualIP
//
$assignedNas = AssignedNas::where([
    "id" => Auth::user()->dealerid,
])->first();
//
$userusualIPs = UserUsualIP::where(["status" => "0"])->where(['nas' => $assignedNas->nas])->first();
$ip = $userusualIPs->ip;

// inserting ip in radreply
$radreply = new Radreply();
$radreply->username = $request->get("username");
$radreply->attribute = "Framed-IP-Address";
$radreply->op = "=";
$radreply->value = $ip;
$radreply->dealerid = $request->get("dealerid");
$radreply->resellerid = $request->get("resellerid");
$radreply->sub_dealer_id = $request->get("sub_dealer_id");
$radreply->manager_id = $request->get("manager_id");
$radreply->trader_id = null;

// changing status to 1 of got ip in usualIP
$userusualIPs->status = "1";

// inserting ip in radusergroup
$radusergroup = new RaduserGroup();
$radusergroup->username = $request->get("username");
$radusergroup->groupname = 'DISABLED';
$radusergroup->name = 'DISABLED';
$radusergroup->priority = "0";

////inserting into useripstatus

$useripstatus = new UserIPStatus();
$useripstatus->username = $request->get("username");
$useripstatus->ip = $ip;
$useripstatus->type = "usual_ip";

// Cactigroup

$graph = new CactiGraph();
$graph->user_id = $request->get("username");
$graph->graph_no = "0";

DB::transaction(function () use (
	$useripstatus,
	$radusergroup,
	$userusualIPs,
	$radreply,
	$RadCheck,
	$RadCheck2,
	$RadCheck3,
	$graph,
	$userAmount
) {
	$radreply->save();
	$useripstatus->save();
	$radusergroup->save();
	$userusualIPs->save();
	$RadCheck->save();
	$RadCheck2->save();
	$RadCheck3->save();
	$graph->save();
	$userAmount->save();

/*
* insert new record for question category
*/
});
break;
case "trader":
$active = 0;
// validation
$this->validate($request, [
	"username" => "required|unique:user_info",
	"manager_id" => "required",
	"resellerid" => "required",
	"dealerid" => "required",
	"sub_dealer_id" => "required",
	"trader_id" => "required|unique:user_info",
	"password" => "required|confirmed",
]);

//
$domainManagement = Domain::where('resellerid',$request->get("resellerid"))->first();
//
$user->username = $request->get("username");
$user->manager_id = $request->get("manager_id");
$user->resellerid = $request->get("resellerid");
$user->dealerid = $request->get("dealerid");
$user->password = Hash::make($request->get("password"));
$user->sub_dealer_id = $request->get("sub_dealer_id");
$user->trader_id = $request->get("trader_id");
$user->profile = "CONTRACTOR";
$user->name = "CONTRACTOR";
$user->domainID = $domainManagement->id;

// default amount is zero;
$userAmount = new UserAmount();
$userAmount->username = $request->get("username");
$userAmount->status = "trader";
$userAmount->amount = 0;
$userAmount->credit_limit = 0;

//assigned server

$nas_type1 = AssignNasType::where([
	"manager_id" => Auth::user()->manager_id,
])->first();

$assignas = $nas_type1->nas_type;

$server = new AssignNasType();
$server->dealerid = $request->get("dealerid");
$server->resellerid = $request->get("resellerid");
$server->manager_id = $request->get("manager_id");
$server->sub_dealer_id = $request->get("sub_dealer_id");
$server->trader_id = $request->get("trader_id");
$server->nas_type = $assignas;
$server->save();
// saving in redCheck: entry - 1
$RadCheck = new RadCheck();
$RadCheck->username = $request->get("username");
$RadCheck->attribute = "Cleartext-Password";
$RadCheck->op = ":=";
$RadCheck->value = $request->get("password");
$RadCheck->resellerid = $request->get("resellerid");
$RadCheck->manager_id = $request->get("manager_id");
$RadCheck->status = "trader";
$RadCheck->dealerid = $request->get("dealerid");
$RadCheck->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck->trader_id = $request->get("trader_id");
$RadCheck->svlan = $request->get("dealerid");

////
// saving in redCheck: entry - 2
$RadCheck2 = new RadCheck();
$RadCheck2->username = $request->get("username");
$RadCheck2->attribute = "Simultaneous-Use";
$RadCheck2->op = ":=";
$RadCheck2->value = "1";
$RadCheck2->resellerid = $request->get("resellerid");
$RadCheck2->manager_id = $request->get("manager_id");
$RadCheck2->status = "trader";
$RadCheck2->dealerid = $request->get("dealerid");
$RadCheck2->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck2->trader_id = $request->get("trader_id");
$RadCheck2->svlan = $request->get("dealerid");

///
// saving in redCheck: entry - 3
$RadCheck3 = new RadCheck();
$RadCheck3->username = $request->get("username");
$RadCheck3->attribute = "Calling-Station-Id";
$RadCheck3->op = ":=";
$RadCheck3->value = "NEW";
$RadCheck3->resellerid = $request->get("resellerid");
$RadCheck3->manager_id = $request->get("manager_id");
$RadCheck3->status = "trader";
$RadCheck3->dealerid = $request->get("dealerid");
$RadCheck3->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck3->trader_id = $request->get("trader_id");
$RadCheck3->svlan = $request->get("dealerid");

///

// inserting ip in radusergroup
$radusergroup = new RaduserGroup();
$radusergroup->username = $request->get("username");
$radusergroup->groupname = "CONTRACTOR";
$radusergroup->name = "CONTRACTOR";
$radusergroup->priority = "0";

// Cactigroup

$graph = new CactiGraph();
$graph->user_id = $request->get("username");
$graph->graph_no = "0";

DB::transaction(function () use (
	$radusergroup,
	$RadCheck,
	$RadCheck2,
	$RadCheck3,
	$graph,
	$userAmount
) {
	$radusergroup->save();

	$RadCheck->save();
	$RadCheck2->save();
	$RadCheck3->save();
	$graph->save();
	$userAmount->save();

/*
* insert new record for question category
*/
});
break;
case "user":

$active = 1;

// validation
$this->validate($request, [
	"username" => "required|unique:user_info",
	"manager_id" => "required",
	"resellerid" => "required",
	"dealerid" => "required",

	"password" => "required|confirmed",
]);

//
$domainManagement = Domain::where('resellerid',$request->get("resellerid"))->first();

$user = new UserInfo();
//
$user->username = $request->get("username");
$user->manager_id = $request->get("manager_id");
$user->resellerid = $request->get("resellerid");
$user->dealerid = $request->get("dealerid");
$user->password = Hash::make($request->get("password"));
$user->domainID = $domainManagement->id;

if (Auth::user()->status == "dealer") {
	$user->sub_dealer_id = null;
    $dealer__Rate = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->where('name',$request->get("profile"))->first();
    $base__price = $dealer__Rate->base_price;

} elseif (Auth::user()->status == "subdealer") {
	$user->sub_dealer_id = $request->get("sub_dealer_id");
    $subdealer__Rate = SubdealerProfileRate::where('sub_dealer_id',Auth::user()->sub_dealer_id)->where('name',$request->get("profile"))->first();
    $base__price = $subdealer__Rate->base_price;

} else {
	$user->sub_dealer_id = $request->get("sub_dealer_id");
	$user->trader_id = $request->get("trader_id");
}

$profile = Profile::where('name',$request->get("profile"))->first();
//
$domainInfo = Domain::where(['resellerid' => $request->get("resellerid")])->first();
// $newprofile = $domainInfo->package_name.'-'.($profile->groupname/1024).'mb';
$newprofile = $profile->groupname;
//
// $user->profile = $profile->groupname;
$user->profile = $newprofile;
$user->company_rate = 'yes';
$user->profile_amount = $base__price;
//
//
$dataprofile1 = $request->get("profile");
$dataprofile2 = str_replace("BE-", "", $dataprofile1);
$dataprofile = str_replace("k", "", $dataprofile2);

$checkname = DealerProfileRate::where(
	"dealerid",
	$request->get("dealerid")
)
->where("groupname", $dataprofile)
->first();
$name = @$checkname["name"];
$user->name = $profile->name;
$accessData = DealerFUP::where(
	"dealerid",
	$request->get("dealerid")
)
->where("groupname", $dataprofile)
->first();
$datalimit2 = @$accessData["datalimit"];

$user->qt_total = $datalimit2;
// saving in redCheck: entry - 1
$RadCheck = new RadCheck();
$RadCheck->username = $request->get("username");
$RadCheck->attribute = "Cleartext-Password";
$RadCheck->op = ":=";
$RadCheck->value = $request->get("password");
$RadCheck->resellerid = $request->get("resellerid");
$RadCheck->manager_id = $request->get("manager_id");
$RadCheck->status = "user";
$RadCheck->dealerid = $request->get("dealerid");
$RadCheck->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck->trader_id = $request->get("trader_id");
$RadCheck->svlan = $request->get("dealerid");

////
// saving in redCheck: entry - 2
$RadCheck2 = new RadCheck();
$RadCheck2->username = $request->get("username");
$RadCheck2->attribute = "Simultaneous-Use";
$RadCheck2->op = ":=";
$RadCheck2->value = "1";
$RadCheck2->resellerid = $request->get("resellerid");
$RadCheck2->manager_id = $request->get("manager_id");
$RadCheck2->status = "user";
$RadCheck2->dealerid = $request->get("dealerid");
$RadCheck2->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck2->trader_id = $request->get("trader_id");
$RadCheck2->svlan = $request->get("dealerid");

///
// saving in redCheck: entry - 3
$RadCheck3 = new RadCheck();
$RadCheck3->username = $request->get("username");
$RadCheck3->attribute = "Calling-Station-Id";
$RadCheck3->op = ":=";
$RadCheck3->value = "NEW";
$RadCheck3->resellerid = $request->get("resellerid");
$RadCheck3->manager_id = $request->get("manager_id");
$RadCheck3->status = "user";
$RadCheck3->dealerid = $request->get("dealerid");
$RadCheck3->sub_dealer_id = $request->get("sub_dealer_id");
$RadCheck3->trader_id = $request->get("trader_id");
$RadCheck3->svlan = $request->get("dealerid");

$UserStatusInfo = new UserStatusInfo();
$UserStatusInfo->username = $request->get("username");
$UserStatusInfo->card_expire_on = "1990-03-03";
$UserStatusInfo->card_charge_by = Auth::user()->username;
$UserStatusInfo->expire_datetime = "1990-03-03 12:00:00";
$UserStatusInfo->card_charge_by_ip = $request->ip();

//  // inserting ip in radusergroup
$radusergroup = new RaduserGroup();
$radusergroup->username = $request->get("username");
$radusergroup->groupname = "NEW";
$radusergroup->name = "NEW";
$radusergroup->priority = "0";

$userExpire = new ExpireUser();
$userExpire->username = $request->get("username");
$userExpire->status = "expire";
$userExpire->last_update = date("Y-m-d H:i:s");

DB::transaction(function () use (
	$UserStatusInfo,
	$radusergroup,
	$RadCheck,
	$RadCheck2,
	$RadCheck3,
	$userExpire
) {
	$UserStatusInfo->save();
	$radusergroup->save();

	$RadCheck->save();
	$RadCheck2->save();
	$RadCheck3->save();
	$userExpire->save();

/*
* insert new record for question category
*/
});
break;
}
$user->firstname = $request->get("fname");
$user->lastname = $request->get("lname");
$user->address = $request->get("address");
$user->permanent_address = $request->get("address");
$user->mobilephone = $request->get("mobile_number");
$user->homephone = $request->get("land_number");
$user->nic = $request->get("nic");
$user->email = $request->get("mail");
$user->area = $request->get("area");
$user->creationdate = date("Y-m-d");
$user->creationby = Auth::user()->status;
$user->creationbyip = $request->ip();
$user->disabled = "";
$user->active = 1;
$user->disabled_old_profile = "";
$user->disabled_expired = "";
// $user->disabled_date = date('Y-m-d H:m:s',strtotime('0000-00-00 00:00:00'));
$user->verified = 0;
$user->status = $status;

/* 30 Jan 2023 */
/*
DB::table("partner_themes")
->where("username", $request->get("resellerid"))
->update(["color" => $request->get("theme_color")]);
*/

DB::transaction(function () use ($user) {
	$user->save();

/*
* insert new record for question category
*/
});
$Uname = $request->get("username");
$accessID = UserInfo::where("username", $Uname)->first();

///////////////////////////////////////////////////////////////
$this->create_access($accessID->id,$status); ///////// CREATE ALL SUBDEALER ENTRY
///////////////////////////////////////////////////////////////
//inserting menus for resller // 26 jan 2022

// if ($accessID->status == "reseller") {
// 	$accessID = $accessID->id;
// 	$subMenu = SubMenu::where("flag", "cp")->get();

// //dd($subMenu);

// 	foreach ($subMenu as $key => $submenu) {
// 		$ac_id = $submenu->id;
// 		$accessMenu = new UserMenuAccess();
// 		$accessMenu->user_id = $accessID;
// 		$accessMenu->sub_menu_id = $submenu->id;
// 		$sts = 0;
// 		if (
// 			$ac_id == 1 ||
// 			$ac_id == 3 ||
// 			$ac_id == 6 ||
// 			$ac_id == 7 ||
// 			$ac_id == 14 ||
// 			$ac_id == 15 ||
// 			$ac_id == 16 ||
// 			$ac_id == 17 ||
// 			$ac_id == 18 ||
// 			$ac_id == 19 ||
// 			$ac_id == 20 ||
// 			$ac_id == 21 ||
// 			$ac_id == 22 ||
// 			$ac_id == 23 ||
// 			$ac_id == 24 ||
// 			$ac_id == 25 ||
// 			$ac_id == 26 ||
// 			$ac_id == 27 ||
// 			$ac_id == 28 ||
// 			$ac_id == 29 ||
// 			$ac_id == 30 ||
// 			$ac_id == 31 ||
// 			$ac_id == 32 ||
// 			$ac_id == 33 ||
// 			$ac_id == 38 ||
// 			$ac_id == 39 ||
// 			$ac_id == 40
// 		) {
// 			$sts = 1;
// 		} else {
// 			$sts = 0;
// 		}
// 		$accessMenu->status = $sts;
// 		$accessMenu->created_at = NOW();
// 		$accessMenu->save();
// 	}
// } elseif ($accessID->status == "dealer") {
// 	$accessID = $accessID->id;
// 	$subMenu = SubMenu::where("flag", "cp")->get();
// 	foreach ($subMenu as $key => $submenu) {
// 		$ac_id = $submenu->id;
// 		$accessMenu = new UserMenuAccess();
// 		$accessMenu->user_id = $accessID;
// 		$accessMenu->sub_menu_id = $submenu->id;
// 		$sts = 0;
// 		if (
// 			$ac_id == 1 ||
// 			$ac_id == 4 ||
// 			$ac_id == 6 ||
// 			$ac_id == 7 ||
// 			$ac_id == 8 ||
// 			$ac_id == 9 ||
// 			$ac_id == 10 ||
// 			$ac_id == 11 ||
// 			$ac_id == 12 ||
// 			$ac_id == 13 ||
// 			$ac_id == 14 ||
// 			$ac_id == 19 ||
// 			$ac_id == 20 ||
// 			$ac_id == 21 ||
// 			$ac_id == 24 ||
// 			$ac_id == 25 ||
// 			$ac_id == 26 ||
// 			$ac_id == 28 ||
// 			$ac_id == 32 ||
// 			$ac_id == 33 ||
// 			$ac_id == 34 ||
// 			$ac_id == 35 ||
// 			$ac_id == 36 ||
// 			$ac_id == 37 ||
// 			$ac_id == 38 ||
// 			$ac_id == 60
// 		) {
// 			$sts = 1;
// 		} else {
// 			$sts = 0;
// 		}
// 		$accessMenu->status = $sts;
// 		$accessMenu->created_at = NOW();
// 		$accessMenu->save();
// 	}
// } elseif ($accessID->status == "subdealer") {
// 	$accessID = $accessID->id;
// 	$subMenu = SubMenu::where("flag", "cp")->get();
// 	foreach ($subMenu as $key => $submenu) {
// 		$ac_id = $submenu->id;
// 		$accessMenu = new UserMenuAccess();
// 		$accessMenu->user_id = $accessID;
// 		$accessMenu->sub_menu_id = $submenu->id;
// 		$sts = 0;
// 		if (
// 			$ac_id == 1 ||
// 			$ac_id == 6 ||
// 			$ac_id == 7 ||
// 			$ac_id == 8 ||
// 			$ac_id == 9 ||
// 			$ac_id == 10 ||
// 			$ac_id == 11 ||
// 			$ac_id == 12 ||
// 			$ac_id == 13 ||
// 			$ac_id == 19 ||
// 			$ac_id == 34 ||
// 			$ac_id == 35 ||
// 			$ac_id == 36 ||
// 			$ac_id == 37 ||
// 			$ac_id == 60
// 		) {
// 			$sts = 1;
// 		} else {
// 			$sts = 0;
// 		}
// 		$accessMenu->status = $sts;
// 		$accessMenu->created_at = NOW();
// 		$accessMenu->save();
// 	}
// } elseif ($accessID->status == "trader") {
// 	$accessID = $accessID->id;
// 	$subMenu = SubMenu::where("flag", "cp")->get();
// 	foreach ($subMenu as $key => $submenu) {
// 		$ac_id = $submenu->id;
// 		$accessMenu = new UserMenuAccess();
// 		$accessMenu->user_id = $accessID;
// 		$accessMenu->sub_menu_id = $submenu->id;
// 		$sts = 0;
// 		if (
// 			$ac_id == 1 ||
// 			$ac_id == 6 ||
// 			$ac_id == 7 ||
// 			$ac_id == 8 ||
// 			$ac_id == 9 ||
// 			$ac_id == 10 ||
// 			$ac_id == 11 ||
// 			$ac_id == 12 ||
// 			$ac_id == 13 ||
// 			$ac_id == 19 ||
// 			$ac_id == 34 ||
// 			$ac_id == 35 ||
// 			$ac_id == 36 ||
// 			$ac_id == 37 ||
// 			$ac_id == 60
// 		) {
// 			$sts = 1;
// 		} else {
// 			$sts = 0;
// 		}
// 		$accessMenu->status = $sts;
// 		$accessMenu->created_at = NOW();
// 		$accessMenu->save();
// 	}
// }


session()->flash("success", "Congratulation! Successfully Created.");
return redirect()->route("users.user.index1", ["status" => $status]);
}

public function edit($status, $id)
{
	$url = url()->previous();
	switch ($status) {
		case "reseller":
		$reseller = UserInfo::find($id);
		$profileList = ManagerProfileRate::where([
			"manager_id" => $reseller->manager_id,
		])
		->orderby("groupname")
		->get();

        $ResellerProfileRate = ResellerProfileRate::where('resellerid',$reseller->resellerid)->first();

// $profileList = Profile::all();
$assignedProfileRates = ResellerProfileRate::where('resellerid',$reseller->resellerid)->get(); // assign
$assignedProfileNameList = [];
foreach ($assignedProfileRates as $profileRate) {
	$assignedProfileNameList[] = ucfirst(
		$profileRate->name
	);
}
$userAmount = UserAmount::where([
	"username" => $reseller->username,
])->first();
//
$StaticIp = StaticIp::where('username',$reseller->username)->first();
//
$nas = Nas::all();
$assignedNas = AssignedNas::where(["id" => $reseller->resellerid])->get();
//
$domainInfo = Domain::where(['resellerid' => $reseller->resellerid])->first();
//
$assignedNasArray = array();
foreach($assignedNas as $assignedNasValue){
	array_push($assignedNasArray,$assignedNasValue->nas);
}
//
return view("users.manager.update_reseller", [
	"id" => $id,
	"reseller" => $reseller,
	"profileList" => $profileList,
	"assignedProfileRates" => $assignedProfileRates,
	"assignedProfileNameList" => $assignedProfileNameList,
	"userAmount" => $userAmount,
	"nas" => $nas,
	"assignedNas" => $assignedNasArray,
	"domainInfo" => $domainInfo,
    "StaticIp" => $StaticIp,
    "ResellerProfileRate" => $ResellerProfileRate,
]);
break;
case "dealer":
$dealer = UserInfo::find($id);
$profileList = ResellerProfileRate::where([
	"resellerid" => $dealer->resellerid,
])
->orderby("groupname")
->get();

$resellerPorfileInfo = ResellerProfileRate::where('resellerid' , $dealer->resellerid)->first();

$graph1 = CactiGraph::where([
	"user_id" => $dealer->username,
])->first();
$ip_static_rates = StaticIp::where("username", $dealer->username)
->first();
$ip_amount = 0;
if (empty($ip_static_rates)) {
    $ip_amount = 0;
    $ip_amount_max = 0;
    $ip_update_on = '';
} else {
    $ip_amount = $ip_static_rates["rates"];
    $ip_amount_max = $ip_static_rates["max_ip_rate"];
    $ip_update_on = $ip_static_rates["update_on"];
}

$assignedProfileRates = DealerProfileRate::where('dealerid',$dealer->dealerid)->get(); // assign
$assignedProfileNameList = [];
foreach ($assignedProfileRates as $profileRate) {
	$assignedProfileNameList[] = ucfirst(
		$profileRate->name
	);
}
$nas_type = Nas::all();
$userAmount = UserAmount::where([
	"username" => $dealer->username,
])->first();
$dhcp_server = Dhcp_server::all();
//
$naslist = AssignedNas::where(["id" => $dealer->resellerid])->get();
$assignedNas = AssignedNas::where(["id" => $dealer->dealerid])->get();
//
return view("users.reseller.update_dealer", [
	"id" => $id,
	"dealer" => $dealer,
	"profileList" => $profileList,
	"nas_type" => $nas_type,
	"assignedProfileRates" => $assignedProfileRates,
	"assignedProfileNameList" => $assignedProfileNameList,
	"graph1" => $graph1,
	"userAmount" => $userAmount,
    "ip_amount" => $ip_amount,
    "ip_amount_max" => $ip_amount_max,
    "ip_update_on" => $ip_update_on,
    "dhcp_server" => $dhcp_server,
    "nas" => $naslist,
    "assignedNas" => $assignedNas,
    "resellerPorfileInfo" => $resellerPorfileInfo,
]);
break;
case "subdealer":
$subdealer = UserInfo::find($id);
$profileList = DealerProfileRate::where([
	"dealerid" => $subdealer->dealerid,
])
->orderby("groupname")
->get();
$graph1 = CactiGraph::where([
	"user_id" => $subdealer->username,
])->first();

$resellerPorfileInfo = ResellerProfileRate::where('resellerid' , $subdealer->resellerid)->first();

$assignedProfileRates = SubdealerProfileRate::where('sub_dealer_id',$subdealer->sub_dealer_id)->get();// assign

$assignedProfileNameList = [];
foreach ($assignedProfileRates as $profileRate) {
	$assignedProfileNameList[] = ucfirst(
		$profileRate->name
	);
}

return view("users.dealer.update_sub_dealer", [
	"id" => $id,
	"subdealer" => $subdealer,
	"profileList" => $profileList,
	"assignedProfileRates" => $assignedProfileRates,
	"assignedProfileNameList" => $assignedProfileNameList,
	"graph1" => $graph1,
    "resellerPorfileInfo" => $resellerPorfileInfo,
]);
break;
case "trader":
$trader = UserInfo::find($id);
$profileList = SubdealerProfileRate::where([
	"sub_dealer_id" => $trader->sub_dealer_id,
])
->orderby("groupname")
->get();
$graph1 = CactiGraph::where([
	"user_id" => $trader->username,
])->first();

$assignedProfileRates = TraderProfileRate::where('trader_id',$trader->trader_id)->get(); // assign

$assignedProfileNameList = [];
foreach ($assignedProfileRates as $profileRate) {
	$assignedProfileNameList[] = ucfirst(
		$profileRate->name
	);
}

return view("users.sub_dealer.update_trader", [
	"id" => $id,
	"trader" => $trader,
	"profileList" => $profileList,
	"assignedProfileRates" => $assignedProfileRates,
	"assignedProfileNameList" => $assignedProfileNameList,
	"graph1" => $graph1,
]);
break;
case "user":


//
$check_user = UserInfo::find($id);
$staticIp = StaticIPServer::where('userid',$check_user->username)->first();
$staticIpRate = StaticIp::where("username", Auth::user()->dealerid)->first();
//
$user = UserInfo::find($id);
$status = Auth::user()->status;
//
$userRadCheck = RadCheck::where([
    "username" => $user->username,
    "attribute" => "Calling-Station-Id",
])->first();
//
$nevereExpireInfo = DB::table('never_expire')->where('username',$user->username)->first();
$expireInfo = DB::table('user_status_info')->where('username',$user->username)->first();
//
$updated_by = NULL;
if($user->updated_by){
    $updated_by = UserInfo::where('id',$user->updated_by)->select('username')->first();
}
//
if ($status == "dealer" || $status == "inhouse") {
	if (Auth::user()->dealerid == $user->dealerid) {
        //
		$serverip = StaticIPServer::where([
			"dealerid" => Auth::user()->dealerid,
			"status" => "NEW",
		])->get();
        //
		return view("users.dealer.update_users", [
			"id" => $id,
			"user" => $user,
			"serverip" => $serverip,
			"url" => $url,
            "staticIp" => $staticIp,
            "userRadCheck" => $userRadCheck,
            "staticIpRate" => $staticIpRate,
            "nevereExpireInfo" => $nevereExpireInfo,
            "expireInfo" => $expireInfo,
            "updated_by" => $updated_by,

        ]);
	} else {
		return redirect()->route("users.dashboard");
	}
} elseif ($status == "subdealer") {
	if (Auth::user()->sub_dealer_id == $user->sub_dealer_id) {
		return view("users.dealer.update_users", [
			"id" => $id,
			"user" => $user,
			"url" => $url,
            "staticIp" => $staticIp,
            "userRadCheck" => $userRadCheck,
            "staticIpRate" => $staticIpRate,
            "nevereExpireInfo" => $nevereExpireInfo,
            "expireInfo" => $expireInfo,
            "updated_by" => $updated_by,
        ]);
	} else {
		return redirect()->route("users.dashboard");
	}
} else {
	if (Auth::user()->trader_id == $user->trader_id) {
		return view("users.dealer.update_users", [
			"id" => $id,
			"user" => $user,
			"url" => $url,
            "staticIp" => $staticIp,
            "userRadCheck" => $userRadCheck,
        ]);
	} else {
		return redirect()->route("users.dashboard");
	}
}
break;
default:
return redirect()->route("users.dashboard");
}
}

/////// show profile////////
public function show(Request $request, $status)
{

	$id = $request->get("id");
	if ($id) {
		switch ($status) {
			case "reseller":
			$reseller = UserInfo::find($id);
			$download = "";
			$upload = "";
			$userProfileRates = $reseller->reseller_profile_rate;
			$userRadCheck = RadCheck::where([
				"username" => $reseller->username,
				"attribute" => "Cleartext-Password",
			])->first();
			$download = RadAcct::select(
				"acctoutputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $reseller->username])
			->get();
//
			$upload = RadAcct::select(
				"acctinputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $reseller->username])
			->get();
			if ($reseller->status == "reseller") {
				return view("users.billing.user_detail", [
					"user" => $reseller,
					"userRedCheck" => $userRadCheck,
					"userProfileRates" => $userProfileRates,

					"download" => $download,
					"upload" => $upload,
				]);
			} else {
				return redirect()->route("users.dashboard");
			}
			break;
			case "dealer":
			$dealer = UserInfo::find($id);
			$download = "";
			$upload = "";

			$userProfileRates = $dealer->dealer_profile_rates;
			$userRadCheck = RadCheck::where([
				"username" => $dealer->username,
				"attribute" => "Cleartext-Password",
			])->first();
			$download = RadAcct::select(
				"acctoutputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $dealer->username])
			->get();
//
			$upload = RadAcct::select(
				"acctinputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $dealer->username])
			->get();
			if ($dealer->status == "dealer") {
				return view("users.billing.user_detail", [
					"user" => $dealer,
					"userRedCheck" => $userRadCheck,
					"userProfileRates" => $userProfileRates,
					"download" => $download,
					"upload" => $upload,
				]);
			} else {
				return redirect()->route("users.dashboard");
			}
			break;
			case "subdealer":
			$subdealer = UserInfo::find($id);
			$download = "";
			$upload = "";
			$userProfileRates = $subdealer->subdealer_profile_rates;
			$userRadCheck = RadCheck::where([
				"username" => $subdealer->username,
				"attribute" => "Cleartext-Password",
			])->first();

// daily data usage
			$download = RadAcct::select(
				"acctoutputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $subdealer->username])
			->get();
//
			$upload = RadAcct::select(
				"acctinputoctets",
				"acctstarttime"
			)
			->where("acctstarttime", ">=", date("Y-m-01 00:00:00"))
			->where("acctstarttime", "<=", date("Y-m-t 00:00:00"))
			->where(["username" => $subdealer->username])
			->get();
			if ($subdealer->status == "subdealer") {
				return view("users.billing.user_detail", [
					"user" => $subdealer,
					"userRedCheck" => $userRadCheck,
					"userProfileRates" => $userProfileRates,
					"download" => $download,
					"upload" => $upload,
				]);
			} else {
				return redirect()->route("users.dashboard");
			}
			break;
			case "user":
			$user = UserInfo::find($id);
			$status = Auth::user()->status;
			if ($status == "manager") {
				if (Auth::user()->manager_id == $user->manager_id) {
					$userRadCheck = RadCheck::where([
						"username" => $user->username,
						"attribute" => "Cleartext-Password",
					])->first();
					$userstatusinfo = UserStatusInfo::where([
						"username" => $user->username,
					])->first();
					$userexpirelog = UserExpireLog::where([
						"username" => $user->username,
					])->first();
					$package = $user->profile;
					$packagename = $user->name;

					$package = str_replace("BE-", "", $package);
					$package = str_replace("k", "", $package);
					$profile = Profile::where([
						"name" => $packagename,
					])->first();

					$cur_pro = RaduserGroup::select("name")
					->where(["username" => $user->username])
					->first();
					$package = str_replace(
						"BE-",
						"",
						$cur_pro->groupname
					);
					$package = str_replace("k", "", $package);
// $cur_pro = Profile::where(['groupname'=>$package])->first();
// daily data usage
					$download = RadAcct::select(
						"acctoutputoctets",
						"acctstarttime"
					)
					->where(
						"acctstarttime",
						">=",
						date("Y-m-01 00:00:00")
					)
					->where(
						"acctstarttime",
						"<=",
						date("Y-m-t 00:00:00")
					)
					->where(["username" => $user->username])
					->get();
//
					$upload = RadAcct::select(
						"acctinputoctets",
						"acctstarttime"
					)
					->where(
						"acctstarttime",
						">=",
						date("Y-m-01 00:00:00")
					)
					->where(
						"acctstarttime",
						"<=",
						date("Y-m-t 00:00:00")
					)
					->where(["username" => $user->username])
					->get();

					return view("users.billing.user_detail", [
						"user" => $user,
						"userRedCheck" => $userRadCheck,
						"userstatusinfo" => $userstatusinfo,
						"userexpirelog" => $userexpirelog,
						"profile" => $profile,
						"cur_profile" => $cur_pro,
						"download" => $download,
						"upload" => $upload,
					]);
				} else {
					return redirect()->route("users.dashboard");
				}
			} elseif ($status == "reseller") {
				if (Auth::user()->resellerid == $user->resellerid) {
					$userRadCheck = RadCheck::where([
						"username" => $user->username,
						"attribute" => "Cleartext-Password",
					])->first();
					$userstatusinfo = UserStatusInfo::where([
						"username" => $user->username,
					])->first();
					$userexpirelog = UserExpireLog::where([
						"username" => $user->username,
					])->first();
					$package = $user->profile;
					$packagename = $user->name;


					$package = str_replace("BE-", "", $package);
					$package = str_replace("k", "", $package);
					$profile = Profile::where([
						"name" => $packagename,
					])->first();

					$cur_pro = RaduserGroup::select("name")
					->where(["username" => $user->username])
					->first();
					$package = str_replace(
						"BE-",
						"",
						$cur_pro->groupname
					);
					$package = str_replace("k", "", $package);
// $cur_pro = Profile::where(['groupname'=>$package])->first();
// daily data usage
					$download = RadAcct::select(
						"acctoutputoctets",
						"acctstarttime"
					)
					->where(
						"acctstarttime",
						">=",
						date("Y-m-01 00:00:00")
					)
					->where(
						"acctstarttime",
						"<=",
						date("Y-m-t 00:00:00")
					)
					->where(["username" => $user->username])
					->get();
//
					$upload = RadAcct::select(
						"acctinputoctets",
						"acctstarttime"
					)
					->where(
						"acctstarttime",
						">=",
						date("Y-m-01 00:00:00")
					)
					->where(
						"acctstarttime",
						"<=",
						date("Y-m-t 00:00:00")
					)
					->where(["username" => $user->username])
					->get();

					return view("users.billing.user_detail", [
						"user" => $user,
						"userRedCheck" => $userRadCheck,
						"userstatusinfo" => $userstatusinfo,
						"userexpirelog" => $userexpirelog,
						"profile" => $profile,
						"cur_profile" => $cur_pro,
						"download" => $download,
						"upload" => $upload,
					]);
				} else {
					return redirect()->route("users.dashboard");
				}
			} elseif ($status == "dealer" || $status == "inhouse") {
				// if (Auth::user()->dealerid == $user->dealerid) {

               $userRadCheck = RadCheck::where([
                  "username" => $user->username,
                  "attribute" => "Cleartext-Password",
              ])->first();
               $userstatusinfo = UserStatusInfo::where([
                  "username" => $user->username,
              ])->first();
               $userexpirelog = UserExpireLog::where([
                  "username" => $user->username,
              ])->first();
               $package = $user->profile;
               $packagename = $user->name;


               $package = str_replace("BE-", "", $package);
               $package = str_replace("k", "", $package);
               $profile = Profile::where([
                  "name" => $packagename,
              ])->first();

               $cur_pro = RaduserGroup::select("name")
               ->where(["username" => $user->username])
               ->first();
               $package = str_replace(
                  "BE-",
                  "",
                  $cur_pro->groupname
              );
               $package = str_replace("k", "", $package);
// $cur_pro = Profile::where(['groupname'=>$package])->first();
//
// daily data usage
               $download = RadAcct::select(
                  "acctoutputoctets",
                  "acctstarttime"
              )
               ->where(
                  "acctstarttime",
                  ">=",
                  date("Y-m-01 00:00:00")
              )
               ->where(
                  "acctstarttime",
                  "<=",
                  date("Y-m-t 00:00:00")
              )
               ->where(["username" => $user->username])
               ->get();
//
               $upload = RadAcct::select(
                  "acctinputoctets",
                  "acctstarttime"
              )
               ->where(
                  "acctstarttime",
                  ">=",
                  date("Y-m-01 00:00:00")
              )
               ->where(
                  "acctstarttime",
                  "<=",
                  date("Y-m-t 00:00:00")
              )
               ->where(["username" => $user->username])
               ->get();

               return view("users.billing.user_detail", [
                  "user" => $user,
                  "userRedCheck" => $userRadCheck,
                  "userstatusinfo" => $userstatusinfo,
                  "userexpirelog" => $userexpirelog,
                  "profile" => $profile,
                  "cur_profile" => $cur_pro,
                  "download" => $download,
                  "upload" => $upload,
                  "download" => $download,
                  "upload" => $upload,
              ]);
				// }

                // else {
				// 	return redirect()->route("users.dashboard");
				// }


           } elseif ($status == "subdealer" || $status == "inhouse") {
            if (
               Auth::user()->sub_dealer_id == $user->sub_dealer_id
           ) {
               $userRadCheck = RadCheck::where([
                  "username" => $user->username,
                  "attribute" => "Cleartext-Password",
              ])->first();
               $userstatusinfo = UserStatusInfo::where([
                  "username" => $user->username,
              ])->first();
               $userexpirelog = UserExpireLog::where([
                  "username" => $user->username,
              ])->first();
               $package = $user->profile;
               $packagename = $user->name;


               $package = str_replace("BE-", "", $package);
               $package = str_replace("k", "", $package);
               $profile = Profile::where([
                  "name" => $packagename,
              ])->first();

               $cur_pro = RaduserGroup::select("name")
               ->where(["username" => $user->username])
               ->first();
               $package = str_replace(
                  "BE-",
                  "",
                  $cur_pro->groupname
              );
               $package = str_replace("k", "", $package);
// $cur_pro = Profile::where(['groupname'=>$package])->first();
// daily data usage
               $download = RadAcct::select(
                  "acctoutputoctets",
                  "acctstarttime"
              )
               ->where(
                  "acctstarttime",
                  ">=",
                  date("Y-m-01 00:00:00")
              )
               ->where(
                  "acctstarttime",
                  "<=",
                  date("Y-m-t 00:00:00")
              )
               ->where(["username" => $user->username])
               ->get();
//
               $upload = RadAcct::select(
                  "acctinputoctets",
                  "acctstarttime"
              )
               ->where(
                  "acctstarttime",
                  ">=",
                  date("Y-m-01 00:00:00")
              )
               ->where(
                  "acctstarttime",
                  "<=",
                  date("Y-m-t 00:00:00")
              )
               ->where(["username" => $user->username])
               ->get();

               return view("users.billing.user_detail", [
                  "user" => $user,
                  "userRedCheck" => $userRadCheck,
                  "userstatusinfo" => $userstatusinfo,
                  "userexpirelog" => $userexpirelog,
                  "profile" => $profile,
                  "cur_profile" => $cur_pro,
                  "download" => $download,
                  "upload" => $upload,
              ]);
           } else {
               return redirect()->route("users.dashboard");
           }
       }
       break;
       default:
       return redirect()->route("users.dashboard");
   }
} else {
  return redirect()->route("users.dashboard");
}
}

/////// end profile///////
public function update(Request $request, $status, $id)
{

    if(MyFunctions::is_freezed(Auth::user()->username)){
        Session()->flash("error", "Your panel has been freezed");
        return back();
    }
    /* 30 Jan 2023 */

    if(!empty($request->get("theme_color")) || !empty($request->get("login_alignment"))) {
      DB::table("partner_themes_user")
      ->where("username", $request->get("resellerid"))
      ->update(["color" => $request->get("theme_color"),
         "login_alignment" => $request->get("login_alignment")]);
  }

// dd($request->username);
  $sstfromdb = $request->sstField;
  $advfromdb = $request->advField;
  switch ($status) {
      case "manager":
      $manager = UserInfo::find($id);

      $manager->firstname = $request->get("fname");
      $manager->lastname = $request->get("lname");
      $manager->address = $request->get("address");
      $manager->mobilephone = $request->get("mobile_number");
      $manager->homephone = $request->get("land_number");
      $manager->nic = $request->get("nic");
      $manager->email = $request->get("mail");
      $manager->area = $request->get("area");
      $manager->save();
//
      session()->flash("success", "Congratulation! Manager Successfully Updated.");
      return redirect()->route("admin.user.index1", [
         "status" => $status,
     ]);
      break;
      case "reseller":
      $reseller = UserInfo::find($id);

      $reseller->firstname = $request->get("fname");
      $reseller->lastname = $request->get("lname");
      $reseller->address = $request->get("address");
      $reseller->mobilephone = $request->get("mobile_number");
      $reseller->homephone = $request->get("land_number");
      $reseller->nic = $request->get("nic");
      $reseller->email = $request->get("mail");
      $reseller->area = $request->get("area");
      $reseller->allow_invoice = $request->get('allow_invoice');
      $reseller->city = $request->get('city');
      $reseller->state = $request->get('state');
      $reseller->has_license = $request->get('has_license');
      $reseller->save();
        //
      if ($request->hasFile('cnic_front')) {
        $reseller_cnic_front = $request->file('cnic_front');
        $reseller_cnic_front_name = $request->get("resellerid"). '-cnic_front.jpg';
        $reseller_cnic_front->move(public_path('Reseller-NIC/'), $reseller_cnic_front_name);
    }
    if ($request->hasFile('cnic_back')) {
        $reseller_cnic_back = $request->file('cnic_back');
        $reseller_cnic_back_name = $request->get("resellerid"). '-cnic_back.jpg';
        $reseller_cnic_back->move(public_path('Reseller-NIC/'), $reseller_cnic_back_name);
    }

    $userAmount = UserAmount::where([
       "username" => $reseller->username,
   ])->first();
    $userAmount->credit_limit = $request->get("limit");
    $userAmount->discount = $request->get("discount");
    $userAmount->update_on = date('Y-m-d H:i:s');
    $userAmount->save();

// delete existing profile rates of resller
    $dlt = ResellerProfileRate::where([
       "resellerid" => $reseller->resellerid,
   ])->delete();
        //
        //
    $staticIpExist = StaticIp::where('username',$reseller->username)->first();
    if($staticIpExist){

        $staticIpExist->rates = $request->get('static_ip_rate');
        $staticIpExist->update_on = date('Y-m-d H:i:s');
        $staticIpExist->save();

    }else{
        $staticIP = new StaticIp();
        $staticIP->username = $reseller->username;
        $staticIP->rates = $request->get('static_ip_rate');
        $staticIP->save();
    }
        //

        //
// getting assigned profile rates
    $profileList = Profile::all();
    foreach ($profileList as $profile) {
       $name = $profile->name;
       $profileName = ucfirst($profile->name);
       if ($request->has("" . $profileName)) {
$profileRate = $request->get("" . $profileName); // will get rate form request
$profileCommission = $request->get("comm" . $profileName); // will get rate form request
$groupName = $profile->groupname;
$resellerId = $reseller->resellerid;

// save into reseller profile rate.
$resellerProfileRate = new ResellerProfileRate();
$resellerProfileRate->groupname = $groupName;
$resellerProfileRate->name = $name;
$resellerProfileRate->resellerid = $resellerId;
$resellerProfileRate->ip_rates = 0;
$resellerProfileRate->billing_type = "amount";
$resellerProfileRate->sst = 0;
$resellerProfileRate->adv_tax = 0;
$resellerProfileRate->rate = $profileRate;
$resellerProfileRate->final_rates = $profileRate;
$resellerProfileRate->commission = $profileCommission;
$resellerProfileRate->allow_auto_profit = $request->get('allow_auto_profit');
$resellerProfileRate->allow_flat_rate = $request->get('allow_flat_rate');
$resellerProfileRate->allow_manual_rate_update = $request->get('allow_manual_rate_update');
$resellerProfileRate->save();
}
}
$isvisible = $request->isvisible;
if ($isvisible == "on") {
	$allowed = "yes";
} else {
	$allowed = "no";
}
$showAmount = UserAmount::where(
	"username",
	$reseller->username
)->update([
	"isvisible" => $allowed,
]);
//
DB::table("assigned_nas")->where("id", $request->get("resellerid"))->delete();
//
foreach($request->get("nas") as $nasValue){
	$assigned_nas = new AssignedNas();
	$assigned_nas->id = $request->get("resellerid");
	$assigned_nas->nas = $nasValue;
	$assigned_nas->save();
}
////// domain management update /////
$domainManagement = Domain::where('resellerid',$request->get("resellerid"))->first();
$domainManagement->domainname = $request->get("bm_domain");
$domainManagement->slogan = $request->get("bm_slogan");
$domainManagement->bm_invoice_email = $request->get("bm_invoice_email");
$domainManagement->bm_helpline_number = $request->get("bm_helpline_number");
//
$domainManagement->main_heading = $request->get("bm_heading");
$domainManagement->logo = $request->get("resellerid").'-logo.jpg';
$domainManagement->bg_image = $request->get("resellerid").'-bgImg.jpg';
$domainManagement->favicon = $request->get("resellerid").'-fav.jpg';
//
$domainManagement->facebook_url = $request->get("facebook_url");
$domainManagement->twitter_url = $request->get("twitter_url");
$domainManagement->linkedin_url = $request->get("linkedin_url");
$domainManagement->contractor_profile = $request->get("contractor_profile");
$domainManagement->trader_profile = $request->get("trader_profile");
//
$domainManagement->save();



// updating all contractor profiles
if($request->get("contractor_profile")){
  $contractorList = DB::table('user_info')->select('username')->where('resellerid',$request->get("resellerid"))->where('status','dealer')->get();
  //
  foreach($contractorList as $value){
    //
    $updateUserInfo = DB::table('user_info')->where('username',$value->username)->where('resellerid',$request->get("resellerid"))->where('status','dealer')->update(['profile' => $request->get("contractor_profile"), 'name' => $request->get("contractor_profile")]);
    //
    if($updateUserInfo){
      $updateRadusergroup = Radusergroup::where('username',$value->username)->where('groupname','!=','DISABLED')->update(['groupname' => $request->get("contractor_profile"), 'name' => $request->get("contractor_profile")]);
  }
}
}
// updating all trader profiles
if($request->get("trader_profile")){
  $traderList = DB::table('user_info')->select('username')->where('resellerid',$request->get("resellerid"))->where('status','subdealer')->get();
  //
  foreach($traderList as $value){
    //
    $updateUserInfo = DB::table('user_info')->where('username',$value->username)->where('resellerid',$request->get("resellerid"))->where('status','subdealer')->update(['profile' => $request->get("trader_profile"), 'name' => $request->get("trader_profile")]);
    //
    if($updateUserInfo){
      $updateRadusergroup = Radusergroup::where('username',$value->username)->where('groupname','!=','DISABLED')->update(['groupname' => $request->get("trader_profile"), 'name' => $request->get("trader_profile")]);
  }
}
}




//
if($request->hasFile('bm_logo')){
	$bm_logo = $request->file('bm_logo');
	$bm_logo_name = $request->get("resellerid").'-logo.jpg';
	$bm_logo->move(public_path('img/'), $bm_logo_name);
}if($request->hasFile('bm_bgImage')){
	$bm_bgImage = $request->file('bm_bgImage');
	$bm_bgImage_name = $request->get("resellerid").'-bgImg.jpg';
	$bm_bgImage->move(public_path('images/'), $bm_bgImage_name);
}if($request->hasFile('bm_favicon')){
	$bm_favicon = $request->file('bm_favicon');
	$bm_favicon_name = $request->get("resellerid").'-fav.jpg';
	$bm_favicon->move(public_path('img/favicon/'), $bm_favicon_name);
}if ($request->hasFile('bm_inv_banner')) {
	$bm_inv_banner = $request->file('bm_inv_banner');
	$bm_inv_banner_name = $request->get("resellerid") . '-inv-banner.jpg';
	$bm_inv_banner->move(public_path('img/invoice_banner/'), $bm_inv_banner_name);
}
//
session()->flash("success", "Congratulation! Reseller Successfully Updated.");
return redirect()->route("users.user.index1", [
	"status" => $status,
]);
break;
case "dealer":
$basePrice = "";
$allowed = "";
$change = "";
$receipt = $request->get("receipt");
$tax = $request->get("tax");
$taxc = $request->get("taxc");
$changeprofile = $request->get("changeprofile");
if ($tax == "") {
	$tax = $request->get("taxtValue");
}
$Allowplan = $request->get("Allowplan");
$verification = $request->get("Verification");
$payment_type1 = $request->get("payment_type");
$billingType = $request->billingtype;
$dealer = UserInfo::find($id);
$isvisible = $request->isvisible;
//
$proTax = DB::table('provincial_taxation')->where('state',$dealer->state)->first();
if(empty($proTax)){
    Session()->flash("error", "Error : Invalid or no state found");
    return back();
}
//
$traderAllowed = $request->traderAllow;
if ($traderAllowed == "on") {
	$allowtrader = "yes";
} else {
	$allowtrader = "no";
}
if ($isvisible == "on") {
	$allowed = "yes";
} else {
	$allowed = "no";
}
if ($changeprofile == "on") {
	$change = "yes";
} else {
	$change = "no";
}

$dealer->firstname = $request->get("fname");
$dealer->lastname = $request->get("lname");
$dealer->address = $request->get("address");
$dealer->mobilephone = $request->get("mobile_number");
$dealer->homephone = $request->get("land_number");
$dealer->nic = $request->get("nic");
$dealer->email = $request->get("mail");
$dealer->area = $request->get("area");
$dealer->allow_invoice = $request->get('allow_invoice');
$dealer->bind_mac = $request->get('bind_mac');
$dealer->save();
//
$get_tax_selection = $request->get('tax_amt');
// $filer_status = $get_tax_selection;
//
if ($request->hasFile('cnic_front')) {
	$cnic_front = $request->file('cnic_front');
	$cnic_front_name = $request->get("dealerid") . '-cnic_front.jpg';
	$cnic_front->move(public_path('Dealer-NIC/'), $cnic_front_name);
}
if ($request->hasFile('cnic_back')) {
	$cnic_back  = $request->file('cnic_back');
	$cnic_back_name = $request->get("dealerid") . '-cnic_back.jpg';
	$cnic_back->move(public_path('Dealer-NIC/'), $cnic_back_name);
}

if (!empty($dealer->ntn_num)) {
	$ntn_num = $dealer->ntn_num;
} else {
	$ntn_num = $request['ntn_num'];
}



Userinfo::where("username", $dealer->dealerid)
->update(["receipt" => $receipt, "is_filer" => $get_tax_selection, "ntn_num" => $ntn_num]);
// catigraph
// CactiGraph::where(["user_id" => $dealer->username])->delete();
// $newarray = [];
// $mydata = $request->get("graph");
// for ($i = 0; $i < sizeof($mydata); $i++) {
// 	if ($mydata[$i] != null) {
// 		$graph1 = new CactiGraph();
// 		$graph1->user_id = $request->get("username");
// 		$graph1->graph_no = $mydata[$i];
// 		$graph1->save();
// 	}
// }

// end
$userAmount = UserAmount::where([
	"username" => $dealer->username,
])->first();

$userAmount->credit_limit = str_replace(
	",",
	"",
	$request->get("limit")
);
$userAmount->save();

if ($Allowplan == "on") {
	$yes = "yes";
} else {
	$yes = "no";
}

if ($verification == "on") {
	$verify = "yes";
} else {
	$verify = "no";
}

if ($payment_type1 == "on") {
	$payment_type = "cash";
} else {
	$payment_type = "credit";
}

// delete existing profile rates of resller
// $dlt = DealerProfileRate::where([
// 	"dealerid" => $dealer->dealerid,
// ])->delete();


// getting assigned profile rates
$profileList = Profile::all();

// foreach ($profileList as $key => $profile) {
// 	$name = $profile->name;
// 	$profileName = ucfirst($profile->name);
// 	if ($request->has("" . $profileName)) {
// 		$profileRate = $request->get($profileName);

//         $basePrice = $request->get("bp" . $profileName);
//         $basePriceET = $request->get("bpET" . $profileName);
//         $profileComision = $request->get("comm" . $profileName);
// //end
//         $groupName = $profile->groupname;

//         $check_profile=$profileList->where('name',$name)->first()->base_price;

//         $checkmargin = ProfileMargins::where(
//            "dealerid",
//            $dealer["dealerid"]
//        )
//         ->where("sub_dealer_id", "=", null)
//         ->where("groupname", "=", $groupName)
//         ->first();

//         if ($profileRate > $basePrice) {
//             Session()->flash("error", "Base Price Should be greator than " .$profileRate);
//             return back();
//         }
//     // if ($check_profile > $profileRate) {
//     //     Session()->flash("error", "Profile Rate Should be greator than or equal to " .$check_profile);
//     //     return back();
//     // }


//         if (empty($checkmargin)) {
//          $get_taxt_data = '';
// $sale_price = $basePrice; //700
// $profit_margin_dealer = $sale_price - $profileRate; //700-300=400
// $ss_tax = $basePrice * $sstfromdb; //136.5
// $after_ss_tax = $basePrice + $ss_tax; //836.5
// $adv_tax = $after_ss_tax * $advfromdb; //125.475
// $after_adv_tax = $after_ss_tax + $adv_tax; //961.975
// $sst = $ss_tax;
// $adv = $adv_tax;
// $consumer = $sale_price + $sst + $adv;
// $multyply = $profileRate * 2 + $sst + $adv;
// $final_rates = $multyply / 2;

// $profileMargin = new ProfileMargins();
// $profileMargin->groupname = $groupName;
// $profileMargin->manager_id = $dealer->manager_id;
// $profileMargin->resellerid = $dealer->resellerid;
// $profileMargin->dealerid = $dealer->dealerid;
// $profileMargin->sub_dealer_id = "";
// $profileMargin->trader_id = null;
// $profileMargin->margin = $profile->margin;
// $profileMargin->save();
// } else {
// 	$get_taxt_data = '';
// $sale_price = $basePrice; //700
// $profit_margin_dealer = $sale_price - $profileRate; //700-300=400
// $ss_tax = $basePrice * $sstfromdb; //136.5
// $after_ss_tax = $basePrice + $ss_tax; //836.5
// $adv_tax = $after_ss_tax * $advfromdb; //125.475
// $after_adv_tax = $after_ss_tax + $adv_tax; //961.975
// $sst = $ss_tax;
// $adv = $adv_tax;
// $consumer = $sale_price + $sst + $adv;

// $multyply = $profileRate * 2 + $sst + $adv;
// $final_rates = $multyply / 2;
// }

// $dealerid = $dealer->dealerid;
// // $profileComision = 0;
// $profileMax = 0;

// // save into reseller profile rate.
// $dealerProfileRate = new DealerProfileRate();
// $dealerProfileRate->groupname = $groupName;
// $dealerProfileRate->name = $name;
// $dealerProfileRate->dealerid = $dealer->dealerid;
// $dealerProfileRate->rate = $profileRate;
// $dealerProfileRate->sst = $sst;
// $dealerProfileRate->sstpercentage = $sstfromdb;
// $dealerProfileRate->advpercentage = $advfromdb;
// $dealerProfileRate->taxgroup = "E";
// $dealerProfileRate->adv_tax = $adv;
// $dealerProfileRate->final_rates = $final_rates;
// $dealerProfileRate->max = $profileMax;
// $dealerProfileRate->consumer = $consumer;
// $dealerProfileRate->changeprofile = $change;
// $dealerProfileRate->show_sub_dealer = $allowed;
// $dealerProfileRate->trader = $allowtrader;
// $dealerProfileRate->billing_type = $billingType;
// $dealerProfileRate->verify = $verify;
// // $dealerProfileRate->commision = $profileComision;
// $dealerProfileRate->allowplan = $yes;
// $dealerProfileRate->payment_type = $payment_type;
// $dealerProfileRate->discount = $request->discount;
// $dealerProfileRate->username = $request->username;
// $dealerProfileRate->base_price = $basePrice;
// $dealerProfileRate->base_price_ET = $basePriceET;
// $dealerProfileRate->commision = $profileComision;
// $dealerProfileRate->save();
// }
// }

$dealerProfileRate = DealerProfileRate::where('dealerid',$dealer->dealerid);
//
$dealerProfileRate->update([
    'verify' => $verify,
    'show_sub_dealer' => $allowed,
    'payment_type' => $payment_type,
]);


// subDealerProfileRate Update as per dealer sst adv change start....
// $subdealerprofilerateData = SubdealerProfileRate::where('dealerid',$dealer->dealerid)->get();
// // delete existing profile rates of resller
// // $deleteSubdealerProfileRate = SubdealerProfileRate::where(['dealerid' => $dealer->dealerid])->delete();
// // dd($subdealerprofilerateData);
// foreach($subdealerprofilerateData as $profile){
// 	$name = $profile->name;
// // $profileName = ucfirst($profile->name);
// // if($request->has(''.$profileName)){
// //       $profileRate = $request->get(''.$profileName); // will get rate form request
// 	$profileRate = $profile->rate;
// 	$groupName = $profile->groupname;
// 	$checkmargin = ProfileMargins::where('dealerid',$dealer->dealerid)->where('sub_dealer_id','=',$profile->sub_dealer_id)->where('groupname','=',$groupName)->first();

// 	$marginByProfile = Profile::where('name',$name)->first();

// 	if(empty($checkmargin)){
// 		$margin = $marginByProfile->margin;
// 		$total_margin = $profileRate + $margin;

// 		$culsst = $total_margin * $sstfromdb;
// $sst =   $culsst;//number_format($culsst,2);
// $culadv = ($total_margin+$sst)* $advfromdb;

// $adv = $culadv; //number_format($culadv,2);
// $consumer = $total_margin + $sst + $adv;
// $multyply = $profileRate*2 + $sst + $adv;
// $final_rates = $multyply/2;

// }else{
// 	$margin = $checkmargin->margin;
// 	$total_margin = $profileRate + $margin;

// 	$culsst = $total_margin * $sstfromdb;
// $sst =   $culsst;//number_format($culsst,2);
// $culadv = ($total_margin+$sst)* $advfromdb;

// $adv = $culadv; //number_format($culadv,2);
// $consumer = $total_margin + $sst + $adv;
// $multyply = $profileRate*2 + $sst + $adv;
// $final_rates = $multyply/2;
// }
// // save into reseller profile rate.
// $subdealerProfileRateUpdate = SubdealerProfileRate::where('name',$profile->name)->where('dealerid',$profile->dealerid)->where('sub_dealer_id',$profile->sub_dealer_id);
// $subdealerProfileRateUpdate->update([
// 	'sst' => $sst,
// 	'adv_tax' => $adv,
// 	'sstpercentage' => $sstfromdb,
// 	'advpercentage' => $advfromdb,
// 	'final_rates' => $final_rates,
// 	'consumer' => $consumer
// ]);

// }
// subDealerProfileRate Update as per dealer sst adv change End....
////
$dlt = DealerFUP::where([
	"dealerid" => $dealer->dealerid,
])->delete();
$profileList = Profile::all();

foreach ($profileList as $profile) {
	$profilegroupname = $profile->groupname;
	if ($request->has("" . $profilegroupname)) {
		$profileRate = $request->get("" . $profilegroupname);
		$groupName = $profile->groupname;
		$userProfile = "BE-" . $groupName . "k";
		$datasave = $profileRate * 1073741824;

// will get rate form request

		$dealerFUP = new DealerFUP();
		$dealerFUP->resellerid = $dealer->resellerid;
		$dealerFUP->dealerid = $dealer->dealerid;
		$dealerFUP->manager_id = $dealer->manager_id;
		$dealerFUP->groupname = $groupName;
		$dealerFUP->datalimit = $profileRate * 1073741824;

		$dealerFUP->save();

// DB::table('user_info')->where('dealerid',$dealer->dealerid)->where('profile',$userProfile)->update('qt_total',$datasave);

		$userinfo = UserInfo::where(
			"dealerid",
			$dealer->dealerid
		)->where("profile", $userProfile);
		$userinfo->update([
			"qt_total" => $datasave,
		]);
	}
}
// update static ips
// $ipassign = $request->get("ipassign");
// $ipType = $request->get("ip_type");
// $numip = $request->get("noofip");
// $thisreseller = $request->get("resellerid");
$staticIpRates = $request->get("rates");
$staticIpRates_max = $request->get("rates_max");
// $thisdealer = $request->get("dealerid");
$thisusername = $request->get("username");

// if ($ipassign == "assign") {
// 	for ($i = 0; $i < $numip; $i++) {
// 		$ips = StaticIPServer::where([
// 			"dealerid" => $thisdealer,
// 			"type" => "static",
// 		])->count();
// 		$serverip = StaticIPServer::where(["type" => $ipType])
// 		->whereNull("dealerid")
// 		->where(["resellerid" => $thisreseller])
// 		->where(["status" => "NEW"])
// 		->first();
//         //
//         if(!$serverip){
//             session()->flash("error","No Static IP Found");
//             return back();
//         }
//         //
//         $userid = StaticIp::select("username")
//         ->where("username", $thisusername)
//         ->first();
//         //
//         $ip = $serverip["ipaddress"];
//         //
//         DB::table("static_ips_server")
//         ->where("ipaddress", $ip)
//         ->update(["dealerid" => $thisdealer]);
//         //
//         if ($userid) {
//            if ($ipType == "static") {
//             $updateIp = StaticIp::where(
//              "username",
//              $thisusername
//          );
//             $updateIp->update(["numberofips" => $ips + 1]);
//         }
//     } else {
//        $staticIP = new StaticIp();
//        $staticIP->username = $dealer->username;
//        $staticIP->numberofips = $numip;
//        $staticIP->rates = $staticIpRates;
//        $staticIP->max_ip_rate = $staticIpRates_max;
//        $staticIP->save();
//    }
// }
// } elseif ($ipassign == "remove") {
// 	for ($i = 0; $i < $numip; $i++) {
// 		$ips = StaticIPServer::where([
// 			"dealerid" => $thisdealer,
// 			"type" => "static",
// 		])->count();
// 		$serverip = StaticIPServer::where([
// 			"type" => $ipType,
// 			"status" => "NEW",
// 		])
// 		->where(["dealerid" => $thisdealer])
// 		->where(["resellerid" => $thisreseller])
// 		->where(["status" => "NEW"])
// 		->first();
//         //
// 		$userid = StaticIp::select("username")
// 		->where("username", $thisusername)
// 		->first();
//         //
// 		$ip = $serverip["ipaddress"];
// 		DB::table("static_ips_server")
// 		->where("ipaddress", $ip)
// 		->update(["dealerid" => null]);
// 		if ($userid["username"] == $thisusername) {
// 			if (
// 				$ipType == "static" &&
// 				$serverip["status"] == "NEW"
// 			) {
// 				$updateIp = StaticIp::where(
// 					"username",
// 					$thisusername
// 				);
// 				$updateIp->update(["numberofips" => $ips - 1]);
// 			}
// 		}
// 	}
// }
//
$staticIpExist = StaticIp::where('username',$thisusername)->first();
if($staticIpExist){
    $staticIpExist->rates = $staticIpRates;
    $staticIpExist->max_ip_rate = $staticIpRates_max;
    $staticIpExist->update_on = date('Y-m-d H:i:s');
    $staticIpExist->save();
}else{
    $staticIP = new StaticIp();
    $staticIP->username = $thisusername;
    $staticIP->rates = $staticIpRates;
    $staticIP->max_ip_rate = $staticIpRates_max;
    $staticIP->save();
}
//
//
// Allow never Expire Check
// $allowNeverExpire = $request->neverexpire;
// if ($allowNeverExpire == "on") {
// 	$isSetNeverExpre = UserInfo::where(
// 		"username",
// 		$dealer->username
// 	)
// 	->where("status", "dealer")
// 	->first();
// 	$isSetNeverExpre->update([
// 		"never_expire" => "yes",
// 	]);
// 	$never = NeverExpire::join(
// 		"user_info",
// 		"user_info.username",
// 		"never_expire.username"
// 	)
// 	->where("user_info.dealerid", $dealer->dealerid)
// 	->select(
// 		"never_expire.username",
// 		"never_expire.old_date"
// 	)
// 	->get();
// 	foreach ($never as $value) {
// // NeverExpire::whereIn('username',$never)->delete();
// 		$ss = NeverExpire::where("username", $value->username);
// 		$ss->update([
// 			"todate" => $value->old_date,
// 			"old_date" => null,
// 		]);
// 	}
// } else {
// 	$isSetNeverExpre = UserInfo::where(
// 		"username",
// 		$dealer->username
// 	)
// 	->where("status", "dealer")
// 	->first();
// 	$isSetNeverExpre->update([
// 		"never_expire" => null,
// 	]);
// 	$never = NeverExpire::join(
// 		"user_info",
// 		"user_info.username",
// 		"never_expire.username"
// 	)
// 	->where("user_info.dealerid", $dealer->dealerid)
// 	->select("never_expire.username", "never_expire.todate")
// 	->get();
// 	foreach ($never as $value) {
// // NeverExpire::whereIn('username',$never)->delete();
// 		$ss = NeverExpire::where("username", $value->username);
// 		$ss->update([
// 			"old_date" => $value->todate,
// 			"todate" => "2020-01-01",
// 		]);
// 	}
// }

//
// $dhcp_serverip = $request->dhcp_serverip;
// if (!empty($dhcp_serverip)) {
// 	$servers = explode(" ", $dhcp_serverip);
// 	$serverid = $servers[0];
// 	$servername = $servers[1];
// 	$dhcp_server = Dhcp_dealer_server::where(
// 		"dealerid",
// 		$dealer->dealerid
// 	)->first();

// 	if ($dhcp_server == null && empty($dhcp_server)) {
// 		$newdhcpEntry = new Dhcp_dealer_server();
// 		$newdhcpEntry->dealerid = $dealer->dealerid;
// 		$newdhcpEntry->server_id = $serverid;

// 		$newdhcpEntry->save();
// 	} else {
// 		$dhcp_server->server_id = $serverid;
// 		$dhcp_server->dealerid = $dealer->dealerid;
// 		$dhcp_server->save();
// 	}
// }


$showAmount = UserAmount::where(
	"username",
	$dealer->username
)->update([
	"isvisible" => $allowed,
]);
$userData = UserInfo::find($id);

$dealerid = $userData["dealerid"]; //DEALER ID
$pNames = $request->profileNames; // PROFILE NAME
$rates = $request->dastiAmount; //DASTI RATE
if ($rates) {
	foreach ($rates as $key => $dastiRate) {
		$dealerProfileRate = DealerProfileRate::where(
			"name",
			$pNames[$key]
		)->where("dealerid", $dealerid);
		$dealerProfileRate->update([
			"dasti_amount" => $rates[$key],
		]);
	}
}
// if ($billingType == "card") {
// 	$userStatusChangeOnBillingType = UserMenuAccess::where(
// 		"user_id",
// 		$dealer->id
// 	)->whereIn("sub_menu_id", [21, 32, 33]);
// 	$userStatusChangeOnBillingType->update([
// 		"status" => 0,
// 	]);
// } else {
// 	$userStatusChangeOnBillingType = UserMenuAccess::where(
// 		"user_id",
// 		$dealer->id
// 	)->whereIn("sub_menu_id", [21, 32, 33]);
// 	$userStatusChangeOnBillingType->update([
// 		"status" => 1,
// 	]);
// }
//
//
DB::table("assigned_nas")->where("id", $request->get("dealerid"))->delete();
//
$assigned_nas = new AssignedNas();
$assigned_nas->id = $request->get("dealerid");
$assigned_nas->nas = $request->get("nas");
$assigned_nas->save();
//
if(!empty($request->get("nas"))){
    $CTprof = ContractorTraderProfile::where('nas_shortname',$request->get("nas"))->first();

    if($CTprof){
        $dealer->name = $CTprof->contractor_profile;
        $dealer->profile = $CTprof->contractor_profile;
        $dealer->save();
    //
        $radusergroup = RaduserGroup::where('username',$dealer->username)->first();
        $radusergroup->groupname = $CTprof->contractor_profile;
        $radusergroup->name = $CTprof->contractor_profile;
        $radusergroup->save();
    }
}
//
session()->flash("success", "Congratulation! Contractor Successfully Updated.");
return redirect()->route("users.user.index1", [
	"status" => $status,
]);
break;
case "subdealer":
$subdealer = UserInfo::find($id);
$subdealerid = $subdealer->sub_dealer_id;
$dealerid = $subdealer->dealerid;
$trader = $request->get("traderAllow");
$verification = $request->get("Verification");


if (empty($trader)) {
	$allowtraders = "no";
} elseif ($trader == "on") {
	$allowtraders = "yes";
} else {
	$allowtraders = "no";
}

$checkDealer = DealerProfileRate::where([
	"dealerid" => $subdealer->dealerid,
])->first();
$allowplan = @$checkDealer->allowplan;
$dealertax = @$checkDealer->taxgroup;

if ($allowplan == "yes") {
	$tax = $request->get("tax");
} else {
	$tax = $dealertax;
}

$subdealer->firstname = $request->get("fname");
$subdealer->lastname = $request->get("lname");
$subdealer->address = $request->get("address");
$subdealer->mobilephone = $request->get("mobile_number");
$subdealer->homephone = $request->get("land_number");
$subdealer->nic = $request->get("nic");
$subdealer->email = $request->get("mail");
$subdealer->area = $request->get("area");
$subdealer->allow_invoice = $request->get('allow_invoice');
$subdealer->save();

// //cacti graph
CactiGraph::where([
	"user_id" => $subdealer->username,
])->delete();
$newarray = [];
$mydata = $request->get("graph");
for ($i = 0; $i < sizeof($mydata); $i++) {
	if ($mydata[$i] != null) {
		$graph1 = new CactiGraph();
		$graph1->user_id = $request->get("username");
		$graph1->graph_no = $mydata[$i];
		$graph1->save();
	}
}

///cacti graph end
$verifyOption = SubdealerProfileRate::where([
	"sub_dealer_id" => $subdealer->sub_dealer_id,
])->first();
if (!empty($verifyOption)) {
	$verifyOption = $verifyOption->verify;
} else {
	$verifyOption = null;
}

if ($verification == "on") {
    $verify = "yes";
} else {
    $verify = "no";
}

// delete existing profile rates of resller
// $dlt = SubdealerProfileRate::where([
// 	"sub_dealer_id" => $subdealer->sub_dealer_id,
// ])->delete();
// getting assigned profile rates
// getting assigned profile rates
$profileList = Profile::all();
if(empty($profileList))
{
    Session()->flash("error", "Profile Not Found!..");
    return back();
}

// foreach ($profileList as $profile) {
// 	$name = $profile->name;
// 	$profileName = ucfirst($profile->name);
// 	if ($request->has("" . $profileName)) {
// $profileRate = $request->get("" . $profileName); // will get rate form request
// //
// $basePrice = $request->get("bp" . $profileName);
// $basePriceET = $request->get($profileName);
// $ct_margin = $request->get("ctm" . $profileName);


// $groupName = $profile->groupname;
// $gname = $profile->name;
// $checkmargin = ProfileMargins::where(
// 	"dealerid",
// 	$dealerid
// )
// ->where("sub_dealer_id", "=", $subdealerid)
// ->where("name", "=", $gname)
// ->first();

// $get_Dealer_data = DealerProfileRate::where([
//     "dealerid" => $subdealer->dealerid,
//     'name' => $name
// ])->first();
//                         // dd($get_Dealer_data);
// if (empty($get_Dealer_data)) {
//     Session()->flash("error", "You have't any Profile Assigned by Dealer!..");
//     return back();
// }
// //
// if ($get_Dealer_data->base_price_ET > $basePriceET) {
//     Session()->flash("error", "Profile rate should be greator than or equal to Dealer rate");
//     return back();
// }if ($get_Dealer_data->base_price > $basePrice) {
//     Session()->flash("error", "Company Consumer Base Price should be greator than or equal to Dealer Company Consumer Base Price");
//     return back();
// }
// //
// if (empty($checkmargin)) {
//     $sale_price = $profileRate;
//     $profit_margin_dealer = $sale_price - $profileRate;
//     $sstfromdb = $get_Dealer_data->sstpercentage;
//     $advfromdb = $get_Dealer_data->advpercentage;
//     $ss_tax = $sale_price * $sstfromdb;
//     $after_ss_tax = $sale_price + $ss_tax;
//     $adv_tax = $after_ss_tax * $advfromdb;
//     $after_adv_tax = $after_ss_tax + $adv_tax;
//     $sst = $ss_tax;
//     $adv = $adv_tax;
//     $consumer = $sale_price + $sst + $adv;
//     $multyply = $profileRate * 2 + $sst + $adv;
//     $final_rates = $multyply / 2;

//     $profileMargin = new ProfileMargins();
//     $profileMargin->groupname = $groupName;
//     $profileMargin->name = $gname;
//     $profileMargin->manager_id = $subdealer->manager_id;
//     $profileMargin->resellerid = $subdealer->resellerid;
//     $profileMargin->dealerid = $subdealer->dealerid;
//     $profileMargin->sub_dealer_id = $subdealerid;
//     $profileMargin->trader_id = null;
//     $profileMargin->margin = $profile->margin;
//     $profileMargin->save();
// } else {

//     $sale_price = $profileRate;
//     $profit_margin_dealer = $sale_price - $profileRate;
//     $sstfromdb = $get_Dealer_data->sstpercentage;
//     $advfromdb = $get_Dealer_data->advpercentage;
//     $ss_tax = $sale_price * $sstfromdb;
//     $after_ss_tax = $sale_price + $ss_tax;
//     $adv_tax = $after_ss_tax * $advfromdb;
//     $after_adv_tax = $after_ss_tax + $adv_tax;
//     $sst = $ss_tax;
//     $adv = $adv_tax;
//     $consumer = $sale_price + $sst + $adv;
//     $multyply = $profileRate * 2 + $sst + $adv;
//     $final_rates = $multyply / 2;

// }

// // save into reseller profile rate.
// $subdealerProfileRate = new SubdealerProfileRate();
// $subdealerProfileRate->sub_dealer_id = $subdealerid;
// $subdealerProfileRate->dealerid = $dealerid;
// $subdealerProfileRate->groupname = $groupName;
// $subdealerProfileRate->name = $name;
// $subdealerProfileRate->taxgroup = "E";
// $subdealerProfileRate->rate = $basePrice;
// $subdealerProfileRate->sst = $sst;
// $subdealerProfileRate->sstpercentage = $sstfromdb;
// $subdealerProfileRate->advpercentage = $advfromdb;
// $subdealerProfileRate->adv_tax = $adv;
// $subdealerProfileRate->final_rates = $final_rates;
// $subdealerProfileRate->consumer = $consumer;
// $subdealerProfileRate->allow_trader = $allowtraders;
// $subdealerProfileRate->verify = $verify;
// $subdealerProfileRate->base_price = $basePrice;
// $subdealerProfileRate->base_price_ET = $basePriceET;
// $subdealerProfileRate->margin = $ct_margin;
// $subdealerProfileRate->save();

// }
// }

$subdealerProfileRate = SubdealerProfileRate::where('sub_dealer_id',$subdealerid);
$subdealerProfileRate->update([
    'taxgroup' => 'E',
    'verify' => $verify,
    'allow_trader' => $allowtraders,
]);

$isvisible = $request->isvisible;
if ($isvisible == "on") {
	$allowed = "yes";
} else {
	$allowed = "no";
}
$showAmount = UserAmount::where(
	"username",
	$subdealer->username
)->update([
	"isvisible" => $allowed,
]);
session()->flash("success", "Congratulation! Trader Successfully Updated.");
return redirect()->route("users.user.index1", [
	"status" => $status,
]);
break;
case "trader":
$trader = UserInfo::find($id);
$traderid = $trader->trader_id;
$dealerid = $trader->dealerid;
$subdealerid = $trader->sub_dealer_id;

$tax = "E";
$trader->firstname = $request->get("fname");
$trader->lastname = $request->get("lname");
$trader->address = $request->get("address");
$trader->mobilephone = $request->get("mobile_number");
$trader->homephone = $request->get("land_number");
$trader->nic = $request->get("nic");
$trader->email = $request->get("mail");
$trader->area = $request->get("area");
$trader->save();

// //cacti graph
CactiGraph::where(["user_id" => $trader->username])->delete();
$newarray = [];
$mydata = $request->get("graph");
for ($i = 0; $i < sizeof($mydata); $i++) {
	if ($mydata[$i] != null) {
		$graph1 = new CactiGraph();
		$graph1->user_id = $request->get("username");
		$graph1->graph_no = $mydata[$i];
		$graph1->save();
	}
}
$sstfromdb = DealerProfileRate::where('dealerid',$subdealer->dealerid)->first()->sstpercentage;
$advfromdb = DealerProfileRate::where('dealerid',$subdealer->dealerid)->first()->advpercentage;
// delete existing profile rates of resller
$dlt = TraderProfileRate::where([
	"trader_id" => $trader->trader_id,
])->delete();
// getting assigned profile rates
$profileList = Profile::all();
foreach ($profileList as $profile) {
	$profileName = ucfirst($profile->name);
	if ($request->has("" . $profileName)) {
$profileRate = $request->get("" . $profileName); // will get rate form request

$groupName = $profile->groupname;
$groupName = $profile->groupname;
$checkmargin = ProfileMargins::where(
	"dealerid",
	$dealerid
)
->where("sub_dealer_id", "=", $subdealerid)
->where("trader_id", "=", $traderid)
->where("groupname", "=", $groupName)
->first();

if (empty($checkmargin)) {
	$margin = $profile->margin;
	$total_margin = $profileRate + $margin;

// $culsst = $total_margin * 0.195;
// $sst = $culsst; //number_format($culsst,2);
// $culadv = ($total_margin + $sst) * 0.125;

	$culsst = $total_margin * $sstfromdb;
$sst =   $culsst;//number_format($culsst,2);
$culadv = ($total_margin+$sst)* $advfromdb;

$adv = $culadv; //number_format($culadv,2);
$consumer = $total_margin + $sst + $adv;
$multyply = $profileRate * 2 + $sst + $adv;
$final_rates = $multyply / 2;

$profileMargin = new ProfileMargins();
$profileMargin->groupname = $groupName;
$profileMargin->manager_id = $trader->manager_id;
$profileMargin->resellerid = $trader->resellerid;
$profileMargin->dealerid = $trader->dealerid;
$profileMargin->sub_dealer_id = $subdealerid;
$profileMargin->trader_id = $traderid;
$profileMargin->margin = $profile->margin;
$profileMargin->save();
} else {
	$margin = $checkmargin->margin;
	$total_margin = $profileRate + $margin;

// $culsst = $total_margin * 0.195;
// $sst = $culsst; //number_format($culsst,2);
// $culadv = ($total_margin + $sst) * 0.125;
	$culsst = $total_margin * $sstfromdb;
$sst =   $culsst;//number_format($culsst,2);
$culadv = ($total_margin+$sst)* $advfromdb;

$adv = $culadv; //number_format($culadv,2);
$consumer = $total_margin + $sst + $adv;
$multyply = $profileRate * 2 + $sst + $adv;
$final_rates = $multyply / 2;
}

// save into reseller profile rate.
$traderProfileRate = new TraderProfileRate();
$traderProfileRate->trader_id = $traderid;
$traderProfileRate->sub_dealer_id =
$trader->sub_dealer_id;
$traderProfileRate->dealerid = $dealerid;
$traderProfileRate->groupname = $groupName;
$traderProfileRate->taxgroup = $tax;
$traderProfileRate->rate = $profileRate;
$traderProfileRate->sst = $sst;
$traderProfileRate->adv_tax = $adv;
$traderProfileRate->final_rates = $final_rates;

$traderProfileRate->save();
}
}
session()->flash("success", "Congratulation! Trader Successfully Updated.");
return redirect()->route("users.user.index1", [
	"status" => $status,
]);
break;
case "user":
$get_dealer_data_rate = DealerProfileRate::where(['dealerid'=> Auth::user()->dealerid,'name'=>$request['profile']])->first();
if(empty($get_dealer_data_rate))
{
    Session()->flash("error", "Dealer Profile Not Found!..");
    return back();
}
$get_dealer_data_base = $get_dealer_data_rate->base_price;


$base_price = $request->get('base_price');
$data = $request->validate(['base_price' => 'required']);
//
if (Auth::user()->status == "dealer") {
    if ( ($request->get('base_price') > 0) &&  ($get_dealer_data_rate->base_price_ET > $base_price) ) {
        Session()->flash("error", "Base price should be greator than or equal to company rate");
        return back();
    }
    $profile_amount = $get_dealer_data_base;

} elseif (Auth::user()->status == 'subdealer') {
    $get_subdealer_data = SubdealerProfileRate::where(['name' => $request['profile'], 'dealerid' => Auth::user()->dealerid, 'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
    //
    if ( ($request->get('base_price') > 0) &&  ($get_subdealer_data->base_price_ET > $base_price) ) {
        Session()->flash("error", "Base price should be greator than or equal to company rate");
        return back();
    }
    $profile_amount = $get_subdealer_data->base_price;
}


$user = UserInfo::find($id);
if(empty($user))
{
    Session()->flash("error", "User Not Found!..");
    return back();
}
                //end
$static_ip = 0;
if(!empty($request->get("static_ip")) && !empty($request->get("ipassign")) && $request->get("ipassign") == 'assign')
{
    $static_ip = $request->get("static_ip_amount");
    //
    $StaticIp_info = StaticIp::where("username", Auth::user()->dealerid)->first();
        //
    if(empty($static_ip) || $static_ip <= 0){
        $static_ip = $StaticIp_info->rates;
    }else{
        if($static_ip > $StaticIp_info->max_ip_rate){
            Session()->flash("error", "Maximum IP rate is ".$StaticIp_info->max_ip_rate);
            return back();
        } if($static_ip < $StaticIp_info->rates){
            Session()->flash("error", "IP rate should be greater than or equal to company rate.");
            return back();
        }
    }
    //
    $staticip = StaticIPServer::where([
        "ipaddress" => $request->get("static_ip"),
        "status" => "NEW",
        "userid" => NULL,
    ])->first();
    if($staticip){
        $staticip->userid = $user->username;
        $staticip->status = "USED";
        $staticip->save();
    }
    $user->static_ip_amount =$static_ip ;

}else if(!empty($request->get("ipassign")) && $request->get("ipassign") == 'remove'){
    $staticip = StaticIPServer::where([
        "userid" => $user->username
    ])->first();
    if($staticip){
        $staticip->userid = NULL;
        $staticip->status = "NEW";
        $staticip->save();
    }
    $user->static_ip_amount = 0;
}
//
$user->firstname = $request->get("fname");
$user->lastname = $request->get("lname");
$user->address = $request->get("address");
$user->mobilephone = $request->get("mobile_number");
$user->homephone = $request->get("land_number");
$user->nic = $request->get("nic");
$user->email = $request->get("mail");
$user->updated_by = Auth::user()->id;
$user->updated_on = date('Y-m-d H:i:s');
                      // billing work

                //end
$profileRequestGroupname = Profile::where('name', $request->get("profile"))->first();
$profilePrefix = Domain::where('resellerid', $user->resellerid)->first();
                $user->profile = $profileRequestGroupname->groupname; //// like LOGON-10mb
                $checkname = DealerProfileRate::where(
                    "dealerid",
                    $user->dealerid
                )
                ->where("name", $request->get("profile"))
                ->first();

      // billing work
                if(empty($checkname))
                {
                    Session()->flash("error", "Dealer Profile Not Found!..");
                    return back();
                }
                    //end
                $name = $checkname["name"];
                $user->name = $profileRequestGroupname->name;
                $user->never_expire = $request->get("neverexpire");
                $user->taxprofile = $request->get("taxprofile");
                      // billing work
                if($request->get("base_price") > 0){
                    $user->profile_amount = $request->get("base_price");
                    $user->company_rate = 'no';
                }else{
                 $user->profile_amount = $profile_amount;
                 $user->company_rate = 'yes';
             }
                //end
             $user->save();

                //
                // Never Expire New Code Start
                $next_neverexpire_date = $request->nextexpire; // Fetch data from field
                $nextDate = date("Y-m-t", strtotime($next_neverexpire_date)); //set last date of month
                if ($next_neverexpire_date) {
                    $isAv = DB::select(
                        "select `username` from `never_expire` where `username` = ?",
                        [$user->username]
                    ); // select data if exist
                    if ($isAv) {
                        DB::update(
                            "update `never_expire` set  `todate` = ?, `last_update` = ? where `username` = ?",
                            [$nextDate, NOW(), $user->username]
                        ); // update date if Exist
                    } else {
                        DB::insert(
                            "insert into `never_expire` ( `username`, `todate`,`last_update`) VALUES (?, ?, ?)",
                            [$user->username, $nextDate, NOW()]
                        ); // insert date if not Exist
                    }
                }

                // End of Never Expire Code
                //

                // session()->flash("success", "Congratulation! Consumer Successfully updated.");
                //
                // return redirect()->to($request->url);
                Session()->flash("success", "Updated Successfully");
                return back();
                //
                break;
                default:
                return redirect()->route("users.dashboard");
            }
        }

// online users
// public function loadOnLineUsers(Request $request){
// 	$currentUserr = Auth::user();
// 	$status = $currentUser->status;
// 	switch($status){
// 		case : "dealer"{

// 		}break;
// 		case : "sub_dealer"{}break;
// 	}

// }

//Aslam Code

        public function singalUserData(Request $request)
        {
           $v = "";
           $usersCollection = [];
           $dealerid = Auth::user()->dealerid;
           $subdealerid = Auth::user()->sub_dealer_id;
           $trader_id = Auth::user()->trader_id;
           $currentStatus = Auth::user()->status;
           $sbdealer = $request->get("sb");
           $id = $request->get("id");
           $st = $request->get("st");
           $tb = $request->get("tb");
           if ($id == "" || $st == "dealer") {
              return "false";
          }
          $output = "";
          if ($currentStatus == "dealer") {
              $data = userInfo::where("dealerid", $dealerid)
              ->where("id", $id)
              ->where("status", "user")
              ->select(
                 "mobilephone",
                 "username",
                 "resellerid",
                 "dealerid",
                 "sub_dealer_id",
                 "nic"
             )
              ->first();
          } elseif ($currentStatus == "subdealer") {
              $data = userInfo::where("sub_dealer_id", $subdealerid)
              ->where("id", $id)
              ->select(
                 "mobilephone",
                 "username",
                 "resellerid",
                 "dealerid",
                 "sub_dealer_id",
                 "nic"
             )
              ->first();
          } elseif ($currentStatus == "trader") {
              $data = userInfo::where("trader_id", $trader_id)
              ->where("id", $id)
              ->select(
                 "mobilephone",
                 "username",
                 "resellerid",
                 "dealerid",
                 "sub_dealer_id",
                 "nic",
                 "trader_id"
             )
              ->first();
          } elseif ($currentStatus == "inhouse") {
              $data = userInfo::where("id", $id)
              ->select(
                 "mobilephone",
                 "username",
                 "resellerid",
                 "dealerid",
                 "sub_dealer_id",
                 "nic"
             )
              ->first();
          }

          $verifyCode = "";
          $up_on = "";
          $status = "";
          $resendCode = UserVerification::where("username", $data["username"])
          ->select("verificationCode", "update_on", "mobile_status")
          ->first();
          $verifyCode = $resendCode["verificationCode"];
          $up_on = $resendCode["update_on"];
          $status = $resendCode["mobile_status"];
          $curdate = date("Y-m-d H:i:s");
          if ($curdate < $up_on) {
              if ($status == "" && $verifyCode != "") {
                 return "false";
             }
         } elseif ($status == "" && $verifyCode != "") {
          return "false";
      }

      $verimob = "";
      $vericnic = "";
      $isverify = UserVerification::where("username", $data["username"])
      ->select("mobile_status", "cnic")
      ->first();
      $verimob = $isverify["mobile_status"];
      $vericnic = $isverify["cnic"];
      ?>
      <!-- Mobile Verification -->
      <input type="hidden" name="uName" id="uName" value="<?php echo $data[
          "username"
          ]; ?>">
          <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data[
             "dealerid"
             ]; ?>">
             <input type="hidden" name="subDid" id="subDid" value="<?php echo $data[
                "sub_dealer_id"
                ]; ?>">
                <input type="hidden" name="resId" id="resId" value="<?php echo $data[
                   "resellerid"
                   ]; ?>">
                   <input type="hidden" name="nic" id="nic" value="<?php echo $data["nic"]; ?>">
                   <input  class="form-control" name="mobile" type="text" id="edit1" value="" size="7" maxlength="7"  minlength="7" required>
                   <div class="input-group-btn">
                      <button type="submit" class="btn btn-primary" style="background-color: #4878C0; color:white" >
                         <span class="glyphicon glyphicon-check"></span> Verify
                     </button>
                 </div>

                 <?php
             }
             public function singalUserData1(Request $request)
             {
               $v = "";
               $usersCollection = [];
               $dealerid = Auth::user()->dealerid;
               $subdealerid = Auth::user()->sub_dealer_id;
               $currentStatus = Auth::user()->status;

               $id = $request->get("id");

               $output = "";
               if ($currentStatus == "dealer") {
                  $data = userInfo::where("id", $id)
                  ->select(
                     "mobilephone",
                     "username",
                     "resellerid",
                     "dealerid",
                     "sub_dealer_id",
                     "nic"
                 )
                  ->first();
              } elseif ($currentStatus == "subdealer") {
                  $data = userInfo::where("sub_dealer_id", $subdealerid)
                  ->where("id", $id)
                  ->select(
                     "mobilephone",
                     "username",
                     "resellerid",
                     "dealerid",
                     "sub_dealer_id",
                     "nic"
                 )
                  ->first();
              }

              $verifyCode = "";
              $up_on = "";
              $status = "";
              $resendCode = UserVerification::where("username", $data["username"])
              ->select("verificationCode", "update_on", "mobile_status")
              ->first();
              $verifyCode = $resendCode["verificationCode"];
              $up_on = $resendCode["update_on"];
              $status = $resendCode["mobile_status"];
              $curdate = date("Y-m-d H:i:s");
              if ($curdate < $up_on) {
                  if ($status == "" && $verifyCode != "") {
                     return "false";
                 }
             } elseif ($status == "" && $verifyCode != "") {
              return "false";
          }

          $verimob = "";
          $vericnic = "";
          $isverify = UserVerification::where("username", $data["username"])
          ->select("mobile_status", "cnic")
          ->first();
          $verimob = $isverify["mobile_status"];
          $vericnic = $isverify["cnic"];
          ?>
          <!-- Mobile Verification -->
          <input type="hidden" name="uName" id="uName" value="<?php echo $data[
              "username"
              ]; ?>">
              <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data[
                 "dealerid"
                 ]; ?>">
                 <input type="hidden" name="subDid" id="subDid" value="<?php echo $data[
                    "sub_dealer_id"
                    ]; ?>">
                    <input type="hidden" name="resId" id="resId" value="<?php echo $data[
                       "resellerid"
                       ]; ?>">
                       <input type="hidden" name="nic" id="nic" value="<?php echo $data["nic"]; ?>">
                       <input  class="form-control" name="mobile" type="text" id="edit1" value="" size="7" maxlength="7"  minlength="7" required>
                       <div class="input-group-btn">
                          <button type="submit" class="btn btn-primary" style="background-color: #4878C0; color:white" >
                             <span class="glyphicon glyphicon-check"></span> Verify
                         </button>
                     </div>

                     <?php
                 }

// ////////
                 public function clearMac(Request $request)
                 {
                   $username = $request->get("clearmac");
                   $userid = $request->get("userid");

                   $user = UserInfo::where(["username" => $username])->first();

                   $userRadCheck = RadCheck::where([
                      "username" => $user->username,
                      "attribute" => "Cleartext-Password",
                  ])->first();
                   $userstatusinfo = UserStatusInfo::where([
                      "username" => $user->username,
                  ])->first();
                   $userexpirelog = UserExpireLog::where([
                      "username" => $user->username,
                  ])->first();
                   $package = $user->profile;

                   $package = str_replace("BE-", "", $package);
                   $package = str_replace("k", "", $package);
                   $profile = Profile::where(["groupname" => $package])->first();

                   $clearMac = RadCheck::where([
                      "username" => $username,
                      "attribute" => "Calling-Station-Id",
                  ])->first();
                   $clearMac->value = "NEW";
                   $clearMac->save();

//  return redirect()->route("users.user.show", [
//   "status" => "user",
//   "id" => $userid,
// ]);
                   return back()->with('success','Mac Has been Cleared.');
               }
////

////enable disabled
               public function enableDisable(Request $request)
               {
                   $username = $request->get("username");
                   $userid = $request->get("userid");

                   $getprofile = RaduserGroup::select("groupname")
                   ->where(["username" => $username])
                   ->first();
                   $profile = $getprofile->groupname;
                   if ($profile == "DISABLED") {
//
                      $userInfo = UserInfo::where(["username" => $username])->first();
                      $oldprofile = $userInfo->disabled_old_profile;
//
                      $userInfo->profile = $oldprofile;
                      $userInfo->disabled_old_profile = null;

                      $userInfo->save();
//
                      $radusergroup = RaduserGroup::where([
                         "username" => $username,
                     ])->first();

  //
                      $profileInfo = Profile::where('groupname',$oldprofile)->first();
                      if($profileInfo){
                        $profName = $profileInfo->name;
                    }else{
                        $profName = $oldprofile;
                    }
  //
                    $package = $oldprofile;

/////////// comment Talha end /////////////
                    $radusergroup->groupname = $package;
                    $radusergroup->name = $profName;
                    $radusergroup->save();
//
                    $disabled_user = DisabledUser::where([
                     "username" => $username,
                 ])->first();
                    $disabled_user->status = "enable";
                    $disabled_user->updated_by = Auth::user()->status;
                    $disabled_user->last_update = date("Y-m-d H:i:s");
                    $disabled_user->save();
//
                } else {
//
                  $radusergroup = RaduserGroup::where([
                     "username" => $username,
                 ])->first();
                  $radusergroup->groupname = "DISABLED";
                  $radusergroup->name  = 'DISABLED';
                  $radusergroup->save();
//
                  $userInfo = UserInfo::where(["username" => $username])->first();
                  $profile = $userInfo->profile;
                  $userInfo->disabled_old_profile = $getprofile->groupname;
                  $userInfo->profile = "DISABLED";

                  $userInfo->save();
//
                  $disabled_user = DisabledUser::where([
                     "username" => $username,
                 ])->first();
                  if ($disabled_user != null) {
                     $disabled_user->status = "disable";
                     $disabled_user->updated_by = Auth::user()->status;
                     $disabled_user->last_update = date("Y-m-d H:i:s");
                     $disabled_user->save();
                 } else {
                     $insert = new DisabledUser();
                     $insert->username = $username;
                     $insert->status = "disable";
                     $insert->updated_by = Auth::user()->status;
                     $insert->last_update = date("Y-m-d H:i:s");
                     $insert->save();
                 }
//
             }
             $url = "https://api-radius.logon.com.pk/kick/user-dc-api.php?username=" . $username;

             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, "$url");
//
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
             $result = curl_exec($ch);

             return redirect()->route("users.user.show", [
              "status" => "user",
              "id" => $userid,
          ]);
         }
         public function changePass(Request $request)
         {

            if(MyFunctions::is_freezed(Auth::user()->username)){
                Session()->flash("error", "Your panel has been freezed");
                return back();
            }

            $uName = $request->get("user");
            $repass = $request->get("repass");
            $pass = Hash::make($request->get("pass"));
            $redpass = $request->get("pass");

        #Match The Old Password
            if(!Hash::check($request->get('password'), auth()->user()->password)){

                return redirect()->route("users.dashboard")->with(
                    "error",
                    "Old-Password doesn't matched."
                );
            }

            $userinfo = UserInfo::where("username", $uName);
            $userinfo->update([
                "password" => $pass,
            ]);

            if(Auth::user()->status != 'user'){
            //
                $redcheck = RadCheck::where("username", $uName)->where(
                    "attribute",
                    "Cleartext-Password"
                );
                $redcheck->update([
                    "value" => $redpass,
                ]);
                //
            }

            return redirect()->route("users.dashboard")->with(
                "success",
                "Password has been changed."
            );


        }
///

        public function changePlan(Request $request)
        {
           $username = $request->get("username");
           $plan = $request->get("plan");

           $today = date("Y-m-d H:i:s");
           $today1 = strtotime($today);
           if ($today < date("Y-m-25 12:00:00")) {
              $plandate = date("Y-m-25");
          } else {
              $plandate = date("Y-m-25", strtotime("+1 month", $today1));
          }

          $userdata = UserInfo::where("username", $username)->first();
          $userAmount = UserAmount::where("username", $username)->first();

          $checkDealerPlan = DealerProfileRate::where(
              "dealerid",
              $userdata->dealerid
          )->first();
          $currentplan = $checkDealerPlan["taxgroup"];
          if ($plan == $currentplan) {
              session()->flash(
                 "error",
                 "Your current plan and requrest plan both are same."
             );
              return redirect()->route("users.user.index1", ["status" => "plan"]);
          }

          $amount = $userAmount->amount;

          if ($amount > 5000) {
              $allready = ChangePlan::where("username", $username)
              ->where("change_plan", $plandate)
              ->first();
              if (empty($allready)) {
                 $changeplan = new ChangePlan();
                 $changeplan->username = $username;
                 $changeplan->dealerid = $userdata->dealerid;
                 $changeplan->sub_dealer_id = $userdata->sub_dealer_id;
                 $changeplan->change_plan = $plandate;
                 $changeplan->request_date = date("Y-m-d H:i:s");
                 $changeplan->amount = 5000;
                 $changeplan->plan_name = $plan;
                 $changeplan->request_by = Auth::user()->username;
                 $changeplan->status = 1;
                 $changeplan->save();

                 $userAmount->amount = $amount - 5000;
                 $userAmount->save();
             } else {
                 $status = $allready->status;
                 if ($status == "1") {
                    session()->flash(
                       "error",
                       "Your Request are Allready in Process."
                   );
                    return redirect()->route("users.user.index1", [
                       "status" => "plan",
                   ]);
                } else {
                    $changeplan->username = $username;
                    $changeplan->dealerid = $userdata->dealerid;
                    $changeplan->sub_dealer_id = $userdata->sub_dealer_id;
                    $changeplan->change_plan = $plandate;
                    $changeplan->request_date = date("Y-m-d H:i:s");
                    $changeplan->amount = 5000;
                    $changeplan->plan_name = $plan;
                    $changeplan->request_by = Auth::user()->username;
                    $changeplan->status = 1;
                    $changeplan->save();

                    $userAmount->amount = $amount - 5000;
                    $userAmount->save();
                }
            }
        } else {
          session()->flash("error", "Your Amount is less then 5,000.");
          return redirect()->route("users.user.index1", ["status" => "plan"]);
      }

      session()->flash("success", "Plan success fully updated.");
      return redirect()->route("users.user.index1", ["status" => "plan"]);
  }

  public function userTerminated(Request $requrest)
  {
   $username = $requrest->get("username");
   $expireUsers = ExpireUser::where(["username" => $username])->first();
   $expireUsers->connection = "terminate";
   $expireUsers->save();
   $pdf = PDF::loadView("users.billing.terminate_PDF");
   return $pdf->stream($username . ".pdf");
}
public function cirProfile(Request $request)
{
   $username = $request->username;
   $profile = $request->profile;
   $status = $request->check;
   $name = $request->name;
   $group = ['NEW','EXPIRED','TERMINATE','DISABLED'];

   $checkGroup = RaduserGroup::where('username',$username)->first();
   if(in_array($checkGroup->groupname,$group)){
// Do Nothing....
   }else{
//Do This...
      if($status == 'true'){
//store/update data in ciruserprofile table
         $newUser = Cirprofile::updateOrCreate([
//Add unique field combo to match here
            'username'   => $username,
        ],[
            'profile'     => $profile,
            'name'       => $name,
            'status' => 1
        ]);
//update profile radius
         $updateProfile = RaduserGroup::where('username',$username);
         $updateProfile->update([
            'groupname' => $profile.'-p',
            'name' => 'pure-'.$name
        ]);
//API Call for DC user
         $url='http://192.168.100.103/api/user-dc-api.php?username='.$username;
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "$url");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $result = curl_exec($ch);
     }
     else{
//store/update data in ciruserprofile table
         $newUser = Cirprofile::updateOrCreate([
//Add unique field combo to match here
            'username'   => $username,
        ],[
            'profile'     => $profile,
            'name'       => $name,
            'status' => 0
        ]);
//update profile radius
         $updateProfile = RaduserGroup::where('username',$username);
         $updateProfile->update([
            'groupname' => $profile,
            'name' => $name
        ]);
//API Call for DC user
         $url='http://192.168.100.103/api/user-dc-api.php?username='.$username;
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "$url");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $result = curl_exec($ch);

     }
 }
}

public function amount_profile(Request $request)
{
   $get_profile = $request->get('profileName');
   $get_status = $request->get('status');
   $get_price = '';
   if ($get_status == 'dealer') {
      $get_dealer_base_price = DealerProfileRate::where(['name' => $get_profile, 'dealerid' => Auth::user()->dealerid])->first();
      $get_price = $get_dealer_base_price->base_price;
  } elseif ($get_status == 'subdealer') {

      $get_sub_dealer_base_price = SubdealerProfileRate::where(['name' => $get_profile, 'dealerid' => Auth::user()->dealerid, 'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
      $get_price = $get_sub_dealer_base_price->rate;
  }

  return $get_price;

}
//end
public function view_billing_history()
{

	return view('users.bill-history.view_bill_history');
}
//
public function create_access($userid,$status){
    if($status == 'user'){
        $subMenu = SubMenu::where('submenu','Dashboard')->get();
    }else{
        $subMenu = SubMenu::get();
    }
    //
    foreach ($subMenu as $key => $submenu){
       //
        $accessMenu = new UserMenuAccess();
        $accessMenu->user_id = $userid;
        $accessMenu->sub_menu_id = $submenu->id;
        if($submenu->id == 1 ){
            $accessMenu->status = 1;
        }
        $accessMenu->save();
    }
}


//////
public function change_user_pass(Request $request)
{

    $user = $request->get("user");
    $redpass = $request->get('pass');

    $redcheck = RadCheck::where('username', $user)->where(['attribute'=>'Cleartext-Password'])
    ->update(['value' => $redpass]);

    return back()->with( "success","Password has been changed Successfully.");
}
///////////



public function dataremove(Request $request){
    $username =$request->username;
    $nic =$request->nic;
    $mobile =$request->mobile;
    $image =$request->image;
    $reset = '';
    //
    if(empty($username)){
     return back()->with( "error","Please type username");
 }
    //
 $exist = UserVerification::where(['username'=>$username])->first();
 if(!$exist){
     return back()->with( "error","Consumer unverified");
 }
    //
 if($nic =="on"){
  $removedata = UserVerification::where(['username'=>$username])->first();
  $removedata->cnic =NULL;
  $removedata->save();
  $reset .= 'NIC';
}
    //

if($mobile =="on"){
    $removedata = UserVerification::where(['username'=>$username])->first();
    $removedata->mobile =NULL;
    $removedata->mobile_status =NULL;
    $removedata->verificationCode =NULL;
    $removedata->save();
    $reset .= ',Mobile';
}
//
if($image =="on"){
    $removedata = UserVerification::where(['username'=>$username])->first();
    $removedata->nic_front =NULL;
    $removedata->nic_back =NULL;
    $removedata->save();
    $reset .= ',Images';
    //
    $frontImg = 'UploadedNic/'.$username.'-front.jpg';
    $backImg = 'UploadedNic/'.$username.'-back.jpg';
    //

    if(file_exists(public_path().'/'.$frontImg)){
        unlink($frontImg);
    }if(file_exists(public_path().'/'.$backImg)){
        unlink($backImg);
    }
}

//
DB::table('verification_reset_log')->insert(['username' => $username, 'action_by' => Auth::user()->id, 'reset' => $reset]);
//
return back()->with( "success","Reset Successfully.");

}




public function get_contractor_trader_profiles(Request $request) {
// This will be an array of selected NAS values
  $nasArray = $request->nas;

// Fetch records matching any of the NAS values
  $get_table = ContractorTraderProfile::whereIn('nas_shortname', $nasArray)->get();

// Extract contractor and trader profiles from all matching records
  $contractor_profiles = [];
  $trader_profiles = [];

  foreach ($get_table as $item) {
      $contractor_profiles[] = $item->contractor_profile;
      $trader_profiles[] = $item->trader_profile;
  }

  return response()->json([
      'contractors' => $contractor_profiles,
      'traders' => $trader_profiles,
  ]);

}




// -------- Online view user ---------------
public function onlineUsers_view() {
    return view("users.dealer.Online_User.online_user");
}

// use DataTables;

public function onlineUsers_get_table(Request $request)
{

    $manager_id = Auth::user()->manager_id ?? null;
    $resellerid = Auth::user()->resellerid ?? null;
    $dealerid = Auth::user()->dealerid ?? $request->contractor;
    $sub_dealer_id = Auth::user()->sub_dealer_id ?? $request->trader;
    $searchFilter = $request->searchFilter;
    $dateFilter = $request->dateFilter;
    // $IpFilter = $request->IpFilter;
    // dd($manager_id, $resellerid, $dealerid, $sub_dealer_id);

    $whereArray = [];
    if ($manager_id) {
        $whereArray[] = ['manager_id', $manager_id];
    }
    if ($resellerid) {
        $whereArray[] = ['resellerid', $resellerid];
    }
    if ($dealerid) {
        $whereArray[] = ['dealerid', $dealerid];
    }
    if ($sub_dealer_id) {
        $whereArray[] = ['sub_dealer_id', $sub_dealer_id];
    }
    //
    $Users = UserInfo::where($whereArray)
    ->when(!empty($searchFilter), function ($query) use ($searchFilter) {
        $query->where(function ($subQuery) use ($searchFilter) {
            $subQuery->where('username', 'LIKE', '%' . $searchFilter . '%')
                ->orWhere('firstname', 'LIKE', '%' . $searchFilter . '%')
                ->orWhere('lastname', 'LIKE', '%' . $searchFilter . '%')
                // ->orWhere('nic', 'LIKE', '%' . $searchFilter . '%')
                ->orWhere('address', 'LIKE', '%' . $searchFilter . '%');
                // ->orWhere('city', 'LIKE', '%' . $searchFilter . '%')
                // ->orWhere('permanent_address', 'LIKE', '%' . $searchFilter . '%');
        });
    })
    ->where('status','user')
    ->whereIn('username', function ($query) {
        $query->select('username')
        ->from('user_status_info')
        ->where('card_expire_on', '>', now()->subDays(60));
    })->select('username')->get()->toArray();
    //
    // dd($Users);
    $query = RadAcct::where('acctstoptime', null)
    ->whereIn('username', $Users)
    ->select('username','acctstarttime');

    // if($IpFilter){
    //     $query->where('framedipaddress',$IpFilter);
    // }
    if($dateFilter){
        $query->whereDate('acctstarttime',$dateFilter);
    }
    //
    return DataTables::of($query)
    ->addColumn('username', function ($user) {
        $userStatusInfo = UserStatusInfo::where('username', $user->username)->select('expire_datetime')->first();
        $isExpiredOrToday = $userStatusInfo && strtotime($userStatusInfo->expire_datetime) <= strtotime(now() );
        return $isExpiredOrToday ? "{$user->username}<span style='color:red'><br><small>(Expired)</small></span>" : $user->username;
    })
    ->addColumn('firstname', function ($user) {
        $userInfo = UserInfo::where('username', $user->username)->select('firstname','lastname')->first();
        return $userInfo->firstname . ' ' . $userInfo->lastname;
    })
    ->addColumn('address', function ($user) {
        $userInfo = UserInfo::where('username', $user->username)->select('address')->first();
        return $userInfo->address;
    })
    ->addColumn('login_time', function ($user) {
        $radAcct = RadAcct::where('username', $user->username)
        ->whereNull('acctstoptime')
        ->orderBy('acctstarttime', 'DESC')
        ->first();
        return $radAcct ? date('M d,Y H:i:s', strtotime($radAcct->acctstarttime)) : '-';
    })
    ->addColumn('session_time', function ($user) {
        return $this->getSessionTime($user->acctstarttime);
    })
    ->addColumn('sub_dealer_id', function ($user) {
        $userInfo = UserInfo::where('username', $user->username)->select('dealerid','sub_dealer_id')->first();
        return $userInfo->sub_dealer_id ?: ($userInfo->dealerid . ' (Contractor)');
    })
    ->addColumn('framedipaddress', function ($user) {
        $radAcct = RadAcct::where('username', $user->username)
        ->whereNull('acctstoptime')
        ->orderBy('acctstarttime', 'DESC')
        ->first();
        return $radAcct->framedipaddress ?? '-';
    })
    ->addColumn('data_usage', function ($user) {
        $radAcct = RadAcct::where('username', $user->username)
        ->whereNull('acctstoptime')
        ->orderBy('acctstarttime', 'DESC')
        ->first();
        return $radAcct ? $this->ByteSize($radAcct->acctoutputoctets) . ' | ' . $this->ByteSize($radAcct->acctinputoctets) : '-';
    })
    ->addColumn('dynamic_ips', function ($user) {
        $radAcct = RadAcct::where('username', $user->username)
        ->whereNull('acctstoptime')
        ->orderBy('acctstarttime', 'DESC')
        ->first();
        return $radAcct ? $this->getDynamicIP($radAcct->callingstationid) : '-';
        //
    })
        ->rawColumns(['username','dynamic_ips']) // Allow raw HTML for the username column
        ->make(true);
    }
//


public function getSessionTime($givenDatetime)
{
    // $dtF = new DateTime('@0');
    // $dtT = new DateTime("@$seconds");
    // return $dtF->diff($dtT)->format('%a Days : %h Hrs : %i Mins %s Secs');

    $currentDatetime = new DateTime(); // Current datetime
    $givenDatetime = new DateTime($givenDatetime); // Convert input datetime string to DateTime object

    // Calculate the difference
    $interval = $currentDatetime->diff($givenDatetime);

    // Format the result
    $differenceString = sprintf(
        "%d Days : %d Hrs : %d Mins : %d Secs",
        $interval->days,
        $interval->h,
        $interval->i,
        $interval->s
    );

    return $differenceString;

}

public function getDynamicIP($mac)
{
    return '<button type="button" class="btn btn-primary" onclick="getIP(this, \'' . $mac . '\');">Dynamic (IP)</button>';
}
// -------- End Online view user ------------



}
