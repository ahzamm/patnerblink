<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Users\Services;



class ServicesController extends Controller
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

    return view('admin.services.index');
}
//
public function service_list(){
    //
    $serviceList =  Services::all();
    //
    $num = 0;
    foreach($serviceList as $key => $serviceListValue){ 
        $num++;
        $ping = $this->ping($serviceListValue->ip_address);
        if($ping[0] == 1){
            $pingRes = '<i class="fa fa-server" style="color:green;font-size:22px;"></i>';
        }else{
            $pingRes = '<i class="fa fa-server" style="color:red;font-size:22px;"></i>';
        }

        ?>
        <tr>
          <td style="width: 100px"><?= $num;?></td>
          <td class="td__profileName"><?= $serviceListValue->server_name;?></td>
          <td><?= $pingRes;?></td>
          <td><?= str_replace('time','',$ping[1]);?></td>
          <td><?= $serviceListValue->domain_name;?></td>
          <td><?= $serviceListValue->ip_address;?></td>
          <td><?= $serviceListValue->service?></td>
          <td><?= $this->curl('https://api.logon.com.pk/linux-services/cpu.php?host='.$serviceListValue->ip_address.'&username='.$serviceListValue->username.'&password='.$serviceListValue->password.'&port='.$serviceListValue->port);?></td>
          <td><?= $this->curl('https://api.logon.com.pk/linux-services/ram.php?host='.$serviceListValue->ip_address.'&username='.$serviceListValue->username.'&password='.$serviceListValue->password.'&port='.$serviceListValue->port);?></td>
          <td>
            <div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-default btn-xs serviceBtn" data-action="restart" data-id="<?= $serviceListValue->id;?>" data-toggle="tooltip" title="Restart" data-placement="top"><i class="las la-undo-alt"></i></button>
              <button type="button" class="btn btn-default btn-xs serviceBtn" data-action="start" data-id="<?= $serviceListValue->id;?>" data-toggle="tooltip" title="Start" data-placement="top"><i class="las la-play"></i></button>
              <button type="button" class="btn btn-default btn-xs serviceBtn" data-action="stop" data-id="<?= $serviceListValue->id;?>" data-toggle="tooltip" title="Stop" data-placement="top"><i class="las la-stop"></i></button>
              <button type="button" class="btn btn-default btn-xs editBtn" data-id="<?= $serviceListValue->id;?>" data-toggle="tooltip" title="Edit" data-placement="top"><i class="las la-pen"></i></button>
              <button type="button" class="btn btn-default btn-xs deleteBtn" data-id="<?= $serviceListValue->id;?>" data-toggle="tooltip" title="Delete" data-placement="top"><i class="las la-times"></i></button>
          </div>

      </td>
  </tr>

  <?php
} 
//

}
//
public function store(request $request){
        //
    $service = new Services();
    $service->server_name = $request->server_name;
    $service->domain_name = $request->domain_name;
    $service->ip_address  = $request->ip_address;
    $service->api_url = $request->api_url;
    $service->service = $request->service;
    $service->start_cmd = $request->start_cmd;
    $service->stop_cmd = $request->stop_cmd;
    $service->restart_cmd = $request->restart_cmd;
    //
    $service->username = $request->username;
    $service->password = $request->password;
    $service->port = $request->port;

    $service->save();
        //
    session()->flash('success','Added Successfully');
    return back();
}
    //
public function action(request $request){
    $id = $request->id;
    $action = $request->action;
        //
    if(!empty($id) && !empty($action)){
        $service = Services::where('id',$id)->first();
            //
        if(!$service){
            return 'Error : Invalid Service';
        }
            //
        $api_url = $service->api_url;
            //
        if($action == 'start'){
            $cmd = $service->start_cmd;
        }elseif($action == 'stop'){
            $cmd = $service->stop_cmd;
        }elseif($action == 'restart'){
            $cmd = $service->restart_cmd;
        }else{
            return 'Error : Invalid Command';   
        } 
        //
        $final_url = $api_url.'?cmd='.$cmd;
        //
        $url = $final_url;
        $url = str_replace(" ","+",$url);
        //
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url"); 
        //
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        return 'Successfully '.$action;
        //
    }

}
    //
public function edit(request $request){
    $id = $request->id;
    $service = Services::where('id',$id)->first();
    return json_encode($service);
}
//
public function update(request $request){
    //
    $id = $request->id;
    //
    $service = Services::where('id',$id)->first();
    $service->server_name = $request->server_name;
    $service->domain_name = $request->domain_name;
    $service->ip_address  = $request->ip_address;
    $service->api_url = $request->api_url;
    $service->service = $request->service;
    $service->start_cmd = $request->start_cmd;
    $service->stop_cmd = $request->stop_cmd;
    $service->restart_cmd = $request->restart_cmd;
    //
    $service->username = $request->username;
    $service->password = $request->password;
    $service->port = $request->port;
    //
    $service->save();
        //
    session()->flash('success','Updated Successfully');
    return back();
}
//
public function delete(request $request){
    //
    $id = $request->id;
    $service = Services::where('id',$id)->delete();
    //
    if($service){
        session()->flash('success','Deleted Successfully');   
    }   
}
///


function ping($ip){
    set_time_limit(1);
    //
    exec("ping -c 1 -q " . $ip, $ping_time);
    // 
    if(isset($ping_time[7])){
        $response = $ping_time[7];
    }else{ 
        $response = $ping_time[3];
    }
    //
    if (strpos($response, 'packets transmitted') !== false) {
    //
        $packet_loss = explode(',', $response);
    }
    //
    $res = str_replace('received','',$packet_loss[1]);
    //
    return $data = array($res,$packet_loss[3]);
}
//
//
function curl($url){
    //
    $url = str_replace(" ","+",$url);
        //
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "$url"); 
        //
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    return $result;
    //    
}



}
