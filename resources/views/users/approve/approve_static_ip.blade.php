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
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
	.slider:before {
		position: absolute;
		content: "";
		height: 11px !important;
		width: 13px !important;
		left: 3px !important;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}
	#successalert{
		display: none;
	}
	#ip select{
		display: none;
	}
	#sno select{
		display: none;
	}
	#username select{
		display: none;
	}
	#nic select{
		display: none;
	} 
	#res select{
		display: none;
	} 
	#app select{
		display: none;
		}#mob select{
			display: none;
		}
	</style>
	@endsection
	@section('content')
	<div class="page-container row-fluid container-fluid">
		<section id="main-content">
			<section class="wrapper main-wrapper">
				<div class="">
					<div class="">
						<div class="header_view">
							<h2>Approve Static IPs
								<span class="info-mark" onmouseenter="popup_function(this, 'static_ip_approval');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
							</h2>
						</div>
						<div>
							<section class="box">
								<div class="content-body">
									<div class="">
										<div class="">
											<div class="">
												<table id="example1" class="table table-bordered dt-responsive display w-100">
													<thead>
														<tr>
															<th>Serial#</th>
															<th>Consumer (ID)</th>
															<th>CNIC Number</th>
															<th>Mobile Number</th>
															<th>Assign IPs</th>
															<th>IPs Category</th>
															<th>Reseller (ID)</th>
															<th>Contractor (ID)</th>
															<th>Apporved <span style="color: red">*</span></th>
														</tr>
														<tr style="background:white !important;" id="filter_row">
															<td id="sno"></td>
															<td id="username"></td>
															<td id="nic"></td>
															<td id="mob"></td>
															<td id="ip"></td>
															<td ></td>
															<td id="res"></td>
															<td ></td>
															<td id="app"></td>
														</tr>
													</thead>
													<tbody>
														@php $num=0;@endphp
														@foreach($userCollection as $data)
														@php
														$user = App\model\Users\UserInfo::where(['username' =>  $data->userid])->first();
														$num++;
														@endphp
														<tr>
															<td>{{$num}}</td>
															<td class="td__profileName">{{$data->userid}}</td>
															@if(!empty($user))
															<td>{{$user->nic}}</td>
															<td>{{$user->mobilephone}}</td>
															@else
															<td>N/A</td>
															<td>N/A</td>
															@endif
															<td class="td__profileName">{{$data->ipaddress}}</td>
															<td>{{$data->type}}</td>
															<td>{{$data->resellerid}}</td>
															<td>{{$data->dealerid}}</td>
															<td>
																<label class="switch" style="width: 46px;height: 19px;">
																	<input type="checkbox" name="chk" onchange="statChange(this, '{{$data->userid}}')" {{(App\model\Users\UserIPStatus::where(['username' => $data->userid])->first()['type'] == 'usual_ip') ? '' : 'checked'}} >
																	<span class="slider square" ></span>
																</label>
															</td>
														</tr>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</section>
						</div>
					</div>
				</div>
			</section>
		</section>
	</div>
	@endsection
	@section('ownjs')
	<script type="text/javascript">
		$(document).ready(function() {
			var table = $('#example1').DataTable({
				orderCellsTop: true
			});
			$("#example1 thead td").each( function ( i ) {
				var select = $('<select class="form-control"><option value="">Show All</option></select>')
				.appendTo( $(this).empty() )
				.on( 'change', function () {
					table.column( i )
					.search( $(this).val() )
					.draw();
				} );
				table.column( i ).data().unique().sort().each( function ( d, j ) {
					select.append( '<option value="'+d+'">'+d+'</option>' )
				} );
			} );
		} );
	</script>
	<script>
		function statChange(checkBox, userid){
			let isCheck = checkBox.checked;
			console.log("isCheck: " + isCheck);
			$.post(
				"{{route('users.approvestatic.post')}}",
				{
					"isChecked" : ""+isCheck+"",
					"userid" : userid
				},
				function(data){
					console.log(data);
				});
		}
	</script>
	@endsection
<!-- Code Finalize -->