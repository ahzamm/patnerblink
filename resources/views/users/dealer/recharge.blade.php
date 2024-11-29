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
	table.tax_table{
		margin-top: 0 !important;
	}
	.tax_table td{
		border: 1px solid #000;
	}
	.tax_table>thead>tr>th{
		position: sticky;
		top: 0;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Single recharge account
					<span class="info-mark" onmouseenter="popup_function(this, 'singal_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<div id="returnMsg"></div>
			<section class="box">
				<div class="content-body">
					<div class="row" style="padding-top:30px">
						<form  id="singleRechargeForm">
							@csrf
							<div class="col-md-6">
								<div class="form-group position-relative">
									<label for="form-control">Select Internet Profile <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_internet_profile');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select name="profileGroupname" onchange="onProfileChange(this)" class="js-select2" required>
										<option value="">Select Internet Profile</option>
										@foreach($profileRates as $profile)
										<option value="{{@$profile->profile->name}}">{{@$profile->profile->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group position-relative">
									<label for="form-control">Select Consumer (ID) <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_consumer_ID');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select id="select-username" name="username" class="form-control" required>
										<option value="">First Select Internet Profile</option>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group pull-right">
									<button type="submit" class="btn btn-primary " id="chargeBtn">Recharge Now</button>
									<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
								</div>
							</div>
						</form>
					</div>
				</div>
			</section>
			<section class="box">
				<div class="header_view" style="padding-top: 20px;">
					<h2 style="font-size: 26px;">Recently Recharged Accounts <small style="color: ligtgray">(History)</small>
						<span class="info-mark" onmouseenter="popup_function(this, 'recently_recharged_accounts_history');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<hr>
				<div class="content-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered dataTables_filter dt-responsive " id="example-1" style="width:100%">
								<thead>
									<tr>
										<th>Serial#</th>
										<th>Consumer (ID)</th>
										<th class="desktop">Contractor (ID)</th>
										<th>Internet Profile Name</th>
										<th class="desktop">Expiry (Date & Time)</th>
										<th>Invoice</th>
									</tr>
								</thead>
								<tbody>
									@php
									$count=1;
									@endphp
									@foreach($rechargeUsers as $data)
									@php
									if(Auth::user()->status == "dealer"){
									$rate= $data->dealerrate;
								}else{
								$rate= $data->subdealerrate;
							}
							$userProfile = App\model\Users\Profile::where('groupname',$data->profile)->first()->name;
							$userStatus = App\model\Users\UserStatusInfo::where('username',$data->username)->first()->card_expire_on;
							@endphp
							<tr>
								<td>{{$count++}}</td>
								<td class="td__profileName">{{$data->username}}</td>
								<td>{{$data->dealerid}}</td>
								<td>{{$userProfile}}</td>
								<td>{{$userStatus}}</td>
								<!--  Blling Processing -->
								@php	
								$userStatus = App\model\Users\UserStatusInfo::where('username',$data->username)->first()->card_charge_on;
								$get_invoice =App\model\Users\AmountBillingInvoice::where(['username'=>$data->username,'date'=>$userStatus])->latest('charge_on')->first();
								@endphp
								<?php
								$show_invoice = 0;
								$reseller_inv_status = App\model\Users\UserInfo::where('resellerid',Auth::user()->resellerid)->first()->allow_invoice;
								$dealer_inv_status = App\model\Users\UserInfo::where('dealerid',Auth::user()->dealerid)->first()->allow_invoice;
								$subdealer_inv_status = App\model\Users\UserInfo::where('sub_dealer_id',Auth::user()->sub_dealer_id)->first()->allow_invoice;
								if(Auth::user()->status == 'dealer'){
									if($reseller_inv_status == 1 && $dealer_inv_status == 1){
										$show_invoice = 1;
									}
								}else if(Auth::user()->status == 'subdealer'){
									if($reseller_inv_status == 1 && $dealer_inv_status == 1 && $subdealer_inv_status == 1){
										$show_invoice = 1;
									}
								}
								?>
								<td>
									<?php if($show_invoice == 1){ ?>
										<a href="{{url('/users/bill/view').'/'.$get_invoice->username.'/'.$get_invoice->date}}"  target="_blank" class="btn btn-default btn-xs" style="color:red;border:none"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red"></i> Invoice</a>
									<?php }else{ echo 'Not Available';} ?>
								</td>
								<!-- CONTENT END -->
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</section>
</section>
</section>
<!-- Processing -->
<div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
		<div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
			<div class="modal-body">
				<center><h1 style="color:white;">Processing....</h1>
					<p style="color:white;">please wait.</p>
				</center>
			</div>
		</div>
	</div>
</div>  
@endsection
@section('ownjs')
<script type="text/javascript">
	function onProfileChange(profileGroupName){
		profileGroupName = profileGroupName.value
		console.log("profileGroupName: " + profileGroupName);
		$.post(
			"{{route('users.ajax.charge.profileGroupWiseUsers')}}",
			{
				"profileGroupName" : ""+profileGroupName+""
			},
			function(data){
				console.log(data);
				let content = "<option value=''>Select Username</option>";
				$.each(data,function(i,user){
					content += "<option value="+user.username+">"+user.username+"</option>";
				});
				$("#select-username").empty().append(content);
			});
	}
	$(document).ready(function() {
		var table = $('#mytable').DataTable();
	} );
</script>
<script type="text/javascript">
	$("#singleRechargeForm").submit(function() {
// 
if(confirm("Do your really want to recharge ?")){
	$('#processLayer').modal('show');
//
$.ajax({ 
	type: "POST",
	url: "{{route('users.single.recharge')}}",
	data:$("#singleRechargeForm").serialize(),
	success: function (data) {
		$('html, body').scrollTop(0);
		$('#processLayer').modal('hide');
		$('#returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
		setTimeout(function() { 
		}, 2000);
	},
	error: function(jqXHR, text, error){
		$('html, body').scrollTop(0);
		$('#returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
		$('#processLayer').modal('hide');
	},
	complete:function(){
	},
});
}
return false;
})
</script>
@endsection
<!-- Code Finalize -->