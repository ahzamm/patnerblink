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
use App\model\Users\SubdealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\RaduserGroup;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserStatusInfo;
use App\model\Users\UserUsualIP;
use App\model\Users\UserAmount;
use App\model\Users\Profile;
use App\model\Users\ProfileTax;
use App\model\Users\CactiGraph;
use App\model\Users\Nas;
use App\model\Users\AssignNasType;
use App\model\Users\UserExpireLog;
use App\model\Users\ExpireUser;
use App\model\Users\StaticIPServer;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\DisabledUser;
use Session;
use App\model\Users\FreezeAccount;
use App\model\Users\ChangePlan;
use App\model\Users\userAccess;
use Validator;
use App\SendMessage;
use Eihror\Compress\Compress;


class userPanelController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {
   
 }

   public function show(){
    
      // $id = $request->get('id');
      $id = 3;
      $status = 'user';
      if($id){
       switch($status){
        
    case "user" : {
      $user = UserInfo::find(Auth::user()->id);
      $status = Auth::user()->status;
    if($status == "user"){

   if(Auth::user()->dealerid == $user->dealerid ){
    $userRadCheck = RadCheck::where(["username" => $user->username, "attribute" => "Cleartext-Password"])->first();
    $userstatusinfo = UserStatusInfo::where(["username" => $user->username])->first();
    $userexpirelog = UserExpireLog::where(["username" => $user->username])->first();
    $package= $user->profile;

    $package = str_replace('BE-', '', $package);
    $package = str_replace('k', '', $package);
    $profile = Profile::where(['groupname'=>$package])->first();

    $cur_pro = RaduserGroup::select('groupname')->where(["username" => $user->username])->first();
    $package = str_replace('BE-', '', $cur_pro->groupname);
    $package = str_replace('k', '', $package);
    $cur_pro = Profile::where(['groupname'=>$package])->first();
    //
    // daily data usage
    $download = RadAcct::select('acctoutputoctets','acctstarttime')->where('acctstarttime','>=',date('Y-m-01 00:00:00'))->where('acctstarttime','<=',date('Y-m-t 00:00:00'))->where(["username" => $user->username])->get();
    //
    $upload = RadAcct::select('acctinputoctets','acctstarttime')->where('acctstarttime','>=',date('Y-m-01 00:00:00'))->where('acctstarttime','<=',date('Y-m-t 00:00:00'))->where(["username" => $user->username])->get();

    return view('users.userPanelView.user_detail',[
      'user' => $user,
      'userRedCheck' => $userRadCheck,
      'userstatusinfo' => $userstatusinfo,
      'userexpirelog' => $userexpirelog,
      'profile' =>$profile,
      'cur_profile' => $cur_pro,
      'download' => $download,
      'upload' => $upload,
      'download' => $download,
      'upload' => $upload
    ]);
  }else{
    return redirect()->route('users.dashboard');
  }
}else{
  return redirect()->route('users.dashboard');
}
}

default :{
return redirect()->route('users.dashboard');
}
}

}
else{
return redirect()->route('users.dashboard');
}
}
 function checkExpire(){
      $username = Auth::user()->username;
      $chExpire = UserStatusInfo::where('username',$username)
      ->select('expire_datetime','username')
      ->first();

      $data = $chExpire['expire_datetime']; //2020-01-19 12:00:00
      $today =  date('Y-m-d H:i:s'); //2020-01-23 16:17:31

      if($data < $today){
        return "expire";
      }else{
        return "noexpire";
      }

      // if(!empty($chExpire)){
      //   if($chExpire['status'] == 'expire'){
      //     return "expire";
      //   }else{
      //     return "charge";
      //   }
      // }
     } 
    function viewChangeUserPass(){
      return view('users.userPanelView.changeUserPassword');
    }
    public function changeUserPass(Request $request){

      $uName = $request->get('user');
      $repass = $request->get('repass');
      $pass = Hash::make($request->get('pass')); 
      

     $userinfo = UserInfo::where('username',$uName);
     $userinfo->update([
       'password' => $pass,
   ]);
//    $redcheck = RadCheck::where('username',$uName)->where('attribute','Cleartext-Password');
//    $redcheck->update([
//      'value' => $redpass,
//  ]);
    return redirect()->route('users.dashboard');

   }

  //  function userNicView(){
  //   return view('users.userPanelView.userNicView');
  // }

  public function userNicView(){

    $currentStatus = Auth::user()->status;
    if($currentStatus == "user"){
        $userDealer = UserInfo::where(['id' => Auth::user()->id ,'username' => Auth::user()->username])->select('id','username','dealerid','nic','sub_dealer_id','resellerid','status')->first();

  }else{
    return redirect()->route('users.dashboard');
  }
 return view('users.userPanelView.userNicView',[

   'userDealer' =>  $userDealer,
 ]);
 }

 public function userNicData(Request $request)
 {
   
   $v = '';
   $dealerid = Auth::user()->dealerid;
   $subdealerid = Auth::user()->sub_dealer_id;
   $currentStatus = Auth::user()->status;
   $id = $request->get('id');
   
     $output="";
    if($currentStatus == "user"){
       $data = userInfo::where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
    }
     
    $verifyCode = '';
    $up_on= '';
    $status = '';
         $resendCode = UserVerification::where('username',$data['username'])->select('verificationCode','update_on','mobile_status')->first();
         $verifyCode = $resendCode['verificationCode'];
         $up_on = $resendCode['update_on'];
         $status = $resendCode['mobile_status'];
        $curdate = date("Y-m-d H:i:s");
         
  $verimob = '';
  $vericnic = '';
      $isverify = UserVerification::where('username',$data['username'])->select('mobile_status','cnic')->first();
        $verimob = $isverify['mobile_status'];
        $vericnic = $isverify['cnic'];
      
?>
   <!-- Mobile Verification -->
   <input type="hidden" name="uName" id="uName" value="<?php echo $data['username']?>">
   <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data['dealerid']?>">
   <input type="hidden" name="subDid" id="subDid" value="<?php echo $data['sub_dealer_id']?>">
   <input type="hidden" name="resId" id="resId" value="<?php echo $data['resellerid']?>">
   <input type="hidden" name="nic" id="nic" value="<?php echo $data['nic']?>">
   <input  class="form-control" name="mobile" type="text" id="edit1" value="" size="7" maxlength="7"  minlength="7" required>
     <div class="input-group-btn">
        <button type="submit" class="btn btn-primary" style="background-color: #4878C0; color:white" >
         <span class="glyphicon glyphicon-check"></span> Verify
       </button>
     </div>

<?php


}
      
