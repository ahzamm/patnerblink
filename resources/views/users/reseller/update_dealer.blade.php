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
		color: black !important;
		font-weight: bold !important;
	}
	textarea {
		resize: none;
	}
	.tax_payer{
		display:flex;
		place-content: center;
		text-align:center;
		column-gap: 2px;
	}
	.tax_payer input{
		display: none;
		cursor: pointer
	}
	.tax_payer label{
		background-color: gray;
		padding: 3px 10px;
		color: #fff;
		cursor: pointer;
		flex: 1 1 auto;
	}
	.tax_payer input:checked+.label_filer{
		background-color: green;
	}
	.tax_payer input:checked+.label_nonfiler{
		background-color: red;
	}
	.tax_payer input:checked+.label_none{
		background-color: red;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="header_view">
				<h2>Update Contractor
					<span class="info-mark" onmouseenter="popup_function(this, 'update_contractor_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box ">
				<header class="panel_header">
					@if(session('success'))
					<div class="alert alert-success alert-dismissible">
						{{session('success')}}
					</div>
					@endif
					@if(session('error'))
					<div class="alert alert-danger alert-dismissible">
						{{session('error')}}
					</div>
					@endif
				</header>
				<div class="content-body">
					<form id="general_validate"	action="{{route('users.user.update',['status' => 'dealer','id' => $id])}}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="col-lg-4 col-md-6">
								<input type="hidden" value="{{$dealer->manager_id}}"  name="managerid" class="form-control"  placeholder="Manager-ID" required readonly>
								<input type="hidden" value="{{$dealer->resellerid}}" name="resellerid" class="form-control"  placeholder="Reseller-ID" required readonly>
								<input type="hidden" value="{{$dealer->dealerid}}" name="dealerid" class="form-control"  placeholder="Contractor ID" required readonly>
								<div class="form-group position-relative">
									<label  class="form-label">Username <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'contractor_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->username}}" name="username" class="form-control"  placeholder="Username" required readonly>
								</div>
								<div class="form-group position-relative">
									<label  class="form-label" >First Name <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->firstname}}" name="fname" class="form-control" placeholder="First Name" required>
								</div>
								<div class="form-group position-relative">
									<label  class="form-label">Last Name <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->lastname}}" name="lname" class="form-control"  placeholder="Last Name" required>
								</div>
								<div class="form-group position-relative" style="position: relative">
									<label  class="form-label">National Tax Number (NTN) <span style="color: #0d4dab">(NTN)</span> <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'ntn_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->ntn_num}}" name="ntn_num" class="form-control" id="ntn_no" placeholder="Assign NTN" disabled>
									<div class="tax_payer">
										<input type="radio" id="filer" name="tax_amt" value="filer" class="tax_amount" required @if($dealer->is_filer === 'filer') checked  @endif>
										<label for="filer" class="label_filer">Filer</label>
										<input type="radio" id="nonfiler" name="tax_amt" value="non filer" class="tax_amount" required @if($dealer->is_filer === "non filer") checked  @endif>
										<label for="nonfiler" class="label_nonfiler">Non Filer</label>
										<input type="radio" id="none" name="tax_amt" value="none" class="tax_amount" required @if($dealer->is_filer === "none") checked  @endif>
										<label for="none" class="label_none">Not Applicable</label>
									</div>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->mobilephone}}" name="mobile_number"  data-mask="9999-9999999" class="form-control"   required>
								</div>
								<div class="form-group position-relative">
									<label  class="form-label">Landline Number <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->homephone}}" name="land_number"  class="form-control" data-mask="(999)9999999"  >
								</div>
								<div class="form-group position-relative">
									<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->nic}}" name="nic" class="form-control"  data-mask="99999-9999999-9" required>
								</div>
								<div class="form-group position-relative">
									<label  class="form-label">Email Address <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="email" value="{{$dealer->email}}" name="mail" class="form-control"  placeholder="info@gmail.com" required>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">Assigned Contractor Area <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'assgin_contractor_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$dealer->area}}" name="area" class="form-control"  placeholder="Assigned Contractor Area " required>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">Business Address <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<textarea  value="{{$dealer->address}}" name="address" class="form-control"  placeholder="Business Address" required>{{$dealer->address}} </textarea>
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">CNIC <span style="color: #0d4dab">(Front Image)</span> <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="file" name="cnic_front" class="form-control"  placeholder="">
								</div>
							</div>
							<div class="col-lg-4 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">CNIC <span style="color: #0d4dab">(Back Image)</span> <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="file" name="cnic_back" class="form-control"  placeholder="">
								</div>
							</div>
							@php
							$check = '';
							$verify = '';
							$payment = '';
							$trader = '';
							$discount = '';
							$data = App\model\Users\DealerProfileRate::where('dealerid',$dealer->dealerid)->select('show_sub_dealer','trader','allowplan','verify','changeprofile','payment_type')->first();
							$trader = @$data['trader'];
							$verify = @$data['verify'];
							$payment = @$data['payment_type'];
							$isSetDealerNeverExpire = App\model\Users\UserInfo::where('dealerid',$dealer->dealerid)->where('status','dealer')->select('never_expire')->first();
							$check = App\model\Users\UserAmount::where('username',$dealer->username)->first();
							$check = $check->isvisible;
							@endphp
							<div class="col-md-12">
								<hr>
								<div class="header_view">
									<h2 style="font-size: 26px;">Management</h2>
								</div>
								<div class="row">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" id="DDU" href="#menu5">Internet Profiles <span style="color: red">*</span></a></li>
										<li><a data-toggle="tab" href="#home">Static (IPs) Rates</a></li>
										<li><a data-toggle="tab" id="DDU" href="#menu7">Allow Access</a></li>
										<li><a data-toggle="tab" id="DDU" href="#creditlimit">Credit Limit <span style="color: red">*</span></a></li>
										<li><a data-toggle="tab" id="DDU" href="#billingtype">Billing Method <span style="color: red">*</span></a></li>
										<li><a data-toggle="tab" id="DDU" href="#taxsection" style="display:none;">Taxation Amount <span style="color: red">*</span></a></li>
										<li><a data-toggle="tab" id="DDU" href="#nas">Assign (NAS) <span style="color: red">*</span></a></li>
									</ul>
									<div class="tab-content">
										<div id="home" class="tab-pane fade ">
											<div style="overflow-x: auto;">
												<div class="row">
													<div class="col-lg-4 col-md-6">
														<div class="form-group position-relative">
															<label >Static (IPs) Company Price(PKR)</label>
															<span class="helping-mark" onmouseenter="popup_function(this, 'static_ip_rates');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
															<input type="text" value="{{$ip_amount}}" name="rates" id="iprates" min="0" class="form-control"  placeholder="0.00" required>
															<small> <p>Last Update: <span>{{$ip_update_on}}</span></p></small>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="form-group position-relative">
															<label >Static (IPs) Maximum Consumers Price(PKR)</label>
															<span class="helping-mark" onmouseenter="popup_function(this, 'static_ip_max_price_conusmers');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
															<input type="text" value="{{$ip_amount_max}}" name="rates_max" id="" min="0" class="form-control"  placeholder="0.00" required>
															<small> <p>Last Update: <span>{{$ip_update_on}}</span></p></small>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div id="creditlimit" class="tab-pane fade">
											<div class="row">
												<div class="col-lg-4 col-md-6">
													<div class="form-group position-relative" style="padding: 10px;">
														<label  class="form-label">Credit Limit (PKR) <span style="color: red">*</span></label>
														<span class="helping-mark" onmouseenter="popup_function(this, 'contractor_credit_limit');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
														<input type="text" value="{{number_format($userAmount->credit_limit)}}" name="limit" class="form-control"  placeholder="0.00" required>
														<small> <p>Last Update: <span>{{$userAmount->update_on}}</span></p></small>
													</div>
												</div>
											</div>
										</div>
										<div id="nas" class="tab-pane fade">
											<div class="row">
												<div class="col-lg-4 col-md-6">
													<div class="form-group position-relative" style="padding: 10px;">
														<label  class="form-label">Assigned (BRAS) NAS <span style="color: red">*</span></label>
														<span class="helping-mark" onmouseenter="popup_function(this, 'select_nas');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
														<select name="nas" id="nas" class="form-control" required>
															<option value="">Select (BRAS) NAS </option>
															<?php foreach($nas as $nasvalue){ ?>
																<option value="<?= $nasvalue->nas;?>" <?= (@$assignedNas[0]->nas == $nasvalue->nas) ? 'selected' : ''; ?>  ><?= $nasvalue->nas;?>
															</option>
														<?php } ?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<!-- Internet Profile -->
									<div id="menu5" class="tab-pane fade in active">
										<div class="row">
											<div class="">
												<div class="col-lg-3"style="padding-right: 0;">
													<div class="button-group" >
														<button type="button" class="btn dropdown-toggle dropdown__btn" data-toggle="dropdown">
															Select Internt Profiles <span style="color: red">*</span>
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu" style="max-height:250px;overflow-y:auto;max-width:100%;width: 93%;margin-left: 20px">
															@foreach($profileList as $profile_data)
															@php
															$profile_remove=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges1 = @$profile_remove['final_ratesE'];
															$charges12 = $charges1-50;
															$profile =ucwords($profile_data->name);
															$pro_rate=$profile_data->rate +1;
															$userName = Auth::user()->username;
															@endphp
															@if($profile != 'Lite' && $profile != 'Social' && $profile != 'Smart' && $profile != 'Super' && $profile != 'Turbo' && $profile != 'Mega' && $profile != 'Jumbo' && $profile != 'Sonic' )
															<li>
																<input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id,'{{$pro_rate}}','{{$charges12}}','{{$userName}}','{{$profile_data->rate}}')"  style="height: 16px;width: 16px;margin:5px 5px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif/>&nbsp;{{$profile}}
															</li>
															@endif
															@endforeach
														</ul>
													</div>
												</div>
												{{-- <div class="col-lg-10 " style="height: 155px; overflow: auto;" > --}}
													<div class="col-lg-9 " style="overflow: auto; padding-left: 0" >
														<center>
															<table class="table table-responsive table-bordered" >
																<thead class="thead table-striped">
																	<tr>
																		<th scope="col">Internet Profile Name</th>
																		<th scope="col">Internet Profile Rates (PKR) (Excluding Tax) <span style="color: red">*</span></th>
																		<th scope="col">Internet Company Profile Rates (PKR) (Including Tax) <span style="color: red">*</span></th>
																		<th scope="col">Company Consumer Base Price (PKR) <span style="color: red">*</span></th>
																		<th scope="col">Commission <span style="color: red">*</span></th>
																	</tr>
																</thead>
																<tbody class="tbody">
																	@foreach($assignedProfileRates as $profileRate)
																	@php
																	$dealerrate=App\model\Users\ResellerProfileRate::where(['resellerid' => $dealer->resellerid])->where(['groupname' => $profileRate->groupname])->first();
																	///
																	$sdealerrate=App\model\Users\DealerProfileRate::where(['dealerid' => $dealer->dealerid])->where(['groupname' => $profileRate->groupname])->first();
																	$gtax = $sdealerrate->taxgroup;
																	if($gtax == "A"){
																	$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																	$charges = $profile['final_rates'];
																}else if($gtax == "B"){
																$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																$charges = $profile['final_ratesB'];
															}else if($gtax == "C"){
															$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
															$charges = $profile['final_ratesC'];
														}else if($gtax == "D"){
														$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
														$charges = $profile['final_ratesD'];
													}else if($gtax == "E"){
													$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
													$charges1 = @$profile['final_ratesE'];
													$charges = @$charges1-1;
												}
												///
												if(!empty($dealerrate)){
												$drate1=$dealerrate->rate;
												$drate = $drate1+1;
												@endphp
												<tr id="{{ucfirst($profileRate->name)}}tr">
													<td scope='row' class="td__profileName">{{ucfirst($profileRate->name)}}</td>
													<td scope='row'> <input type="number" class='form-control' placeholder='0' style='border: none; text-align: center;' name="bpET{{ucfirst($profileRate->name)}}" value="{{$profileRate->base_price_ET}}"  step="0.01" min='0'>
													</td>
													<td scope='row'> <input type="number" class='form-control IPR' placeholder='0'
														style='border: none; text-align: center;'
														name="{{ucfirst($profileRate->name)}}" value="{{$profileRate->rate}}"  min="{{$drate1}}" required="" step="0.01">
													</td>
													<td scope='row'> <input type="number" class='form-control'
														placeholder='0'
														style='border: none; text-align: center;'
														name="bp{{ucfirst($profileRate->name)}}" value="{{$profileRate->base_price}}" min="{{$profileRate->rate}}" id="{{$profileRate->name}}result" step="0.01">
													</td>
													<td scope='row'> <input type="number" class='form-control'
														placeholder='0'
														style='border: none; text-align: center;'
														name="comm{{ucfirst($profileRate->name)}}" value="{{$profileRate->commision}}" min="0"  step="0.01">
													</td>
												</tr>
											<?php	}?>
											@endforeach
										</tbody>
									</table>
									@foreach($assignedProfileRates as $profileRate)
									<input type="hidden" name="profileNames[]" id="" value="{{$profileRate->name}}">
									<input style="display: none" type="number" class='form-control'
									placeholder='0'
									style='border: none; text-align: center;'
									name="dastiAmount[]" value="{{$profileRate['dasti_amount']}}" max="" min="0" step="0.01">
									@endforeach
								</center>
							</div>
						</div>
					</div>
				</div>
				<!-- Allow Access -->
				<div id="menu7" class="tab-pane fade">
					<div class="row" style="padding: 20px; text-align: center">
						<div class="col-lg-3 col-md-6">
							<h5 style="color: #000"> Consumer's Verification
								<span class="helping-mark" onmouseenter="popup_function(this, 'consumers_Verification_module');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
							</h5>
							<div class="form-group">
								@if($verify == 'yes')
								<label class="switch" style="width: 46px;height: 19px;">
									<input type="checkbox" checked name="Verification" id="Verification">
									<span class="slider square" ></span>
									<span id="verifystate"></span>
								</label>
								@elseif($verify == 'no')
								<label class="switch" style="width: 46px;height: 19px;">
									<input type="checkbox"  name="Verification" id="Verification">
									<span class="slider square" ></span>
									<span id="verifystate"></span>
								</label>
								@else
								<label class="switch" style="width: 46px;height: 19px;">
									<input type="checkbox" name="Verification" id="Verification">
									<span class="slider square" ></span>
									<span id="verifystate"></span>
								</label>
								@endif
							</div>
						</div>
						<div class="col-lg-3 col-md-6">
							<h5 style="color: #000">Available Balance <span class="helping-mark" onmouseenter="popup_function(this, 'available_balance_on_off');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></h5>
							<div class="">
								<div class="form-group">
									<p id="radiostate"></p>
									@if($check == 'yes')
									<label class="switch" style="width: 46px;height: 19px;" >
										<input type="checkbox" checked name="isvisible" id="isvisible" >
										<span class="slider square" ></span>
									</label>
									@elseif($check == 'no')
									<label class="switch" style="width: 46px;height: 19px;" >
										<input type="checkbox"  name="isvisible" id="isvisible" >
										<span class="slider square" ></span>
									</label>
									@else
									<label class="switch" style="width: 46px;height: 19px;" >
										<input type="checkbox" name="isvisible" id="isvisible" >
										<span class="slider square" ></span>
									</label>
									@endif
								</div>
							</div>
						</div>
						{{--
							<div class="col-lg-3 col-md-6">
								<h5 style="color: #000">Trader</h5>
								<div class="">
									<div class="form-group">
										<p id="radiostate"></p>
										@if($trader == 'yes')
										<label class="switch" style="width: 46px;height: 19px;" >
											<input type="checkbox" checked name="traderAllow" id="traderAllow" >
											<span class="slider square" ></span>
										</label>
										@elseif($trader == 'no')
										<label class="switch" style="width: 46px;height: 19px;" >
											<input type="checkbox"  name="traderAllow" id="traderAllow" >
											<span class="slider square" ></span>
										</label>
										@else
										<label class="switch" style="width: 46px;height: 19px;" >
											<input type="checkbox" name="traderAllow" id="traderAllow" >
											<span class="slider square" ></span>
										</label>
										@endif
									</div>
								</div>
							</div>
							--}}
							<div class="col-lg-3 col-md-6">
								<h5 style="color: #000">Cash & Credit Indication <span class="helping-mark" onmouseenter="popup_function(this, 'cash_credit_indication_reseller_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></h5>
								<div class="form-group">
									@if($payment == 'cash')
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox" checked name="payment_type" id="payment_type">
										<span class="slider square" ></span>
										<span id="paymentstate"></span>
									</label>
									@elseif($payment == 'credit')
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox"  name="payment_type" id="payment_type">
										<span class="slider square" ></span>
										<span id="paymentstate"></span>
									</label>
									@else
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox" name="payment_type" id="payment_type">
										<span class="slider square" ></span>
										<span id="paymentstate"></span>
									</label>
									@endif
								</div>
							</div>
							{{--
								<div class="col-lg-3 col-md-6">
									<h5 style="color: #000"> Never Expire <span class="helping-mark" onmouseenter="popup_function(this, 'never-expire_on_of');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></h5>
									<div class="form-group">
										<label class="switch" style="width: 46px;height: 19px;">
											<input type="checkbox" {{$isSetDealerNeverExpire['never_expire'] == 'yes' ? 'checked' : ''}} name="neverexpire" id="neverexpire">
											<span class="slider square" ></span>
										</label>
									</div>
								</div>
								--}}
								<div class="col-lg-3 col-md-6">
									<h5 style="color: #000">Allow Invoice <span class="helping-mark" onmouseenter="popup_function(this, 'invoice_on_off');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></h5>
									<div class="">
										<div class="form-group">
											<p id="radiostate"></p>
											<label class="switch" style="width: 46px;height: 19px;">
												<input type="hidden" name="allow_invoice" value="0">
												<input type="checkbox" name="allow_invoice" value="1" @if($dealer->allow_invoice == 1) checked @endif>
												<span class="slider square"></span>
											</label>
										</div>
									</div>
								</div>
								<div class="col-lg-3 col-md-6">
									<h5 style="color: #000">Allow MAC Bind Enable Disable <span class="helping-mark" onmouseenter="popup_function(this, 'invoice_on_off');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></h5>
									<div class="">
										<div class="form-group">
											<p id="radiostate"></p>
											<label class="switch" style="width: 46px;height: 19px;">
												<input type="hidden" name="bind_mac" value="0">
												<input type="checkbox" name="bind_mac" value="1" @if($dealer->bind_mac == 1) checked @endif>
												<span class="slider square"></span>
											</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="billingtype">
							@php
							$billing_type = 'amount';
							$discount = 2;
							//
							$check = DB::table('provincial_taxation')->where('state',$dealer->state)->first();
							if(!empty($check)){
							$sstfromdb = $check->ss_tax;
							$advfromdb = $check->adv_tax;
						}else{
						$sstfromdb = '0.195';
						$advfromdb = '0.15';
					}
					@endphp
					<div class="row">
						<div class="col-lg-12" style="padding: 20px;">
							<h3>Contractor & Trader Billing Method</h3>
							<div class="custom-control custom-radio custom-control-inline">
								<div class="">
									<input type="radio" class="custom-control-input" id="defaultInline1" value="amount" name="billingtype" {{$billing_type == 'amount'? 'checked' : ''}} style="height:20px; width:30px; vertical-align: middle;">
									<label class="custom-control-label position-relative" for="defaultInline1" style="margin-top: 10px;">Prepaid Amount Billing <span style="color: red">*</span>
										<span class="helping-mark" onmouseenter="popup_function(this, 'prepaid_amount_billing_reseller_side');" onmouseleave="popover_dismiss();" style="right:-20px"><i class="fa fa-question"></i></span>
									</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="taxsection">
					<div class="row">
						<h3 style="padding-left:20px">Government of Pakistan (Taxation Rates)</h3>
						<div class="col-lg-4 col-md-6" style="padding: 20px;">
							<div class="form-group">
								<label for="">Sindh Sales Tax (SST) (%) <span style="color: red">*</span></label>
								<input type="text" class="form-control" value="{{$sstfromdb}}" name="sstField" id="sstField">
							</div>
						</div>
						<div class="col-lg-4 col-md-6" style="padding: 20px;">
							<div class="form-group">
								<label for="">Advance Income Tax (AIT) (%) <span style="color: red">*</span></label>
								<input type="text" class="form-control" value="{{$advfromdb}}" name="advField" id="advField">
							</div>
							<input type="hidden" value="{{$discount}}" name="discount" id="discount">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="pull-right ">
		<button type="submit" class="btn btn-primary">Update</button>
	</div>
