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
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="">
					<div class="header_view">
						<h2>Contractor <span style="color: lightgray"><small>(Profit Detail)</small></span></h2>
					</div>
					<div class="">
						<section class="box ">
							<header class="panel_header">
								<div class="actions panel_actions pull-right">
									<a class="box_toggle fa fa-chevron-down"></a>
								</div>
							</header>
							<div class="content-body">
								<div class="row">
									<form action="{{route('users.billing.report.details')}}" method="POST">
										@csrf
										<div class="col-md-5">
											<div class="form-group">
												<label  class="form-label">Select (Date , Time & Year) Range <span style="color: red">*</span></label>
												<span style="float: right; padding-right: 10px; font-weight: bold; color: darkgreen"><input class="radio2"  type="radio" name="gender" value="male"> Billing (wise) <span style="color: red">*</span>
												<input class="radio2" type="radio" name="gender" value="female"> Date & Time (wise) <span style="color: red">*</span></span><br>
												<div class="controls" style="margin-top: 0px;">
													<input id="mytest" type="text" 
													name="datetimes" style="width: 100%;height: 34px"  >
													<select id="mytest2" class="form-control" name="date" style="display: none;" >
														<option value="">Select MM/DD/YY </option>
														@php
														$from=date('2020-01-25');
														$startTime = strtotime($from);
														$now = time();
														$datediff =($now-$startTime);
														$range=floor(($datediff / (60 * 60 * 24))+1);
														for($i=0;$i<$range;$i++){
														$date = date('Y-m-d',strtotime($from ."+".$i." days"));
														$newdate=explode("-",$date);
														if($newdate[2] == 10 || $newdate[2] == 25){
														@endphp
														<option value="{{$date}}">{{date('M d,Y' ,strtotime($date))}}</option>
														@php
													}
												}
												@endphp
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label  class="form-label">Select Account <span style="color: red">*</span></label>
										<select class="form-control" id="username-select" name="username" required >
											<option value="">Select Account</option>
											@if(Auth::user()->status == "dealer")
											<option value="own">own</option>
											@endif
											@if(Auth::user()->status == "manager")
											<option value="{{Auth::user()->username}}">own</option>
											@endif
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
							</div>
						</div>
						<div class="col-s-12" style="">
							<div style="border: 2px #225094 solid;overflow-x: auto;">
								<div style="">
									<h3 class="theme-bg" style="color:white;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Profit Detail by Contractor</h3>
								</div>
								<div style="overflow-x: auto;"> 
									<table id="example1" class="table display">
										<thead>
											<button class="btn btn-default" style="border: 1px solid black;float: right;margin-right: 15px;" onclick="exportTableToCSV('#example1','{{Auth::user()->username}}.csv')"><i class="fa fa-download"></i></button>
											<tr>
												<th>Serial# </th>
												<th>Consumer (ID) </th>
												<th>Internet Profile </th>
												<th>Contractor Profile Rates (PKR) </th>
												<th>Reseller Profile Rates (PKR)  </th>
												<th>Commsion (PKR) </th>
												<th>Profit (PKR)</th>
											</tr>
										</thead>
										<tbody>
											@php $sno = 1;
											@endphp
											@foreach($monthlyBillingEntries as $entry)
											@php
											if($selectedUser->status == "reseller"){
											$rate= $entry->rate;
											$profile_rate=App\model\Users\ResellerProfileRate::where(['name' => $entry->name,'resellerid' =>$entry->resellerid])->first();
											$servicerate = $profile_rate->rate;
										}elseif($selectedUser->status == "dealer"){
										$rate= $entry->dealerrate;
										$profile_rate=App\model\Users\DealerProfileRate::where(['name' => $entry->name,'dealerid' =>$entry->dealerid])->first();
										$servicerate = $profile_rate->rate;
									}elseif($selectedUser->status == "subdealer"){
									$rate= $entry->subdealerrate;
									$profile_rate=App\model\Users\SubdealerProfileRate::where(['name' => $entry->name,'sub_dealer_id' =>$entry->sub_dealer_id])->first();
									$servicerate = $profile_rate->rate;
								}elseif($selectedUser->status == "manager"){
								$rate= $entry->m_rate;
								$profile_rate=App\model\Users\ManagerProfileRate::where(['groupname' => $entry->profileR->groupname,'manager_id' =>$entry->manager_id])->first();
								$servicerate = $profile_rate->rate;
							}
							$profit = $entry->d_acc_rate - $entry->r_acc_rate;
							$profit2 = $profit - $entry->commision;
							@endphp
							<tr>
								<td>{{$sno++}}</td>
								<td>{{$entry->username}}</td>
								<td>{{$entry->name}}</td>
								<td>{{$entry->d_acc_rate}}</td>
								<td>{{$entry->r_acc_rate}}</td>
								<td>{{$entry->commision}}</td>
								<td>{{$profit2}}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr class="btn-default" style="background-color:#d8d8d8;">
								<th colspan="6" style="text-align:right;font-weight:bold;font-size: 15px;">Grand Total Amount <span style="color: green"><small>(PKR)</small></span></th>
								<th style="text-align:center;font-weight:bold;font-size: 15px;"> </th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			@endif
		</div>
	</div>
</section></div>
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
</div>
@endsection
@section('ownjs')
<script type="">
	$(document).ready(function() {
		$('#example1').DataTable( {
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
// Remove The Formatting To Get Integer Data For Summation
var intVal = function ( i ) {
	return typeof i === 'string' ?
	i.replace(/[\$,]/g, '')*1 :
	typeof i === 'number' ?
	i : 0;
};
// Total Over All Pages
total = api
.column( 6 )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( 6, { page: 'current'} )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 6 ).footer() ).html(pageTotal);
}
} );
	} );
</script>
<script type="text/javascript">
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
<script type="text/javascript">
	$('.radio2').click(function(e){
		var test = $(this).val();
		if(test == "male"){
			$('#mytest').hide();
			$('#mytest2').show();
		}else{
			$('#mytest').show();
			$('#mytest2').hide();
		}
	});
</script>
@endsection
<!-- Code Finalize -->