public function addUserCnicData(Request $request){
  $front = $request->file('select_file');
  $back = $request->file('nicback');
  $uname = $request->username;
  $nic = $request->nic;
  $status = $request->status;
  $type = $request->ptrn;
  if($front == '' && $back == ''){
     if($status == 'subdealer'){
        $check = UserVerification::where('cnic',$nic)->select('cnic')->get();
        $numCount =  count($check);
        if($numCount > 1){
           return "more";
        }
      }else{
         $check = UserVerification::where('cnic',$nic)->select('cnic')->get();
        $numCount =  count($check);
        if($numCount > 1){
           return "more1";
        }
      }
      $datas = UserVerification::where('username',$uname)->select('username','dealerid','cnic')->first();
          
      if($datas != ''){
         if($datas['username'] == $uname){
          if($type == 'nic'){
             DB::update('update user_verification set cnic = ? where username = ?',[$nic,$uname]);
             DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]);   
          }elseif($type == 'Onic'){
             DB::update('update user_verification set overseas = ? where username = ?',[$nic,$uname]);
             DB::update('update user_info set overseas_cnic = ? where username = ?',[$nic,$uname]); 
          }elseif($type == 'ntn'){
             DB::update('update user_verification set ntn = ? where username = ?',[$nic,$uname]);
             DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]); 
          }elseif($type == 'passport'){
             DB::update('update user_verification set intern_passport = ? where username = ?',[$nic,$uname]);
             DB::update('update user_info set passport = ? where username = ?',[$nic,$uname]); 
          }
    
     
      return $status;
      }
                  
      }

  }
  
  $frontFile =  $request->file('select_file')->getClientSize();
  $backFile =  $request->file('nicback')->getClientSize();
  if($frontFile > 5245330 || $backFile > 5245330){
    return "false";
  }else{
 
 $validation = Validator::make($request->all(), [
             'select_file' => 'required|image|mimes:jpeg,png,jpg,gif',
             'nicback' => 'required|image|mimes:jpeg,png,jpg,gif'
            ]);
            
            if($validation->passes())
            {

       $image = $request->file('select_file'); //file that you wanna compress
       $back = $request->file('nicback');
       $quality = 60; // Value that I chose
       $pngQuality = 9; // Exclusive for PNG files
       $destination = 'UploadedNic/'; //This destination must be exist on your project
       $maxsize = 5245330; //Set maximum image size in bytes. if no value given 5mb by default.
       // DB Work Here
       $uname = $request->username;
      $dealer = $request->dealer;
       $subdealer = $request->sub_dealer;
       $reseller = $request->resId;
       $nic = $request->nic;
       $status = $request->status;

       $type = $request->ptrn;

       if($status == 'subdealer'){
        $destination = 'sub_dealerNic/';
       }
       if($status == 'subdealer'){
       $check = UserVerification::where('cnic',$nic)->select('cnic')->get();
       $numCount =  count($check);
       if($numCount > 0){
          return "more";
       }

       
     }else{
        $check = UserVerification::where('cnic',$nic)->select('cnic')->get();
       $numCount =  count($check);
       if($numCount > 1){
          return "more1";
       }
     }

       $file =  $uname.'-front.jpg';
       $bfile = $uname.'-back.jpg';

            
       $image_compress = new Compress($image, $file, $quality, $pngQuality, $destination,$maxsize);
       $image_compress->compress_image();
       $image_compress = new Compress($back, $bfile, $quality, $pngQuality, $destination,$maxsize);
       $image_compress->compress_image();
            }
          //  CNIC Image Save to DB Start-Code...
             
          $datas = UserVerification::where('username',$uname)->select('username','dealerid','cnic')->first();
          
          if($datas != ''){
             if($datas['username'] == $uname){
              if($type == 'nic'){
                 DB::update('update user_verification set cnic = ?, nic_front = ? ,nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
                 DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]);   
              }elseif($type == 'Onic'){
                 DB::update('update user_verification set overseas = ?, nic_front = ? ,nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
                 DB::update('update user_info set overseas_cnic = ? where username = ?',[$nic,$uname]); 
              }elseif($type == 'ntn'){
                 DB::update('update user_verification set ntn = ?, nic_front = ? ,nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
                 DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]); 
              }elseif($type == 'passport'){
                 DB::update('update user_verification set intern_passport = ?, nic_front = ? ,nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
                 DB::update('update user_info set passport = ? where username = ?',[$nic,$uname]); 
              }
        
         
          return $status;
          }
                      
          }else{
          
          $data = new UserVerification();
          DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]);
          $data->username = $uname;
          $data->dealerid = $dealer;
          $data->resellerid = $reseller;
          $data->sub_dealer_id = $subdealer;
          if($type == 'nic'){
          $data->cnic = $nic;
          }elseif($type == 'Onic'){
           $data->overseas = $nic;
        }elseif($type == 'ntn'){
              $data->ntn = $nic;
        }elseif($type == 'passport'){
           $data->intern_passport = $nic;
        }
          $data->nic_front = $file;
          $data->nic_back = $bfile;
          $data->save();
          return $status;
          }
    }

} 

 public function verifySms(){

  $currentStatus = Auth::user()->status;
  if($currentStatus == "user"){
      $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'username' => Auth::user()->username])->select('id','username','dealerid','mobilephone','status')->first();
}
return view('users.userPanelView.smsView',[

 'userDealer' =>  $userDealer,
]);
}

