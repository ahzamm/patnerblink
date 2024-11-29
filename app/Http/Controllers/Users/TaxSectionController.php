<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use Illuminate\Support\Facades\DB;
use App\model\Users\DealerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\Profile;




class TaxSectionController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {}
      public function groupname(){
        if(Auth::user()->status == "dealer"){
          return $taxgroups = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->select('taxgroup')->first();
        }else if(Auth::user()->status == "subdealer"){
          return $taxgroups = SubdealerProfileRate::where('sub_dealer_id',Auth::user()->sub_dealer_id)->select('taxgroup')->first();
        }
      }
    public function profilename(){
      $name = array();
      if(Auth::user()->status == "dealer"){
      $groupnames = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->select('groupname')->get();
    }else if(Auth::user()->status == "subdealer"){
      $groupnames = SubdealerProfileRate::where('sub_dealer_id',Auth::user()->sub_dealer_id)->select('groupname')->get();
    }
      foreach ($groupnames as $value) {
        $profilename = Profile::where('groupname',$value['groupname'])->select('name')->first();
      $name[] = $profilename['name'];
      }
    return $name;
    }
     public function profileData(Request $request)
    {
      $totalTax = 0;
      $profilename =  $request->get('id');
      if(Auth::user()->status == "dealer"){
      $taxgroups = DealerProfileRate::where('dealerid',Auth::user()->dealerid)->select('taxgroup')->first();
    }else if(Auth::user()->status == "subdealer"){
      $taxgroups = SubdealerProfileRate::where('sub_dealer_id',Auth::user()->sub_dealer_id)->select('taxgroup')->first();
    }
      if(!empty($taxgroups['taxgroup'] == "A")){
        $profile = Profile::where('name',$profilename)->select('sst','adv_tax','charges','final_rates')->first();
        $totalTax =  $profile['sst'] + $profile['adv_tax'];
        ?>
     <div class="tab-content">
        <div id="homes" class="tab-pane fade in active">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">

              <div style="overflow-x: auto;">
                <table style="text-align:center" border="1"  class="table table-striped dt-responsive display">
                  <thead class="text-primary" style="background:#225094c7;">
                  <tr style="color:white;">
                  <th style="text-align:center" class="th-color">Consumer Basic Pricesss</th>
                  <th style="text-align:center" class="th-color">Sindh Sales Tax 19.5%</th>
                  <th style="text-align:center" class="th-color">Advance Tax 12.5%</th>
                  <th style="text-align:center" class="th-color">Total Tax</th>
                  <th style="text-align:center" class="th-color">Consumer Price With Tax</th>
                
                </tr>
                </thead>
                <tbody style="text-align:center">
                <tr>
                <td><?= $profile['charges'];?></td>
                  <td><?= $profile['sst'];?></td>
                  <td><?= $profile['adv_tax'];?></td>
                  <td><?= $totalTax;?></td>
                  <td><?= $profile['final_rates'];?></td>
                </tr>
                 
                 </tbody>
                </table>
</div>

              </div>
            </div>
        </div>
</div>

<?php
      }else if(!empty($taxgroups['taxgroup'] == "B")){
        $profile = Profile::where('name',$profilename)->select('sstB','adv_taxB','chargesB','final_ratesB')->first();
        $totalTax =  $profile['sstB'] + $profile['adv_taxB'];
        ?>
     <div class="tab-content">
        <div id="homes" class="tab-pane fade in active">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
              <div style="overflow-x: auto;">
                <table border="1" class="table table-striped dt-responsive display">
                  <thead class="text-primary" style="background:#225094c7;">
                  <tr style="color:white">
                  <th style="text-align:center" class="th-color">Consumer Basic Price</th>
                  <th style="text-align:center" class="th-color">Sindh Sales Tax 19.5%</th>
                  <th style="text-align:center" class="th-color">Advance Tax 12.5%</th>
                  <th style="text-align:center" class="th-color">Total Tax</th>
                  <th style="text-align:center" class="th-color">Consumer Price With Tax</th>
                
                </tr>
                </thead>
                <tbody style="text-align:center">
                <tr>
                <td><?= $profile['chargesB'];?></td>
                  <td><?= $profile['sstB'];?></td>
                  <td><?= $profile['adv_taxB'];?></td>
                  <td><?= $totalTax;?></td>
                  <td><?= $profile['final_ratesB'];?></td>
                </tr>
                 
                 </tbody>
                </table>
              </div>
              </div>
            </div>
        </div>
</div>

<?php
      }else if(!empty($taxgroups['taxgroup'] == "C")){
        $profile = Profile::where('name',$profilename)->select('sstC','adv_taxC','chargesC','final_ratesC')->first();
        $totalTax =  $profile['sstC'] + $profile['adv_taxC'];
        ?>
     <div class="tab-content">
        <div id="homes" class="tab-pane fade in active">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
              <div style="overflow-x: auto;">
                <table border="1" class="table table-striped dt-responsive display">
                  <thead class="text-primary" style="background:#225094c7;">
                  <tr style="color:white">
                  <th style="text-align:center" class="th-color">Consumer Basic Price</th>
                  <th style="text-align:center" class="th-color">Sindh Sales Tax 19.5%</th>
                  <th style="text-align:center" class="th-color">Advance Tax 12.5%</th>
                  <th style="text-align:center" class="th-color">Total Tax</th>
                  <th style="text-align:center" class="th-color">Consumer Price With Tax</th>
                
                </tr>
                </thead>
                <tbody style="text-align:center">
                <tr>
                <td><?= $profile['chargesC'];?></td>
                  <td><?= $profile['sstC'];?></td>
                  <td><?= $profile['adv_taxC'];?></td>
                  <td><?= $totalTax;?></td>
                  <td><?= $profile['final_ratesC'];?></td>
                </tr>
                 
                 </tbody>
                </table>
              </div>
            </div>
            </div>
        </div>
</div>

<?php
      }else if(!empty($taxgroups['taxgroup'] == "D")){
        $profile = Profile::where('name',$profilename)->select('sstD','adv_taxD','chargesD','final_ratesD')->first();
        $totalTax =  $profile['sstD'] + $profile['adv_taxD'];
        ?>
     <div class="tab-content">
        <div id="homes" class="tab-pane fade in active">
            <div class="row">
              <div class="col-lg-12 col-md-12 col-sm-12">
              <div style="overflow-x: auto;">
                <table border="1" class="table table-striped dt-responsive display">
                  <thead class="text-primary" style="background:#225094c7;">
                  <tr style="color:white">
                  <th style="text-align:center" class="th-color">Consumer Basic Price</th>
                  <th style="text-align:center" class="th-color">Sindh Sales Tax 19.5%</th>
                  <th style="text-align:center" class="th-color">Advance Tax 12.5%</th>
                  <th style="text-align:center" class="th-color">Total Tax</th>
                  <th style="text-align:center" class="th-color">Consumer Price With Tax</th>
                
                </tr>
                </thead>
                <tbody style="text-align:center">
                <tr>
                <td><?= $profile['chargesD'];?></td>
                  <td><?= $profile['sstD'];?></td>
                  <td><?= $profile['adv_taxD'];?></td>
                  <td><?= $totalTax;?></td>
                  <td><?= $profile['final_ratesD'];?></td>
                </tr>
                 
                 </tbody>
                </table>
              </div>
            </div>
            </div>
        </div>
</div>

<?php
      }

    }
}