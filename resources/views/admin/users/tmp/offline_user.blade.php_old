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
	#getip select{display: none;}
	div.popover-body{
	background-color: black !important;
    color: white !important;
    font-weight: bold !important;
    font-size: 13px !important;
}
</style>
@endsection
@include('admin.Modals.offlineDetail')
@section('content')
<div class="page-container row-fluid container-fluid">
	<!-- SIDEBAR - START -->
	<section id="main-content" class=" ">
		<section class="wrapper main-wrapper row" >
			<div class="">
				<div class="col-lg-12">
					<div class="header_view">
						<h2 id="heading">Offline User</h2>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered data-table">
							<thead class="text-primary" style="background:#225094c7;">
								<tr>
									<th class="th-color">Sno</th>
									<th class="th-color">Username</th>
									<th class="th-color">Login Time</th>
									<th class="th-color">Session Time</th>
									<th class="th-color">IP Assign</th>
									<th class="th-color">Download/Upload</th>
									<th class="th-color">Action</th>
								
								</tr>
							</thead>
							<tbody>
						
							</tbody>
						</table>
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
{{-- <script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable();
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
</script> --}}
<script type="text/javascript">
	$(function () {
	  var table = $('.data-table').DataTable({
		  processing: true,
		  serverSide: true,
		  "lengthMenu": [[10, 25, 50, 100,-1], [10, 25, 50, 100,'All']],
		  ajax: "{{ route('admin.user.offlinePost') }}",
		  columns: [
			  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
			  {data: 'username', name: 'username'},
			  {data: 'acctstarttime', name: 'acctstarttime'},
			  {data: 'sessionTime', name: 'sessionTime'},
			  {data: 'framedipaddress', name: 'framedipaddress'},
			  {data: 'dwUP', name: 'dwUP'},
			  {data: 'action', name: 'action'},
		  ]
	  });
  
	});
  </script>
<script type="text/javascript">
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
            CMPLTADMIN_SETTINGS.mainmenuScroll();
        }
	});
</script>
	  <script>
		function onlineUserDetail(mac,username){
		$.ajax({
		url: "{{route('admin.offlineUserDetails')}}",
		type: "POST",
		data: {mac:mac,username:username},
		dataType: "json",
		beforeSend: function(){
			$("#load").show();
			$("#myModal").modal('show');
			$("#tblData").hide();
		},
		success: function(data){
			subdealerid = data.details.sub_dealer_id ? data.details.sub_dealer_id : 'My User';
			$("#dhcpIP").html(data.result);
			$("#username").html(data.details.username);
			$("#subdealerid").html(subdealerid);
			$("#address").html(data.details.address);
			$("#fullname").html(data.details.lastname +' '+ data.details.firstname);
			$("#dealerid").html(data.details.dealerid);
	  }
	,complete: function(){
		$("#load").hide();
		$("#tblData").show();
	  }
	 });
		  }
	  </script>
@endsection