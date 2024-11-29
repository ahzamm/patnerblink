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
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<div class="header_view">
				<h2>Transfer Commission Amount
					<span class="info-mark" onmouseenter="popup_function(this, 'current_balance_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
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
							<div class="col-md-12 col-xs-12">
								<label >Add To Account:</label>
								<div class="form-group">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										@if(Auth::user()->status == 'manager')
										<label class="btn btn-primary active">
											<input type="radio" name="options" id="option2" onchange="loadUserList(this)" autocomplete="off" > Reseller
										</label>
										@endif
										@if(Auth::user()->status == 'reseller')
										<label class="btn btn-primary active">
											<input type="radio" name="options" id="option3" autocomplete="off"> Contractor
										</label>
										@endif
										@if(Auth::user()->status == 'dealer')
										<label class="btn btn-primary active">
											<input type="radio" name="options" id="option4" autocomplete="off"> Trader
										</label>
										@endif
									</div>
								</div>
								<div class="col-md-4 col-xs-12">
									<div class="form-group">
										@if(Auth::user()->status == 'manager')
										<label  class="form-label">Reseller <span style="color: red">*</span></label>
										<select class="form-control" name="username" required >
											<option value="">Select Reseller</option>
											@foreach($managerCollection as $manager)
											<option value="{{$manager->username}}">{{$manager->username}}</option>
											@endforeach
										</select>
										@endif
										@if(Auth::user()->status == 'reseller')
										<label  class="form-label">Contractor <span style="color: red">*</span></label>
										<select class="form-control" name="username" required >
											<option value="">Select Contractor</option>
											@foreach($managerCollection as $manager)
											<option value="{{$manager->username}}">{{$manager->username}}</option>
											@endforeach
										</select>
										@endif
										@if(Auth::user()->status == 'inhouse')
										<label  class="form-label">Contractor <span style="color: red">*</span></label>
										<select class="form-control" name="username" required >
											<option value="">Select Contractor</option>
											@foreach($managerCollection as $manager)
											<option value="{{$manager->username}}">{{$manager->username}}</option>
											@endforeach
										</select>
										@endif
										@if(Auth::user()->status == 'dealer')
										<label  class="form-label">Trader <span style="color: red">*</span></label>
										<select class="form-control" name="username" required id="username" >
											<option value="">Select Trader</option>
											@foreach($managerCollection as $manager)
											<option value="{{$manager->username}}">{{$manager->username}}</option>
											@endforeach
										</select>
										@endif
									</div>
								</div>
								<div class="col-md-4 col-xs-12">
									<div class="form-group">
										<label  class="form-label">Tranfer Amount (PKR) <span style="color: red">*</span></label>
										<input type="Number" name="amount" id="amounts" min="0" class="form-control"  placeholder="0.00" required>
									</div>
								</div>
								@if(Auth::user()->status =='reseller' || Auth::user()->status =='inhouse' || Auth::user()->status =='manager')
								<div class="col-md-4 col-xs-12">
									<div class="form-group">
										<label  class="form-label">Comments <span style="color: red">*</span></label>
										<textarea name="comment" id="comment" class="form-control"  placeholder="Comments Here !!!!!" ></textarea>
									</div>
								</div>
								@else
								<div class="col-md-4 col-xs-12">
									<input type="hidden" name="comment" id="comment" class="form-control"  placeholder="0" >
								</div>
								@endif
								<div class="col-md-12 col-xs-12">
									<div class="form-group pull-right">
										<button type="button" class="btn btn-primary" onclick="showConfrom(username.value,amounts.value,comment.value)" style="margin-top: 28px;">Transfer Now</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</section>
			<!--  Modal Start -->
			<form action="{{route('users.billing.post',['status' => 'commision'])}}" method="POST" id="myform">
				@csrf
				<div class="modal fade" id="confromMsg" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<input type="hidden" name="username" id="showUsername" required="">
								<input type="hidden" name="comment" id="showcommnet" required="">
								<input type="hidden" name="commision" id="showcommision" required="">
								<input type="hidden" name="amount" id="showAmount" required="">
								<center>
									<h4> Are you sure want to transfer amount ? That is </h4>
									<h2 id="Amount"></h2>
									<h4 id="amounInWords"></h4>
								</center>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-danger" id="transferBtn">Yes</button>
								<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
								<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			<!--  Modal Ends -->
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
				<p style="text-align:center; font-size:18px">First Select Account (ID) and Transfer Amount.</p>
				<p style="text-align:center;font-size:20px;">تمام معلومات کا درست انتخاب کریں</p>
			</div>
		</div>
	</div>
</div>
<!-- Username And Password Modal End -->
@endsection
@section('ownjs')
<script type="text/javascript">
	function showConfrom(username,amounts,comment)
	{
		if (username!="" && amounts!="") {
			$('#showUsername').val(username);
			$('#showcommnet').val(comment);
			$('#showAmount').val(amounts);
			var amount= "Rs. "+formatMoney(amounts)+"/-";
			var inwords=inWords(amounts);
			$('#Amount').html(amount);
			$('#amounInWords').html(inwords.toUpperCase());
			$('#confromMsg').modal('show');
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
	</script
	@endsection