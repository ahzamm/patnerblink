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
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2>Management Member<small></small></h2>
          </div>
          <div class="col-lg-4">
            <div class="">
              <img alt="" src="{{asset('img/avatar/admin_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 200px;">
            </div>
            <div class="uprofile-name">
              <h3>
                <a href="#">{{$data->username}}</a>
              </h3>
              <p class="uprofile-title">Management Member</p>
            </div>
            <form action="" method="POST" >
              <button type="button" id="" value="enable" onclick="" class=" mb1 bg-olive btn-lg btn-success" style="border-radius:7px;width: 100%;border:none">This Management Member is Enable</button>
            </form>
          </div>
          <div class="col-lg-8">
            <section class="box ">
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
              <div class="content-body">
                <div class="row" style="margin-top: 20px;">
                  <div class="col-xs-12">
                    <div class="table table-responsive">
                      <table class="table">
                        <tbody>
                          <tr>
                            <th class="td__profileName">Username</th>
                            <td>{{$data->username}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">First Name</th>
                            <td>{{$data->firstname}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Last Name</th>
                            <td>{{$data->lastname}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">CNIC Number</th>
                            <td>{{$data->nic}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Email Address</th>
                            <td>{{$data->email}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Mobile Phone</th>
                            <td>{{$data->mobilephone}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Extension Number</th>
                            <td>{{$data->homephone}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Password</th>
                            <td>{{$data->password_text}}</td>
                          </tr>
                          <tr>
                            <th class="td__profileName">Status</th>
                            <td>{{$data->status}}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </section>
  </section>
</div>
@endsection
<!-- Code Finalize -->