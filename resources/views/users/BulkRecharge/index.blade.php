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
@section('owncss')
<style type="text/css">
	#mobile select,
	#filter_ser select{
		display: none;
	}
	#filter_username select{
		display: none;
	}
	#filter_fullname select{
		display: none;
	}
	#date select{
		display: none;
	}
	#check select{
		display: none;
	}
	#address select{
		display: none;
	}
	.badge .fa {
		font-size: 25px !important;
	}
	#loadingnew{
		display: none;
	}
	.modal {
		text-align: center;
	}
	@media screen and (min-width: 200px) { 
		.modal:before {
			display: inline-block;
			vertical-align: middle;
			content: " ";
			height: 100%;
		}
	}
	.modal-dialog {
		display: inline-block;
		text-align: left;
		vertical-align: middle;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2 id="output">Bulk Recharge Accounts
					<span class="info-mark" onmouseenter="popup_function(this, 'bulk_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box">
				<header class="header"></header>
				<div class="content-body">
					<div id="returnMsg"></div>
					<div class="row">
						<form  id="bulkRechargeForm">
							@csrf
							<input type="hidden" name="level" value="confirmation">
							<table id="bulkRechargeTable" class="table table-striped dt-responsive" style="width:100%">
								<thead>
									<tr>
										<th style="width: 30px;"><input type="checkbox" class="from-control" onclick="checkall(this)" name="allcheck" style="width:25px;height:25px;"></th>
										<th>Consumer (ID)</th>
										<th>Consumer Name</th>
										<th class="desktop">Address</th>
										<th>Mobile Number</th>
										<th>Internet Profile Name</th>
										<th>Expiry (Date & Time)</th>
									</tr>
								</thead>
								<tbody id="">
									<tr><td colspan="7">Loading...</td></tr>
								</tbody>
							</table>
							<div class="col-md-12">
								<div class="form-group pull-right">
									<button type="button" class="btn btn-info" id="rechargeLogs">Recharge Logs</button>
									<button type="button" class="btn btn-warning" id="errorLogs">Recent Error Logs</button>
									<button type="submit" id="chargeBtn" disabled="true" class="btn btn-primary" value="Charge">Recharge</button>
									<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
		</section>
	</section>
	<!-- Processing -->
	<div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
		<div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
			<div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
				<div class="modal-body">
					<center><h1 style="color:white;">Processing....</h1>
						<p style="color:white;">please wait.</p>
					</center>
				</div>
			</div>
		</div>
	</div>	
	<!-- Recharge Confirmation -->
	<div aria-hidden="true"  role="dialog" tabindex="-1" id="rechargeConfirmation" class="modal fade" style="display: none;" data-backdrop="static">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" style="text-align: center; color: white"> Confirmation
					</h4>
				</div>
				<div class="modal-body">
					<form id="bulkRechargeConfirmationForm">
						@csrf
						<div class="row">
							<div class="col-md-12">
								<input type="hidden" name="level" value="confirmed">
								<table id="bulkRechargeConfirmationTable" class="table table-striped dt-responsive" style="width:100%">
									<thead>
										<tr>
											<th>Username</th>
											<th>Package</th>
											<th>Wallet Deduction</th>
										</tr>
									</thead>
									<tbody id="">
										<tr><td colspan="7">Loading...</td></tr>
									</tbody>
								</table>
							</div>
						</div>
						<div style="display: flex; justify-content: space-between; align-items: center;flex-wrap:wrap">
							<div class="form-group" style="float:right;">
								<h3>Please confirm to proceed</h3>
								<button type="submit" class="btn btn-primary">Confirm</button>
								<button type="" class="btn btn-danger" data-dismiss="modal" onclick="$('#processLayer').modal('hide');">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- Recent Recharge Logs -->
	<div aria-hidden="true"  role="dialog" tabindex="-1" id="rechargeLogModal" class="modal fade" style="display: none;" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center; color: white">Consumers Recharge Logs
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="d-flex">
								<label class="d-flex">Seach by Recharge Date: </label>
								<input type="date" name="date" id="datefilter" class="d-flex" value="<?= date('Y-m-d');?>" >				
							</div>
						</div>
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="rechargeLogTable" class="table table-striped dt-responsive" style="width:100%">
									<thead>
										<tr>
											<th>Serial#</th>
											<th>Consumer (ID)</th>
											<th>Internet Profile Name</th>
											<th>Expiry (Date & Time)</th>
											<th>Charge On (Date & Time)</th>
										</tr>
									</thead>
									<tbody id="">
										<tr><td colspan="7">Loading...</td></tr>
									</tbody>
								</table>			
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Recent Error Logs -->
	<div aria-hidden="true"  role="dialog" tabindex="-1" id="errorLogModal" class="modal fade" style="display: none;" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center; color: white"> Recently Recharged Error Logs
					</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<table id="errorLogTable" class="table table-striped dt-responsive" style="width:100%">
								<thead>
									<tr>
										<th>Serial#</th>
										<th>Consumer (ID)</th>
										<th>Date & Time</th>
										<th>Error Message</th>
									</tr>
								</thead>
								<tbody id="">
									<tr><td colspan="7">Loading...</td></tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection
@section('ownjs')
{{-- <script type="text/javascript">
	if(localStorage.openpages){
		alert('File already opened in a tab.');
		$(".btn").css("display", "none");
	}else{
		localStorage.openpages = "1";
		window.onbeforeunload = function () {
			localStorage.openpages = "";
		};
		$("#btnText").css("display", "none");
	}
</script> --}}
<script>
	$(document).ready(function(){
		var vals = $('#example1_length select').val();
		if (vals == 100){
			alert('Select below then 100.');
		}
	});
	$(document).ready(function(){
		$(document).on('click','input[type="checkbox"]',function(){
			var count =$('#checkthis:checked').length;
			if(count > 0){
				$('#chargeBtn').prop('disabled', false);
			}else{
				$('#chargeBtn').prop('disabled', true);
			}
		});
	});
	$("#myform").submit(function(){
		$('#chargeBtn').hide();
		$('#loadingnew').show();
	});
</script>
<script type="text/javascript">
	function checkall(source) {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i] != source)
				checkboxes[i].checked = source.checked;
		}
	}
