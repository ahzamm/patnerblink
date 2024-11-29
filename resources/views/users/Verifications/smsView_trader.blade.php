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
@php
$username = '';
$id = '';
$mobile = '';
$username =  $userDealer['username'];
$id = $userDealer['id'];
$mobile = $userDealer['mobilephone'];
@endphp
@include('users.Verifications.smsVarification_trader')
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
</style>
<style type="text/css">
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
    #mobileError{
      display:none;
    }
  </style>
  @endsection
  @section('content')
  <div class="page-container row-fluid container-fluid">
    <!-- CONTANT START -->
    <section id="main-content" class=" ">
      <section class="wrapper main-wrapper row" style=''>
        <div class="">
          <div class="col-lg-12">
            <div class="header_view">
              <h2>Un-Verified Trader <small>(Mobile Number)</small></h2>
            </div>
            <section class="box" style="padding:20px; max-width: 900px;margin:auto">
              <header class="panel_header">
                <center>
                  <p style="font-size: 16px;margin-top: 20px"><b style="margin-bottom:10px"><span style="color: red;">NOTE:</span> Enter correct Mobile Number to verify Trader , in case of any mismatch Trader will not be able to login account.</b> <br><br> <b><span style="color: red;">اطلاع:</span>ٹریڈر کو تصدیق کرنے کے لیے درست موبائل نمبر درج کریں- بصورت دیگر ٹریڈر کی تصدیق نہیں ہوگی اور ٹریڈر اپنے اکائونٹ کو لاگ ان نہیں کر پائیں گے-</b></p>
                </center>
              </header>
              <div class="content-body" style="display:flex; justify-content:center;align-item:center;">
                <div>
                  <button class="btn btn-primary" style="font-size:16px;" onclick="showMobileModel(<?php echo $id ?>)" data-toggle="modal"><span class="blink_effect"> Click here to Process <span style="color:yellow">(Trader)</span> Mobile Number </span></button>
                </div>
              </div>
              <div style="margin-top:20px">
                <p class="text-center" style="padding-bottom:0px">
                  <span class="text-center" style="font-size:18px;background-color: #f44336;color: #fff" id="mobileError">Please verify Computerized National Identity Card (CNIC) first <br/>
                    تصدیق کریں Computerized National Identity Card (CNIC) پہلے ٹریڈر کا  
                  </span>
                </p>
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
  <script type="text/javascript">
    $(document).ready(function() {
      var table = $('#example1').DataTable();
      $("#example1 thead td").each( function ( i ) {
        var select = $('<select class="form-control"><option value="">Show All</option></select>')
        .appendTo( $(this).empty() )
        .on( 'change', function () {
          table.column( i )
          .search( $(this).val() )
          .draw();
        } );
        table.column( i ).data().unique().sort().each( function ( d, j ) {
          select.append( '<option value="'+d+'">'+d+'</option>' )
        } );
      } );
    } );
  </script>
  <script>
    function showMobileModel($id){
      $.ajax({
        type : 'get',
        url : "{{route('users.billing.mobileData')}}",
        data:'id='+$id,
        success:function(res){
// SMS Verification Hidden Fields
if(res.includes("Alert")){
  $('#mobileError').css('display', 'block');
  $('#mobileError').css('padding', '5px 8px');
  setTimeout(function() {
    $('#mobileError').css('display', 'none');
    $('#mobileError').css('padding', '0px');
  }, 10000)
}else{
  $('#test').html(res);
  var uname = $('#uName').val();
  $('.user').val(uname);
  if(res == 'false'){
    $('#verifynumber').hide();
    $('#codeverify').show();
    $('#selectBox').hide();
    $('#space').hide();
    $('#smsVarification').modal('show');
  }else{
    $('#smsVarification').modal('show');
  }
}
},
error: function(jqXHR, text, error){
  console.log(error);
}
});
    }
  </script>
  <script>
    $(document).ready(function(){
      fetchcode();
    });
    function fetchcode(){
      var username = '<?php echo $username;?>';
      $.ajax({
        url: "{{route('users.billing.validCode')}}",
        type: "POST",
        data: 'username='+username,
        success: function(res){
          if(res.verificationCode != '' && res.verificationCode != null){
            $(".code").html("<span style='font-family: serif; font-size: 18px; color:black;font-weight: bold;'>Enter This Company Verification Code <span class='blink_effect' style='font-family: serif; font-size: 22px; color:red;font-weight: bold;'>("+ res.verificationCode +")</span></span>");
          }
        }
      });
    }
  </script>
  @endsection
<!-- Code Finalize -->