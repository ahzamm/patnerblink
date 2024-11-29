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
@include('users.Verifications.smsVarification')
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
            <h2>Mobile Un-Verified Customers</h2>
          </div>
          <section class="box">
            <header class="panel_header">
              <h2 class="title">Verify User Mobile Number</h2>
              <center>
                <p style="font-size: 16px;"><b><span style="color: red;">NOTE:</span> Enter correct Mobile Number to verify user , in case of any mismatch user will not be verified and you can not change user profile.</b> <br> <b><span style="color: red;"></span>اطلاع: یوزر کو تصدیق کرنے کے لیے درست موبائل نمبر درج کریں . بصورت دیگر یوزر کی تصدیق نہیں ہوگی اور ہ آپ اپنے یوزر کی پروفائل تبدیل نہیں کر سکیں  گے </b></p>
              </center>
            </header>
            <div class="content-body">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <button class="btn btn-primary" style="font-size:16px;" onclick="showMobileModel(<?php echo $id ?>)" data-toggle="modal"> Click here to Verify Mobile </button>
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
      url : "{{route('users.userPanelView.userMobileVerify')}}",
      data:'id='+$id,
      success:function(res){
//SMS Verification Hidden Fields
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
        if(res != '')
          $("#code").html("<span style='font-family: serif; font-size: 22px; color:black;font-weight: bold;'>Please Enter This Verification Code <span style='font-family: serif; font-size: 22px; color:red;font-weight: bold;'>("+ res.verificationCode +")</span></span>");
      }
    });
  }
</script>
@endsection
<!-- Code Finalize -->