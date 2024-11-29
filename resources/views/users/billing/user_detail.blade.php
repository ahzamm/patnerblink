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
@section('owncss')
<style type="text/css">
	.flip-card-inner {
		position: relative;
		text-align: center;
		transition: transform 0.6s;
		transform-style: preserve-3d;
		box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
	}
	.flip-card:hover .flip-card-inner {
		transform: rotateY(180deg);
	}
	.flip-card-front, .flip-card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
	}
	.flip-card-front {
		background-color: #bbb;
		color: black;
	}
	.flip-card-back {
		background-color: #2980b9;
		color: white;
		transform: rotateY(180deg);
	}
	(1:20:33 PM) .flip-card {
		background-color: transparent;
		perspective: 1000px;
	}
	(1:21:08 PM) .flip-card {
		background-color: transparent;
		perspective: 1000px;
	}
	.flip-card-inner {
		position: relative;
		text-align: center;
		transition: transform 0.6s;
		transform-style: preserve-3d;
		box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
	}
	.flip-card:hover .flip-card-inner {
		transform: rotateY(180deg);
	}
	.flip-card-front, .flip-card-back {
		position: absolute;
		width: 100%;
		height: 100%;
		backface-visibility: hidden;
	}
	.flip-card-front {
		background-color: #bbb;
		color: black;
	}
	.flip-card-back {
		background-color: #2980b9;
		color: white;
		transform: rotateY(180deg);
	}
	.canvasjs-chart-credit{
		display: none;
	}
	#disableBtn.btn.btn-success:hover{
		background-color:red
	}
	#disableBtn.btn.btn-danger:hover{
		background-color:green
	}
	.canvasjs-chart-canvas{
		width:100% !important;
		height: 370px !important;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			@if($user->status == 'subdealer')
			<center><div class="header_view"> <h2>Trader Detail</h2></div></center>
			@endif
			@if($user->status == 'user')
			<center><div class="header_view"> <h2>Consumer Detail</h2></div></center>
			@endif
			@if($user->status == 'dealer')
			<center><div class="header_view"> <h2>Contractor Detail</h2></div></center>
			@endif
			@if($user->status == 'reseller')
			<center><div class="header_view"> <h2>Reseller Detail</h2></div></center>
			@endif
			@if($user->status == 'manager')
			<center><div class="header_view"> <h2>Manager Detail</h2></div></center>
			@endif
			<div class="row" style="margin:0">
				<div class="col-md-3">
					<div class="">
						@if($user->status == 'manager')
						<img alt="" src="{{asset('img/avatar/manager_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 100px;">
						@elseif($user->status == 'reseller')
						<img alt="" src="{{asset('img/avatar/reseller_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 100px;">
						@elseif($user->status == 'dealer')
						<img alt="" src="{{asset('img/avatar/dealer_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 100px;">
						@elseif($user->status == 'subdealer')
						<img alt="" src="{{asset('img/avatar/trader_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 100px;">
						@else
						<img alt="" src="{{asset('img/avatar/user_detail_avatar.png')}}" class="img-responsive" style="margin: auto; width: 100px;">
						@endif
					</div>
					<div class="uprofile-name">
						<h3>
							<a href="#">{{$user->username}}</a>
							<span class="uprofile-status online"></span>
						</h3>
						@if($user->status == 'manager')
						<p class="uprofile-title">Manager</p>
						@elseif($user->status == 'reseller')
						<p class="uprofile-title">Reseller</p>
						@elseif($user->status == 'dealer')
						<p class="uprofile-title">Contractor</p>
						@elseif($user->status == 'subdealer')
						<p class="uprofile-title">Trader</p>
						@else
						<p class="uprofile-title">Consumer</p>
						@endif
					</div>
					<div class="uprofile-info">
						<ul class="list-unstyled">
							<li><i class='fa fa-home'></i> {{$user->address}}</li>
							@if($user->status == 'dealer')
							<li><i class='fa fa-users-cog'></i>Trader(s):  {{App\model\Users\UserInfo::where(['status' => 'subdealer','dealerid' => $user->dealerid])->count()}} </li>
							<li><i class='fa fa-users'></i>Consumer(s):  {{App\model\Users\UserInfo::where(['status' => 'user','dealerid' => $user->dealerid])->count()}} </li>
							@endif
							@if($user->status == 'reseller')
							<li><i class='fas fa-user-friends'></i>Contractor(s): {{App\model\Users\UserInfo::where(['status' => 'dealer','resellerid' => $user->resellerid])->count()}}</li>
							@endif
							@if($user->status == 'manager')
							<li><i class='fas fa-user-tie'></i> {{App\model\Users\UserInfo::where(['status' => 'reseller','manager_id' => $user->manager_id])->count()}} Resellers</li>
							<li><i class='fas fa-user-friends'></i> {{App\model\Users\UserInfo::where(['status' => 'dealer','manager_id' => $user->manager_id])->count()}} Contractor</li>
							@endif
						</ul>
					</div>
					@php
					$status = Auth::user()->status;
					if($status == 'manager' || $status == 'reseller' || $status == 'dealer' || $status == 'subdealer'){ @endphp
					<div class="uprofile-buttons">
						@php
						$statmnt= 'Disabled';
						$disabled=App\model\Users\DisabledUser::where('username' ,'=', $user->username);
						if($disabled->count() > 0){
						$btnStatus=$disabled->first()->status;
						$raddisabled =App\model\Users\RaduserGroup::where('username' ,'=', $user->username);
						$radbtnStatus=$raddisabled->first()->groupname;
						if($radbtnStatus == 'DISABLED'){
						$disbleby=$disabled->first()->updated_by;
						$class='btn btn-danger';
						if($disbleby == 'manager' && $status == 'manager'){
						$type='submit';
					}else if( ($disbleby == 'reseller') && ($status == 'manager' || $status == 'reseller' )){
					$type='submit';
				}else if(($disbleby == 'dealer') && ($status == 'manager' || $status == 'reseller' || $status == 'dealer')){
				$type='submit';
			}else if($disbleby == 'subdealer'){
			$type='submit';
		}else{
		$type='button';
	}
	$statmnt= 'This Consumer is Disabled';
}else {
$statmnt= 'This Consumer is Enabled';
$class='btn btn-success';
$type='submit';
}
}else{
$class='btn btn-success';
$type='submit';
$statmnt= 'This Consumer is Enabled';
}
@endphp
<form action="{{route('users.billing.enabledisable')}}" method="POST" >
	@csrf
	<input type="hidden" name="username" value="{{$user->username}}" >
	<input type="hidden" name="userid" value="{{$user->id}}">
	@if($user->status =="user")
	<button type="{{$type}}" id="disableBtn" value="enable" onclick="toggle();" class=" mb1 bg-olive btn-lg {{$class}}" style="border-radius:7px;width: 100%;">{{$statmnt}}</button>
	@endif
</form>
</div>
@php } @endphp
@php
$mob = '';
$mobile = '';
$cnic = '';
$nicF = '';
$nicB = '';
$ntn = '';
$passport = '';
$overseas = '';
$isverify = App\model\Users\UserVerification::where('username',$user->username)->select('mobile','mobile_status','cnic','nic_front','nic_back','ntn','overseas','intern_passport')->get();
foreach ($isverify as $value) {
$mob = $value['mobile_status'];
$mobile = $value['mobile'];
$cnic = $value['cnic'];
$nicF = $value['nic_front'];
$nicB = $value['nic_back'];
$ntn = $value['ntn'];
$passport = $value['intern_passport'];
$overseas = $value['overseas'];
}
@endphp
</div>
<div class="col-md-9">
	<div class="uprofile-content row">
		<div class="">
			<div class="">
				<div class="row" style="margin:0;padding-bottom:20px;">
					<div class="">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">Details </a></li>
							<li><a data-toggle="tab" href="#menu2">Internet Usage Statistics</a></li>
							<li><a data-toggle="tab" href="#data-graph">Internet Data Usage Graph</a></li>
							<li><a data-toggle="tab" href="#cnic_tab">CNIC (Picture)</a></li>
						</ul>
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div style="overflow-x: auto;">
									<table class="table table-bordered user_detail_table">
										<tbody>
											@if($user->status == 'reseller' || $user->status == 'dealer' || $user->status == 'manager')
											<tr>
												<td class="td__profileName">Manager ID</td>
												<td>{{$user->manager_id}}</td>
											</tr>
											@endif
											@if($user->status == 'dealer')
											<tr>
												<td class="td__profileName">Reseller ID</td>
												<td>{{$user->resellerid}}</td>
											</tr>
											@endif
											<tr>
												<td class="td__profileName">Username</td>
												<td>{{$user->username}}</td>
											</tr>
											@if($user->status != 'manager' && $user->status != 'user')
											<tr>
												<td class="td__profileName">Internet Profile</td>
												<td>
													@if($user->status != 'user')
													@foreach($userProfileRates as $uPr)
													<span class="badge badge-default" style="background-color:{{$uPr->color}}">
														{{$uPr->name}}
													</span>
													@endforeach
													@endif
												</td>
											</tr>
											@endif
											@if($user->status == 'user' && $profile !='')
											<tr>
												<td class="td__profileName">Internet Profile</td>
												<td>
													<span class="badge badge-default" style="background-color:{{$profile->color}}">	{{$profile->name}}
													</span>
												</td>
											</tr>
											@endif
											@if($user->status == 'user' && $cur_profile !='')
											<tr>
												<td class="td__profileName">Current Profile</td>
												@if($user->taxprofile == "yes")
												<td>
													<span class="badge badge-default" style="background-color:#8c8e8c">	{{$cur_profile->name}}-CDN
													</span>
												</td>
												@else
												<td>
													<span class="badge badge-default" style="background-color:#8c8e8c">	{{$cur_profile->name}}
													</span>
												</td>
												@endif
											</tr>
											@endif
											<tr>
												<td class="td__profileName">Full Name</td>
												<td>{{$user->firstname}} {{$user->lastname}}</td>
											</tr>
											<?php if($user->status == 'user'){?>
												<tr>
													<td class="td__profileName">CNIC / NTN / Passport / Overseas</td>
													<td>
														@if($cnic != '')
														{{$cnic}}
														<span class="fa fa-check" style="color: green"></span>
														@elseif($ntn != '')
														{{$ntn}}
														<span class="fa fa-check" style="color: green"></span>
														@elseif($overseas != '')
														{{$overseas}}
														<span class="fa fa-check" style="color: green"></span>
														@elseif($passport != '')
														{{$passport}}
														<span class="fa fa-check" style="color: green"></span>
														@else
														Not Available
														<span class="las la-exclamation-triangle" style="color: red"></span>
														@endif
													</td>
												</tr>
												<tr>
													<td class="td__profileName">Mobile Number</td>
													<td>
														{{$mobile}}
														@if(!empty($mobile))
														<span class="fa fa-check" style="color: green"></span>
														@else
														Not Available
														<span class="las la-exclamation-triangle" style="color: red"></span>
														@endif
													</td>
												</tr>
											<?php }else{ ?>
												<tr>
													<td class="td__profileName">CNIC / NTN / Passport / Overseas</td>
													<td>{{$user->nic}}</td>
												</tr>
												<tr>
													<td class="td__profileName">Mobile Number</td>
													<td>
														@if(!empty($user->mobilephone))
														<a href="tel:{{$user->mobilephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$user->mobilephone}}</a>
														@endif
													</td>
												</tr>
											<?php } ?>
											<tr>
												<td class="td__profileName">Landline# | Mobile#2</td>
												<td>
													@if($user->homephone != '')
													<a href="tel:{{$user->homephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$user->homephone}}</a>
													@else Not Available
													@endif
												</td>
											</tr>
											<tr>
												<td class="td__profileName">Business Address</td>
												<td>{{$user->address}}</td>
											</tr>
											<tr>
												<td class="td__profileName">Password</td>
												@if($user->status != 'manager')
												<td style="position:relative">
													<input type="password" id="user_password" class="form-control copy-password" value="{{$userRedCheck->value}}" readonly disabled style="text-align:center"> 
													<a href="javascript:void(0);" class="btn btn-secondary btn-xs btn__copy" onclick="copyToClipboard()"><i class="fa fa-clipboard"></i> Copy</a>
													<i class="fa fa-eye" style="position:absolute; right:20px; top: 18px; cursor:pointer"></i>
												</td>
												@else
												<td>Not Available</td>
												@endif
											</tr>
											@if($user->status == 'user')
											<tr>
												<td class="td__profileName">Creation Date</td>
												<td>{{date('M d,Y',strtotime($user->creationdate))}}</td>
											</tr>
											<tr>
												<td class="td__profileName"> Charge On</td>
												<td><?php if(empty($userstatusinfo->card_charge_on) || ($userstatusinfo->card_charge_on == '1990-03-03') ){ echo 'NEW';}else{ echo date('M d,Y',strtotime($userstatusinfo->card_charge_on)); }?>
											</td>
										</tr>
										<tr>
											<?php 
											$exptext = $expVal = NULL;
											if($userstatusinfo->card_expire_on == '1990-03-03'){ 
												$exptext = 'New Consumer'; $expVal = 'NEW' ;
											}else{ 
												$exptext = 'Expire On'; 
												$expVal = date('M d,Y',strtotime($userstatusinfo->card_expire_on)); 
											}?>
											<td class="td__profileName"><?= $exptext;?></td>
											<td>
												<?= $expVal;?>
											</td>
										</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						@php
						$totalDownload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctoutputoctets');
						$tDownMonth1= App\model\Users\UserInfo::where('username', $user->username)->first();
						$tDownMonth = $tDownMonth1['qt_used'];
						if(empty($tDownMonth)){
						$tDownMonth =0;
					}
					$totalupload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctinputoctets');
					$tupMonth= App\model\Users\RadAcct::where('username', $user->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctinputoctets');
					@endphp
					<div id="menu2" class="tab-pane fade">
						<div class="row">
							<div class="col-md-6" style="margin-top: 30px;">
								<center>
									<h3>Total Internet Data (Download)</h3>
									<i class="fa fa-download icon_size"></i>
									<h3>{{ByteSize($totalDownload)}}</h3>
								</center>
							</div>
							<div class="col-md-6" style="margin-top: 30px;">
								<center>
									<h3>Total Internet Data (Upload)</h3>
									<i class="fa fa-upload icon_size"></i>
									<h3>{{ByteSize($totalupload)}}</h3>
								</center>
							</div>
							<div class="col-md-12" style="margin-top: 50px; overflow-x: auto;">
								<table class="table table-bordered" style="overflow: auto;">
									@php
									$mac	=App\model\Users\RadCheck::where(['username' => $user->username, 'attribute'=>'Calling-Station-Id'])->first();
									@endphp
									<tr>
										<th>Total Login Session</th>
										<th>Last Login Date & TIme</th>
										<th>Monthly Internet Data <br>
										Usage</th>
										<th>Interface MAC Address
										</th>
										<th>Nating IP Address</th>
										<th style="text-align: center">Assigned Dynamic IP Address</th>
									</tr>
									@php
									$total_login  = App\model\Users\RadAcct::where(['username' => $user->username])->count();
									$login  = App\model\Users\RadAcct::where(['acctstoptime' => NULL,'username' => $user->username])->first();
									$mac	=App\model\Users\RadCheck::where(['username' => $user->username, 'attribute'=>'Calling-Station-Id'])->first();
									$lastlogin= App\model\Users\RadAcct::where(['username' => $user->username])->orderBy('radacctid','desc')->first();
									if($lastlogin){
									$lastlogin=$lastlogin->acctstarttime;
								}else{
								$lastlogin='Not Yet Login';
							}
							@endphp
							<tr>
								<td>{{$total_login}}</td>
								<td>{{$lastlogin}}</td>
								<td>{{ByteSize($tDownMonth)}}</td>
								<td>{{$mac->value}}
									@php
									if($mac->value!='NEW'){
									@endphp
									<form action="{{route('users.billing.user_detail')}}" method="POST" >
										@csrf
										<input type="hidden" name="clearmac" value="{{$user->username}}">
										<input type="hidden" name="userid" value="{{$user->id}}">
										<button type="submit" class="btn btn-success">Clear Mac Address</button>
									</form>
									@php
								}
								@endphp
								<hr style="margin-top:10px;margin-bottom:10px">
								@if($mac->value!="NEW")
								@php
								$mv=explode('-', $mac->value);
								$my_mac = @$mv[0].'-'.@$mv[1].'-'.@$mv[2];
								$vendor=App\model\Users\MacVendor::where('oui' , '=' , $my_mac )->first();
								if($vendor != null){
								$vendor=$vendor->vendor;
							}else{$vendor='No Vendor Found'; }
							@endphp
							<span style="color: green">{{$vendor}}</span>
							@endif
						</td>
						@if(!empty($login))
						<td>{{$login->framedipaddress}}</td>
						@else
						<td>Not Available</td>
						@endif
						<td id="dhcp_ip"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div id="data-graph" class="tab-pane fade">
		<div class="row">
			<div class="col-md-12 " >
				@include('users.billing.admin_users_graph')
			</div>
		</div>
	</div>
	<?php $status_directory = null;?>						
	@if($user->status == 'reseller')
	<?php $status_directory = "Reseller-NIC"; ?>
	@elseif($user->status == 'dealer')
	<?php	$status_directory = "Dealer-NIC";?>
	@elseif($user->status == 'subdealer')
	<?php $status_directory = "sub_dealerNic";?>
	@elseif($user->status == 'user')
	<?php $status_directory = "UploadedNic";?>
	@endif
	<?php
	$status_directory = null;
	if($user->status == 'reseller'){
		$srcF = 'Reseller-NIC/'.$user->username.'-cnic_front.jpg';
		$srcB = 'Reseller-NIC/'.$user->username.'-cnic_back.jpg';
	}elseif($user->status == 'dealer'){
		$srcF = 'Dealer-NIC/'.$user->username.'-cnic_front.jpg';
		$srcB = 'Dealer-NIC/'.$user->username.'-cnic_back.jpg';
	}elseif($user->status == 'subdealer'){
		$srcF = 'sub_dealerNic/'.$user->username.'-front.jpg';
		$srcB = 'sub_dealerNic/'.$user->username.'-back.jpg';
	}elseif($user->status == 'user'){
		$srcF = 'UploadedNic/'.$user->username.'-front.jpg';
		$srcB = 'UploadedNic/'.$user->username.'-back.jpg';
	}
	?>
	<div id="cnic_tab" class="tab-pane fade">
		<div class="cnic_wrapper">
			<div class="cnic-box">
				<?php if(file_exists(public_path().'/'.$srcF)){ ?>
					<a href="{{asset($srcF)}}" target="_blan">
						<img src="{{asset($srcF)}}" alt="CNIC Front" class="zoom_image" style="width:100%; height:100%"></a>
					<?php }else{ ?>
						<img src="{{asset('images/placeholder_nic.png')}}" alt="Placeholder" style="width:100%; height:100%">
					<?php } ?>	
					<p style="font-weight:bold; font-size: 18px;margin-top:15px;">CNIC (Front Image)</p>
				</div>
				<div class="cnic-box">
					<?php if(file_exists(public_path().'/'.$srcB)){ ?>
						<a href="{{asset($srcB)}}" target="_blan" >
							<img src="{{asset($srcB)}}" alt="CNIC Back" style="width:100%; height:100%"></a>
						<?php }else{ ?>
							<img src="{{asset('images/placeholder_nic.png')}}" alt="Placeholder" style="width:100%; height:100%">
						<?php } ?>
						<p style="font-weight:bold; font-size: 18px;margin-top:15px;">CNIC (Back Image)</p>
					</div>
				</div>
			</div> 
		</div>
	</div>
</div>
</div>
</div>
</div>
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
<script type="text/javascript">
	$('.fa-eye').click(function(){
		var x = document.getElementById("user_password");
		if (x.type == 'password') {   
			x.type = "text";
		} else {
			x.type = "password";
		}
	});
</script>
<script>
	$(document).ready(function(){
		getIP('{{$mac->value}}');
	});
	function getIP(mac){	
		$.ajax({
			url: "{{route('user.dhcp')}}",
			type: "GET",
			data: {mac:mac},
			success: function(data){
$("#dhcp_ip").html(data); //Data From Controller....
}
});
	}
</script>
@endsection
<!-- Code Finalize -->