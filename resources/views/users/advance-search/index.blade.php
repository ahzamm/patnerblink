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
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content" >
    <img src="/images/search-logo-bg.png" alt="" style="position: absolute;left: 57%;bottom: 100px;transform: translateX(-50%);user-select: none;z-index: 0;opacity: .1;width:20%;">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="alert alert-danger print-error-msg" style="display:none" >
          <ul></ul>
        </div>
        <div class="alert alert-success success-msg" style="display:none" >
          <ul></ul>
        </div>
        <div style="margin-bottom: 20px;" >
          <div class="header_view">
            <h2>Advance Search</h2>
          </div>
        </div> 
      </div>
      <section class="" style="max-width: 800px;margin:auto">
        <div class="content-body" style="padding-top:20px;background-color:transparent">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <div style="position:relative">
                  <input type="search" class="form-control" placeholder="Search by Consumer (ID), Consumer Name, Mobile number, CNIC" style="padding-left: 15px;height:50px;font-size: 20px; background-color:transparent;border:none; border-bottom:1px solid #000">
                  <i class="fa fa-search" style="position: absolute;top: 14px;right: 16px;font-size: 20px;"></i>
                  <a class="btn" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" style="position: absolute;
                  right: -170px;
                  top: 15px;"> Advance Search  <i class="fa fa-chevron-down"></i></a>
                </div>
              </div>
              <div class="collapse" id="collapseExample" style="margin-top:20px;background: #bbbbbb82;padding: 15px;backdrop-filter: blur(6px);">
                <div class="card card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Package Name</label>
                        <input type="text" class="form-control" placeholder="" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Login Date</label>
                        <input type="date" class="form-control" placeholder="" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Consumer Creation Date</label>
                        <input type="date" class="form-control" placeholder="" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Charge On</label>
                        <input type="date" class="form-control" placeholder="" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Expire On</label>
                        <input type="date" class="form-control" placeholder="" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">MAC Address</label>
                        <input type="text" class="form-control" placeholder="AB:CD:EF:GH" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">IP Address</label>
                        <input type="text" class="form-control" placeholder="192.168.100.1" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Consumer Status</label>
                        <select name="" id="" class="form-control">
                          <option value="">Active</option>
                          <option value="">Expired</option>
                          <option value="">Terminated</option>
                          <option value="">Offline</option>
                          <option value="">Online</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Total Download</label>
                        <input type="text" class="form-control" placeholder="100 Mb" style="">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="">Total Upload</label>
                        <input type="text" class="form-control" placeholder="100 Mb" style="">
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="">Upcoming Expiry (in days)</label>
                        <select name="" id="" class="form-control">
                          @for($i=1; $i <= 30; $i++)
                          <option value="{{$i}}">{{$i}}</option>
                          @endfor
                        </select>                        
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" style="width:100%;text-align:center">CNIC Verified</label>
                        <div style="display:flex;justify-content:space-around;align-items:center">
                          <div>
                            <input type="radio" class="" name="cnic_check" placeholder="100 Mb" style="width:34px; height:24px">
                            <label for="">Yes</label>
                          </div>
                          <div>
                            <input type="radio" class="" name="cnic_check" placeholder="100 Mb" style="width:34px; height:24px">
                            <label for="">No</label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="" style="width:100%;text-align:center">Mobile Verified</label>
                        <div style="display:flex;justify-content:space-around;align-items:center">
                          <div>
                            <input type="radio" class="" name="mobile_check" placeholder="100 Mb" style="width:34px; height:24px">
                            <label for="">Yes</label>
                          </div>
                          <div>
                            <input type="radio" class="" name="mobile_check" placeholder="100 Mb" style="width:34px; height:24px">
                            <label for="">No</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group pull-right" style="margin-bottom:0;margin-top:10px;">
                  <a href="" class="btn btn-primary" target="_blank">Search</a>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
    </section>
  </div>
  @endsection
<!-- Code Finalize -->