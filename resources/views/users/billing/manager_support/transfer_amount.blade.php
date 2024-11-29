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
			<div class="">
				<div class="header_view">
					<h2>Manager Transfer Amount
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
				<?php
				$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
				$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
				$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
				$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
				$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
				if(empty($resellerid)){
					$panelof = 'manager';
				}else if(empty($dealerid)){
					$panelof = 'reseller';
				}else if(empty($sub_dealer_id)){
					$panelof = 'dealer';
				}else{
					$panelof = 'subdealer'; 
				}
				?>
				<section class="box ">
					@if(Auth::user()->status !="manager")
					<header class="panel_header"></header>
					@endif
					<div class="content-body">
						<form id="getExceedDataForm" >
							@csrf
							<div class="row">
								<?php
								$selectedReseller = App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get();
								?>
								<div class="col-md-4">
									<div class="form-group position-relative">
										<label style="font-weight: normal">Reseller <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_reseller_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="reseller-dropdown" class="js-select2" name="username" data-status="reseller">
											<option value="">--- Select Reseller (ID) ---</option>
											@foreach($selectedReseller  as $reseller)
											<option value="{{$reseller->username}}">{{$reseller->username}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group position-relative">
										<label>Contractor <span style="color: red">*</span></label>
										<span class="helping-mark" onmouseenter="popup_function(this, 'select_contractor_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
										<select id="dealer-dropdown" class="js-select2 " name="username" data-status="contractor">
											<option value="">--- Select Contractor (ID) ---</option> 
											<?php
											if(Auth::user()->status == 'reseller' || Auth::user()->status == 'inhouse'){
												$selectedDealer = App\model\Users\UserInfo::where('status','dealer')->where('resellerid',Auth::user()->resellerid)->get(); 
												foreach ($selectedDealer as $dealer) { ?>
													<option value="{{$dealer->username}}">{{$dealer->username}}</option>
													<?php   
												} 
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-lg-4 col-md-6">
									<div class="form-group">
										<label  class="form-label">Tranfer Amount (PKR) <span style="color: red">*</span></label>
										<input type="Number" name="amount" min="0" step="0.01" id="amounts" min="0" class="form-control"  placeholder="0.00" required>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="form-group">
										<label  class="form-label">Comments <span style="color: red">*</span></label>
										<textarea name="comment" id="comment" class="form-control"  placeholder="Comments Here !!!" ></textarea>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 form-group">
									<button type="button" class="btn btn-primary" onclick="showConfirm()" style="margin-top: 28px;float:right;">Transfer Now</button>
								</div>	
							</div>
						</form>
					</div>
				</section>
				<form action="{{route('users.billing.post',['status' => 'transfer'])}}" method="POST" id="myform">
					@csrf
					<div class="modal fade" id="confromMsg" role="dialog">
						<div class="modal-dialog">
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
				<p style="text-align:center;font-size:18px">First Select Account (ID) and assign Amount.</p>
				<p style="text-align:center;font-size:20px;">تمام معلومات کا درست انتخاب کریں</p>
			</div>
		</div>
	</div>
</div>
<!-- Username And Password Modal End -->
@endsection
@section('ownjs')
<script type="text/javascript">
	function showConfirm()
	{
		var reseller = $('#reseller-dropdown').val();
		var username = $('#dealer-dropdown').val();
		if(username == ''){
			username = reseller;
		}
		var amounts = $('#amounts').val();
		var comment = $('#comment').val();
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
<script type="text/javascript">
	$(document).ready(function () {
		$('#reseller-dropdown').on('change', function () {
			var reseller_id = this.value;
			if(reseller_id == ''){
				$('#btn_generate').prop('disabled', true)
			}else{
				$('#btn_generate').prop('disabled', false)
			}
			$("#dealer-dropdown").html('');
			$.ajax({
				url: "{{route('get.dealer')}}",
				type: "POST",
				data: {
					reseller_id: reseller_id,
					_token: '{{csrf_token()}}'
				},
				dataType: 'json',
				success: function (result) {
					$('#dealer-dropdown').html('<option value="">-- Select contractor --</option>');
					$.each(result.dealer, function (key, value) {
						$("#dealer-dropdown").append('<option value="' + value
							.username + '">' + value.username + '</option>');
					});
				}
			});
		});
	});
</script>
@endsection
<!-- Code Finalize -->