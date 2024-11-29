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

</style>
@endsection
@section('content')
<?php
    $manager_id    = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
    $resellerid    = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
    $dealerid      = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
    $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
    $trader_id     = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;

    if(empty($resellerid)){
    $panelof = 'manager';
    }else if(empty($dealerid)){
    $panelof = 'reseller';
    }else if(empty($sub_dealer_id)){
    $panelof = 'dealer';
    }else{
    $panelof = 'subdealer';
    }
?>
<meta name="reseller-id" content="{{ Auth::user()->resellerid }}">

    <div class="page-container row-fluid container-fluid">
        <section id="main-content">
            <section class="wrapper main-wrapper">
                <div class="header_view">
                    <h2>Filter Users</h2>
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
                            @if($panelof == 'manager')
                                @php $resellers = \App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get(); @endphp
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label for="reseller-dropdown">Select Reseller <span style="color: red">*</span></label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <select id="reseller-dropdown" class="form-select js-select2" required>
                                            <option value="">-- Select Reseller --</option>
                                            @foreach($resellers  as $reseller)
                                                <option value="{{$reseller->username}}">{{$reseller->username}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="resellers-data" style="display:none;">{{ $resellers->toJson() }}</div>
                            @endif
                            @if(($panelof == 'manager') || ($panelof == 'reseller'))
                                @php $contractors = []; @endphp
                                @if ($panelof == 'reseller')
                                    @php $contractors = App\model\Users\UserInfo::where('status', 'dealer')->where('resellerid', Auth::user()->resellerid)->get(); @endphp
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label for="contractor-dropdown">Select Contractor</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <select id="contractor-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Contractor --</option>
                                            @foreach ($contractors as $contractor)
                                                <option value="{{ $contractor->username }}">{{ $contractor->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(($panelof == 'manager') || ($panelof == 'reseller') || ($panelof == 'dealer') )
                                @php $traders = []; @endphp
                                @if ($panelof == 'dealer')
                                    @php $traders = \App\model\Users\UserInfo::where('status', 'subdealer')->where('dealerid', Auth::user()->dealerid)->get(); @endphp
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label for="trader-dropdown">Select Trader</label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <select id="trader-dropdown" class="form-select js-select2">
                                            <option value="">-- Select Trader --</option>
                                            @foreach ($traders as $trader)
                                                <option value="{{ $trader->username }}">{{ $trader->username }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if($panelof == 'manager')
                                @php $profiles = \App\model\Users\ManagerProfileRate::where('manager_id', Auth::user()->manager_id)->distinct()->get(); @endphp
                                <div class="col-md-4">
                                    <div class="form-group position-relative">
                                        <label for="manager-profile-dropdown">Select Profile <span style="color: red">*</span></label>
                                        <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                        <select id="manager-profile-dropdown" class="form-select js-select2" required>
                                            <option value="">-- Select Profile --</option>
                                            @foreach($profiles  as $profile)
                                                <option value="{{$profile->name}}">{{$profile->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="manager-profile-data" style="display:none;">{{ $profiles->toJson() }}</div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="chargeOnRange">Charge On</label>
                                    <div class="input-group">
                                        <input type="text" id="chargeOnRange" class="form-control" placeholder="Select date range">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="expireOnRange">Expire On</label>
                                    <div class="input-group">
                                        <input type="text" id="expireOnRange" class="form-control" placeholder="Select date range">
                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="searchIP">Search by IP</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchIP" class="form-control" placeholder="Enter IP address">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="verifiedBy">Verified By</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <select id="verifiedBy" class="form-select js-select2">
                                        <option value="">-- Select Verification Type --</option>
                                        <option value="CNIC">CNIC</option>
                                        <option value="Mobile">Mobile</option>
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="userStatus">User Status</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <select id="userStatus" class="form-select js-select2">
                                        <option value="">-- Select User Status --</option>
                                        <option value="enable">Enabled</option>
                                        <option value="disable">Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="cardStatus">Active/Deactive</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <select id="cardStatus" class="form-select js-select2">
                                        <option value="">-- Select Active/Deactive --</option>
                                        <option value="active">Active</option>
                                        <option value="deactive">Deactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="searchPhone">Search by Phone Number</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchPhone" class="form-control" placeholder="Enter Phone Number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group position-relative">
                                    <label for="searchCNIC">Search by CNIC</label>
                                    <span class="helping-mark"><i class="fa fa-question-circle"></i></span>
                                    <input type="text" id="searchCNIC" class="form-control" placeholder="Enter CNIC">
                                </div>
                            </div>
                        </div>
                        <button type="button" id="getUsers" class="btn btn-primary mt-3">Get Users</button>
                    </form>
                </section>
                <section class="box" id="usersTableContainer" style="margin-top: 20px; padding: 20px; overflow: hidden; display: none;">
                    <div class="form-group">
                        <h3 class="text-center">Users Table</h3>
                    </div>
                    <table id="usersTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                    </table>
                </section>
            </section>
        </section>
    </div>
@endsection
@section('ownjs')
<script>
      $(document).ready(function () {
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
            const options   = resellers.map(function (reseller) {
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
                url: "{{route('get.dealer')}}",
                type: 'POST',
                data: {
                    reseller_id: resellerId,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    console.log(response);
                    const options = response.dealer.map(dealer => ({
                        value: dealer.username,
                        text: dealer.username,
                    }));
                    populateDropdown($('#contractor-dropdown'), options, null);
                },
                error: function () {
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
                url: "{{route('get.trader')}}",
                type: 'POST',
                data: {
                    dealer_id: contractorId,
                    _token: '{{csrf_token()}}'
                },
                success: function (response) {
                    const options = response.subdealer.map(subdealer => ({
                        value: subdealer.username,
                        text: subdealer.username,
                    }));
                    populateDropdown($('#trader-dropdown'), options, null);
                },
                error: function () {
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
            const options   = managerProfiles.map(function (managerProfile) {
                return {
                    value: managerProfile.name,
                    text: managerProfile.name,
                };
            });

            populateDropdown($managerProfileDropdown, options, selectedValue);
        }

        $('#reseller-dropdown').on('change', function () {
            $('#contractor-dropdown').html('<option value="">-- Select Contractor --</option>').val('').trigger('change');
            $('#trader-dropdown').html('<option value="">-- Select Trader --</option>').val('').trigger('change');

            const resellerId = $(this).val();
            populateContractorDropdown(resellerId);
        });

        $('#contractor-dropdown').on('change', function () {
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
                const resellerId = isReseller
                    ? $('meta[name="reseller-id"]').attr('content')
                    : params.resellerId;

                if (resellerId) {
                    await $.ajax({
                        url: "{{route('get.dealer')}}",
                        type: 'POST',
                        data: {
                            reseller_id: resellerId,
                            _token: '{{csrf_token()}}'
                        },
                        success: function (response) {
                            const options = response.dealer.map(dealer => ({
                                value: dealer.username,
                                text: dealer.username,
                            }));
                            populateDropdown($('#contractor-dropdown'), options, params.contractorId);
                        },
                        error: function () {
                            console.error('Error fetching contractors');
                        },
                    });
                } else {
                    console.warn('No Reseller ID available to fetch contractors.');
                }
            }

            if (params.traderId && params.contractorId) {
                await $.ajax({
                    url: "{{route('get.trader')}}",
                    type: 'POST',
                    data: {
                        dealer_id: params.contractorId,
                        _token: '{{csrf_token()}}'
                    },
                    success: function (response) {
                        const options = response.subdealer.map(subdealer => ({
                            value: subdealer.username,
                            text: subdealer.username,
                        }));
                        populateDropdown($('#trader-dropdown'), options, params.traderId);
                    },
                    error: function () {
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

            if (params.userStatus) $('#userStatus').val(params.userStatus).trigger('change');
            if (params.chargeOnRange) $('#chargeOnRange').val(params.chargeOnRange);
            if (params.expireOnRange) $('#expireOnRange').val(params.expireOnRange);
            if (params.searchIP) $('#searchIP').val(params.searchIP);
            if (params.verifiedBy) $('#verifiedBy').val(params.verifiedBy).trigger('change');
            if (params.cardStatus) $('#cardStatus').val(params.cardStatus).trigger('change');
            if (params.searchPhone) $('#searchPhone').val(params.searchPhone);
            if (params.searchCNIC) $('#searchCNIC').val(params.searchCNIC);

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
                if ($('#chargeOnRange').val()) params.append('chargeOnRange', $('#chargeOnRange').val());
                if ($('#expireOnRange').val()) params.append('expireOnRange', $('#expireOnRange').val());
                if ($('#searchIP').val()) params.append('searchIP', $('#searchIP').val());
                if ($('#verifiedBy').val()) params.append('verifiedBy', $('#verifiedBy').val());
                if ($('#userStatus').val()) params.append('userStatus', $('#userStatus').val());
                if ($('#cardStatus').val()) params.append('cardStatus', $('#cardStatus').val());
                if ($('#searchPhone').val()) params.append('searchPhone', $('#searchPhone').val());
                if ($('#searchCNIC').val()) params.append('searchCNIC', $('#searchCNIC').val());
            }

            const newUrl = `/users/get-users?${params.toString()}`;
            window.history.replaceState(null, null, newUrl);

            $('#usersTableContainer').show();

            if (!$.fn.dataTable.isDataTable('#usersTable')) {
                $('#usersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '/users/get-filtered-users',
                        type: 'GET',
                        data: function (d) {
                            d.resellerId     = $('#reseller-dropdown').val();
                            d.contractorId   = $('#contractor-dropdown').val();
                            d.traderId       = $('#trader-dropdown').val();
                            d.managerProfile = $('#manager-profile-dropdown').val();
                            d.chargeOnRange  = $('#chargeOnRange').val();
                            d.expireOnRange  = $('#expireOnRange').val();
                            d.searchIP       = $('#searchIP').val();
                            d.verifiedBy     = $('#verifiedBy').val();
                            d.userStatus     = $('#userStatus').val();
                            d.cardStatus     = $('#cardStatus').val();
                            d.searchPhone    = $('#searchPhone').val();
                            d.searchCNIC     = $('#searchCNIC').val();
                        },
                    },
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'username', name: 'username' },
                        { data: 'email', name: 'email' },
                        { data: 'status', name: 'status' },
                    ],
                });
            } else {
                $('#usersTable').DataTable().ajax.reload();
            }
        }

        (async function () {
            const queryParams = getQueryParams();

            if (Object.keys(queryParams).length > 0) {
                await populateFilters(queryParams);
                fetchData(queryParams);
            } else {
                console.log('No query parameters present. Skipping fetchData.');
            }
        })();


        $('#getUsers').on('click', function () {
            let isSelectFilter = false;
            if (
                !$('#reseller-dropdown').val() &&
                !$('#contractor-dropdown').val() &&
                !$('#trader-dropdown').val() &&
                !$('#manager-profile-dropdown').val() &&
                !$('#chargeOnRange').val() &&
                !$('#expireOnRange').val() &&
                !$('#searchIP').val() &&
                !$('#verifiedBy').val() &&
                !$('#userStatus').val() &&
                !$('#cardStatus').val() &&
                !$('#searchPhone').val() &&
                !$('#searchCNIC').val()
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

        $('#chargeOnRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#chargeOnRange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $('#expireOnRange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        });

        $('#expireOnRange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#expireOnRange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

    });
</script>

@endsection
