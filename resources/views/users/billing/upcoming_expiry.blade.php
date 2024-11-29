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
<style>
	.blinkMe{
		color: red;
		font-weight:bold;
		animation: blinkDays 2s 1s ease alternate infinite;
	}
	@keyframes blinkDays {
		0%{
			opacity:.3
		}
		100%{
			opacity:1
		}
	}
</style>
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Upcoming Expiries
					<span class="info-mark" onmouseenter="popup_function(this, 'upcoming_expired_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>
			</div>
			<div class="col-lg-12">
				<section class="box ">
					<header class="panel_header">
						<div class="actions panel_actions pull-right">
						</div>
					</header>
					<div class="content-body">
						<div class="row">
							<table id="example-1" class="table table-bordered dt-responsive" style="width: 100%">
								<thead>
									<tr>
										<th>Serial#</th>
										<th>Consumer (ID)</th>
										<th>Full Name</th>
										<th style="white-space:nowrap">Mobile Number</th>
										<th>Address</th>
										<th>Remaing Days</th>
										<th>Internet Profile</th>
										<th>Charged Date</th>
										<th>Expiry Date</th>
										<th>Contractor</th>
										<th>Trader</th>
									</tr>
								</thead>
								<tbody>
									@php $sno=1; @endphp
									@foreach($expiry_users as $value)
									@php
									$now = time(); 
									$your_date = strtotime($value->card_expire_on);
									$datediff = $your_date - $now;
									$days_remains= round($datediff / (60 * 60 * 24)+1);
									$profile=$value->name;
									if($days_remains < 1){
									$days_remains='to';
								}
								@endphp
								<tr>
									<td>{{$sno++}}</td>
									<td class="td__profileName">{{$value->username}}</td>
									<td>{{$value->firstname}} {{$value->lastname}}</td>
									<td>
										@if($value->mobilephone != '')
										<a href="tel:{{$value->mobilephone}}" class="cta_btn"> <i class="fa fa-phone"></i> {{$value->mobilephone}}</a>
										@endif
									</td>
									<td>{{$value->address}}</td>
									<td><span class="blinkMe">{{$days_remains}} day(s)</span></td>
									<td>{{$profile}}</td>
									<td>{{date('M d,Y',strtotime($value->card_charge_on))}}</td>
									<td class="td__profileName">{{date('M d,Y',strtotime($value->card_expire_on))}}</td>
									<td>{{$value->dealerid}}</td>
									<td>{{(empty($value->sub_dealer_id) ? 'N/A' : $value->sub_dealer_id)}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</div>
	</section>
</section>
</div>
@endsection
@section('ownjs')
<script></script>
<script></script>
@endsection
<!-- Code Finalize -->