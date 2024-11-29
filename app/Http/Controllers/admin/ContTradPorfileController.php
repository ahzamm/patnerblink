<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Profile;
use App\model\Users\Nas;
use App\model\Users\ContractorTraderProfile;
use Illuminate\Support\Facades\DB;
//
class ContTradPorfileController extends Controller
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


    $naslist = Nas::orderBy('id')->get();  
    $list = ContractorTraderProfile::orderBy('id')->get();
        // 	
    return view('admin.users.view_contractor_trader_profile',['list' => $list, 'naslist' => $naslist]);



}



public function store(Request $request)
{
    $checking = ContractorTraderProfile::where('nas_shortname',$request->get('nas'))->first();
    if($checking){
        return abort(403, 'Error : Same NAS already exist'); 
    }
        //
    $CTProfile = new ContractorTraderProfile();
    $CTProfile->nas_shortname = $request->get('nas');
    $CTProfile->contractor_profile = $request->get('contractor_profile');
    $CTProfile->trader_profile = $request->get('trader_profile');
    $CTProfile->save();
    return 'Added Successfully';
    // return redirect()->route('admin.contractor_trader_profile');
}

public function delete(Request $request)
{
 $id =$request->get('id');
 ContractorTraderProfile::where(['id' => $id])->delete();
}

}



