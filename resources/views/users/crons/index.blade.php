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
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2></h2>
          </div>
          <div class="table-responsive">
            <table id="example-1" class="table table-striped">
              <thead class="text-primary" style="background:#225094c7;">
                <tr>
                  <th class="th-color">Sno</th>
                  <th class="th-color">Username</th>
                  <th class="th-color">Profile</th>
                </tr>
              </thead>
              @php
              $count=1;
              @endphp
              <tbody>
                @forelse ($is_profile as $key =>  $item)
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$item['username']}}</td>
                  <td>{{$item['groupname']}}</td>
                </tr>
                @empty
                @endforelse
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
@endsection
@section('ownjs')
@endsection
<!-- Code Finalize -->