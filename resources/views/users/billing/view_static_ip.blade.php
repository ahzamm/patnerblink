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
@section('owncss')
<style type="text/css">
	#sno select,
	#filter_ser select,
	#ip select,
	#customer select,
	#res select
	{
		display: none;
	} 
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper">
			<?php if(Auth::user()->status == 'manager' || Auth::user()->status == 'reseller'){?>
				<a href="{{route('users.assignip.data')}}" class="btn btn-primary" style="margin-left:15px"><i class="fas fa-network-wired	"></i> Assign Static IPs</a>
			<?php } ?>
			<div class="header_view">
				<h2>Static IPs
					<span class="info-mark" onmouseenter="popup_function(this, 'static_ips');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box ">
				<header class="panel_header"></header>
				<div class="content-body">
					<table id="example1" class="table table-bordered data-table dt-responsive w-100 display" >
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Consumer (ID)</th>
								<th>IP Address</th>
								<th>Reseller (ID)</th>
								<th>Contractor (ID)</th>
								<th>Expiry Date</th>
								<th>Status</th>
								<th>IPs Category</th>
							</tr>
							<tr style="background:white !important;" id="filter_row">
								<td id="sno"></td>
								<td id="customer"></td>
								<td id="ip"></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td ></td>
							</tr>
						</thead>
						<tbody>
							@php
							$count=0;
							@endphp
							@foreach($export_data as $data)
							@php
							$dealerid=$data->dealerid;
							if(empty($dealerid)){
							$dealerid='Not Assign';
						}
						if($data->status == 'NEW'){
						$colors='#ebeb9d';
					}
					else{
					$colors='white';
				}
				$expire =  DB::table('user_status_info')->where('username',$data->userid)->first();
				$expire = !empty($expire) ? $expire->card_expire_on : 'N/A';
				@endphp
				<tr>
					<td>{{++$count}}</td>
					<td>{{$data->userid}}</td>
					<td class="td__profileName">{{$data->ipaddress}}</td>
					<td>{{$data->resellerid}}</td>
					<td>{{$dealerid}}</td>
					<td>{{ date('M d,Y',strtotime($expire)) }}</td>
					<td style="background-color:{{$colors}};">{{$data->status}}</td>
					<td>{{$data->type}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</section>
</sction>
</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
			orderCellsTop: true,
			responsive:true
		});
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
	} );
</script>
@endsection
<!-- Code Finalize -->