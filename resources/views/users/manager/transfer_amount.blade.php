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
		text-align: center;
		font-family:Arial,Helvetica,sans-serif;
	}
	h2{
		color: #225094 !important;
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
						<h2>Tansfer Amount
							<span class="info-mark" onmouseenter="popup_function(this, 'transfer_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<div class="col-lg-12">
						<section class="box ">
							<header class="panel_header">
							</header>
							<div class="content-body">   
								<div class="row">
									<div class="col-xs-12">
										<div class="col-md-3"></div>
										<div class="col-md-6">
											<div class="form-group">
												<label  class="form-label">Tranfer Amount</label>
												<input type="Number" class="form-control"  placeholder="username" required>
											</div>
											<label >Add To Acc:</label>
											<div class="form-group">
												<div class="btn-group btn-group-toggle" data-toggle="buttons">
													<label class="btn btn-secondary">
														<input type="radio" name="options" id="option2" autocomplete="off"> Reseller
													</label>
													<label class="btn btn-secondary">
														<input type="radio" name="options" id="option3" autocomplete="off"> Contactor
													</label>
												</div>
											</div>
											<div class="form-group">
												<label  class="form-label">Dealer</label>
												<select class="form-control" required >
													<option>test1</option>
													<option>tets2</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-3"></div>
									<div class="col-md-6">
										<div class="form-group">
											<button type="submit" class="btn btn-primary">Transfer Amount</button>
										</div>
									</div>
								</div>
							</div>
						</section></div>
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
<!-- Code Finalize -->