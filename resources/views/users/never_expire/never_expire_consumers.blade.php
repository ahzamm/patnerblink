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
<style>
	.blinkMe{
		color: red;
		font-weight:bold;
		animation: blinkDays 2s 1s ease alternate infinite;
	}
	@keyframes blinkDays {
		0%{
			opacity:.3
		}
		100%{
			opacity:1
		}
	}
</style>
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2> Never Expire Consumers
					<span class="info-mark" onmouseenter="popup_function(this, 'never_expire_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<div class="col-lg-12">
				<section class="box">
					<header class="panel_header">
					</header>
					<ul class="nav nav-tabs ml-3" style="display:flex;align-items:center;flex-wrap:wrap;margin-left: 15px;">
						<li class="active"><a data-toggle="tab" href="#tab1" aria-expanded="true">Consumer List</a></li>
						<li><a data-toggle="tab" href="#tab2" aria-expanded="false">Error Logs</a></li>
					</ul>
					<div class="tab-content" style="background-color:transparent">
						<div id="tab1" class="tab-pane fade active in">
							<div class="content-body">
								<div class="row">
									<table id="example-2" class="table table-bordered dt-responsive" style="width: 100%">
										<thead>
											<tr>
												<th>Serial#</th>
												<th>Consumer (ID)</th>
												<th>Full Name</th>
												<th>Last Charged Date</th>
												<th>Current Expiry Date</th>
												<th>Never Expire Till</th>
												<th>Contractor</th>
												<th>Trader</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div id="tab2" class="tab-pane fade ">
							<div class="content-body">
								<div class="row">
									<div class="d-flex">
										<label class="d-flex">Seach by Date: </label>
										<input type="date" name="date" id="datefilter" class="d-flex" value="<?= date('Y-m-d');?>" >
									</div>
									<table id="errorLogTable" class="table table-bordered dt-responsive" style="width: 100%">
										<thead>
											<tr>
												<th>Serial#</th>
												<th>Consumer (ID)</th>
												<th>Date & Time</th>
												<th>Message</th>
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
				</section>
			</div>
		</section>
	</section>
</div>
@endsection
@section('ownjs')
<script></script>
<!-- Select User List -->
<script type="text/javascript">
	$(document).ready(function(){
		get_error();
	});
	$('#datefilter').on('change',function(){
		get_error();
	});
	function get_error(){
		var date = $('#datefilter').val();
		$.ajax({
			type: "POST",
			url: "{{route('users.never_expire.errorlogs')}}",
			data: "date="+date,
			success: function (data) {
				$('#errorLogTable tbody').html(data);
				$('#errorLogTable').dataTable();
			},
			error: function(jqXHR, text, error){
				$('html, body').scrollTop(0);
				$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
			},
			complete:function(){
				$('#errorLogModal').modal('show');
			},
		});
	}

    $('#example-2').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('users.never_expire.getConsumers') }}',
            columns: [
                { data: 'serial', name: 'serial' },
                { data: 'consumer_id', name: 'consumer_id' },
                { data: 'full_name', name: 'full_name' },
                { data: 'last_charged_date', name: 'last_charged_date' },
                { data: 'current_expiry_date', name: 'current_expiry_date' },
                { data: 'never_expire_till', name: 'never_expire_till' },
                { data: 'contractor', name: 'contractor' },
                { data: 'trader', name: 'trader' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
</script>
@endsection
<!-- Code Finalize -->
