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
use App\model\Users\Domain;
use Validator;
use Session;
use App\SendMessage;
use Eihror\Compress\Compress;


class SmsSenderController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {}

// Mobile Varification Code Start Here 
// Send Data To smsView
 public function verifySms(Request $request)
 {
   $currentStatus = Auth::user()->status;
   $username = $request->username;
   //
   $domain = Domain::where(['resellerid' => Auth::user()->resellerid])->first();
   $shortName = $domain->package_name;
   //
   if($currentStatus == "dealer"){
      $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'username' => $username])
      ->select('id','username','dealerid','mobilephone','status')->first();
   }else{
      $userDealer = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'username' => $username])
      ->select('id','username','sub_dealer_id','mobilephone','status')->first();
   }
   if($userDealer->status == 'user'){
      return view('users.Verifications.smsView', ['userDealer' =>  $userDealer, 'shortName' => $shortName,]);
   }else{
      return view('users.Verifications.smsView_trader', ['userDealer' =>  $userDealer, 'shortName' => $shortName,]);
   }
//
}
public function MobileData(Request $request)
{
   $dealerid = Auth::user()->dealerid;
   $subdealerid = Auth::user()->sub_dealer_id;
   $currentStatus = Auth::user()->status;
   $id = $request->get('id');

   if($currentStatus == "dealer"){
      $data = userInfo::where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
   }else if($currentStatus == "subdealer"){
      $data = userInfo::where('sub_dealer_id',$subdealerid)->where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
   }
   $verifyCode = '';
   $up_on= '';
   $status = '';
   
   $resendCode = UserVerification::where('username',$data['username'])->select('verificationCode','update_on','mobile_status')->first();
   if(empty($resendCode)){
    return 'Alert : Please verify CNIC first';
    
 }
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
?>
<!-- Mobile Verification -->
<input type="hidden" name="uName" id="uName" value="<?php echo $data['username']?>">
<input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data['dealerid']?>">
<input type="hidden" name="subDid" id="subDid" value="<?php echo $data['sub_dealer_id']?>">
<input type="hidden" name="resId" id="resId" value="<?php echo $data['resellerid']?>">
<input  class="form-control" name="mobile" type="text" id="edit1" value="" data-mask="9999999" maxlength="7" placeholder="1234567" pattern="[0-9]{7}" required>
<div class="input-group-btn">
   <button type="submit" class="btn btn-primary theme-bg" style="color:white" >
      <span class="glyphicon glyphicon-check"></span> Verify Now
   </button>
</div>
<?php
}
public function sendSms(Request $request){
  $v ='';
  $mob = $request->mobile;
  $mobCode = $request->mobCode;
  $uname = $request->uName;
  $dealer = $request->dealerid;
  $subdealer = $request->subDid;
  $reseller = $request->resId;
  $subdealer = $request->subDid;
  $mobileNumber = $mobCode.$mob;
  $code = $this->genrateNum();

  $check = UserVerification::where('mobile',$mobileNumber)->select('mobile')->count();
  if($check >= 2){
   return "false";
}
$data = UserVerification::where('username',$uname)->select('username','dealerid')->first();
if($data != ''){
 if($data['username'] == $uname){
    DB::update('update user_verification set verificationCode = ?,mobile = ?  where username = ?',[$code,$mobileNumber,$uname]);
    DB::update('update user_info set mobilephone = ? where username = ?',[$mobileNumber,$uname]);
    return $code;
 }
}else{
   $data = new UserVerification();
   DB::update('update user_info set mobilephone = ? where username = ?',[$mobileNumber,$uname]);
   $data->username = $uname;
   $data->dealerid = $dealer;
   $data->resellerid = $reseller;
   $data->sub_dealer_id = $subdealer;
   $data->mobile = $mobileNumber;
   $data->verificationCode = $code;
   $data->update_on = date("Y-m-d H:i:s");
   $data->save();
   return $code;
}

}


public function addMobileData(Request $request){
   $v ='';
     // $mobileNumber = '';
       // $mob_code = str_replace('LBI-','',$request->mobileCode);
   $mob_code = preg_replace("/[A-Zaz-]/", "", $request->mobileCode );
   $uname = $request->username;
   
   
   $finalmessage = "معزز صارف آپکا انٹرنیٹ کنکشن آپکے اس موبائل نمبر پر تصدیق کردیا گیا ہے .اگر اپ انٹرنیٹ کنکشن استعمال کر رہیں ہیں تو اس میسج کو نظر انداز کردیں . ورنہ اپنے مطلقہ  ڈیلر سے رابطہ کریں . شکریہ .
   ";
   $finalmessage2 = "Dear valaued customer, your internet connection has been verified on your
   mobile number. if your are still using your connection then ignore this 
   message, otherwise contact to your service provider. Thanks";

   $data = UserVerification::where('username',$uname)->select('verificationCode','username')->first();
      // $mobileNumber = $data['mobile'];
   if($data != ''){
     
    if($data['username'] == $uname && $data['verificationCode'] == $mob_code){
       DB::update('update user_verification set mobile_status = 1  where username = ?',[$uname]);
                  //   dd(SendMessage::smsSend($mobileNumber,$finalmessage));
       return 'done';
    }
    else{
       return "false";
    }
 }
 
}
public function fetchValidCode(Request $request){
   $username = $request->username;
   $validCode = UserVerification::where('username',$username)->select('verificationCode')->first();
   return $validCode;
}
// Mobile Verification Code End Here............!!


// Genrate Random Numbers
public function genrateNum(){
   // generate a pin based on 2 * 7 digits + a random character
   $pin = mt_rand(1000000, 9999999).mt_rand(1000000, 9999999);
   // shuffle the result
   $string = str_shuffle($pin);
   $res = substr($string,0,4);
   return $res;
}
}