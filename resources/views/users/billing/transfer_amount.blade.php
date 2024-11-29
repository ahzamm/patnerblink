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
	#loadingnew{
		display: none;
	}
	#transferTo{
		font-size: 26px;
		font-weight: bold;
	}
	.blink__effect{
		color: green;
		animation: 1s ease blinkMe alternate infinite;
	}
	@keyframes blinkMe {
		0%{
			opacity: 0%
		}
		100%{
			opacity: 100%
		}
	}
</style>
@endsection
@section('content')
@php
$checkCardType = 'Transfer';
if(Auth::user()->status == 'dealer'){
$checkBillingType = App\model\Users\DealerProfileRate::where('dealerid',Auth::user()->dealerid)->first()->billing_type;
$checkCardType = $checkBillingType == 'amount' ? 'Transfer' : ($checkBillingType == 'card'  ? 'CardTransfer' : NULL);
// dd($checkCardType);
}
@endphp
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			@if($checkCardType == 'Transfer')
			<div class="">
				<div class="header_view">
					<h2>Transfer Amount
						<span class="info-mark" onmouseenter="popup_function(this, 'transfer_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				@if(session('error'))
				<div class="alert alert-error alert-dismissible">
					{{session('error')}}
				</div>
				@endif
				@if(session('success'))
				<div id="alert" class="alert alert-success alert-dismissible">
					{{session('success')}}
				</div>
				@endif
				<section class="box ">
					@if(Auth::user()->status !="manager")
					<header class="panel_header"></header>
					@endif
					<div class="content-body">
						<div class="row">
							<form>
								@csrf
								<div class="col-xs-12">
									<label >Add To Account:</label>
									<div class="form-group">
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											@if(Auth::user()->status == 'manager')
											<label class="btn btn-primary active">
												<input type="radio" name="options" id="option2" onchange="loadUserList(this)" autocomplete="off" > Reseller
											</label>
											@endif
											@if(Auth::user()->status == 'reseller')
											<label class="btn btn-secondary active">
												<input type="radio" name="options" id="option3" autocomplete="off"> Contractor
											</label>
											@endif
											@if(Auth::user()->status == 'dealer')
											<label class="btn btn-secondary active">
												<input type="radio" name="options" id="option4" autocomplete="off"> Trader
											</label>
											@endif
										</div>
									</div>
									<div class="col-lg-4 col-md-6">
										<div class="form-group">
											@if(Auth::user()->status == 'manager')
											<label  class="form-label">Reseller Account <span style="color: red">*</span></label>
											<select class="js-select2 accToTransfer" name="username" required >
												<option value="">Select Reseller</option>
												@foreach($managerCollection as $manager)
												<option value="{{$manager->username}}">{{$manager->username}}</option>
												@endforeach
											</select>
											@endif
											@if(Auth::user()->status == 'reseller')
											<label  class="form-label">Contractor Account <span style="color: red">*</span></label>
											<select class="js-select2 accToTransfer" name="username" required >
												<option value="">Select Contractor</option>
												@foreach($managerCollection as $manager)
												@php
												$data1='';
												$data1 = App\model\Users\DealerProfileRate::where('dealerid',$manager->dealerid)->select('payment_type')->first();
												if(empty($data1)){
												$payment_type='';
											}else{
											$payment_type=$data1['payment_type'];
										}
										@endphp
										<option style="color: {{$payment_type == 'credit'?('green'):'black'}}" value="{{$manager->username}}">{{$manager->username}}</option>
										@endforeach
									</select>
									@endif
									@if(Auth::user()->status == 'inhouse')
									<label  class="form-label">Contractor Account</label>
									<select class="js-select2 accToTransfer" name="username" required >
										<option value="">Select Contractor</option>
										@foreach($managerCollection as $manager)
										@php
										$data1='';
										$data1 = App\model\Users\DealerProfileRate::where('dealerid',$manager->dealerid)->select('payment_type')->first();
										if(empty($data1)){
										$payment_type='';
									}else{
									$payment_type=$data1['payment_type'];
								}
								@endphp
								<option style="color: {{$payment_type == 'credit'?('green'):'black'}}" value="{{$manager->username}}">{{$manager->username}}</option>
								@endforeach
							</select>
							@endif
							@if(Auth::user()->status == 'dealer')
							<label  class="form-label">Traders Account <span style="color: red">*</span></label>
							<select class="js-select2 accToTransfer" name="username" required id="username" >
								<option value="">Select Trader</option>
								@foreach($managerCollection as $manager)
								<option value="{{$manager->username}}">{{$manager->username}}</option>
								@endforeach
							</select>
							@endif
							@if(Auth::user()->status == 'subdealer')
							<label  class="form-label">Sub Trader Account <span style="color: red">*</span></label>
							<select class="js-select2 accToTransfer" name="username" required id="username" >
								<option value="">Select Sub Trader</option>
								@foreach($managerCollection as $manager)
								<option value="{{$manager->username}}">{{$manager->username}}</option>
								@endforeach
							</select>
							@endif
						</div>
					</div>
					<div class="col-lg-4 col-md-6">
						<div class="form-group">
							<label  class="form-label">Tranfer Amount (PKR) <span style="color: red">*</span></label>
							<input type="Number" name="amount" min="0" step="0.01" id="amounts" min="0" class="form-control"  placeholder="0.00" required>
						</div>
					</div>
					@if(Auth::user()->status =='reseller' || Auth::user()->status =='inhouse')
					<div class="col-lg-4">
						<div class="form-group">
							<label  class="form-label">Comments <span style="color: red">*</span></label>
							<textarea name="comment" id="comment" class="form-control"  placeholder="Comments Here !!!" ></textarea>
						</div>
					</div>
					@else
					<div class="col-lg-4">
						<input type="hidden" name="comment" id="comment" class="form-control"  placeholder="0" >
					</div>
					@endif
					<div class="col-xs-12">
						<div class="form-group pull-right">
							<button type="button" class="btn btn-primary" onclick="showConfrom(username.value,amounts.value,comment.value)" style="margin-top: 28px;">Transfer Now</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<!--  Modal Start -->
