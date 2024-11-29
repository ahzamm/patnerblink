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
	textarea {
		resize: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div>
				<form >
					@csrf
					<div class="header_view">
						<h2>Third Party Transfer Commission
							<span class="info-mark" onmouseenter="popup_function(this, 'third_party_commission');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					@if(session('error'))
					<div class="alert alert-error alert-dismissible show">
						{{session('error')}}
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
					@endif
					@if(session('success'))
					<div id="alert" class="alert alert-success alert-dismissible">
						{{session('success')}}
					</div>
					@endif
					<section class="box ">
						<header class="panel_header"></header>
						<div class="content-body">
							<div class="row">
								<div class="col-xs-12">
									<div class="col-md-3"></div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group position-relative">
											@if(Auth::user()->status == 'manager')
											<label  class="form-label">Reseller <span style="color: red">*</span></label>
											<select name="username" id="username" class="form-control" required >
												<option value="">Select Reseller</option>
												@foreach($userCollection as $data)
												<option value="{{$data->username}}">{{$data->username}}</option>
												@endforeach
											</select>
											@endif
											@if(Auth::user()->status == 'reseller' || Auth::user()->status == "inhouse")
											<label  class="form-label">Contractor <span style="color: red">*</span></label>
											<select name="username" id="username" class="form-control" required >
												<option value="">Select Contractor</option>
												@foreach($userCollection as $data)
												@php
												$data1='';
												$data1 = App\model\Users\DealerProfileRate::where('dealerid',$data->dealerid)->select('payment_type')->first();
												if(empty($data1)){
												$payment_type='';
											}else{
											$payment_type=$data1['payment_type'];
										}
										@endphp
										<option style="color: {{$payment_type == 'credit'?('green'):'black'}}" value="{{$data->username}}">{{$data->username}}</option>
										@endforeach
									</select>
									@endif
									<span class="helping-mark" data-toggle="tooltip" data-placement="left" title="
									<p style='margin-bottom:0'>Select Contractor to whom you want to transfer commission</p>
									<p>ٹھیکیدار کو منتخب کریں جس کو آپ کمیشن منتقل کرنا چاہتے ہیں۔</p>" 
									data-html="true"><i class="fa fa-question"></i></span>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="table-responsive">
								<table class="table" style="min-width:500px">
									<thead>
										<tr>
											<th>Amount (PKR) <span class="position-relative" style="color: red">*</span></th>
											<th>Comment <span style="color: red">*</span></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><input type="Number" class="form-control"  placeholder="0.00" required name="amount" id="amounts"></td>
											<td><textarea class="form-control" name="comment"  placeholder="Comment Here !!!" id="comment" required></textarea></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group pull-right">
								<button type="button" class="btn btn-primary" onclick="showConfrom(username.value,amounts.value,comment.value)">Recieve Amount Now</button>
							</div>
						</div>
					</div>
				</div>
			</section>
		</form>
		<!-- Content Start -->
		<form action="{{route('users.billing.post',['status' => 'nonecash'])}}" method="POST">
			@csrf
			<div class="modal fade" id="confromMsg" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<input type="hidden" name="username" id="showUsername" required="">
							<input type="hidden" name="amount" id="showAmount" required="">
							<input type="hidden" name="comment" id="showcomment" required="">
							<center>
								<h4> Are you sure want to Recieve amount ? That is </h4>
								<h2 id="Amount"></h2>
								<h4 id="amounInWords"></h4>
							</center>
						</div>
						<div class="modal-footer">
							<button type="submit" id="" class="btn btn-danger">Yes</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<!-- Content End -->
	</div>
</section>
</section>
</div>
<!-- Username And Password Modal -->
<div class="modal fade" id="error_msg" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content Start-->
		<div class="modal-content">
			<div class="modal-header" style="background-color:red">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Alert</h4>
			</div>
			<div class="modal-body">
				<p style="text-align:center;font-size:18px;">Select Account (ID) and Fill Amount.</p>
				<p style="text-align:center;font-size:20px;">تمام معلومات کا درست انتخاب کریں</p>
			</div>
		</div>
	</div>
</div>
<!-- Modal Content End -->
@endsection
@section('ownjs')
@include('freezeAlert');
<script type="text/javascript">
	if(localStorage.noneCash){
		$('#freezeLayer').modal('show');
	}else{
		localStorage.noneCash = "1";
		window.onbeforeunload = function () {
			localStorage.noneCash = "";
		};
	}
</script>
<script type="text/javascript">
	function showConfrom(username,amounts,comment)
	{
		if (username!="" && amounts!="" && comment!="") {
			$('#showUsername').val(username);
			$('#showAmount').val(amounts);
			$('#showcomment').val(comment);
			var amount= "Rs. "+formatMoney(amounts)+"/-";
			var inwords=inWords(amounts);
			$('#Amount').html(amount);
			$('#amounInWords').html(inwords.toUpperCase());
			$('#confromMsg').modal('show');
		}
		else{
			$('#error_msg').modal('show');
// alert('Kindly fill all field');
}
}
function formatMoney(n, c, d, t) {
	var c = isNaN(c = Math.abs(c)) ? 2 : c,
	d = d == undefined ? "." : d,
	t = t == undefined ? "," : t,
	s = n < 0 ? "-" : "",
	i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
	j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
</script>
<script >
	function chequeDetail(){
		$('#showChequeDetails').slideDown();
		$('#onlinebank').hide();
// Add Required From Check Details
$("input[name='bankname']").attr('required', 'required');
$("input[name='checkNo']").attr('required', 'required');
// Remove Required To Onlinebankname
$("input[name='onlinebankname']").removeAttr('required');
// Clear Value(s) Of Onlinebankname
$("input[name='onlinebankname']").val("");
}
function slideup()
{
	$('#showChequeDetails').slideUp();
	$('#onlinebank').slideUp();
// Clear Values(s) Of OnloneDetails And CheckDetails
$("input[name='onlinebankname']").val("");
$("input[name='bankname']").val("");
$("input[name='checkNo']").val("");
}
function onlineDetail(){
	$('#onlinebank').slideDown();
	$('#showChequeDetails').hide();
// Add Required To Onlinebankname
$("input[name='onlinebankname']").attr('required', 'required');
// Remove Required From Check Details
$("input[name='bankname']").removeAttr('required');
$("input[name='checkNo']").removeAttr('required');
// Clear Values(s) Of CheckDetails
$("input[name='bankname']").val("");
$("input[name='checkNo']").val("");
}
</script>
<script>
	function loadUserList(option){
		let userStatus = option.value;
		console.log("URL: " + "{{route('admin.user.status.usernameList')}}?status="+userStatus);
		$.get(
			"{{route('admin.user.status.usernameList')}}?status="+userStatus,
			function(data){
				console.log(data);
				let content = "<option>select "+userStatus+"</option>";
				$.each(data,function(i,obj){
					content += "<option value='"+obj.username+"'>"+obj.username+"</option>";
				});
				$("#username").empty().append(content);
			});
	}
</script>
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
</script>
@endsection
<!-- Code Finalize -->