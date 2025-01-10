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
@include('users.layouts.bytesConvert')
@section('title')
    Dashboard
@endsection
@section('content')
    <style>
        #disableBtn.enable:hover {
            background-color: red
        }

        #disableBtn.disable:hover {
            background-color: green
        }
    </style>
    @php
        dd($user->status);
    @endphp
    <div class="page-container row-fluid container-fluid">
        <!-- CONTENET START -->
        <section id="main-content" class=" ">
            <section class="wrapper main-wrapper row" style=''>
                <div class="header_view">
                    @if ($user->status == 'manager')
                        <h2>Manager Detail</h2>
                    @elseif($user->status == 'reseller')
                        <h2>Reselller Detail</h2>
                    @elseif($user->status == 'dealer')
                        <h2>Contractor Detail</h2>
                    @elseif($user->status == 'subdealer')
                        <h2>Trader Detail</h2>
                    @else
                        <h2>Consumer Detail</h2>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="">
                            @if ($user->status == 'manager')
                                <img alt="" src="{{ asset('img/avatar/manager_detail_avatar.png') }}" class="img-responsive" style="margin: auto; width: 100px;">
                            @elseif($user->status == 'reseller')
                                <img alt="" src="{{ asset('img/avatar/reseller_detail_avatar.png') }}" class="img-responsive" style="margin: auto; width: 100px;">
                            @elseif($user->status == 'dealer')
                                <img alt="" src="{{ asset('img/avatar/dealer_detail_avatar.png') }}" class="img-responsive" style="margin: auto; width: 100px;">
                            @elseif($user->status == 'subdealer')
                                <img alt="" src="{{ asset('img/avatar/trader_detail_avatar.png') }}" class="img-responsive" style="margin: auto; width: 100px;">
                            @else
                                <img alt="" src="{{ asset('img/avatar/user_detail_avatar.png') }}" class="img-responsive" style="margin: auto; width: 100px;">
                            @endif
                        </div>
                        <div class="uprofile-name">
                            <h3>
                                <a href="#">{{ $user->username }}</a>
                                <!-- Available statuses: online, idle, busy, away and offline -->
                                <span class="uprofile-status online"> </span>
                            </h3>
                            @if ($user->status == 'dealer')
                                <p class="uprofile-title">Contractor</p>
                            @else
                                <p class="uprofile-title">{{ $user->status }}</p>
                            @endif
                        </div>
                        <div class="uprofile-info">
                            <ul class="list-unstyled">
                                <li><i class='fa fa-home'></i> {{ $user->address }}</li>
                                @if ($user->status == 'dealer')
                                    <li><i class='fa fa-users-cog'></i>Trader(s):
                                        {{ App\model\Users\UserInfo::where(['status' => 'subdealer', 'dealerid' => $user->dealerid])->count() }} </li>
                                    <li><i class='fa fa-users'></i>User(s): {{ App\model\Users\UserInfo::where(['status' => 'user', 'dealerid' => $user->dealerid])->count() }}
                                    </li>
                                @endif
                                @if ($user->status == 'reseller')
                                    <li><i class='fas fa-user-friends'></i>Contractor(s):
                                        {{ App\model\Users\UserInfo::where(['status' => 'dealer', 'resellerid' => $user->resellerid])->count() }}</li>
                                @endif
                                @if ($user->status == 'manager')
                                    <li><i class='fa fa-user-tie'></i>
                                        {{ App\model\Users\UserInfo::where(['status' => 'reseller', 'manager_id' => $user->manager_id])->count() }} Resellers</li>
                                    <li><i class='fas fa-user-friends'></i>
                                        {{ App\model\Users\UserInfo::where(['status' => 'dealer', 'manager_id' => $user->manager_id])->count() }} Contractors</li>
                                @endif
                            </ul>
                        </div>
                        @if (Auth::user()->status != 'support')
                            <div class="uprofile-buttons">
                                <button class="btn btn-success enable" id="disableBtn" value="enable" onclick="toggle('{{ $user->status }}');" style="width: 100%;">This
                                    {{ ucwords($user->status) }} is Enable</button>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="uprofile-content row">
                            <div class="col-lg-12">
                                <div class="header_view">
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#home">Detials</a></li>
                                                <li><a data-toggle="tab" href="#cnic_tab">CNIC (Picture)</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="home" class="tab-pane fade in active">
                                                    <div style="overflow-x: auto;">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                @if ($user->status == 'reseller' || $user->status == 'dealer' || $user->status == 'manager')
                                                                    <tr>
                                                                        <td class="td__profileName">Manager (ID)</td>
                                                                        <td>{{ $user->manager_id }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if ($user->status == 'reseller' || $user->status == 'dealer')
                                                                    <tr>
                                                                        <td class="td__profileName">Reseller (ID)</td>
                                                                        <td>{{ $user->resellerid }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if ($user->status == 'dealer')
                                                                    <tr>
                                                                        <td class="td__profileName">Contractor (ID)</td>
                                                                        <td>{{ $user->dealerid }}</td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td class="td__profileName">Username</td>
                                                                    <td>{{ $user->username }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td__profileName">Full Name</td>
                                                                    <td>{{ $user->firstname }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td__profileName">CNIC Number</td>
                                                                    <td>{{ $user->nic }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td__profileName">Mobile Number</td>
                                                                    <td>{{ $user->mobilephone }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td class="td__profileName">Address</td>
                                                                    <td>{{ $user->address }}</td>
                                                                </tr>
                                                                @if ($user->status != 'manager' && $user->status != 'user')
                                                                    <tr>
                                                                        <td class="td__profileName">Internet Profile</td>
                                                                        <td>
                                                                            @foreach ($userProfileRates as $uPr)
                                                                                <span class="badge badge-default" style="background-color:{{ $uPr->profile->color }}">
                                                                                    {{ $uPr->profile->name }}
                                                                                </span>
                                                                            @endforeach
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if ($user->status == 'user' && $profile != '')
                                                                    <tr>
                                                                        <td class="td__profileName">Internet Profile</td>
                                                                        <td>
                                                                            <span class="badge badge-default" style="background-color:{{ $profile->color }}">
                                                                                {{ $profile->name }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if ($user->status == 'user' && $cur_profile != '')
                                                                    <tr>
                                                                        <td class="td__profileName">Current Internet Profile</td>
                                                                        <td>
                                                                            <span class="badge badge-default" style="background-color:#8c8e8c"> {{ $cur_profile->name }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                {{-- <tr>
																<td>Password</td>
																@if ($user->status != 'manager' && $user->status != 'reseller' && $user->username != 'logonhome' && $user->username != 'logoncorp')
																<td> <span id="showpassword"> ********</span>
																	<i class="fa fa-eye" style="float: right;" onclick="showpass(this.id)" id="hide"> </i>
																</td>
																@else
																<td>N/A</td>
																@endif
															</tr>
															<tr>
																<td>Start Date</td>
																<td>{{$user->creation_date}}</td>
															</tr>
															@if ($user->status != 'manager' && $user->status != 'reseller')
															@if (!empty($userstatusinfo))
															<tr>
																<td>Last  Expire ON</td>
																<td>{{$userstatusinfo->card_charge_on}}</td>
															</tr>
															<tr>
																<td> Charge ON</td>
																<td>{{$userstatusinfo->card_charge_on}}</td>
															</tr>
															<tr>
																<td> Expire ON</td>
																<td>{{$userstatusinfo->card_expire_on}}</td>
															</tr>
															@endif
															@endif --}}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                @php
                                                    $totalDownload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctoutputoctets');
                                                    $tDownMonth = App\model\Users\RadAcct::where('username', $user->username)
                                                        ->where('acctstarttime', '>=', DATE('Y-m-01'))
                                                        ->where('acctstarttime', '<=', DATE('Y-m-t'))
                                                        ->sum('acctoutputoctets');
                                                    $totalupload = App\model\Users\RadAcct::where(['username' => $user->username])->sum('acctinputoctets');
                                                    $tupMonth = App\model\Users\RadAcct::where('username', $user->username)
                                                        ->where('acctstarttime', '>=', DATE('Y-m-01'))
                                                        ->where('acctstarttime', '<=', DATE('Y-m-t'))
                                                        ->sum('acctinputoctets');
                                                @endphp
                                                {{-- <div id="menu2" class="tab-pane fade">
												<div class="row">
													<div class="col-md-6" style="margin-top: 30px;">
														<center>
															<h3>Total Download</h3>
															<i class="fa fa-download" style="font-size: 65px;"></i>
															<h3>{{ByteSize($totalDownload)}}</h3>
														</center>
													</div>
													<div class="col-md-6" style="margin-top: 30px;">
														<center>
															<h3>Total Upload</h3>
															<i class="fa fa-upload" style="font-size: 65px;"></i>
															<h3>{{ByteSize($totalupload)}}</h3>
														</center>
													</div>
													<div class="col-md-12" style="margin-top: 50px; overflow-x: auto;">
														<table class="table table-bordered" style="overflow: auto;">
															<tr>
																<th>Total Login Session</th>
																<th>Last Login Date</th>
																<th>Monthly Download</th>
																<th>Monthly Upload</th>
																<th>Mac Address</th>
																<th>IP Address</th>
															</tr>
															<tr>
																@php
																$total_login  = App\model\Users\RadAcct::where(['username' => $user->username])->count();
																$login  = App\model\Users\RadAcct::where(['acctstoptime' => NULL,'username' => $user->username])->first();
																$mac	=App\model\Users\RadCheck::where(['username' => $user->username, 'attribute'=>'Calling-Station-Id'])->first();
																$lastlogin= App\model\Users\RadAcct::where(['username' => $user->username])->orderBy('radacctid','desc')->first();
																@endphp
																<td>{{$total_login}}</td>
																@if (!empty($lastlogin->acctstarttime))
																<td>{{$lastlogin->acctstarttime}}</td>
																@else
																<td>N/A</td>
																@endif
																<td>{{ByteSize($tDownMonth)}}</td>
																<td>{{ByteSize($tupMonth)}}</td>
																@if ($user->status != 'manager')
																<td>{{$mac->value}}<br>
																	@php
																	if($mac->value!='NEW'){
																	@endphp
																	<form
																	action="{{route('admin.users.user_detail')}}" method="POST" >
																	@csrf
																	<input type="hidden" name="clearmac" value="{{$user->username}}">
																	<input type="hidden" name="userid" value="{{$user->id}}">
																	<button type="submit" class="btn btn-xs">Clear Mac</button>
																</form>
																@php
															}
															@endphp
														</td>@else<td>N/A</td>
														@endif
														@if (!empty($login))
														<td>{{$login->framedipaddress}}</td>
														@else
														<td>N/A</td>
														@endif
													</tr>
												</table>
											</div>
										</div>
									</div>
									<div id="menu3" class="tab-pane fade">
										<div class="row">
											<div class="col-md-12">
												@include('admin.users.graph')
											</div>
										</div>
									</div> --}}
                                                <?php $status_directory = null; ?>
                                                @if ($user->status == 'reseller')
                                                    <?php $status_directory = 'Reseller-NIC'; ?>
                                                @elseif($user->status == 'dealer')
                                                    <?php $status_directory = 'Dealer-NIC'; ?>
                                                @elseif($user->status == 'subdealer')
                                                    <?php $status_directory = 'sub_dealerNic'; ?>
                                                @elseif($user->status == 'user')
                                                    <?php $status_directory = 'UploadedNic'; ?>
                                                @endif
                                                <?php
                                                $status_directory = null;
                                                if ($user->status == 'manager') {
                                                    $srcF = 'Manager-NIC/' . $user->username . '-cnic_front.jpg';
                                                    $srcB = 'Manager-NIC/' . $user->username . '-cnic_back.jpg';
                                                } elseif ($user->status == 'reseller') {
                                                    $srcF = 'Reseller-NIC/' . $user->username . '-cnic_front.jpg';
                                                    $srcB = 'Reseller-NIC/' . $user->username . '-cnic_back.jpg';
                                                } elseif ($user->status == 'dealer') {
                                                    $srcF = 'Dealer-NIC/' . $user->username . '-cnic_front.jpg';
                                                    $srcB = 'Dealer-NIC/' . $user->username . '-cnic_back.jpg';
                                                } elseif ($user->status == 'subdealer') {
                                                    $srcF = 'sub_dealerNic/' . $user->username . '-front.jpg';
                                                    $srcB = 'sub_dealerNic/' . $user->username . '-back.jpg';
                                                } elseif ($user->status == 'user') {
                                                    $srcF = 'UploadedNic/' . $user->username . '-front.jpg';
                                                    $srcB = 'UploadedNic/' . $user->username . '-back.jpg';
                                                }
                                                ?>
                                                <div id="cnic_tab" class="tab-pane fade">
                                                    <div class="cnic_wrapper">
                                                        <div class="cnic-box">
                                                            <?php if(file_exists(public_path().'/'.$srcF)){ ?>
                                                            <a href="{{ asset($srcF) }}" target="_blan">
                                                                <img src="{{ asset($srcF) }}" alt="CNIC Front" class="zoom_image" style="width:100%; height:100%"></a>
                                                            <?php }else{ ?>
                                                            <img src="{{ asset('images/placeholder_nic.png') }}" alt="Placeholder" style="width:100%; height:100%">
                                                            <?php } ?>
                                                            <p style="font-weight:bold; font-size: 18px;margin-top:15px;">CNIC (Front Image)</p>
                                                        </div>
                                                        <div class="cnic-box">
                                                            <?php if(file_exists(public_path().'/'.$srcB)){ ?>
                                                            <a href="{{ asset($srcB) }}" target="_blan">
                                                                <img src="{{ asset($srcB) }}" alt="CNIC Back" style="width:100%; height:100%"></a>
                                                            <?php }else{ ?>
                                                            <img src="{{ asset('images/placeholder_nic.png') }}" alt="Placeholder" style="width:100%; height:100%">
                                                            <?php } ?>
                                                            <p style="font-weight:bold; font-size: 18px;margin-top:15px;">CNIC (Back Image)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            </section>
        </section>
        <!-- CONTENT END -->
    </div>
@endsection
@section('ownjs')
    <script type="text/javascript">
        function toggle(status) {
            const str2 = status.charAt(0).toUpperCase() + status.slice(1);
            var val = $('#disableBtn').val();
            if (val == 'disable') {
                $('#disableBtn').attr('class', 'btn btn-success');
                $('#disableBtn').prop('value', 'enable');
                $('#disableBtn').html('This ' + str2 + ' is Enable');
                $('#disableBtn').addClass('enable');
                $('#disableBtn').removeClass('disable');
            } else {
                $('#disableBtn').attr('class', 'btn btn-danger');
                $('#disableBtn').prop('value', 'disable');
                $('#disableBtn').html('This ' + str2 + ' is Disable')
                $('#disableBtn').addClass('disable');
                $('#disableBtn').removeClass('enable');
            }
        }
    </script>
    <script type="text/javascript">
        function showpass(value) {
            if (value == 'hide') {
                $('#showpassword').html("{{ isset($userRedCheck->value) ? $userRedCheck->value : '' }}");
                $('#hide').attr('id', 'show');
            } else {
                $('#showpassword').html("********");
                $('#show').attr('id', 'hide');
            }
        }
    </script>
@endsection
<!-- Code Finalize -->
