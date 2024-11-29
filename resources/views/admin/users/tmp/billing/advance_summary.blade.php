@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
	.th-color{
		color: white !important;
		/*font-size: 15px !important;*/
	}
	#example-1_filter{
		margin-right: 15px;
	}
	.header_view{
		margin: auto;
		height: 40px;
		padding: auto;
		text-align: center;
		font-family:Arial,Helvetica,sans-serif;
	}
	h2{
		color: #225094 !important;
	}
	.dataTables_filter{
		margin-left: 60%;
	}
	tr,th,td{
		text-align: center;
	}
	select{
		color: black;
	}
	.slider:before {
		position: absolute;
		content: "";
		height: 11px !important;
		width: 13px !important;
		left: 3px !important;
		/*bottom: 3px !important;*/
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}
	.active{
		background-color: #225094 !important;
	}
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
						<h2>Advance Amount <span style="color: lightgray"><small>(Reports)</small></span></h2>
					</div>
					
					<div class="col-lg-12">

						<section class="box ">
							<header class="panel_header">
								<h2 class="title pull-left">Advance Report</h2>
								<div class="actions panel_actions pull-right">
									<a class="box_toggle fa fa-chevron-down"></a>
								</div>
							</header>
							<div class="content-body">
								<label >Add To Acc:</label>
								<div class="form-group">
									<div class="btn-group btn-group-toggle" data-toggle="buttons">
										<label class="btn btn-secondary active ">
											<input type="radio" name="options" value="reseller" id="option1" onchange="loadUserList(this)" autocomplete="off" checked="true"> Manager
										</label>
											
										</div>
									</div>
									<div class="row">
										<form action="{{route('admin.users.billing.summary.advance')}}" method="POST">
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
													<button class="btn btn-flat btn-info">Search</button>
												</div>
											</div>
											 <div class="col-md-12">
            <label class="radio-inline">
      <input type="radio" name="receipttype" value="logon" checked>Logon
    </label>
    <label class="radio-inline">
      <input type="radio" name="receipttype" value="cyber">Cyber
    </label>
           </div>
										</form>

										<!-- Report Summary -->
										@if($isSearched)
										
										<!-- 2nd table -->
										<div class="col-md-12" >
											<div style="border: 2px #225094 solid; overflow-x: auto;">
												<div>
													<h3 style="color:white;background:#225094;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Advance Report 
														
													</h3>
												</div>
												<div style="overflow-x: auto;"> 
												<table id="example223" class="table display">
													<thead>
														<button class="btn btn-default" style="border: 1px solid black;float: right;margin-right: 15px;" onclick="exportTableToCSV('#example223','logonbroadband.csv')"><i class="fa fa-download"></i></button>

														<tr>
															<th>S.No </th>
															<th>Customer ID </th>
															<th>Customer Address </th>
															<th>Package</th>
															<th>Package Plan</th>
															<th>Cell No. </th>
															<th>Customer Name  </th>
															
															<th>Reseller ID </th>
															<th>Dealer ID </th>
															<th>Sub Dealer ID </th>
															<th>CNIC/NTN</th>
															<th>Payment Date</th>
															<th>Receipt</th>
															<th>Amount on which Advance Income tax charged</th>
															<th>Advance Income tax Amount</th>
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
												$rate= $entry->m_rate;
											}
											$card_expire = '';
											$user_expire = App\model\Users\UserStatusInfo::where('username' , $entry->username)->first();

											$card_expire = $user_expire['card_expire_on'];

											$receipt = $entry->receipt;
											$address='';
											$fname='';
											$mobilephone='';
											$nic='';
											$data = App\model\Users\UserInfo::where('username' , $entry->username)->first();
											$address = $data['address'];
											$mobilephone = $data['mobilephone'];
											$nic = $data['nic'];
											$fname = $data['firstname'];
											$totalsst ='0';
											$totalsst1 ='0';
											$totalsst2 ='0';
											if($receipt =="logon"){
										    $totalsst1 = '0';
											$totalsst2 = '0';
											$totalsst = '0';
											$adv='0';
											$receipt ="logon";
										}else{
											

												//$totalsst1 = $entry->sst;
											//$totalsst2 = $entry->c_charges;
											//$totalsst = $totalsst1 + $totalsst2;
											$totalsst = ($entry->adv_tax*100)/12.5;
											$totalsst = number_format((float)$totalsst, 1, '.', '');

											$adv=$entry->adv_tax;
									}
											@endphp	


											<tr>
												<td>{{$sno++}}</td>
												<td>{{$entry->username}}</td>
												<td>{{$address}}</td>
												{{-- <td>{{$entry->profileR->groupname /1024}}MB</td> --}}
												<td></td>
												<td>{{$entry->taxname}}</td>
											<td>{{$mobilephone}}</td>
												<td>{{$fname}}</td>
												<td>{{$entry->resellerid}}</td>
												<td>{{$entry->dealerid}}</td>
												<td>{{$entry->sub_dealer_id}}</td>
												<td>{{$nic}}</td>
												<td>{{$entry->date}}</td>
												<td>{{$receipt}}</td>
												<td>{{$totalsst}}</td>
												<td>{{$adv}}</td>

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
      format: 'M/DD/YYYY HH:mm'
    }
  });
 
});
</script>
@endsection