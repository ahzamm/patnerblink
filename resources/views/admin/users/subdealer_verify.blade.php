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
		<section class="wrapper main-wrapper row">
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Computerised National Identity Card (CNIC) Verification <small>Trader</small>
							<span class="info-mark" onmouseenter="popup_function(this, 'cnic_verification_traders_approval');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<div>
						<section class="box" style="padding-top:20px">
							<div class="content-body">    <div class="row">
								<div class="col-xs-12">
									<div class="table-responsive">
										<table id="example-1" class="table table-bordered">
											<thead>
												<tr>
													<th>Serial#</th>
													<th>Reseller (ID)
													</th>
													<th>Contractor (ID)
													</th>
													<th>Trader (ID)
													</th>
													<th>Number of Consumers
													</th>
													<th>Apporved <span style="color: red">*</span></th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
												@php $sno = 1; @endphp
												@foreach($subdealerCollection as $subdealer)
												@php
												$status ='';
												$active1 ='';
												$active = App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => $subdealer->sub_dealer_id])->first();
												$status = @$active['verify'];
												if($status == "no"){
												$active1 ='Deactive';
											}else{
											$active1 ='Active';
										}
										@endphp
										<tr>
											<th scope="row">{{$sno++}}</th>
											<td>{{$subdealer->resellerid}}</td>
											<td>{{$subdealer->dealerid}}</td>
											<td class="td__profileName">{{$subdealer->sub_dealer_id}}</td>
											<td>{{DB::table('user_info')
												->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
												->where('user_status_info.card_expire_on', '>=', today())
												->where(['status' => 'user','resellerid' => $subdealer->resellerid,'sub_dealer_id' => $subdealer->sub_dealer_id])->count()}}</td>
												<td>
													<label class="switch" style="width: 46px;height: 19px;">
														<input type="checkbox" onchange="statChange(this, '{{$subdealer->sub_dealer_id}}')"   {{(@$active['verify'] == 'yes') ? 'checked' : ''}} >
														<span class="slider square" ></span>
													</label>
												</td>
												<td>{{$active1}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
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
@section('ownjs')
<script>
	function statChange(checkBox, sub_dealer_id){
		let isCheck = checkBox.checked;
		console.log("isCheck: " + isCheck);
// ajax call: jquery
$.post(
	"{{route('admin.approve.ajax.verify')}}",
	{
		"isChecked" : ""+isCheck+"",
		"sub_dealer_id" : sub_dealer_id
	},
	function(data){
		console.log(data);
	});
}
</script>
@endsection
<!-- Code Finalize -->