</div>
</div>
</form>
</div>
</section>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).on('keyup','.IPR',function(){
		var name = $(this).attr('name');
		var value = $(this).val();
		$("input[name='bp"+name+"']").prop('min',value);
	});
</script>
<script type="text/javascript">
	var num=0;
	$(document).ready(function() {
		$(".add-more").click(function(){
			if(num < 1){
				var html = $(".copy").html();
				$(".after-add-more").after(html);
				num++;
			}
		});
		$("body").on("click",".remove",function(){
			$(this).parents(".control-group").remove();
			num--;
		});
	});
</script>
<script type="text/javascript">
	function mycheckfunc(val,id,rate,charges12,userName,base_price_dealer){
		var va="#"+id;
		var string_id = '"'+id+'"';
		var name =userName;
		if($(va).attr("title") == "uncheck"){
			if(name == "sarbaaz"){
			}else{
				var markup = "<tr id='"+id+"tr'><td scope='row' class='td__profileName'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='bpET"+id+"' placeholder=0 required min='0' step='0.01' style='border: none; text-align: center;' min='"+rate+"' ></td><td scope='row'> <input type='number' class='form-control IPR' name='"+id+"' id='"+id+"' value='0' placeholder=0 required min='"+base_price_dealer+"' step='0.01'  style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='bp"+id+"' placeholder=0 required min='0' step='0.01' style='border: none; text-align: center;'' , min='"+rate+"' ></td><td scope='row'> <input type='number' class='form-control' name='comm"+id+"' placeholder=0 required min='0' step='0.01' style='border: none; text-align: center;'' , min='0' ></td></tr>";
			}
			$(".tbody").append(markup);
			$(va).attr('title', 'check');
		} else if($(va).attr("title") == "check"){
			var trvar=va+"tr";
			$(trvar).remove();
			$(va).attr('title', 'uncheck');
		}
	}
