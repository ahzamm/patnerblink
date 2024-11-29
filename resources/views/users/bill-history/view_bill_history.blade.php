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
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2>Bill History
              <span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div class="table-responsive">
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
            <table id="example-1" class="table table-striped  display">
              <thead>
                <tr>
                  <th class="th-color">Serial#</th>
                  <th class="th-color">Billing Month</th>
                  <th class="th-color">Billing Date</th>
                  <th class="th-color">Amount </th>
                  <th class="th-color">Action</th>              
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>July, 2023</td>
                  <td>20 July, 2023</td>
                  <td>3,000</td>
                  <td>
                    <center><a href="#" >
                      <button class="btn btn-primary btn-xs">
                        <i class="fa fa-eye"> </i> View Bill</button></a>
                      </center>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </section>
  </div>
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