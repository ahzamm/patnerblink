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
	.USMLCheckbox{
		width:25px;
		height:25px;
	}
	.dataTables_filter{
		margin-left: 60%;
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
	#mob select,
	#app select,
	#res select,
	#nic select,
	#username select,
	#sno select,
	#ip select{
		display: none;
	}
	#snackbar {
		visibility: hidden;
		min-width: 250px;
		margin-left: -125px;
		background-color: #3CB371;
		color: #fff;
		text-align: center;
		border-radius: 2px;
		box-shadow: 0 15px 10px #777;
		padding: 16px;
		position: fixed;
		z-index: 1;
		left: 85%;
		top: 70px;
		font-size: 17px;
	}
	#snackbar.show {
		visibility: visible;
		-webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
		animation: fadein 0.5s, fadeout 0.5s 2.5s;
	}
	@-webkit-keyframes fadein {
		from {top: 0; opacity: 0;} 
		to {top: 70px; opacity: 1;}
	}
	@keyframes fadein {
		from {top: 0; opacity: 0;}
		to {top: 70px; opacity: 1;}
	}
	@-webkit-keyframes fadeout {
		from {top: 70px; opacity: 1;} 
		to {top: 0; opacity: 0;}
	}
	@keyframes fadeout {
		from {top: 70px; opacity: 1;}
		to {top: 0; opacity: 0;}
	}
</style>
@endsection
@section('content')
@foreach($admins as $admin){
{{$admin->firstname.' '.$admin->lastname}}
}
@endforeach
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<section class="box">
				<div class="header_view" style="padding-top: 20px;">
					<h2 style="font-size: 26px;">Admin Roles Management
						<span class="info-mark" onmouseenter="popup_function(this, 'menu_usersside_access_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>										
					<h2 style="font-size: 20px;">Admin Member : <span style="color:green">{{$user->firstname.' '.$user->lastname.' | @'.$user->username}}</span></h2>
				</div>
				<div class="content-body">
					<div class="table-responsive">					
						<table class="table table-striped  display" style="height:30%" id="menu-management">
							<thead>								
								<tr>
									<th>Serial#</th>
									<th>Menu</th>
									<th>Module</th>
									<th>Action On & Off</th>
								</tr>
							</thead>								
							<tbody>
								@php
								$num=1;
								@endphp
								@foreach($access as $datas)
								<tr>
									<td>{{$num++}}</td>
									<td>{{$datas->menu}}</td>
									<td>{{$datas->submenu}}</td>
									<td>
										@php									
										$check ='';								
										$loadData = App\model\admin\AdminMenuAccess::where('sub_menu_id',$datas->id)->where('user_id',$user_id)->select('status')->first();
										if(!empty($loadData)){
										$check = $loadData->status;									
									}
									$isCheck = $check;     
									//dd($isCheck);
									@endphp
									@if($isCheck == 0)
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox" name="chk" onchange="changeAccess(this, '{{$user_id}}','{{$datas->id}}');myFunction()">
										<span class="slider square" ></span>
									</label>
									@elseif($isCheck == 1)
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox" checked  name="chk" onchange="changeAccess(this, '{{$user_id}}','{{$datas->id}}');myFunction()">
										<span class="slider square" ></span>
									</label>
									@else
									<label class="switch" style="width: 46px;height: 19px;">
										<input type="checkbox"   name="chk" onchange="changeAccess(this, '{{$user_id}}','{{$datas->id}}');myFunction()">
										<span class="slider square" ></span>
									</label>
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>								
					</table>							
				</div>
			</div>
		</section>
	</section>
</section>
</div>	
@endsection
@section('ownjs')	
<script>
	function changeAccess(checkBox,user_id,id){
		let isCheck = checkBox.checked;
		$.ajax({
			url:"{{route('admin.adminsubmenu.update')}}",
			method:"POST",
			data:{isChecked:isCheck, user_id:user_id,id:id},
			success:function(data){                 
			}
		});
	}
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#menu-management').DataTable();
	} );
</script>
@endsection
<!-- Code Finalize -->