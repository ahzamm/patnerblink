<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\UserStatusInfo;
use Validator;
use Session;
use App\SendMessage;
use App\model\Users\User_Support;
use App\model\Users\userAccess;
use App\model\Users\RaduserGroup;
use App\model\Users\RadCheck;
use App\model\Users\Radreply;
use App\model\Users\SubMenu;
use App\model\Users\UserMenuAccess;
use App\model\Users\NotificationAllow;
use App\model\Users\Domain;
use App\MyFunctions;




class SupportManagementController extends Controller
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
    $status =  Auth::user()->status;
    if($status == 'user'){
      return redirect()->route('users.dashboard');
  }elseif($status == 'reseller' || $status == 'inhouse' ){
    $allData = UserInfo::where('status','inhouse')->where('resellerid',Auth::user()->resellerid)->where('dealerid',NULL)->get();        
    return view('users.AccessManagement.viewUsers',[
        'allData' => $allData
    ]);
}elseif($status == 'manager' ){
    $allData = UserInfo::where('status','inhouse')->where('manager_id',Auth::user()->manager_id)->where('resellerid',NULL)->where('dealerid',NULL)->get();        
    return view('users.AccessManagement.viewUsers',[
        'allData' => $allData
    ]);
}elseif($status == 'dealer'){
	$allData = UserInfo::where('status','inhouse')->where('resellerid',Auth::user()->resellerid)->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',NULL)->get();
	return view('users.AccessManagement.viewUsers',[
		'allData' => $allData
	]);
}elseif($status == 'subdealer' && Auth::user()->sub_dealer_id != NULL){
    $allData = UserInfo::where('status','inhouse')->where('resellerid',Auth::user()->resellerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->get();
    return view('users.AccessManagement.viewUsers',[
        'allData' => $allData
    ]);
}
}


public function store(Request $request){

    if(MyFunctions::is_freezed(Auth::user()->username)){
        Session()->flash("error", "Your panel has been freezed");
        return back();      
    }

    // check allowed users
    $dealerid = Auth::user()->dealerid;
    $subdealerid = Auth::user()->sub_dealer_id;
    $resellerid = Auth::user()->resellerid;
    $managerid = Auth::user()->manager_id;
    //
    $maxNumber = array('manager' => 15, 'reseller' => 15, 'dealer' => 4, 'subdealer' => 4);
    //
    $manager_id = (empty(Auth::user()->manager_id)) ? NULL : Auth::user()->manager_id;
    $resellerid = (empty(Auth::user()->resellerid)) ? NULL : Auth::user()->resellerid;
    $dealerid = (empty(Auth::user()->dealerid)) ? NULL : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? NULL : Auth::user()->sub_dealer_id;
    $trader_id = (empty(Auth::user()->trader_id)) ? NULL : Auth::user()->trader_id;
   //
    $whereArray = array();
    //
    // if(!empty($manager_id)){
        array_push($whereArray,array('manager_id' , $manager_id));
    // }if(!empty($resellerid)){
        array_push($whereArray,array('resellerid' , $resellerid));
    // }if(!empty($dealerid)){
        array_push($whereArray,array('dealerid' , $dealerid));
    // }if(!empty($sub_dealer_id)){
        array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));   
    // }
   //
    $checkAllowed = UserInfo::where($whereArray)->where('status','inhouse')->count();
        if($checkAllowed >= $maxNumber[Auth::user()->status]){
             return redirect()->route('users.manage.supportView')
            ->with('error','You can not create more then '.$maxNumber[Auth::user()->status].' helpdesk account');
    }


    // if($resellerid == NULL && $managerid !=NULL){
    //     $checkAllowed = UserInfo::where('manager_id',$managerid)->where('status','inhouse')->count();
    //     if($checkAllowed >= 15){
    //         return redirect()->route('users.manage.supportView')
    //         ->with('error','You Can not Create More then 4 Inhouse Account');
    //     }
    // }
    // if($dealerid == NULL && $resellerid !=NULL){
    //     $checkAllowed = UserInfo::where('resellerid',$resellerid)->where('status','inhouse')->count();
    //     if($checkAllowed >= 15){
    //         return redirect()->route('users.manage.supportView')
    //         ->with('error','You Can not Create More then 4 Inhouse Account');
    //     }
    // }
    // if($dealerid != NULL && $subdealerid == NULL){
    //     $checkAllowed = UserInfo::where('dealerid',$dealerid)->where('status','inhouse')->count();
    //     if($checkAllowed >= 4){
    //         return redirect()->route('users.manage.supportView')
    //         ->with('error','You Can not Create More then 2 Inhouse Account');
    //     }
    // }
    // if($dealerid != NULL && $subdealerid != NULL){
    //     $checkAllowed = UserInfo::where('dealerid',$dealerid)->where('sub_dealer_id',$subdealerid)->where('status','inhouse')->count();
    //     if($checkAllowed >= 4){
    //         return redirect()->route('users.manage.supportView')
    //         ->with('error','You Can not Create More then 1 Inhouse Account');
    //     }
    // }




