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
<style>
	.field-icon {
		float: right;
		margin-left: -20px;
		margin-top: -22px;
		position: relative;
		z-index: 2;
		margin-right: 10px;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="content-body">
				<div class="header_view">
					<h2>Manage DHCP Servers
						<span class="info-mark" onmouseenter="popup_function(this, 'manage_dhcp_server_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<form  action="{{route('admin.addDHCP')}}" method="POST" >
					@csrf
					<div class="row">
						<div class="col-lg-4 col-md-6">
							<div class="form-group position-relative">
								<input type="hidden" name="id" id="id" value="0">
								<label  class="form-label">Server Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_name_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="" name="name" id="name" class="form-control"  placeholder="Example : AirPort PoP" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group position-relative">
								<label  class="form-label">Server IP Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_ipaddress_assgin_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="" id="ip" pattern="\b((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(\.|$)){4}\b" name="ip" class="form-control"  placeholder="1.1.1.1" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_assign_username_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="" id="username" name="username" class="form-control"  placeholder="Username" required>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group position-relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_assgin_password_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="password" value="" id="pass" name="password" class="form-control"  placeholder="Password"  required>
								<span toggle="#pass" class="fa fa-fw fa-eye field-icon toggle-password"></span>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="form-group position-relative">
								<label  class="form-label">Server C0 Location <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dhcp_server_colocation_assgin_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" value="" id="address" name="address" class="form-control"  placeholder="Co Location"  required>
							</div>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="text-right pull-right" style="margin-top:30px">
								<button type="submit" class="btn btn-primary text-white " id="updateBTN">Add (DHCP Server)</button>
								<button type="button" class="btn btn-danger text-white" onclick="resetForm()" id="resetBTN">Reset</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<hr>
			<div  class="content-body" style="padding: 15px">
				<div class="table-responsive ttable">
					<table id="example1" class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Server Name</th>
								<th>Server IP Address</th>
								<th>Username</th>
								<th>Server Co Location</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($serverData as $key => $item)
							<tr>
								<td>{{$key+1}}</td>
								<td class="td__profileName">{{$item['name']}}</td>
								<td>{{$item['ip']}}</td>
								<td>{{$item['username']}}</td>
								<td>{{$item['address']}}</td>
								<td><Button class="btn btn-primary" onclick="editData({{$item['id']}})">Edit</Button></td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div> 
		</section>
	</section>
</div>
@endsection
@section('ownjs')
<!-- Select User List -->
<script>
	$(document).ready( function () {
		$('#example1').DataTable();
	} );
	function loadUserList(option){
		let userStatus = option.value;
// ajax call: jquery
console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
$.get(
	"{{route('admin.user.status.usernameList')}}?status="+userStatus,
	function(data){
		console.log(data);
		let content = "<option>select "+userStatus+"</option>";
		$.each(data,function(i,obj){
			content += "<option value='"+obj.username+"'>"+obj.username+"</option>"
		});
		$("#username-select").empty().append(content);
	});
}
</script>
<script>
	function editData(id){
		$.ajax({
			url: "{{route('admin.DHCPUpdate')}}",
			type: "GET",
			data: {id:id},
			dataType: "json",
			success: function(res){
				$("#id").val(res.id);
				$("#name").val(res.name);
				$("#ip").val(res.ip);
				$("#username").val(res.username);
				$("#address").val(res.address);
				$("#pass").val(res.password);
				$("#updateBTN").text("Update DHCP Server").removeClass('btn-success').addClass('btn-primary');
// console.log(res);
}
});
	}
	function resetForm(){
		$("#id").val('');
		$("#name").val('');
		$("#ip").val('');
		$("#username").val('');
		$("#address").val('');
		$("#pass").val('');
		$("#updateBTN").text("Add DHCP Server").removeClass('btn-primary').addClass('btn-success');
	}
	$(".toggle-password").click(function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});
</script>
@endsection
<!-- Code Finalize -->