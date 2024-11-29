<?php

namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\model\Users\UserInfo;
use App\model\Users\AssignedNas;
use App\model\Users\StaticIPServer;
use App\model\Users\StaticIp;
use Session;
use Validator;

class AssignIpsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }




    public function assign_ip_data()
    {
        if(Auth::user()->status == 'manager' || Auth::user()->status == 'reseller'){
            //
            $get_reseller_data = UserInfo::where(['manager_id'=>Auth::user()->manager_id,'status'=>'reseller'])->select('username')->get();
            $get_dealer_data = UserInfo::where(['resellerid'=>Auth::user()->resellerid,'status'=>'dealer'])->select('username')->get();
            //
            $get_server_ips = StaticIPServer::where('resellerid',Auth::user()->resellerid)->get()->count();
            return view('users.assign-ip.index',compact('get_reseller_data','get_server_ips','get_dealer_data'));
        }else{
            echo 'Permission denied';   
        }
    }
    public function check_nas(Request $request){
    //  dd($request->all());
        if(!empty($request->get('user')))
        {
            $user = $request->get('user');
            $check_nas = AssignedNas::where('id',$user)->get(['nas']);
            return response()->json($check_nas);
        }
    }
    //
    public function assign_ip_store(Request $request){
        $data['error'] = $data['success'] = null;
        //
        $reseller_name = $request->get('reseller');
        $reseller_name = (empty($reseller_name) ? NULL : $reseller_name);
        //
        $dealer_name = $request->get('dealer');
        $dealer_name = (empty($dealer_name) ? NULL : $dealer_name);
        //
        $get_bras = $request->get('bras');
        $noofip = $request->get('noofip');
        // $ip_rate = $request->get('ip_rate');
        $ip_type = $request->get('ip_type');
        $ipassign = $request->get('ipassign');
        //
        //////////////////////////////////////////////////////////  FOR RESELLER ////////////////////////////////////////////////////////////////////////
        if(Auth::user()->status == 'manager'){
        ///////////////////////////
        if($ipassign == 'assign'){
            //
            $ip_count = StaticIPServer::where('resellerid',NULL)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->get()->count();
            $checkStaticIpRate = StaticIp::where("username", $reseller_name)->first();
            //
            if(!$checkStaticIpRate || $checkStaticIpRate->rates <= 0){
                 $data['error'] = "Please assign rate first";
            }else if($ip_count <= 0 || ($ip_count < $noofip) ){
                $data['error'] = "Not enough IPs available";      
            }else{
               $update_assignip = StaticIPServer::where('resellerid',NULL)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('status','NEW')->where('type',$ip_type)->take($noofip)->update(['resellerid'=> $reseller_name]);
               $data['success'] ="IPs Successfully Assigned"; 
           }
        //
       }else if($ipassign == 'remove'){
            //
           $ip_count = StaticIPServer::where('resellerid',$reseller_name)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->get()->count();
           if($ip_count <= 0 || ($ip_count < $noofip) ){
            $data['error'] = "IPs in use or not available";      
        }else{
            $update_assignip = StaticIPServer::where('resellerid',$reseller_name)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->take($noofip)->update(['resellerid'=> NULL]);
            $data['success'] = "IPs Successfully Removed"; 
        }
            //
    }
    //
    //
    $total_ip_count = StaticIPServer::where('resellerid',$reseller_name)->where('type','static')->get()->count();
    $userid = StaticIp::where("username", $reseller_name)->first();
    //
    if($userid){
        $updateIp = StaticIp::where("username",$reseller_name);
        $updateIp->update(["numberofips" => $total_ip_count]);
    }else{
     $staticIP = new StaticIp();
     $staticIP->username = $reseller_name;
     $staticIP->numberofips = $total_ip_count;
     // $staticIP->rates = $ip_rate;
     $staticIP->max_ip_rate = 0;
     $staticIP->save();
 }
 //
}
 //////////////////////////////
 //////////////////////////////////////////////////////////  FOR DEALER ////////////////////////////////////////////////////////////////////////
 if(Auth::user()->status == 'reseller'){
        ///////////////////////////
        $resellerid = Auth::user()->resellerid;
        //
        if($ipassign == 'assign'){
            //
            $ip_count = StaticIPServer::where('resellerid',$resellerid)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->get()->count();
            $checkStaticIpRate = StaticIp::where("username", $dealer_name)->first();
            //
            if(!$checkStaticIpRate || $checkStaticIpRate->rates <= 0){
                 $data['error'] = "Please assign rate first";
            }else if($ip_count <= 0 || ($ip_count < $noofip) ){
                $data['error'] = "Not enough IPs available";      
            }else{
               $update_assignip = StaticIPServer::where('resellerid',$resellerid)->where('dealerid',NULL)->where('userid',NULL)->where('bras',$get_bras)->where('status','NEW')->where('type',$ip_type)->take($noofip)->update(['dealerid'=> $dealer_name]);
               $data['success'] ="IPs Successfully Assigned"; 
           }
        //
       }else if($ipassign == 'remove'){
            //
           $ip_count = StaticIPServer::where('resellerid',$resellerid)->where('dealerid',$dealer_name)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->get()->count();
           if($ip_count <= 0 || ($ip_count < $noofip) ){
            $data['error'] = "IPs in use or not available";      
        }else{
            $update_assignip = StaticIPServer::where('resellerid',$resellerid)->where('dealerid',$dealer_name)->where('userid',NULL)->where('bras',$get_bras)->where('type',$ip_type)->where('status','NEW')->take($noofip)->update(['dealerid'=> NULL]);
            $data['success'] = "IPs Successfully Removed"; 
        }
            //
    }
    //
    //
    $total_ip_count = StaticIPServer::where('resellerid',$resellerid)->where('dealerid',$dealer_name)->where('type','static')->get()->count();
    $userid = StaticIp::where("username", $dealer_name)->first();
    //
    if($userid){
        $updateIp = StaticIp::where("username",$dealer_name);
        $updateIp->update(["numberofips" => $total_ip_count]);
    }else{
     $staticIP = new StaticIp();
     $staticIP->username = $dealer_name;
     $staticIP->numberofips = $total_ip_count;
     // $staticIP->rates = $ip_rate;
     $staticIP->max_ip_rate = 0;
     $staticIP->save();
 }
 //
}

/////////////

    //
 return json_encode($data);


}

}