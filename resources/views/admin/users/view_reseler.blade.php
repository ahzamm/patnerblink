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
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <div class="header_view">
            <h2>Resellers
              <span class="info-mark" onmouseenter="popup_function(this, 'reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="">
            @if(session('success'))
            <div id="alert" class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            <table id="example-1" class="table table-striped dt-responsive display w-100">
              <thead>
                <tr>
                  <th>Serial#</th>
                  <th>Username</th>
                  <th>Full Name</th>
                  <th>Number of Contractors </th>
                  <th>Number of Traders  </th>
                  <th>Number of Consumers</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php
                $count = 1;
                @endphp
                @foreach($resellerCollection as $data)
                <?php
                $userCount  = DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                ->where('user_status_info.card_expire_on', '>', DATE('Y-m-d H:i:s'))
                ->where('user_info.resellerid',$data->username)
                ->where('user_info.status','=','user')
                ->count();
                ?>
                <tr>
                  <td>{{$count++}}</td>
                  <td class="td__profileName">{{$data->username}}</td>
                  <td>{{$data->firstname}}</td>
                  <td>{{$data->reseller_dealer->count()}}</td>
                  <td>{{$data->reseller_sub_dealer->count()}}</td>
                  <td>{{$userCount}}</td>
                  <td>
                    <center><a href="{{route('admin.user.show',['status' => 'reseller','id' => $data->id])}}" ><button class="btn btn-primary btn-xs">
                      <i class="fa fa-eye"> </i> View</button></a>
                      <a href="{{route('admin.my.sheetdownload',['managerid' => $data->manager_id,'resellerid' => $data->resellerid])}}">
                        <button class="btn btn-success btn-xs" style="margin-top:4px;margin-bottom:4px"><i class="fa fa-download"></i>  Download (CSV) </button>
                      </a>
                    </center>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="chart-container " style="display: none;">
        <div class="" style="height:200px" id="platform_type_dates"></div>
        <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
        <div class="" style="height:200px" id="user_type"></div>
        <div class="" style="height:200px" id="browser_type"></div>
        <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
        <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
      </div>
    </section>
  </section>
</div>
@include('admin.users.model_reseler',['managerIdList' => $managerIdList])
@endsection
@section('ownjs')
<script>
  $(document).ready(function(){
    setTimeout(function(){ 
      $('.alert').fadeOut(); }, 3000);
  });
</script>
@endsection
<!-- Code Finalize -->