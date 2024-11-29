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
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="header_view">
				<h2>Utilization Internet Data
					<span class="info-mark" onmouseenter="popup_function(this, 'utilization_internet_data');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box">
				<header class="panel_header">
					<h2 class="title pull-left"></h2>
				</header>
				<?php
				$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
				$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
				$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
				$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
				$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
				if(empty($resellerid)){
					$panelof = 'manager';
				}else if(empty($dealerid)){
					$panelof = 'reseller';
				}else if(empty($sub_dealer_id)){
					$panelof = 'dealer';
				}else{
					$panelof = 'subdealer'; 
				}
				?>
				<div class="content-body">
					<form id="getExceedDataForm" >
						@csrf
						<div class="row">
							<?php
							if($panelof == 'manager'){
								$selectedReseller = App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get();
								?>
								<div class="col-md-3">
									<div class="form-group position-relative">
										<label style="font-weight: normal">Reseller <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="reseller-dropdown" class="js-select2" name="reseller_data" data-status="reseller">
											<option value="">--- Select Reseller (ID) ---</option>
											@foreach($selectedReseller  as $reseller)
											<option value="{{$reseller->username}}">{{$reseller->username}}</option>
											@endforeach
										</select>
									</div>
								</div>
							<?php } if(($panelof == 'manager') || ($panelof == 'reseller')){?>
								<div class="col-md-3">
									<div class="form-group position-relative">
										<label>Contractor <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="dealer-dropdown" class="js-select2" name="dealer_data" data-status="contractor">
											<option value="">--- Select Contractor (ID) ---</option> 
											<?php
											if(Auth::user()->status == 'reseller' || Auth::user()->status == 'inhouse'){
												$selectedDealer = App\model\Users\UserInfo::where('status','dealer')->where('resellerid',Auth::user()->resellerid)->get(); 
												foreach ($selectedDealer as $dealer) { ?>
													<option value="{{$dealer->username}}">{{$dealer->username}}</option>
													<?php   
												} 
											}
											?>
										</select>
									</div>
								</div>
							<?php } if(($panelof == 'manager') || ($panelof == 'reseller') || ($panelof == 'dealer') ){ ?>
								<?php if($panelof == 'dealer'){ ?>
									<div class="col-md-3">
										<div class="form-group position-relative">
											<label>Contractor <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<select id="dealer-dropdown" class="js-select2" name="dealer_data" data-status="contractor">
												<option value="">--- Select Contractor (ID) ---</option> 
												<?php
												$selectedDealer = App\model\Users\UserInfo::where('status','dealer')->where('dealerid',Auth::user()->dealerid)->get(); 
												foreach ($selectedDealer as $dealer) { ?>
													<option value="{{$dealer->username}}">{{$dealer->username}}</option>
													<?php   
												} 
												?>
											</select>
										</div>
									</div>
								<?php } ?>
								<div class="col-md-3">
									<div class="form-group position-relative">
										<label>Trader <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="trader-dropdown" class="js-select2" name="trader_data" data-status="trader">
											<option value="">--- Select Trader (ID) ---</option>
											<?php
											if(Auth::user()->status == 'dealer'){
												$selectedDealer = App\model\Users\UserInfo::where('status','subdealer')->where('dealerid',Auth::user()->dealerid)->get(); 
												foreach ($selectedDealer as $subdealer) { ?>
													<option value="{{$subdealer->username}}">{{$subdealer->username}}</option>
												<?php  } 
											} ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<?php if($panelof == 'subdealer'){ ?>
								<div class="col-md-3">
									<div class="form-group position-relative">
										<label>Trader <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_trader_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="trader-dropdown" class="js-select2" name="trader_data" data-status="trader">
											<option value="">--- Select Trader (ID) ---</option>
											<?php
											$selectedDealer = App\model\Users\UserInfo::where('status','subdealer')->where('sub_dealer_id',Auth::user()->sub_dealer_id)->get(); 
											foreach ($selectedDealer as $subdealer) { ?>
												<option value="{{$subdealer->username}}">{{$subdealer->username}}</option>
											<?php  }  ?>
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="col-md-3">
								<div class="form-group position-relative">
									<label>Internet Profile <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_internet_profile');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select class="js-select2" name="proSelect" id="profiles">
										<option value="">--- Select Internet Profile ---</option>
									</select>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group position-relative">
									<label>(IPT) Bandwidth Data Range From <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'ipt_bandwidth_range_user_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="number" class="form-control" min="1" name="rangeFrom" placeholder="Example: 500">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group position-relative">
									<label>(IPT) Bandwidth Data Raneg To <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'ipt_bandwidth_range_user_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="number" class="form-control" min="1" name="rangeTo" placeholder="Example: 1000">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group position-relative">
									<label>Data Measurement Size <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'data_measurement_size_user_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select class="form-control" name="unit" id="unit">
										<option value="">--- Select Data Measurement Size ---</option>
										<option>PB </option>
										<option>TB</option>
										<option>GB</option>
										<option>MB</option>
										<option>KB</option>
									</select>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" class="btn btn-primary" style="float:right;">Submit</button>
							</div>	
						</div>
					</form>
					<table id="table1" class="table dt-responsive display w-100">
						<thead>
							<tr>
								<th style="width:25px">Serial#</th>
								<th>Consumer (ID)</th>
								<th>Reseller (ID)</th>
								<th>Contractor (ID)</th>
								<th>Trader (ID)</th>
								<th>Internet Profile</th>
								<th>Download Utilization (Data)</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody id="example-1_tbody">
						</tbody>
					</table>
				</div>
			</section>
		</section>
	</section>
</div>
<!-- The Modal -->
<div class="modal" id="graphModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title text-white" style="color: #fff"> Live Graph</h4>
				<input type="hidden" id="username_for_graph">
			</div>
			<!-- Modal Body -->
			<div class="modal-body image-graph-model">
				<div id="chartContainer" style="height: 370px; width: 100%;"></div>
			</div>
			<!-- Modal Footer -->
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('ownjs')
<script>
	$(document).ready(function () {
		$('#reseller-dropdown').on('change', function () {
			var reseller_id = this.value;
			if(reseller_id == ''){
				$('#btn_generate').prop('disabled', true)
			}else{
				$('#btn_generate').prop('disabled', false)
			}
			$("#dealer-dropdown").html('');
			$.ajax({
				url: "{{route('get.dealer')}}",
				type: "POST",
				data: {
					reseller_id: reseller_id,
					_token: '{{csrf_token()}}'
				},
				dataType: 'json',
				success: function (result) {
					$('#dealer-dropdown').html('<option value="">-- Select contractor --</option>');
					$.each(result.dealer, function (key, value) {
						$("#dealer-dropdown").append('<option value="' + value
							.username + '">' + value.username + '</option>');
					});
					$('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
				}
			});
		});
/*------------------------------------------
--------------------------------------------
Trader Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#dealer-dropdown').on('change', function () {
	var dealer_id = this.value;
	$("#trader-dropdown").html('');
	$.ajax({
		url: "{{route('get.trader')}}",
		type: "POST",
		data: {
			dealer_id: dealer_id,
			_token: '{{csrf_token()}}'
		},
		dataType: 'json',
		success: function (result) {
			$('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
			$.each(result.subdealer, function (key, value) {
				$("#trader-dropdown").append('<option value="' + value
					.username + '">' + value.username + '</option>');
			});
		}
	});
});
$('#reseller-dropdown,#dealer-dropdown,#trader-dropdown').on('change', function () {
	var id = this.value;
	var status = $(this).attr('data-status');
$.ajax({
	url: "{{route('get.profiles')}}",
	type: "POST",
	data: {
		id: id,
		status: status,
		_token: '{{csrf_token()}}'
	},
	dataType: 'json',
	success: function (result) {
		$('#profiles').html('<option value="">-- Select --</option>');
		$.each(result, function (key, value) {
			$("#profiles").append('<option value="' + value
				.name + '">' + value.name + '</option>');
		});
	}
});
});
$(document).ready(function() {
	$("#getExceedDataForm").submit(function() {
		$('#table1').dataTable().fnDestroy();
		$('#example-1_tbody').html('<tr><td colspan="8">loading...</td></tr>');
		$.ajax({
			type: "POST",
			url: "{{route('users.billing.data_exceed_consumers_list')}}",
			data:$("#getExceedDataForm").serialize(),
			success: function (data) {
				$('#example-1_tbody').html(data);
				$('#table1').dataTable();
// alert(data);
},
error: function(jqXHR, text, error){
}
});
		return false;
	});
});
});
</script>
<script>
	$(document).on('click','.showGraph',function(){
		var username = $(this).attr('data-username');
		$('#username_for_graph').val(username);
		live_data_usage_graph();
		$('#graphModal').modal('show');
	});
	function live_data_usage_graph(){
		var username = $('#username_for_graph').val();
		$.ajax({
			method: 'POST',
			dataType : 'json',
			url: "{{route('users.user_graph.user_data_usage_graph')}}",
			data: {
				_token: '{{csrf_token()}}',
				username : username,
			},
			success: function(res){
				var downloadDataArray = [];
				var uploadDataArray = [];
				$.each(res.downloadDate, function( index, value ) {
					downloadDataArray[index] = { label: value, y: res.downloadData[index] };
				});
				$.each(res.uploadDate, function( index, value ) {
					uploadDataArray[index] = { label: value, y: res.uploadData[index] };
				});
				var chart = new CanvasJS.Chart("chartContainer", {
					title: {
						text: "Currently 'Internet Data' Utilization"
					},
					theme: "light2",
					animationEnabled: false,
					toolTip:{
						shared: true,
						reversed: true
					},
					axisY: {
						title: "Data in MB",
						suffix: " MB"
					},
					legend: {
						cursor: "pointer",
						itemclick: toggleDataSeries
					},
					data: [
					{
						type: "line",
						name: "Download",
						showInLegend: true,
						yValueFormatString: "#,##0.00 MB",
						dataPoints: downloadDataArray
					},
					{
						type: "line",
						name: "Upload",
						showInLegend: true,
						yValueFormatString: "#,##0.00 MB",
						dataPoints: uploadDataArray
					}
					]
				});
				chart.render();
			},
			complete: function(){
				setInterval(function(){ live_data_usage_graph(); }, 20000);	
			}   
		});
	}
	function toggleDataSeries(e) {
		if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
			e.dataSeries.visible = false;
		} else {
			e.dataSeries.visible = true;
		}
		e.chart.render();
	}
</script>
@endsection
<!-- Code Finalize -->