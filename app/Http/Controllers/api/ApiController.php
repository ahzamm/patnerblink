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

class ApiController extends Controller{

    public function index(Request $request){
        //token variable from url
        $token = $request->get('token');        
        $check = DB::table('api_clients')->where('api_token', $token)->first();        
        if(empty($check)){  
            return response()->json('Error: Invalid Token Key', 401);           
        }           
            //get variable from url
        $status = $request->get('status');            
        $id = $request->get('id');
        $username = $request->get('username');
        $resellerid = $request->get('resellerid');            
        $dealerid = $request->get('dealerid');            
        $sub_dealer_id = $request->get('sub_dealer_id');
        $city = $request->get('city');
        $city = $request->get('city');

        $ids = $request->get('ids');
        if(!empty($ids)){
            $ids = explode(',',$ids);
        }
        //
        if ($request->except('token') === []) {
            return response()->json('Error: No Parameter Found', 404);
        }
        //
        $query = DB::table('user_info');            
        //
        if($status != NULL){
            $query ->where('user_info.status',$status);            
        }
        //
        if($username != NULL){
            $query ->where('user_info.username',$username);            
        }
        if($resellerid != NULL){
            $query ->where('user_info.resellerid',$resellerid);            
        }
        if($dealerid != NULL){
            $query ->where('user_info.dealerid',$dealerid);            
        }
        if($sub_dealer_id != NULL){
            $query ->where('user_info.sub_dealer_id',$sub_dealer_id);
        } 
        if($city != NULL){
            $query ->where('user_info.city',$city);
        } 
        if($id != NULL){
            $query ->where('user_info.id',$id);
        }
        if(!empty($ids)){
            $query->whereIn('user_info.id',$ids);
        }

        //          
        $getuser = $query->get();
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
    
    public function brands(Request $request){
        //token variable from url
        $token = $request->get('token');        
        $check = DB::table('api_clients')->where('api_token', $token)->first();        
        if(empty($check)){  
            return response()->json('Error: Invalid Token Key', 401);           
        }           
            //get variable from url
        $name = $request->get('name');
        $resellerid = $request->get('resellerid');
        $id = $request->get('id');
        $ids = $request->get('ids');
        if(!empty($ids)){
            $ids = explode(',',$ids);
        } 
        //
        $query = DB::table('brands');            
        //
        if($name != NULL){
            $query ->where('brand_name',$name);            
        }
        if($resellerid != NULL){
            $query ->where('reseller_id',$resellerid);            
        } 
        if($id != NULL){
            $query ->where('id',$id);            
        }
        if($ids != NULL){
            $query ->whereIn('id',$ids);            
        } 
        //          
        $getData = $query->get();
        if(count($getData) < 1 || empty($getData)){
            $message = 'No Data Found';
            return response()->json($message, 201);
        }
        else{
            return response()->json($getData, 201);
        }  
    }

    ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////
   ///////////////////////////////////////////////////////////////////////////////////
    
    public function cities(Request $request){
        //token variable from url
        $token = $request->get('token');        
        $check = DB::table('api_clients')->where('api_token', $token)->first();        
        if(empty($check)){  
            return response()->json('Error: Invalid Token Key', 401);           
        }           
            //get variable from url
        $name = $request->get('name');
        $id = $request->get('id');
        //
        $query = DB::table('cities');            
        //
        if($name != NULL){
            $query ->where('city_name',$name);            
        }if($id != NULL){
            $query ->where('id',$id);            
        } 
        //          
        $getData = $query->get();
        if(count($getData) < 1 || empty($getData)){
            $message = 'No Data Found';
            return response()->json($message, 201);
        }
        else{
            return response()->json($getData, 201);
        }  
    }
    ////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////
    public function invalid_login(request $request){
   //
        $token = $request->get('token');        
        $manager_id = $request->get('managerid');        
        $check = DB::table('api_clients')->where('api_token', $token)->first();        
        if(empty($check)){  
            return response()->json('Error: Invalid Token Key', 401);           
        }
        //
        if($manager_id != null){
            $whereArray = array();
        //
            array_push($whereArray,array('manager_id' , $manager_id));
        //
            $invalidLogin = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereIn('username', function($query){
               $query->select('username')
               ->from('radpostauth');
           })->select('username')->get()->toArray();
            //
            return response()->json(count($invalidLogin), 201);
            //
        }else{
            $message = 'No Data Found';
            return response()->json($message, 201);
        }
    }
    /////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    public function disabled_consumer(request $request){
    //
     $token = $request->get('token');        
     $manager_id = $request->get('managerid');        
     $check = DB::table('api_clients')->where('api_token', $token)->first();        
     if(empty($check)){  
        return response()->json('Error: Invalid Token Key', 401);           
    }
        //
    if($manager_id != null){
        $whereArray = array();
        //
        array_push($whereArray,array('manager_id' , $manager_id));
        //
        $diabledUser = UserInfo::where($whereArray)->where('status','user')->whereIn('username', function($query){
         $query->select('username')
         ->from('disabled_users')
         ->where('status', 'disable');
     })->select('username')->get();
            //
        return response()->json(count($diabledUser), 201);
            //
    }else{
        $message = 'No Data Found';
        return response()->json($message, 201);
    }
}
/////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////
public function suspicious_consumer(request $request){
    //
 $suspiciousUserCount = 0;
 $token = $request->get('token');        
 $manager_id = $request->get('managerid');        
 $check = DB::table('api_clients')->where('api_token', $token)->first();        
 if(empty($check)){  
    return response()->json('Error: Invalid Token Key', 401);           
}
        //
if($manager_id != null){
    $whereArray = array();
        //
    array_push($whereArray,array('manager_id' , $manager_id));
        //
    $suspiciousUser = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereNotIn('radcheck.username', function($query){
       $query->select('radacct.username')
       ->from('radacct')
       ->where('radacct.acctstoptime',NULL);
   })
    ->join('radusergroup','radcheck.username','radusergroup.username')->whereNotIn('radusergroup.groupname',['NEW','DISABLED','EXPIRED','TERMINATE'])
    ->select('radcheck.username')->get();
    //
    $now = date('Y-m-d H:i:s');
    //
    foreach($suspiciousUser as $sus){
       $hourdiff = '';
       $lastLoginDetail = RadAcct::where('username',$sus->username)->orderBy('radacctid','DESC')->first();
       if($lastLoginDetail){
          $hourdiff = round((strtotime($now) - strtotime($lastLoginDetail->acctstoptime))/3600, 1);
      }
        //
      if(empty($hourdiff) || $hourdiff > 24){
          $suspiciousUserCount++;
      }
  }
            //
  return response()->json($suspiciousUserCount, 201);
            //
}else{
    $message = 'No Data Found';
    return response()->json($message, 201);
}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
public function online_consumer(request $request){
   //
    $token = $request->get('token');        
    $manager_id = $request->get('managerid');        
    $check = DB::table('api_clients')->where('api_token', $token)->first();        
    if(empty($check)){  
        return response()->json('Error: Invalid Token Key', 401);           
    }
        //
    if($manager_id != null){
        $whereArray = array();
        //
        array_push($whereArray,array('manager_id' , $manager_id));
        //
        $onlineUser = RadCheck::where('status','user')->where('attribute','Cleartext-Password')->where($whereArray)->whereIn('username', function($query){
         $query->select('username')
         ->from('radacct')
         ->where('acctstoptime',NULL);
     })->count();
    //
        // $data['count'] = $onlineUser;        
        // return $data;
            //
        return response()->json($onlineUser, 201);
            //
    }else{
        $message = 'No Data Found';
        return response()->json($message, 201);
    }
}



public function get_domain(Request $request){
        //token variable from url
    $token = $request->get('token');        
    $check = DB::table('api_clients')->where('api_token', $token)->first();        
    if(empty($check)){  
        return response()->json('Error: Invalid Token Key', 401);           
    }           
            //get variable from url
    $brand_id = $request->get('brand_id');
    $id = $request->get('id');
        //
    $query = DB::table('domain');            
        //
    if($brand_id != NULL){
        $query ->where('brand_id',$brand_id);            
    }if($id != NULL){
        $query ->where('id',$id);            
    } 
        //          
    $getData = $query->get();
    if(count($getData) < 1 || empty($getData)){
        $message = 'No Data Found';
        return response()->json($message, 201);
    }
    else{
        return response()->json($getData, 201);
    }  
}

/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////

public function get_cgn_ip_detail(Request $request){
        //token variable from url
    $token = $request->get('token');        
    $check = DB::table('api_clients')->where('api_token', $token)->first();        
    if(empty($check)){  
        return response()->json('Error: Invalid Token Key', 401);           
    }           
    //get variable from url
    $ip = $request->get('ip');
    $status = $request->get('status');
    $nas = $request->get('nas');
    $public_ip = $request->get('public_ip');
    $public_ip_array = $request->get('public_ip_array');
    //
    $query = DB::table('user_usual_ips');            
    //
    if($ip != NULL){
        $query ->where('ip',$ip);            
    }if($status != NULL){
        $query ->where('status',$status);            
    }if($nas != NULL){
        $query ->where('nas',$nas);            
    }if($public_ip != NULL){
        $query ->where('public_ip',$public_ip);            
    }if($public_ip_array != NULL){
        $public_ip_array = explode(",",$public_ip_array);
        $query ->whereIn('public_ip',$public_ip_array);            
    } 
        //          
    $getData = $query->get();
    if(count($getData) < 1 || empty($getData)){
        $message = 'No Data Found';
        return response()->json($message, 201);
    }
    else{
        return response()->json($getData, 201);
    }  
}



}



