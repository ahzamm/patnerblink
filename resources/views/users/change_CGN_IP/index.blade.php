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
        <h2>Change CGN IP
          <span class="info-mark" onmouseenter="popup_function(this, 'repair_consumer_id');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <span id="outputt"></span>
      <section class="box ">
        <div class="content-body" style="border-bottom: 0">
          <form id="ip-change-form">
            @csrf
            <div class="" style="display:flex;align-items:center;justify-content:center;column-gap:20px;row-gap:10px;margin-top:20px;flex-wrap:wrap">
              <label for="username">Consumer (ID) <span style="color: red">*</label>
                <input type="text" class="form-control" name="username"  placeholder="Enter Consumer (ID) here..." required style="max-width:300px">
                <button type="submit" name="submit" class="btn btn-primary" id="repair_btn">Change Now </button>
              </div>
            </form>
          </div>
        </section>
      </section>
    </section>
  </div>
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
  @endsection
  @section('ownjs')
  <script type="text/javascript">
    $(document).ready(function() {
      $("#ip-change-form").submit(function() {
        $('#outputt').html("");
        $('#processLayer').modal('show');
        $.ajax({
          type: "POST",
          url: "{{route('users.change_cgn_ip_action')}}",
          data:$("#ip-change-form").serialize(),
          success: function (data) {
            $('#outputt').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
          },
          error: function(jqXHR, text, error){
            $('#outputt').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
          }
        });
        $('#processLayer').modal('hide');
        return false;
      });
    });
  </script>
  @endsection
<!-- Code Finalize -->