<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\model\Users\UserInfo;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\SubdealerProfileRate;



class MrctController extends Controller
{
		 /**
			 * Create a new controller instance.
			 *
			 * @return void
			 */
		 public function __construct()
		 {

		 }
		 public function get_manager(){
		 	$manager_data = userInfo::where('status','manager')->get(["manager_id", "id"]);
		 	return $manager_data;
		 }
		//
		 public function get_reseller(Request $request){
		 	$data['reseller'] = userInfo::where('manager_id',$request->manager_id)
		 	->where('status','reseller')->get(["resellerid", "id","username"]);
		 	return response()->json($data);
		 }
		 //
		 public function get_dealer(Request $request){
		 	//
		 	if(is_array($request->reseller_id)){
		 		$resellerArray = $request->reseller_id;
		 	}else{
		 		$resellerArray = array($request->reseller_id);
		 	}
		 	//
		 	$data['dealer'] = userInfo::whereIn('resellerid',$resellerArray)
		 	->where('status','dealer')->get(["dealerid", "id","username"]);
		 	return response()->json($data);
		 }
		 //
		 public function get_trader(Request $request){
		 	//
		 	if(is_array($request->dealer_id)){
		 		$dealerArray = $request->dealer_id;
		 	}else{
		 		$dealerArray = array($request->dealer_id);
		 	}
		 	//
		 	$data['subdealer'] = userInfo::whereIn('dealerid',$dealerArray)->where('status','subdealer')->get(["sub_dealer_id", "id","username"]);
		 	return response()->json($data);
		 }
		 //
		 public function get_profiles(Request $request){
		 	$id = $request->id;
		 	$status = $request->status;
		 	//
		 	if($status == 'manager'){
  				//
		 		$profileName = Profile::orderBy('groupname')->get();
  				//
		 	}elseif($status == 'reseller'){
  				//
		 		$profileName = ResellerProfileRate::where(['resellerid' => $id])->orderBy('groupname')->get();
  				//
		 	}elseif($status == 'contractor'){
  				//
		 		$profileName = DealerProfileRate::where(['dealerid' => $id])->orderBy('groupname')->get();
  				//
		 	}elseif($status == 'trader'){
  				//
		 		$profileName = SubdealerProfileRate::where(['sub_dealer_id' => $id])->orderBy('groupname')->get();
  				//
		 	}
		 	return response()->json($profileName);
		 }

		}
