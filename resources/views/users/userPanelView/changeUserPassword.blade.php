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
@include('users.layouts.bytesConvert')
@include('users.userPanelView.ExpireUserModal')
@section('title') Dashboard @endsection
@section('owncss')
<style>
	input[type="file"] {
		display: block;
	}
	.imageThumb {
		height: 150px;
		width: 300px;
		border: 2px solid;
		padding: 1px;
		cursor: pointer;
		margin-top: 10px;
	}
	.pip {
		display: inline-block;
		margin: 10px 10px 0 0;
	}
	.remove {
		display: block;
		background: #444;
		border: 1px solid black;
		color: white;
		text-align: center;
		cursor: pointer;
	}
	.remove:hover {
		background: white;
		color: black;
	}
	#output_images
	{
		margin-top: 10px;
		width:250px;
		height: 140px;
		margin-left: -10px;
	}
	#output_image
	{
		margin-top: 10px;
		width:250px;
		height: 140px;
		margin-left: -10px;
	}
</style>
@endsection
@section('content')
@php
$username = Auth::user()->username;
@endphp
<!-- SMS Module Verification -->
{{-- <div aria-hidden="true"  role="dialog" tabindex="-1" id="changePass" class="modal fade" style="display: none;"> --}}
	<div class="page-container row-fluid container-fluid">
		<section id="main-content" class=" ">
			<section class="wrapper main-wrapper row" style=''>
				<div class="col-md-2"> </div>
				<div class="col-md-8"> 
					<div class="modal-dialog" style="width: 100%">
						<div class="modal-content">
							<div class="modal-body">
								<div class="row">
									<div class="col-md-12" >
										<h3 style="font-family: serif; font-weight: bold">User Name: <span style="color: red">{{$username}}</span></h3>
										<hr style="margin-top: 0px;margin-bottom: 0px; border: 1;border-top: 1px solid black;">
										<div class="col-md-3">		
										</div>
										<div class="col-md-6" style="margin-top: 40px;">
											<form action="{{route('users.userPanel.changeUserPass')}}" method="GET" class="form-group">
												<input type="hidden" name="user" value="{{$username}}" id="">
												<label for="pass">New Password <span style="color: red">*</span></label>
												<input type="password" name="pass" id="userpassword" class="form-control">
												<label for="pass">Re-enter Password <span style="color: red">*</span></label>
												<input type="password" name="repass" id="userconfirm_password" class="form-control"><span id='message1'></span><br>
												<button type="submit" id="btnPass1" class="btn btn-success" disabled>Update Password</button>
											</form>
										</div>
										<div class="col-md-3">
										</div>
									</div>
									{{-- <div class="col-md-5">
									</div> --}}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		{{-- </div> --}}
		<div>
			<div class="chart-container " style="display: none;">
				<div class="" style="height:200px" id="platform_type_dates"></div>
				<div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
				<div class="" style="height:200px" id="user_type"></div>
				<div class="" style="height:200px" id="browser_type"></div>
				<div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
				<div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
			</div>
		</div>
		@endsection
		@section('ownjs')
		<script>
			$(document).ready(function(){
				$.ajax({
					type: "POST",
					url: "{{route('users.userPanel.checkExpire')}}",
					success: function(data){
						if(data == "expire"){
							$("#myModal").modal('show');
						}
					}
				});
			});
		</script>
		<script>
			$('#userpassword, #userconfirm_password').on('keyup', function () {
				if ($('#userpassword').val() == $('#userconfirm_password').val()) {
					$('#message1').html('Matching').css('color', 'green');
					$('#btnPass1').attr('disabled',false);
				} else{
					$('#message1').html('Not Matching').css('color', 'red');
					$('#btnPass1').attr('disabled',true);
				}
			});
		</script>
		@endsection
<!-- Code Finalize -->