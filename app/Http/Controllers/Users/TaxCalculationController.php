<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\UserInfo;
use App\model\Users\Tax;
use Illuminate\Http\Request;
use App\MyFunctions;




class TaxCalculationController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
 public function __construct()
 {

 }

 //////////////////////////////////////////////////////////////////////

 public function tax_calculation()
 {
   ///////////////////// check access ///////////////////////////////////
     if(!MyFunctions::check_access('Tax Calculator',Auth::user()->id)){
            abort(404);
        }
   /////////////////////////////////////////////////////
   if(Auth::user()->status == "dealer"){
      $profileList = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->orderby('groupname')->get();
   }
   else{
      $profileList = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->orderby('groupname')->get();
   }
   return view('users.tax-calculator.tax-calculation',['profileList' => $profileList]);
}

//////////////////////////////////////////////////////////////////////

public function tax_calculation_action(Request $request){
 $profile = $request->get('profile');
 $consumerPrice = $request->get('consumerPrice');
    //
 if(Auth::user()->status == "dealer"){
   $profileData = DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->where(['name' => $profile])->first();
}else{
   $profileData = SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->where(['name' => $profile])->first();
}
$infoData = UserInfo::where(['dealerid' => Auth::user()->dealerid])->where(['status' => 'dealer'])->first();
$taxData = Tax::first();

//
$data['filerText'] = 'none';
//
$data['margin'] = $consumerPrice - $profileData->base_price_ET;
//
$fll_sst = $cvas_sst = $tip_sst = 0;
$fll_adv = $cvas_adv = $tip_adv = 0;
//
if($taxData->fll_sst == 'yes'){
   $fll_sst = $consumerPrice * $taxData->fll_tax * $profileData->sstpercentage;
}if($taxData->cvas_sst == 'yes'){
   $cvas_sst = $consumerPrice * $taxData->cvas_tax * $profileData->sstpercentage;
}if($taxData->tip_sst == 'yes'){
   $tip_sst = $consumerPrice * $taxData->tip_tax * $profileData->sstpercentage;
}
//
$data['sst'] = $fll_sst + $cvas_sst + $tip_sst;
//
if($taxData->fll_adv == 'yes'){
   $fll_adv = (($consumerPrice * $taxData->fll_tax) + $fll_sst) * $profileData->advpercentage;
}if($taxData->cvas_adv == 'yes'){
   $cvas_adv = (($consumerPrice * $taxData->cvas_tax) + $cvas_sst) * $profileData->advpercentage;
}if($taxData->tip_adv == 'yes'){
   $tip_adv = (($consumerPrice * $taxData->tip_tax) + $tip_sst) * $profileData->advpercentage;
}

$data['adv'] = $fll_adv + $cvas_adv + $tip_adv;
//

if($data['margin'] > 0){
   if($infoData->is_filer == 'filer'){
      $data['filerText'] =  'Filer';
      $data['tax'] = $data['margin'] * $taxData->filer_tax;
   }elseif($infoData->is_filer == 'non filer'){
      $data['filerText'] =  'Non Filer';
      $data['tax'] = $data['margin'] * $taxData->non_file_tax;
   }else{
      $data['tax'] = 0;
      $data['filerText'] =  'none';
   }
   
}else{
   $data['tax'] = 0 ;
}
$data['total'] = $profileData->base_price_ET + $data['sst'] + $data['adv'] + $data['tax'];
$data['consumertotal'] = $consumerPrice + $data['sst'] + $data['adv'];
//
//
$data['sst'] = number_format($data['sst'],2);
$data['adv'] = number_format($data['adv'],2);
$data['consumertotal'] = number_format($data['consumertotal'],2);
$data['total'] = number_format($data['total'],2);
$data['tax'] = number_format($data['tax'],2);
$data['margin'] = number_format($data['margin'],2);
//
return json_encode($data);


}





}
?>