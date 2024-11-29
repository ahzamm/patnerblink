<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\MacVendor;
use App\model\Users\RadAcct;
use App\model\Users\RadCheck;
use App\model\Users\UserStatusInfo;
use App\model\Users\UserExpireLog;
use App\model\Users\Profile;
use App\model\Users\UserVerification;
class SupportSearchController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
// public function index(Request $request){

    public function index(Request $request){
        // 
        $status = Auth::user()->status;
        $user =$request->get('user');
        // 
        $output  = DB::table('user_info')
        ->where('username', 'like', '%'.$user . '%')->where('status','=','user')
        ->orwhere('dealerid', 'like', '%'.$user . '%')->where('status','=','user')
        ->orwhere('sub_dealer_id', 'like', '%'.$user . '%')->where('status','=','user')
        ->orwhere('address', 'like', '%'.$user . '%')->where('status','=','user')
        ->orwhere('firstname', 'like', '%'.$user . '%')->where('status','=','user')
        ->orwhere('lastname', 'like', '%'.$user . '%')->where('status','=','user')
        ->orderBy('status', 'ASC')->take(30)->get();
        
        if($output->count() > 0 ){
        foreach ($output as $key ) {
        $id=$key->id;
        $username=$key->username;
        $status=$key->status;
        $fullname=$key->firstname." ".$key->lastname;
        $dealerid=$key->dealerid;
        $subdealer=$key->sub_dealer_id;
        $address=$key->address;
        ?>
        
        <li class="unread available"> <!-- available: success, warning, info, error -->
        <a href="#" onclick="searchResult('<?php echo $username;?>');" style="color: #666"> 
            <div style="padding-top:12px;padding-left:30px;font-family:serif;font-size:19px" class="col-lg-12 text-left">
        <span class="name">
            <small > </small><span style="color: #000;font-size: 16px">Consumer (ID):</span> <strong style="color: green"><?php echo $username;?></strong> |
            <small> <span style="color: #000">Full Name:</span> <?php echo $fullname;?></small>
        </span>
    </div>
        </a>
        </li>
        <?php
        }
        }else{
        ?>
        <li class="unread available">
            <div class="notice-icon">
                <center>
                <span>No result found !</span>
                </center>
            </div>
        </li>
        <?php
        }
        }

function searchResult(Request $request){
     $username = $request->get('username');
    //  echo json_encode($username);
     $data = UserInfo::select('user_info.*','user_status_info.card_expire_on','user_status_info.card_charge_on',
     'user_verification.mobile','user_verification.cnic','user_verification.nic_back','user_verification.nic_front','user_verification.mobile_status')
    ->leftjoin('user_status_info','user_info.username', '=', 'user_status_info.username')
    ->leftjoin('user_verification','user_info.username','=','user_verification.username')
    ->where('user_info.username',$username)
     ->first();

     echo json_encode($data);

}
function showupDownGraph(Request $request){
    $id = $request->get('id');
    $graph = UserInfo::find($id);
    
    $totalDownload = RadAcct::where(['username' => $graph->username])->sum('acctoutputoctets');
    $tDownMonth= RadAcct::where('username', $graph->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctoutputoctets');
    $totalupload = RadAcct::where(['username' => $graph->username])->sum('acctinputoctets');
    $tupMonth= RadAcct::where('username', $graph->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctinputoctets');
   ?>
    <div id="" class="text-center">
        <div class="row">
            <div class="col-md-6" style="margin-top: 0px;">
                <center>
                    <h3>Total Internet Data (Download)</h3>
                    <i class="fa fa-download" style="font-size: 40px;"></i>
                    <h3> <?= $this->ByteSize($totalDownload);?></h3>
                </center>
            </div>
            <div class="col-md-6" style="margin-top: 0px;">
                <center>
                    <h3>Total Internet Data (Upload)</h3>
                    <i class="fa fa-upload" style="font-size: 40px;"></i>
                    <h3><?= $this->ByteSize($totalupload)?></h3>
                </center>
            </div>
            
            <div class="col-md-12" style="margin-top: 0px; overflow-x: auto;">
                <table class="table table-bordered" style="overflow: auto;">
                    <?php
                    $mac = RadCheck::where(['username' => $graph->username, 'attribute'=>'Calling-Station-Id'])->first();
                   ?>
                    <tr >
                        <th style="text-align:center">Total Login Session</th>
                        <th style="text-align:center">Last Login Date & Time</th>
                        <th style="text-align:center">Monthly Internet Data Usage</th>
                        
                        <th style="text-align:center">Interface MAC Address
                            <br>
                            
                        </th>
                <th>Nating IP Address</th>
                <th>Assign Dynamic IP Address</th>
            </tr>
            <?php
            $total_login = RadAcct::where(['username' => $graph->username])->count();
            $login = RadAcct::where(['acctstoptime' => NULL,'username' => $graph->username])->first();
            $mac = RadCheck::where(['username' => $graph->username, 'attribute'=>'Calling-Station-Id'])->first();
            $lastlogin = RadAcct::where(['username' => $graph->username])->orderBy('radacctid','desc')->first();
            if($lastlogin){
            $lastlogin=$lastlogin->acctstarttime;
        }else{
        $lastlogin='Not Yet Login';
    }
    
    ?>
    <tr>
        <td><?= $total_login?></td>
        <td><?= $lastlogin?></td>
        <td><?= $this->ByteSize($tDownMonth)?> | <?= $this->ByteSize($tupMonth)?></td>
        
        <td><?=$mac->value?>
        <?php
                            if($mac->value!='NEW'){
                            ?>
                            <form id="macForm">
                                <input type="hidden" name="clearmac"  value="<?= $graph->username?>">
                                <input type="hidden" name="userid" value="<?= $graph->id?>">
                                <input type="submit" class="btn btn-success" value="Clear MAC Address">
                            </form>
                            <?php } ?>
        <hr>
        <?php   
        if($mac->value!="NEW"){
           
            $mv=explode('-', $mac->value);
            $my_mac = $mv[0].'-'.$mv[1].'-'.$mv[2];
            $vendor=MacVendor::where('oui' , '=' , $my_mac )->first();
            if($vendor != null){
            $vendor=$vendor->vendor;
        }else{$vendor='No Vendor Found'; }
        ?>
        <span style="color: green"><?= $vendor?></span>
        <?php } ?>
    </td>
    <?php if(!empty($login)){?>
    <td><?= $login->framedipaddress?></td>
    <?php }else{ ?>
    <td>Not Available</td>
    
    <?php } ?>
    <td>Not Available</td>
</tr>
</table>
</div>
</div>
</div>
   
<?php

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
public function SupportclearMac(Request $request){

   $username = $request->get('clearmac');
    $userid = $request->get('userid');

    $user = UserInfo::where(["username" => $username])->first();

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


    // return redirect()->route('users.dashboard');
    echo json_encode($userid);


}

}
?>