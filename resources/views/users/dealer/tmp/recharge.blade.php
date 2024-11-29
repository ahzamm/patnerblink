@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
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
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Single recharge account
					<span class="info-mark" onmouseenter="popup_function(this, 'singal_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			@if(session('error'))
			<div class="alert alert-error alert-dismissible show">
				{{session('error')}}
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			@endif
			@if(session('success'))
			<div class="alert alert-success alert-dismissible show">
				{{session('success')}}
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			@endif
			<section class="box">
				<div class="content-body">
					
					<div class="row" style="padding-top:30px">
						<form action="{{route('users.single.charge')}}" method="POST" id="myform">
							@csrf
							<div class="col-md-6">
								<div class="form-group position-relative">
									<label for="form-control">Select Internet Profile <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_internet_profile');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select name="profileGroupname" onchange="onProfileChange(this)" class="js-select2" required>
										<option value="">Select Internet Profile</option>
										@foreach($profileRates as $profile)
										<option value="{{$profile->profile->name}}">{{$profile->profile->name}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group position-relative">
									<label for="form-control">Select Consumer (ID) <span style="color: red">*</span></label>
									<span class="helping-mark" onmouseenter="popup_function(this, 'select_consumer_ID');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
									<select id="select-username" name="username" class="form-control" required>
										<option value="">First Select Internet Profile</option>
									</select>
								</div>
								
							</div>
							<!--  -->
							<div class="col-md-12">
								<div class="form-group pull-right">
									<button type="submit" class="btn btn-primary " id="chargeBtn">Recharge Now</button>
									<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
								</div>
							</div>
							
						</form>
					</div>
				</div>
			</section>
			
			<section class="box">
				<div class="header_view" style="padding-top: 20px;">
					<h2 style="font-size: 26px;">Recently Recharged Accounts <small style="color: ligtgray">(History)</small>
                    <span class="info-mark" onmouseenter="popup_function(this, 'recently_recharged_accounts_history');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<hr>
				<div class="content-body">
					<div class="row">
						<div class="col-md-12">
							<table class="table table-bordered dataTables_filter dt-responsive " id="example-1" style="width:100%">
								<thead>
									<tr>
										<th>Serial#</th>
										<th>Consumer (ID)</th>
										<th class="desktop">Contractor (ID)</th>
										<th>Internet Profile Name</th>
										<th class="desktop">Expiry (Date & Time)</th>
										<th>Invoice</th>
										
									</tr>
								</thead>
								<tbody>
									@php
									$count=1;
									@endphp
									@foreach($rechargeUsers as $data)
									
									@php
									
									if(Auth::user()->status == "dealer"){
										$rate= $data->dealerrate;
									}else{
										$rate= $data->subdealerrate;
									}
									$userProfile = App\model\Users\Profile::where('groupname',$data->profile)->first()->name;

									$userStatus = App\model\Users\UserStatusInfo::where('username',$data->username)->first()->card_expire_on;
									@endphp

									<tr>
										<td>{{$count++}}</td>
										<td class="td__profileName">{{$data->username}}</td>
										<td>{{$data->dealerid}}</td>
										<td>{{$userProfile}}</td>
										<td>{{$userStatus}}</td>
										<!-- // billing work -->
										@php	
										$userStatus = App\model\Users\UserStatusInfo::where('username',$data->username)->first()->card_charge_on;

										$get_invoice =App\model\Users\AmountBillingInvoice::where(['username'=>$data->username,'date'=>$userStatus])->latest('charge_on')->first();
										@endphp

										<?php
										$show_invoice = 0;
								//
										$reseller_inv_status = App\model\Users\UserInfo::where('resellerid',Auth::user()->resellerid)->first()->allow_invoice;
										$dealer_inv_status = App\model\Users\UserInfo::where('dealerid',Auth::user()->dealerid)->first()->allow_invoice;
										$subdealer_inv_status = App\model\Users\UserInfo::where('sub_dealer_id',Auth::user()->sub_dealer_id)->first()->allow_invoice;
								//
										if(Auth::user()->status == 'dealer'){
											if($reseller_inv_status == 1 && $dealer_inv_status == 1){
												$show_invoice = 1;
											}
										}else if(Auth::user()->status == 'subdealer'){
											if($reseller_inv_status == 1 && $dealer_inv_status == 1 && $subdealer_inv_status == 1){
												$show_invoice = 1;
											}
										}
										?>
										<td>
											<?php if($show_invoice == 1){ ?>
												<a href="{{url('/users/bill/view').'/'.$get_invoice->username.'/'.$get_invoice->date}}"  target="_blank" class="btn btn-default btn-xs" style="color:red;border:none"><i class="fa fa-file-pdf-o" aria-hidden="true" style="color:red"></i> Invoice</a>
											<?php }else{ echo 'Not Available';} ?>
										</td>
										
										<!-- //end -->
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
		</section>
	</section>

	<!-- Single Recharge Modal -->
	<div aria-hidden="true"  role="dialog" tabindex="-1" id="alert__modal" class="modal fade" style="display: none;">
		<div class="col-md-2 col-sm-12"> </div>
		<div class="col-md-8 col-sm-12">
			<div class="modal-dialog" style="width: 100%">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
						<h4 class="modal-title" style="text-align: center; color: white"> Alert!</h4>
					</div>
					<div class="modal-body">
						<p style="font-size: 18px">Mr. <span>Contractor Name</span> you have <span><strong>100,000 (PKR)</strong></span> in your wallet. </p>
						<h2>Consumer ID</h2>
						<div class="table-responsive" style="border: 1px solid #0d4dab">
							<table class="table tax_table">
								<thead>
									<tr>
										<th>Consumer Base Price  (Rs.)</th>
										<th>Sindh Sales Tax  (Rs.)</th>
										<th>Advance Income Tax  (Rs.)</th>
										<th>Contractor Commission  (Rs.)</th>
										<th>Total Amount  (Rs.)</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- <p><strong style="font-size: 18px">Customer ID</strong> the base price is <strong>1000</strong> SST Amount <strong>100</strong> Advance Income Tax <strong>100</strong> Commission <strong>100</strong> and total deduction <strong>1300</strong> </p> -->
						<p style="font-size: 18px; margin-top: 20px;">The recharge amuont is <span style="color: darkgreen"><strong>12,000 (PKR)</strong></span>. After recharge the amount in your wallet will be <span><strong>88,000</strong></span></p>
						<hr style="margin-top: 20px>
						<p style="font-size: 16px; color: #d16565">Please provide the correct information regarding (Consumer) CNIC for taxation purpose. However, Company is not responsible for any false information.</p>
						<p style="text-align: right; color: #d16565;font-size: 16px">براہ کرم ان صارف کا ٹیکس ادا کرنے کے لیے اپنے شناختی کارڈ کی صحیح معلومات دیں- غلط معلومات کی صورت میں کمپنی زمہ دار نہیں ہو گی
						براہ کرم صارفین تک کمپنی کی انوائس کی رسائی کو یقینی بنائیں- شکریہ</p>

					</div>
					<div class="modal-footer">
						<div class="col-md-12">
							<div class="form-group pull-right">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

	<!-- Bulk Recharge Modal -->
	<div aria-hidden="true"  role="dialog" tabindex="-1" id="alert_bulk_modal" class="modal fade" style="display: none;">
		<div class="col-md-2 col-sm-12"> </div>
		<div class="col-md-8 col-sm-12">
			<div class="modal-dialog" style="width: 100%">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
						<h4 class="modal-title" style="text-align: center; color: white"> Alert!</h4>
					</div>
					<div class="modal-body">
						<p style="font-size: 18px">Mr. <span>Contractor Name</span> you have <span><strong>100,000 (PKR)</strong></span> in your wallet. </p>
						<!-- <h2>Consumer ID</h2> -->
						<div style="max-height: 200px;overflow-y: auto;position: relative;
						border-left: 1px solid #0d4dab;
						border-right: 1px solid #0d4dab;
						border-bottom: 1px solid #0d4dab;
						border-top: 1px solid #0d4dab;
						">
						<div class="table-responsive">
							<table class="table tax_table">
								<thead>
									<tr>
										<th>Consumer ID</th>
										<th>Consumer Base Price  (Rs.)</th>
										<th>Sindh Sales Tax  (Rs.)</th>
										<th>Advance Income Tax  (Rs.)</th>
										<th>Contractor Commission  (Rs.)</th>
										<th>Total Amount  (Rs.)</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>2</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>3</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>4</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
									<tr>
										<td>5</td>
										<td>1000</td>
										<td>100</td>
										<td>100</td>
										<td>100</td>
										<td><strong style="font-size: 16px; color: darkgreen">1300</strong></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- <p><strong style="font-size: 18px">Customer ID</strong> the base price is <strong>1000</strong> SST Amount <strong>100</strong> Advance Income Tax <strong>100</strong> Commission <strong>100</strong> and total deduction <strong>1300</strong> </p> -->
					<p style="font-size: 18px;margin-top: 20px;">The recharge amuont is <span style="color: darkgreen"><strong>12,000 (PKR)</strong></span>. After recharge the amount in your wallet will be <span><strong>88,000</strong></span></p>
					<hr style="margin-top: 20px">
					<p style="font-size: 16px; color: #d16565">Please provide the correct information regarding (Consumer) CNIC for taxation purpose. However, Company is not responsible for any false information.</p>
					<p style="text-align: right; color: #d16565;font-size: 16px">براہ کرم ان صارف کا ٹیکس ادا کرنے کے لیے اپنے شناختی کارڈ کی صحیح معلومات دیں- غلط معلومات کی صورت میں کمپنی زمہ دار نہیں ہو گی
					براہ کرم صارفین تک کمپنی کی انوائس کی رسائی کو یقینی بنائیں- شکریہ</p>
					
				</div>
				<div class="modal-footer">
					<div class="col-md-12">
						<div class="form-group pull-right">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('ownjs')
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
</script>
<script>
	$(document).ready(function(){
		$("#myform").submit(function(){
			$('#chargeBtn').hide();
			$('#loadingnew').show();
		});
	});
</script>
<script type="text/javascript">
	function onProfileChange(profileGroupName){
		profileGroupName = profileGroupName.value
		console.log("profileGroupName: " + profileGroupName);
		// ajax call: jquery
		$.post(
			"{{route('users.ajax.charge.profileGroupWiseUsers')}}",
			{
				"profileGroupName" : ""+profileGroupName+""
			},
			function(data){
				console.log(data);

				let content = "<option value=''>Select Username</option>";
				$.each(data,function(i,user){
					
					// if(user.user_status_info_expired){
					content += "<option value="+user.username+">"+user.username+"</option>";
					// }
				});
				$("#select-username").empty().append(content);
			});
	}


	$(document).ready(function() {
		var table = $('#mytable').DataTable();
	} );
</script>
<script>
	var msg = '{{Session::get('alert')}}';
	var exist = '{{Session::has('alert')}}';
	if(exist){
		alert(msg);
	}
</script>

@endsection