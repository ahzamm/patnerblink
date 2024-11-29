<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\RaduserGroup;       
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\AssignNasType;
use App\model\Users\RadCheck;
use App\model\Users\Radreply;
use App\model\Users\UserAmount;
use App\model\Users\CactiGraph;



class ApprovedNewUserController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{}
public function index(){
    $data = UserInfo::where('active',0)->where('resellerid',Auth::user()->resellerid)->whereNotIn('status',['user','inhouse'])->get();
    $numOfUser = count($data);
    if($numOfUser != 0){
    foreach ($data as $key => $value) {
	?>
	<li class="unread available"> <!-- available: success, warning, info, error -->

    <div class="notice-icon">
        <i class="fa fa-check-circle" style="background-color: #fe6501;"></i>
    </div>
    <div>
        <span class="name">
        <input type="hidden" value="<?=$numOfUser?>" id="numUser">
            <small><strong>Username :</strong>  </small><strong><?= $value->username?></strong><br>
            <small><strong>Full Name :</strong> <?= $value->firstname.' '.$value->lastname?></small><br>
            <small><strong>Reseller :</strong> <?= $value->resellerid?> </small><br>
            <small><strong>Dealer :</strong> <?= $value->dealerid?> | <strong>Sub Dealer :</strong> <?= $value->sub_dealer_id?></small>  <br>
            <small><strong>Status :</strong> <?= $value->status?> </small> | <small><strong>Mobile :</strong> <?= $value->mobilephone?> </small><br>
            <small><strong>Address :</strong>  <?= $value->permanent_address?></small>
			
		<span  style="float: right;" class="">
		<button onclick="approveUser(<?=$value->id?>)" class="btn btn-link" style="color:green;font-weight:bold">Approve</button>
			<button onclick="rejectUser(<?=$value->id?>)" class="btn btn-link" style="color:red;font-weight:bold">Reject</button><br>
		</span>
        </span>
    </div>

</li>
<?php
    }
}else{
    ?>
    <li class="unread available">
        <div class="notice-icon">
            <center>
            <span>No New Notification!</span>
            </center>
        </div>
    </li>
    <?php
}
}
public function approvedNewUserNotification()
{
    $data = UserInfo::where('active',0)->where('resellerid',Auth::user()->resellerid)->whereNotIn('status',['user','inhouse'])->count();
    return response()->json($data);
}
// update data while reseller/dealer/subdealer/trader approve
public function approveNewUserPost(Request $request)
{
    $id = $request->id;
    $userInfo = UserInfo::find($id);
    $username = $userInfo->username;

    $updateData = UserInfo::where('username',$username);
    $updateData->update([
        'active' => 1
    ]);
    $updateData = RaduserGroup::where('username',$username);
    $updateData->update([
        'groupname' => 'DISABLED',
        'name' => 'DISABLED'
    ]);

    return response()->json('done');
}
public function rejectNewUserPost(Request $request)
{
    $id = $request->id;
    $userInfo = UserInfo::find($id);
    $username = $userInfo->username;
    $status = $userInfo->status;

    if($status == 'reseller'){
        $deletedRows = UserInfo::where('username', $username)->delete();
        $deletedRows = UserAmount::where('username', $username)->delete();
        $deletedRows = RadCheck::where('username', $username)->delete();
        $deletedRows = Radreply::where('username', $username)->delete();
        $deletedRows = RaduserGroup::where('username', $username)->delete();
        $deletedRows = AssignNasType::where('resellerid', $userInfo->resellerid)->where('dealerid','')->delete();
        
        $getIP = UserIPStatus::where('username', $username)->select('ip')->first();
        $u_u_ip = UserUsualIP::where('ip',$getIP->ip);
        $u_u_ip->update([
            'status' => 0
        ]);

        $deletedRows = UserIPStatus::where('username', $username)->delete();

    }else if($status == 'dealer'){
        $deletedRows = UserInfo::where('username', $username)->delete();
        $deletedRows = UserAmount::where('username', $username)->delete();
        $deletedRows = RadCheck::where('username', $username)->delete();
        $deletedRows = Radreply::where('username', $username)->delete();
        $deletedRows = RaduserGroup::where('username', $username)->delete();
        $deletedRows = CactiGraph::where('user_id', $username)->delete();

        $deletedRows = AssignNasType::where('resellerid', $userInfo->resellerid)->where('dealerid',$userInfo->dealerid)->where('sub_dealer_id','')->delete();
        
        $getIP = UserIPStatus::where('username', $username)->select('ip')->first();
        $u_u_ip = UserUsualIP::where('ip',$getIP->ip);
        $u_u_ip->update([
            'status' => 0
        ]);

        $deletedRows = UserIPStatus::where('username', $username)->delete();
    }else if($status == 'subdealer'){
        $deletedRows = UserInfo::where('username', $username)->delete();
        $deletedRows = UserAmount::where('username', $username)->delete();
        $deletedRows = RadCheck::where('username', $username)->delete();
        $deletedRows = Radreply::where('username', $username)->delete();
        $deletedRows = RaduserGroup::where('username', $username)->delete();
        $deletedRows = CactiGraph::where('user_id', $username)->delete();

        $deletedRows = AssignNasType::where('resellerid', $userInfo->resellerid)->where('dealerid',$userInfo->dealerid)->where('sub_dealer_id',$userInfo->sub_dealer_id)->where('trader_id','')->delete();
        $getIP = UserIPStatus::where('username', $username)->select('ip')->first();
        $u_u_ip = UserUsualIP::where('ip',$getIP->ip);
        $u_u_ip->update([
            'status' => 0
        ]);

        $deletedRows = UserIPStatus::where('username', $username)->delete();
    }else if($status == 'trader'){
        $deletedRows = UserInfo::where('username', $username)->delete();
        $deletedRows = UserAmount::where('username', $username)->delete();
        $deletedRows = RadCheck::where('username', $username)->delete();
        $deletedRows = Radreply::where('username', $username)->delete();
        $deletedRows = RaduserGroup::where('username', $username)->delete();
        $deletedRows = CactiGraph::where('user_id', $username)->delete();

        $deletedRows = AssignNasType::where('resellerid', $userInfo->resellerid)->where('dealerid',$userInfo->dealerid)->where('sub_dealer_id',$userInfo->sub_dealer_id)->where('trader_id',$userInfo->trader_id)->delete();
        $getIP = UserIPStatus::where('username', $username)->select('ip')->first();
        $u_u_ip = UserUsualIP::where('ip',$getIP->ip);
        $u_u_ip->update([
            'status' => 0
        ]);

        $deletedRows = UserIPStatus::where('username', $username)->delete();
    }
    return response()->json($username);
}
}