<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\RadCheck;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\RaduserGroup;
use App\model\Users\Radreply;
use App\model\Users\UserIPStatus;
use App\model\Users\UserUsualIP;
use App\model\Users\Profile;
use App\model\Users\Nas;

class PaymentController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

     public function index($status){
        switch($status){
            case "manager" : {
            	$managerCollection = UserInfo::where(['status' => 'manager'])->get();
                return view('admin.users.view_manager',[
                	'managerCollection' => $managerCollection
                ]);
            }break;
            case "reseller" : {
                $resellerCollection = UserInfo::where(['status' => 'reseller'])->get();
                $managerIdList = UserInfo::where(['status' => 'manager'])->get(['manager_id']);
                return view('admin.users.view_reseler',[
                    'resellerCollection' => $resellerCollection,
                    'managerIdList' => $managerIdList
                ]);
            }break;
            case "dealer" : {
                $dealerCollection = UserInfo::where(['status' => 'dealer'])->get();
                $managerIdList = UserInfo::where(['status' => 'manager'])->get(['manager_id']);
                $resellerIdList = UserInfo::where(['status' => 'reseller'])->get(['resellerid']);
                return view('admin.users.view_dealer',[
                     'dealerCollection' => $dealerCollection,
                     'managerIdList' => $managerIdList,
                     'resellerIdList' => $resellerIdList
                ]);
            }break;
            default :{
                return redirect()->route('admin.dashboard');
            }
        }

    }

    


     
}
