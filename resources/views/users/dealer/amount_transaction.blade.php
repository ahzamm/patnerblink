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
@section('owncss')
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
  .slider:before {
    position: absolute;
    content: "";
    height: 11px !important;
    width: 13px !important;
    left: 3px !important;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }
  .active{
    background-color: #225094 !important;
  }
  .title h2 {
    font-size: 30px;
    line-height: 30px;
    margin: 3px 0 7px;
    font-weight: 700;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT  START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2>Transactions tey</h2>
          </div>
          <div class="col-lg-12">
            <section class="box ">
              <header class="panel_header">
                <div class="actions panel_actions pull-right">
                  <a class="box_toggle fa fa-chevron-down"></a>
                </div>
              </header>
              <div class="content-body collapsed " style="display: none;">   
                <div class="row">
                  <div style="overflow-x: auto;">
                    <table class="table table-responsive table-bordered">
                      <thead class="thead">
                        <tr>
                          <th class="success">TRANSFER</th>
                          <th class="success">RECEIVE</th>
                          <th class="success">BALANCE</th>
                          <th class="success">DISCOUNT</th>
                        </tr>
                      </thead>
                      <tbody class="tbody">
                        <tr>
                          <td colspan="4" > <h4 style="color: #225094 !important">YEARLY </h4></td>
                        </tr>
                        <tr class="info">
                          <td >1000</td>
                          <td>121</td>
                          <td>4</td>
                          <td>54</td>
                        </tr>
                        <tr>
                          <td colspan="4">  <h4 style="color: #225094 !important">MONTHLY <h4 ></td>
                          </tr>
                          <tr class="info">
                            <td>1000</td>
                            <td>121</td>
                            <td>4</td>
                            <td>54</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- Monthly  -->
                  </div>
                </div>
              </section></div>
              <div class="col-lg-12">
                <section class="box ">
                  <header class="panel_header">
                    <h2 class="title pull-left">Billing Report</h2>
                    <div class="actions panel_actions pull-right">
                      <a class="box_toggle fa fa-chevron-down"></a>
                    </div>
                  </header>
                  <div class="content-body">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="form-group">
                          <label  class="form-label">Billing Cycle</label>
                          <select class="form-control" required>
                            <option>Select Billing Cycle</option>
                            <option>1/Feb/2019</option>
                            <option>1/Feb/2019</option>
                            <option>1/Feb/2019</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label  class="form-label">Dealers/Sub Dealers Name</label>
                          <select class="form-control" required >
                            <option>Select Option</option>
                            <option>ali</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <br>
                        <div class="form-group" style="    margin-top: 5px;">
                          <button class="btn btn-flat btn-info">Search</button>
                        </div>
                      </div>
                      <!-- Report Summary -->
                      <div class="col-md-12" ">
                        <div style="overflow-x: auto;">
                          <table class="table table-bordered table-responsive" style="border: 2px #225094 solid">
                            <thead>
                              <tr>
                                <th colspan="5" style="background: #225094;color: white; font-weight: bold;">Report Summary</th>
                              </tr>
                              <tr>
                                <th>Manager Name</th>
                                <th>Reseller Name</th>
                                <th>Dealer Name</th>
                                <th>Sub Dealer Name </th>
                                <th>Net Payable Amount (PKR)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td> Uzair manager</td>
                                <td> Uzair Resller</td>
                                <td> Uzair Dealer</td>
                                <td> uzair sub dealer</td>
                                <td>25,500</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="col-xs-12" style="overflow-x: auto;">
                        <div style="border: 2px #225094 solid;">
                          <div style="">
                            <h3 style="color:white;background:#225094;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Monthly Billing Report</h3>
                          </div>
                          <table id="example-1" class="table table-striped dt-responsive display">
                            <thead>
                              <tr>
                                <th>S.No </th>
                                <th>Customer ID </th>
                                <th>Package </th>
                                <th>Service Activation Date </th>
                                <th>Service Billing Start Date  </th>
                                <th>Service Billing End Date </th>
                                <th>Sub Dealer ID </th>
                                <th>Package Amount (PKR)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>1</td>
                                <td>ALi</td>
                                <td>Lite</td>
                                <td>21/2/2018</td>
                                <td>21/2/2018</td>
                                <td>21/2/2018</td>
                                <td> ali subdealer</td>
                                <td>250000 </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </section></div>
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
        <!-- CONTENT END-->
      </div>
      @endsection
<!-- Code Finalize -->