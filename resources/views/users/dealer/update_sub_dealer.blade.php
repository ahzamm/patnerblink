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
		background-color: rgb(217, 83, 79) !important;
		border-color: rgb(212, 63, 58);
	}
	.ac-mgt {
		float: left;
		margin: 5px 44px 4px 39px;
		margin-bottom: 20px;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Update Trader
					<span class="info-mark" onmouseenter="popup_function(this, 'update_trader_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<div class="">
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
						<form id="general_validate" 
						action="{{route('users.user.update',['status' => 'subdealer','id' => $id])}}" method="POST" >
						@csrf
						<div class="row">
							<div class="col-lg-4 col-md-6">
								<input type="hidden" value="{{$subdealer->manager_id}}" name="managerid" class="form-control" readonly/>
								@php
								$mob = '';
								$nic = '';
								$isverify = App\model\Users\UserVerification::where('username',$subdealer->username)->select('mobile_status','cnic')->get();
								foreach ($isverify as $value) {
								$mob = $value['mobile_status'];
								$nic = $value['cnic'];
							}
							@endphp
							<input type="hidden" value="{{$subdealer->resellerid}}" name="resellerid" class="form-control" readonly/>
							<input type="hidden" value="{{$subdealer->dealerid}}" name="dealerid" class="form-control" readonly/>
							<input type="hidden" value="{{$subdealer->sub_dealer_id}}" name="sub_dealer_id" class="form-control"  placeholder="Trader (ID)"  readonly/>
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'trader_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="{{$subdealer->username}}" name="username" class="form-control"  placeholder="Username" required readonly>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label" >First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="{{$subdealer->firstname}}" name="fname" class="form-control" placeholder="First Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="{{$subdealer->lastname}}" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" value="{{$subdealer->email}}" name="mail" class="form-control"  placeholder="info@logon.com.pk" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group" style="display: none;">
								<label  class="form-label">MRTG Graph </label>
								<div class="input-group control-group after-add-more">
									<input type="text" name="graph[]" value="{{ ($graph1) ? $graph1->graph_no : '' }}" class="form-control" >
									<div class="input-group-btn">
										<button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i></button>
									</div>
								</div>
								<div class="copy hide">
									<div class="control-group input-group" style="margin-top:10px">
										<input type="text" name="graph[]" class="form-control" >
										<div class="input-group-btn">
											<button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group position-relative">
								<label for="validate-phone">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<div class="input-group" data-validate="phone">
									<input type="hidden" name="" id="variCode" value="{{$mob}}">
									<input type="text" class="form-control" name="mobile_number" id="validate-phone"  data-mask="9999 9999999" value="{{$subdealer->mobilephone}}" required readonly>
									<span class="input-group-addon danger">Unverify 
									</span>
								</div>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline# | Mobile#2 <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="{{$subdealer->homephone}}" name="land_number"  class="form-control" >
							</div>
							<div class="form-group position-relative">
								<label for="validate-nic">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<div class="input-group" data-validate="nic">
									<input type="hidden" name="" id="nicvariCode" value="{{$nic}}">
									<input type="text" class="form-control" name="nic" value="{{$subdealer->nic}}" data-mask="99999-9999999-9"  required readonly>
									<span class="input-group-addon danger">
										Unverify 
									</span>
								</div>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Business Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="{{$subdealer->address}}" name="address" class="form-control"  placeholder="Address" required>
							</div>
						</div>
						<div class="col-lg-4" style="margin-bottom: 50px;padding:0">
							<div class="col-lg-12 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">Assign Trader Area <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'assgin_trader_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="text" value="{{$subdealer->area}}" name="area" class="form-control"  placeholder="Assign Trader Area " required>
								</div>
							</div>
							<div class="col-lg-12 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">CNIC <span style="color: #0d4dab">(Front Image)</span> <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="file" name="cnic_front" class="form-control"  placeholder="">
								</div>
							</div>
							<div class="col-lg-12 col-md-6">
								<div class="form-group position-relative">
									<label  class="form-label">CNIC <span style="color: #0d4dab">(Back Image)</span> <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<input type="file" name="cnic_back" class="form-control"  placeholder="">
								</div>
							</div>
							@php
							$radioCheck1 = '';
							$allowplan = '';
							$tax2 = App\model\Users\SubDealerProfileRate::where('sub_dealer_id',$subdealer->sub_dealer_id)->select('taxgroup','allow_trader','verify')->first();
							$radioCheck1 = @$tax2['taxgroup'];
							$allowtrader = @$tax2['allow_trader'];
							$verify = @$tax2['verify'];
							//	$data = App\model\Users\DealerProfileRate::where('dealerid',$subdealer->dealerid)->select('allowplan','trader')->first();
							$data = App\model\Users\DealerProfileRate::where('dealerid',$subdealer->dealerid)->select('allowplan','trader','sstpercentage','advpercentage')->first();
							$trader = @$data['trader'];
							if(!empty($data)){
							$sstfromdb = $data->sstpercentage;
							$advfromdb = $data->advpercentage;
						}else{
						$sstfromdb = '0.195';
						$advfromdb = '0.15';
					}
					@endphp
					<input type="hidden" value="{{$sstfromdb}}" name="sstField" id="sstField">
					<input type="hidden" value="{{$advfromdb}}" name="advField" id="advField">
					@if($allowplan == 'yes')
					<label for="">Taxes</label>
					<div class="form-group">
						<div class="btn-group btn-group-toggle" data-toggle="buttons">
							@if($radioCheck1 == 'A')
							<label  class="btn btn-secondary active"  disabled>CX-V1
								<input type="radio" name="tax" id="r1" checked placeholder="0" value="A" />
							</label>
							@else
							<label  class="btn btn-secondary" disabled>CX-V1
								<input type="radio" name="tax" id="r1" placeholder="0" value="A" />
							</label>
							@endif
							@if($radioCheck1 == 'B')
							<label  class="btn btn-secondary active" disabled> CX-V2
								<input type="radio" name="tax" id="r2" checked placeholder="0" value="B" />
							</label>
							@else
							<label  class="btn btn-secondary" disabled> CX-V2
								<input type="radio" name="tax"  id="r2" placeholder="0" value="B" />
							</label>
							@endif
							@if($radioCheck1 == 'C')
							<label  class="btn btn-secondary active" disabled> CX-V3
								<input type="radio" name="tax" id="r3" checked placeholder="0" value="C" />
							</label>
							@else
							<label  class="btn btn-secondary" disabled> CX-V3
								<input type="radio" name="tax" id="r3" placeholder="0" value="C" />
							</label>
							@endif
							@if($radioCheck1 == 'D')
							<label  class="btn btn-secondary active" disabled> CX-V4
								<input type="radio" name="tax" id="r4" checked placeholder="0" value="D" />
							</label>
							@else
							<label  class="btn btn-secondary" disabled> CX-V4
								<input type="radio" name="tax" id="r4" placeholder="0" value="D" />
							</label>
							@endif
							<input type="hidden" name="tax" value="{{$radioCheck1}}">
						</div>
					</div>
					@endif
				</div>
				@if($trader == "yes")
				<h4>Allow Traders</h4>
				@if($allowtrader == "yes")
				<p id="radiostate"></p>
				<label class="switch" style="width: 46px;height: 19px;" >
					<input type="checkbox" checked name="traderAllow" id="traderAllow" >
					<span class="slider square" ></span>
				</label>
				@else
				<p id="radiostate"></p>
				<label class="switch" style="width: 46px;height: 19px;" >
					<input type="checkbox" name="traderAllow" id="traderAllow" >
					<span class="slider square" ></span>
				</label>
				@endif					
				@endif	
				<br/>
				<?php
				if(Auth::user()->status != 'reseller'){
					$display = 'none';
				}else{
					$display = 'block';
				}
				?>
				<div class="col-md-12" style="display:<?= $display;?>">
					<hr style="margin-bottom: 45px;">
					<div class="header_view">
						<h2 style="font-size: 26px;">Access Management</h2>
					</div>
					<hr style="background-color: transparent;    height: auto; margin-top: initial;">
					<div class="form-group ac-mgt">
						<label  class="form-label">Available Balance Menu (On & Off)</label><br>
						@php
						$isVisible = App\model\Users\UserAmount::where('username',$subdealer->username)->first();
						$check = $isVisible['isvisible'];
						@endphp
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
					<div class="form-group ac-mgt">
						<label class="form-label">Allow Invoice (On & Off)</label><br>
						<label class="switch" style="width: 46px;height: 19px;">
							<input type="hidden" name="allow_invoice" value="0">
							<input type="checkbox" name="allow_invoice" value="1" @if($subdealer->allow_invoice == 1) checked @endif>
							<span class="slider square"></span>
						</label>
					</div>
					<div class="form-group ac-mgt">
						<h5 style="color: #000"> Consumers Verification (On & Off)   </h5>
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
				</div>
				<div id="profileRateDiv" style="display:<?= $display;?>">
					<hr style="margin-bottom: 45px;">
					<div class="header_view">
						<h2 style="font-size: 26px;">Assigned Internet Profile</h2>
					</div>
					<hr style="background-color: transparent;
					height: auto;
					margin-top: initial;">
					<div class="col-md-12">
						<div class="col-lg-4" style="padding: 0">
							<div class="button-group form-group" style="margin-bottom: 0">
								<button type="button" class="btn dropdown-toggle dropdown__btn" data-toggle="dropdown">
									Select Internet Profiles <span style="color: red">*</span>
									<span class="caret"></span>&nbsp;&nbsp;
									<span>(Dropdown)</span>
								</button>
								<ul class="dropdown-menu"  style="max-height:200px;overflow-y:auto;max-width:200px;">
									@foreach($profileList as $profile_data)
									@php $profile =ucwords($profile_data->name);
									$sdealerrate=App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => $subdealer->sub_dealer_id])->where(['groupname' => $profile_data->groupname])->first();
									$gtax = "E";
									if($gtax == "A"){
									$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
									$charges = $profile1['final_rates'];
								}else if($gtax == "B"){
								$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
								$charges = $profile1['final_ratesB'];
							}else if($gtax == "C"){
							$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
							$charges = $profile1['final_ratesC'];
						}else if($gtax == "D"){
						$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
						$charges = $profile1['final_ratesD'];
					}else if($gtax == "E"){
					$profile1=App\model\Users\Profile::where(['name' => $profile_data->name])->first();
					$charges = $profile1['final_ratesE'];
				}
				$rate=$profile_data->rate+1;
				$max=$profile_data->max;
				$checkUserIsCard = App\model\Users\DealerProfileRate::where('dealerid',$subdealer->dealerid)->first()->billing_type;
				// dd($checkUserIsCard);
				@endphp
				@if(@$checkUserIsCard == 'amount')
				<li>
					<input type="checkbox" class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id,'{{$profile_data->base_price_ET}}','{{$profile_data->base_price}}')" style="height: 16px;width: 16px;margin:5px 5px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif/>&nbsp;{{$profile}}
				</li>
				@else
				<li><a href="#" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;"><input type="checkbox" class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc2(this.value,this.id,'{{$rate}}','{{$max}}')" style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif/>&nbsp;{{$profile}}</a></li>
				@endif
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-lg-8 " style="padding: 0" >
		<center>
			<div class="table-responsive">
				<table class="table table-bordered" >
					<thead class="thead table-striped">
						<tr>
							<th scope="col">Internet Profile Name</th>
							@if(@$checkUserIsCard == 'amount')
							<th scope="col"> Internet Profile Rates (PKR) (Excluding Tax)  <span style="color: red">*</span></th>
							<th scope="col"> Company Consumer Base Price (PKR)  <span style="color: red">*</span></th>
							<th scope="col"> Contractor Trader Margin (PKR)  <span style="color: red">*</span></th>
							@endif
						</tr>
					</thead>
					<tbody class="tbody">
						@foreach($assignedProfileRates as $profileRate)
						<tr id="{{ucfirst($profileRate->name)}}tr">
							<td scope='row' class="td__profileName">{{ucfirst($profileRate->name)}}
								@php
								$dealerrate=App\model\Users\DealerProfileRate::where(['dealerid' => $subdealer->dealerid])->where(['name' => $profileRate->name])->first();
								$sdealerrate=App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => $subdealer->sub_dealer_id])->where(['name' => $profileRate->name])->first();
								$gtax = $sdealerrate->taxgroup;
								if($gtax == "A"){
								$profile=App\model\Users\Profile::where(['name' => $profileRate->name])->first();
								$charges = $profile['final_rates'];
							}else if($gtax == "B"){
							$profile=App\model\Users\Profile::where(['name' => $profileRate->name])->first();
							$charges = $profile['final_ratesB'];
						}else if($gtax == "C"){
						$profile=App\model\Users\Profile::where(['name' => $profileRate->name])->first();
						$charges = $profile['final_ratesC'];
					}else if($gtax == "D"){
					$profile=App\model\Users\Profile::where(['name' => $profileRate->name])->first();
					$charges = $profile['final_ratesD'];
				}else if($gtax == "E"){
				$profile=App\model\Users\Profile::where(['name' => $profileRate->name])->first();
				$charges1 = $profile['final_ratesE'];
				$charges = $charges1-1;
			}
			$drate=@$dealerrate->rate+1;
			$dmax=@$dealerrate->max;
			@endphp
		</td>
		@if(@$checkUserIsCard == 'amount')
		<td scope='row'> <input type="number" class='form-control' 
			placeholder='0' 
			style='border: none; text-align: center;'
			name="{{ucfirst($profileRate->name)}}" value="{{($profileRate) ? $profileRate->base_price_ET : 0}}" step='0.01'  min="{{($profileRate) ? $profileRate->base_price_ET : 0}}" required="">
		</td>
		<td scope='row'> <input type="number" class='form-control' 
			placeholder='0' 
			style='border: none; text-align: center;'
			name="bp{{ucfirst($profileRate->name)}}" value="{{($profileRate) ? $profileRate->base_price : ''}}" step='0.01'  min="{{($profileRate) ? $profileRate->base_price : ''}}" required="">
		</td>
		<td scope='row'> <input type="number" class='form-control' 
			placeholder='0' 
			style='border: none; text-align: center;'
			name="ctm{{ucfirst($profileRate->name)}}" value="{{$profileRate->margin}}" step='0.01'  min="0" required="">
		</td>
		@endif
	</tr>
	@endforeach
