<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserInfo;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\ManagerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\TraderProfileRate;
use App\model\Users\Profile;
use App\model\Users\Tax;
use Illuminate\Support\Facades\Route;
use App\model\Users\Error;
use App\MyFunctions;
use Session;


class ProfitCalculatorController extends Controller
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
 	//
    ///////////////////// check access ///////////////////////////////////
    if(!MyFunctions::check_access('Profit Calculator',Auth::user()->id)){
    abort(404);
}
        //
$currentUser = Auth::user()->status;
if($currentUser == "reseller"){
 $userCollection = UserInfo::select('username')->where(['status'=>'dealer','resellerid' => Auth::user()->resellerid ])->get();
}
    //
    /////////////////////////////////////////////////////
if(Auth::user()->status == "reseller"){
  $profileList = ResellerProfileRate::where(['resellerid' => Auth::user()->resellerid])->select('name','rate')->orderby('groupname')->get();
}else if(Auth::user()->status == "dealer"){
  $profileList = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->select('name','base_price_ET as rate')->orderby('groupname')->get();
}else{
    $profileList = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->select('name','base_price_ET as rate')->orderby('groupname')->get();
}
 	//
return view('users.profit_calculator.index',[
   'userCollection' => $userCollection,
   'isSearched' => false,
   'profileList' => $profileList,

]);
 	//
}

 //
public function calculate_action(Request $request){

  $parent_rate = $request->parent_rate;
  $child_rate = $request->child_rate;
 	// defualt values
  $data['reseller_profit'] = 0;
		// if has CVAS
  $data['reseller_profit'] =  $child_rate - $parent_rate;
	// if dont have CVAS
  $resellerInfo = UserInfo::where('resellerid',Auth::user()->resellerid)->where('status','reseller')->select('has_license')->first();
  if($resellerInfo->has_license <= 0){
		//
   $data['resellerTaxes'] = $this->get_tax_summ($parent_rate);
   $data['dealerTaxes'] = $this->get_tax_summ($child_rate);
		//
     // $data['reseller_profit']  = $data['reseller_profit'] - ($data['dealerTaxes']['sum'] - $data['resellerTaxes']['sum'] );
     //
   $data['gross_profit'] = number_format($data['reseller_profit'],2);
   $data['tax'] = number_format($data['dealerTaxes']['sum'] - $data['resellerTaxes']['sum'],2);
   $data['net_profit'] = number_format($data['reseller_profit'] - ($data['dealerTaxes']['sum'] - $data['resellerTaxes']['sum']),2);
}
 //
return json_encode($data);
}


public function get_tax_summ($p_rate,$company_rate='yes'){

  $state = Auth::user()->state;
  $get_taxt_data = Tax::first();
  $pro_tax = DB::table('provincial_taxation')->where('state',$state)->first();
      //
  $sst_rate_per = $pro_tax->ss_tax;
  $adv_rate_per = $pro_tax->adv_tax;
  $taxRev_fll = $taxRev_cvas = $taxRev_tip = $p_rate;
        //
  if($company_rate == 'yes'){
         //
   if($get_taxt_data->fll_sst == 'yes'){
    $taxRev_fll = $p_rate / ($sst_rate_per+1);
}if($get_taxt_data->cvas_sst == 'yes'){
    $taxRev_cvas = $p_rate / ($sst_rate_per+1);
}if($get_taxt_data->tip_sst == 'yes'){
    $taxRev_tip = $p_rate / ($sst_rate_per+1);
}
        //
if($get_taxt_data->fll_adv == 'yes'){
    $taxRev_fll = $taxRev_fll / ($adv_rate_per+1);
}if($get_taxt_data->cvas_adv == 'yes'){
    $taxRev_cvas = $taxRev_cvas / ($adv_rate_per+1);
}if($get_taxt_data->tip_adv == 'yes'){
    $taxRev_tip = $taxRev_tip / ($adv_rate_per+1);
}
}
        //
$fll_charges = $taxRev_fll * $get_taxt_data->fll_tax;
$cvas_charges = $taxRev_cvas * $get_taxt_data->cvas_tax;
$tip_charges = $taxRev_tip * $get_taxt_data->tip_tax;
    //
$fll_sst = $cvas_sst = $tip_sst = 0;
$fll_adv = $cvas_adv = $tip_adv = 0;
                        //
if($get_taxt_data->fll_sst == 'yes'){
   $fll_sst = $fll_charges * $sst_rate_per;
}if($get_taxt_data->cvas_sst == 'yes'){
   $cvas_sst = $cvas_charges * $sst_rate_per;
}if($get_taxt_data->tip_sst == 'yes'){
   $tip_sst = $tip_charges * $sst_rate_per;
}
                        //
$sst_u = $fll_sst + $cvas_sst + $tip_sst;
                        //
if($get_taxt_data->fll_adv == 'yes'){
   $fll_adv = ($fll_charges + $fll_sst) * $adv_rate_per;
}if($get_taxt_data->cvas_adv == 'yes'){
   $cvas_adv = ($cvas_charges + $cvas_sst) * $adv_rate_per;
}if($get_taxt_data->tip_adv == 'yes'){
   $tip_adv = ($tip_charges + $tip_sst) * $adv_rate_per;
}
    // 
$adv_tax_u = $fll_adv + $cvas_adv + $tip_adv;

$data = array('sst' => $sst_u, 'adv' => $adv_tax_u, 'sum' => ($sst_u + $adv_tax_u) );

return $data;
}




}
