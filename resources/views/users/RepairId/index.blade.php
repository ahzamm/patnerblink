<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="header_view">
        <h2>Repair Consumer (ID)
          <span class="info-mark" onmouseenter="popup_function(this, 'repair_consumer_id');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box ">
        <div class="content-body" style="border-bottom: 0">
          <p style="font-size: 18px;text-align:center;margin-bottom:30px;padding-top:30px"><b> Enter the correct Consumer (ID) to be repaired, in case of any mismatch Consumer (ID) will not repaired. <br><br/> </p>
            <form id="repair-id">
              @csrf
              <div class="" style="display:flex;align-items:center;justify-content:center;column-gap:20px;row-gap:10px;margin-top:20px;flex-wrap:wrap">
                <label for="username">Consumer (ID) <span style="color: red">*</label>
                  <input type="text" class="form-control" name="username"  placeholder="Enter Consumer (ID) here..." required style="max-width:300px">
                  <button type="submit" name="submit" class="btn btn-primary" id="repair_btn">Repair Now </button>
                </div>
              </form>
              <span id="outputt"></span>
            </div>
          </section>
          <div class="content-body" style="padding-top:20px">
            <table id="example-1" class="table dt-responsive display w-100">
              <thead>
                <tr>
                  <td>Serial#</td>
                  <td>Consumer (ID)</td>
                  <td>Contractor (ID) </td>
                  <td>Trader (ID) </td>
                  <td>Date & Time</td>
                  <td>Action By</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach($repair_log as $key => $repairValue){ 
                  $userInfo = DB::table('user_info')->where('username',$repairValue->username)->select('dealerid','sub_dealer_id')->first();
                  $actionBy = DB::table('user_info')->where('id',$repairValue->action_by)->select('username')->first();
                  ?>
                  <tr>
                    <td>{{$key+1}}</td>
                    <td class="td__profileName">{{$repairValue->username}}</td>
                    <td>{{$userInfo->dealerid}}</td>
                    <td>{{ (empty($userInfo->sub_dealer_id)) ? 'N/A' : $userInfo->sub_dealer_id }}</td>
                    <td>{{date('M d,Y H:i:s' ,strtotime($repairValue->datetime))}}</td>
                    <td>{{$actionBy->username}}</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </section>
      </section>
    </div>
    <div class="modal" id="counter" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <h1 id="countdown" class="number_counter"></h1>
            <p class="countdown_msg">Please be patient ...</p>
          </div>
        </div>
      </div>
    </div>
    @endsection
    @section('ownjs')
    <script type="text/javascript">
// Countdown Timer Start
function showCounter(response) {
  document.getElementById("countdown").innerHTML =  '10';
  $('#repair_btn').attr('disabled', true);
  var timeleft = 10;
  var downloadTimer = setInterval(() => {
    if(timeleft == 0){
      $('#counter').modal('hide');
      $('#repair_btn').attr('disabled', false);
      clearInterval(downloadTimer);
      document.getElementById("countdown").innerHTML =  '';
      $('#outputt').html(response);
    }
    document.getElementById("countdown").innerHTML =  timeleft;
    timeleft -= 1;
  }, 900);
  $('#counter').modal('show');
}
// Countdown Timer End
$(document).ready(function() {
  $("#repair-id").submit(function() {
    $('#outputt').html("");
    $.ajax({
      type: "POST",
      url: "{{route('users.repair.index')}}",
      data:$("#repair-id").serialize(),
//
success: function (data) {
  showCounter('<p style="color:green;text-align:center;font-size:18px;margin-top:20px">'+data+'</p>');
},
error: function(jqXHR, text, error){
  var err = eval("(" + jqXHR.responseText + ")");
  $('#outputt').html('<p style="color:red;text-align:center;font-size:18px;margin-top:20px">'+err.Message+'</p>');
}
});
    return false;
  });
});
</script>
@endsection
<!-- Code Finalize -->