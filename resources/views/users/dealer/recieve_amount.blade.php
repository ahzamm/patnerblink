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
@extends('admin.layouts.app')
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
	textarea {
		resize: none;
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
					<form action="{{route('admin.billing.post',['status' => 'recieve'])}}" method="POST">
						@csrf
						<div class="header_view">
							<h2>Recieve Amount
								<span class="info-mark" onmouseenter="popup_function(this, 'receipt_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
							</h2>
						</div>
						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
								</header>
								<div class="content-body">   
									<div class="row">
										<div class="col-xs-12">
											<div class="col-md-12" style="text-align: center;">
												<label >Add To Acc:</label>
												<div class="form-group">
													<div class="btn-group btn-group-toggle" data-toggle="buttons">
														<label class="btn btn-secondary active">
															<input type="radio" name="options" value="manager" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true" > Manager
														</label>
														<label class="btn btn-secondary">
															<input type="radio" name="options" value="reseller" id="option2" onchange="loadUserList(this)" autocomplete="off"> Reseller
														</label>
														<label class="btn btn-secondary">
															<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Dealer
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-4"></div>
											<div class="col-md-4">
												<div class="form-group">
													<select name="username" id="username-select" class="form-control" required >
														<option>select manager</option>
													</select>
												</div>
											</div>
											<div class="col-md-4"></div>
											<div class="col-md-12">
												<div style="overflow-x:auto;">
													<table class="table table-responsive">
														<thead style="background:#225094;color: white">
															<tr>
																<th>Amount</th>
																<th>Discount</th>
																<th>Paid By</th>
																<th>Comment</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td><input type="Number" class="form-control"  placeholder="0" required name="amount"></td>
																<td><input type="Number" class="form-control"  placeholder="0" required name="discount"></td>
																<td><input type="text" class="form-control"  placeholder="Paid by" required name="paidBy"></td>
																<td><textarea class="form-control" name="comment"  placeholder="Comment here !!" ></textarea></td>
															</tr>
															<tr>
																<td colspan="4" style="background:#225094;color: white">Payment Method:</td>
															</tr>
															<tr>
																<td colspan="1"></td>
																<td colspan="2"><div class="btn-group btn-group-toggle" data-toggle="buttons">
																	<label class="btn btn-secondary" onclick="chequeDetail()">
																		<input type="radio" name="paymentType" value="cheque" id="option2" autocomplete="off"> Cheque 
																	</label>
																	<label class="btn btn-secondary active" onclick="slideup()">
																		<input type="radio" name="paymentType" value="chash" id="option3" autocomplete="off" checked="true"> Cash
																	</label>
																	<label class="btn btn-secondary " onclick="onlineDetail()">
																		<input type="radio" name="paymentType" value="online" id="option4" autocomplete="off"> Online
																	</label>
																</div>
																<div style="display: none;" id="showChequeDetails">
																	<div class="form-group">
																		<br> <br>
																		<input type="text" class="form-control"  name="bankname" placeholder="Enter Bank name " >
																	</div>
																	<div class="form-group">
																		<input type="text" class="form-control"  name="checkNo" placeholder="Enter Cheque No here" >
																	</div>
																</div> 
																<div style="display: none;" id="onlinebank">
																	<div class="form-group">
																		<br> <br>
																		<input type="text" class="form-control"  name="onlinebankname" placeholder="Enter Bank name " >
																	</div>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<button type="submit" class="btn btn-primary">Recieve Amount</button>
										</div>
									</div>
								</div>
							</div>
						</section></div>
						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
									<h2 class="title pull-left">PAYMENT STATUS</h2>
								</header>
								<div class="content-body">   
									<div class="row">
										<div class="col-md-12">
											<div style="overflow-x: auto;">
												<table id="example-1" class="table ">
													<thead>
														<tr>
															<th>Username</th>
															<th>Recieve Amount</th>
															<th>Remaing Amount</th>
															<th>Recieve By</th>
															<th>Date</th>
															<th>I.P</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>Waqas</td>
															<td>12,000</td>
															<td>500</td>
															<td>Talha</td>
															<td>21/2/2016 7:25:01</td>
															<td>192.168.10.1</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</section></div>
						</form>
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
	<script >
		function chequeDetail(){
			$('#showChequeDetails').slideDown(); 
			$('#onlinebank').hide(); 
		}
		function slideup()
		{
			$('#showChequeDetails').slideUp(); 
			$('#onlinebank').slideUp(); 
		}
		function onlineDetail(){
			$('#onlinebank').slideDown(); 
			$('#showChequeDetails').hide(); 
		}
	</script>
	<script>
		function loadUserList(option){
			let userStatus = option.value;
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
	@endsection
<!-- Code Finalize -->