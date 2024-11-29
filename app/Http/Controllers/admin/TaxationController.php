<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\Tax;
use App\model\Users\ProvincialTaxation;
use Illuminate\Support\Facades\DB;



class TaxationController extends Controller
{
	function taxation_view(){
		$show_tax = Tax::all();
		$provincial_tax = ProvincialTaxation::all();
		return view('admin.taxation.index',compact('show_tax','provincial_tax'));
	}

	function taxation_update(Request $request){
		$all_data = $request->all();

		$update_tax=[
		// 'ss_tax' => $request->get('ss_tax'),
		// 'adv_tax' =>$request->get('adv_tax'),
			'filer_tax' =>$request->get('filer_tax')/100,
			'non_file_tax' =>$request->get('non_file_tax')/100,
			'fll_tax' =>$request->get('fll_tax')/100,
			'cvas_tax' =>$request->get('cvas_tax')/100,
			'tip_tax' =>$request->get('tip_tax')/100,
		//
			'fll_sst' => $request->get('fll_sst'),
			'fll_adv' => $request->get('fll_adv'),

			'cvas_sst' => $request->get('cvas_sst'),
			'cvas_adv' => $request->get('cvas_adv'),

			'tip_sst' => $request->get('tip_sst'),
			'tip_adv' => $request->get('tip_adv'),

		];

	// dd($all_data);

		$get_data = Tax::where('serial',$request->get('tax_id'))->update($update_tax);

		return response()->json(['success'=> 'Successfully Update']);
	}

	////////////////////////////////////////////////////
	function provincial_taxation_update(Request $request){

		$id = $request->get('id');
		$name = $request->get('name');
		$sst = $request->get('sst');
		$adv = $request->get('adv');
		//
		foreach($id as $key => $value){

			$update_tax=[
				'ss_tax' => $sst[$key]/100,
				'adv_tax' => $adv[$key]/100,
				'last_update' => date('Y-m-d H:i:s'),
			];
			
			$get_data = ProvincialTaxation::where('id',$id[$key])->update($update_tax);
		
		//
		//////// UPDATE ALL RESELLER /////////
		$get_reseller = DB::table('user_info')->where('status','reseller')->where('state',$name[$key])->select('resellerid')->get();

		foreach($get_reseller as $reseller){
			DB::table('reseller_profile_rate')->where('resellerid',$reseller->resellerid)->update(['sst' => $sst[$key]/100, 'adv_tax' => $adv[$key]/100]);
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////// UPDATE ALL DEALER /////////
		$get_dealer = DB::table('user_info')->where('status','dealer')->where('state',$name[$key])->select('dealerid')->get();

		foreach($get_dealer as $dealer){
			DB::table('dealer_profile_rate')->where('dealerid',$dealer->dealerid)->update(['sstpercentage' => $sst[$key]/100, 'advpercentage' => $adv[$key]/100]);
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////// UPDATE ALL SUB DEALER /////////
		$get_subdealer = DB::table('user_info')->where('status','subdealer')->where('state',$name[$key])->select('sub_dealer_id')->get();

		foreach($get_subdealer as $subdealer){
			DB::table('subdealer_profile_rate')->where('sub_dealer_id',$subdealer->sub_dealer_id)->update(['sstpercentage' => $sst[$key]/100, 'advpercentage' => $adv[$key]/100]);
		}
		//



		}
		//
		return response()->json(['success'=> 'Successfully Update']);

	}
	


}
