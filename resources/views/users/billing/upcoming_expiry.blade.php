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
							<table id="example-2" class="table table-bordered dt-responsive" style="width: 100%">
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
<script>
    $(document).ready(function() {
    $('#example-2').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('users.billing.upcoming_expiry.data') }}',
        columns: [
            { data: 'serial', name: 'serial' },
            { data: 'username', name: 'username' },
            { data: 'fullname', name: 'fullname' },
            { data: 'mobilephone', name: 'mobilephone' },
            { data: 'address', name: 'address' },
            { data: 'remaining_days', name: 'remaining_days' },
            { data: 'profile', name: 'profile' },
            { data: 'card_charge_on', name: 'card_charge_on' },
            { data: 'card_expire_on', name: 'card_expire_on' },
            { data: 'dealerid', name: 'dealerid' },
            { data: 'sub_dealer_id', name: 'sub_dealer_id' }
        ],
        createdRow: function(row, data, dataIndex) {
            if (data.remaining_days < 1) {
                $('td:eq(5)', row).html('<span class="blinkMe">to day(s)</span>');
            } else {
                $('td:eq(5)', row).html('<span class="blinkMe">' + data.remaining_days + ' day(s)</span>');
            }
        }
    });
});
</script>
<script></script>
@endsection
<!-- Code Finalize -->
