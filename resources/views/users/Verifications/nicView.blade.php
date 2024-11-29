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
@include('users.layouts.bytesConvert')
@php
$username = '';
$id = '';
$res = '';
$subdealer = '';
$nic = '';
$dealer = '';
$status = '';
$username =  $userDealer['username'];
$id = $userDealer['id'];
$res = $userDealer['resellerid'];
$subdealer = $userDealer['sub_dealer_id'];
$nic = $userDealer['nic'];
$dealer = $userDealer['dealerid'];
$status = $userDealer['status'];
@endphp
@include('users.Verifications.nicVarification')
@section('title') Dashboard @endsection
@section('owncss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<meta name="_token" content="{{ csrf_token() }}">
<style>
  body {
    font-family: Arial;
  }
  * {
    box-sizing: border-box;
  }
  form.example input[type=text] {
    padding: 10px;
    font-size: 17px;
    border: 1px solid grey;
    float: left;
    width: 80%;
    height: 40px;
    background: #f1f1f1;
  }
  form.example button {
    float: left;
    width: 20%;
    height: 40px;
    padding: 10px;
    background: #4e72a7;
    color: white;
    font-size: 17px;
    border: 1px solid grey;
    border-left: none;
    cursor: pointer;
  }
  form.example button:hover {
    background: #0b7dda;
  }
  form.example::after {
    content: "";
    clear: both;
    display: table;
  }
  #Sno select{
    display: none;
  }
  #Status select{
    display: none;
  }
  #Full-Name select{
    display: none;
  }
  #Sub-Dealer-ID select{
    display: none;
  }
  #Expiry select{
    display: none;
  }
  #Actions select{
    display: none;
  }
  #address select{
    display: none;
  }
  #Upload select{
    display: none;
  }
  .blink_effect{
    animation: 1s blinkMe alternate infinite;
  }
  @keyframes blinkMe {
    0%{
      opacity: 10%
      }100%{
        opacity: 100%
      }
    }
  </style>
  @endsection
  @section('content')
  <div class="page-container row-fluid container-fluid">
    <!-- Contant START -->
    <section id="main-content" class=" ">
      <section class="wrapper main-wrapper row" style=''>
        <div class="">
          <div class="col-lg-12">
            <div class="header_view">
              <h2>VERIFICATION <small>(CNIC Number)</small></h2>
            </div>
            <section class="box" style="padding: 20px;max-width: 900px;margin: auto;">
              <header class="panel_header">
                <center>
                  <p style="font-size: 16px;margin-top:20px;padding:5px"><b><span style="color: red;">NOTE:</span> Registered correct (CNIC) information to verify this valuable Consumer, in case of any wrong and mismatch this information otherwise this consumer (ID) will be permanently <span style="color:red">BLOCKED</span>.</b> <br><br><span style="color:green;font-weight:700">Making Government TAX payment can be simplified by ensuring that consumers provide their correct Computerized National Identity Card (CNIC) information.</span><br><br> <b><span style="color: red;">اطلاع: </span> کنزیومر کو تصدیق کرنے کے لئے درست شناختی کارڈ اپ لوڈ کریں- بصورت دیگر کنزیومر کی تصدیق نہیں ہوگی اور آئی ڈی ہمیشہ کے لئے 
                    <span style="color:red">بند </span>
                  کردی جائے گی</b><br><b>
                    <span style="color:green"> آپ کی صحیح معلومات فرہم کرنے سے کنزیومر کے ٹیکس کی ادائیگی کو آسان بنایا جا سکتا ہے</span></b></p>
                  </center>
                </header>
                <div class="content-body">
                  <div class="">
                    <div style="display: flex;align-items: center;justify-content: center;max-width: 600px;margin: auto;">
                      <label for="" style="white-space:nowrap;color:green; margin-right:10px;">Username: </label>
                      <input type="text" name="" value="<?php echo $username ?>" class="form-control" readonly="">
                      <button class="btn btn-primary " style="font-size:15px;margin-left:5px" onclick="showModal(<?php echo $id?>)" data-toggle="modal"> <span class="blink_effect">Process Now</span> </button>
                    </div>
                  </div>
                </div>
              </section>
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
      function showModal(id){
        $.ajax({
          type : 'get',
          url : "{{route('users.billing.nicData')}}",
          data:'id='+id,
          success:function(res){
            $('#test').html(res);
            $('#nicVarification').modal('show');
          }
        });
      }
    </script>
    @endsection
<!-- Code Finalize -->