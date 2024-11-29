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
		background-color: #225094 !important;
	}

	.input-group-addon.primary {
		color: rgb(255, 255, 255);
		background-color: rgb(50, 118, 177);
		border-color: rgb(40, 94, 142);
	}
	.input-group-addon.success {
		color: rgb(255, 255, 255);
		background-color: rgb(92, 184, 92);
		border-color: rgb(76, 174, 76);
	}
	.input-group-addon.info {
		color: rgb(255, 255, 255);
		background-color: rgb(57, 179, 215);
		border-color: rgb(38, 154, 188);
	}
	.input-group-addon.warning {
		color: rgb(255, 255, 255);
		background-color: rgb(240, 173, 78);
		border-color: rgb(238, 162, 54);
	}
	.input-group-addon.danger {
		color: rgb(255, 255, 255);
		background-color: rgb(217, 83, 79);
		border-color: rgb(212, 63, 58);
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
						<h2>Update Trader</h2>
					</div>
					
				</div>
			</div>
			<div class="col-lg-12">
				<section class="box ">
					<header class="panel_header">
					</header>
					<div class="content-body">
						<form id="general_validate"
						action="{{route('users.user.update',['status' => 'trader','id' => $id])}}" method="POST" >
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group" style="display: none;">
									<label  class="form-label">Manager-ID</label>
									<input type="text" value="{{$trader->manager_id}}"  name="managerid" class="form-control"  placeholder="Manager-ID"  readonly/>
								</div>
								@php
								$mob = '';
								$nic = '';
										 $isverify = App\model\Users\UserVerification::where('username',$trader->username)->select('mobile_status','cnic')->get();
										 foreach ($isverify as $value) {
											$mob = $value['mobile_status'];
											$nic = $value['cnic'];
										 }
										
		
								@endphp
								<div class="form-group" style="display: none;">
									<label  class="form-label">Reseller-ID</label>
									<input type="text" value="{{$trader->resellerid}}" name="resellerid" class="form-control"  placeholder="Reseller-ID"  readonly/>
								</div>
								<div class="form-group">
									<label  class="form-label">Dealer ID</label>
									<input type="text" value="{{$trader->dealerid}}" name="dealerid" class="form-control"  placeholder="Dealer-ID"  readonly/>
								</div>
								<div class="form-group">
									<label  class="form-label">Sub-Dealer-ID</label>
									<input type="text" value="{{$trader->sub_dealer_id}}" name="sub_dealer_id" class="form-control"  placeholder="Dealer-ID"  readonly/>
								</div>
										<div class="form-group">
									<label  class="form-label">Trader-ID</label>
									<input type="text" value="{{$trader->trader_id}}" name="sub_dealer_id" class="form-control"  placeholder="Dealer-ID"  readonly/>
								</div>
								<div class="form-group">
										<label  class="form-label">Email</label>
										<input type="email" value="{{$trader->email}}" name="mail" class="form-control"  placeholder="lbi@gmail.com" required>
									</div>
									
							</div>
							<div class="col-md-3">
								<div class="form-group">
								<label for="validate-phone">Mobile Number</label>
								<div class="input-group" data-validate="phone">
									<input type="hidden" name="" id="variCode" value="{{$mob}}">
									<input type="text" class="form-control" name="mobile_number" id="validate-phone"  data-mask="9999 9999999" value="{{$trader->mobilephone}}" required readonly>
									<span class="input-group-addon danger"><a href="#" data-toggle="modal" style="color: white">Varify <span class="glyphicon glyphicon-remove"></span></a></span>
								</div>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Landline# | Mobile#2</label>
									
									<input type="text" value="{{$trader->homephone}}" name="land_number"  class="form-control" >
									
								</div>
								<div class="form-group">
								<label for="validate-nic">CNIC</label>
								<div class="input-group" data-validate="nic">
										<input type="hidden" name="" id="nicvariCode" value="{{$nic}}">
									<input type="text" class="form-control" name="nic" value="{{$trader->nic}}" data-mask="99999-9999999-9"  required readonly>
									<span class="input-group-addon danger"><a href="#" data-toggle="modal" style="color: white">Varify <span class="glyphicon glyphicon-remove"></span></a></span>
								</div>
									
								</div>
							
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label" >First Name</label>
									
									<input type="text" value="{{$trader->firstname}}" name="fname" class="form-control" placeholder="first name" required>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Last Name</label>
									<input type="text" value="{{$trader->lastname}}" name="lname" class="form-control"  placeholder="lastname" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Username</label>
									<input type="text" value="{{$trader->username}}" name="username" class="form-control"  placeholder="username" required readonly>
								</div>

								<div class="form-group" style="display: none;">
									<label  class="form-label">MRTG Graph </label>
									<div class="input-group control-group after-add-more">
										<!-- <input type="text" name="addmore[]" value="{{ $graph1->graph_no}}" class="form-control" > -->
										<input type="text" name="graph[]" value="{{ $graph1->graph_no}}" class="form-control" >
										<div class="input-group-btn">
											<button class="btn btn-success add-more" type="button"><i class="glyphicon glyphicon-plus"></i></button>
										</div>
									</div>
									<div class="copy hide">
										<div class="control-group input-group" style="margin-top:10px">
											<input type="text" name="graph[]" class="form-control" >
											<div class="input-group-btn">
												<button class="btn btn-danger remove" type="button"><i class="glyphicon glyphicon-remove"></i></button>
											</div>
										</div>
									</div>
								</div>
								
								
								
							</div>
							<!--  -->
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Address</label>
									<input type="text" value="{{$trader->address}}" name="address" class="form-control"  placeholder="Address" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Sub-Dealer Area</label>
									<input type="text" value="{{$trader->area}}" name="area" class="form-control"  placeholder="Area " required>
								</div>
								<div class="form-group">
									<label  class="form-label">Show Balance</label><br>
                  @php
                     $isVisible = App\model\Users\UserAmount::where('username',$trader->username)->first();
                     $check = $isVisible['isvisible'];
                  @endphp
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

								@php
											$radioCheck1 = '';
											$allowplan = '';
										
										
										$data = App\model\Users\DealerProfileRate::where('dealerid',$trader->dealerid)->select('allowplan')->first();
										$allowplan = $data['allowplan'];
											@endphp
											
							</div>
							<!--  -->
								<div class="col-md-12">
									<div class="col-md-3">
											<div class="button-group form-group">
									<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">

										Select Profiles
										<span class="caret"></span></button>
										<ul class="dropdown-menu">
											@foreach($profileList as $profile_data)
											@php $profile =ucwords($profile_data->profile->name);
											

															$sdealerrate=App\model\Users\DealerProfileRate::where(['dealerid' => $trader->dealerid])->where(['groupname' => $profile_data->groupname])->first();
															$gtax = "E";
															if($gtax == "A"){
															$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges = $profile1['final_rates'];
														}else if($gtax == "B"){
														$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges = $profile1['final_ratesB'];
														}else if($gtax == "C"){
														$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges = $profile1['final_ratesC'];
													}else if($gtax == "D"){
														$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges = $profile1['final_ratesD'];
													}else if($gtax == "E"){
														$profile1=App\model\Users\Profile::where(['groupname' => $profile_data->groupname])->first();
															$charges = $profile1['final_ratesE'];
													}
														
											$rate=$profile_data->rate+1;
											$max=$sdealerrate->max;
										


											@endphp
											<li><a href="#" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;"><input type="checkbox" class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id,'{{$rate}}','{{$max}}')" style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif/>&nbsp;{{$profile}}</a></li>
											@endforeach
										</ul>
									</div>
									</div>
									<div class="col-md-5 " style="height: 155px; overflow: auto;" >
										<center>
											<table class="table table-responsive table-bordered" >
												<thead class="thead table-striped">
													<tr>
														<th scope="col" class="th-color">Profile Name</th>
														<th scope="col" class="th-color"> Profile Rate</th>
													</tr>
												</thead>
												<tbody class="tbody">
													@foreach($assignedProfileRates as $profileRate)
													<tr id="{{ucfirst($profileRate->name)}}tr">
														<td scope='row'>{{ucfirst($profileRate->name)}}
															@php
															$dealerrate=App\model\Users\DealerProfileRate::where(['dealerid' => $trader->dealerid])->where(['groupname' => $profileRate->groupname])->first();
															$sdealerrate=App\model\Users\SubdealerProfileRate::where(['sub_dealer_id' => $trader->sub_dealer_id])->where(['groupname' => $profileRate->groupname])->first();
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
											$drate=$sdealerrate->rate+1;
											$dmax=$dealerrate->max;
										
															@endphp
														</td>
														<td scope='row'> <input type="number" class='form-control' 
															placeholder='0' 
															style='border: none; text-align: center;'
															name="{{ucfirst($profileRate->name)}}" value="{{$profileRate->rate}}" max="{{$dmax}}" min="{{$drate}}" required="">
														</td>
													</tr>
													@endforeach
													
												</tbody>
											</table>
										</center>
									</div>
								</div>
								<div class="col-xs-12">
									<div class="pull-right ">
										<button type="submit" class="btn btn-success">Update</button>
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
		function mycheckfunc(val,id,rate,max){
			var va="#"+id;
			if($(va).attr("title") == "uncheck"){
				//
				var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='"+id+"' placeholder='0' required min='"+rate+"' max='"+max+"' style='border: none; text-align: center;''></td></tr>";
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
	$(document).ready(function() {
		$('.input-group input[required], .input-group textarea[required], .input-group select[required]').on('keyup change', function() {
			var $form = $(this).closest('form'),
			$group = $(this).closest('.input-group'),
			$addon = $group.find('.input-group-addon'),
			$icon = $addon.find('span'),
			state = false;

			//$mobNum = document.getElementById('validate-phone').value;
			$code = document.getElementById('variCode').value;
			$codenic = document.getElementById('nicvariCode').value;

			if($group.data('validate') == 'phone' && $code != 0) {
					state = true;
				}
			
				if($group.data('validate') == 'nic' && $codenic != '') {
					state = true;
				}
				if (state) {
					$addon.removeClass('danger');
					$addon.addClass('success');
					$addon.html('Varified <span class="glyphicon glyphicon-ok"></span>');
					
				}
			});
		$('.input-group input[required], .input-group textarea[required], .input-group select[required]').trigger('change');
		
		
	});
</script>
	<!--  -->
	@endsection