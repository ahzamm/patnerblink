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
  #ips select, #Balance select, #action select{
    display: none;
  }
  #Rates select{
    display: none;
  }
  #Amount select{
    display: none;
  }
  #num select{
    display: none;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Static IPs Bill
          <span class="info-mark" onmouseenter="popup_function(this, 'static_ips_bill');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span> 
        </h2>
      </div>
      <section class="box">
        <header class="panel_header">
          <h2 class="title pull-left"></h2>
        </header>
        <div class="content-body">
          <table id="example1" class="table table-bordered display dt-responsive w-100">
            <thead>
              <tr>
                <th style="width:25px">Receipt#</th>
                <th>Username</th>
                <th>Number of IPs</th>
                <th>Rates (PKR)</th>
                <th>Billing Date & Time</th>
                <th>Amount (PKR)</th>
                <th>Balance Amount (PKR)</th>
                <th>Status</th>
              </tr>
              <tr style="background:white !important;" id="filter_row">
                <td id="num" style="width:25px">Receipt#</td>
                <td id="Username"></td>
                <td id="ips"></td>
                <td id="Rates"></td>
                <td id="Bill"></td>
                <td id="Amount"></td>
                <td id="Balance"></td>
                <td id="Status"></td>
              </tr>
            </thead>
            <tbody>
              @php $sno = 100000; @endphp
              @foreach($static_ip_bills as $data)
              @php
              $available =App\model\Users\UserAmount::where(['username' => $data->username])->first();
              $balance = $available['amount'];
              @endphp
              <tr>
                <td>{{++$sno}}</td>
                <td class="td__profileName">{{$data->username}}</td>
                <td>{{$data->numberofips}}</td>
                <td>{{$data->rates}}</td>
                <td>{{date('M d,Y',strtotime($data->date))}}</td>
                <td>{{$data->amount}}</td>
                <td>{{$balance}}</td>
                @if($data->status == "paid")
                <td>Paid</td>
                @else
                <td>Unpaid</td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#example1').DataTable({
      responsive: true,
      orderCellsTop: true
    });
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
  function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  }
</script>
<script type="text/javascript">
  function formsubmit(username,amount,date) {
    $.ajax({
      type : "POST",
      url : "{{route('users.billing.bill')}}",
      data : {username:username,amount:amount,date:date},
      success : function(data){
        if(data == "not able"){
          alert("Balance is less then bill amount");
        }else{
          location.reload();
        }
      },
      error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#demo').html(error);
}
});
  }
</script>
@endsection
<!-- Code Finalize -->