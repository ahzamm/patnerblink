<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
class SearchControllerAslam extends Controller
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
    <div class="notice-icon">
        <i class="fa fa-user" style="background-color: #fe6501;"></i>
    </div>
    <div>
        <span class="name">
            <small>Username : </small><strong><?php echo $username;?> <span  style="float: right;" class="badge"><?php echo $status;?></span></strong><br>
            <small>Full Name : <?php echo $fullname;?></small><br>
            <small>Dealer : <?php echo $dealerid;?> | Sub Dealer : <?php echo $subdealer;?></small><br>
            <small>Address : <?php echo $address;?></small>

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
}