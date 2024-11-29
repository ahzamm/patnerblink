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
	#Sno select,
	#Status select,
	#username select,
	#Full-Name select,
	#Expiry select,
	#Actions select,
	#address select,
	#verification select
	{
		display: none;
	}
	.btn.btn-secondary.assigned.active{
		background-color: green
	}
	.btn.btn-secondary.removed.active{
		background-color: red
	}
	.btn.btn-secondary.gaming_ip.active{
		background-color: #40abb1
	}
	.btn.btn-secondary.static_ip.active{
		background-color: #7a7c0c
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content"> 
		@include('users.layouts.session')
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Assign Static IP
					<span class="info-mark" onmouseenter="popup_function(this, 'static_ips_assign');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<div class="box">
				<div class="alert alert-dismissible" id="msg"></div>
				<div class="content-body" style="padding-top:25px">
					<form id="get-assign-ip">
						<div class="row">
							<?php if(Auth::user()->status == 'manager'){ ?>
								<div class="col-md-3">
									<div class="form-group">
										<label for="">Select Reseller</label>
										<select name="reseller" id="findnas" class="form-control" required="">
											<option value="">Select Reseller</option>	
											@foreach($get_reseller_data as $reseller)
											<option value="{{$reseller->username}}">{{$reseller->username}}</option>
											@endforeach
										</select>
									</div>
								</div>
							<?php } if( Auth::user()->status == 'reseller'){ ?>
								<div class="col-md-3">
									<div class="form-group">
										<label for="">Select Dearler</label>
										<select name="dealer" id="findnas" class="form-control" required="">
											<option value="">Select Dealer</option>	
											@foreach($get_dealer_data as $dealer)
											<option value="{{$dealer->username}}">{{$dealer->username}}</option>
											@endforeach
										</select>
									</div>
								</div>
							<?php } ?>
							<div class="col-md-3">
								<div class="form-group">
									<label for="">BRAS</label>
									<select name="bras" id="bras" class="form-control" required="">
										<option value="">Select</option>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Number Of IPs</label>
									<input type="number" id="noofip" name="noofip" class="form-control" min="1" placeholder="0" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>IP Type</label>
									<select name="ip_type" id="ip_type" class="form-control" required="">
										<option value="">Select</option>
										<option value="static">Static</option>
										<option value="gaming">Gaming</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="btn-group btn-group-toggle" data-toggle="buttons" id="ipassign">
										<label class="btn btn-success assigned">
											<input type="radio" name="ipassign" value="assign" autocomplete="off" required=""> Assign
										</label>
										<label class="btn btn-danger removed" id="ipremove">
											<input type="radio" name="ipassign" value="remove" autocomplete="off" required=""> Remove
										</label>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<button type="submit" class="btn btn-primary btn-submit pull-right">Submit</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div style="overflow-x: auto;">
				<div class="col-lg-4">
				</div>
			</div>
		</div>
	</form>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$("#get-assign-ip").submit(function(e){
		$('#msg').html('');
		$('#msg').removeClass('alert-success');
		$('#msg').removeClass('alert-danger');
		$('#msg').hide();
		e.preventDefault();
		$.ajax({
			dataType: 'json',
			type:'POST',
			url : "{{route('users.assignip.store')}}",
			data:$("#get-assign-ip").serialize(),
			success:function(data){
				if(data.error == null){
					$('#msg').addClass('alert-success');
					$('#msg').html(data.success);
					$('#msg').show();
				}else{
					$('#msg').addClass('alert-danger');
					$('#msg').html(data.error);	
					$('#msg').show();
				}
			}
		});
	});
</script>
<script type="text/javascript">
	$(document).on('change','#findnas',function(e){
		var user = $(this).val();
		e.preventDefault();
		$.ajax({
			dataType: 'json',
			type:'GET',
			url : "{{route('users.nas_assign.data')}}",
			data:'user='+user,
			success:function(data){
				$('#bras').html('<option value="">Select</option>');
				$.each(data, function(index, value) {
					$('#bras').append('<option value="'+value.nas+'">'+value.nas+'</option>');
				});
			}
		});
	});
</script>
@endsection
<!-- Code Finalize -->