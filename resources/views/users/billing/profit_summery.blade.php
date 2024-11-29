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
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Profit Report
							<span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<div class="col-lg-12">
						<section class="box ">
							<header class="panel_header">
								<h2 class="title pull-left">Report</h2>
								<div class="actions panel_actions pull-right">
									<a class="box_toggle fa fa-chevron-down"></a>
								</div>
							</header>
							<div class="content-body">
								<div class="row">
									<form action="{{route('users.billing.profitSummary')}}" method="POST" target="_blank">
										@csrf
										<div class="col-md-5">
											<div class="form-group">
												<label  class="form-label">Select Date Range</label>
												<div class="controls" style="margin-top: 0px;">
													<input type="text" 
													name="datetimes" style="width: 100%;height: 34px" required="">
												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label  class="form-label">Select Username</label>
												<select class="form-control" id="username-select" name="username" required >
													<option value="">select Username</option>
													@if(Auth::user()->status == "dealer")
													<option value="{{Auth::user()->username}}">own</option>
													@endif
													@foreach($userCollection as $data)
													<option value="{{$data->username}}">{{$data->username}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-2">
											<br>
											<div class="form-group" style="margin-top: 5px;">
												<button class="btn btn-flat btn-primary" type="submit" >Generate</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</section>
	</section>
</div>
<div class="chart-container " style="display: none;">
	<div class="" style="height:200px" id="platform_type_dates"></div>
	<div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
	<div class="" style="height:200px" id="user_type"></div>
	<div class="" style="height:200px" id="browser_type"></div>
	<div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
	<div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
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