</script>
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
	$(document).ready(function(){
		$.ajax({ 
			type: "POST",
			url: "{{route('users.bulk_recharge.show_consumer')}}",
			success: function (data) {
				$('#bulkRechargeTable tbody').html(data);
			},
			error: function(jqXHR, text, error){
			},
			complete:function(){
				$('#bulkRechargeTable').dataTable();
			},
		});
		return false;
	})
</script>
<script type="text/javascript">
	$("#bulkRechargeForm").submit(function() {
		$('#processLayer').modal('show');
		$.ajax({ 
			type: "POST",
			url: "{{route('users.bulk_recharge.action')}}",
			data:$("#bulkRechargeForm").serialize(),
			success: function (data) {
				$('#rechargeConfirmation').modal('show');
				$('#bulkRechargeConfirmationTable tbody').html(data);
			},
			error: function(jqXHR, text, error){
				$('html, body').scrollTop(0);
				$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
				$('#processLayer').modal('hide');
			},
			complete:function(){
			},
		});
		return false;
	})
</script>
<script type="text/javascript">
	$("#bulkRechargeConfirmationForm").submit(function() {
		if(confirm("Do your really want to recharge ?")){
			$('#rechargeConfirmation').modal('hide');
			$('#processLayer').modal('show');
			$.ajax({ 
				type: "POST",
				url: "{{route('users.bulk_recharge.action')}}",
				data:$("#bulkRechargeConfirmationForm").serialize(),
				success: function (data) {
//
$('html, body').scrollTop(0);
$('#processLayer').modal('hide');
$('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
setTimeout(function() { 
	location.reload();
}, 2000);
},
error: function(jqXHR, text, error){
	$('html, body').scrollTop(0);
	$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
	$('#processLayer').modal('hide');
},
complete:function(){
},
});
		}
		return false;
	})
</script>
<script type="text/javascript">
	$('#rechargeLogs').on('click',function(){
		getRechargeLog();
	});
	$('#datefilter').on('change',function(){
		getRechargeLog();
	});
	function getRechargeLog(){
		var date = $('#rechargeLogModal #datefilter').val();
		$.ajax({ 
			type: "POST",
			url: "{{route('users.bulk_recharge.logs')}}",
			data: "date="+date,
			success: function (data) {
				$('#rechargeLogTable tbody').html(data);
			},
			error: function(jqXHR, text, error){
				$('html, body').scrollTop(0);
				$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
			},
			complete:function(){
				$('#rechargeLogModal').modal('show');
				$('#rechargeLogTable').dataTable();
			},
		});
	}
</script>
<script type="text/javascript">
	$('#errorLogs').on('click',function(){
		$.ajax({ 
			type: "POST",
			url: "{{route('users.bulk_recharge.errorlogs')}}",
			success: function (data) {
				$('#errorLogTable tbody').html(data);
			},
			error: function(jqXHR, text, error){
				$('html, body').scrollTop(0);
				$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
			},
			complete:function(){
				$('#errorLogModal').modal('show');
			},
		});
	})
</script>
<script type="text/javascript">
	$('#rechargeConfirmation').on('hidden.bs.modal', function () {
		$('#processLayer').modal('hide');
	});
</script>
@endsection
<!-- Code Finalize -->