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
	#loadingnew{
		display: none;
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
						<h2>Set Margin
							<span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<div class="col-lg-2"></div>
					<div class="col-lg-8" >
						<section class="box ">
							<div class="content-body">
								<div class="row" style="padding-top: 20px;">
									<div class="col-md-2"></div>
									<div class="col-md-8 col-sm-12 col-xs-12">
										<label >Add To Acc:</label>
										<div class="form-group">
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn btn-secondary">
													<input type="radio" name="options"  value="dealer" id="option2"  autocomplete="off" >Dealer
												</label>
												<label class="btn btn-secondary">
													<input type="radio" name="options" value="subdealer" id="option3" autocomplete="off"> Sub Dealer
												</label>
												<label class="btn btn-secondary">
													<input type="radio" name="options" value="trader" id="option4" autocomplete="off"> Trader
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-2"></div>
									<form id="marginForm">
										<div class="col-md-8">
											<div class="form-group">
												<label  class="form-label">Selected User</label>
												<select class="form-control" name="selectData" required id="selectData">
													<option value="">Select Data</option>
												</select>
											</div>
										</div>
										<div class="col-md-12" id="marginTable" style="display: none">
											<table class="table table-hover table-striped table-bordered">
												<thead>
													<tr>
														<th>Profile</th>
														<th>Margin</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
										<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px;">
											<div class="form-group">
												<button type="submit" id="submit" class="btn btn-success pull-right">Submit</button>
											</div>
										</div>
									</form>
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
	@section('ownjs')
	<script>
		$('input:radio[name="options"]').change(
			function(){
				var status = $("input[name='options']:checked").val();
				$.ajax({
					type: "POST",
					url : "{{route('users.billing.MarginUserStatus')}}",
					data: {"_token": "{{ csrf_token() }}",status:status},
// dataType: "json",
success: function(data){
	$('#selectData').html('');
	$('#selectData').html('<option>Select Data</option>');
	if(status == 'dealer'){
		$.each(data,function(index,item){
			$('#selectData').append('<option value='+item.dealerid+'>'+item.dealerid+'</option>');
		});
	}else if(status == 'subdealer'){
		$.each(data,function(index,item){
			$('#selectData').append('<option value='+item.sub_dealer_id+'>'+item.sub_dealer_id+'</option>');
		});
	}else{
		$.each(data,function(index,item){
			$('#selectData').append('<option value='+item.trader_id+'>'+item.trader_id+'</option>');
		});
	}
}
});
			});
		</script>
		<script>
			$("#selectData"). change(function(){
				var selectedname = $("#selectData option:selected").val();
				var status = $("input[name='options']:checked").val();
				$.ajax({
					type: "POST",
					url : "{{route('users.billing.updateMigrateData')}}",
					data: {"_token": "{{ csrf_token() }}",selectedname:selectedname,status:status},
// dataType: "json",
success: function(data){
// alert(data);
$('#marginTable').show();
$('tbody').html(data);
}
});
			});
		</script>
		<!--  Update Comp Nature Script-->
		<script>
			$('#marginForm').submit(function(e){
				$.ajax({
					url: "{{route('users.billing.updateMarginDB')}}",
					type: "POST",
					data : $('#marginForm').serialize(),
					success: function(data){
						alert('done');
						location.reload();
					}
				});
				e.preventDefault();
			});
		</script>
		@endsection
<!-- Code Finalize -->