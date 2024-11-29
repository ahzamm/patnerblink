@extends('admin.layouts.app')
@include('admin.layouts.bytesConvert')
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
	#sno select{
		display: none;
	}
	#username select{
		display: none;
	}
	#Full-Name select{
		display: none;
	}
	#address select{
		display: none;
	}
	#Login_Time select{
		display: none;
	}
	#Assign select{
		display: none;
	}
	#Download select{
		display: none;
	}
	#Upload select{
		display: none;
	}
	#Session_Time select{
		display: none;
	}

	#example1_paginate{
		display: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" >
			<div class="">
				<div class="col-lg-12">
					
					<div class="header_view">
						
						<h2 id="heading">Online Consumers</h2>
					</div>
					
					
					<div class="table-responsive">
						<table id="example1" class="table table-striped dt-responsive display">
							<thead class="text-primary" style="background:#225094c7;">
								<tr>
									<th class="th-color">Serial#</th>
									<th class="th-color">Username</th>
									
									<th class="th-color">Full Name</th>
									<th class="th-color">Address</th>
									<th class="th-color">Login Time</th>
									<th class="th-color">Session Time</th>
									
									<th class="th-color">Contractor</th>
									<th class="th-color">Trader</th>
									
									
									<th class="th-color">IPs Assign</th>
									<th class="th-color">Usage (Download/Upload)</th>
									
								</tr>
								<tr style="background:white !important;">
									<td id="sno"></td>
									<td id="username"></td>
									<td id="Full-Name"></td>
									<td id="address"></td>
									<td id="Login_Time"></td>
									<td id="Session_Time"></td>
									<td id=""></td>
									<td id=""></td>
									
									<td id="Assign"></td>
									<td id="Download"></td>
									
								</tr>
							</thead>
							@php
							$count=1;
							@endphp
							<tbody>
								@foreach($online as $ar)
								@php
								$userDetail = App\model\Users\UserInfo::where(['username' => $ar->username])->first();
								if($userDetail){
								$fname = $userDetail->firstname;
								$lname = $userDetail->lastname;
								$address = $userDetail->address;
								$sub_dealer_id = $userDetail->sub_dealer_id;
								$dealerid = $userDetail->dealerid;
								
							}else{
							$fname = '';
							$lname = '';
							$address = '';
							$sub_dealer_id = '';
							$dealerid = '';
						}
						$totalDownload = App\model\Users\RadAcct::where(['username' => $ar->username])->sum('acctoutputoctets');
						$totalupload = App\model\Users\RadAcct::where(['username' => $ar->username])->sum('acctinputoctets');



						$datetime1=new DateTime($ar->acctstarttime);
						$datetime2=new DateTime("now");
						$interval=$datetime1->diff($datetime2);
						$Day=$interval->format('%dD' );
						if($Day>0)
						{
							$session_time = $interval->format('%dDays : %hHrs : %iMins');
						}
						else
						{
							$session_time = $interval->format('%hHrs : %iMins : %sSecs');
						}
						@endphp
						<tr>
							<td>{{$count++}}</td>
							<td>{{$ar->username}}</td>
							<td>{{$fname}} {{$lname}}</td>
							<td>{{$address}}</td>
							<td>{{$ar->acctstarttime}}</td>
							<td>{{$session_time}}</td>
							<td>{{$dealerid}}</td>
							<td>{{$sub_dealer_id}}</td>
							<td>{{$ar->framedipaddress}}</td>
							<td>{{ByteSize($totalDownload)}}/{{ByteSize($totalupload)}}</td>

						</tr>
						@endforeach

					</tbody>
				</table>
			</div>
			<div style="float: right;">
				{{$online->links()}}
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
<!-- END CONTENT -->
</div>
@endsection
@section('ownjs')
<script type="">

	$(document).ready(function(){
   var width = window.innerWidth;
	if (width < 767) {

            // small window
            $(".page-topbar").addClass("sidebar_shift").removeClass("chat_shift");
            $(".page-sidebar").addClass("collapseit").removeClass("expandit");
            $("#main-content").addClass("sidebar_shift").removeClass("chat_shift");
            $(".page-chatapi").removeClass("showit").addClass("hideit");
            $(".chatapi-windows").removeClass("showit").addClass("hideit");
            CMPLTADMIN_SETTINGS.mainmenuCollapsed();

        } else {

            // large window
            $(".page-topbar").addClass("sidebar_shift").removeClass("chat_shift");
            $(".page-sidebar").addClass("collapseit").removeClass("expandit");
            $("#main-content").addClass("sidebar_shift").removeClass("chat_shift");

            // $(".page-topbar").removeClass("sidebar_shift chat_shift");
            // $(".page-sidebar").removeClass("collapseit chat_shift");
            // $("#main-content").removeClass("sidebar_shift chat_shift");
            CMPLTADMIN_SETTINGS.mainmenuScroll();
        }


	});
		

</script>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({

			"searching": false
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
@endsection