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
	#Logout_Time select
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
						<h2 id="heading">Offline Consumers
							<span class="info-mark" onmouseenter="popup_function(this, 'offline_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>
					<table id="example-1" class="table table-striped dt-responsive display" style="width:100%">
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Consumer (ID)</th>
								<th>Consumer Name</th>
								<th>Address</th>
								<th>Mobile Number</th>
								<th class="desktop">Logout Date & Time</th>
								@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
								<th>Contracor & Trader</th>
								@php } @endphp
							</tr>
							@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
							<tr style="background:white !important;" id="filter_row">
								<td id="sno"></td>
								<td id="username"></td>
								<td id="Full-Name"></td>
								<td id="address"></td>
								<td class="desktop" id="address"></td>
								<td class="desktop" id="Logout_Time"></td>
								<td id=""></td>
							</tr>
							@php } @endphp
						</thead>
						@php
						$count=1;
						@endphp
						<tbody>
							@foreach($arr as $ar)
							@php
							$userDetail = App\model\Users\UserInfo::where(['username' => $ar->username])->first();
							$fname = $userDetail->firstname;
							$lname = $userDetail->lastname;
							$mobile = $userDetail->mobilephone;
							$address = $userDetail->address;
							$sub_dealer_id = $userDetail->sub_dealer_id;
							if($sub_dealer_id == ''){
							$sub_dealer_id='My Users';
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
						$expire_date = $userExpire['expire_datetime'];
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
					<td>{{$userDetail->address}}</td>
					<td>
						@if($mobile != '')
						<a href="tel:{{$mobile}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$mobile}}</a>
						@endif
					</td>
					<td>{{date('M d,Y H:i:s',strtotime($ar->acctstoptime))}}</td>
					@php if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support'){ @endphp
					<td>{{$sub_dealer_id}}</td>
					@php } @endphp
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example-1').DataTable({
			orderCellsTop: true
		});
		if($(window).width() > 1024){
			$("#example-1 thead td").each( function ( i ) {
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
		$(item).popover('show');
		$.ajax({
			url: "{{route('user.dhcp')}}",
			type: "GET",
			data: {mac:mac},
			beforeSend: function(){
				$(item).prop("disabled",true);
			},
			success: function(data){
$(item).attr("data-content",data); // Data From Controller....
$(item).popover('show');
setInterval(function(){
	$(item).popover('hide');
},7000)
},complete: function(){
	$(item).prop("disabled",false);
}
});
	}
</script>
@endsection
<!-- Code Finalize -->