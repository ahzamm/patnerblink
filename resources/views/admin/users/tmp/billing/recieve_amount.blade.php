@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">

	.active{
		background-color: #225094 !important;
	}
	textarea {
		resize: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row">
			<div class="">
				<div class="col-lg-12">
					<form action="{{route('admin.billing.post',['status' => 'recieve'])}}" method="POST">
						@csrf
						<div class="header_view">
							<h2>Cash Recieve Amount</h2>
						</div>
						@if(session('success'))
						<div id="alert" class="alert alert-success alert-dismissible">
							{{session('success')}}
						</div>
						@endif
						<div class="col-lg-12">
							<section class="box ">
								<header class="panel_header">
								</header>
								<div class="content-body">
									<div class="row">
										<div class="col-md-12 col-xs-12">
											<div class="col-md-6 col-xs-12">
												<label >Add To Acc:</label>
												<div class="form-group">
													<div class="btn-group btn-group-toggle" data-toggle="buttons">
														<label class="btn btn-secondary active">
															<input type="radio" name="options" value="manager" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true" > Manager
														</label>
														<label class="btn btn-secondary ">
															<input type="radio" name="options" value="reseller" id="option2" onchange="loadUserList(this)" autocomplete="off"> Reseller
														</label>
														<label class="btn btn-secondary">
															<input type="radio" name="options" value="dealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Contractor
														</label>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-xs-12">
												<div class="form-group">
													<label >Select ID:</label>
													<select name="username" id="username-select" class="form-control" required >
														<option value="">Select Reseller</option>
														@foreach($managerCollection as $manager)
														<option>{{$manager->username}}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
										<!--  -->
										<div class="col-md-12 col-xs-12">
											<div style="overflow-x:auto;">
												<table class="table table-responsive">
													<thead>
														<tr>
															<th>Amount</th>
															<th>Paid By</th>
															<th>Comment</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td><input type="Number" class="form-control"  placeholder="0" required name="amount"></td>
															
															<td><input type="text" class="form-control"  placeholder="Paid by" required name="paidBy"></td>
															<td><textarea class="form-control" name="comment"  placeholder="Comment here !!" ></textarea></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-12 col-xs-12">
											<table class="table">
												<thead>
													<tr>
														<th>Payment Method</th>
													</tr>
												</thead>
											</table>
											<!-- <h5 style="background-color: #225094;color: white;padding: 10px;text-align: center;">Payment Method</h5> -->
											<div class="col-md-4 col-xs-12"></div>
											<div class="col-md-4 col-xs-12">
											
												
												<div class="btn-group btn-group-toggle" data-toggle="buttons">
												<label class="btn btn-secondary" onclick="chequeDetail()">
													<input type="radio" name="paymentType" value="cheque" id="option2" autocomplete="off"> Cheque
												</label>
												<label class="btn btn-secondary active" onclick="slideup()">
													<input type="radio" name="paymentType" value="chash" id="option3" autocomplete="off" checked="true"> Cash
												</label>
												<label class="btn btn-secondary " onclick="onlineDetail()">
													<input type="radio" name="paymentType" value="online" id="option4" autocomplete="off"> Online
												</label>
											</div>
											

												<div style="" id="showcash">
													<div class="form-group">
														<br> <br>
														<input type="Number" class="form-control"  name="deposit_num" placeholder="Enter Deposit " required>
													</div>
												</div>
												<div style="display: none;" id="showChequeDetails">
													<div class="form-group">
														<br> <br>
														<input type="text" class="form-control"  name="bankname" placeholder="Enter Bank name " >
													</div>
													<div class="form-group">
														<input type="text" class="form-control"  name="checkNo" placeholder="Enter Cheque No here" >
													</div>
												</div>
												<div style="display: none;" id="onlinebank">
													
													<div class="form-group">
														<br> <br>
														<input type="text" class="form-control"  name="onlinebankname" placeholder="Enter Bank name " >
													</div>
												</div>

											
											<div class="col-md-6">
												<div class="form-group">
													<button type="submit" class="btn btn-primary">Recieve Amount</button>
												</div>
											</div>
											</div>
										</div>
										
										
										
									</div>
									
									
								</div>
								
							</section>
						</div>
						
					</form>
				</div>
			</div>
			<div class="chart-container " style="display: none;">
				<div class="" style="height:200px" id="platform_type_dates"></div>
				<div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
				<div class="" style="height:200px" id="user_type"></div>
				<div class="" style="height:200px" id="browser_type"></div>
				<div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
				<div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
			</div>
		</section>
	</section>
	<!-- END CONTENT -->
	<!---Model Dialog --->
</div>
<!---Model Dialog --->
@endsection
@section('ownjs')
<script >
	function chequeDetail(){
		$('#showChequeDetails').slideDown();
		$('#onlinebank').hide();
		
		// add required from check details
		$("input[name='bankname']").attr('required', 'required');
		$("input[name='checkNo']").attr('required', 'required');
		
		// remove required to onlinebankname
		$("input[name='onlinebankname']").removeAttr('required');
		
		// clear value(s) of onlinebankname
		$("input[name='onlinebankname']").val("");
	}
	function slideup()
	{
		$('#showChequeDetails').slideUp();
		$('#onlinebank').slideUp();
		
		// clear values(s) of onloneDetails and checkDetails
		$("input[name='onlinebankname']").val("");
		$("input[name='bankname']").val("");
		$("input[name='checkNo']").val("");
	}
	function onlineDetail(){
		$('#onlinebank').slideDown();
		$('#showChequeDetails').hide();
		
		// add required to onlinebankname
		$("input[name='onlinebankname']").attr('required', 'required');
		
		// remove required from check details
		$("input[name='bankname']").removeAttr('required');
		$("input[name='checkNo']").removeAttr('required');
		
		
		// clear values(s) of checkDetails
		$("input[name='bankname']").val("");
		$("input[name='checkNo']").val("");
	}
</script>
<script>
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
					content += "<option value='"+obj.username+"'>"+obj.username+"</option>";
				});
				$("#username-select").empty().append(content);
			});
	}
</script>
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
//
</script>
@endsection