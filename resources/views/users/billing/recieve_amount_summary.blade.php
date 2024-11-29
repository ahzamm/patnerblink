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
@php
$checkCardType = 'TransferRec';
if(Auth::user()->status == 'subdealer'){
$checkBillingType = App\model\Users\DealerProfileRate::where('dealerid',Auth::user()->dealerid)->first()->billing_type;
$checkCardType = $checkBillingType == 'amount' ? 'TransferRec' : ($checkBillingType == 'card'  ? 'CardTransferRec' : NULL);
// dd($checkCardType);
}
@endphp
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			@if($checkCardType == 'TransferRec')
			<div class="">
				<div class="header_view">
					<h2>Received Transfer Amount <span style="color: lightgray"><small>(History)</small></span>
						<span class="info-mark" onmouseenter="popup_function(this, 'received_transfer_amount');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
					</h2>
				</div>
				<section class="box" style="padding-top:20px">
					<div class="content-body">
						<table id="example1" class="table table-bordered display dt-responsive" style="width:100%">
							<?php $file_name = 'Received-Transfer-Amount-'.date('d-M-Y[H-i-s]').'.csv'; ?>
							<button class="btn btn-primary" style="float: right;margin-right: 15px;margin-bottom:20px;" onclick="exportTableToCSV('#example1','{{$file_name}}')"><i class="fa fa-download"></i> Download</button>
							<thead>
								<tr>
									<th>Serial#</th>
									<th>Account (ID)</th>
									<th>Received Amount (PKR) </th>
									<th>Transferred By</th>
									@if(Auth::user()->status == "dealer")
									<th>Comments</th>
									@endif
									<th>Action Date</th>
								</tr>
							</thead>
							<tbody>
								@php
								$sno=1;
								@endphp
								@foreach($amount as $value)
								<tr>
									<td>{{$sno++}}</td>
									<td class="td__profileName">{{$value->receiver}}</td>
									@if($value->amount == 0)
									<td style="color: green;font-weight: bold">{{number_format(-$value->cash_amount)}}</td>
									@else
									<td style="color: green;font-weight: bold">{{number_format($value->amount)}}</td>
									@endif
									<td>{{$value->sender}}</td>
									@if(Auth::user()->status == "dealer")
									<td>{{$value->comments}}</td>
									@endif
									<td>{{$value->date}}</td>
								</tr>
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<th colspan="2" style="text-align:center;font-weight:bold;font-size: 15px;border-left:1px solid #000;border-bottom:1px solid #000">Grand Total (PKR):</th>
									<th style="text-align:center;font-weight:bold;font-size: 15px; color: green;border-right:1px solid #000;border-bottom:1px solid #000"> </th>
								</tr>
							</tfoot>	
						</table>
					</div>
				</section>
			</div>
			@else
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>View Transfer Profile(Receive)</h2>
					</div>
					<div class="col-lg-12">
						<section class="box ">
							<header class="panel_header"></header>
							<div class="content-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="example-1" class="table table-bordered display">
												<thead>
													<tr>
														<th>Serial#</th>
														<th>Username</th>
														<th>Transfer Profile </th>
														<th>Number of Profile</th>
														<th>Date</th>
													</tr>
													<tr>
														<td id="filter1"></td>
														<td id="filter2"></td>
														<td id="filter3"></td>
														<td id="filter4"></td>
														<td id="filter5"></td>
													</tr>
												</thead>
												<tbody>
													@php
													$sno=1;
													$total=0;
													@endphp
													@foreach ($transferCard as $cards)
													<tr>
														<td>{{$sno++}}</td>
														<td>{{$cards->sub_dealer_id}}</td>
														<td >{{$cards->name}}</td>
														<td>{{$cards->profilecount}}</td>
														<td>{{$cards->date}}</td>
													</tr>
													@php
													$total +=$cards->profilecount;
													@endphp
													@endforeach
												</tbody>
												<tfoot>
													<tr>
														<th colspan="2" style="text-align:center;font-weight:bold;font-size: 15px;">Total Profiles:</th>
														<th> </th>
														<th id="" style="text-align:center;font-weight:bold;font-size: 15px; color: green">{{$total}}</th>
														<th> </th>
													</tr>
												</tfoot>
											</table>
										</div>
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
<!-- CONTENT END -->
<!---Model Dialog --->
@endsection
@section('ownjs')
<script type="">
	$(document).ready(function() {
		var bool = "<?php echo $checkCardType ?>"; 
		if(bool == "TransferRec"){
			$('#example1').DataTable( {
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
.column( 2 )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( 2, { page: 'current'} )
.data()
.reduce( function (a, b) {
	return intVal(a) + intVal(b);
}, 0 );
// Update Footer
$( api.column( 2 ).footer() ).html(formatMoney(pageTotal)
	);
}
} );
		}
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
@endsection
<!-- Code Finalize -->