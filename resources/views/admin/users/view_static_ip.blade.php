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
@extends('admin.layouts.app')
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
			<a href="{{route('admin.ips_create')}}" class="btn btn-default"><i class="fas fa-keyboard"></i> Add Static IPs</a>
			<div class="header_view">
				<h2>Manage Static (IPs)
					<span class="info-mark" onmouseenter="popup_function(this, 'static_ips_management_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<section class="box ">
				<header class="panel_header">
				</header>
				<div class="content-body">
					<table id="example1" class="table table-bordered dt-responsive display w-100">
						<thead>
							<tr>
								<th>Serial#</th>
								<th>Server IP Address</th>
								<th>Static IP Address</th>
								<th>Reseller</th>
								<th>Contractor</th>
								<th>Trader</th>
								<th>Consumers</th>
								<th>Status</th>
								<th>IP Type</th>
							</tr>
							<tr style="background:white !important;" id="filter_row">
								<td id="sno"></td>
								<td id="filter_ser"></td>
								<td id="ip"></td>
								<td ></td>
								<td ></td>
								<td ></td>
								<td id="customer"></td>
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
							$resellerid=$data->resellerid;
							if(empty($dealerid)){
							$dealerid='Not Assign';
						}
						if(empty($resellerid)){
						$resellerid='Not Assign';
					}
					if($data->status == 'NEW' && $resellerid=='Not Assign' && $dealerid=='Not Assign'){
					$colors='#d9ead8';
				}
				else{
				$colors='white';
			}
			@endphp
			<tr>
				<td style="background-color:{{$colors}};">{{++$count}}</td>
				<td style="background-color:{{$colors}};">{{$data->serverip}}</td>
				<td style="background-color:{{$colors}};">{{$data->ipaddress}}</td>
				<td style="background-color:{{$colors}};">{{$resellerid}}</td>
				<td style="background-color:{{$colors}};">{{$dealerid}}</td>
				<td style="background-color:{{$colors}};"></td>
				<td style="background-color:{{$colors}};">{{$data->userid}}</td>
				<td style="background-color:{{$colors}};">{{$data->status}}</td>
				<td style="background-color:{{$colors}};">{{$data->type}}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
</section>
</section>
</section>
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			orderCellsTop: true
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