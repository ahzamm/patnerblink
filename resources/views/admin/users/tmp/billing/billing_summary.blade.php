@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
	
	#example-1_filter{
		margin-right: 15px;
	}

	/* .active{
		background-color: #225094 !important;
	} */
	.title h2 {
		font-size: 30px;
		line-height: 30px;
		margin: 3px 0 7px;
		font-weight: 700;
	}
	.modal {
		display: none; /* Hidden by default */
		position: fixed; /* Stay in place */
		z-index: 1; /* Sit on top */
		padding-top: 100px; /* Location of the box */
		left: 0;
		top: 0;
		width: 100%; /* Full width */
		height: 100%; /* Full height */
		overflow: auto; /* Enable scroll if needed */
		background-color: rgb(0,0,0); /* Fallback color */
		background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}
	/* Modal Content */
	.modal-content {
		background-color: #fefefe;
		margin: auto;
		padding: 20px;
		border: 1px solid #888;
		width: 80%;
	}
	/* The Close Button */
	.close {
		color: #aaaaaa;
		float: right;
		font-size: 28px;
		font-weight: bold;
	}
	.close:hover,
	.close:focus {
		color: #000;
		text-decoration: none;
		cursor: pointer;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Billing <span style="color: lightgray"><small>(Report)</small></span></h2>
					</div>
					
					<div class="col-lg-12">

						<section class="box ">
							<header class="panel_header">
								<!-- <h2 class="title pull-left">Billing Report</h2> -->
								<div class="actions panel_actions pull-right">
									<a class="box_toggle fa fa-chevron-down"></a>
								</div>
							</header>
							<div class="content-body">
								<label >Add To Acc:</label>
								<div class="form-group">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn btn-primary active ">
											<input type="radio" name="options" value="reseller" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true"> Manager
										</label>
											<!-- <label class="btn btn-secondary">
												<input type="radio" name="options" value="dealer" id="option2" onchange="loadUserList(this)" autocomplete="off" checked="true"> Dealer
											</label>
											<label class="btn btn-secondary">
												<input type="radio" name="options" value="subdealer" id="option3" onchange="loadUserList(this)" autocomplete="off"> Sub Dealer
											</label> -->
										</div>
									</div>
									<div class="row">
										<form action="{{route('admin.users.billing.summary.generate')}}" method="POST">
											@csrf
											<div class="col-md-5">
												<div class="form-group">
													<label  class="form-label">Date Range</label>
													<div class="controls" style="margin-top: 0px;">
														<input type="text" 
														name="datetimes" style="width: 100%;height: 34px" 
														>
													</div>
												</div>
											</div>
											<div class="col-md-5">
												<div class="form-group">
													<label  class="form-label">Manager Name</label>
													<select class="form-control" id="username-select" name="username" required >
														<option value="">select manager</option>
														@foreach($userCollection as $dealer)
														<option value="{{$dealer->username}}">{{$dealer->username}}</option>
														@endforeach
													</select>
												</div>
											</div>

											<div class="col-md-2">
												<br>
												<div class="form-group" style="margin-top: 5px;">
													<button class="btn btn-flat btn-primary">Search</button>
												</div>
											</div>
										</form>

										<!-- Report Summary -->
										@if($isSearched)
										<div class="col-md-12">
											<div style="overflow-x: auto;">
												<table  class="table table-bordered table-responsive" style="border: 2px #225094 solid">
													<thead>
														<tr>
															<th colspan="5" style="font-weight: bold;">Report Summary</th>
														</tr>
														<tr>
															<th>Manager Name</th>
															<th>Reseller Name</th>
															<th>Dealer Name</th>
															<th>Sub Dealer Name </th>
															<th>Net Payable Amount (PKR)</th>
														</tr>
													</thead>
													<tbody>
														<tr>
															<td>{{$selectedUser->manager_id}}</td>
															<td>{{$selectedUser->resellerid}}</td>
															<td>{{$selectedUser->dealerid}}</td>
															<td>{{$selectedUser->sub_dealer_id}}</td>
															<td>{{number_format($monthlyPayableAmount,2)}}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<!-- 2nd table -->
										<div class="col-md-12" >
											<div style="border: 2px #225094 solid; overflow-x: auto;">
												<div>
												<table  class="table">
													<thead>
														<tr>
															<th colspan="5" style="font-weight: bold;">Monthly Billing Report </th>
														</tr>
</thead>
</table>
													<!-- <h3 style="height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> </h3> -->
												</div>
												<div style="overflow-x: auto;">
												<table id="example223" class="table display">
													<thead>
														<button class="btn btn-default" style="border: 1px solid black;float: right;margin-right: 15px;" onclick="exportTableToCSV('#example223','logonbroadband.csv')"><i class="fa fa-download"></i></button>

														<tr>
															<th>S.No </th>
															<th>Customer ID </th>
															<th>Package Name </th>
															<th>Package</th>
															<th>Service Activation Date </th>
															<th>Service Billing Start Date  </th>
															<th>Service Billing End Date </th>
															<th>Reseller ID </th>
															<th>Dealer ID </th>
															<th>Sub Dealer ID </th>
															<th>Receipt</th>
															<th>Package Amount (PKR)</th>
														</tr>
													</thead>
													<tbody>
														@php $sno = 1; @endphp
														
														@foreach($monthlyBillingEntries as $entry)
														@php
														if($selectedUser->status == "reseller"){
														$rate= $entry->rate;
													}
													elseif($selectedUser->status == "dealer"){
													$rate= $entry->dealerrate;
												}
												elseif($selectedUser->status == "subdealer"){
												$rate= $entry->subdealerrate;
											}
											elseif($selectedUser->status == "manager"){
												// $rate= $entry->m_rate;
												$rate= $entry->m_acc_rate;
											}
											$card_expire = '';
											$user_expire = App\model\Users\UserStatusInfo::where('username' , $entry->username)->first();
											$card_expire = $user_expire['card_expire_on'];

									$receipt1 = '';
									$receipt1 = App\model\Users\AmountBillingInvoice::where(['id' => $entry->id])->first();
									if(empty($receipt1)){
									$receipt = "none";
									}else{
									$receipt = $receipt1->receipt;
									}
											@endphp	


											<tr>
												<td>{{$sno++}}</td>
												@if(Auth::user()->username == 'administrator' || $receipt != 'none')
												{{-- <td>{{$entry->username}}</td> --}}
												<td><a href="{{route('admin.billing.customer_bill_PDF',['username'=>$entry->username,'date' => $entry->date])}}">{{$entry->username}}</a></td>
												@else
												<td>{{$entry->username}}</td>
												{{-- <td><a href="{{route('admin.billing.customer_bill_PDF',['username'=>$entry->username,'date' => $entry->date])}}">{{$entry->username}}</a></td> --}}
												@endif
												<td>{{$entry->name}}</td>
												<td>{{$entry->profile/1024}}MB</td>
												@if(!empty($entry->user_info->creationdate))
										<td>{{$entry->user_info->creationdate}}</td>
										@else
										<td>N/A</td>
										@endif
										<td>{{$entry->charge_on}}</td>
										@if(!empty($card_expire))
										<td>{{$card_expire}}</td>
										@else
										<td>N/A</td>
										@endif
												<td>{{$entry->resellerid}}</td>
												<td>{{$entry->dealerid}}</td>
												<td>{{$entry->sub_dealer_id}}</td>
												@if($entry->receipt =="logon")
												<td>logon</td>
												@else
												<td>cyber</td>
												@endif
												<td>{{$rate}}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								</div>
							</div>
							@endif

						</div>
					</div>
				</section>

			</div>
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
<!-- Select User List -->
<script>
// 	$(document).ready( function () {
//     $('#example223').DataTable();
// } );
	
	$(document).ready(function() {
    $('#example223').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
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
$(function() {
  $('input[name="datetimes"]').daterangepicker({
    timePicker: true,
    startDate: moment().startOf('hour'),
    endDate: moment().startOf('hour'),
    locale: {
      format: 'M/DD/YYYY HH:mm:ss'
    }
  });
 
});
</script>
@endsection