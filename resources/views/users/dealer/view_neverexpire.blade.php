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
  #Sno select{
    display: none;
  }
  #username select{
    display: none;
  }
  #resellerid select{
    display: none;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="col-lg-12">
        <div class="header_view">
          <h2>Never Expired Consumers</h2>
        </div>
        <div class="table-responsive">
          <table id="example1" class="table table-striped display">
            <thead class="text-primary" style="background:#225094c7;">
              <tr>
                <th class="th-color">Sno</th>
                <th class="th-color">Username</th>
                <th class="th-color">Dealer Id</th>
                <th class="th-color">Reseller Id</th>
                <th class="th-color">Subdealer Id</th>
                <th class="th-color">Trader Id</th>
                <th class="th-color">Expire Set Date</th>
                <th class="th-color">Expire Till Date</th>
              </tr>
              <tr style="background:white !important;">
                <td id="Sno"></td>
                <td id="username"></td>
                <td id="dealerid"></td>
                <td id="resellerid"></td>
                <td id="subdealerid"></td>
                <td id="traderid"></td>
                <td id="setDate"></td>
                <td id="tillDate"></td>
              </tr>
            </thead>
            <tbody>
              @forelse ($allNeverExpire as $key => $item)
              <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->username}}</td>
                <td>{{$item->dealerid}}</td>
                <td>{{$item->resellerid}}</td>
                <td>{{$item->sub_dealer_id == '' ? 'My User' : $item->sub_dealer_id}}</td>
                <td>{{$item->trader_id}}</td>
                <td>{{$item->last_update}}</td>
                <td>{{$item->todate}}</td>
              </tr>
              @empty
              @endforelse
            </tbody>
          </table>
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
@endsection
<!-- Code Finalize -->