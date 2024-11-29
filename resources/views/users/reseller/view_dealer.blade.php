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
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <?php if(Auth::user()->status == 'reseller'){?>
            <a href="{{('#add_dealer')}}" data-toggle="modal"><button class="btn btn-primary mb1 bg-olive btn-md"><i class="fas fa-user-friends"></i> Add Contractor </button></a>
          <?php } ?>
          <div class="header_view">
            <h2>Contractors 
              <span class="info-mark" onmouseenter="popup_function(this, 'contractors');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible">
              {{session('success')}}
            </div>
            @endif
            @if(count($errors) > 0)
            <div class="alert-danger">
              <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <table id="example-1" class="table table-striped dt-responsive display w-100">
              <thead>
                <tr>
                  <th>Serial#</th>
                  <th>Username</th>
                  <th>Full Name</th>
                  <th style="white-space:nowrap">Mobile Number </th>
                  <th>Assigned Area </th>
                  <?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'inhouse'){?>
                    <th>Reseller (ID) </th>
                  <?php } ?>
                  <th>Contractor (ID) </th>
                  <th>Number of Traders</th>
                  <th>Number of Consumers</th>
                  <th>Actions</th>
                </tr>
              </thead>
              @php
              $count=1;
              @endphp
              <tbody>
                @foreach($dealerCollection as $data)
                @php
                $data1='';
                $payment_type='black';
                $data1 = App\model\Users\DealerProfileRate::where('dealerid',$data->dealerid)->select('payment_type','sst')->first();
                if(empty($data1)){
                $payment_type='';
                $sst='';
              }else{
              $payment_type=$data1['payment_type'];
              $sst=$data1['sst'];
            }
            @endphp
            <tr style="color: {{$payment_type == 'credit'?('green'):'black'}}">
              <td>{{$count++}} <i class="{{$sst == 0 && $sst != '' ? 'fa fa-crop' : ''}} text-danger"></i></td>
              <td class="td__profileName">{{$data->username}}</td>
              <td>{{$data->firstname}} {{$data->lastname}}</td>
              <td style="white-space:nowrap">
                @if(!empty($data->mobilephone))
                <a href="tel:{{$data->mobilephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$data->mobilephone}}</a>
                @endif
              </td>
              <td>{{$data->area}}</td>
              <?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'inhouse'){?>
                <td>{{$data->resellerid}}</td>
              <?php } ?>
              <td>{{$data->dealerid}}</td>
              <td>{{App\model\Users\UserInfo::where(['status' => 'subdealer','resellerid' => $data->resellerid,'dealerid' => $data->dealerid])->count()}}</td>
              <td>{{ DB::table('user_info')
                ->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
                //  ->where('user_status_info.card_expire_on', '>', today())
                ->where('user_status_info.expire_datetime', '>', DATE('Y-m-d H:i:s'))
                ->where(['status' => 'user','resellerid' => $data->resellerid,'dealerid' => $data->dealerid])->count() }}</td>
                <td>
                  <div style="display:flex;align-items:center;justify-content:center;column-gap:10px;">
                    <a href="{{route('users.user.show',['status' => 'dealer','id' => $data->id])}}" >
                      <button class="btn btn-primary btn-xs" style="margin-bottom:4px">
                        <i class="fa fa-eye"> </i> View </button></a>
                        <?php if(Auth::user()->status == 'reseller'){ ?>
                          <a href="{{route('users.user.edit',['status' => 'dealer','id' => $data->id])}}"><button class="btn btn-info mb1 bg-olive btn-xs" style="margin-bottom:4px">
                            <i class="fa fa-edit"> </i> Edit
                          </button></a> 
                        <?php } ?>
                        <!-- Dropdown -->
                        <?php if(Auth::user()->status != 'inhouse'){ ?>
                          <div class="dropdown action-dropdown">
                            <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                            <div class="dropdown-menu action-dropdown_menu">
                              <ul>
                                <li class="dropdown-item"><a href="{{route('users.user.show',['status' => 'dealer','id' => $data->id])}}" > <i class="la la-eye"> </i> View</a></li>
                                <?php if(Auth::user()->status == 'reseller'){ ?>
                                  <li class="dropdown-item">
                                    <a href="{{route('users.user.edit',['status' => 'dealer','id' => $data->id])}}"><i class="la la-edit"> </i> Edit</a> 
                                  </li>
                                <?php } ?>
                                <hr style="margin-top:0">
                                <li class="dropdown-item">
                                  <a href="{{route('users.all.sheetdownload',['resellerid' => $data->resellerid, 'dealerid' => $data->dealerid])}}" > <i class="la la-download"></i> Download (CSV)</a>
                                </li>
                                <li class="dropdown-item">
                                  <a href="{{route('useraccess.show',$data->id)}}" title="Allow Access"><i class="la la-lock"></i> Access Management</a>
                                </li>
                                <li class="dropdown-item">
                                  @php
                                  @$freezeCheck = App\model\Users\FreezeAccount::where('username',$data->username)->first();
                                  @endphp
                                  @if(@$freezeCheck['freeze'] == 'yes')
                                  <button class="freeze-contractor" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{$freezeCheck['freeze']}}" @if($freezeCheck->status == 'dealer') hidden @endif > <i class="la la-check"> </i> Unfreeze </button>
                                  @else
                                  <button class="freeze-contractor" data-id ="{{$data->id}}" data-username ="{{$data->username}}" data-check ="{{isset($freezeCheck) ? $freezeCheck['freeze'] :'no'}}" style="margin-bottom:4px"><i class="la la-check"> </i> Freeze </button>
                                  @endif
                                </li>
                                <li>
                                  <button class="dropdown-item agent-account" data-id="{{$data->id}}" data-username="{{$data->username}}" data-status="{{$data->account_disabled}}">
                                    @if($data->account_disabled==1)
                                    <i class="las la-user-check"> </i> Active 
                                    @else 
                                    <i class="las la-user-alt-slash"> </i> Deactive 
                                  @endif</button> 
                                </li>
                              </ul>
                            </div>
                          </div>
                        <?php } ?>
                      </div>
                    </td>
                  </tr>
                  @endforeach
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
    <!-- END CONTENT -->
  </div>
  @endsection
  @section('ownjs')
  <script>
    function checkDealer(dealerid){
      var val=$('#dealerid').val();
      $('#username').val(val);
      if(val.length > 0){
        $.ajax({
          type: "POST",
          url: "{{route('users.checkunique.ajax.post')}}",
          data:'dealerid='+dealerid,
          success: function(data){
// For Get Return Data
$('#dealercheck').html(data);
}
});
      }
      else{
        $('#dealercheck').html('');
      }
    }
    function showpass() {
      var x = document.getElementById("pass");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      $('.toggleClass').toggleClass('fa-eye fa-eye-slash');
    }
    function showpass2() {
      var x = document.getElementById("pass_c");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      $('.toggleClass2').toggleClass('fa-eye fa-eye-slash');
    }
    $(document).ready(function(){
      setTimeout(function(){ 
        $('.alert').fadeOut(); }, 3000);
    });
  </script>
  <script>
    $(document).on('click','.freeze-contractor',function(){
      status =0;
      id = $(this).attr('data-id');
      username = $(this).attr('data-username');
      isChecked = $(this).attr('data-check');
      var status = 'Do you want to Freeze the Account Please Click on Freeze Now Button.';
      if(isChecked == 'yes')
      {
        var status = "Do you want to Active the Account Please Click on Active Now Button."
      }
      var user_status ="dealer";
      $('#status').text(status);
      $.ajax({
        type: 'GET',
        url : "{{route('users.freeze.dealershow')}}",
        data:{
          username:username,id:id,isChecked:isChecked,user_status:user_status,
        },
        dataType:'json',
        beforeSend:function(){
        },
        success:function(res){
          if(res == 'parentFreezed'){
            alert("You can't unFreeze your subdealer");
          }else if(res.freeze == 'yes'){
            $('#user-status').text(res.status);
            $('#subIdActive').text(res.username);
            $('#subIdTextActive').text(res.subdealerid);
            $('#freezeDate').text(res.freezeDate);
            $("#usernameActive").val(res.username);
            $('#activeModel').show();
            $('#freezeModal').hide();
            $('#dealerfreeze').modal('show');
          }else if(res.freeze == 'no'){
            $('#user-status').text(res.status);
            $('#subId').text(res.username);
            $('#subIdText').text(res.subdealerid);
            $("#freezusername").val(res.username);
            $('#activeModel').hide();
            $('#freezeModal').show();
            $('#dealerfreeze').modal('show');
          }else{
            $('#subId').text(res.username);
            $('#subIdText').text(res.sub_dealer_id);
            $('#user-status').text(res.status);
            $("#freezusername").val(res.username);
            $('#activeModel').hide();
            $('#freezeModal').show();
            $('#dealerfreeze').modal('show');
          }
        }
      })
    })
  </script>
  @endsection
  @include('users.dealer.DealerFreeze')
  @include('users.reseller.model_dealer')
<!-- Code Finalize -->