<form action="{{route('users.billing.post',['status' => 'transfer'])}}" method="POST" id="myform">
	@csrf
	<div class="modal fade" id="confromMsg" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center;color: white">Alert</h4>
				</div>
				<div class="modal-body">
					<input type="hidden" name="username" id="showUsername" required="">
					<input type="hidden" name="comment" id="showcommnet" required="">
					<input type="hidden" name="commision" id="showcommision" required="">
					<input type="hidden" name="amount" id="showAmount" required="">
					<center>
						<h4> Please confirm to transfer this amount to account <span id="transferTo"></span> ?</h4>
						<h4>This transaction cannot be revert</h4>
						<h4>
							براہ کرم بھیجنے والی رقم کو تصدیق کریں کہ یہ درست اکائونٹ میں منتقل کی جا رہی ہے- غلط اکائونٹ میں منتقل کی ہوئی رقم ناقابل واپسی ہوگی- شکریہ
						</h4>
						<hr />
						<h2 id="Amount" class="blink__effect"></h2>
						<h4 id="amounInWords"></h4>
					</center>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
					<button type="submit" class="btn btn-primary" id="transferBtn">Confirm</button>
					<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
				</div>
			</div>
		</div>
	</div>
</form>
<!--  Modal End -->
</div>
@else
<div class="row">
	<div class="">
		<div class="header_view">
			<h2>Transfer Internet packages</h2>
		</div>
		<div class="">
			<section class="box">
				<div class="content-body">
					<div class="row">
						<div class="col-lg-8">
							<div class="alertMsgE alert-error alert-dismissible" style="display: none;padding: 10px;">
								<span id="msgError" style="font-size: 18px; color: white"></span>
							</div>
							<div id="alert" class="alertMsgS alert-success alert-dismissible" style="display: none;padding: 10px;">
								<span id="msgSuccess" style="font-size: 18px; color: white"></span>
							</div>
							{{-- <span id="msg" style="font-size: 18px; color: red"></span> --}}
							<form id="transferForm" style="padding: 20px">
								<div class="form-group" style="margin-bottom: 20px;">
									<label for="">Select Trader</label>
									<select name="subdealername" id="subdealername" onchange="fetchProfiles(this);" class="form-control" required>
										<option value="">Select Trader</option>
										@foreach ($subdealer as $item)
										<option value="{{$item->username}}">{{$item->username}}</option>
										@endforeach
									</select>
								</div>
								<div class="row" >
									<table class="table" style="border: none;">
										<tbody id="submenu-list">
											<tr class="tr">
												<td class="td-first"style="text-align: left;">
													<label for="" >Select Internet Profile</label>
													<select name="packagename[]" id="packagename" class="form-control" required>
														<option value="">Select Internet Profile</option>
													</select>
												</td>
												<td class="td-second" style="text-align: left;">
													<label for="" >Enter number of profile</label>
													<input type="number" min="1" class="form-control" name="no_card[]" id="no_card" placeholder="Enter number of profile" required>
												</td>
												<td style="vertical-align: bottom"><button class="btn btn-primary btn-sm my-1" type="button" id="btnAddSubMenu"><i class="fa fa-plus"></i></button></td>
											</tr>
										</tbody>
									</table>
								</div>
								<button type="submit" class="btn btn-primary pull-right">Submit</button>
							</form>
						</div>
						<div class="col-lg-4">
							<p><b>Note:</b> Using your amount, you can create:</p>
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Internet Profiles</th>
										<th>Profile Sum Up</th>
									</tr>
								</thead>
								<tbody>
									@php
									$totalPkg = 0;
									@endphp
									@foreach ($no_of_packages as $key => $pkg)
									<tr>
										<th>{{$key}}</th>
										<td>{{$pkg}}</td>
									</tr>
									@php
									$totalPkg += $pkg;
									@endphp
									@endforeach
									<tr>
										<th style="color: red">Total</th>
										<th style="color: red">{{$totalPkg}}</th>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12" style="margin-top: 30px;">
							<hr>
							<p style="color: red;font-size: 18px;font-weight: bold; text-align:center">Note: If you move the profile to a sub-dealer, it will not be changed or removed.</p>
							<h4 style="color: red;font-size: 22px;font-weight: bold;text-align: center">نوٹ: اگر آپ پروفائل کو سب ڈیلر میں منتقل کریں گے تو یہ نہ  تبدیلی ہوگی  اور نہ ہی اسے ہٹایا جائے گا۔ </h4>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