</script>
<script>
	$(document).ready(function(){
		$("#ipassign").click(function(){
			$("#noofip").prop('required',true);
			$("#ip_type").prop('required',true);
			iprate = $('#iprates').val();
			if(iprate == 0){
				document.getElementById("iprates").value = '';
			}
		});
	});
</script>
<script>
	$(document).ready(function(){
	});
</script>
<script>
	$(document).ready(function(){
		$('.tax_amount').on('change', function(){
			var $value = $(this).val();
			if($value === 'filer'){
				$("#ntn_no").prop('disabled',false);
				$("#ntn_no").prop('required',true);
			}
			else{
				$("#ntn_no").prop('disabled',true);
				$("#ntn_no").prop('required',false);
			}
		})
	});
</script>
{{-- <script>
	$(document).ready(function(){
		var a = document.getElementById('r1');
		var b = document.getElementById('r2');
		var c = document.getElementById('r3');
		var d = document.getElementById('r4');
		var e = document.getElementById('r5');
		if(a.checked == true){
			$('#v1').show();
			$('#v2').hide();
			$('#v3').hide();
			$('#v4').hide();
			$('#v5').hide();
		}
		if(b.checked == true){
			$('#v1').hide();
			$('#v2').show();
			$('#v3').hide();
			$('#v4').hide();
			$('#v5').hide();
		}
		if(c.checked == true){
			$('#v1').hide();
			$('#v2').hide();
			$('#v3').show();
			$('#v4').hide();
			$('#v5').hide();
		}
		if(d.checked == true){
			$('#v1').hide();
			$('#v2').hide();
			$('#v3').hide();
			$('#v4').show();
			$('#v5').hide();
		}
		if(e.checked == true){
			$('#v1').hide();
			$('#v2').hide();
			$('#v3').hide();
			$('#v4').hide();
			$('#v5').show();
		}
	});
</script> --}}
@endsection
<!-- Code Finalize -->