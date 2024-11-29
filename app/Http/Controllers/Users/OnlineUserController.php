<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\Profile;
use App\model\Users\ScratchCards;
use App\model\Users\RadAcct;


class OnlineUserController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


 
  public $arr = array();
     public function index($status){
     	
         switch($status){
            case "online" : {
            
            	$userDealer = UserInfo::where(['dealerid' => 'newera','status' => 'user','sub_dealer_id' => ''])->select('username','dealerid')->get();

				
				   foreach ($userDealer as $value) {
				   $dealerids = $value->dealerid;	
				   	$online = RadAcct::where(['acctstoptime' => NULL, 'username' => $value->username])->get();
				   foreach ($online as $value) {
				   $arr[] = $value;
				   }
				   }

                return view('admin.users.online_user'
                	,['arr'=>$arr,'dealerids' => $dealerids]);
            }break;
            case "view" : {
               
                return view('admin.users.view_user');
            }break;
            default :{
                return redirect()->route('admin.dashboard');
            }

    }
  



}

}