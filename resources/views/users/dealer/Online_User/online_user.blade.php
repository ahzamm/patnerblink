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
	#getip select {
		display: none;
	}

	div.popover-body {
		background-color: black !important;
		color: white !important;
		font-weight: bold !important;
		font-size: 13px !important;
	}
	#example12 {
		margin: 0;
		width: 100% !important;
		overflow-x: hidden !important;
	}
	.table-responsive {
		overflow-x: hidden;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- CONTENT START -->
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			
				<div class="col-lg-12">
					<div class="header_view">
						<h2 id="heading">Online Consumers
							<span class="info-mark" onmouseenter="popup_function(this, 'online_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
						</h2>
					</div>

					<div class="content-body" style="padding-top: 30px">

						<?php
						$manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
						$resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
						$dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
						$sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
						$trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
						//
						if (empty($resellerid)) {
							$panelof = 'manager';
						} else if (empty($dealerid)) {
							$panelof = 'reseller';
						} else if (empty($sub_dealer_id)) {
							$panelof = 'dealer';
						} else {
							$panelof = 'subdealer';
						}
						?>

						<div class="row">


							<?php if (($panelof == 'reseller')) {
								$selectedContractors = [];
								if ($panelof == 'reseller') {
									$selectedContractors = App\model\Users\UserInfo::where('status', 'dealer')
										->where('resellerid', Auth::user()->resellerid)
										->get();
								}
							?>
								<div class="col-md-4">
									<div class="form-group">
										<label style="font-weight: normal">Select Contractor <span style="color: red">*</span></label>
										<select id="dealer-dropdown" class="js-select2" name="dealer_data">
											<option value="">-- Select contractor --</option>
											@foreach ($selectedContractors as $contractor)
											<option value="{{ $contractor->dealerid }}">{{ $contractor->dealerid }}</option>
											@endforeach
										</select>
									</div>
								</div>

							<?php }
							if (($panelof == 'reseller') || ($panelof == 'dealer')) {
								$selectedTraders = [];
								if ($panelof == 'dealer') {
									$selectedTraders = App\model\Users\UserInfo::where('status', 'subdealer')
										->where('dealerid', Auth::user()->dealerid)
										->get();
								}
							?>
								<div class="col-md-4">
									<div class="form-group">
										<label style="font-weight: normal">Select Trader <span style="color: red">*</span></label>
										<select id="trader-dropdown" class="js-select2" name="trader_data">
											<option value="">-- Select trader --</option>
											@foreach ($selectedTraders as $trader)
											<option value="{{ $trader->sub_dealer_id }}">{{ $trader->sub_dealer_id }}</option>
											@endforeach
										</select>
									</div>
								</div>
					<?php } ?>

					<div class="col-md-4">
						<input type="text" id="searchFilter" class="form-control" placeholder="Search by username, name, or address">
					</div>

						<div class="col-md-4">
							<button id="loadData" class="btn btn-primary mb-5" style="margin-top: 30px;">Get Data</button>
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

		<section id="tablediv" class="wrapper main-wrapper" style="margin-top: 0px;padding: 0px;">
					<div class="table-responsive" id="tableContainer">
					<table id="example12" class="table table-bordered dt-responsive display" style="width:100%">
						<thead>
							<tr>
								<!-- <th>Serial#</th> -->
								<th>Consumer (ID)</th>
								<th>Consumer Name</th>
								<th class="desktop">Address</th>
								<th>Login Date & Time</th>
								<th>Session Time</th>
								@if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support')
								<th>Contractor & Trader</th>
								@endif
								<th>Assign (CGN) IPs</th>
								<th>Download/Upload (Data)</th>
								<th>Dynamic (LOCAL)IPs</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="9">No Data Found</td>
							</tr>
							<!-- Table body will be populated via AJAX -->
						</tbody>
					</table>
					</div>
					</section>
					</div>
				</div>

					</section>

	</section>
	<!-- CONTENT END -->
</div>
@endsection
@section('ownjs')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			responsive: true,
			orderCellsTop: true
		});
		if ($(window).width() > 1024) {
			$("#example1 thead td").each(function(i) {
				var select = $('<select class="form-control"><option value="">Show All</option></select>')
					.appendTo($(this).empty())
					.on('change', function() {
						table.column(i)
							.search($(this).val())
							.draw();
					});
				table.column(i).data().unique().sort().each(function(d, j) {
					select.append('<option value="' + d + '">' + d + '</option>')
				});
			});
		}
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
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
</script> -->
<script>
	function getIP(item, mac) {
		$.ajax({
			url: "{{route('user.dhcp')}}",
			type: "GET",
			data: {
				mac: mac
			},
			beforeSend: function() {
				$(item).prop("disabled", true);
				$(item).text('Please Wait...');
			},
			success: function(data) {
				$(item).css('font-weight', '900');
				$(item).text(data);
			},
			complete: function() {}
		});
	}
</script>
<script>
$(document).ready(function() {
	let table
    // Initialize the DataTable on button click
    $('#loadData').on('click', function() {
        let selectedContractor = $('#dealer-dropdown').val();
        let selectedTrader = $('#trader-dropdown').val();
		let searchFilter = $('#searchFilter').val();

		if (table) {
			table.destroy(); // Destroy existing table if it exists
		}

        // Initialize the DataTable
         table = $('#example12').DataTable({
            processing: true,
            serverSide: true,
			responsive: true,
            ajax: {
                url: "{{ route('users.user.onlineuser.table') }}",
                type: "GET",
                data: function(d) {
                    d.contractor = selectedContractor;
                    d.trader = selectedTrader;
					d.searchFilter = searchFilter;
                }
            },
            columns: [
				// { data: 'id', orderable: false },  // Serial#
				{ data: 'username' , name: 'RadAcct.username' },              // Consumer (ID)
				{ data: 'firstname' },             // Consumer Name
				{ data: 'address' },               // Address
				{ data: 'login_time', name: 'acctstarttime' },            // Login Date & Time
				{ data: 'session_time' },          // Session Time
				@if(Auth::user()->status == 'dealer' || Auth::user()->status == 'support')
				{ data: 'sub_dealer_id' },         // Contractor & Trader (only for dealers and support)
				@endif
				{ data: 'framedipaddress'},       // Assign (CGN) IPs
				{ data: 'data_usage'},            // Download/Upload (Data)
				{ data: 'dynamic_ips' },           // Dynamic (LOCAL)IPs
            ],
            order: [[1, 'asc']]
        });

        // Reload the DataTable with new data based on selected filters
        table.ajax.reload();
    });

    // Handle the change in the dealer dropdown
    $('#dealer-dropdown').change(function() {
        const tradersUsername = $(this).val();

        // Clear the traders dropdown if no trader is selected
        if (!tradersUsername) {
            $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
            return;
        }

        // Send AJAX request to fetch traders for the selected dealer
        $.ajax({
            url: "{{ route('get.trader') }}",
            type: 'POST',
            data: { dealer_id: tradersUsername },
            success: function(response) {
                const traderDropdown = $('#trader-dropdown');
                traderDropdown.empty(); // Clear existing options
                traderDropdown.append('<option value="">-- Select Trader --</option>');

                // Populate the trader options dynamically
                $.each(response.subdealer, function(index, trader) {
                    traderDropdown.append(`<option value="${trader.sub_dealer_id}">${trader.sub_dealer_id}</option>`);
                });
            },
            error: function(error) {
                console.error('Error fetching traders:', error);
            }
        });
    });
});

</script>
@endsection
<!-- Code Finalize -->