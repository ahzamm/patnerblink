<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\Profile;
use App\model\Users\ManagerProfileRate;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\Hash;
use App\model\Users\RadCheck;
use App\model\Users\userAccess;
use App\model\Users\ActionLog;
use Illuminate\Support\Facades\Auth;
use App\MyFunctions;


class UpdatePasswordController extends Controller
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
    $status = Auth::user()->status;
    if($status == "manager"){
      $userCollection = UserInfo::where(['status' => 'reseller', 'manager_id' => Auth::user()->manager_id])->get();
    }
    elseif ($status == "reseller") {
      $userCollection = UserInfo::where(['status' => 'dealer', 'resellerid' => Auth::user()->resellerid])->get();
      
    }
    elseif ($status == "dealer") {
    
      $userCollection = UserInfo::where(['dealerid' => Auth::user()->dealerid])->where('username','!=',Auth::user()->username )->get();
      
    }elseif ($status=="subdealer") {
    
      $userCollection = UserInfo::where(['sub_dealer_id' => Auth::user()->sub_dealer_id,'status' => 'user'])->get();


    }elseif ($status == "inhouse") {
      
      if(Auth::user()->status =='inhouse' && Auth::user()->sub_dealer_id == ''){
     
      $userCollection = UserInfo::where(['dealerid' => Auth::user()->dealerid])
      ->where('username','!=',Auth::user()->username )->where('sub_dealer_id','')->get();

    }elseif(Auth::user()->status =='inhouse' && Auth::user()->sub_dealer_id != ''){
    
      $userCollection = UserInfo::where(['dealerid' => Auth::user()->dealerid])
      ->where('username','!=',Auth::user()->username )->where('sub_dealer_id','!=','')
      ->where('status','!=','subdealer')->get();

    }
    }
    
      // 
    
    return view('users.billing.update_password',[
      'userCollection' => $userCollection

    ]);
 }

public function changePass(Request $request)
{
  //
  if(MyFunctions::is_freezed(Auth::user()->username)){
        Session()->flash("error", "Your panel has been freezed");
        return back();      
    }  
  //
    $status = Auth::user()->status;
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
        array_push($whereArray,array('manager_id' , $manager_id));
    }if(!empty($resellerid)){
        array_push($whereArray,array('resellerid' , $resellerid));
    }if(!empty($dealerid)){
        array_push($whereArray,array('dealerid' , $dealerid));
    }if(!empty($sub_dealer_id)){
        array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));  
    }
    //
    $userExist = UserInfo::where(['username' => $request->get('username')])->where('status','user')->where($whereArray)->first();
    if(!$userExist){
      session()->flash('error','Invalid or no user found');
      return back();
    }
    //
  
  if($status == "manager")
  {
    $username=$request->get('username');
   

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();

    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();
    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);

  }elseif($status == "reseller"){
    $username=$request->get('username');
  

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();

    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);
  }elseif($status == "dealer"){
    $username=$request->get('username');

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();

    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);


  }elseif($status == "subdealer"){
    $username=$request->get('username');
  

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();

    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();
    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);

  }elseif($status == "inhouse" && Auth::user()->sub_dealer_id !=''){
    $username=$request->get('username');
    

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();


    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();
    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);

  }elseif($status == "inhouse" && Auth::user()->sub_dealer_id ==NULL){
    $username=$request->get('username');

    $saveUserRad=RadCheck::where(["username"=>$username, "attribute" => "Cleartext-Password"])->first();
    $saveUserRad->value=$request->get('password');
    $saveUserRad->save();


    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('password'));
    $saveUser->save();
    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Updated By User : ".Auth::user()->username,$username);
  }
$username=$request->get('username');
// API FOR USER DC//////////////////////////////////////////////////////////////

MyFunctions::kick_it($username);
  
////////////////////////////////////////////////////////////////////////////////    

  return redirect()->route('users.billing.update_password');




}

public function reSetPass(Request $request)
{
 
 $status = Auth::user()->status;
  
  if($status == "manager")
  {
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);


  }elseif($status == "reseller"){
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();

 
    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);

  }elseif($status == "dealer"){
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);

  }elseif($status == "subdealer"){
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);


  }elseif($status == "inhouse" && Auth::user()->sub_dealer_id !=''){
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();


    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);

  }elseif($status == "inhouse" && Auth::user()->sub_dealer_id ==''){
    $username=$request->get('username');
    $saveUser = UserInfo::where(['username' => $username])->first();
    $saveUser->password = Hash::make($request->get('username'));
    $saveUser->save();

    ActionLog::userActivityLog(__CLASS__,__FUNCTION__,"Password Reset By User : ".Auth::user()->username,$username);


  }



  return redirect()->route('users.billing.update_password');




}
}
