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
						<h2>Billing Report with Taxation</h2>
					</div>
	<div class="col-lg-12">
		<section class="box ">
		<header class="panel_header">
		<!-- <h2 class="title pull-left">Billing With Tax</h2> -->
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
		<form action="{{route('admin.billing.billing_with_tax')}}" method="POST">
		@csrf
		<div class="col-md-5">
		<div class="form-group">
			<label  class="form-label">Date Range</label>
		<div class="controls" style="margin-top: 0px;">
			<input type="text" 
			name="datetimes" style="width: 100%;height: 34px">
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
    {{-- <label class="radio-inline">
      <input type="radio" name="receipttype" value="cyber">Cyber
    </label> --}}
           </div>
</form>
			<!-- Report Summary -->
					@if($isSearched)
					<!-- 2nd table -->
					<div class="col-md-12" >
						<div style="border: 2px #225094 solid; overflow-x: auto;">
							<div>
								<h3 style="color:white;background:#225094;height: 40px; margin-top: 0px;  padding-top: 7px;font-size: 18px;font-weight: bold; text-align: center;"> Billing With Tax 
								</h3>
							</div>
						<div style="overflow-x: auto;"> 
					<table id="example223" class="table display">
				<thead>
					<button class="btn btn-default" style="border: 1px solid black;float: right;margin-right: 15px;" onclick="exportTableToCSV('#example223','logonbroadband.csv')"><i class="fa fa-download"></i></button>
				<tr>
					<td>S.No</td>
					<td>Invoice No</td>
					<td>Invoice Date</td>
					<td>Service Name</td>
					<td>Package/Add-on Name</td>
					<td>Package Bandwidth(Mbps)</td>
					<td>Reseller ID</td>
					<td>Dealer Id</td>
					<td>Sub Dealer ID</td>
					<td>Province</td>
					<td>city</td>
					<td>Customer serving enity name</td>
					<td>Customer ID</td>
					<td>Customer Name</td>
					<td>Mobile#</td>
					<td>CNIC</td>
					<td>Customer Base Price (Excl tax)</td>
					<td>Sales Tax Rate %</td>
					<td>Sales Tax Amount</td>
					<td>Advance Income Tax Rate %</td>
					<td>Advance Income Tax Amount</td>
					<td>Total Tax Amount</td>
					<td>Customer Price(Inc tax)</td>
					<td>ISP share</td>
					<td>Customer Serving entity share</td>
					<td>ISP To Partner</td>
					<td>Payable</td>
					<td>ISP to Partner Full</td>
					<td>Base Price 1/4(Excl tax)</td>
					<td>Sales New</td>
					<td>ISP to Partner Full W/O tax</td>
					<td>ISP to Partner with Tax (Full)</td>
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
		$address='';
		$fname='';
		$mobilephone='';
		$nic='';
		$data = App\model\Users\UserInfo::where('username' , $entry->username)->first();
		   //Yeh code 10 ko0 hatana hai.............
           $oldProfileName = ($entry['profile'] == '5120'?'ghaznavi':($entry['profile'] == '8192' ? 'shaheen':($entry['profile'] == '16384' ? 'hatf' :($entry['profile'] == '23552' ? 'ghauri' :($entry['profile'] == '47104' ? 'ababeel' : null)))));
		$address = $data['address'];
		$mobilephone = $data['mobilephone'];
		$nic = $data['nic'];
		$fname = $data['firstname'];
		$totalsst ='0';
		$totalsst1 ='0';
		$totalsst2 ='0';
		$receipt = $entry->receipt;
		$m_rate_cyber = 0;
	//	if($receipt == 'logon'){

	//	$rate=$entry['m_acc_rate'];
	//	$sst =0.195;
	//	$final = $rate * $sst;
	//	$sst2 = number_format((float)$final, 2, '.', '');
	//	$totalRate = $rate+$sst2;
	//	$adv='0';
	//	$receipt ="logon";

	//	}else
	//	{
	//	$totalsst1 = $entry->c_sst;
	//	$totalsst2 = $entry->c_charges;
	//	$totalRate = $totalsst1 + $totalsst2;
	//	$rate = $entry->c_charges;
	//	$sst2 = $entry->c_sst;
	//	$adv=$entry->adv_tax;
	//	$receipt ="cyber";
	//}
	if($receipt == 'logon'){
		$rate=$entry['m_acc_rate'];
		$sst =0.195;
		$final = $rate * $sst;
		$sst2 = number_format((float)$final, 2, '.', '');
		$totalRate = $rate+$sst2;
        $totalRate = number_format((float)$totalRate, 2, '.', '');
		$adv='0';
	
		$receipt ="logon";
	}else{
		$m_rate_cyber = $entry['m_acc_rate'];
		$totalsst1 = $entry->sst;
		$totalsst2 = $entry->c_charges;
		//$totalRate = $totalsst1 + $totalsst2;
		//$rate = $entry->c_charges;
		$sstamount = $entry->sst;
        $rate = ($sstamount*100)/19.5;
        $rate = number_format((float)$rate, 1, '.', '');
        $sst2 = $entry->sst;
		$totalRate = $rate+$sst2;
		$adv=$entry->adv_tax;

		//$sst2a = ($margin['margin']+$dealerRate['rate'])*0.195;
		//$sst2 = number_format((float)$sst2a, 2, '.', '');
		
		$receipt ="cyber";
		}
		$totalTax = $sst2+$adv;
		$customerPrice = $rate+$totalTax;
		$ISP_Share = $entry->m_acc_rate-$totalTax;
		$cses = ($customerPrice-$ISP_Share-$totalTax);
		$cse = ($customerPrice-$totalTax-$m_rate_cyber);
		$ispToPartner = $totalTax+$m_rate_cyber;
		$payable = ($m_rate_cyber*2)+$totalTax;
		@endphp	
		<tr>
		<td>{{$sno++}}</td>
		<td>{{$entry->receipt_num}}</td>
		<td>{{$entry->date}}</td>
		<td>N/A</td>
		{{-- @if($oldProfileName == null) --}}
        {{-- <td>{{$entry->profileR->name}}</td> --}}
        <td>{{$entry->name}}</td>
        {{-- @else
        <td>{{$oldProfileName}}</td>
        @endif <!--package Name--> --}}
		 {{-- <td>{{$entry->groupname / 1024}}MB</td> <!--Bandwith--> --}}
		 <td>{{$entry->profileR->groupname / 1024}}MB</td> <!--Bandwith-->
		 <td>{{$entry->resellerid}}</td>
		 <td>{{$entry->dealerid}}</td>
		 <td>{{$entry->sub_dealer_id}}</td>
		<td>SINDH</td>
		<td>Karachi</td>
		<td>{{$receipt}}</td>  <!--Formula if else-->
		<td>{{$entry->username}}</td>
		<td>{{$fname}}</td>
		<td>{{$mobilephone}}</td>
		<td>{{$nic}}</td>
		<td>{{$rate}}</td>  <!--Customer Base price(EXCl TAX)-->
		<td>19.5%</td>
		<td>{{$sst2}}</td>
		<td>0%</td>
		<td>{{$adv}}</td>
		<td>{{$totalTax}}</td> <!---Total Tax Amount-->
		<td>{{$customerPrice}}</td>  <!--Customer Base price(EXCl TAX) + Total Tax Amount-->
		@if($receipt == 'logon')
		<td>{{$ISP_Share}}</td>
		<td>{{$cses}}</td>
		@else
		<td>{{$m_rate_cyber}}</td>
		<td>{{$cse}}</td>
		@endif
		
		@if($receipt == 'logon')
		<td>{{$rate}}</td>
		<td>{{$totalRate}}</td>
		<td>{{$rate*4}}</td>
		<td>{{$rate}}</td>
		<td>{{$sst2}}</td>
		<td>{{$rate*4}}</td>
		<td>{{($rate*4)+$sst2}}</td>
		@else
		<td>{{$ispToPartner}}</td>
		<td>{{$payable}}</td>
		
		@endif
		
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