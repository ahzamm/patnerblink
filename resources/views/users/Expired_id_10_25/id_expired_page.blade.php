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
<meta name="_token" content="{{ csrf_token() }}">
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <div class="header_view">
            <h2>Consumer Expire by Forcefully
              <span class="info-mark" onmouseenter="popup_function(this, 'consumer_forcefully_expire');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <section class="box">
            <div class="col-md-12 bg-success success-div" style="display: none">
              <p style="font-size: 20px;padding: 10px" class="success-message text-white" ></p>
            </div>
            <header class="content-body" style="padding-top:20px">
              <center>
                <p><span style="color: red;"><b style="font-size:20px">NOTE:</b></span></p>
                <p style="font-size: 18px;"><b> Enter the correct Consumer (ID) to be expired, in case of any mismatch Consumer (ID) will not expire. You can use this option only month of dates <span style="font-size:22px">10<sup>th</sup></span> and <span style="font-size:22px">25<sup>th</sup></span>.</b> <br><br/> <b style="font-size: 24px">
                  کنزیومر آئی ڈی کی میعاد کو ختم کرنے کے لیے درست کنزیومر آئی ڈی کا اندراج کریں- اس آپشن کو استعمال کرنے کے لیے ١٠ اور ٢٥ تاریخ مقرر ہے  
                </b></p>
              </center>
            </header>
            <div class="content-body">
              <form>
                <div class="">
                  <div class="" style="display:flex;align-items:center;justify-content:center;column-gap:20px;row-gap:10px;margin-top:20px;flex-wrap:wrap">
                    <label for="username">Consumer (ID) <span style="color: red">*</label>
                      <input type="text" class="form-control" name="username" id="usernames"  placeholder="Enter Consumer (ID) here..." required style="max-width:300px">
                      <a href="#" onclick="showModal();" class="btn btn-primary">Expire Now</a>
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="error-div" style="padding:10px;margin-top: 25px;font-weght: bold;font-size:20px; color: #fff;color: rgb(255, 4, 4);display:none;">
                      <center>
                        <span class="error-message"></span>
                      </center>
                    </div>
                  </div>
                </form>
              </div>
            </section>
            <section class="box" id="error-section" style="display: none">
              <div class="content-body">
                <header class="panel_header" style="min-height:40px">
                  <h2 class="title" style="margin-top:0px;padding-left:20px;line-height:20px;
                  font-weight:500;padding-top:10px">Result:</h2>
                </header>
                <div class="content-body">
                  <div class="row">
                    <div class="col-12">
                      <table class="table" border="2">
                        <thead>
                          <tr>
                            <th style="width: 130px">Consumer (ID)</th>
                            <th>Error Description</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </section>
    </section>
  </div>
  <!-- Delete Modal Start -->
  <div class="modal fade" id="deleteModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="background-color:red">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white">Alert</h4>
        </div>
        <div class="modal-body">
          <h4>Do you realy want to delete this?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" id="deletbtn" class="btn btn-danger">Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal Ends -->
  <!-- Delete Modal Start -->
  <div class="modal fade" id="emptyModel" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header" style="background-color:red">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white">Alert</h4>
        </div>
        <div class="modal-body">
          <h4>Consumer (ID) is required.</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal Ends -->
  @endsection
  @section('ownjs')
  <script>
    function showModal() {
      var username = $("#usernames").val();
      if(username == ''){
        $('#emptyModel').modal('show');
      }
      else{
        $('#deleteModel').modal('show');
      }
    }
    $(document).ready(function(){
      setTimeout(function(){
      });
    });
    jQuery(document).ready(function($){
// CREATE
$("#deletbtn").click(function (e) {
  e.preventDefault();
  var username = $("#usernames").val();
  $.ajax({
    type: "POST",
    url: "{{route('billingDeleteUserPost')}}",
    data: {username:username},
    dataType: 'json',
    success: function (data) {
      $("tbody").html("");
      if(data.users){
        $.each(data.users, function(index,item){
          console.log(item);
          $("tbody").append('<tr style="font-weight: bold;"><td class="td__profileName">'+item+'</td><td style="color:#f00;">This Consumer (ID) Is Not Exist In Database Please Enter Correct Consumer (ID)</td></tr>');
          $("#error-section").show();
          $(".success-div").hide();
          $(".error-message").hide();
          $(".error-div").hide();
          $("#deleteModel").modal('hide');
        });
      }
      if(data.error){
        $(".error-message").html(data.error);
        $(".error-message").show();
        $("#error-section").hide();
        $(".error-div").show();
        $(".success-div").hide();
        $("#deleteModel").modal('hide');
      }
      if(data.success){
        $(".success-message").html(data.success);
        $(".success-div").show();
        $("#error-section").hide();
        $(".error-message").hide();
        $(".error-div").hide();
        $("#deleteModel").modal('hide');
        setTimeout(function() {
          $(".success-div").hide();
        }, 5000);
      }             
    }
  });
});
});
</script>
@endsection
<!-- Code Finalize -->