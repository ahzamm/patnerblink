<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;



class RepairIdController extends Controller
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

 public function index(){
   return view('admin.RepairId.index');
}

//////////////////////////////////////////////////////////////////////

public function store(Request $request){
  $username = $request->username;
  //
  $url='http://cron.lbi.net.pk/partner-cron/repair_id.php?username='.$username;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
  //
  return $result;
}

//////////////////////////////////////////////////////////////////////

public function destroy(Request $request){
 $id =$request->get('id');
}


}
