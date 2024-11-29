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
use Eihror\Compress\Compress;

class NicController extends Controller
{
// NIC Verification Code Start Here...................!!
   public function verifyUser(Request $request)
   {
      $currentStatus = Auth::user()->status;
      $username = $request->username;
      if($currentStatus == "dealer"){
         $userDealer = UserInfo::where(['dealerid' => Auth::user()->dealerid ,'username' => $username])->select('id','username','dealerid','nic','sub_dealer_id','resellerid','status')->first();
      }elseif($currentStatus == "subdealer"){
         $userDealer = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id ,'username' => $username])->select('id','username','dealerid','nic','sub_dealer_id','resellerid','status')->first();
      }else{
         $userDealer = UserInfo::where(['trader_id' => Auth::user()->trader_id ,'username' => $username])->select('id','username','dealerid','nic','sub_dealer_id','resellerid','status','trader_id')->first();
      }
      //
      if($userDealer->status == 'user'){
         return view('users.Verifications.nicView', ['userDealer' =>  $userDealer,]);
      }else{
         return view('users.Verifications.nicView_trader', ['userDealer' =>  $userDealer,]);
      }
   }
//
   public function nicData(Request $request)
   {
      $usersCollection = array();
      $dealerid = Auth::user()->dealerid;
      $subdealerid = Auth::user()->sub_dealer_id;
      $trader_id = Auth::user()->trader_id;
      $currentStatus = Auth::user()->status;

      $id = $request->get('id');
      if($currentStatus == "dealer"){
         // $data = userInfo::where('dealerid',$dealerid)->where('id',$id)->where('status','user')->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
         $data = userInfo::where('dealerid',$dealerid)->where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
      }else if($currentStatus == "subdealer"){
         $data = userInfo::where('sub_dealer_id',$subdealerid)->where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
      }else if($currentStatus == "trader"){
         $data = userInfo::where('trader_id',$trader_id)->where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic','trader_id')->first();
      }else if($currentStatus == "support"){
         $data = userInfo::where('id',$id)->select('mobilephone','username','resellerid','dealerid','sub_dealer_id','nic')->first();
      }
      ?>
      <!-- NIC Verification -->
      <input type="hidden" name="uName" id="uName" value="<?php echo $data['username']?>">
      <input type="hidden" name="dealerid" id="dealerid" value="<?php echo $data['dealerid']?>">
      <input type="hidden" name="subDid" id="subDid" value="<?php echo $data['sub_dealer_id']?>">
      <input type="hidden" name="resId" id="resId" value="<?php echo $data['resellerid']?>">
      <input type="hidden" name="nic" id="nic" value="<?php echo $data['nic']?>">
      <div class="input-group-btn">
         <button type="submit" class="btn btn-primary" style="background-color: #4878C0; color:white" >
            <span class="glyphicon glyphicon-check"></span> Verify
         </button>
      </div> 
      <?php
   }
   public function addCnicData(Request $request){

      $frontImageFile = $request->file('select_file');
      $backImageFile = $request->file('nicback');
   // Check If Front and Back Image File is not empty.. else _ _ _ _ up
      if($frontImageFile != '' && $backImageFile != ''){
         $validation = Validator::make($request->all(), [
            'select_file' => 'required|image|mimes:jpeg,png,jpg,gif',
            'nicback' => 'required|image|mimes:jpeg,png,jpg,gif'
         ]);

         if($validation->passes())
         {
           $frontImageFile = $request->file('select_file');
           $backImageFile = $request->file('nicback');
           $uname = $request->username;
           $nic = $request->nic;
           $status = $request->status;
           $type = $request->ptrn;
           $dealer = $request->dealer;
           $subdealer = $request->sub_dealer;
           $reseller = $request->resId;

   // Get NIC Image File Size For Checking These Size
           $frontFile = $frontImageFile->getsize();
           $backFile =  $backImageFile->getsize();
   // If File size greater Then 5 mb then genrate error... else go on
           if($frontFile > 5245330 || $backFile > 5245330){
            return "false";
         }else{
      $image = $frontImageFile; //file that you wanna compress
      $back = $backImageFile;
      $quality = 60; // Value that I chose
      $pngQuality = 9; // Exclusive for PNG files
      $destination = 'UploadedNic/'; //This destination must be exist on your project
      $maxsize = 5245330; //Set maximum image size in bytes. if no value given 5mb by default.
      if($status == 'subdealer'){
         $destination = 'sub_dealerNic/';
      }
      if($status == 'subdealer'){
         $check = UserVerification::where('cnic',$nic)->select('cnic')->count();
         if($check >= 2){ return "exceed_NIC"; }
      }else if($status == 'dealer'){
         $check = UserVerification::where('cnic',$nic)->select('cnic')->count();
         if($check >= 2){ return "exceed_NIC"; }
      }else if($status == 'trader'){
         $check = UserVerification::where('cnic',$nic)->select('cnic')->count();
         if($check >= 2){ return "exceed_NIC"; }
      }else if($status == 'user'){
         $check = UserVerification::where('cnic',$nic)->select('cnic')->count();
         if($check >= 2){ return "exceed_NIC"; }
      }
      $file =  $uname.'-front.jpg';
      $bfile = $uname.'-back.jpg';
      //
      if(file_exists(public_path($destination.$file))){
         unlink(public_path($destination.$file));  
      }
      //
      if(file_exists(public_path($destination.$bfile))){
         unlink(public_path($destination.$bfile));  
      }
      //
      $image->move(public_path($destination), $file);
      $back->move(public_path($destination), $bfile);
      // $image_compress = new Compress($image, $file, $quality, $pngQuality, $destination,$maxsize);
      // $image_compress->compress_image();
      // $image_compress = new Compress($back, $bfile, $quality, $pngQuality, $destination,$maxsize);
      // $image_compress->compress_image();
// Check If This already in User Verification Table Then will Update the data
      $datas = UserVerification::where('username',$uname)->select('username','dealerid','cnic')->first();
      if($datas != ''){
         if($datas['username'] == $uname){
            if($type == 'nic'){
               DB::update('update user_verification set cnic = ?,nic_front = ?, nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
               DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]);   
            }elseif($type == 'Onic'){
               DB::update('update user_verification set overseas = ?,nic_front = ?, nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
               DB::update('update user_info set overseas_cnic = ? where username = ?',[$nic,$uname]); 
            }elseif($type == 'ntn'){
               DB::update('update user_verification set ntn = ?,nic_front = ?, nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
               DB::update('update user_info set nic = ? where username = ?',[$nic,$uname]); 
            }elseif($type == 'passport'){
               DB::update('update user_verification set intern_passport = ?,nic_front = ?, nic_back = ? where username = ?',[$nic,$file,$bfile,$uname]);
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
} //End Validation if condition
} //End if($frontImageFile == '' && $backImageFile == '')
} //End addCnicData
} //End Class