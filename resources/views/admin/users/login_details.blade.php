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
	.modal {
		display: none;
		position: fixed;
		z-index: 1;
		padding-top: 100px; 
		left: 0;
		top: 0;
		width: 100%; 
		height: 100%; 
		overflow: auto; 
		background-color: rgb(0,0,0); 
		background-color: rgba(0,0,0,0.4); 
	}
	/* Modal Content */
	.modal-content {
		background-color: #fefefe;
		margin: auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}
	/* The Close Button */
	.close {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}
	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content" >
		<section class="wrapper main-wrapper">
			<div class="header_view">
				<h2>Login Authentication Detail
					<span class="info-mark" onmouseenter="popup_function(this, 'login_authentication_detail_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box ">
				<header class="panel_header">
					<div class="actions panel_actions pull-right">
						<a class="box_toggle fa fa-chevron-down"></a>
					</div>
				</header>
				<div class="content-body">
					<div class="row">
						<form action="{{route('admin.logindetails')}}" method="POST">
							@csrf
							<div class="col-md-4">
								<div class="form-group position-relative">
									<label  class="form-label">Assgin (Date&Time) Range <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_date_time_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<div class="controls" style="margin-top: 0px;">
										<input id="mytest" type="text" 
										name="datetimes" style="width: 100%;height: 34px"  >
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group position-relative">
									<label  class="form-label">Select Contractor <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select class="form-control" id="username-select" name="username" onchange="showsubdealer(this);" required >
										<option value="">Select Contractor</option>
										@foreach($userCollection as $data)
										<option value="{{$data->username}}">{{$data->username}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group position-relative">
									<label  class="form-label">Select Trader <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select name="subname" onchange="showtrader(this)" id="state" class="form-control" required>
										<option value="">Select Trader</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group pull-right" style="margin-left:  16px;">
									<button class="btn btn-flat btn-primary">Download Now</button>
								</div>
							</div>
						</form>
					</div>
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
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
.column( 7 )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( 7, { page: 'current'} )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 7 ).footer() ).html(pageTotal);
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
<script>
	$(function() {
		$('input[name="datetimes"]').daterangepicker({
			timePicker: true,
			startDate: moment().startOf('hour'),
			endDate: moment().startOf('hour'),
			locale: {
				format: 'M/DD/YYYY HH:mm'
			}
		});
	});
</script>
<script>
	function showsubdealer($username){
		$.ajax({
			type : 'get',
			url : "{{route('admin.loadSubDealer')}}",
			data:'username='+$username.value,
			success:function(res){
				$("#state").empty();
				$("#state").append('<option>Select</option><option>OWN (Account)</option>');
				$.each(res,function(key,value){
					$("#state").append('<option value="'+value.username+'">'+value.username+'</option>');
				})
			}
		});
	}
</script>
<script type="text/javascript">
	$('.radio2').click(function(e){
		var test = $(this).val();
		if(test == "male"){
			$('#mytest').hide();
			$('#mytest2').show();
		}else{
			$('#mytest').show();
			$('#mytest2').hide();
		}
	});
</script>
@endsection
<!-- Code Finalize -->