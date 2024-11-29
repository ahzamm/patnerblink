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
	#f1 select,#f3 select,#f4 select,#f5 select,#f6 select,#f7 select {display: none}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Margin</span>
					<span class="info-mark" onmouseenter="popup_function(this, 'margin');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box ">
				<header class="panel_header"></header>
				<div class="content-body">
					<table id="example1" class="table table-bordered display dt-responsive w-100">
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Account (ID)</th>
								<th>Margin From</th>
								<th>Margin (PKR) </th>
								<th>Transferred By</th>
								<th>Comments</th>
								<th>Assgin (Date & Time)</th>
								<th>Assgin (IP Address)</th>
							</tr>
							<tr style="background-color: #fff !important">
								<td id="f1"></td>
								<td id="f2"></td>
								<td id="f8"></td>
								<td id="f3"></td>
								<td id="f4"> </td>
								<td id="f5"></td>
								<td id="f6"></td>
								<td id="f7"></td>
							</tr>
						</thead>
						<tbody>
							@php
							$sno=1;
							@endphp
							@foreach($amountTransactions as $data)
							<tr>
								<td>{{$sno++}}</td>
								<td>{{$data->receiver}}</td>
								<td>{{$data->margin_from}}</td>
								<td>{{number_format($data->com_amount,2)}}</td>
								<td>{{$data->action_by_user}}</td>
								<td>{{$data->comments}}</td>
								<td>{{$data->date}}</td>
								<td>{{$data->action_ip}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3" style="text-align:center;font-weight:bold;font-size: 15px;border-left:1px solid #000;border-bottom:1px solid #000">Grand Total (PKR)</th>
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
		var table = $('#example1').DataTable( {
			orderCellsTop: true,
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
$( api.column( 3 ).footer() ).html( formatMoney(pageTotal) );
}
} );
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
	function xyz(username,amount){
		var submit = confirm("Are you sure want to post this?? \n"+formatMoney(amount) +" \n"+inWords(amount));
		if(submit == true){
			$("#myform").submit(function(){
				$.ajax({
					type : "POST",
					url : "{{route('users.billing.save')}}",
					data :$("#myform").serialize(),
					success : function(data){
						location.reload();
					},
					error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#demo').html(error);
}
});
				return false;
			});
		}else{
		}
	}
</script>
@endsection
<!-- Code Finalize -->