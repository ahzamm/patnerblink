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
@section('owncss')
<style type="text/css">
  .th-color{
    color: white !important;
  }
  .header_view{
    margin: auto;
    height: 40px;
    padding: auto;
    text-align: center;
    font-family:Arial,Helvetica,sans-serif;
  }
  h2{
    color: #225094 !important;
  }
  .dataTables_filter{
    margin-left: 60%;
  }
  tr,th,td{
    text-align: center;
  }
  select{
    color: black;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2>Invalid Login
              <span class="info-mark" onmouseenter="popup_function(this, 'invalid_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="table-responsive">
            <table id="example-1" class="table table-striped dt-responsive display">
              <thead class="text-primary" style="background:#225094c7;">
                <tr>
                  <th class="th-color">Username</th>
                  <th class="th-color">Assigned Mac-Address </th>
                  <th class="th-color">Requesting Mac-Address</th>
                  <th class="th-color">Reason</th>
                  <th class="th-color">Login-Attempt</th>
                  <th class="th-color">Login-Time</th>
                </tr>
              </thead>
              <tbody>
                @php
                $status = Auth::user()->status;
                foreach($error_log as $value){
                $username=$value->username;
                $count=App\model\Users\RadPostauth::where('authdate','>',now()->subMinutes('5')->toDateTimeString())->where('username','=',$value->username)->count();
                $infouser = App\model\Users\UserInfo::where('username','=','mob1')->get();
                foreach($infouser as $data){
                $mac12344=$data->mac_address;
              }
              @endphp
              <tr>
                <td>{{$username}}</td>
                <td>{{$mac12344}}</td>
                <td>text</td>
                <td>{{$value->rejectreason}}</td>
                <td>{{$count}}</td>
                <td>{{$value->authdate}}</td>
              </tr>
              @php } @endphp
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</section>
</div>
@endsection
<!-- Code Finalize -->