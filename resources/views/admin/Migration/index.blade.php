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
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="col-lg-12" >
          <div class="header_view">
            <h2>IPs & Internet Profile Migration
              <span class="info-mark" onmouseenter="popup_function(this, 'consumer_migration_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
            <div id="returnMsg"></div>
          </div>
        </div>
        <div class="col-md-12">
          <section class="box">
            <div class="content-body">
              <form id="ip-migration" method="POST">
                @csrf
                <div class="row register-form">
                  <h3>CGN (IPs) Migration</h3>
                  <hr>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label for="reseller" class="form-label pull-left">Select Reseller (ID) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="reseller" class="js-select2 reseller" id=""  required>
                        <option value="">--- Select Reseller ---</option>
                        @foreach($resellers as $reseller)
                        <option value="{{$reseller->resellerid}}">{{$reseller->resellerid}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label for="dealer" class="form-label pull-left">Select Contractor (ID) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="dealer" class="form-control dealer" id="contractor" required>
                        <option value="">--- First Select Reseller ---</option>  
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label for="dealer" class="form-label pull-left">Current (BRAS) NAS</label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_bras_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" readonly id="currentnas" class="form-control" />
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group position-relative">
                      <label for="nas" class="form-label pull-left">Move AnOther (BRAS) NAS <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_bras_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="nas" class="js-select2" id="" required>
                        <option value="">--- Select (BRAS) NAS ---</option>
                        <?php
                        foreach($nas as $nasValue){ ?>
                          <option><?= $nasValue->shortname; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group pull-right">
                      <input type="submit" class="btn btn-primary btn-submit"  value="Migrate"/>
                    </div>
                  </div>
                </div> 
              </form>
              <span id="ipMigrateResponse"></span>
            </div>
          </section>
          <section class="box">
            <div class="content-body">
              <form id="pro-group-migration" method="POST">
                @csrf
                <div class="row register-form">
                  <h3>Internet Profile Groupname Migration</h3>
                  <hr>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="reseller" class="form-label pull-left">Select Reseller (ID) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="reseller" class="js-select2 reseller" id=""  required>
                        <option value="">--- Select Reseller ---</option>
                        @foreach($resellers as $reseller)
                        <option value="{{$reseller->resellerid}}">{{$reseller->resellerid}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="dealer" class="form-label pull-left">Select Contractor (ID) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="dealer" class="form-control dealer" id="pgm_dealer">
                        <option value="">--- First Select Reseller ---</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-striped dt-responsive display">
                        <thead>
                          <tr>
                            <td>Current Internet Profiles</td>
                            <td>Number Of Consumers</td>
                            <td>Migrate Internet Profiles</td>
                          </tr>
                        </thead>
                        <tbody id="pgm_tbody"></tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group pull-right">
                      <input type="submit" class="btn btn-primary btn-submit"  value="Migrate"/>
                    </div>
                  </div>
                </div> 
              </form>
              <span id="response2"></span>
            </div>
          </section>
        </section>
      </section>
      <!-- Processing -->
      <div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
          <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
            <div class="modal-body">
              <center><h1 style="color:white;">Processing....</h1>
                <p style="color:white;">please wait.</p>
              </center>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endsection
    @section('ownjs')
    <script type="text/javascript">
      $(document).ready(function() {
        $("#ip-migration").submit(function() {
          if(confirm("Are you sure you want to migrate NAS IP?")){
            $('#processLayer').modal('show');
            $.ajax({
              type: "POST",
              url: "{{route('admin.migration.store')}}",
              data:$("#ip-migration").serialize(),
              success: function (data) {
                $('html, body').scrollTop(0);
                $('#processLayer').modal('hide');
                $('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
              },
              error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('html, body').scrollTop(0);
$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
$('#processLayer').modal('hide');
}
});
          }
          return false;
        });
      });
    </script>
    <script>
      $(document).ready(function () {
/*------------------------------------------
--------------------------------------------
Reseller Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('.reseller').on('change', function () {
  var reseller_id = this.value;
  console.log(reseller_id);
var find_dealer = $(this).parent().parent().parent().find('.dealer');
$(find_dealer).html('');
$.ajax({
  url: "{{route('admin.dealer')}}",
  type: "POST",
  data: {
    reseller_id: reseller_id,
    _token: '{{csrf_token()}}'
  },
  dataType: 'json',
  success: function (result) {
    $(find_dealer).html('<option value="">--- Select Contractor ---</option>');
    $.each(result.dealer, function (key, value) {
      $(find_dealer).append('<option value="' + value
        .dealerid + '">' + value.dealerid + '</option>');
    });
  }
});
});
$('#contractor').on('change', function () {
  var reseller_nas = this.value;
  var find_nas = $(this).parent().parent().parent().find('.nas');
  $("find_nas").html('');
  $.ajax({
    url: "{{route('admin.migration.nas')}}",
    type: "GET",
    data: {
      reseller_nas: reseller_nas,
      _token: '{{csrf_token()}}'
    },
// dataType: 'json',
success: function (result) {
  $("#currentnas").val(result);
}
});
});
});
</script>
<script type="text/javascript">
// $("#pro-group-migration").submit(function() {
  $(document).on('change','#pgm_dealer',function(){ 
    $.ajax({
      type: "POST",
      url: "{{route('admin.migration.getprofile')}}",
      data:$("#pro-group-migration").serialize(),
      success: function (data) {
        $('#pgm_tbody').html(data);
      },
      error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#output').html(error);
}
});
    return false;
  });
</script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#pro-group-migration").submit(function() {
      if(confirm("Are you sure you want to migrate Groupname?")){
        $('#processLayer').modal('show');
        $.ajax({
          type: "POST",
          url: "{{route('admin.migration.groupname')}}",
          data:$("#pro-group-migration").serialize(),
          success: function (data) {
            $('html, body').scrollTop(0);
            $('#processLayer').modal('hide');
            $('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
          },
          error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('html, body').scrollTop(0);
$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
$('#processLayer').modal('hide');
}
});
      }
      return false;
    });
  });
</script>
<script>
  @endsection