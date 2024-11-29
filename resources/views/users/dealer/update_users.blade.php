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
	<!-- Month Picker Start -->
	<script src="https://jsuites.net/v4/jsuites.js"></script>
	<link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css" />
	<!-- Month Picker End -->
	<style type="text/css">
		.input-group-addon.primary {
			color: rgb(255, 255, 255);
			background-color: rgb(50, 118, 177);
			border-color: rgb(40, 94, 142);
		}
		.input-group-addon.success {
			color: rgb(255, 255, 255);
			background-color: rgb(92, 184, 92);
			border-color: rgb(76, 174, 76);
		}
		.input-group-addon.info {
			color: rgb(255, 255, 255);
			background-color: rgb(57, 179, 215);
			border-color: rgb(38, 154, 188);
		}
		.input-group-addon.warning {
			color: rgb(255, 255, 255);
			background-color: rgb(240, 173, 78);
			border-color: rgb(238, 162, 54);
		}
		.input-group-addon.danger {
			color: rgb(255, 255, 255);
			background-color: rgb(217, 83, 79);
			border-color: rgb(212, 63, 58);
		}
		.btn.btn-secondary.assign.active{
			background-color: green
		}
		.btn.btn-secondary.removed.active{
			background-color: red
		}
		.btn.btn-secondary.assign:hover{
			background-color:green;
		}
		.btn.btn-secondary.removed:hover{
			background-color:red;
		}
		#cons_base_price{
			display:none;
		}
		a:hover, a:focus {
			color: #ddd;
		}
		.ne_wrapper{
			align-items: center;
			justify-content: flex-start;
			display: none;
		}
		.ne_wrapper.visible{
			display: flex
		}
		.jcalendar-controls {
			display: none;
		}
		.toggle-label {
			position: relative;
			display: block;
			width: 140px;
			height: 40px;
			margin-top: 5px;
			border: 1px solid #808080;
			margin-left: 20px;
			cursor: pointer;
			border-radius:5px;
		}
		.toggle-label input[type=checkbox] { 
			opacity: 0;
			position: absolute;
			width: 100%;
			height: 100%;
		}
		.toggle-label input[type=checkbox]+.back {
			position: absolute;
			width: 100%;
			height: 100%;
			background: #ed1c24;
			transition: background 150ms linear;  
		}
		.toggle-label input[type=checkbox]:checked+.back {
			background: #00a651;
		}
		.toggle-label input[type=checkbox]+.back .toggle {
			display: block;
			position: absolute;
			content: ' ';
			background: #fff;
			width: 50%; 
			height: 100%;
			transition: margin 150ms linear;
			border: 1px solid #808080;
			border-radius: 0px;
		}
		.toggle-label input[type=checkbox]:checked+.back .toggle {
			margin-left: 69px;
		}
		.toggle-label .label {
			display: block;
			position: absolute;
			width: 50%;
			color: #ddd;
			line-height: 25px;
			text-align: center;
			font-size: 1em;
		}
		.toggle-label .label.on { left: 0px; }
		.toggle-label .label.off { right: 0px; }
		.toggle-label input[type=checkbox]:checked+.back .label.on {
			color: #fff;
		}
		.toggle-label input[type=checkbox]+.back .label.off {
			color: #fff;
		}
		.toggle-label input[type=checkbox]:checked+.back .label.off {
			color: #ccc;
		}
		.edit__btn {
			background-color: transparent;float:right; margin-right: 5px
		}
	</style>
	@endsection
	@section('content')
	@php
	$mob = '';
	$nic = '';
	$ntn = '';
	$passport = '';
	$overseas = '';
	$verified = '';
	$isverify = App\model\Users\UserVerification::where('username',$user->username)->select('mobile_status','cnic','ntn','overseas','intern_passport')->get();
	foreach ($isverify as $value) {
		$mob = $value['mobile_status'];
		$nic = $value['cnic'];
		$ntn = $value['ntn'];
		$passport = $value['intern_passport'];
		$overseas = $value['overseas'];
	}
	if($nic != ''){
		$verified = $nic;
	}elseif($ntn != ''){
		$verified = $ntn;
	}elseif($overseas != ''){
		$verified = $overseas;
	}elseif($passport != ''){
		$verified = $passport;
	}
	@endphp
	<div class="page-container row-fluid container-fluid">
		<section id="main-content">
			<section class="wrapper main-wrapper row">
				<div class="header_view">
					<h2>Update Consumer
						<span class="info-mark" onmouseenter="popup_function(this, 'update_consumer_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<div class="">
					<section class="box ">
						<header class="panel_header">
							@include('users.layouts.session')
						</header>
						<div class="content-body">
							<form id="general_validate"	action="{{route('users.user.update',['status' => 'user','id' => $id])}}" method="POST" >
								@csrf
								<input type="hidden" name="url" value="{{$url}}">
								<div class="row">
									<div class="col-lg-3 col-md-4">
										<input type="hidden" value="{{$user->manager_id}}" class="form-control" placeholder="Manager (ID)" readonly >
										<input type="hidden" value="{{$user->resellerid}}" class="form-control" placeholder="Reseller (ID)" readonly >
										<input type="hidden" value="{{$user->dealerid}}" class="form-control" placeholder="Contractor (ID)" readonly >
										<input type="hidden" value="{{$user->sub_dealer_id}}" class="form-control" placeholder="Trader (ID)" readonly  >
										<div class="form-group position-relative">
											<label  class="form-label">Username <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'pppoe_consumer_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" value="{{$user->username}}" class="form-control"  placeholder="username" readonly>
										</div>
										<div class="form-group position-relative">
											<label  class="form-label">First Name <span style="color: red">*</span></label>
											@if(empty($verified))
											<button type="button" class="btn edit__btn" onclick="editFunction('fname');"><i class="fa fa-edit"></i></button>
											@endif
											<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" name="fname" id="fname" value="{{$user->firstname}}" class="form-control"  placeholder="First Name" readonly>
										</div>

										<div class="form-group position-relative">
											<label  class="form-label">Last Name <span style="color: red">*</span></label>
											@if(empty($verified))
											<button type="button" class="btn edit__btn" onclick="editFunction('lname');"><i class="fa fa-edit"></i></button>
											@endif
											<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" name="lname" id="lname" value="{{$user->lastname}}" class="form-control"  placeholder="Last Name" readonly>
										</div>
										<div class="form-group position-relative">
											<label for="validate-nic">CNIC Number <span style="color: red">*</span></label>
											@if(empty($verified))
											<button type="button" class="btn edit__btn" onclick="editFunction('nic');"><i class="fa fa-edit"></i></button>
											@endif
											<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<div class="input-group" data-validate="nic">
												<input type="hidden" name="" id="nicvariCode" value="{{$verified}}">
												<input type="text" class="form-control" name="nic" id="nic" value="{{$user->nic}}"  required readonly>
												<span class="input-group-addon @if(empty($verified)) danger @else success @endif">
													@if(empty($verified))
													Unverified
													@else
													Verified
													@endif
												</span>
											</div>
										</div>
										<div class="form-group position-relative">
											<label  class="form-label">Email Address <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="email" name="mail" value="{{$user->email}}" class="form-control"  placeholder="info@blinkbroadband.pk" required>
										</div>
									</div>
									<div class="col-lg-3 col-md-4">
										<div class="form-group position-relative">
											<label  class="form-label">Residential Address <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" name="address" value="{{$user->address}}" class="form-control"  placeholder="Office No# E-1 Karachi." required>
										</div>
										<div class="form-group position-relative">
											<label for="validate-phone">Mobile Number <span style="color: red">*</span></label>
											@if(empty($verified))
											<button type="button" class="btn edit__btn" onclick="editFunction('validate-phone');"><i class="fa fa-edit"></i></button>
											@endif
											<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<div class="input-group" data-validate="phone">
												<input type="hidden" name="" id="variCode" value="{{$mob}}">
												<input type="text" class="form-control" name="mobile_number" id="validate-phone"  data-mask="9999 9999999" value="{{$user->mobilephone}}" required readonly>
												<span class="input-group-addon @if(empty($mob)) danger @else success @endif">
													@if(empty($mob))
													Unverified
													@else
													Verified
													@endif
												</span>
											</div>
										</div>
										<div class="form-group position-relative">
											<label  class="form-label">Landline# | Mobile#2 <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" name="land_number" value="{{$user->homephone}}" class="form-control">
										</div>
										<div class="form-group position-relative">
											<label  class="form-label">Passport Number</label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'passport_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" class="form-control"  placeholder="AB1234567"readonly>
										</div>
										<div class="form-group position-relative">
											<label  class="form-label">Mac Address</label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'mac_address_view');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<input type="text" class="form-control" value="<?= $userRadCheck->value;?>"   readonly>
										</div>
									</div>
									<div class="col-lg-3 col-md-4">
										@if(Auth::user()->status != "trader")
										<!-- Billing Working -->
										<div class="form-group position-relative"> 
											<label  class="form-label" >Select Internet Profile <span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'select_internet_profile');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<select class="form-control profile_amount" name="profile" id="profile">
												<option value="">Select Internet Profile</option>
												@php
												$status = Auth::user()->status;
												if(Auth::user()->status == "dealer"){
													$profileList = App\model\Users\DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->orderby('groupname')->get();
													if(!empty($user->name)){
														$get_dealer_rate = App\model\Users\DealerProfileRate::where(['dealerid' => Auth::user()->dealerid, 'name' => $user->name ])->first();
													}
												}
												else{
													$profileList = App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => Auth::user()->sub_dealer_id])->orderby('groupname')->get();
													if(!empty($user->name)){
														$get_dealer_rate = App\model\Users\SubdealerProfileRate::where(['name' => $user->name, 'dealerid' => Auth::user()->dealerid, 'sub_dealer_id' => Auth::user()->sub_dealer_id])->first();
													}
												}
												@endphp
												@foreach($profileList as $data)
												@php $name=$data->name;
												@endphp
												<option value="{{$data->name}}" data-status="{{$status}}" @if($user->name === $name ) selected @endif>{{ucfirst($name)}}</option>
												@endforeach
											</select>
										</div>
										<?php
										$companyDiv = 'block';		
										$consumerDiv = 'none';	
										$consumer_price = 0;
										if($get_dealer_rate){
											if($user->profile_amount > $get_dealer_rate->rate){
												$companyDiv = 'none';		
												$consumerDiv = 'block';
												$consumer_price = $user->profile_amount;	
											}
										}
										?>	
										<div class="form-group position-relative" id="cons_base_price" style="display:<?= $consumerDiv;?>">
											<label  class="form-label">Consumer Base Price (PKR)<span style="color: red">*</span></label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_base_price_update_page');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<div class="input-group">
												<input type="number" class="form-control" name="base_price" id="base_price"  step="0.01" value="<?= $consumer_price;?>">
												<span class="input-group-addon primary"><a href="#tax-calculator" data-toggle="modal" style="color: white"><i class="fa fa-calculator"></i> Tax Calculator </a></span>
												@error('base_price')
												<span class="text text-danger">{{ $message }}</span>
												@enderror
											</div>
										</div>
										@endif
										<div class="form-group" id="input_comp_rate" style="display:<?= $companyDiv;?>">
											<div class="input-group">
												<input type="text" class="form-control" id="" value="Company Rate" name="company_rate" readonly >
												<span class="input-group-addon primary"><a href="javascript:void(0);" onclick="showFunction();"> Click to Change </a></span>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-4">
										<div class="form-group position-relative">
											<label >Static IP</label>
											<span class="helping-mark" onmouseenter="popup_function(this, 'static_ip_assign_conusmer_update');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
											<div class="btn-group btn-group-toggle" style="width:100%" data-toggle="buttons" id="ipassign">
												<?php if(!$staticIp){ ?>
													<label class="btn btn-secondary assign" style="width:100%">
														<input type="radio" name="ipassign" value="assign"  autocomplete="off"> Process 
													</label>
												<?php }else{ ?>
													<label class="btn btn-secondary removed" style="width:100%" id="ipremove">
														<input type="radio" name="ipassign" value="remove"  autocomplete="off"> Remove IP: 
														<?php if($staticIp){?>
															<label style="margin-bottom:0px;"> {{$staticIp->ipaddress}}</label>
														<?php } ?>
													</label>
												<?php } ?>
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-4">
										@if(Auth::user()->status != "subdealer" && Auth::user()->status != "trader")
										<div class="form-group" id="ip_wrapper" style="display:none">
											<label  class="form-label">Select Static (IP)</label>
											<select class="form-control" name="static_ip" id="select_static_ip">
												<option value="">None</option>
												<?php if($staticIp){?>
													<option value="{{$staticIp->ipaddress}}" selected>{{$staticIp->ipaddress}} [{{$staticIp->type}}]</option>	
												<?php }else{?>
													@foreach($serverip as $data)
													<option value="{{$data->ipaddress}}">{{$data->ipaddress}} [{{$data->type}}]</option>
													@endforeach 
												<?php } ?>
											</select>
										</div>
										@endif
										<?php
										if(!empty($user->static_ip_amount)){
											$staticIPMinRate = $user->static_ip_amount;
										}else{
											$staticIPMinRate = @$staticIpRate->rates;
										}
										?>
										<div class="form-group" id="ip_rate_wrapper" style="display:none">
											<label  class="form-label">Assign Static IP Rates (PKR) <span style="color: red">*</span></label>
											<div class="input-group">
												<input type="number" class="form-control" id="input_ip_rates" value="Company Rate" name="static_ip_amount" readonly placeholder="Company Rate" >
												<span class="input-group-addon primary"><a href="javascript:void(0);" onclick="changeFunction();"> Click to Change </a></span>
											</div>
										</div>
										@php
										$time=strtotime(date('Y-m-d'));
										$month=date("m",$time);
										$year=date("Y",$time);
										$toMonth = $year.'-'.$month;
										$date = '';
										$expire = App\model\Users\UserStatusInfo::where(['username' => $user->username])->first();
										$allow = App\model\Users\DealerProfileRate::where(['dealerid' => Auth::user()->dealerid])->first();
										$allowprofile = $allow['changeprofile'];
										$date = $expire['expire_datetime'];
										if($user->never_expire == 'yes'){
											$condition='checked';
										}else{
											$condition='';
										}
										if($user->taxprofile == 'yes')
										{
											$condition1='checked';
										}else{
											$condition1='';
										}
										// Allow never Expire Check
										$isSetNeverExpre = App\model\Users\UserInfo::where('dealerid',Auth::user()->dealerid)->where('status','dealer')->select('never_expire')->first();
										@endphp
										<div class="col-sm-6" style="display: {{$isSetNeverExpre['never_expire'] == 'yes' ? 'block' : 'none'}}">
											<div class="form-group" style="width: 15% !important;display: inline-table;margin: 5px;">
												<label  class="form-label">NeverExpire</label>
												<input style="margin-left: 10px;" type="checkbox" <?php echo $condition; ?> name="neverexpire" value="yes" id="neverexpire"> 
												<input type="month" id="nextexpire" name="nextexpire" min="{{$toMonth}}" style="display: none">
											</div>
										</div>
										<div class="col-sm-6">
											@if($allowprofile == 'yes' && $date < date('Y-m-d 12:00:00'))
											<label  class="form-label">ChangeProfile</label>
											<div class="form-check" align="center">
												<input type="checkbox" <?php echo $condition1; ?> name="taxprofile" value="yes" style="width: 20px;height: 20px;" > 
											</div>
											@endif
										</div>
										<div class="col-md-12">
											@php
											$updateProfile = App\model\Users\RaduserGroup::where('username',$user->username)->first();
											$newProfile = $updateProfile->groupname;
											$packages = ['ghaznavi','shaheen','hatf','ghauri'];
											@endphp
											@if(in_array($user->name,$packages))
											<label  class="form-label">Pure-{{$user->name}}</label>
											<div class="form-check">
												<input type="checkbox" data-name="{{$user->name}}" data-username="{{$user->username}}" {{$newProfile == $user->profile.'-p' ? 'checked' : ''}}  name="cirprofile" id="cirprofile" value="{{$user->profile}}" style="width: 15px;height: 20px;">
											</div>
											@endif
										</div>
									</div>
									<div class="col-xs-12">
										<div class="row">
											<div class="col-md-6">
												<?php if($updated_by){?>
													Last updated by : <?= ucfirst($updated_by->username).' on '.date('M d,y H:i',strtotime($user->updated_on));
												} ?>
											</div>	
											<div class="col-md-6">
												<div class="pull-right ">
													<button type="submit" class="btn btn-primary">Update</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</section></div>
					<?php
					$nevereExpireStatus = NULL;
					$nevereExpireDate = NULL;
					$nevereExpireLastUpdatDate = NULL;
					$neverExpireBtn = 'hide'; 
					$panelOf = $user->dealerid;
					if(!empty($user->sub_dealer_id)){
						$panelOf = $user->sub_dealer_id;
					}
					if(!empty($nevereExpireInfo)){
						$nevereExpireStatus = $nevereExpireInfo->status;
						$nevereExpireDate = $nevereExpireInfo->date;
						$nevereExpireLastUpdatDate = $nevereExpireInfo->updated_at;
					}
					$invalidProfile = array('NEW','DISABLED','EXPIRED','TERMINATE');
					if(!in_array($user->name, $invalidProfile) && \App\MyFunctions::check_access('Never Expire Consumers',Auth::user()->id) ){
						$neverExpireBtn = 'show';
					}
					?>
					<?php if(\App\MyFunctions::check_access('Never Expire Consumers',Auth::user()->id) && ($panelOf == Auth::user()->username) ){ ?>
						<section class="box">
							<div class="content-body">
								<div style="display:flex;align-items:center;">
									<h3 class="mb-2">Never Expire &nbsp;</h3>
									<?php if($neverExpireBtn == 'show'){?>
										<label class='toggle-label'>
											<input type='checkbox' id="expire_cb" <?= ($nevereExpireStatus == 'enable') ? 'checked' : 'unchecked';?> data-username="{{$user->username}}"/>
											<span class='back'>
												<span class='toggle'></span>
												<span class='label on'>Enable</span>
												<span class='label off'>Disable</span>  
											</span>
										</label>
									<?php } else{ echo ' <span class="badge badge-warning"> Select valid profile first to access never expire </span>';}?>
								</div>
								<div class="ne_wrapper <?= ($nevereExpireStatus == 'enable' && $neverExpireBtn == 'show' ) ? 'visible' : '';?>">
									<div class="form-group position-relative" style="width: 400px">
										<label  class="form-label">Select Month</label>
										<input type="text" id="month_input" value="<?= $nevereExpireDate;?>" name="never_expire_month" class="form-control month-input">
										<span>Expiry Date <?= $expireInfo->card_expire_on;?></span>
										<span style="float:right">Last Update <?= $nevereExpireLastUpdatDate;?></span>
									</div>
									<div style="margin-left: 20px;margin-top:-10px">
										<button type="button" onclick="saveNeverExpireMonth();" class="btn btn-primary">Apply</button>
									</div>
								</div>					
								<span id="neverExpireOutMsg"></span>	
							</div>
						</section>
					<?php } ?>
				</section>
			</section>
			<!-- CONTENT END -->
		</div>
		<script>
			function editFunction(id) {
				document.getElementById(id).removeAttribute('readonly');
			}
			function saveNeverExpireMonth(){
				var nemonth = $('#month_input').val();
				var username = $('#expire_cb').attr('data-username');
				enableDisableNeverExpire(username,'enable',nemonth);
			}
			function enableDisableNeverExpire(username,status,month) {
				$.ajax({
					url: "{{route('users.never_expire_update')}}",
					method:"post",
					data:{username:username,status:status,month:month},
					success: function(data){
						$('#neverExpireOutMsg').html(data);
						if(data.includes("Updated")){
							alert(data);
							location.reload(); 
						}
					}
				});
			}
		</script>
		<script>
			$(window).on("load", function() {
				let monthCalendar = document.getElementById('month_input');
				if(monthCalendar) {
					jSuites.calendar(document.getElementById('month_input'),{
						type: 'year-month-picker',
						format: 'YYYY-MM'
					});
				}
			})
