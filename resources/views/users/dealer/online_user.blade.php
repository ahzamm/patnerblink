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
@include('users.layouts.bytesConvert')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
	#sno select,
	#username select,
	#Full-Name select,
	#address select,
	#Login_Time select,
	#Assign select,
	#MAC select,
	#Download select,
	#Upload select,
	#Session_Time select,
	#getip select
	{
		display: none;
	}
	div.popover-body{
		background-color: black !important;
		color: white !important;
		font-weight: bold !important;
		font-size: 13px !important;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content">
		<section class="wrapper main-wrapper row" >
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2 id="heading">Online Consumers
							<span class="info-mark" onmouseenter="popup_function(this, 'online_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<table id="example1" class="table table-striped dt-responsive display" style="width:100%">
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Consumer (ID)</th>
								<th>Consumer Name</th>
								<th class="desktop">Address</th>
								<th>Login Date & Time</th>
								<th>Session Time</th>
								@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
								<th>Contractor & Trader</th>
								@php } @endphp
								<th>Assign (CGN) IPs</th>
								<th>MAC Address</th>
								<th>Download/Upload (Data)</th>
								@if(@$dhcp_server['server_id'] != 0)
								<th>Dynamic (LOCAL)IPs</th>
								@endif
							</tr>
							@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
							<tr style="background:white !important;" id="filter_row">
								<td id="sno"></td>
								<td id="Consumer (ID)"></td>
								<td id="Consumer Name"></td>
								<td id="address"></td>
								<td id="Login_Time"></td>
								<td id="Session_Time"></td>
								<td id=""></td>
								<td id="Assign"></td>
								<td id="MAC"></td>
								<td id="Download"></td>
								@if(@$dhcp_server['server_id'] != 0)
								<td id="getip" ></td>
								@endif
							</tr>
							@php } @endphp
						</thead>
						@php
						$count=1;
						$key_values = array_column($arr, 'acctstarttime'); 
						array_multisort($key_values, SORT_DESC, $arr);
						@endphp
						<tbody>
							@foreach($arr as $ar)
							@php
							$userDetail = App\model\Users\UserInfo::where(['username' => $ar->username])->first();
							$fname = $userDetail->firstname;
							$lname = $userDetail->lastname;
							$address = $userDetail->address;
							$sub_dealer_id = $userDetail->sub_dealer_id;
							if($sub_dealer_id == ''){
							$sub_dealer_id= $userDetail->dealerid.' (Contractor)';
						}else{
						$sub_dealer_id = $userDetail->sub_dealer_id.' (Trader)';
					}
					$seconds = $ar->acctsessiontime;
					$dtF = new DateTime('@0');
					$dtT = new DateTime("@$seconds");
					$onlineTime =  $dtF->diff($dtT)->format('%aDays : %hHrs : %i Mins %s Secs');
					$datetime1=new DateTime($ar->acctstarttime);
					$datetime2=new DateTime("now");
					$interval=$datetime1->diff($datetime2);
					$Day=$interval->format('%dD' );
					if($Day>0)
					{
						$session_time = $interval->format('%dDays : %hHrs : %iMins');
					}
					else
					{
						$session_time = $interval->format('%hHrs : %iMins : %sSecs');
					}
					$userExpire = App\model\Users\UserStatusInfo::where(['username' => $ar->username])->first();
					$expire_date = @$userExpire['expire_datetime'];
					$cur_date=date('Y-m-d H:i:s');
					if($expire_date < $cur_date){
					$color ='red';
				}else{
				$color ='black';
			}
			@endphp
			<tr style="color: {{$color}}">
				<td>{{$count++}}</td>
				<td class="td__profileName">{{$ar->username}}</td>
				<td>{{$fname}} {{$lname}}</td>
				<td>{{$address}}</td>
				<td><span style="color: #30761b">{{date('M d,Y H:i:s',strtotime($ar->acctstarttime))}}</span></td>
				<td>{{$session_time}}</td>
				<!-- <td>{{$onlineTime}}</td> -->
				@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
				<td>{{$sub_dealer_id}}</td>
				@php } @endphp
				<td>{{$ar->framedipaddress}}</td>
				<td>{{$ar->callingstationid}}</td>
				<td><span style="color: #30761b">{{ByteSize($ar->acctoutputoctets)}} | {{ByteSize($ar->acctinputoctets)}}</span></td>
				@if(@$dhcp_server['server_id'] != 0)
				<td><button type="button" class="btn btn-primary" onclick="getIP(this,'<?=$ar->callingstationid?>');">Dynamic (IP)</button></td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
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
<!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			responsive: true,
			orderCellsTop: true
		});
		if($(window).width() > 1024){
			$("#example1 thead td").each( function ( i ) {
				var select = $('<select class="form-control"><option value="">Show All</option></select>')
				.appendTo( $(this).empty() )
				.on( 'change', function () {
					table.column( i )
					.search( $(this).val() )
					.draw();
				} );
				table.column( i ).data().unique().sort().each( function ( d, j ) {
					select.append( '<option value="'+d+'">'+d+'</option>' )
				} );
			} );
		}
	} );
</script>
<script type="text/javascript">
	$(document).ready(function(){
		var width = window.innerWidth;
		if (width < 767) {
// Small Window
$(".page-topbar").addClass("sidebar_shift").removeClass("chat_shift");
$(".page-sidebar").addClass("collapseit").removeClass("expandit");
$("#main-content").addClass("sidebar_shift").removeClass("chat_shift");
$(".page-chatapi").removeClass("showit").addClass("hideit");
$(".chatapi-windows").removeClass("showit").addClass("hideit");
CMPLTADMIN_SETTINGS.mainmenuCollapsed();
} else {
// Large Window
$(".page-topbar").addClass("sidebar_shift").removeClass("chat_shift");
$(".page-sidebar").addClass("collapseit").removeClass("expandit");
$("#main-content").addClass("sidebar_shift").removeClass("chat_shift");
CMPLTADMIN_SETTINGS.mainmenuScroll();
}
});
</script>
<script>
	function getIP(item,mac){	
		$.ajax({
			url: "{{route('user.dhcp')}}",
			type: "GET",
			data: {mac:mac},
			beforeSend: function(){
				$(item).prop("disabled",true);
				$(item).text('Please Wait...');
			},
			success: function(data){
				$(item).css('font-weight', '900');
				$(item).text(data);
			},
			complete: function(){
			}
		});
	}
</script>
@endsection
<!-- Code Finalize -->