</div>
@endif
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
				<p style="text-align:center;font-size:18px">First Select Account (ID) and assign Amount.</p>
				<p style="text-align:center;font-size:20px;">تمام معلومات کا درست انتخاب کریں</p>
			</div>
		</div>
	</div>
</div>
<!-- Modal content End-->
@endsection
@section('ownjs')
<script type="text/javascript">
	function showConfrom(username,amounts,comment)
	{
		if (username!="" && amounts!="") {
			if(amounts > 0){
				$('#showUsername').val(username);
				$('#showcommnet').val(comment);
				$('#showAmount').val(amounts);
				var amount= formatMoney(amounts)+"/- (PKR)";
				var inwords=inWords(amounts);
				$('#Amount').html(amount);
				$('#confromMsg').modal('show');
			}else{
				alert('Cannot sent minus value');
			}
		}
		else{
			$('#error_msg').modal('show');
		}
	}
// 
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
	$("#myform").submit(function(){
		$('#transferBtn').hide();
		$('#loadingnew').show();
	});
</script>
<script>
	function checkinput(input)
	{
		if(input.val() == "")
		{
			$(input).css('border','1px solid red');
			return false;
		}
		else
		{
			$(input).css('border','1px solid #444951');
			return true;
		}
	}
	$(document).on('click','#btnAddSubMenu',function(){
		fristInput = $(this).parents('tr').find('td.td-first > select');
		secondInput = $(this).parents('tr').find('td.td-second > input');
		button = $(this);
		if(checkinput(fristInput) && checkinput(secondInput))
		{
			roData = "<tr class='tr'>"+$(button).parents('tr').html()+"</tr>";
			$('#submenu-list').append(roData);
			$(button).removeClass("btn-success").addClass("btn-danger").html("<i class='fa fa-trash'></i>").attr('id','btnDeleteSubMenu');   
			fristInput.prop('readonly',true);
			secondInput.prop('readonly',true);
		}
	});
	$(document).on('click','#btnDeleteSubMenu',function(){
		$(this).parents('tr').remove();
	});
