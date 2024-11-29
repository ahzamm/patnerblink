<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;


class SearchEngineController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(Request $request){
// 
$status = Auth::user()->status;
$supportdealerid = Auth::user()->dealerid;
$supportSubdealerid = Auth::user()->sub_dealer_id;
$user =$request->get('user');

// 
if($status == 'manager'){

$manager_id = Auth::user()->manager_id;

    $output = UserInfo::where('manager_id', '=' ,$manager_id)->where('username','like',"%".$user."%")
    ->orWhere('address','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
    ->orWhere('dealerid','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
    ->orWhere('sub_dealer_id','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
    ->orWhere('firstname','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
    ->orWhere('lastname','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
    ->orWhere('resellerid','like',"%".$user."%")->where('manager_id', '=' ,$manager_id)
     ->orderBy('status', 'ASC')->take(30)->get();

}elseif($status == 'reseller'){

$resellerid = Auth::user()->resellerid;

    $output = UserInfo::where('resellerid', '=' ,$resellerid)->where('username','like',"%".$user."%")
    ->orWhere('address','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
    ->orWhere('dealerid','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
    ->orWhere('sub_dealer_id','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
    ->orWhere('firstname','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
    ->orWhere('lastname','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
    ->orWhere('resellerid','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
     ->orderBy('status', 'ASC')->take(30)->get();


}elseif($status == 'dealer'){

	 $dealerid = Auth::user()->dealerid;

	 $output = UserInfo::where('dealerid', '=' ,$dealerid)->where('username','like',"%".$user."%")
	 ->orWhere('address','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
	 ->orWhere('dealerid','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
	 ->orWhere('sub_dealer_id','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
	 ->orWhere('firstname','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
	 ->orWhere('lastname','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
	 ->orderBy('status', 'ASC')->take(30)->get();
	 
}else if($status == 'subdealer'){

$sub_dealer_id = Auth::user()->sub_dealer_id;

    $output = UserInfo::where('sub_dealer_id', '=' ,$sub_dealer_id)->where('username','like',"%".$user."%")
    ->orWhere('address','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('sub_dealer_id','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('firstname','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('lastname','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
     ->orderBy('status', 'ASC')->take(30)->get();

}elseif($status == 'inhouse'  && $supportSubdealerid != ''){

    $sub_dealer_id = Auth::user()->sub_dealer_id;

    $output = UserInfo::where('sub_dealer_id', '=' ,$sub_dealer_id)->where('username','like',"%".$user."%")
    ->orWhere('address','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('sub_dealer_id','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('firstname','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
    ->orWhere('lastname','like',"%".$user."%")->where('sub_dealer_id', '=' ,$sub_dealer_id)
     ->orderBy('status', 'ASC')->take(30)->get();
    
    
    }elseif($status == 'inhouse'  && $supportSubdealerid == ''){

        $dealerid = Auth::user()->dealerid;

        $output = UserInfo::where('dealerid', '=' ,$dealerid)->where('username','like',"%".$user."%")
        ->orWhere('address','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
        ->orWhere('dealerid','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
        ->orWhere('sub_dealer_id','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
        ->orWhere('firstname','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
        ->orWhere('lastname','like',"%".$user."%")->where('dealerid', '=' ,$dealerid)
        ->orderBy('status', 'ASC')->take(30)->get();
        
        }elseif($status == 'inhouse'){

            $resellerid = Auth::user()->resellerid;

            $output = UserInfo::where('resellerid', '=' ,$resellerid)->where('username','like',"%".$user."%")
            ->orWhere('address','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
            ->orWhere('dealerid','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
            ->orWhere('sub_dealer_id','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
            ->orWhere('firstname','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
            ->orWhere('lastname','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
            ->orWhere('resellerid','like',"%".$user."%")->where('resellerid', '=' ,$resellerid)
             ->orderBy('status', 'ASC')->take(30)->get();
            
            }

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
<a href="<?php echo url('/users/user/'.$status.'?id='.$id); ?>">
    <div style="display:flex; align-items:start; justify-content:flex-start">
        <div class="notice-icon">
            <i class="lab la-searchengin" style="color: #25b512;font-size: 36px;"></i>
        </div>

        <div style="flex:1">
            <span class="name">
                <small><b>Consumer (ID)</b> : </small><strong><?php echo $username;?> </strong><br>
                <small><b>Full Name</b> : <?php echo $fullname;?></small><br>
                <small><b>Address</b> : <?php echo $address;?></small><br>
                <small><b>Internet Profile</b> : </small><br>
                <small><b>CNIC Number</b> : </small><br>
                <small><b>Mobile Number</b> : </small><br>
                <small><b>Expiry Date</b> : </small><br>
                <small><b>Contractor (ID)</b> : <?php echo $dealerid;?></small><br>
                <small><b>Trader (ID)</b> : <?php echo $subdealer;?></small><br>
            </span>
            <!-- <span class="name">
                <small>Username : </small><strong><php echo $username;?> <span  style="float: right;" class="badge"><php echo $status;?></span></strong><br>
                <small>Full Name : <php echo $fullname;?></small><br>
                <small>Dealer : <php echo $dealerid;?> | Sub Dealer : <php echo $subdealer;?></small><br>
                <small>Address : <php echo $address;?></small>

            </span> -->
        </div>
        <div>
                    <span  style="border: 1px solid #002d6d;padding: 4px 8px;color: #002d6d;font-weight: 400;text-transform: uppercase;font-size: 14px;" class="">
                        <?php
                            if($status == 'dealer'){
                                echo 'Contractor';
                            }
                            else if($status == 'subdealer'){
                                echo 'Trader';
                            }
                            else if($status == 'inhouse'){
                                echo 'Helpdesk';
                            }
                            else if($status == 'user'){
                                echo 'Consumer';
                            }
                            else{
                                echo $status;
                            }
                        ?>
                    </span><br>
                </div>
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
}