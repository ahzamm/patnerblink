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
@include('users.userPanelView.ExpireUserModal')
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
	h1{
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
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			@if($user->status == 'subdealer')
			<center><h3>Sub Dealer Detail</h3></center><br>
			@endif
			@if($user->status == 'user')
			<center><h1>Customer Detail</h1></center><br>
			@endif
			<div class="row">
				<div class="col-md-1 col-sm-1 col-xs-1">
					@php
					$status = Auth::user()->status;
					if($status == 'manager' || $status == 'reseller' || $status == 'dealer' || $status == 'subdealer'){ @endphp
					<div class="uprofile-buttons">
						@php
						$disabled=App\model\Users\DisabledUser::where('username' ,'=', $user->username);
						if($disabled->count() > 0){
						$btnStatus=$disabled->first()->status;
						if($btnStatus == 'disable'){
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
	$statmnt= 'Disabled by '. ucwords($disabled->first()->updated_by);
}else if($btnStatus == 'enable'){
$statmnt= 'Enabled by '. ucwords($disabled->first()->updated_by);
$class='btn btn-success';
$type='submit';
}
}else{
$class='btn btn-success';
$type='submit';
$statmnt= 'Enable / Disable';
}
@endphp
<form action="{{route('users.billing.enabledisable')}}" method="POST" >
	@csrf
	<input type="hidden" name="username" value="{{$user->username}}" >
	<input type="hidden" name="userid" value="{{$user->id}}">
	<button type="{{$type}}" class="{{$class}}" id="disableBtn" value="enable" onclick="toggle();" style="width: 100%;">{{$statmnt}}</button>
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
<div class="col-md-10 col-sm-10 col-xs-10">
	<div class="uprofile-content row">
		<div class="col-lg-12">
			<div class="header_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">Daily Data Usage</a></li>
							<li><a data-toggle="tab" href="#menu2">Data Used</a></li>
							<li ><a data-toggle="tab" href="#menu3">Detials</a></li>
							<li><a data-toggle="tab" href="#menu4">Verification</a></li>
						</ul>
						<div class="tab-content">
							<div id="home" class="tab-pane fade in active">
								<div class="row">
									<div class="col-md-12 " >
										@include('users.billing.admin_users_graph')
									</div>
								</div>
							</div>
							@php
							$totalDownload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctoutputoctets');
							$tDownMonth= App\model\Users\RadAcct::where('username', $user->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctoutputoctets');
							$totalupload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctinputoctets');
							$tupMonth= App\model\Users\RadAcct::where('username', $user->username)->where('acctstarttime','>=',DATE('Y-m-01'))->where('acctstarttime','<=',DATE('Y-m-t'))->sum('acctinputoctets');
							@endphp
							<div id="menu2" class="tab-pane fade">
								<div class="row">
									<div class="col-md-6" style="margin-top: 30px;">
										<center>
											<h3>Total Download</h3>
											<i class="fa fa-download" style="font-size: 65px;"></i>
											<h3>{{ByteSize($totalDownload)}}</h3>
										</center>
									</div>
									<div class="col-md-6" style="margin-top: 30px;">
										<center>
											<h3>Total Upload</h3>
											<i class="fa fa-upload" style="font-size: 65px;"></i>
											<h3>{{ByteSize($totalupload)}}</h3>
										</center>
									</div>
									<div class="col-md-12" style="margin-top: 50px; overflow-x: auto;">
										<table class="table table-bordered" style="overflow: auto;">
											@php
											$mac	=App\model\Users\RadCheck::where(['username' => $user->username, 'attribute'=>'Calling-Station-Id'])->first();
											@endphp
											<tr>
												<th>Total Login</th>
												<th>Last Login</th>
												<th>Monthly <br>
												Download | Upload</th>
												<th>Mac Address
													<br>
													@php
													if($mac->value!='NEW'){
													@endphp
													<form
													action="{{route('users.billing.user_detail')}}" method="POST" >
													@csrf
													<input type="hidden" name="clearmac" value="{{$user->username}}">
													<input type="hidden" name="userid" value="{{$user->id}}">
												</form>
												@php
											}
										@endphp</th>
										<th>IP Address</th>
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
								<td>{{ByteSize($tDownMonth)}} | {{ByteSize($tupMonth)}}</td>
								<td>{{$mac->value}}<hr>
									@if($mac->value!="NEW")
									@php
									$mv=explode('-', $mac->value);
									$my_mac = $mv[0].'-'.$mv[1].'-'.$mv[2];
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
							<td>N/A</td>
							@endif
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div id="menu3" class="tab-pane fade">
			<div style="overflow-x: auto;">
				<table class="table table-bordered">
					<tbody>
						@if($user->status == 'reseller' || $user->status == 'dealer' || $user->status == 'manager')
						<tr>
							<td>Manager Id</td>
							<td>{{$user->manager_id}}</td>
						</tr>
						@endif
						@if($user->status == 'dealer')
						<tr>
							<td>Reseller Id</td>
							<td>{{$user->resellerid}}</td>
						</tr>
						@endif
						<tr>
							<td>Username</td>
							<td>{{$user->username}}</td>
						</tr>
						@if($user->status != 'manager' && $user->status != 'user')
						<tr>
							<td>Profile</td>
							<td>
								@if($user->status != 'user')
								@foreach($userProfileRates as $uPr)
								<span class="badge badge-default" style="background-color:{{$uPr->profile->color}}">
									{{$uPr->profile->name}}
								</span>
								@endforeach
								@endif
							</td>
						</tr>
						@endif
						@if($user->status == 'user' && $profile !='')
						<tr>
							<td>Profile</td>
							<td>
								<span class="badge badge-default" style="background-color:{{$profile->color}}">	{{$profile->name}}
								</span>
							</td>
						</tr>
						@endif
						@if($user->status == 'user' && $cur_profile !='')
						<tr>
							<td>Current Profile</td>
							<td>
								<span class="badge badge-default" style="background-color:#8c8e8c">	{{$cur_profile->name}}
								</span>
							</td>
						</tr>
						@endif
						<tr>
							<td>Full Name</td>
							<td>{{$user->firstname}} {{$user->lastname}}</td>
						</tr>
						<tr>
							<td>Landline# | Mobile#2</td>
							<td>
								@if($user->homephone != '')
								{{$user->homephone}}
								@else N/A
								@endif
							</td>
						</tr>
						<tr>
							<td>Address</td>
							<td>{{$user->address}}</td>
						</tr>
						@if($user->status == 'user')
						<tr>
							<td>Last  Expire ON</td>
							<td>{{$userstatusinfo->card_charge_on}}</td>
						</tr>
						<tr>
							<td> Charge ON</td>
							<td>{{$userstatusinfo->card_charge_on}}</td>
						</tr>
						<tr>
							<td> Expire ON</td>
							<td>{{$userstatusinfo->card_expire_on}}</td>
						</tr>
						@endif
					</tbody>
				</table>
			</div>
		</div>
		<div id="menu4" class="tab-pane fade">
			<div class="row">
				<div class="col-md-12">
					<table  class="table table-bordered" style="overflow: auto;">
						<tr>
							<td>NIC / NTN / Passport / Overseas</td>
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
								N/A
								<span class="fa fa-close" style="color: red"></span>
								@endif
							</td>
						</tr>
						<tr>
							<td>Mobile</td>
							<td>{{$mobile}}
								@if($mob != 0)
								<span class="fa fa-check" style="color: green"></span>
								@else
								N/A
								<span class="fa fa-close" style="color: red"></span>
								@endif
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row" id="hmm">
				<div class="form-group">
					<p style="text-align: center;" >The <span class="text-danger"> CNIC VERIFICATION </span> used for active your Recharge process if any user is not verified you can't recharge these id's First verify user then recharge
					</p>
					<p style="text-align: center;"> براۓ مہربانی یوزر  ریچارج کرنے سے پہلے یوزر  کا شناسختی کارڈ  تصدیق کریں دوسری صورت میں آپ یوزر  کو ریچارج نہیں کر سکتے شکریہ </p>
				</div>
				<div class="">
					<div class="col-md-6">
						<div style="margin-left: 80px;"><p>
							<span class="text-danger">Front of your CNIC</span></p>
						</div>
						<div class="field"  align="center">
							<div>
								<input type="hidden" name="usname" class="user">
								@if($nicF != '' && $nicB != '')
								<input type="hidden" name="select_file" id="select_file" value="{{$nicF}}">
								@if(Auth::user()->status == 'user')
								<img src="{{asset('UploadedNic/'.$nicF)}}" alt="" width="250px;" height="140px;">
								@else 
								<img src="{{asset('sub_dealerNic/'.$nicF)}}" alt="" width="250px;" height="140px;">
								@endif
								@else
								<input type="file" accept="image/*" name="select_file" id="select_file" onchange="preview_image(event)" required>
								@endif
								<img id="output_image" style="display: none"/>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div style="margin-left: 80px;"><p>
							<span class="text-danger">Back of your CNIC</span></p>
						</div>
						<div class="field"  align="center">
							@if($nicB != '' && $nicF != '' )
							<input id="nicback" type="hidden" value="{{$nicB}}" name="nicback">
							@if(Auth::user()->status == 'user')
							<img src="{{asset('UploadedNic/'.$nicB)}}" alt="" width="250px;" height="140px;">
							@else 
							<img src="{{asset('sub_dealerNic/'.$nicB)}}" alt="" width="250px;" height="140px;">
							@endif
							@else
							<input id="nicback" type="file" accept="image/*" name="nicback" onchange="preview_images(event)" required>
							@endif           
							<img id="output_images" style="display: none"/>
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
<!-- END CONTENT -->
</div>
@endsection
@section('ownjs')
<script>
	$(document).ready(function(){
		$.ajax({
			type: "POST",
			url: "{{route('users.userPanel.checkExpire')}}",
			success: function(data){
				if(data == "expire"){
					$("#myModal").modal('show');
				}
			}
		});
	});
</script>
<script type="text/javascript">
	function showpass (value) {
		if(value=='hide'){
			$('#showpassword').html("{{isset($userRedCheck->value) ?$userRedCheck->value : ''}}");
			$('#hide').attr('id','show');
		}
		else{
			$('#showpassword').html("********");
			$('#show').attr('id','hide');
		}
	}
</script>
@endsection
<!-- Code Finalize -->