public function userMobileVerify(Request $request)
              {
                
                $v = '';
                $usersCollection = array();
                $dealerid = Auth::user()->dealerid;
                $subdealerid = Auth::user()->sub_dealer_id;
                $currentStatus = Auth::user()->status;
               
                $id = $request->get('id');
              
              
                  $output="";
                 if($currentStatus == "user"){
                    $data = userInfo::where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
                 }
                  
                 $verifyCode = '';
                 $up_on= '';
                 $status = '';
                      $resendCode = UserVerification::where('username',$data['username'])->select('verificationCode','update_on','mobile_status')->first();
                      $verifyCode = $resendCode['verificationCode'];
                      $up_on = $resendCode['update_on'];
                      $status = $resendCode['mobile_status'];
                     $curdate = date("Y-m-d H:i:s");
                      if($curdate <  $up_on){
                        if($status == '' && $verifyCode != ''){
                          return "false";
                      }
                    }else if($status == '' && $verifyCode != ''){
                      return "false";
                  }
             
               $verimob = '';
               $vericnic = '';
                   $isverify = UserVerification::where('username',$data['username'])->select('mobile_status','cnic')->first();
                     $verimob = $isverify['mobile_status'];
                     $vericnic = $isverify['cnic'];
                   
            ?>
                <!-- Mobile Verification -->
                <input type="hidden" name="uName" id="uName" value="<?php echo $data['username']?>">
                <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data['dealerid']?>">
                <input type="hidden" name="subDid" id="subDid" value="<?php echo $data['sub_dealer_id']?>">
                <input type="hidden" name="resId" id="resId" value="<?php echo $data['resellerid']?>">
                <input type="hidden" name="nic" id="nic" value="<?php echo $data['nic']?>">
                <input  class="form-control" name="mobile" type="text" id="edit1" value="" size="7" maxlength="7"  minlength="7" required>
                  <div class="input-group-btn">
                     <button type="submit" class="btn btn-primary" style="background-color: #4878C0; color:white" >
                      <span class="glyphicon glyphicon-check"></span> Verify
                    </button>
                  </div>
            
            <?php
             
             
             }

}