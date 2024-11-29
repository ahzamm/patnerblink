<?php
namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\model\Users\UserExpireLog;
use App\model\Users\RadAcct;
use App\model\Users\UserInfo;
use App\model\Users\DealerProfileRate;
use App\model\Users\ResellerProfileRate;
use App\model\Users\SubdealerProfileRate;
use App\model\Users\Profile;
class MaxDataUsageController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
}
public function index(Request $request){

return view('users.billing.max_data_usage');

}


//
public function profilename($groupname){
  $groupname=str_replace('BE-', '', $groupname);
  $groupname=str_replace('k', '', $groupname);
  $profileName = Profile::select('name')->where(['groupname' => $groupname])->first();
  return $profileName['name'];
}
//
public function ByteSizee($bytes)
{
  $size = $bytes / 1024;
  if ($size < 1024) {
    $size = number_format($size, 2);
    $size .= ' KB';
  } else {
    if ($size / 1024 < 1024) {
      $size = number_format($size / 1024, 2);
      $size .= ' MB';
    } else if ($size / 1024 / 1024 < 1024) {
      $size = number_format($size / 1024 / 1024, 2);
      $size .= ' GB';
    } else if ($size / 1024 / 1024 / 1024 < 1024) {
      $size = number_format($size / 1024 / 1024 / 1024, 2);
      $size .= ' TB';
    } else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
      $size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
      $size .= ' PB';
    }
  }
  $size = preg_replace('/.00/', '', $size);
  return $size;
}
  //
  //
public function data_exceed_consumers_list(Request $request){
    //
  $users=array();

  //
  $resellerid = $request->reseller_data;
  $dealerid = $request->dealer_data;
  $sub_dealer_id = $request->trader_data;
  $profile =  $request->proSelect;
  $rangeFrom =  $request->rangeFrom;
  $rangeTo =  $request->rangeTo;
  $unit =  $request->unit;
//
// $downlimit = 1099511627776;//1 TB
// $downlimit = 1099;//1 TB
  $limitFrom = $this->convertToBytes($rangeFrom, $unit);
  $limitTo = $this->convertToBytes($rangeTo, $unit);
//
$whereArray = array();
//
if(!empty($resellerid)){
 array_push($whereArray,array('resellerid' , $resellerid));
}if(!empty($dealerid)){
 array_push($whereArray,array('dealerid' , $dealerid));
}if(!empty($sub_dealer_id)){
 array_push($whereArray,array('sub_dealer_id' , $sub_dealer_id));   
}
//
$maxData = UserInfo::select('username','profile','name','sub_dealer_id','qt_used','resellerid','dealerid')
->where($whereArray)
->where('status','=','user')
->where('name',$profile)
// ->where('qt_used','>',$downlimit)
// ->orWhere('qt_used','>','qt_total')
->where('qt_used','>=',$limitFrom)
->where('qt_used','<=',$limitTo)
->orderBy('qt_used','DESC')
->get();
//
$num = 1;
foreach ($maxData as $key => $value) {
  ?>
  <tr>
    <td><?= $num;?></td>
    <td class="td__profileName"><?= $value->username;?></td>
    <td><?= $value->resellerid;?></td>
    <td><?= $value->dealerid;?></td>
    <td><?= (empty($value->sub_dealer_id)) ? 'N/A' : $value->sub_dealer_id ;?></td>
    <td style="font-weight:bold"><?= $value->name;?></td>
    <td  class="" style="font-weight:bold"><?= $this->ByteSizee($value['qt_used']); ?></td>
    <td>
      <a href="#" class="btn btn-primary btn-xs showGraph" data-username = "<?= $value->username;?>" >Live Graph</a>
    </td>
  </tr>

  <?php
  //
  $num++;

//   //
//   $users['username'][]=$value->username;
//   $users['subdealerid'][]=$value->sub_dealer_id;
//   $users['resellerid'][]=$value->resellerid;
//   $users['dealerid'][]=$value->dealerid;
//   // $users['profile'][]=$this->profilename($value->profile);
//   $users['profileName'][]= $value->name;
//   $users['download'][] = $this->ByteSizee($value['qt_used']);
// //


}
//
}



public function convertToBytes($size, $unit)
{
    $units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);
    //
    $sizeInBytes = $size * pow(1024, $units[$unit]);
    //
    return $sizeInBytes;
}


  //
}