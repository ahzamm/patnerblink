@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
.th-color{
color: white !important;
background-color: #225094;
/*font-size: 15px !important;*/
}
.header_view{
margin: auto;
height: 40px;
padding: auto;
text-align: center;
font-family:Arial,Helvetica,sans-serif;
}
h2{
color: #225094 !important;
}
.dataTables_filter{
margin-left: 60%;
}
tr,th,td{
text-align: center;
}
select{
color: black;
}
.slider:before {
position: absolute;
content: "";
height: 11px !important;
width: 13px !important;
left: 3px !important;
/*bottom: 3px !important;*/
background-color: white;
-webkit-transition: .4s;
transition: .4s;
}
.active{
background-color: #F2F3F4 !important;
color: black !important;
font-weight: bold !important;
}
textarea {
resize: none;
}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
<!-- SIDEBAR - START -->
<section id="main-content" class=" ">
<section class="wrapper main-wrapper row" style=''>
<div class="">
<div class="col-lg-12" >
<div class="header_view">
<h2>Update Contractor</h2>
</div>

</div>
</div>
<div class="col-lg-12">
<section class="box ">
<header class="panel_header">
</header>
<div class="content-body">
<form id="general_validate"	action="{{route('users.user.update',['status' => 'dealer','id' => $id])}}" method="POST">
@csrf
<div class="row">
<div class="col-md-4">
<!-- <div class="form-group">
<label  class="form-label">Manager ID</label> -->
<input type="hidden" value="{{$dealer->manager_id}}"  name="managerid" class="form-control"  placeholder="Manager-ID" required readonly>
<!-- </div> -->
<!-- <div class="form-group">
<label  class="form-label">Reseller ID</label> -->
<input type="hidden" value="{{$dealer->resellerid}}" name="resellerid" class="form-control"  placeholder="Reseller-ID" required readonly>
<!-- </div> -->
<div class="form-group">
<label  class="form-label">Contractor ID</label>
<input type="text" value="{{$dealer->dealerid}}" name="dealerid" class="form-control"  placeholder="Contractor ID" required readonly>
</div>
<div class="form-group">
<label  class="form-label">Username</label>
<input type="text" value="{{$dealer->username}}" name="username" class="form-control"  placeholder="Username" required readonly>
</div>
<div class="form-group">
<label  class="form-label" >First Name</label>

<input type="text" value="{{$dealer->firstname}}" name="fname" class="form-control" placeholder="First Name" required>

</div>
<div class="form-group">
<label  class="form-label">Last Name</label>
<input type="text" value="{{$dealer->lastname}}" name="lname" class="form-control"  placeholder="Last Name" required>
</div>
</div>
<div class="col-md-4">
<div class="form-group">
<label  class="form-label">Mobile Number</label>
<input type="text" value="{{$dealer->mobilephone}}" name="mobile_number"  data-mask="9999 9999999" class="form-control"   required>
</div>
<div class="form-group">
<label  class="form-label">Landline Number</label>
<input type="text" value="{{$dealer->homephone}}" name="land_number"  class="form-control" data-mask="(999)9999999"  >
</div>
<div class="form-group">
<label  class="form-label">CNIC Number</label>
<input type="text" value="{{$dealer->nic}}" name="nic" class="form-control"  data-mask="99999-9999999-9" required>
</div>
<div class="form-group">
<label  class="form-label">Email Address</label>
<input type="email" value="{{$dealer->email}}" name="mail" class="form-control"  placeholder="lbi@gmail.com" required>
</div>
</div>
<div class="col-md-4">

<div class="form-group">
<label  class="form-label">Assigned Contractor Area</label>
<input type="text" value="{{$dealer->area}}" name="area" class="form-control"  placeholder="Assigned Contractor Area " required>
</div>
<div class="form-group">
<label  class="form-label">Business Address</label>
<textarea  value="{{$dealer->address}}" name="address" class="form-control"  placeholder="Business Address" required>{{$dealer->address}} </textarea>
</div>

</div>
<!--  -->

