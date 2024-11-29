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
  .slider:before {
    position: absolute;
    content: "";
    height: 11px !important;
    width: 13px !important;
    left: 3px !important;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }
  .active{
    background-color: #225094 !important;
  }
  .title h2 {
    font-size: 30px;
    line-height: 30px;
    margin: 3px 0 7px;
    font-weight: 700;
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
            <h2>Transactions </h2>
          </div>
          <div class="col-lg-12">
            <section class="box ">
              <header class="panel_header">
                <h2 class="title pull-left">Billing Report</h2>
                <div class="actions panel_actions pull-right">
                  <a class="box_toggle fa fa-chevron-down"></a>
                </div>
                <form method="POST" action="{{route('users.billing.report.generate')}}" >
                  @csrf
                </header>
                <div class="content-body">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-group">
                        <label  class="form-label">Billing Cycle</label>
                        <select id="monthList" name="date" class="form-control"  required>
                          <option>Select Billing Cycle</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-5">
                      <div class="form-group">
                        <label  class="form-label">Dealers/Sub Dealers Name</label>
                        <select class="form-control" name="username" required >
                          <option value="">Select Option</option>
                          @foreach($userCollection as $data)
                          <option value="{{$data->username}}">{{$data->username}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <br>
                      <div class="form-group" style="    margin-top: 5px;">
                        <button class="btn btn-flat btn-info">Search</button>
                      </div>
                    </div>
                  </form>
                  <!-- Report Summary -->
                  @if($isSearched)
                  <div class="col-md-12" ">
                    <div style="overflow-x: auto;">
                      <table class="table table-bordered table-responsive" style="border: 2px #225094 solid">
                        <thead>
                          <tr>
                            <th colspan="5" style="background: #225094;color: white; font-weight: bold;">Report Summary</th>
                          </tr>
                          <tr>
                            <th>Manager Name</th>
                            <th>Reseller Name</th>
                            <th>Dealer Name</th>
                            <th>Sub Dealer Name </th>
                            <th>Net Payable Amount (PKR)</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>{{$selectedUser->manager_id}}</td>
                            <td>{{$selectedUser->resellerid}}</td>
                            <td>{{$selectedUser->dealerid}}</td>
                            <td>{{$selectedUser->sub_dealer_id}}</td>
                            <td>{{$monthlyPayableAmount}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-xs-12" style="overflow-x: auto;">
                    <div style="border: 2px #225094 solid;">
                      <div style="">
                        <h3 style="color:white;background:#225094;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Monthly Billing Report</h3>
                      </div>
                      <table id="example-1" class="table table-striped dt-responsive display">
                        <thead>
                          <tr>
                            <th>S.No </th>
                            <th>Customer ID </th>
                            <th>Package </th>
                            <th>Service Activation Date </th>
                            <th>Service Billing Start Date  </th>
                            <th>Service Billing End Date </th>
                            <th>Sub Dealer ID </th>
                            <th>Package Amount (PKR)</th>
                          </tr>
                        </thead>
                        <tbody>
                          @php $sno = 1; @endphp
                          @foreach($monthlyBillingEntries as $entry)
                          @php
                          if($selectedUser->status == "reseller"){
                          $rate= $entry->rate;
                        }elseif($selectedUser->status == "dealer"){
                        $rate= $entry->dealerrate;
                      }elseif($selectedUser->status == "subdealer"){
                      $rate= $entry->subdealerrate;
                    }
                    @endphp
                    <tr>
                      <td>{{$sno++}}</td>
                      <td>{{$entry->username}}</td>
                      <td>{{$entry->profileR->name}}</td>
                      <td>{{$entry->user_info->creationdate}}</td>
                      <td>{{$entry->user_info->user_status_info->card_charge_on}}</td>
                      <td>{{$entry->user_info->user_status_info->card_expire_on}}</td>
                      <td>{{$entry->sub_dealer_id}}</td>
                      <td>{{$rate}}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            @endif
          </div>
        </div>
      </section></div>
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
<!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
<script>
  function loadUserList(option){
    let userStatus = option.value;
    console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
    $.get(
      "{{route('admin.user.status.usernameList')}}?status="+userStatus,
      function(data){
        console.log(data);
        let content = "<option>select "+userStatus+"</option>";
        $.each(data,function(i,obj){
          content += "<option value='"+obj.username+"'>"+obj.username+"</option>"
        });
        $("#username-select").empty().append(content);
      });
  }
  function loadMonths(n) {  
    var months = new Array();
    var today = new Date();
    var year = today.getFullYear();
    var month = today.getMonth() + 1;
    var i = 0;
    do {
      months.push(year + "-" + (month > 9 ? "" : "0") + month + "-" + 25);
      if(month == 1) {
        month = 12;
        year--;
      } else {
        month--;
      }
      i++;
    } while(i < n);
    return months;
  }
  function loadMonthOptions() {   
    var optionValues = loadMonths(4);
    var dropDown = document.getElementById("monthList");
    for(var i=0; i<optionValues.length; i++) {
      var key = optionValues[i];
      var value = optionValues[i];
      dropDown.options[i] = new Option(value, key);
    }   
  }
  $(document).ready(function(){
    loadMonthOptions(); 
  });
</script>
@endsection
<!-- Code Finalize -->