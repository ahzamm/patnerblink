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
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Third Party Commission 
					<span style="color: lightgray"><small>(History)</small></span> <span class="info-mark" data-toggle="tooltip" data-placement="bottom" title="
					<p class='mb-0'>Below showing entries transferred against 3rd party commission </p>" 
					data-html="true"><i class="las la-info-circle"></i></span></h2>
				</div>
				@if(session('error'))
				<div class="alert alert-error alert-dismissible show">
					{{session('error')}}
					<button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
				@endif
				@if(session('success'))
				<div id="alert" class="alert alert-success alert-dismissible">
					{{session('success')}}
				</div>
				@endif
				<section class="box ">
					<header class="panel_header"></header>
					<div class="content-body">
						<table id="example1" class="table table-bordered display dt-responsive w-100">
							<thead>
								<tr>
									<th>Serial#</th>
									<th>Username</th>
									<th>Recieved Amount (PKR)</th>
									<th>Recieved By</th>
									<th>Comments</th>
									<th>Date & Time</th>
									<th>IP Address</th>
								</tr>
							</thead>
							<tbody>
								@php $sno = 0; @endphp
								@foreach($paymentTransactions as $data)
								<tr>
									<td>{{++$sno}}</td>
									<td>{{$data->sender}}</td>
									<td>{{number_format($data->amount,2)}}</td>
									<td>{{$data->receiver}}</td>
									<td>{{$data->detail}}</td>
									<td>{{$data->date}}</td>
									<td>{{$data->action_ip}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2" style="text-align:center;font-weight:bold;font-size: 15px;border-left:1px solid #000;border-bottom:1px solid #000">Grand Total (PKR):</th>
									<th style="text-align:center;font-weight:bold;font-size: 15px; color: green;border-right:1px solid #000;border-bottom:1px solid #000"> </th>
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
.column( 2 )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Total Over All Pages
pageTotal = api
.column( 2, { page: 'current'} )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 2 ).footer() ).html(formatMoney(pageTotal) );
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