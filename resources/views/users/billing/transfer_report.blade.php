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
				<h2>Amount Transfer Report
					<span class="info-mark" onmouseenter="popup_function(this, 'amount_transfer_report');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box">
				<header class="panel_header">
					<div class="actions panel_actions pull-right">
						<a class="box_toggle fa fa-chevron-down"></a>
					</div>
				</header>
				<div class="content-body">
					<div class="row">
						<form action="{{route('users.billing.transfer_PDF')}}" method="POST" target="_blank">
							@csrf
							<div class="col-md-5">
								<div class="form-group">
									<label  class="form-label">Select (DD/TT/MM/YY) Range <span style="color: red">*</span></label>
									<div class="controls" style="margin-top: 0px;">
										<input id="mytest" type="text" 
										name="datetimes" style="width: 100%;height: 34px" 
										>
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label  class="form-label">Select Account <span style="color: red">*</span></label>
									<select class="js-select2" id="username-select" name="username" required >
										<option value="">Select Account</option>
										@if(Auth::user()->status == "reseller")
										{{-- <option value="All">All</option> --}}
										@endif
										@if(Auth::user()->status == "dealer")
										<option value="{{Auth::user()->username}}">own</option>
										@endif
										@foreach($userCollection as $data)
										<option value="{{$data->username}}">{{$data->username}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<br>
							<div class="col-md-2">
								<div class="form-group" style="margin-top: 5px;">
									<button class="btn btn-flat btn-primary" type="submit" >Generate</button>
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
<!-- Select User List -->
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
@endsection
<!-- Code Finalize -->