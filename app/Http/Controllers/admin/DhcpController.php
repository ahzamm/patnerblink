<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\StaticIp;
use App\model\Users\UserVerification;
use App\model\Users\UserStatusInfo;
use App\model\Users\exceedData;
use App\model\Users\RadAcct;
use App\model\Users\Profile;
use App\model\Users\Dhcp_server;
use Validator;
use Session;




class DHCPController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {}
public function add_DHCPView()
{
    $serverData = Dhcp_server::all();
    return view('admin.users.dhcpView',compact('serverData'));
}
    public function add_DHCP_Post(Request $request)
    {
        $name = $request->name;
        $ip =   $request->ip;
        $username   = $request->username;
        $password   = $request->password;
        $address   = $request->address;
        $id = $request->id;
        if(!empty($id) || $id != 0){
            $addDHCP = Dhcp_server::find($id);
            $addDHCP->name = $name;
            $addDHCP->ip = $ip;
            $addDHCP->username = $username;
            $addDHCP->password = $password;
            $addDHCP->address = $address;
    
            $addDHCP->save();
        }else{
            $addDHCP = new Dhcp_server();
            $addDHCP->name = $name;
            $addDHCP->ip = $ip;
            $addDHCP->username = $username;
            $addDHCP->password = $password;
            $addDHCP->address = $address;
    
            $addDHCP->save();
        }
        return redirect()->route('admin.adddhcpView');
    }
    public function DHCP_Post_Update(Request $request)
    {
        $id = $request->id;
        $res = Dhcp_server::where('id',$id)->first();
        return json_encode($res);
    }
}
?>