$checkAllowed = UserInfo::where('username',$request->get('username'))->first();
if(!empty($checkAllowed)){
    return redirect()->route('users.manage.supportView')
    ->with('error','Username Already exist...!');
}
    //
if(empty(Auth::user()->resellerid)){
    $domain = Domain::where('resellerid',NULL)->where('manager_id',Auth::user()->manager_id)->first();
}else{
    $domain = Domain::where('resellerid',Auth::user()->resellerid)->first();  
}
    //
    // Store Data to database
$addData = new UserInfo();
$addData->username = $request->get('username');
$addData->password = Hash::make($request->get('password'));
$addData->domainID = $domain->id;
$addData->firstname = $request->get('fname');
$addData->lastname = $request->get('lname');
$addData->nic = $request->get('nic');
$addData->email = $request->get('mail');
$addData->address = $request->get('address');
$addData->mobilephone = $request->get('mobile_number');
$addData->status = 'inhouse';
$addData->homephone = $request->get('land_number');
$addData->manager_id = Auth::user()->manager_id;
$addData->resellerid = Auth::user()->resellerid == '' ? NULL : Auth::user()->resellerid;
$addData->dealerid = Auth::user()->dealerid == '' ? NULL : Auth::user()->dealerid;
$addData->sub_dealer_id = Auth::user()->sub_dealer_id == '' ? NULL : Auth::user()->sub_dealer_id;
$addData->active = 1;
$addData->creationdate = date("Y-m-d H:i:s");
$addData->creationby = Auth::user()->username;
$addData->creationbyip = $request->ip();

$addData->save();
$userId = $addData->id;

$subMenu = SubMenu::all();
foreach ($subMenu as $key => $submenu) {
    $accessMenu = new UserMenuAccess();
    $accessMenu->user_id = $userId;
    $accessMenu->sub_menu_id = $submenu->id;
    $accessMenu->status = 0;
    $accessMenu->created_at = NOW();
    $accessMenu->save();
}

return redirect()->route('users.manage.supportView')
->with('success','User created successfully!!');
}


public function edit(Request $request){
    //
    $id = $request->id;
    $data = UserInfo::find($id);

    $isNotify = NotificationAllow::where('username',$data->username)->first();
    //  dd($isNotify->allow);
    $notify = $isNotify == NULL ? 'no' : $isNotify->allow;
    //  dd($notify);
    $notify = $notify == 1 ? 'checked' : '';
    ?>
    <form action="userUpdate/<?php echo $data->id?>" method="get" autocomplete="off">

        <div class="row">
            <div class="col-md-4">
                <div class="form-group position-relative">
                    <label  class="form-label">First Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="<?php echo $data->firstname?>" name="fname" class="form-control"  placeholder="firstname" required>
                </div>
                <div class="form-group position-relative">
                    <label  class="form-label">Last Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="<?php echo $data->lastname?>" name="lname" class="form-control"  placeholder="lastname" required>
                </div>
                <div class="form-group position-relative">
                    <label  class="form-label">CNIC Number <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="tel" value="<?php echo $data->nic?>" name="nic" class="form-control"  data-mask="99999-9999999-9" placeholder="99999-9999999-9" required>
                </div>
                <?php if(Auth::user()->username == 'logonbroadband'){?>
                    <div class="form-group">
                        <label  class="form-label">Allow Notification</label><br>

                        <input type="checkbox" <?=$notify?> class="lcs_check" name="allowNotification" id="allowNotification"  autocomplete="off" />
                    </div>
                            <!-- <div class="form-group">
                                <label  class="form-label">Allow Notification</label><br>
                                <label class="switch" style="width: 46px;height: 19px;" >
                                <input type="checkbox" <=$notify?> name="allowNotification" id="allowNotification" >
                                <span class="slider square" ></span>
                                 </label>
                             </div> -->
                         <?php } ?>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label  class="form-label">Email Address <span style="color: red">*</span></label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="email" value="<?php echo $data->email?>" name="mail" class="form-control"  placeholder="info@lbi.net.pk" required>
                        </div>

                        <div class="form-group position-relative">
                            <label  class="form-label">Mobile Number <span style="color: red">*</span></label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="text" name="mobile_number" value="<?php echo $data->mobilephone?>" class="form-control" placeholder="9999 9999999"  data-mask="9999 9999999" required>
                        </div>
                        <div class="form-group position-relative">
                            <label  class="form-label">Landline Number</label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="text" value="<?php echo $data->homephone?>" name="land_number" class="form-control" placeholder="(999)9999999" data-mask="(999)9999999">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group position-relative">
                            <label  class="form-label">Department <span style="color: red">*</span></label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'dpartment_assgin');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="text" value="<?php echo $data->address?>" name="address" class="form-control"  placeholder="Department" required>
                        </div>
                        <div class="form-group position-relative">
                            <label  class="form-label">Username <span style="color: red">*</span></label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'user_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="text" value="<?php echo $data->username?>" class="form-control"  placeholder="User Name" name="username" readonly>
                        </div>
                        <div class="form-group position-relative" style="position:relative">
                            <label  class="form-label">Update Password</label>
                            <span class="helping-mark" onmouseenter="popup_function(this, 'change_password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                            <input type="Password" name="password" value="" class="form-control"  placeholder="Must be 8 characters long" id="update_password" >
                            <i class="fa fa-eye-slash update_password" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass('update_password')"> </i>
                        </div>




                    </div>


                    <!--  -->
                    <div class="col-md-12">
                        <div class="form-group pull-right" style="margin-bottom:0">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>

                </div>
            </form>

            <?php

        }

