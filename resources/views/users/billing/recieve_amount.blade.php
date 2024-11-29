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
			<div class="">
				<form action="{{route('users.billing.post',['status' => 'recieve'])}}" method="POST" id="myform">
					@csrf
					<div class="header_view">
						<h2>Cash Recieved Amount
							<span class="info-mark" onmouseenter="popup_function(this, 'cash_received_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
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
									<div class="col-md-6 col-sm-12">
										<label >Add To Account:</label>
										<div class="form-group">
											<div class="btn-group btn-group-toggle" data-toggle="buttons">
												@if(Auth::user()->status == 'manager')
												<label class="btn btn-primary active">
													<input type="radio" name="options" value="reseller" id="option2" onchange="loadUserList(this)" autocomplete="off"> Reseller
												</label>
												@endif
												@if(Auth::user()->status == 'reseller')
												<label class="btn btn-primary active">
													<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Contractor
												</label>
												@endif
												@if(Auth::user()->status == 'account')
												<label class="btn btn-primary active">
													<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Contractor
												</label>
												@endif
												@if(Auth::user()->status == 'dealer')
												<label class="btn btn-primary active">
													<input type="radio" name="options" value="subdealer" id="option4" onchange="loadUserList(this)" autocomplete="off"> Trader
												</label>
												@endif
											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-12">
										<div class="form-group">
											<label >Select Account: <span style="color: red">*</span></label>
											<select name="username" id="username" class="js-select2" required >
												<option value="">Select Account</option>
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
										<option  style="color: {{$payment_type == 'credit'?('green'):'black'}}" value="{{$data->username}}">{{$data->username}}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="table-responsive">
								<table class="table" style="min-width: 500px">
									<thead>
										<tr>
											<th>Amount (PKR) <span style="color: red">*</span></th>
											<th>Paid By <span style="color: red">*</span></th>
											<th>Comment <span style="color: red">*</span></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><input type="Number" class="form-control"  placeholder="0.00" id="amount" required name="amount" min="1" ></td>
											<td><input type="text" class="form-control"  placeholder="Paid by" id="paidBy" required name="paidBy"></td>
											<td><textarea class="form-control" name="comment" id="comment" placeholder="Comment here !!" ></textarea></td>
										</tr>
										<tr>
											<td colspan="3" style="color: darkgreen; font-weight: bold">	
												<span style="padding-right: 10px;"><input class="radio2"  type="radio" id="r2" name="cash_amount"  value="no" disabled > <label for="r2"> Cash Reciept Only </label> <span style="color: red">*</span></span>
												<input class ="radio2"   type="radio" id="r3" name="cash_amount" value="yes" disabled ><label for="r3"> Transfer Reversal </label> <span style="color: red">*</span>
											</td>
										</tr>
										<tr>
											<td colspan="3" class="theme-bg">Payment Method</td>
										</tr>
										<tr>
											<td colspan="3">
												<div class="btn-group btn-group-toggle" data-toggle="buttons">
													<label class="btn btn-primary active" onclick="cashSelection()" style="border-right: 1px solid #fff;">
														<input type="radio" name="paymentType" value="chash" id="option3" autocomplete="off" checked="true"> Cash
													</label>
													<label class="btn btn-primary" onclick="bankSelection()">
														<input type="radio" name="paymentType" value="bank"> Bank
													</label>
												</div>
												<div style="display: none;" id="bank">
													<div class="form-group">
														<br><label for="bankname">Select Bank</label><br>
														<select name="bankname" id="bankname" class="form-control">
															<option value="">Select Bank</option>
															<option value="BAHLCP">Bank Al Habib</option>
															<option value="HBL">Habib Bank</option>
														</select>
														<span class="p-3 text-danger font-weight-bold" style="display: none" id="msg">Please Select Bnak Name before Submit</span>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="col-md-12 col-xs-12 ">
							<div class="form-group pull-right">
								<button type="button" class="btn btn-primary" onclick="ConfromMsg(amount.value,paidBy.value,comment.value,username.value,cash_amount.value)">Recieve Amount</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</form>
</div>
</section>
</section>
</div>
<!-- Username And Password Modal Start -->
<div class="modal fade" id="error_msg" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header" style="background-color:red">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Alert</h4>
			</div>
			<div class="modal-body">
				<p style="text-align:center;font-size:18px;">Select Account and Fill Amount, Paid by to Receive Amount.</p>
				<p style="text-align:center;font-size:20px;">تمام معلومات کا درست انتخاب کریں</p>
			</div>
		</div>
	</div>
</div>
<!-- Username And Password Modal Start -->
@endsection
@section('ownjs')
<script type="">
	function ConfromMsg(amount,paidBy,comment,username,cash_amount)
	{
		if(amount!=""&& paidBy!="" && comment!="" && username!="" && cash_amount!="" ){
			$("#msg").hide();
			var submit = confirm("Are you sure want to receive this?? \n"+formatMoney(amount) +" \n"+inWords(amount));
			var checkvalue = $('input[name="paymentType"]:checked').val();
			if(checkvalue == "bank"){
				var bn = $("#bankname").val();
				if(bn != ""){
					if (submit == true) {
						$("#myform").submit();
					}
				}else{
					$("#msg").show();
				}
			}else{
				if (submit == true) {
					$("#myform").submit();
				}
			}
		}else{
			$('#error_msg').modal('show');
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
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
</script>
<script type="text/javascript">
	$(document).on('change','#username',function(){
		var a=	$(this).val();
		$.ajax({
			type: "POST",
			url: "{{route('users.check.dues')}}",
			data:'username='+a,
			success: function(data){
// For Get Return Data
if(data =="block"){
	$('#r2').attr('disabled', true);
	$('#r3').attr('disabled', false);
}else{
	$('#r2').attr('disabled', false);
	$('#r3').attr('disabled', true);
}
}
});
	})
	function bankSelection(){
		$("#bank").show();
		$("#bankname").prop('required',true);
	}
	function cashSelection(){
		$("#bank").hide();
		$("#bankname").prop('required',false);
	}
</script>
@endsection
<!-- Code Finalize -->