<!--  -->
@php
$check = '';
$verify = '';
$payment = '';
$trader = '';
$discount = '';
$data = App\model\Users\DealerProfileRate::where('dealerid',$dealer->dealerid)->select('show_sub_dealer','trader','allowplan','verify','changeprofile','payment_type')->first();
$trader = @$data['trader'];
$verify = @$data['verify'];
$payment = @$data['payment_type'];
$isSetDealerNeverExpire = App\model\Users\UserInfo::where('dealerid',$dealer->dealerid)->where('status','dealer')->select('never_expire')->first();
$check = App\model\Users\UserAmount::where('username',$dealer->username)->first();
$check = $check->isvisible;
@endphp
<div class="col-md-12">
<!-- <h3>Controls:</h3> -->
<hr>
<div class="header_view">
<h2 style="font-size: 26px;">Management</h2>
</div>
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="">
	<div class="col-lg-12">
		<div class="header_view">
			<!-- <h2>User Detail</h2> -->
		</div>
		<div class="modal-body" style="margin-top: -60px;">
			<div class="row">
				<div class="">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" id="DDU" href="#menu5">Internet Profiles</a></li>
						<li><a data-toggle="tab" href="#home">Static IPs</a></li>
						<li><a data-toggle="tab" id="DDU" href="#menu7">Allow Access</a></li>
						@if(Auth::user()->username == 'logonbroadband' || Auth::user()->username == 'account01')
						<li><a data-toggle="tab" id="DDU" href="#dhcp"> DHCP Server</a></li>
						<li><a data-toggle="tab" id="DDU" href="#creditlimit">Credit Limit</a></li>
						<li><a data-toggle="tab" id="DDU" href="#billingtype">Billing Method</a></li>
						<li><a data-toggle="tab" id="DDU" href="#taxsection">Taxation Amount</a></li>
						<li><a data-toggle="tab" id="DDU" href="#mrtg">MRTG Graph</a></li>
						<li><a data-toggle="tab" id="DDU" href="#nas">Assign NAS</a></li>
						@endif
					</ul>

					<div class="tab-content">
						<div id="home" class="tab-pane fade ">

							<!-- <hr> -->
							<div style="overflow-x: auto;">
								<div class="col-lg-4">

									<label > Static. Ips</label>
									<div class="form-group">
										<div class="btn-group btn-group-toggle" data-toggle="buttons" id="ipassign">
											<label class="btn btn-secondary" >
												<input type="radio" name="ipassign" value="assign"  autocomplete="off"> Assign
											</label>
											<label class="btn btn-secondary" id="ipremove">
												<input type="radio" name="ipassign" value="remove"  autocomplete="off"> Remove
											</label>
										</div>
									</div>
								</div>
								<div class="col-lg-4">
									<label >Type Of IPs</label>
									<div class="form-group">
										<div class="btn-group btn-group-toggle" data-toggle="buttons">
											<label  class="btn btn-secondary">Gaming IP
												<input type="radio" name="ip_type"   placeholder="0" value="gaming" id="ip_type"/>
											</label>
											<label  class="btn btn-secondary"> Static IP
												<input type="radio" name="ip_type"   placeholder="0" value="static" />
											</label>
										</div>
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label >Number Of IPs</label>
										<input type="number" id="noofip" name="noofip" class="form-control" min="1"  placeholder="0" />
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label >Assigned IP Rates (Rs.)</label>
										<input type="text" value="{{$ip_amount}}" name="rates" id="iprates" min="0" class="form-control"  placeholder="IpRates " required>
									</div>
								</div>
							</div>
						</div>

						<div id="creditlimit" class="tab-pane fade">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group" style="padding: 10px;">
										<label  class="form-label">Credit Limit (Rs.)</label>
										<input type="text" value="{{number_format($userAmount->credit_limit)}}" name="limit" class="form-control"  placeholder="Amount" required>
									</div>
								</div>
							</div>
						</div>
						<!-- MRTG Graph -->
						<div id="mrtg" class="tab-pane fade">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group" style="padding: 10px;">
										<label  class="form-label">Assigned MRTG Graph </label>
										<div class="input-group control-group after-add-more">
											<input type="text" name="graph[]" value="{{ @$graph1->graph_no}}" class="form-control" >
											<div class="input-group-btn">
												<button class="btn btn-success add-more"  type="button"><i class="glyphicon glyphicon-plus"></i></button>
											</div>
										</div>
										<div class="copy hide">
											<div class="control-group input-group" style="margin-top:10px">
												<input type="text" name="graph[]" class="form-control" >
												<div class="input-group-btn">
													<button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i></button>
												</div>
											</div>
										</div>																			</div>
									</div>
								</div>
							</div>
							<!-- assign NAS -->
							<div id="nas" class="tab-pane fade">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group" style="padding: 10px;">
											<label  class="form-label">Assigned NAS</label>
											<select name="nas" id="nas" class="form-control" required>
												<option value="">Select NAS </option>
												<?php foreach($nas as $nasvalue){ ?>
													<option value="<?= $nasvalue->nas;?>" <?= (@$assignedNas[0]->nas == $nasvalue->nas) ? 'selected' : ''; ?>  ><?= $nasvalue->nas;?></option>
												<?php } ?>
											</select>																			</div>
										</div>
									</div>
								</div>
								<div id="menu5" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-12">
											<!-- <h3>Profile:</h3> -->
											<!-- <hr> -->
											<div class="col-md-2"style="padding-right: 0">
												<div class="button-group">
													<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" style="width: 100%;
													margin-top: 7px;
													font-size: 15px;
													font-weight: bold;
													color: #fff;
													background: #225094;
													padding: 9.5px;">
													<!-- <span class="glyphicon glyphicon-cog"></span> -->
													Select Profiles
													<span class="caret"></span></button>
													<ul class="dropdown-menu">
														@foreach($profileList as $profile_data)
														@php
														$profile_remove=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
														$charges1 = $profile_remove['final_ratesE'];
														$charges12 = $charges1-50;




														$profile =ucwords($profile_data->name);
														$pro_rate=$profile_data->rate +1;
														$userName = Auth::user()->username;
														@endphp
														@if($profile != 'Lite' && $profile != 'Social' && $profile != 'Smart' && $profile != 'Super' && $profile != 'Turbo' && $profile != 'Mega' && $profile != 'Jumbo' && $profile != 'Sonic' )
														<li>
															<a href="#" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;">
																<input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id,'{{$pro_rate}}','{{$charges12}}','{{$userName}}')"  style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif/>&nbsp;{{$profile}}</a></li>
																@endif
																@endforeach
															</ul>
														</div>
													</div>
													{{-- <div class="col-md-10 " style="height: 155px; overflow: auto;" > --}}
														<div class="col-md-10 " style="overflow: auto; padding-left: 0" >
															<center>
																<table class="table table-responsive table-bordered" >
																	<thead class="thead table-striped">
																		<tr>
																			<th scope="col" class="th-color">Profile Name</th>
																			<th scope="col" class="th-color">Internet Profile Rate (Rs.)</th>
																			@if(Auth::user()->username == "sarbaaz")
																			<th style="display: none;" scope="col" class="th-color">Commission (Rs.)</th>
																			<th scope="col" class="th-color">Maximum Amount (Rs.)</th>
																			@else
																			<th scope="col" class="th-color">Commission (Rs.)</th>
																			<th scope="col" class="th-color">Maximum Amount (Rs.)</th>
																			@endif
																		</tr>
																	</thead>
																	<tbody class="tbody">
																		@foreach($assignedProfileRates as $profileRate)
																		@php
																		$dealerrate=App\model\Users\ResellerProfileRate::where(['resellerid' => $dealer->resellerid])->where(['groupname' => $profileRate->groupname])->first();
																		///
																		$sdealerrate=App\model\Users\DealerProfileRate::where(['dealerid' => $dealer->dealerid])->where(['groupname' => $profileRate->groupname])->first();
																		$gtax = $sdealerrate->taxgroup;
																		if($gtax == "A"){
																			$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																			$charges = $profile['final_rates'];
																		}else if($gtax == "B"){
																			$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																			$charges = $profile['final_ratesB'];
																		}else if($gtax == "C"){
																			$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																			$charges = $profile['final_ratesC'];
																		}else if($gtax == "D"){
																			$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																			$charges = $profile['final_ratesD'];
																		}else if($gtax == "E"){
																			$profile=App\model\Users\Profile::where(['groupname' => $profileRate->groupname])->first();
																			$charges1 = $profile['final_ratesE'];
																			$charges = $charges1-1;

																		}
																		///
																		$drate1=$dealerrate->rate;
																		$drate = $drate1+1;
																		@endphp
																		<tr id="{{ucfirst($profileRate->name)}}tr">
																			<td scope='row' style="background-color: #649beb">{{ucfirst($profileRate->name)}}</td>
																			<td scope='row'> <input type="number" class='form-control'
																				placeholder='0'
																				style='border: none; text-align: center;'
																				name="{{ucfirst($profileRate->name)}}" value="{{$profileRate->rate}}"  min="0" required="" step="0.01">
																			</td>
																			@if(Auth::user()->username == "sarbaaz")
																			<td style="display: none;" scope='row'> <input type="number" class='form-control'
																				placeholder='0'
																				style='border: none; text-align: center;'
																				name="com{{ucfirst($profileRate->name)}}" value="0" step="0.01">
																			</td>
																			<td scope='row'> <input type="number" class='form-control'
																				placeholder='0'
																				style='border: none; text-align: center;'
																				name="max{{ucfirst($profileRate->name)}}" value="{{$profileRate->max}}"  step="0.01">
																			</td>
																			@else
																			<td  scope='row'> <input type="number" class='form-control'
																				placeholder='0'
																				style='border: none; text-align: center;'
																				name="com{{ucfirst($profileRate->name)}}" value="{{$profileRate->commision}}" step="0.01">
																			</td>
																			<td scope='row'> <input type="number" class='form-control'
																				placeholder='0'
																				style='border: none; text-align: center;'
																				name="max{{ucfirst($profileRate->name)}}" value="{{$profileRate->max}}"  step="0.01">
																			</td>
																			@endif
																		</tr>
																		@endforeach
																	</tbody>
																</table>
																@foreach($assignedProfileRates as $profileRate)
																<input type="hidden" name="profileNames[]" id="" value="{{$profileRate->name}}">
																<input style="display: none" type="number" class='form-control'
																placeholder='0'
																style='border: none; text-align: center;'
																name="dastiAmount[]" value="{{$profileRate['dasti_amount']}}" max="" min="0" step="0.01">
																@endforeach
															</center>
														</div>


													</div>
												</div>
											</div>


											<div id="menu7" class="tab-pane fade">
												<div class="row" style="padding: 20px; text-align: center">
													<div class="col-lg-2 col-sm-12 col-xs-12">
														<h5 style="color: #000"> Verification (On & Off)   </h5>
														<div class="form-group">

															@if($verify == 'yes')
															<label class="switch" style="width: 46px;height: 19px;">
																<input type="checkbox" checked name="Verification" id="Verification">

																<span class="slider square" ></span>
																<span id="verifystate"></span>
															</label>

															@elseif($verify == 'no')
															<label class="switch" style="width: 46px;height: 19px;">
																<input type="checkbox"  name="Verification" id="Verification">
																<span class="slider square" ></span>
																<span id="verifystate"></span>
															</label>
															@else
															<label class="switch" style="width: 46px;height: 19px;">
																<input type="checkbox" name="Verification" id="Verification">
																<span class="slider square" ></span>
																<span id="verifystate"></span>
															</label>
															@endif
														</div>
													</div>
													<div class="col-lg-2 col-sm-12 col-xs-12">
														<h5 style="color: #000">Available Balance (On & Off)</h5>
														<div class="">
															<div class="form-group">
																<p id="radiostate"></p>
																@if($check == 'yes')
																<label class="switch" style="width: 46px;height: 19px;" >
																	<input type="checkbox" checked name="isvisible" id="isvisible" >
																	<span class="slider square" ></span>
																</label>
																@elseif($check == 'no')
																<label class="switch" style="width: 46px;height: 19px;" >
																	<input type="checkbox"  name="isvisible" id="isvisible" >
																	<span class="slider square" ></span>
																</label>
																@else
																<label class="switch" style="width: 46px;height: 19px;" >
																	<input type="checkbox" name="isvisible" id="isvisible" >
																	<span class="slider square" ></span>
																</label>
																@endif
															</div>
														</div>


													</div>

													<div class="col-lg-2 col-sm-12 col-xs-12">
														<h5 style="color: #000">Trader (On & Off)</5>
															<div class="">


																<div class="form-group">
																	<p id="radiostate"></p>
																	@if($trader == 'yes')
																	<label class="switch" style="width: 46px;height: 19px;" >
																		<input type="checkbox" checked name="traderAllow" id="traderAllow" >
																		<span class="slider square" ></span>
																	</label>
																	@elseif($trader == 'no')
																	<label class="switch" style="width: 46px;height: 19px;" >
																		<input type="checkbox"  name="traderAllow" id="traderAllow" >
																		<span class="slider square" ></span>
																	</label>
																	@else
																	<label class="switch" style="width: 46px;height: 19px;" >
																		<input type="checkbox" name="traderAllow" id="traderAllow" >
																		<span class="slider square" ></span>
																	</label>
																	@endif
																</div>
															</div>


														</div>
														<div class="col-lg-2 col-sm-12 col-xs-12">
															<h5 style="color: #000">Payments (On & Off)</h5>
															<div class="form-group">

																@if($payment == 'cash')
																<label class="switch" style="width: 46px;height: 19px;">
																	<input type="checkbox" checked name="payment_type" id="payment_type">

																	<span class="slider square" ></span>
																	<span id="paymentstate"></span>
																</label>

																@elseif($payment == 'credit')
																<label class="switch" style="width: 46px;height: 19px;">
																	<input type="checkbox"  name="payment_type" id="payment_type">
																	<span class="slider square" ></span>
																	<span id="paymentstate"></span>
																</label>
																@else
																<label class="switch" style="width: 46px;height: 19px;">
																	<input type="checkbox" name="payment_type" id="payment_type">
																	<span class="slider square" ></span>
																	<span id="paymentstate"></span>
																</label>
																@endif
															</div>
														</div>
														<div class="col-lg-2 col-sm-12 col-xs-12">
															<h5 style="color: #000"> Never Expire (On & Off)</h4>
																<div class="form-group">
																	<label class="switch" style="width: 46px;height: 19px;">
																		<input type="checkbox" {{$isSetDealerNeverExpire['never_expire'] == 'yes' ? 'checked' : ''}} name="neverexpire" id="neverexpire">
																		<span class="slider square" ></span>
																	</label>
																</div>
															</div>
														</div>
													</div>


													<div class="tab-pane" id="dhcp">
														<div class="row">
															<div class="col-md-3"></div>
															<div class="col-md-6" style="padding: 15px;">
																<label for="" class="text-center">Select DHCP server</label>
																<select name="dhcp_serverip" id="" class="form-control">
																	<option value="">Select Server</option>
																	<option value="0 none">None</option>
																	@foreach ($dhcp_server as $item)
																	<option value="{{$item['id'] }} {{$item['name']}}">{{$item['name']}}</option>
																	@endforeach
																</select>
															</div>
														</div>
													</div>
													<div class="tab-pane" id="billingtype">
														@php
														//$check = App\model\Users\DealerProfileRate::where('dealerid',$dealer->dealerid)->select('billing_type')->first();
														$check = App\model\Users\DealerProfileRate::where('dealerid',$dealer->dealerid)->select('billing_type','sstpercentage','advpercentage','discount')->first();

														if(!empty($check)){
															$billing_type = $check->billing_type;
															$sstfromdb = $check->sstpercentage;
															$advfromdb = $check->advpercentage;
															$discount = @$check->discount;
														}else{
															$billing_type = 'amount';
															$sstfromdb = '0.195';
															$advfromdb = '0.125';
															$discount = 2;
														}
														@endphp
														<div class="row">
															<div class="col-lg-12" style="padding: 20px;">
																<h3>Billing Method</h3>
																<div class="custom-control custom-radio custom-control-inline">
																	<input type="radio" class="custom-control-input" id="defaultInline1" value="amount" name="billingtype" {{$billing_type == 'amount'? 'checked' : ''}} style="height:20px; width:30px; vertical-align: middle;">
																	<label class="custom-control-label" for="defaultInline1" style="margin-top: 10px;">Prepaid Amount Billing</label>
																	<input type="radio" class="custom-control-input" id="defaultInline2" value="card" name="billingtype" {{$billing_type == 'card'? 'checked' : ''}} style="height:20px; width:30px; vertical-align: middle;">
																	<label class="custom-control-label" for="defaultInline2" style="margin-top: 10px;">Card Billing</label>
																</div>
															</div>
														</div>
													</div>
													<div class="tab-pane" id="taxsection">
														<div class="row">
															<div class="col-lg-12" style="padding: 20px;">
																<h3>Tax Section</h3>
																<label for="">Sindh Sales Tax (Rs.)</label>
																<input type="text" value="{{$sstfromdb}}" name="sstField" id="sstField">

																<label for="" style="margin-left: 50px">Advance Income Tax (Rs.)</label>
																<input type="text" value="{{$advfromdb}}" name="advField" id="advField">
																<label for="" style="margin-left: 50px">Discount (Rs.)</label>
																<input type="text" value="{{$discount}}" name="discount" id="discount">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="pull-right ">
									<button type="submit" class="btn btn-success">Update</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-12">
						<div class="pull-right ">
						</div>
					</div>
				</div>
			</form>
		</div>
	</section></div>
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
<!-- END CONTENT -->
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
var num=0;
$(document).ready(function() {
$(".add-more").click(function(){
	if(num < 1){
		var html = $(".copy").html();
		$(".after-add-more").after(html);
		num++;
	}
});
$("body").on("click",".remove",function(){
	$(this).parents(".control-group").remove();
	num--;
});
});
</script>
<script type="text/javascript">
function mycheckfunc(val,id,rate,charges12,userName,commision,max){
var va="#"+id;
var name =userName;
if($(va).attr("title") == "uncheck"){
	if(name == "sarbaaz"){

		var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='"+id+"' placeholder=0 required min='0' max='"+charges12+"' style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='max"+id+"' placeholder=0 required min='"+max+"' step='0.01' style='border: none; text-align: center;''></td></tr>";
	}else{
		var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='"+id+"' placeholder=0 required min='0' step='0.01'  style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='com"+id+"' step='0.01' placeholder=0 required min='0' style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='max"+id+"' placeholder=0 required min='0' step='0.01' style='border: none; text-align: center;''></td></tr>";
	}
	$(".tbody").append(markup);
//
	$(va).attr('title', 'check');
// 
} else if($(va).attr("title") == "check"){
//
	var trvar=va+"tr";
	$(trvar).remove();
//
	$(va).attr('title', 'uncheck');
//
}
}
</script>
<script>
$(document).ready(function(){
$("#ipassign").click(function(){
	$("#noofip").prop('required',true);
	$("#ip_type").prop('required',true);
	iprate = $('#iprates').val();
	if(iprate == 0){
		document.getElementById("iprates").value = '';
	}
});
});
</script>
<script>
$(document).ready(function(){
// var d = document.getElementById('subdealerAllow');


// if(d.checked == true ){
// 	$("#radiostate").text('Active');
// }else{
// 	$("#radiostate").text('Deactive');
// }

});
</script>
<script>
$(document).ready(function(){
// var plan = document.getElementById('allowPlan');

// if(plan.checked == true ){
// 	$("#allowPlanT").text('Active');
// }else{
// 	$("#allowPlanT").text('Deactive');
// }

});
</script>
<script>
$(document).ready(function(){
// var ap = document.getElementById('changeprofile');

// if( ap.checked == true){
// 	$("#allowPlanstate").text('Active');
// }else{
// 	$("#allowPlanstate").text('Deactive');
// }

});
</script>
<script>
$(document).ready(function(){
// var ap = document.getElementById('allowPlan');

// if( ap.checked == true){
// 	$("#allowPlanstate").text('Active');
// }else{
// 	$("#allowPlanstate").text('Deactive');
// }

});
</script>
<script>
$(document).ready(function(){
// var payment = document.getElementById('payment_type');

// if(payment.checked == true ){
// 	$("#paymentstate").text('Active');
// }else{
// 	$("#paymentstate").text('Deactive');
// }

});
</script>
{{-- <script>
$(document).ready(function(){
var a = document.getElementById('r1');
var b = document.getElementById('r2');
var c = document.getElementById('r3');
var d = document.getElementById('r4');
var e = document.getElementById('r5');
if(a.checked == true){
	$('#v1').show();
	$('#v2').hide();
	$('#v3').hide();
	$('#v4').hide();
	$('#v5').hide();
}
if(b.checked == true){
	$('#v1').hide();
	$('#v2').show();
	$('#v3').hide();
	$('#v4').hide();
	$('#v5').hide();
}
if(c.checked == true){
	$('#v1').hide();
	$('#v2').hide();
	$('#v3').show();
	$('#v4').hide();
	$('#v5').hide();
}
if(d.checked == true){
	$('#v1').hide();
	$('#v2').hide();
	$('#v3').hide();
	$('#v4').show();
	$('#v5').hide();
}
if(e.checked == true){
	$('#v1').hide();
	$('#v2').hide();
	$('#v3').hide();
	$('#v4').hide();
	$('#v5').show();
}
});
</script> --}}


<!--  -->
@endsection