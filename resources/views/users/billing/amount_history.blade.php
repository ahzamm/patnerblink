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
      <div class="header_view">
        <h2>Current Balance Amount
          <span class="info-mark" onmouseenter="popup_function(this, 'current_balance_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section class="box" style="padding-top:20px">
        <div class="content-body collapsed"> 
          <form action="{{route('users.billing.current_balance')}}" method="POST" style="">
            @csrf
            <button class="btn btn-flat btn-primary" style="float: right;margin-right: 15px;margin-bottom:20px;" value="download" name="download"><i class="fa fa-download"></i> Download </button>
          </form>
          <table id="example1" class="table dt-responsive table-bordered w-100">
            <thead>
              <tr>
                <th>Serial#</th>
                <th>Account (ID)</th>
                <th>Status</th>
                <th>Balance Amount (PKR)</th>
              </tr>
            </thead>
            <tbody>
              @php $sno = 1;
              $billingType = 'amount';
              if(Auth::user()->status == 'dealer'){
              $billingType = App\model\Users\DealerProfileRate::where('dealerid',Auth::user()->dealerid)->first();
              $billingType = $billingType->billing_type;
            }
            if($billingType == 'card'){
            @endphp
            @foreach($userCollection as $data)
            <tr>
              <td>{{$sno++}}</td>
              <td>{{$data->sub_dealer_id}}</td>
              <td>Trader</td>
              <td>{{number_format($data->rates,2)}}</td>
            </tr>
            @endforeach
            @php }else{ 
            @endphp
            @foreach($userCollection as $data)
            @php
            $status = ($data->status == 'dealer') ? 'contractor' : (($data->status == 'subdealer') ? 'trader' :  $data->status );
            @endphp
            <tr>
              <td>{{$sno++}}</td>
              <td>{{$data->username}}</td>
              <td>{{$status}}</td>
              <td>{{number_format($data->amount,2)}}</td>
            </tr>
            @endforeach
            @php } @endphp
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" style="text-align:center;font-weight:bold;font-size: 15px;">Total Amount (PKR):</th>
              <th style="text-align:center;font-weight:bold;font-size: 15px; color: green"> </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </section>
  </section>
</section>
</div>
@endsection
@section('ownjs')
<script type="">
  $(document).ready(function() {
    $('#example1').DataTable( {
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;
// Remove The Formatting To Get Integer Data For Summation
var intVal = function ( i ) {
  return typeof i === 'string' ?
  i.replace(/[\$,]/g, '')*1 :
  typeof i === 'number' ?
  i : 0;
};
// Total Over All Pages
total = api
.column( 3 )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( 3, { page: 'current'} )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 3 ).footer() ).html( formatMoney(pageTotal)
  );
}
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
@endsection
<!-- Code Finalize -->