</script>
<script>
	$("#transferForm").on('submit', function(e){
		pkgName = $( "#packagename option:selected" ).val();
		pkgCount = $("#no_card").val();
		e.preventDefault();
		$.ajax({
			url: "{{route('users.checkCard')}}",
			method:"post",
			dataType:"json",
// data: {pkgName:pkgName},
data:new FormData(this),
contentType: false,
cache: false,
processData: false,
success: function(data){
	if(data == "exceed"){
		$('.alertMsgE').show();
		$('#msgError').html("<span><strong> Exceed Number of Profile! Put less or equal given number of Profile </strong></span>");
		setTimeout(function(){
			$('.alertMsgE').fadeOut(); }, 5000);
	}else{
		cnfrm = window.confirm('Do you want to transfer Profiles?');
		if(cnfrm){
			cnfrm1 = window.confirm('Are You sure to transfer Profiles?');
			if(cnfrm1){
				cnfrm2 = window.confirm('Profile do not change or replace after you Press OK button?');
			}
			if(cnfrm2){
				transferCard();
			}
		}
	}
}
});
	});
	function transferCard(){
		var form_data = new FormData(document.getElementById("transferForm"));
		$.ajax({
			url: "{{route('users.transferCard')}}",
			method:"post",
			dataType:"json",
			data:form_data,
			contentType: false,
			cache: false,
			processData: false,
			success: function(data){
				$('.alertMsgS').show();
				$('#msgSuccess').html("<span><strong> Profile  Successfully Transferd </strong></span>");
				setTimeout(function(){
					$('.alertMsgS').fadeOut(); }, 5000);
				location.reload();
			}
		});
	}
</script>
<script>
	function fetchProfiles(username){
// alert(username.value);
$.ajax({
	type : 'get',
	url : "{{route('users.fetchProfile')}}",
	data:'username='+username.value,
	success:function(res){
		$('.tr').remove();
		data = '<tr class="tr"><td class="td-first"><select name="packagename[]" id="packagename" class="form-control" required><option value="">Select Profile</option></select></td><td class="td-second"><input type="number" min="1" class="form-control" name="no_card[]" id="no_card" placeholder="Enter number of profile" required></td><td style="vertical-align: bottom"><button class="btn btn-success btn-sm my-1" type="button" id="btnAddSubMenu"><i class="fa fa-plus"></i></button></td>	</tr>';
		$('#submenu-list').append(data);
		$("#packagename").empty();
		$("#packagename").append('<option value>Select</option>');
		$.each(res,function(key,value){
			$("#packagename").append('<option value="'+value.groupname+'">'+value.name+'</option>');
		})
	}
});
}
</script>
<script type="text/javascript">
	$(document).on('change','.accToTransfer',function(){
		var accToTransfer = $(this).val();
		$('#transferTo').html(accToTransfer);
	});
</script>
@endsection
<!-- Code Finalize -->