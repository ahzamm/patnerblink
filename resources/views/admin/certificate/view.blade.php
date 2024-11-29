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
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="alert alert-danger print-error-msg" style="display:none" >
          <ul></ul>
        </div>
        <div class="alert alert-success success-msg" style="display:none" >
          <ul></ul>
        </div>
        <div class="col-lg-12" >
          <div class="header_view">
            <h2>Certificate</h2>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <section class="box ">
          <div class="content-body" style="padding-top:20px">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Select Username</label>
                  <select name="" id="" class="form-control">
                    <option value="1">Asnet</option>
                    <option value="1">Logonhome</option>
                    <option value="1">Paknet</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Select Service</label>
                  <select name="" id="" class="form-control">
                    <option value="1">Standard</option>
                    <option value="1">Normal</option>
                    <option value="1">Premium</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Tax Status</label>
                  <select name="" id="" class="form-control">
                    <option value="1">Filer</option>
                    <option value="1">Non Filer</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Issue Date</label>
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Expiry Date</label>
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group pull-right" style="margin-bottom:0">
                  <a href="{{route('admin.certificate.show')}}" class="btn btn-primary" target="_blank">Generate</a>
                  <!-- <input type="submit" value="Generate" class="btn btn-primary"> -->
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </section>
  </section>
</div>
@endsection
<!-- Code Finalize -->