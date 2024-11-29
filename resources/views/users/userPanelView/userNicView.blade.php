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
@include('users.userPanelView.ExpireUserModal')
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
@include('users.userPanelView.nicVarification')
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
            <h2>Un-Verified Customers</h2>
          </div>
          <section class="box">
            <header class="panel_header">
              <h2 class="title">Verify User</h2>
              <center>
                <p style="font-size: 16px;"><b><span style="color: red;">NOTE:</span> Upload correct CNIC to verify user , in case of any mismatch user will not be verified and id will be blocked permanently.</b> <br> <b><span style="color: red;">اطلاع: </span> یوزر کو تصدیق کرنے کے لیے درست شناختی کارڈ اپ لوڈ کریں . بصورت دیگر یوزر کی تصدیق نہیں ہوگی اور آئی ڈی ہمیشہ کے لیے بند کردی جاۓگی </b></p>
              </center>
            </header>
            <div class="content-body">
              <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-6">
                  <input type="text" name="" value="<?php echo $username ?>" class="form-control" readonly="">
                </div>
                @php
                $nicFront = '';
                $nicBack = '';
                $nic = '';
                $ntn = '';
                $passport = '';
                $overseas = '';
                $nicData = '';
                $userstatus = '';
                $data = App\model\Users\UserVerification::where('username',$username)->first();
                $nicFront = $data['nic_front'];
                $nicBack = $data['nic_back'];
                $nic = $data['cnic'];
                $ntn = $data['ntn'];
                $overseas = $data['overseas'];
                $passport = $data['intern_passport'];
                if(!empty($nic)){
                $nicData = $nic;
              }else if(!empty($overseas)){
              $nicData = $overseas;
            }else if(!empty($passport)){
            $nicData = $passport;
          }else if(!empty($ntn)){
          $nicData = $ntn;
        }
        @endphp
        @if(!empty($nicData))
        <div class="col-md-2">
          <p class="" style="font-size:16px; color: #225094">Verified <span class="fa fa-check"></span></p>
        </div>
        @else
        <div class="col-md-2">
          <button class="btn btn-primary" style="font-size:16px;" onclick="showMoal(<?php echo $id?>)" data-toggle="modal"> Cnic Verify </button>
        </div>
        @endif            
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
  $(document).ready(function(){
    setTimeout(function(){
      $('.alert').fadeOut(); }, 3000);
  });
</script>
<script>
  $(document).ready(function(){
    var vals = $('#example1_length select').val();
    if (vals == 100){
      alert('Select below then 100.');
    }
  });
</script>
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
<script type="text/javascript">
  function charge_alert(data){
    $('#chargeConfirm').val(data);
    $('#confromMsg').modal('show');
  }
  function chargeit(data) {
    var value = data.split("^");
    var username = value[0];
    var profile = value[1];
    $.ajax({
      type: "POST",
      url: "{{route('users.charge.single.post')}}",
      data:'username='+username+'&profileGroupname='+profile,
      success: function(data){
// For Get Return Data
location.reload();
}
});
  }
</script>
<script>
  function showMoal(id){
    $.ajax({
      type : 'get',
      url : "{{route('users.userPanel.userNicData')}}",
      data:{id:id},
      success:function(res){
        if(res == 'false'){
          alert('you trying to add unknown user Please add Correct user..');
        }else{
          $('#test').html(res);
          $('#nicVarification').modal('show');
        }
//SMS Verification Hidden Fields
}
});
  }
</script>
<script>
  $(document).ready(function(){
    $.ajax({
      type: "POST",
      url: "{{route('users.userPanel.checkExpire')}}",
success: function(data){
  if(data == "expire"){
    $("#myModal").modal('show');
  }
}
});
  });
</script>
@endsection
<!-- Code Finalize -->