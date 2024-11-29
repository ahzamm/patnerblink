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
<style type="text/css">
  .row{margin-left: 0px!important;margin-right: 0px!important}
</style>
@section('content')
<div class="page-container row-fluid container-fluid pt-2">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row">
      <div class="clear-fix"></div>
      <div class="header_view">
        <h2>Bulk Consumers (IDs) Create
          <span class="info-mark" onmouseenter="popup_function(this, 'bulk_consumer_create_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <div class="row justify-content-center">
        <div class="card bg-white">
          <div class="card-body" style="padding-top: 20px">
            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
              <button type="button" class="close" data-dismiss="alert"><span class="fa fa-close"></span></button>
              <strong>{{ $message }}</strong>
            </div>
            @endif
            @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
              <button type="button" class="close" data-dismiss="alert"><span class="fa fa-close"></span></button>
              <strong>{{ $message }}</strong>
            </div>
            @endif
            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <strong>Whoops!</strong> There were some problems with your input.<br>
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form action="{{route('admin.csv.upload')}}" enctype="multipart/form-data" method="POST">
              @csrf
              <div class="row">
                <div class="col-lg-3 col-md-6">
                  <div class="form-group position-relative">
                    <label>Select Manager <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_manager_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select  id="manger-dropdown" class="js-select2" name="manager_data" required>
                      <option value="{{old('manager_data')}}">--- Select Manager ---</option>
                      @foreach($manager_data  as $manger)
                      <option value="{{$manger->manager_id}}">{{$manger->manager_id}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="form-group position-relative">
                    <label>Select Reseller <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select id="reseller-dropdown" class="js-select2" name="reseller_data" required>
                      <option value="">--- First Select Manager ---</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="form-group position-relative">
                    <label>Select Contractor <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select id="dealer-dropdown" class="js-select2" name="dealer_data" required>
                      <option value="">--- First Select Reseller ---</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6">
                  <div class="form-group position-relative">
                    <label>Select Trader</label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select id="trader-dropdown" class="js-select2" name="trader_data" >
                      <option value="">--- First Select Contractor ---</option>    
                    </select>
                  </div>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                  <div class="form-group position-relative">
                    <label>Select File <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_file_upload_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="file" class="form-control" name="file" id="file" required>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <br>
                  <input type="submit" class="btn btn-primary" name="submit" value="Submit" id="btn-submit">
                  <button type="button"class="btn btn-default ml-4"><a href="{{asset('sample.csv')}}"><span class="fa fa-download"></span> Download Sample (.csv) File</a></button>
                </div>
              </div>
            </form>
            <div style="padding: 2px 5px 5px 20px">
              <b>Instructions:</b>
              <ul style="padding-inline-start: 20px">
                <li>File must be <b>.csv</b> format.</li>
                <li>File must contain only <b>7</b> columns.</li>
                <li>File must contain heading of column.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center" style="margin-top:20px;">
        <div class="card bg-white">
          <div class="content-body" >
            <h3>Logs</h3>
            <table id="example-1" class="table dt-responsive display w-100">
              <thead>
                <tr>
                  <td>Serial#</td>
                  <td>Manager (ID)</td>
                  <td>Reseller (ID)</td>
                  <td>Contractor (ID) </td>
                  <td>Trader (ID) </td>
                  <td>Count </td>
                  <td>Date & Time</td>
                  <td>Action By</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach($logs as $key => $logsValue){?>
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$logsValue->manager_id}}</td>
                    <td>{{$logsValue->reseller_id}}</td>
                    <td>{{$logsValue->dealer_id}}</td>
                    <td>{{$logsValue->trader_id}}</td>
                    <td>{{$logsValue->count}}</td>
                    <td>{{date('M d,y',strtotime($logsValue->date))}} {{$logsValue->time}}</td>
                    <td>{{$logsValue->created_by}}</td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
  </section>
  <!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
<script>
  $(document).ready(function () {
/*------------------------------------------
--------------------------------------------
Manager Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#manger-dropdown').on('change', function () {
  var manager_id = this.value;
  $("#reseller-dropdown").html('');
  $.ajax({
    url: "{{route('admin.reseler')}}",
    type: "POST",
    data: {
      manager_id: manager_id,
      _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
      $('#reseller-dropdown').html('<option value="">--- Select Reseller ---</option>');
      $.each(result.reseller, function (key, value) {
        $("#reseller-dropdown").append('<option value="' + value
          .resellerid + '">' + value.resellerid + '</option>');
      });
      $('#dealer-dropdown').html('<option value="">--- First Select Reseller ---</option>');
    }
  });
});
/*------------------------------------------
--------------------------------------------
Reseller Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#reseller-dropdown').on('change', function () {
  var reseller_id = this.value;
  $("#dealer-dropdown").html('');
  $.ajax({
    url: "{{route('admin.dealer')}}",
    type: "POST",
    data: {
      reseller_id: reseller_id,
      _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
      $('#dealer-dropdown').html('<option value="">--- Select Contractor ---</option>');
      $.each(result.dealer, function (key, value) {
        $("#dealer-dropdown").append('<option value="' + value
          .dealerid + '">' + value.dealerid + '</option>');
      });
      $('#trader-dropdown').html('<option value="">--- First Select Contractor ---</option>');
    }
  });
});
/*------------------------------------------
--------------------------------------------
Trader Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#dealer-dropdown').on('change', function () {
  var dealer_id = this.value;
  $("#trader-dropdown").html('');
  $.ajax({
    url: "{{route('admin.trader')}}",
    type: "POST",
    data: {
      dealer_id: dealer_id,
      _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
      $('#trader-dropdown').html('<option value="">--- Select Trader ---</option>');
      $.each(result.subdealer, function (key, value) {
        $("#trader-dropdown").append('<option value="' + value
          .sub_dealer_id + '">' + value.sub_dealer_id + '</option>');
      });
    }
  });
});
});
</script>
@endsection
<!-- Code Finalize -->