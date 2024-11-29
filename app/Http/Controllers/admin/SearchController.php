<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
class SearchController extends Controller
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
$user =$request->get('user');
// 
$output  = DB::table('user_info')
->where('username', 'like', '%'.$user . '%')
->orwhere('dealerid', 'like', '%'.$user . '%')
->orwhere('sub_dealer_id', 'like', '%'.$user . '%')
->orwhere('address', 'like', '%'.$user . '%')
->orwhere('firstname', 'like', '%'.$user . '%')
->orwhere('lastname', 'like', '%'.$user . '%')
->orderBy('status', 'ASC')->take(30)->get();

// 
// 
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
<a href="<?php echo url('/admin/user/'.$status.'?id='.$id); ?>">
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