/////////////////////////////////////////

        public function update($id,Request $request){


            if(MyFunctions::is_freezed(Auth::user()->username)){
                Session()->flash("error", "Your panel has been freezed");
                return back();      
            }
    //
            $isChecked = $request->allowNotification;
            if($isChecked == "on"){
                $isChecked = 1;
            }else{
                $isChecked = 0;
            }

            $updateData = UserInfo::find($id);

            $updateData->username = $request->get('username');
            $updateData->password = Hash::make($request->get('password'));
            $updateData->firstname = $request->get('fname');
            $updateData->lastname = $request->get('lname');
            $updateData->nic = $request->get('nic');
            $updateData->email = $request->get('mail');
            $updateData->address = $request->get('address');
            $updateData->mobilephone = $request->get('mobile_number');
            $updateData->status = 'inhouse';
            $updateData->homephone = $request->get('land_number');
            $updateData->save();

            $isNotify = NotificationAllow::where('username',$request->username)->first();
            if(!empty($isNotify)){
                $isNotify->allow = $isChecked;
                $isNotify->save();
            }else{
                $isNotify = new NotificationAllow();
                $isNotify->username = $request->username;
                $isNotify->allow = $isChecked;
                $isNotify->save();
            }
            return redirect()->route('users.manage.supportView');
        }




        public function delete(Request $request)
        {
            if(MyFunctions::is_freezed(Auth::user()->username)){
                Session()->flash("error", "Your panel has been freezed");
                return back();      
            }
    //
            $id = $request->get('id');
            UserInfo::find($id)->delete();
            return "true";
		//return redirect()->route('users.manage.supportView');
        }


        public function allow_Access()
        {
            $status =  Auth::user()->status;
            if($status == 'user'){
                return redirect()->route('users.dashboard');
            }elseif($status == 'manager' || $status == 'inhouse'){
                $allData = UserInfo::where('manager_id',Auth::user()->manager_id)->where('status','inhouse')->where('resellerid',NULL)->get();
            }elseif($status == 'reseller' || $status == 'inhouse'){
                $allData = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','inhouse')->where('dealerid',NULL)->get();
            }elseif($status == 'dealer' && Auth::user()->sub_dealer_id == NULL){
                $allData = UserInfo::where('status','inhouse')->where('resellerid',Auth::user()->resellerid)->where('dealerid',Auth::user()->dealerid)->where('sub_dealer_id',NULL)->get();
            }elseif($status == 'subdealer' && Auth::user()->sub_dealer_id != NULL){
                $allData = UserInfo::where('status','inhouse')->where('resellerid',Auth::user()->resellerid)->where('sub_dealer_id',Auth::user()->sub_dealer_id)->get();
            }
            return view('users.AccessManagement.allowAccess',[
                'allData' => $allData
            ]);
        }
        public function userAccess(Request $request){
    // $id = $request->get('id');
    // $userColumn = 'user'.$id;
    // $check = $request->get('isChecked');
    // $child = $request->get('child');
    // if($check == 'true'){
    //     DB::update('update userAccess set '.$userColumn.' = ? where childModule = ?',[1,$child]);
    // }else{
    //     DB::update('update userAccess set '.$userColumn.' = ? where childModule = ?',[0,$child]);
    // }
    // return $check;
        }
        public function view($id){
            $data = UserInfo::find($id);

            return view('users.AccessManagement.view-agent',compact('data'));

        }

        public  function agent_account_disable(Request $request)
        {
            $id=$request->id;
            $status=$request->status;

            $current_status = 0;
            if($status == 0)
            {
                $current_status=1;
                $get_user_data = UserInfo::find($id);

                $get_user_data->update(['account_disabled'=>$current_status]);

                return response()->json(['success'=>'Account Has Been Disable']);
            }
            else{
                $get_user_data = UserInfo::find($id);

                $get_user_data->update(['account_disabled'=>$current_status]);

                return response()->json(['success'=>'Account Has Been Active']);
            }


        }

    }