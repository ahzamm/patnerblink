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
	#loadingnew{
		display: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="">
				<div class="">
					<form action="{{route('users.reset.verification')}}" method="POST">
						@csrf
						<div class="header_view">
							<h2>Consumer verification Data (Reset)
								<span class="info-mark" onmouseenter="popup_function(this, 'reset_consumer_verification');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
							</h2>
						</div>
						@if(session('error'))
						<div class="alert alert-error alert-dismissible show">
							{{session('error')}}
							<button type="button" class="close" data-dismiss="alert">&times;</button>
						</div>
						@endif
						@if(session('success'))
						<div class="alert alert-success alert-dismissible show">
							{{session('success')}}
							<button type="button" class="close" data-dismiss="alert">&times;</button>
						</div>
						@endif
						<div class="">
							<section class="box">
								<div class="content-body">
									<div class="text-center" style="margin-bottom: 20px">
									</div>
									<div class="row">
										<div class="col-md-3 col-xs-12">			
										</div>
										<div class="col-md-6 col-xs-12">
											<div class="form-group">
												<label>Consumer (ID) <span style="color: red">*</label>
													<input type="text" name="username" class="form-control" placeholder="Enter Consumer (ID) here...">
												</div>
											</div>
											<div class="col-md-3 col-xs-12"></div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<div style="display:flex; align-items:center; justify-content:center;column-gap:20px">
														<span>
															<input type="checkbox" name="nic" id="cnic_number" style="width:18px;height:18px">
															<label for="cnic_number" style="solid black"><i class="fa-solid fa-id-card fa-lg"></i> CNIC Number <span style="color: red">*</label>
															</span>
															<span>
																<input type="checkbox" name="mobile" id="mobile_number" style="width:18px;height:18px">
																<label for="mobile_number" style="solid black"><i class="fa-solid fa-mobile-screen fa-lg"></i> Mobile Number <span style="color: red">*</label>
																</span>
																<span>
																	<input type="checkbox" name="image" id="cnic_img" style="width:18px;height:18px">
																	<label for="cnic_img" style="solid black"><i class="fa-solid fa-image-portrait fa-lg"></i> CNIC Image <span style="color: red">*</label>
																	</span>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group pull-right">
																<button type="submit" class="btn btn-primary"  style="margin-top: 28px;">Reset Now</button>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
									</form>
									<section class="box" style="padding-top: 20px">
										<div class="content-body" style="padding-top:20px">
											<table id="example-1" class="table dt-responsive display w-100">
												<thead>
													<tr>
														<td>Serial#</td>
														<td>Consumer (ID)</td>
														<td>Contractor (ID) </td>
														<td>Trader (ID) </td>
														<td>Date & Time</td>
														<td>Reset Data</td>
														<td>Action By</td>
													</tr>
												</thead>
												<tbody>
													<?php foreach($reset_log as $key => $repairValue){ 
														$userInfo = DB::table('user_info')->where('username',$repairValue->username)->select('dealerid','sub_dealer_id')->first();
														$actionBy = DB::table('user_info')->where('id',$repairValue->action_by)->select('username')->first();
														?>
														<tr>
															<td>{{$key+1}}</td>
															<td class="td__profileName">{{$repairValue->username}}</td>
															<td>{{@$userInfo->dealerid}}</td>
															<td>{{ (empty($userInfo->sub_dealer_id)) ? 'N/A' : $userInfo->sub_dealer_id }}</td>
															<td>{{date('M d,Y H:i:s' ,strtotime($repairValue->datetime))}}</td>
															<td>{{$repairValue->reset}}</td>
															<td>{{$actionBy->username}}</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
										</div>
									</section>
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
				@endsection
<!-- Code Finalize -->