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
	#example-1_filter{
		margin-right: 15px;
	}
	.slider:before {
		position: absolute;
		content: "";
		height: 11px !important;
		width: 13px !important;
		left: 3px !important;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Contractor <span style="color: lightgray"><small>(Profit Summary)</small></span>
							<span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<div class="col-lg-12">
						<section class="box ">
							<header class="panel_header">
								<div class="actions panel_actions pull-right">
									<a class="box_toggle fa fa-chevron-down"></a>
								</div>
							</header>
							<div class="content-body">
								<div class="row">
									<form action="{{route('users.billing.summeryDetail')}}" target="_blank" method="POST">
										@csrf
										<div class="col-md-5">
											<div class="form-group">
												<label  class="form-label">Select (DD/TT/MM/YY) Range <span style="color: red">*</span></label>
												<span style="float: right; padding-right: 10px; font-weight: bold; color: darkgreen"><input class="radio2"  type="radio" name="gender" value="male"> Bill wise <span style="color: red">*</span>
												<input class="radio2" type="radio" name="gender" value="female"> Date wise <span style="color: red">*</span></span><br>
												<div class="controls" style="margin-top: 0px;">
													<input id="mytest" type="text" 
													name="datetimes" style="width: 100%;height: 34px"  >
													<select id="mytest2" class="form-control" name="date" style="display: none;" >
														<option value="">Select Date</option>
														@php
														$from=date('2020-01-25');
														$startTime = strtotime($from);
														$now = time();
														$datediff =($now-$startTime);
														$range=floor(($datediff / (60 * 60 * 24))+1);
														for($i=0;$i<$range;$i++){
														$date = date('Y-m-d',strtotime($from ."+".$i." days"));
														$newdate=explode("-",$date);
														if($newdate[2] == 10 || $newdate[2] == 25){
														@endphp
														<option value="{{$date}}">{{date('M d,Y' ,strtotime($date))}}</option>
														@php
													}
												}
												@endphp
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label  class="form-label">Select Account <span style="color: red">*</span></label>
										<select class="form-control" id="username-select" name="username" required >
											<option value="">Select Account</option>
											@if(Auth::user()->status == "dealer")
											<option value="own">own</option>
											@else
											<option value="all">All</option>
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
										<button class="btn btn-flat btn-primary" type="submit">Generate</button>
									</div>
								</div>
							</form>
							<!-- Report Summary -->
							<div class="col-md-12"></div>
							<!-- 2nd Table -->
							<div class="col-md-12" ></div>
						</div>
					</div>
				</section>
			</div>
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
<!-- Select User List -->
<script>
	function loadUserList(option){
		let userStatus = option.value;
// ajax call: jquery
console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
$.get(
	"{{route('admin.user.status.usernameList')}}?status="+userStatus,
	function(data){
		console.log(data);
		let content = "<option>select "+userStatus+"</option>";
		$.each(data,function(i,obj){
			content += "<option value='"+obj.username+"'>"+obj.username+"</option>"
		});
		$("#username-select").empty().append(content);
	});
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