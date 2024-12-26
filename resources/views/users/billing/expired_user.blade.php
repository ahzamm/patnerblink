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
						<h2 id="heading">Offline Consumers
							<span class="info-mark" onmouseenter="popup_function(this, 'offline_consumers');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
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
                        <table class="table table-bordered data-table dt-responsive display" style="width: 100%">
                            <thead>
                              <tr>
                                <th>Serial#</th>
                                <th>Consumer (ID)</th>
                                <th>Consumer Name</th>
                                <th class="desktop">Address </th>
                                <th>Mobile Number</th>
                                <th class="desktop">Internet Profile</th>
                                <th>Expiry Date</th>
                                <th class="desktop">Trader (ID)</th>
                                <th style="width: 17%">Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="9">No Data Found</td>
                                </tr>
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
<script>
 $(document).ready(function() {
	var table;  // Define table outside of the click handler

$('#loadData').on('click', function() {
    let selectedContractor = $('#dealer-dropdown').val();
    let selectedTrader = $('#trader-dropdown').val();
    let searchFilter = $('#searchFilter').val();

    if ($.fn.dataTable.isDataTable('.data-table')) {
        table.clear().destroy();  // Clear and destroy the table if it's already initialized
    }

    // Initialize the DataTable
    table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('users.expireServerSideUser') }}",
            type: "GET",
            data: function(d) {
                d.contractor = selectedContractor;
                d.trader = selectedTrader;
                d.searchFilter = searchFilter;
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'username', name: 'username', class:'td__profileName'},
            {data: 'fullname', name: 'fullname'},
            {data: 'address', name: 'address'},
            {data: 'mobilephone', name: 'mobilephone'},
            {data: 'name', name: 'name', class:'green_color'},
            {data: 'expireDate', name: 'expireDate', class:'fw-bold'},
            {data: 'subdealerid', name: 'subdealerid'},
            {data: 'action', name: 'action'},
        ]
    });

    // Reload the DataTable
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
@include('users.never_expire.neverExpireModal')
@endsection
<!-- Code Finalize -->
