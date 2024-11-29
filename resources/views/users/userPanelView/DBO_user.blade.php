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
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Consumer (ID) Disabled <small>(But Status Online)</small>
          <span class="info-mark" onmouseenter="popup_function(this, 'consumer_id_disabled_but_online');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box">
        <div class="content-body" style="padding-top:20px">
          <table id="example-1" class="table display dt-responsive w-100 display">
            <thead>
              <tr>
                <th style="width:25px">Serial#</th>
                <th>Consumer (ID)</th>
                <th>Internet Profile</th>
                <th>Contractor (ID)</th>
                <th>Session Date & Time</th>
                <th>Expiry Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php
              $sno=1;
              foreach($allusers as $data){
              $users=App\model\Users\UserInfo::leftJoin('user_status_info', function($join1) {
              $join1->on('user_info.username', '=', 'user_status_info.username');
            })->where('user_info.username','=',$data['username'])->first(['user_info.name','user_info.dealerid','user_status_info.card_expire_on']);
            @endphp
            <?php
            $radacct = App\model\Users\RadAcct::where('acctstoptime',NULL)->where('username',$data['username'])->select('acctstarttime')->first();
            ?>
            <tr>
              <td>{{$sno++}}</td>
              <td class="td__profileName">{{$data['username']}}</td>
              <td>{{$users['name']}}</td>
              <td>{{$users['dealerid']}}</td>
              <td>{{date('M d,Y H:i:s',strtotime(@$radacct['acctstarttime'])) }}</td>
              <td>{{date('M d,Y',strtotime($users['card_expire_on']))}}</td>
              <td><button class="btn btn-danger btn-xs kickBtn" type="button" id="" data-id="{{$data['username']}}">Kick Consumer (ID)</button></td>
            </tr>
            @php
          }
          @endphp
        </tbody>
      </table>
    </div>
  </section>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script>
  $(document).ready(function() {
    $(document).on('click', '.kickBtn', function(e) {
      $(this).prop('disabled', true);
      $(this).html('processing..');
      e.preventDefault();
      var username = $(this).attr('data-id');
      $.ajax({
        type: "POST",
        url: "{{route('users.kickit')}}",
        data:'username='+username,
        success: function (data) {
          alert('Successfully Kicked');
          location.reload();
        },
        error: function(jqXHR, text, error){
          $("#outputt").html('<p style="color:red">Inavlid username or no record found</p>');
        }
      });
      return false;
    });
  });
</script>
@endsection
<!-- Code Finalize -->