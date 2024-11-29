<?php

namespace App\Http\Controllers\api;


use Illuminate\Http\Request;
use App\Post;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\ExpireUser;
use App\model\Users\UserStatusInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\model\Users\RadCheck;
use App\model\Users\RadAcct;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RadiusApiController extends Controller{

    public function index(Request $request){
        //token variable from url
        //
        $token = $request->get('token');        
        $check = DB::table('api_clients')->where('api_token', $token)->first();        
        if(empty($check)){  
            return response()->json('Error: Invalid Token Key', 401);           
        }           
            //get variable from url
        $status = $request->get('status');            
        $radacctid = $request->get('radacctid');            
        $id = $request->get('id');
        $username = $request->get('username');
        $framedipaddress = $request->get('framedipaddress');
        $framedipaddressArr = $request->get('framedipaddressArr');
        $callingstationid = $request->get('callingstationid');
        $limit = $request->get('limit');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $pta_verified = $request->get('ptaverified');

        $ids = $request->get('ids');
        if(!empty($ids)){
            $ids = explode(',',$ids);
        }
        //
        if ($request->except('token') === []) {
            return response()->json('Error: No Parameter Found', 404);
        }
        //
        $query = RadAcct::orderBy('radacctid');            
        //
        if($status != NULL && $status == 'online'){
            $query ->where('acctstoptime',NULL);         
        }
        //
        if($username != NULL){
            $query ->where('username',$username);            
        }
        //
        if(!empty($ids)){
            $query->whereIn('id',$ids);
        }
        //
        if(!empty($framedipaddress)){
            $query->where('framedipaddress',$framedipaddress);
        }
        //
        if(!empty($framedipaddressArr)){
            $framedipaddressArr = explode(",",$framedipaddressArr);
            $query->whereIn('framedipaddress',$framedipaddressArr);
        }
        //
        if(!empty($callingstationid)){
            $query->where('callingstationid',$callingstationid);
        }
        //
        if(!empty($startDate)){
            $query->whereDate('acctstarttime','>=',$startDate);
        }
        //
        if(!empty($endDate)){
            $query->whereDate('acctstarttime','<=',$endDate);
        }
        //
        if(!empty($radacctid)){
            $query->where('radacctid',$radacctid);
        }
        //
        if(!empty($pta_verified) && $pta_verified == 'yes'){
            // only pta reported users
            $pta_reported_users = DB::table('user_info')->where('pta_reported',1)->pluck('username')->toArray();
            $query->whereIn('username',$pta_reported_users);
        }
        //
        $limit = (!empty($limit)) ? $limit : 50000;      
        $query->limit($limit);    
        $getuser = $query->get();
        //
        foreach($getuser as $value){
            $cgnIpInfo = DB::table('user_usual_ips')->where('ip',$value->framedipaddress)->first();
            if($cgnIpInfo ){
                $value->public_ip = $cgnIpInfo->public_ip;
            }else{
                $value->public_ip = NULL;
            }
            
        }
        //
        if(count($getuser) < 1 || empty($getuser)){
            $message = 'No Data Found';
            return response()->json($message, 201);
        }
        else{
            return response()->json($getuser, 201);
        }
        
    }
   ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////


}



