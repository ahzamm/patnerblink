<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\model\Users\Ticker;
use App\model\Users\UserInfo;
use App\model\Users\ProfileMargins;
use App\model\Users\Profile;
use Illuminate\Support\Facades\DB;


class MarginController extends Controller
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
 	return view('users.billing.set_margin');
 }
 public function loadUserStatus(Request $request)
 {
     $status = $request->status;
     $select = $status == 'dealer' ? 'dealerid' : $status == 'subdealer' ? 'sub_dealer_id' : $status == 'trader' ? 'trader_id' : '';
     $data = UserInfo::where('status',$status)->where('manager_id',Auth::user()->manager_id)->get();
     return $data;
 }

 public function updateMigrateData(Request $request)
 {
     $data = $request->selectedname;
     $status = $request->status;
     if($status == 'dealer'){
     $dbData = ProfileMargins::where('dealerid',$data)->where('sub_dealer_id','')->orderby('groupname')->get();
     }else if($status == 'subdealer'){
        $dbData = ProfileMargins::where('sub_dealer_id',$data)->where('trader_id','')->orderby('groupname')->get();
     }else{
        $dbData = ProfileMargins::where('trader_id',$data)->orderby('groupname')->get();
     }
     foreach ($dbData as $key => $value) {
        $profilename = Profile::where('groupname',$value->groupname)->orderby('groupname')->first();
     ?>
        <input type="hidden" name="marginid[]" value="<?=$value->id?>">
     <tr>
        <td><?=$profilename['name']?></td>
        <td><input type="text" name="margin[]" id="" class="form-control text-center" value="<?=$value->margin?>"></td>
        </tr>
     <?php
     }
 }
 public function updateMarginDB(Request $request)
 {
    $id = $request->marginid;
    $margin = $request->margin;
     foreach ($id as $key => $value) {
      DB::table('profile_margins')
      ->where('id',$id[$key])
      ->update(
          [
              'margin' => $margin[$key],
          ]
      );
     }
     return "Done";
 }
}
?>