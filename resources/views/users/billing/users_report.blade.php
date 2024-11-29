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
	.th-color{
		color: white !important;
	}
	.header_view{
		margin: auto;
		height: 40px;
		padding: auto;
		font-family:Arial,Helvetica,sans-serif;
	}
	h2{
		color: #225094 !important;
		text-align: center;
	}
	.dataTables_filter{
		margin-left: 60%;
	}
	tr,th,td{
		text-align: center;
	}
	select{
		color: black;
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
	.active{
		background-color: #225094 !important;
	}
	.title h2 {
		font-size: 30px;
		line-height: 30px;
		margin: 3px 0 7px;
		font-weight: 700;
	}
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
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Billing Invoice Report</h2>
						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left">Billing Invoice Report</h2>
									<div class="actions panel_actions pull-right">
										<a class="box_toggle fa fa-chevron-down"></a>
									</div>
								</header>
								<div class="content-body">
									<div class="row">
										<form action="{{route('users.billing.usersreport')}}" method="POST">
											@csrf
											<div class="col-md-5">
												<div class="form-group">
													<label  class="form-label">Date Range</label>
													<div class="controls" style="margin-top: 0px;">
														<input type="text" id="daterange-1"
														name="date"
														class="form-control daterange" required="">
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<br>
												<div class="form-group" style="margin-top: 5px;">
													<button class="btn btn-flat btn-info" type="submit" >Generate</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</section>
						</div>
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
@endsection
<!-- Code Finalize -->