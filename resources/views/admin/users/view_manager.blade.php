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
    <section class="wrapper main-wrapper">
      <div class="">
        <div class="">
          <a href="{{('#myModal-3')}}" data-toggle="modal">  <button class="btn btn-default" style="border: 1px solid black"><i class="fas fa-user-astronaut"></i> Add Manager </button></a>
          <div class="header_view">
            <h2>Managers
              <span class="info-mark" onmouseenter="popup_function(this, 'manager');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            {{session('success')}}
          </div>
          @endif
          <div>
            <table id="example-1" class="table table-striped dt-responsive w-100 display">
              <thead>
                <tr>
                  <th>Serial#</th>
                  <th>Username</th>
                  <th>Full Name</th>
                  <th>Number of Resellers </th>
                  <th>Number of Contractors</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php
                $count = 1;
                @endphp
                @foreach($managerCollection as $data)
                <tr>
                  <td>{{$count++}}</td>
                  <td class="td__profileName">{{$data->username}}</td>
                  <td>{{$data->firstname}}</td>
                  <td>{{App\model\Users\UserInfo::where(['status' => 'reseller','manager_id' => $data->manager_id])->count()}}</td>
                  <td>{{App\model\Users\UserInfo::where(['status' => 'dealer','manager_id' => $data->manager_id])->count()}}</td>
                  <td>
                    <center>
                      <a href="{{route('admin.user.show',['status' => 'manager','id' => $data->id])}}">
                        <button class="btn btn-primary btn-xs">
                          <i class="fa fa-eye"> </i> View
                        </button>
                      </a>
                      <a href="{{route('admin.user.edit',['status' => 'manager','id' => $data->id])}}">
                        <button class="btn btn-info mb1 bg-olive btn-xs">
                          <i class="fa fa-edit"> </i> Edit
                        </button>
                      </a>
                      <a href="{{route('admin.my.sheetdownload',['managerid' => $data->manager_id,'resellerid' => 'noreseller'])}}">
                        <button class="btn btn-success btn-xs" style="margin-top:4px;margin-bottom:4px"><i class="fa fa-download"></i> Download (CSV) </button>
                        <a href="{{route('admin.alluser.sheetdownload',['managerid' => $data->manager_id,'resellerid' => 'noreseller'])}}">
                          <button class="btn btn-success btn-xs"><i class="fa fa-download"></i> Download  Consumers (CSV) </button>
                        </a>  
                      </center>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </section>
  </div>
  @include('admin.users.model_manager') 
  @endsection
  @section('ownjs')
  <script>
    $(document).ready(function(){
      setTimeout(function(){ 
        $('.alert').fadeOut(); }, 3000);
    });
  </script>
  @endsection
<!-- Code Finalize -->