// Never Expire Checkbox 
			$('#expire_cb').on('change', function() {
				var ischecked = $(this).is(":checked");
				var username = $(this).attr("data-username");
				var status = '';
				if (ischecked) {
					$('.ne_wrapper').addClass('visible');
					status = 'enable';
				}else{
					$('.ne_wrapper').removeClass('visible');
					status = 'disable';
				}
				enableDisableNeverExpire(username,status,null);
			})
			function changeFunction() {
				$('#input_ip_rates').prop('readonly', false)
				$('#input_ip_rates').val('');
			}
			function showFunction() {
				$('#cons_base_price').css('display', 'block');
				$('#input_comp_rate').css('display', 'none');
			}
			$(function(){
				$('input[name=ipassign]').on('change', function(){
					var value = $(this).val();
					if(value === 'assign'){
						$('#ip_wrapper').css('display', 'block');
					}
				})
			})
			$(function() {
				$('#select_static_ip').on('change', function() {
					var value = $("#select_static_ip option:selected").val();
					if(value){
						$('#ip_rate_wrapper').css('display', 'block');
						$('#input_ip_rates').prop('readonly', true);
						$('#input_ip_rates').val('');
					}else{
						$('#input_ip_rates').prop('readonly', true);
						$('#input_ip_rates').val('');
						$('#ip_rate_wrapper').css('display', 'none');
					}
				})
			})
			$('.profile_amount').on('change', function() {
				$('#cons_base_price').css('display', 'none');
				$('#input_comp_rate').css('display', 'block');
				$('#base_price').val(0);
			});
		</script>
		@endsection
		@include('users.tax-calculator.tax-calculator-modal')
		@section('ownjs')
		<script type="text/javascript">
			$(document).ready(function() {
				$(".add-more").click(function(){
					var html = $(".copy").html();
					$(".after-add-more").after(html);
				});
				$("body").on("click",".remove",function(){
					$(this).parents(".control-group").remove();
				});
			});
		</script>
		<script>
			$(document).ready(function() {
				$('.input-group input[required], .input-group textarea[required], .input-group select[required]').on('keyup change', function() {
					var $form = $(this).closest('form'),
					$group = $(this).closest('.input-group'),
					$addon = $group.find('.input-group-addon'),
					$icon = $addon.find('span'),
					state = false;
					$code = document.getElementById('variCode').value;
					$codenic = document.getElementById('nicvariCode').value;
					if($group.data('validate') == 'phone' && $code != 0) {
						state = true;
					}
					if($group.data('validate') == 'nic' && $codenic != '') {
						state = true;
					}
					if (state) {
						$addon.removeClass('danger');
						$addon.addClass('success');
						$addon.html('Varified <span class="glyphicon glyphicon-ok"></span>');
					}
				});
				$('.input-group input[required], .input-group textarea[required], .input-group select[required]').trigger('change');
			});
		</script>
		<script>
			$("#neverexpire").on('click', function () {
				var checkbox_value = "";
				var ischecked = $(this).is(":checked");
				if (ischecked) {
					$('#nextexpire').show();
					$('#nextexpire').attr('required', true);
				}else{
					$('#nextexpire').hide();
					$('#nextexpire').attr('required', false);
				}
			});
		</script>
		<script>
			$("#cirprofile").on('click', function () {
				var ischecked = $(this).is(":checked");
				var profile = $(this).val();
				var username = $(this).attr('data-username');
				var name = $(this).attr('data-name');
				$.ajax({
					url: "{{route('users.cirProfile')}}",
					method:"post",
					data:{check:ischecked,profile:profile,username:username,name:name},
					success: function(data){
					}
				});
			});
		</script>
		@endsection
<!-- Code Finalize -->