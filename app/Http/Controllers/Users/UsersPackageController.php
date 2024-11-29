<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\model\Users\Profile;
use DB;
use App\MyFunctions;



class UsersPackageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
    ///////////////////// check access ///////////////////////////////////
     if(!MyFunctions::check_access('Internet Packages',Auth::user()->id)){
            abort(404);
        }
     /////////////////////////////////////////////////////
        //
        $currentStatus = Auth::user()->status;
        //
        $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
        $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
        $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
        $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
        $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
        //
        if($currentStatus == 'inhouse'){
            if(empty($resellerid)){
                $currentStatus = 'manager';  
            }elseif(empty($dealerid)){
                $currentStatus = 'reseller';  
            }elseif(empty($sub_dealer_id)){
                $currentStatus = 'dealer';  
            }else{
                $currentStatus = 'subdealer';  
            }  
        }
        //
        /////
        if($currentStatus == 'manager'){

            $user_packages_data = DB::table('manager_profile_rate')->where('manager_id', Auth::user()->manager_id)->get();
            $straightCount = Profile::join("manager_profile_rate","manager_profile_rate.name","profiles.name")->where('manager_id', Auth::user()->manager_id)->where('profiles.profile_type','straight')->count();
            $cdnCount = Profile::join("manager_profile_rate","manager_profile_rate.name","profiles.name")->where('manager_id', Auth::user()->manager_id)->where('profiles.profile_type','cdn')->count();   

        }else if($currentStatus == 'reseller'){

            $user_packages_data = DB::table('reseller_profile_rate')->where('resellerid', Auth::user()->resellerid)->get(); 
            $straightCount = Profile::join("reseller_profile_rate","reseller_profile_rate.name","profiles.name")->where('reseller_profile_rate.resellerid', Auth::user()->resellerid)->where('profiles.profile_type','straight')->count();
            $cdnCount = Profile::join("reseller_profile_rate","reseller_profile_rate.name","profiles.name")->where('reseller_profile_rate.resellerid', Auth::user()->resellerid)->where('profiles.profile_type','cdn')->count();
            

        }else if($currentStatus == 'dealer'){

            $user_packages_data = DB::table('dealer_profile_rate')->where('dealerid', Auth::user()->dealerid)->get(); 
            $straightCount = Profile::join("dealer_profile_rate","dealer_profile_rate.name","profiles.name")->where('dealerid', Auth::user()->dealerid)->where('profiles.profile_type','straight')->count();
            $cdnCount = Profile::join("dealer_profile_rate","dealer_profile_rate.name","profiles.name")->where('dealerid', Auth::user()->dealerid)->where('profiles.profile_type','cdn')->count();

        }else if($currentStatus == 'subdealer'){

            $user_packages_data = DB::table('subdealer_profile_rate')->where('sub_dealer_id', Auth::user()->sub_dealer_id)->get(); 
            $straightCount = Profile::join("subdealer_profile_rate","subdealer_profile_rate.name","profiles.name")->where('sub_dealer_id', Auth::user()->sub_dealer_id)->where('profiles.profile_type','straight')->count();
            $cdnCount = Profile::join("subdealer_profile_rate","subdealer_profile_rate.name","profiles.name")->where('sub_dealer_id', Auth::user()->sub_dealer_id)->where('profiles.profile_type','cdn')->count();

        }
        //
        return view("users.User_package.index",compact('user_packages_data','straightCount','cdnCount'));
    }



}
