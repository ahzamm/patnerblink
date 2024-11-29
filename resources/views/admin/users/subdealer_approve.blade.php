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
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="header_view">
				<h2>Traders (FOC) IDS Approval
					<span class="info-mark" onmouseenter="popup_function(this, 'trader_id_foc_approval');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>                
				</h2>
			</div>
			<section class="box" style="padding-top:20px">
				<div class="content-body">    
					<table id="example-1" class="table table-bordered dt-responsive w-100">
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
							$active = App\model\Users\RaduserGroup::where(['username' => $subdealer->username])->first();
							$status = $active['groupname'];
							if($status == "DISABLED"){
							$active1 ='Deactive';
						}else{
						$active1 ='Active';
					}
					@endphp
					<tr>
						<td scope="row">{{$sno++}}</td>
						<td>{{$subdealer->resellerid}}</td>
						<td>{{$subdealer->dealerid}}</td>
						<td class="td__profileName">{{$subdealer->sub_dealer_id}}</td>
						<td>{{
							DB::table('user_info')
							->join('user_status_info', 'user_status_info.username', '=', 'user_info.username')
							->where('user_status_info.card_expire_on', '>=', today())
							->where(['status' => 'user','resellerid' => $subdealer->resellerid,'sub_dealer_id' => $subdealer->sub_dealer_id])->count()
						}}</td>
						<td>
							<label class="switch" style="width: 46px;height: 19px;">
								@php 
								$cc = $active['groupname'] == "DISABLED" ? '' : 'checked';
								@endphp
								<input type="checkbox" onchange="statChange(this, '{{$subdealer->username}}')"  {{$cc}} >
								<span class="slider square" ></span>
							</label>
						</td>
						<td>{{$active1}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script>
	function statChange(checkBox, username){
		let isCheck = checkBox.checked;
		console.log("isCheck: " + isCheck);
// ajax call: jquery
$.post(
	"{{route('admin.approve.ajax.post')}}",
	{
		"isChecked" : ""+isCheck+"",
		"username" : username
	},
	function(data){
		console.log(data);
	});
}
</script>
@endsection
<!-- Code Finalize -->