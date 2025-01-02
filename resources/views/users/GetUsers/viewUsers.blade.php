@extends('users.layouts.app')
@section('title')
    Get Users
@endsection
@section('owncss')
    <style type="text/css">
        .form-group label {
            font-weight: bold;
        }

        .form-group .helping-mark {
            margin-left: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        #usersTable th,
        #usersTable td {
            text-align: center;
        }

        .header_view {
            padding-bottom: 15px;
        }

        section.box {
            padding: 15px;
            border: none;
            margin: 0;
        }

        .d-flex {
            display: flex;
            align-items: center;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .flex-grow-1 {
            flex-grow: 1;
        }

        .input-group {
            display: flex;
            align-items: center;
            position: relative;
        }

        .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group .input-group-text {
            background-color: #fff;
            border: 1px solid #ced4da;
            border-left: none;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.375rem 0.75rem;
            cursor: pointer;
        }

        .input-group .input-group-text i {
            font-size: 1rem;
            color: #6c757d;
        }

        .input-group .input-group-text:hover i {
            color: #495057;
        }

        .select2-container-multi .select2-choices {
            border: none;
            border-bottom: 1px solid !important;
        }
    </style>
@endsection
@section('content')
    <?php
    $manager_id = empty(Auth::user()->manager_id) ? null : Auth::user()->manager_id;
    $resellerid = empty(Auth::user()->resellerid) ? null : Auth::user()->resellerid;
    $dealerid = empty(Auth::user()->dealerid) ? null : Auth::user()->dealerid;
    $sub_dealer_id = empty(Auth::user()->sub_dealer_id) ? null : Auth::user()->sub_dealer_id;
    $trader_id = empty(Auth::user()->trader_id) ? null : Auth::user()->trader_id;

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
    <meta name="reseller-id" content="{{ Auth::user()->resellerid }}">

    <div class="page-container row-fluid container-fluid">
        <section id="main-content">
            <section class="wrapper main-wrapper">
                <div class="header_view">
                    <h2>Advance Search</h2>
                </div>
                <section class="box" style="padding: 20px; overflow: hidden;">
                    <div>
                        @if (session('error'))
                            <div class="alert alert-error alert-dismissible">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    <form id="filterForm">
                        <div class="row">
                            @if ($panelof == 'manager')
                                @php
                                    $resellers = \App\model\Users\UserInfo::where('status', 'reseller')
                                        ->where('manager_id', Auth::user()->manager_id)
                                        ->get();
                                @endphp
                                <div class="col-md-3">
                                    <div class="form-group position-relative">
                                        <label for="reseller-dropdown">Select Reseller</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="reseller-dropdown" class="form-select js-select2" required>
                                            <option value="">-- Select Reseller --</option>
                                            @foreach ($resellers as $reseller)
                                                <option value="{{ $reseller->username }}">{{ $reseller->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="resellers-data" style="display:none;">{{ $resellers->toJson() }}</div>
                            @endif
                            @if ($panelof == 'manager' || $panelof == 'reseller')
                                @php $contractors = []; @endphp
                                @if ($panelof == 'reseller')
                                    @php
                                        $contractors = App\model\Users\UserInfo::where('status', 'dealer')
                                            ->where('resellerid', Auth::user()->resellerid)
                                            ->get();
                                    @endphp
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group position-relative">
                                        <label for="contractor-dropdown">Select Contractor</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="contractor-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Contractor --</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->username }}">{{ $contractor->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if ($panelof == 'manager' || $panelof == 'reseller' || $panelof == 'dealer')
                                @php $traders = []; @endphp
                                @if ($panelof == 'dealer')
                                    @php
                                        $traders = \App\model\Users\UserInfo::where('status', 'subdealer')
                                            ->where('dealerid', Auth::user()->dealerid)
                                            ->get();
                                    @endphp
                                @endif
                                <div class="col-md-3">
                                    <div class="form-group position-relative">
                                        <label for="trader-dropdown">Select Trader</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="trader-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Trader --</option>
                                            @foreach ($traders as $trader)
                                                <option value="{{ $trader->username }}">{{ $trader->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group position-relative">
                                    <label for="multiSelect">Search Option</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <span style="position:absolute; right: 15px;bottom: 10px"><i class="fa fa-chevron-down"></i></span>
                                    <select id="multiSelect" class="form-select js-select2" multiple>
                                        <option value="ip">IP</option>
                                        <option value="phone">Phone Number</option>
                                        <option value="cnic">CNIC</option>
                                        <option value="mac">MAC Address</option>
                                        <option value="data">Data Utilization (GBs)</option>
                                        <option value="email">Email</option>
                                        <option value="passport">Passport</option>
                                        <option value="address">Address</option>
                                        <option value="city">City/State</option>
                                        <option value="date">Creation Date</option>
                                        <option value="verified">Verfified By</option>
                                        <option value="profile">Internet Profile</option>
                                        <option value="charge">Charge On</option>
                                        <option value="expire">Expire On</option>
                                        <option value="status">Consumer Status</option>
                                        <option value="active">Active/Deactive</option>
                                    </select>
                                </div>
                            </div>
                            @if ($panelof == 'manager')
                                @php
                                    $profiles = \App\model\Users\ManagerProfileRate::where('manager_id', Auth::user()->manager_id)
                                        ->distinct()
                                        ->get();
                                @endphp
                                <div class="col-md-3 hideable hide" id="profile">
                                    <div class="form-group position-relative">
                                        <label for="manager-profile-dropdown">Select Profile</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="manager-profile-dropdown" class="form-select js-select2" required>
                                            <option value="">-- Select Profile --</option>
                                            @foreach ($profiles as $profile)
                                                <option value="{{ $profile->name }}">{{ $profile->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="manager-profile-data" style="display:none;">{{ $profiles->toJson() }}</div>
                            @endif
                            @if ($panelof == 'reseller')
                                @php $profiles = \App\model\Users\ResellerProfileRate::where('resellerid', Auth::user()->resellerid)->get(); @endphp
                                <div class="col-md-3 hideable hide" id="profile">
                                    <div class="form-group position-relative">
                                        <label for="reseller-profile-dropdown">Select Profile</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="reseller-profile-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Profile --</option>
                                            @foreach ($profiles as $profile)
                                                <option value="{{ $profile->name }}">{{ $profile->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="reseller-profile-data" style="display:none;">{{ $profiles->toJson() }}</div>
                            @endif
                            @if ($panelof == 'dealer')
                                @php $profiles = \App\model\Users\DealerProfileRate::where('dealerid', Auth::user()->dealerid)->get(); @endphp
                                <div class="col-md-3 hideable hide" id="profile">
                                    <div class="form-group position-relative">
                                        <label for="contractor-profile-dropdown">Select Profile</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="contractor-profile-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Profile --</option>
                                            @foreach ($profiles as $profile)
                                                <option value="{{ $profile->name }}">{{ $profile->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="contractor-profile-data" style="display:none;">{{ $profiles->toJson() }}</div>
                            @endif
                            @if ($panelof == 'subdealer')
                                @php
                                    $profiles = \App\model\Users\SubdealerProfileRate::where('sub_dealer_id', Auth::user()->sub_dealer_id)
                                        ->distinct()
                                        ->get();
                                @endphp
                                <div class="col-md-3 hideable hide" id="profile">
                                    <div class="form-group position-relative">
                                        <label for="subdealer-profile-dropdown">Select Profile</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                        <select id="subdealer-profile-dropdown" class="form-select js-select2" required>
                                            <option value="">-- Select Profile --</option>
                                            @foreach ($profiles as $profile)
                                                <option value="{{ $profile->name }}">{{ $profile->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="subdealer-profile-data" style="display:none;">{{ $profiles->toJson() }}</div>
                            @endif
                            <div class="col-md-3 hideable hide" id="ip">
                                <div class="form-group position-relative">
                                    <label for="searchIP">IP Address</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchIP" class="form-control" placeholder="Enter IP address">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="phone">
                                <div class="form-group position-relative">
                                    <label for="searchPhone">Phone Number</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchPhone" class="form-control" placeholder="Enter Phone Number">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="cnic">
                                <div class="form-group position-relative">
                                    <label for="searchCNIC">CNIC</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchCNIC" class="form-control" placeholder="Enter CNIC">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="mac">
                                <div class="form-group position-relative">
                                    <label for="searchMAC">MAC Address</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchMAC" class="form-control" placeholder="Enter MAC Address">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="data">
                                <div class="form-group position-relative">
                                    <label for="searchDataUtilization">Data Utilization (GBs)</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="number" id="searchDataUtilization" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="email">
                                <div class="form-group position-relative">
                                    <label for="searchEmail">Email Address</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchEmail" class="form-control" placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="passport">
                                <div class="form-group position-relative">
                                    <label for="searchPassport">Passport Number</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchPassport" class="form-control" placeholder="Enter Passport Number">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="address">
                                <div class="form-group position-relative">
                                    <label for="searchAddress">Address</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchAddress" class="form-control" placeholder="Enter Address">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="city">
                                <div class="form-group position-relative">
                                    <label for="searchCityState">City/State</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchCityState" class="form-control" placeholder="Enter City/State">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="date">
                                <div class="form-group position-relative">
                                    <label for="searchCreation">Creation Date</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="date" id="searchCreation" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="charge">
                                <div class="form-group position-relative">
                                    <label for="chargeOnRange">Charge On</label>
                                    <div class="input-group">
                                        <input type="text" id="chargeOnRange" class="form-control" placeholder="Select date range">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="expire">
                                <div class="form-group position-relative">
                                    <label for="expireOnRange">Expire On</label>
                                    <div class="input-group">
                                        <input type="text" id="expireOnRange" class="form-control" placeholder="Select date range">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 hideable hide" id="verified">
                                <div class="form-group position-relative">
                                    <label for="verifiedBy">Verified By</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                    <select id="verifiedBy" class="form-select js-select2">
                                        <option value="">-- Select Verification Type --</option>
                                        <option value="CNIC">CNIC</option>
                                        <option value="Mobile">Mobile</option>
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="status">
                                <div class="form-group position-relative">
                                    <label for="userStatus">User Status</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                    <select id="userStatus" class="form-select js-select2">
                                        <option value="">-- Select User Status --</option>
                                        <option value="enable">Enabled</option>
                                        <option value="disable">Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 hideable hide" id="active">
                                <div class="form-group position-relative">
                                    <label for="cardStatus">Active/Deactive</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <span style="position:absolute; right: 12px;bottom: 7px"><i class="fa fa-chevron-down"></i></span>
                                    <select id="cardStatus" class="form-select js-select2">
                                        <option value="">-- Select Active/Deactive --</option>
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <button type="button" id="getUsers" class="btn btn-primary mt-3 pull-right">Search</button>
                    </form>
                </section>
                <section class="box" id="usersTableContainer" style="margin-top: 20px; padding: 20px; overflow: hidden; display: none;">
                    <div class="form-group">
                        <h3 class="text-center">Advance Search Result</h3>
                    </div>
                    <table id="usersTable" class="table table-bordered">
                    </table>
                </section>
            </section>
        </section>
    </div>
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailsModalLabel" style="color: #fff">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetailsContent">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('ownjs')
    <script>
        $(document).ready(function() {
            const filterMap = {
                ip: {
                    container: '#ip',
                    input: '#searchIP',
                    param: 'searchIP'
                },
                phone: {
                    container: '#phone',
                    input: '#searchPhone',
                    param: 'searchPhone'
                },
                cnic: {
                    container: '#cnic',
                    input: '#searchCNIC',
                    param: 'searchCNIC'
                },
                mac: {
                    container: '#mac',
                    input: '#searchMAC',
                    param: 'searchMAC'
                },
                data: {
                    container: '#data',
                    input: '#searchDataUtilization',
                    param: 'searchDataUtilization'
                },
                email: {
                    container: '#email',
                    input: '#searchEmail',
                    param: 'searchEmail'
                },
                passport: {
                    container: '#passport',
                    input: '#searchPassport',
                    param: 'searchPassport'
                },
                address: {
                    container: '#address',
                    input: '#searchAddress',
                    param: 'searchAddress'
                },
                city: {
                    container: '#city',
                    input: '#searchCityState',
                    param: 'searchCityState'
                },
                date: {
                    container: '#date',
                    input: '#searchCreation',
                    param: 'searchCreationDate'
                },
                charge: {
                    container: '#charge',
                    input: '#chargeOnRange',
                    param: 'chargeOnRange'
                },
                expire: {
                    container: '#expire',
                    input: '#expireOnRange',
                    param: 'expireOnRange'
                },
                verified: {
                    container: '#verified',
                    input: '#verifiedBy',
                    param: 'verifiedBy'
                },
                status: {
                    container: '#status',
                    input: '#userStatus',
                    param: 'userStatus'
                },
                active: {
                    container: '#active',
                    input: '#cardStatus',
                    param: 'cardStatus'
                },
            };

            function removeQueryParam(param) {
                const url = new URL(window.location.href);
                url.searchParams.delete(param);
                window.history.replaceState(null, null, url.toString());
            }

            $('#multiSelect').on('change', function() {
                const selected = $(this).val() || [];

                Object.keys(filterMap).forEach(fieldKey => {
                    const {
                        container,
                        input,
                        param
                    } = filterMap[fieldKey];

                    if (selected.includes(fieldKey)) {
                        $(container).removeClass('hide');
                    } else {
                        $(container).addClass('hide');

                        if (input) {
                            const $field = $(input);
                            if ($field.is('select')) {
                                $field.val('').trigger('change');
                            } else {
                                $field.val('');
                            }
                        }
                        if (param) {
                            removeQueryParam(param);
                        }
                    }
                });
            });

            let isPopulatingFilters = false;

            function getQueryParams() {
                const params = new URLSearchParams(window.location.search);
                const query = {};
                params.forEach((value, key) => {
                    query[key] = value;
                });
                return query;
            }

            function updateQueryParams(params) {
                const query = new URLSearchParams(params).toString();
                const newUrl = `/users/get-users?${query}`;
                window.history.replaceState(null, null, newUrl);
            }

            function populateDropdown(dropdown, options, selectedValue) {
                dropdown.html('<option value="">-- Select --</option>');
                options.forEach(option => {
                    const isSelected = option.value === selectedValue ? 'selected' : '';
                    dropdown.append(`<option value="${option.value}" ${isSelected}>${option.text}</option>`);
                });

                if (selectedValue) {
                    dropdown.val(selectedValue).trigger('change');
                }
            }

            function populateResellerDropdown(selectedValue = null) {
                console.log('Populating Reseller Profile Dropdown ' + selectedValue);
                const $resellerDropdown = $('#reseller-dropdown');

                if ($resellerDropdown.length === 0) {
                    console.warn('Reseller dropdown not present for this user.');
                    return;
                }

                const resellersData = $('#resellers-data').text();
                if (!resellersData) {
                    console.error('No reseller data available.');
                    return;
                }

                const resellers = JSON.parse(resellersData);
                const options = resellers.map(function(reseller) {
                    return {
                        value: reseller.resellerid,
                        text: reseller.username,
                    };
                });

                populateDropdown($resellerDropdown, options, selectedValue);
            }

            async function populateContractorDropdown(resellerId) {
                if (!resellerId) {
                    $('#contractor-dropdown').html('<option value="">-- Select Contractor --</option>');
                    return;
                }

                await $.ajax({
                    url: "{{ route('get.dealer') }}",
                    type: 'POST',
                    data: {
                        reseller_id: resellerId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        const options = response.dealer.map(dealer => ({
                            value: dealer.username,
                            text: dealer.username,
                        }));
                        populateDropdown($('#contractor-dropdown'), options, null);
                    },
                    error: function() {
                        console.error('Error fetching contractors');
                    },
                });
            }

            async function populateTraderDropdown(contractorId) {
                if (!contractorId) {
                    $('#trader-dropdown').html('<option value="">-- Select Traders --</option>');
                    return;
                }

                await $.ajax({
                    url: "{{ route('get.trader') }}",
                    type: 'POST',
                    data: {
                        dealer_id: contractorId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        const options = response.subdealer.map(subdealer => ({
                            value: subdealer.username,
                            text: subdealer.username,
                        }));
                        populateDropdown($('#trader-dropdown'), options, null);
                    },
                    error: function() {
                        console.error('Error fetching traders');
                    },
                });
            }

            function populateManagerProfileDropdown(selectedValue = null) {
                const $managerProfileDropdown = $('#manager-profile-dropdown');

                if ($managerProfileDropdown.length === 0) {
                    console.warn('Manager Profile dropdown not present for this user.');
                    return;
                }

                const managerProfileData = $('#manager-profile-data').text();
                if (!managerProfileData) {
                    console.error('No reseller data available.');
                    return;
                }

                const managerProfiles = JSON.parse(managerProfileData);
                const options = managerProfiles.map(function(managerProfile) {
                    return {
                        value: managerProfile.name,
                        text: managerProfile.name,
                    };
                });

                populateDropdown($managerProfileDropdown, options, selectedValue);
            }

            function populateResellerProfileDropdown(selectedValue = null) {
                const $resellerProfileDropdown = $('#reseller-profile-dropdown');

                if ($resellerProfileDropdown.length === 0) {
                    console.warn('Reseller Profile dropdown not present for this user.');
                    return;
                }

                const resellerProfileData = $('#reseller-profile-data').text();
                if (!resellerProfileData) {
                    console.error('No reseller data available.');
                    return;
                }

                const resellerProfiles = JSON.parse(resellerProfileData);
                const options = resellerProfiles.map(function(resellerProfile) {
                    return {
                        value: resellerProfile.name,
                        text: resellerProfile.name,
                    };
                });

                populateDropdown($resellerProfileDropdown, options, selectedValue);
            }

            function populateContractorProfileDropdown(selectedValue = null) {
                const $contractorProfileDropdown = $('#contractor-profile-dropdown');

                if ($contractorProfileDropdown.length === 0) {
                    console.warn('Contractor Profile dropdown not present for this user.');
                    return;
                }

                const contractorProfileData = $('#contractor-profile-data').text();
                if (!contractorProfileData) {
                    console.error('No contractor data available.');
                    return;
                }

                const contractorProfiles = JSON.parse(contractorProfileData);
                const options = contractorProfiles.map(function(contractorProfile) {
                    return {
                        value: contractorProfile.name,
                        text: contractorProfile.name,
                    };
                });

                populateDropdown($contractorProfileDropdown, options, selectedValue);
            }

            function populateSubdealerProfileDropdown(selectedValue = null) {
                const $subdealerProfileDropdown = $('#subdealer-profile-dropdown');

                if ($subdealerProfileDropdown.length === 0) {
                    console.warn('Subdealer Profile dropdown not present for this user.');
                    return;
                }

                const subdealerProfileData = $('#subdealer-profile-data').text();
                if (!subdealerProfileData) {
                    console.error('No subdealer data available.');
                    return;
                }

                const subdealerProfiles = JSON.parse(subdealerProfileData);
                const options = subdealerProfiles.map(function(subdealerProfile) {
                    return {
                        value: subdealerProfile.name,
                        text: subdealerProfile.name,
                    };
                });

                populateDropdown($subdealerProfileDropdown, options, selectedValue);
            }

            $('#reseller-dropdown').on('change', function() {
                $('#contractor-dropdown').html('<option value="">-- Select Contractor --</option>').val('').trigger('change');
                $('#trader-dropdown').html('<option value="">-- Select Trader --</option>').val('').trigger('change');

                const resellerId = $(this).val();
                populateContractorDropdown(resellerId);
            });

            $('#contractor-dropdown').on('change', function() {
                $('#trader-dropdown').html('<option value="">-- Select Trader --</option>').val('').trigger('change');
                const traderId = $(this).val();
                populateTraderDropdown(traderId);
            });

            async function populateFilters(params) {
                isPopulatingFilters = true;

                const isReseller = $('#reseller-dropdown').length === 0;
                if (!isReseller) {
                    if (params.resellerId) {
                        await populateResellerDropdown(params.resellerId);
                    } else {
                        await populateResellerDropdown();
                    }
                } else {
                    console.log("Logged in as Reseller. Skipping Reseller Dropdown Population.");
                }

                if (params.contractorId) {
                    const resellerId = isReseller ?
                        $('meta[name="reseller-id"]').attr('content') :
                        params.resellerId;

                    if (resellerId) {
                        await $.ajax({
                            url: "{{ route('get.dealer') }}",
                            type: 'POST',
                            data: {
                                reseller_id: resellerId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                const options = response.dealer.map(dealer => ({
                                    value: dealer.username,
                                    text: dealer.username,
                                }));
                                populateDropdown($('#contractor-dropdown'), options, params.contractorId);
                            },
                            error: function() {
                                console.error('Error fetching contractors');
                            },
                        });
                    } else {
                        console.warn('No Reseller ID available to fetch contractors.');
                    }
                }

                if (params.traderId && params.contractorId) {
                    await $.ajax({
                        url: "{{ route('get.trader') }}",
                        type: 'POST',
                        data: {
                            dealer_id: params.contractorId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            const options = response.subdealer.map(subdealer => ({
                                value: subdealer.username,
                                text: subdealer.username,
                            }));
                            populateDropdown($('#trader-dropdown'), options, params.traderId);
                        },
                        error: function() {
                            console.error('Error fetching traders');
                        },
                    });
                }

                const isManagerProfile = $('#manager-profile-dropdown').length === 0;
                if (!isManagerProfile) {
                    if (params.managerProfile) {
                        await populateManagerProfileDropdown(params.managerProfile);
                    } else {
                        await populateManagerProfileDropdown();
                    }
                }

                const isResellerProfile = $('#reseller-profile-dropdown').length === 0;
                if (!isResellerProfile) {
                    if (params.resellerProfile) {
                        await populateResellerProfileDropdown(params.resellerProfile);
                    } else {
                        await populateResellerProfileDropdown();
                    }
                }

                const isContractorProfile = $('#contractor-profile-dropdown').length === 0;
                if (!isContractorProfile) {
                    if (params.contractorProfile) {
                        await populateContractorProfileDropdown(params.contractorProfile);
                    } else {
                        await populateContractorProfileDropdown();
                    }
                }

                const isSubdealerProfile = $('#subdealer-profile-dropdown').length === 0;
                if (!isSubdealerProfile) {
                    if (params.subdealerProfile) {
                        await populateSubdealerProfileDropdown(params.subdealerProfile);
                    } else {
                        await populateSubdealerProfileDropdown();
                    }
                }

                function toggleDropdownVisibility(dropdownId, paramValue) {
                    const $dropdown = $(`#${dropdownId}`);
                    const $container = $dropdown.closest('.hideable');

                    if (paramValue) {
                        $container.removeClass('hide');
                        $dropdown.val(paramValue).trigger('change');
                    } else {
                        $container.addClass('hide');
                        $dropdown.val('').trigger('change');
                    }
                }

                toggleDropdownVisibility('userStatus', params.userStatus);
                toggleDropdownVisibility('chargeOnRange', params.chargeOnRange);
                toggleDropdownVisibility('expireOnRange', params.expireOnRange);
                toggleDropdownVisibility('searchIP', params.searchIP);
                toggleDropdownVisibility('verifiedBy', params.verifiedBy);
                toggleDropdownVisibility('cardStatus', params.cardStatus);
                toggleDropdownVisibility('searchPhone', params.searchPhone);
                toggleDropdownVisibility('searchCNIC', params.searchCNIC);
                toggleDropdownVisibility('searchMAC', params.searchMAC);
                toggleDropdownVisibility('searchDataUtilization', params.searchDataUtilization);
                toggleDropdownVisibility('searchEmail', params.searchEmail);
                toggleDropdownVisibility('searchPassport', params.searchPassport);
                toggleDropdownVisibility('searchAddress', params.searchAddress);
                toggleDropdownVisibility('searchCityState', params.searchCityState);
                toggleDropdownVisibility('searchCreation', params.searchCreation);

                const selectedColumns = [];
                if (params.userStatus) selectedColumns.push('status');
                if (params.chargeOnRange) selectedColumns.push('charge');
                if (params.expireOnRange) selectedColumns.push('expire');
                if (params.searchIP) selectedColumns.push('ip');
                if (params.verifiedBy) selectedColumns.push('verified');
                if (params.cardStatus) selectedColumns.push('active');
                if (params.searchPhone) selectedColumns.push('phone');
                if (params.searchCNIC) selectedColumns.push('cnic');
                if (params.searchMAC) selectedColumns.push('mac');
                if (params.searchDataUtilization) selectedColumns.push('data');
                if (params.searchEmail) selectedColumns.push('email');
                if (params.searchPassport) selectedColumns.push('passport');
                if (params.searchAddress) selectedColumns.push('address');
                if (params.searchCityState) selectedColumns.push('city');
                if (params.searchCreation) selectedColumns.push('date');

                $('#multiSelect').val(selectedColumns).trigger('change');

                isPopulatingFilters = false;
            }


            function fetchData(queryParams = null) {
                const params = new URLSearchParams();

                if (queryParams) {
                    Object.keys(queryParams).forEach((key) => {
                        params.append(key, queryParams[key]);
                    });
                } else {
                    if ($('#reseller-dropdown').val()) params.append('resellerId', $('#reseller-dropdown').val());
                    if ($('#contractor-dropdown').val()) params.append('contractorId', $('#contractor-dropdown').val());
                    if ($('#trader-dropdown').val()) params.append('traderId', $('#trader-dropdown').val());
                    if ($('#manager-profile-dropdown').val()) params.append('managerProfile', $('#manager-profile-dropdown').val());
                    if ($('#reseller-profile-dropdown').val()) params.append('resellerProfile', $('#reseller-profile-dropdown').val());
                    if ($('#contractor-profile-dropdown').val()) params.append('contractorProfile', $('#contractor-profile-dropdown').val());
                    if ($('#subdealer-profile-dropdown').val()) params.append('subdealerProfile', $('#subdealer-profile-dropdown').val());
                    if ($('#chargeOnRange').val()) params.append('chargeOnRange', $('#chargeOnRange').val());
                    if ($('#expireOnRange').val()) params.append('expireOnRange', $('#expireOnRange').val());
                    if ($('#searchIP').val()) params.append('searchIP', $('#searchIP').val());
                    if ($('#verifiedBy').val()) params.append('verifiedBy', $('#verifiedBy').val());
                    if ($('#userStatus').val()) params.append('userStatus', $('#userStatus').val());
                    if ($('#cardStatus').val()) params.append('cardStatus', $('#cardStatus').val());
                    if ($('#searchPhone').val()) params.append('searchPhone', $('#searchPhone').val());
                    if ($('#searchCNIC').val()) params.append('searchCNIC', $('#searchCNIC').val());
                    if ($('#searchMAC').val()) params.append('searchMAC', $('#searchMAC').val());
                    if ($('#searchDataUtilization').val()) params.append('searchDataUtilization', $('#searchDataUtilization').val());
                    if ($('#searchEmail').val()) params.append('searchEmail', $('#searchEmail').val());
                    if ($('#searchPassport').val()) params.append('searchPassport', $('#searchPassport').val());
                    if ($('#searchAddress').val()) params.append('searchAddress', $('#searchAddress').val());
                    if ($('#searchCityState').val()) params.append('searchCityState', $('#searchCityState').val());
                    if ($('#searchCreationDate').val()) params.append('searchCreationDate', $('#searchCreationDate').val());
                }

                const newUrl = `/users/get-users?${params.toString()}`;
                window.history.replaceState(null, null, newUrl);

                $('#usersTableContainer').show();

                if ($.fn.DataTable.isDataTable('#usersTable')) {
                    $('#usersTable').DataTable().clear().destroy();
                    $('#usersTable thead').remove();
                    $('#usersTable tbody').remove();
                }

                const selectedFilters = $('#multiSelect').val() || [];

                let columns = [{
                    data: 'username',
                    name: 'username',
                    title: 'Username'
                }, ];

                if (selectedFilters.includes('email')) {
                    columns.push({
                        data: 'email',
                        name: 'email',
                        title: 'Email'
                    });
                }
                if (selectedFilters.includes('phone')) {
                    columns.push({
                        data: 'mobilephone',
                        name: 'mobilephone',
                        title: 'Phone'
                    });
                }
                if (selectedFilters.includes('cnic')) {
                    columns.push({
                        data: 'nic',
                        name: 'nic',
                        title: 'CNIC'
                    });
                }
                if (selectedFilters.includes('mac')) {
                    columns.push({
                        data: 'mac_address',
                        name: 'mac_address',
                        title: 'MAC Address'
                    });
                }
                if (selectedFilters.includes('address')) {
                    columns.push({
                        data: 'address',
                        name: 'address',
                        title: 'Address'
                    });
                }
                if (selectedFilters.includes('city')) {
                    columns.push({
                        data: 'city',
                        name: 'city',
                        title: 'City'
                    });
                }
                if (selectedFilters.includes('passport')) {
                    columns.push({
                        data: 'passport',
                        name: 'passport',
                        title: 'Passport'
                    });
                }
                if (selectedFilters.includes('ip')) {
                    console.log("IP filter is selected");
                    columns.push({
                        data: 'ip_address',
                        name: 'ip',
                        title: 'IP Address'
                    });
                }
                if (selectedFilters.includes('data')) {
                    columns.push({
                        data: 'data_utilization',
                        name: 'data_utilization',
                        title: 'Data (GBs)',
                    });
                }
                if (selectedFilters.includes('status')) {
                    columns.push({
                        data: 'status',
                        name: 'status',
                        title: 'Consumer Status'
                    });
                }

                if (selectedFilters.includes('active')) {
                    columns.push({
                        data: 'card_active',
                        name: 'card_active',
                        title: 'Active/Deactive'
                    });
                }

                if (selectedFilters.includes('verified')) {
                    columns.push({
                        data: 'verified_cnic',
                        name: 'verified_cnic',
                        title: 'Verified By (CNIC)'
                    });
                }

                if (selectedFilters.includes('charge')) {
                    columns.push({
                        data: 'card_charge_on',
                        name: 'card_charge_on',
                        title: 'Charge On'
                    });
                }

                if (selectedFilters.includes('expire')) {
                    columns.push({
                        data: 'card_expire_on',
                        name: 'card_expire_on',
                        title: 'Expire On'
                    });
                }

                if (selectedFilters.includes('date')) {
                    columns.push({
                        data: 'creationdate',
                        name: 'creationdate',
                        title: 'Creation Date'
                    });
                }

                if (selectedFilters.includes('profile')) {
                    columns.push({
                        data: 'name',
                        name: 'name',
                        title: 'Internet Profile'
                    });
                }

                columns.push({
                    data: 'id',
                    name: 'action',
                    title: 'Action',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                        <button class="btn btn-sm view-user-details" data-id="${data}"
                            style="background-color: #17a2b8; border-color: #17a2b8; color: white; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-eye" style="font-size: 18px;"></i>
                        </button>
                        `;
                    },
                });

                $('#usersTable').append(`
                    <thead><tr></tr></thead>
                    <tbody></tbody>
                `);
                $('#usersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/users/get-filtered-users',
                        type: 'GET',
                        data: function(d) {
                            d.resellerId = $('#reseller-dropdown').val();
                            d.contractorId = $('#contractor-dropdown').val();
                            d.traderId = $('#trader-dropdown').val();
                            d.managerProfile = $('#manager-profile-dropdown').val();
                            d.resellerProfile = $('#reseller-profile-dropdown').val();
                            d.contractorProfile = $('#contractor-profile-dropdown').val();
                            d.subdealerProfile = $('#subdealer-profile-dropdown').val();
                            d.chargeOnRange = $('#chargeOnRange').val();
                            d.expireOnRange = $('#expireOnRange').val();
                            d.searchIP = $('#searchIP').val();
                            d.verifiedBy = $('#verifiedBy').val();
                            d.userStatus = $('#userStatus').val();
                            d.cardStatus = $('#cardStatus').val();
                            d.searchPhone = $('#searchPhone').val();
                            d.searchCNIC = $('#searchCNIC').val();
                            d.searchMAC = $('#searchMAC').val();
                            d.searchDataUtilization = $('#searchDataUtilization').val();
                            d.searchEmail = $('#searchEmail').val();
                            d.searchPassport = $('#searchPassport').val();
                            d.searchAddress = $('#searchAddress').val();
                            d.searchCityState = $('#searchCityState').val();
                            d.searchCreation = $('#searchCreation').val();
                        },
                    },
                    columns: columns,
                });
            }

            (async function() {
                const queryParams = getQueryParams();

                if (Object.keys(queryParams).length > 0) {
                    await populateFilters(queryParams);
                    fetchData(queryParams);
                } else {
                    console.log('No query parameters present. Skipping fetchData.');
                }
            })();

            $('#getUsers').on('click', function() {
                let isSelectFilter = false;
                if (
                    !$('#reseller-dropdown').val() &&
                    !$('#contractor-dropdown').val() &&
                    !$('#trader-dropdown').val() &&
                    !$('#manager-profile-dropdown').val() &&
                    !$('#reseller-profile-dropdown').val() &&
                    !$('#contractor-profile-dropdown').val() &&
                    !$('#subdealer-profile-dropdown').val() &&
                    !$('#chargeOnRange').val() &&
                    !$('#expireOnRange').val() &&
                    !$('#searchIP').val() &&
                    !$('#verifiedBy').val() &&
                    !$('#userStatus').val() &&
                    !$('#cardStatus').val() &&
                    !$('#searchPhone').val() &&
                    !$('#searchCNIC').val() &&
                    !$('#searchMAC').val() &&
                    !$('#searchDataUtilization').val() &&
                    !$('#searchEmail').val() &&
                    !$('#searchPassport').val() &&
                    !$('#searchAddress').val() &&
                    !$('#searchCityState').val() &&
                    !$('#searchCreation').val()
                ) {
                    alert('Please select atleast one filter');
                    return;
                }

                fetchData();
            });

            $('#chargeOnRange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('#chargeOnRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#chargeOnRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $('#expireOnRange').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            $('#expireOnRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#expireOnRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

            $(document).on('click', '.view-user-details', function() {
                const userId = $(this).data('id');

                $.ajax({
                    url: `/users/details/${userId}`,
                    type: 'GET',
                    success: function(response) {
                        $('#userDetailsContent').html(`
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td><strong>ID</strong></td><td>${response.id}</td></tr>
                                <tr><td><strong>Username</strong></td><td>${response.username}</td></tr>
                                <tr><td><strong>Email</strong></td><td>${response.email}</td></tr>
                                <tr><td><strong>Status</strong></td><td>${response.status}</td></tr>
                                <tr><td><strong>Address</strong></td><td>${response.address || 'N/A'}</td></tr>
                                <tr><td><strong>City</strong></td><td>${response.city || 'N/A'}</td></tr>
                                <tr><td><strong>State</strong></td><td>${response.state || 'N/A'}</td></tr>
                                <tr><td><strong>Phone</strong></td><td>${response.mobilephone || 'N/A'}</td></tr>
                                <tr><td><strong>MAC Address</strong></td><td>${response.mac_address || 'N/A'}</td></tr>
                                <tr><td><strong>NIC</strong></td><td>${response.nic || 'N/A'}</td></tr>
                                <tr><td><strong>Passport</strong></td><td>${response.passport || 'N/A'}</td></tr>
                                <tr><td><strong>Creation Date</strong></td><td>${response.creationdate || 'N/A'}</td></tr>
                                <tr><td><strong>IP Address</strong></td><td>${response.ip_address || 'N/A'}</td></tr>
                                <tr><td><strong>Card Charge On</strong></td><td>${response.card_charge_on || 'N/A'}</td></tr>
                                <tr><td><strong>Card Expire On</strong></td><td>${response.card_expire_on || 'N/A'}</td></tr>
                                <tr><td><strong>Verified CNIC</strong></td><td>${response.verified_cnic || 'N/A'}</td></tr>
                                <tr><td><strong>Verified Mobile</strong></td><td>${response.verified_mobile || 'N/A'}</td></tr>
                                <tr><td><strong>Disabled Status</strong></td><td>${response.disabled_status || 'N/A'}</td></tr>
                            </tbody>
                        </table>
                    `);
                        $('#userDetailsModal').modal('show');
                    },
                    error: function(error) {
                        console.error('Error fetching user details:', error);
                        alert('Failed to fetch user details.');
                    }
                });
            });
        });
    </script>
@endsection
