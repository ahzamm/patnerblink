@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<!-- //head is not necssary  -->
<style type="text/css">
.th-color{
	color: white !important;
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
#successalert{
	display: none;
}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" style=''>
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2>Account history</h2>
					</div>
					<div class="col-lg-12">
						<section class="box ">
							<header class="panel_header">
							
							</header>
							<div class="content-body">
								<div class="row">
									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="example-1" class="table table-bordered">
												<thead>
												<tr>
													<th>Sno</th>
													<th>Username</th>
													<th>Status</th>
													<th>Receivable </th>
													<th>Payable</th>
													<th>Remaining Amount</th>
												
													
												</tr>
												</thead>
												<tbody>
													@php
												$count=1;
												
												@endphp
													@foreach($userCollection as $data)
													@php
												
												$recieve=App\model\Users\PaymentsTransactions::where(['sender' =>$data->username])->sum('amount');
												@endphp
													<tr>
														<th scope="row">{{$count++}}</th>
														<td>{{$data->username}}</td>
														<td>{{$data->status}}</td>
														<td>{{$data->debit}}</td>
														<td>{{$data->credit}}</td>
														<td>{{$data->username}}</td>
														
														
														
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</section></div>
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
		<!-- END CONTENT -->
		<!---Model Dialog --->
	</div>
	<!---Model Dialog --->
	@endsection
	@section('ownjs')
	
@endsection