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
				<h2>Monthly billing Download
					<span class="info-mark" onmouseenter="popup_function(this, 'monthly_billing_download');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
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
						<form action="{{route('users.billing.downloadPost')}}" method="POST">
							@csrf
							<div class="col-lg-6 col-md-8">
								<div class="form-group">
									<label  class="form-label">Select (MM/DD-YY) <span style="color: red">*</span></label>
									<div class="controls" style="margin-top: 0px;">
										<select class="form-control" name="date" required="">
											<option value="">Select (MM/DD/YY)</option>
											@php
											$from=date('2021-01-25');
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
					<div class="col-md-4 mt-4">
						<div class="form-group" style="margin-top: 27px;">
							<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-download"></i> Download Billing</button>
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
	}
	);
}
</script>
@endsection
<!-- Code Finalize -->