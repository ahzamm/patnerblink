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
@include('users.dealer.subDealerFreeze')
@section('content')
<style type="text/css">
  .hideSelect select{
    display: none !important;
  }
</style>
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Suspicious Consumers
          <span class="info-mark" onmouseenter="popup_function(this, 'suspicious_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <table id="example-2" class="table table-striped dt-responsive display" style="width:100%">
        <thead>
          <tr>
            <th>Serial#</th>
            <th>Consumer (ID)</th>
            <th>Last Login (Date & Time)</th>
            <th>Last Login Duration</th>
            @php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
            <th>Contrator & Trader</th>
            @php } @endphp
            <th>Action</th>
          </tr>
          <tr>
            <td class="hideSelect"></td>
            <td class="hideSelect"></td>
            <td class="hideSelect"></td>
            <td class="hideSelect"></td>
            @php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
            <td class=""></td>
            @php } @endphp
            <td class="hideSelect"></td>
          </tr>
        </thead>
        <tbody>
          <?php
          $count=1;
          foreach($users as $data){
            $session_time = '';
            $lastLogin_datetime = 'Not login yet';
            $hourdiff = '';
            $lastLoginDetail = App\model\Users\RadAcct::where('username',$data->username)->orderBy('radacctid','DESC')->first();
            if($lastLoginDetail){
              $datetime1=new DateTime($lastLoginDetail->acctstoptime);
              $datetime2=new DateTime("now");
              $interval=$datetime1->diff($datetime2);
              $Day=$interval->format('%dD' );
              if($Day>1)
              {
                $session_time = $interval->format('%d Days');
              }
              $lastLogin_datetime = $lastLoginDetail->acctstoptime;
              $now = date('Y-m-d H:i:s');
              $hourdiff = round((strtotime($now) - strtotime($lastLoginDetail->acctstoptime))/3600, 1);
            }
            if( $hourdiff > 24 || empty($lastLoginDetail)){
              $userDetail = App\model\Users\UserInfo::where(['username' => $data->username])->select('sub_dealer_id')->first();
              $sub_dealer_id = $userDetail->sub_dealer_id;
              if($sub_dealer_id == ''){
                $sub_dealer_id='My Users';
              }
              ?>
              <tr>
                <td>{{$count++}}</td>
                <td class="td__profileName">{{$data->username}}</td>
                <?php if($lastLogin_datetime == 'Not login yet'){?>
                  <td>{{$lastLogin_datetime}}</td>
                <?php }else{?>
                  <td>{{date('M d,Y H:i:s',strtotime($lastLogin_datetime))}}</td>
                <?php }?>
                <td><span style="color:red">{{(empty($session_time)) ? 'N/A' : $session_time }}</span></td>
                @php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
                <td>{{$sub_dealer_id}}</td>
                @php } @endphp
                <td><button class="btn btn-sm btn-primary" onclick="showDetails('{{$data->username}}')"><i class="fa fa-eye"></i> Check Detail</button></td>
              </tr>
              <?php 
            } 
          } 
          ?>
        </tbody>
      </table>
    </section>
  </section>
</div>
<div aria-hidden="true"  role="dialog" tabindex="-1" id="susDetails" class="modal fade" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
        <h4 class="modal-title" style="text-align: center;color: white"> Suspicious Consumer Detail</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-striped table-bordered" style="min-width:700px">
                <thead>
                  <tr>
                    <th>Consumer Name</th>
                    <th>Address</th>
                    <th>Contact Number</th>
                    <th>Internet Profile</th>
                    <th>Expire Date & Time</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><span id="fullname"></span></td>
                    <td><span id="address"></span></td>
                    <td><span id="contact"></span></td>
                    <td><span id="packagename"></span></td>
                    <td><span id="expDate"></span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('ownjs')
<script>
  function showDetails(username){
    console.log(username);
    $.ajax({
      url: "{{route('users.susUserDetails')}}",
      type: "GET",
      data: {username:username},
      dataType: "json",
      success: function(data){
        $('#address').html(data.permanent_address);
        $('#expDate').html(data.expire_datetime);
        $('#packagename').html(data.name);
$('#contact').html(data.mobilephone);   // not functional
$('#fullname').html(data.firstname+' '+data.lastname);
$('#susDetails').modal('show');
}
});
  }
</script>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#example-2').DataTable({
      orderCellsTop: true
    });
// if($(window).width() > 1024){
  $("#example-2 thead td").each( function ( i ) {
    var select = $('<select class="form-control"><option value="">Show All</option></select>')
    .appendTo( $(this).empty() )
    .on( 'change', function () {
      table.column( i )
      .search( $(this).val() )
      .draw();
    } );
    table.column( i ).data().unique().sort().each( function ( d, j ) {
      select.append( '<option value="'+d+'">'+d+'</option>' )
    } );
  } );
} );
</script>
@endsection
<!-- Code Finalize -->