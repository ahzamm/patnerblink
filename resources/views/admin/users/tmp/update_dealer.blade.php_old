@extends('admin.layouts.app')
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
						<h2>Update Dealer</h2>
					</div>
					
				</div>
			</div>
			<div class="col-lg-12">
				<section class="box ">
					
					<div class="content-body">
						<form id="general_validate"
						action="{{route('admin.user.update',['status' => 'dealer','id' => $id])}}" method="POST">
						@csrf
						<div class="row">
							<h3>Basic Info:</h3>
							<hr>
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Manager-ID</label>
									<input type="text" value="{{$dealer->manager_id}}"  name="managerid" class="form-control"  placeholder="Manager-ID" required readonly>
								</div>
								<div class="form-group">
									<label  class="form-label">Reseller-ID</label>
									<input type="text" value="{{$dealer->resellerid}}" name="resellerid" class="form-control"  placeholder="Reseller-ID" required readonly>
								</div>
								<div class="form-group">
									<label  class="form-label">Dealer ID</label>
									<input type="text" value="{{$dealer->dealerid}}" name="dealerid" class="form-control"  placeholder="Dealer-ID" required>
								</div>
								<div class="form-group">
									<label  class="form-label" >First Name</label>
									
									<input type="text" value="{{$dealer->firstname}}" name="fname" class="form-control" placeholder="first name" required>
									
								</div>
								
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Last Name</label>
									<input type="text" value="{{$dealer->lastname}}" name="lname" class="form-control"  placeholder="lastname" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Mobile Number</label>
									
									<input type="text" value="{{$dealer->mobilephone}}" name="mobile_number"  data-mask="9999 9999999" class="form-control"   required>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Landline Number</label>

									<input type="text" value="{{$dealer->homephone}}" name="land_number"  class="form-control" data-mask="(999)9999999">

								</div>
								<div class="form-group">
									<label  class="form-label">CNIC</label>
									
									<input type="text" value="{{$dealer->nic}}" name="nic" class="form-control"  data-mask="99999-9999999-9" required>
									
								</div>
								
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Email</label>
									<input type="email" value="{{$dealer->email}}" name="mail" class="form-control"  placeholder="lbi@gmail.com" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Address</label>
									<input type="text" value="{{$dealer->address}}" name="address" class="form-control"  placeholder="Address" required>
								</div>
								
								
								<div class="form-group">
									<label  class="form-label">Dealer Area</label>
									<input type="text" value="{{$dealer->area}}" name="area" class="form-control"  placeholder="Area " required>
								</div>
								
								
							</div>
							<!--  -->
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Username</label>
									<input type="text" value="{{$dealer->username}}" name="username" class="form-control"  placeholder="username" required readonly>
								</div>

								<div class="form-group">
									<label  class="form-label">Credit</label>
									<input type="text" name="limit" value="{{$userAmount->credit_limit}}" class="form-control"  placeholder="Amount" required="" >
								</div>

								
								<div class="form-group">
									<label  class="form-label">MRTG Graph </label>
									<div class="input-group control-group after-add-more">
										<input type="text" name="graph[]" class="form-control" >
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
								
								<!-- <div class="form-group">
									<label  class="form-label">Server Type</label>
									<input type="text" class="form-control" name="nas_type" readonly>
								</div> -->
								
								
							</div>

							<div class="col-md-12">
								<h3>Access:</h3>
								<hr>
								<div class="col-md-4">
									<label> Static. Ips</label>
									<div class="form-group">
										<div class="btn-group btn-group-toggle" data-toggle="buttons">

											<label class="btn btn-secondary" id="ipassign">
												<input type="radio" name="ipassign"  autocomplete="off"> Assign 
											</label>
											<label class="btn btn-secondary" id="ipremove">
												<input type="radio" name="ipremove"  autocomplete="off"> Remove
											</label>
										</div>
									</div>

								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label  class="form-label">No. Of I.P</label>
										<input type="number" id="noofip" name="noofip" class="form-control" min="1"  placeholder="0" >
									</div>

								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label  class="form-label">Gaming Ips</label>
										<input type="radio" name="ip_type"   placeholder="0" value="gaming" id="ip_type">
										&nbsp; &nbsp; &nbsp;
										<label  class="form-label"> Static Ips
										</label>
										<input type="radio" name="ip_type"   placeholder="0" value="static" >
									</div>

								</div>
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">

											<label class="switch" style="width: 46px;height: 19px;">
												<input type="checkbox" >
												<span class="slider square"></span>
											</label>
											<label class="form-label"> Show Sub-Dealer</label>
											<br>

										</div>

									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label class="switch" style="width: 46px;height: 19px;">
												<input type="checkbox" >
												<span class="slider square" ></span>
											</label>
											<label class="form-label">Enable Verification </label>

										</div>
									</div>
									<div class="col-md-4">
										
										<div class="form-group">
											<label class="switch" style="width: 46px;height: 19px;">
												<input type="checkbox" >
												<span class="slider square" ></span>
											</label>
											<label class="form-label">Never Expiry </label>

										</div>

									</div>
								</div>
							</div>
							<!--  -->
							<div class="col-md-12">
								<h3>Profile:</h3>
								<hr>
								<div class="col-md-3">
									<div class="button-group">
										<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">

											Select Profiles
											<span class="caret"></span></button>
											<ul class="dropdown-menu">



												@foreach($profileList as $profile)
												@php $profile =ucwords($profile->name); @endphp
												<li>
													<a href="#" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;">
														<input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id)"  style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif  />&nbsp;{{$profile}}</a></li>


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

															@php $npro=$profileRate->profile->name; @endphp

                                 @if($npro != 'lite' && $npro != 'social' && $npro != 'smart' && $npro != 'super' && $npro != 'turbo' && $npro != 'mega' && $npro != 'jumbo' && $npro != 'sonic' )

															<tr id="{{ucfirst($profileRate->profile->name)}}tr">
																<td scope='row'>{{ucfirst($profileRate->profile->name)}}</td>
																<td scope='row'> <input type="number" class='form-control' 
																	placeholder='0' 
																	style='border: none; text-align: center;'
																	name="{{ucfirst($profileRate->profile->name)}}" value="{{$profileRate->rate}}">
																</td>
															</tr>
															@endif
															@endforeach
														</tbody>
													</table>
												</center>
											</div>
										</div>
										<div class="col-xs-12">
											<div class="pull-right ">
												<button type="submit"  class="btn btn-success">Update</button>
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
				function mycheckfunc(val,id){
					var va="#"+id;
					if($(va).attr("title") == "uncheck"){
				//
				var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' required class='form-control' name='"+id+"' placeholder=0  min='50' style='border: none; text-align: center;''></td></tr>";
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
			});
		});
	</script>
	<!--  -->
	@endsection