</tbody>
</table>
</div>
</center>
</div>
</div>
</div>
<div class="col-xs-12">
	<div class="pull-right ">
		<button type="submit" class="btn btn-primary">Update</button>
	</div>
</div>
</div>
</form>
</div>
</section></div>
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
	function mycheckfunc(val,id,bpET,bp){
		var va="#"+id;
		if($(va).attr("title") == "uncheck"){
			var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='"+id+"' placeholder='0' required min='"+bpET+"' value='"+bpET+"' step='0.01' style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='bp"+id+"' placeholder='0' required min='"+bp+"' value='"+bp+"' step='0.01' style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='ctm"+id+"' placeholder='0' required min='0' value='0' step='0.01' style='border: none; text-align: center;''></td></tr>";
			$(".tbody").append(markup);
			$(va).attr('title', 'check');
		} else if($(va).attr("title") == "check"){
			var trvar=va+"tr";
			$(trvar).remove();
			$(va).attr('title', 'uncheck');
		}
	}
	function mycheckfunc2(val,id,rate,charges){
		var va="#"+id;
		if($(va).attr("title") == "uncheck"){
			var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row' style='display:none'> <input type='hidden' class='form-control' name='"+id+"' placeholder='0'  required value='"+rate+"' min='"+rate+"'  step='0.01' style='border: none; text-align: center;''></td></tr>";
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
				$addon.html('Varified');
			}
		});
		$('.input-group input[required], .input-group textarea[required], .input-group select[required]').trigger('change');
	});
</script>
@endsection
<!-- Code Finalize -->