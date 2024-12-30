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
@section('title')
    Dashboard
@endsection
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
                        <h2 id="heading">Upcoming Expiries
                            <span class="info-mark" onmouseenter="popup_function(this, 'offline_consumers');" onmouseleave="popover_dismiss();"><i
                                   class="las la-info-circle"></i></span>
                        </h2>
                    </div>

                    <div class="content-body" style="padding-top: 30px">

                        <?php
                        $manager_id = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
                        $resellerid = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
                        $dealerid = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
                        $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
                        $trader_id = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;
                        //
                        if (empty($resellerid)) {
                            $panelof = 'manager';
                        } elseif (empty($dealerid)) {
                            $panelof = 'reseller';
                        } elseif (empty($sub_dealer_id)) {
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function getIP(item, mac) {
            $.ajax({
                url: "{{ route('user.dhcp') }}",
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
            get_upcomming_expiry_users();
            // Initialize the DataTable on button click
            $('#loadData').on('click', function() {
                get_upcomming_expiry_users();
            });

            function get_upcomming_expiry_users() {
                let selectedContractor = $('#dealer-dropdown').val();
                let selectedTrader = $('#trader-dropdown').val();
                let searchFilter = $('#searchFilter').val();

                if (table) {
                    table.destroy(); // Destroy existing table if it exists
                }

                // Initialize the DataTable
                table = $('#example-2').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ route('users.billing.upcoming_expiry.data') }}",
                        type: "GET",
                        data: function(d) {
                            d.contractor = selectedContractor;
                            d.trader = selectedTrader;
                            d.searchFilter = searchFilter;
                        }
                    },
                    columns: [{
                            data: 'serial',
                            name: 'serial'
                        },
                        {
                            data: 'username',
                            name: 'username'
                        },
                        {
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'mobilephone',
                            name: 'mobilephone'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'remaining_days',
                            name: 'remaining_days'
                        },
                        {
                            data: 'profile',
                            name: 'profile'
                        },
                        {
                            data: 'card_charge_on',
                            name: 'card_charge_on'
                        },
                        {
                            data: 'card_expire_on',
                            name: 'card_expire_on'
                        },
                        {
                            data: 'dealerid',
                            name: 'dealerid'
                        },
                        {
                            data: 'sub_dealer_id',
                            name: 'sub_dealer_id'
                        }
                    ],
                    // order: [[1, 'asc']],
                    createdRow: function(row, data, dataIndex) {
                        if (data.remaining_days < 1) {
                            $('td:eq(5)', row).html('<span class="blinkMe">to day(s)</span>');
                        } else {
                            $('td:eq(5)', row).html('<span class="blinkMe">' + data.remaining_days + ' day(s)</span>');
                        }
                    }
                });

                // Reload the DataTable with new data based on selected filters
                table.ajax.reload();
            }
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
                    data: {
                        dealer_id: tradersUsername
                    },
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
