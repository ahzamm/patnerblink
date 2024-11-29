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
@extends('users.layouts.app')
@include('users.AccessManagement.edit_users')
@include('users.AccessManagement.add_user_support')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
  #ip select,
  #sno select,
  #username select,
  #nic select,
  #res select,
  #app select,
  #mob select
  {
    display: none;
  }
  .lcs_switch{
    border-radius:4px
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="">
          <a href="#add_user_support" data-toggle="modal" >
            <button class="btn btn-primary "><i class="fa fa-headset"></i> Add Helpdesk Agent</button>
          </a>
          <div class="header_view">
            <h2>Helpdesk Agents
              <span class="info-mark" onmouseenter="popup_function(this, 'helpdesk_agents');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
              <small></small></h2>
            </div>
            <div class="">
              <section class="box ">
                <header class="panel_header">
                  <center><h3></h3> </center>
                </header>
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button> 
                  <strong>{{ $message }}</strong>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button> 
                  <strong>{{ $message }}</strong>
                </div>
                @endif
                <div class="">
                  <div class="alert alert-danger print-error-msg" data-dismiss="alert" style="display:none">
                    <ul></ul>
                  </div>
                  <div class="alert alert-success success-msg" data-dismiss="alert" style="display:none">
                    <ul></ul>
                  </div>
                  <div class="content-body">
                    <div class="row" style="margin-top: 20px;">
                      <div class="">
                        <div class="">
                          <table id="example-1" class="table table-striped dt-responsive display" style="width:100%">
                            <thead>
                              <tr>
                                <th>Serial#</th>
                                <th>Username</th>
                                <th class="all">Full Name</th>
                                <th class="desktop tablet-l">Status</th>
                                <th class="desktop">Email Address</th>
                                <th class="desktop">Mobile Number</th>
                                <th class="desktop">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              @php
                              $num=1;
                              @endphp
                              @foreach($allData as $data)
                              <tr>
                                <td>{{$num++}}</td>
                                <td class="td__profileName">{{$data->username}}</td>
                                <td>{{$data->firstname.' '.$data->lastname}}</td>
                                <td>{{$data->status}}</td>
                                <td>{{$data->email}}</td>
                                <td>{{$data->mobilephone}}</td>
                                <td>
                                  <center>
                                    <a href="{{url('users/view/'.$data->id)}}" data-toggle="modal" class="btn btn-primary btn-xs"><i class="las la-eye"> </i> View</a>
                                    <button onclick="showEditModal({{$data->id}})" data-toggle="modal" class="btn btn-info mb1 btn-xs">
                                      <i class="las la-user-edit"> </i> Edit
                                    </button>
                                    <!-- Dropdown -->
                                    <div class="dropdown action-dropdown">
                                      <button class="btn dropdown-toggle action-dropdown_toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></button>
                                      <div class="dropdown-menu action-dropdown_menu">
                                        <ul>
                                          <!-- Dropdown List -->
                                          <li class="dropdown-item">
                                            <a href="{{url('users/view/'.$data->id)}}" data-toggle="modal"><i class="las la-eye"> </i> View</a>
                                          </li>
                                          <li class="dropdown-item">
                                            <a onclick="showEditModal({{$data->id}})" data-toggle="modal"><i class="las la-user-edit"></i> Edit</a>
                                          </li> 
                                          <hr style="margin-top:0">
                                          <li class="dropdown-item">
                                            <a href="{{route('useraccess.show',$data->id)}}"><i class="las la-lock"></i> Access Management</a>
                                          </li>
                                          <li class="dropdown-item">
                                            <button class="agent-account" data-id="{{$data->id}}" data-username="{{$data->username}}" data-status="{{$data->account_disabled}}">
                                              @if($data->account_disabled==1)
                                              <i class="las la-user-check"> </i> Active 
                                              @else 
                                              <i class="las la-user-alt-slash"> </i> Deactive 
                                              @endif
                                            </button>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                  </center>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  {{-- <div class="chart-container " style="display: none;">
                    <div class="" style="height:200px" id="platform_type_dates"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
                    <div class="" style="height:200px" id="user_type"></div>
                    <div class="" style="height:200px" id="browser_type"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
                    <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
                  </div> --}}
                </section>
              </section>
            </div>
            {{-- @include('users.reseller.model_dealer')--}}
            @endsection
            @section('ownjs')
            <script type="text/javascript">
              function showpass(value){
                var x = document.getElementById(value);
                if(x.type === 'password'){
                  x.type = 'text';
                }else{
                  x.type = 'password';
                }
                $('.'+value).toggleClass('fa-eye fa-eye-slash');
              }
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
              function showEditModal($id){
                $.ajax({
                  type : 'get',
                  url : "{{route('users.manage.edit')}}",
                  data:'id='+$id,
                  success:function(res){
                    $('#editModal').html(res);
                    $('#edit_users').modal('show');
                    $('.lcs_check').lc_switch();
                  }
                });
              }
            </script>
            <script>
              function myFunction($id) {
                var r = confirm("Do you want to Delete this user Account");
                if (r == true) {
                  $.ajax({
                    type : 'get',
                    url : "{{route('users.manage.delete')}}",
                    data:'id='+$id,
                    success:function(res){
                      location.reload();
                    }
                  });
                }
              }
            </script>
            @endsection
<!-- Code Finalize -->