<?php

namespace App\Http\Controllers\complain;


use Illuminate\Http\Request;
use App\Post;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\model\Users\UserInfo;
use App\model\Users\ExpireUser;
use App\model\Users\UserStatusInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\model\Users\RadCheck;
use App\model\Users\RadAcct;
use App\MyFunctions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ComplainController extends Controller{

    public function index(Request $request){
        ///////////////////// check access ///////////////////////////////////
        if(!MyFunctions::check_access('Complaint Feedback',Auth::user()->id)){
            abort(404);
        }
        //
        $date = $request->get('date');
        $fromDate = $request->get('fromDate');
        $toDate = $request->get('toDate');
        $via = $request->get('via');
        $status = $request->get('status');
        //
        if(empty($fromDate) && empty($toDate)){
            $toDate = date('Y-m-d');
            $fromDate = date('Y-m-d', strtotime('-60 days'));
        }
        //
        $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
        $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
        $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
        //
        // if(empty($date)){
        $url = 'https://cms.blinkbroadband.pk/get_complain?resellerid='.$resellerid.'&contractorid='.$dealerid.'&traderid='.$sub_dealer_id.'&fromDate='.$fromDate.'&toDate='.$toDate;
        // }else{
        //     $url = 'https://cms.blinkbroadband.pk/get_complain?resellerid='.$resellerid.'&contractorid='.$dealerid.'&traderid='.$sub_dealer_id;
        // }
        //
        if(!empty($date)){
            $url .= '&date='.$date;
        }if(!empty($via)){
            $url .= '&via='.$via;
        }if(!empty($status)){
            $url .= '&status='.$status;
        }
        //
        $response = Http::get($url);
        $data = $response->json();
        //
        $complainNatureAPI = 'https://cms.blinkbroadband.pk/get/complaint_nature';
        $complainNatureAPI_Response = (Http::get($complainNatureAPI))->json();
        //
        return view('users.complain.index',compact('data','complainNatureAPI_Response'));
    }
    //////////////////////////////////
    public function give_feedback(Request $request){
        $id = $request->post('id');
        $rate = $request->post('rate');
        $feedback = $request->post('feedback');
        //
        if(empty($rate)){
            return abort(403, 'Error : Kindly select stars');
        }if(empty($rate)){
            return abort(403, 'Error : Kindly give us some feedback');
        }
        //
        $response = Http::get('https://cms.blinkbroadband.pk/give_feedback?id='.$id.'&rating='.$rate.'&msg='.$feedback);
        $data = $response->json();
        return $data;
    }
    //////////////////////////////////
    public function generate_complaint(Request $request){
        //
        $user_info_id = Auth::user()->id;
        $resellerid = Auth::user()->resellerid;
        $contractorid = Auth::user()->dealerid;
        $traderid = Auth::user()->sub_dealer_id;
        //
        $complaint_nature_id = $request->get('complaint_nature_id');
        $description = $request->get('description');
        ///////////////////// check access ///////////////////////////////////
        if(!MyFunctions::check_access('Complaint Feedback',Auth::user()->id)){
            return abort(403, 'Error : Permission denied');
        }
        //
        if(empty($user_info_id) || empty($complaint_nature_id) || empty($description)){
            return abort(403, 'Error : All fields required');
        } 
        //  
        if(strlen($description) > 150){
         return abort(403, 'Error : Description is too long'); 
     }
        //
     $response = Http::get('https://cms.blinkbroadband.pk/get/store_complaint?user_info_id='.$user_info_id.'&complaint_nature_id='.$complaint_nature_id.'&description='.$description.'&resellerid='.$resellerid.'&contractorid='.$contractorid.'&traderid='.$traderid);
     $data = $response->json();
        //
     return 'Success : Complaint Registered Successfully';
 }

    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////

 public function get_complain_nature(){
    $complainNatureAPI = 'https://cms.blinkbroadband.pk/get/complaint_nature';
    $complainNatureAPI_Response = (Http::get($complainNatureAPI))->json();
        //
    ?>
    <option value="">select</option>
    <?php foreach($complainNatureAPI_Response as $value){?>
      <option value="<?= $value['id'];?>"><?= $value['name'];?></option>
      <?php 
  }
}




}



