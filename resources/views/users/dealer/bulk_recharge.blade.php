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
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
	#mobile select,
	#filter_ser select{
		display: none;
	}
	#filter_username select{
		display: none;
	}
	#filter_fullname select{
		display: none;
	}
	#date select{
		display: none;
	}
	#check select{
		display: none;
	}
	#address select{
		display: none;
	}
	.badge .fa {
		font-size: 25px !important;
	}
	#loadingnew{
		display: none;
	}
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">

			<div class="header_view">
				<h2 id="output">Bulk Recharge Accounts
					<span class="info-mark" onmouseenter="popup_function(this, 'bulk_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h2>

			</div>
			<section class="box">
				<header class="header"></header>
				<div class="content-body">
					@if(session('error'))
					<div class="alert alert-error alert-dismissible show">
						{{session('error')}}
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
					@endif
					@if(session('success'))
					<div class="alert alert-success alert-dismissible show">
						{{session('success')}}
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>
					@endif
					<div class="row">
						<form method="POST" action="{{route('users.charge.bulk.post')}}" id="myform">
							@csrf

							<table id="example1" class="table table-striped dt-responsive" style="width:100%">
								<thead>
									<tr>
										<th>Serial#</th>
										<th style="width: 30px;"><input type="checkbox" class="from-control" onclick="checkall(this)" name="allcheck" style="width:30px"></th>
										<th>Consumer (ID)</th>
										<th>Consumer Name</th>
										<th>Mobile Number</th>
										<th class="desktop">Address</th>
										<th>Internet Profile Name</th>
										<th>Expiry (Date & Time)</th>
									</tr>
									<tr style="background:white !important;" id="filter_row">
										<td id="filter_ser"></td>
										<td id="check"></td>
										<td id="filter_username"></td>
										<td id="filter_fullname"></td>
										<td id="mobile"></td>
										<td id="address"></td>
										<td ></td>
										<td id="date"></td>


									</tr>
								</thead>
								<tbody>
									@php $sno = 1; @endphp
									@foreach($userList as $user)
									@if($user->user_status_info_expired)
									@php
									$userProfileGroupname = str_replace(['MT-','BE-','k'],['','',''],$user->profile);
									// dd($userProfileGroupname);
									$userProfile = App\model\Users\Profile::where('name',$user->name)->first()->name;
									$expire_date = App\model\Users\UserStatusInfo::where('username',$user->username)->first()->card_expire_on;
									$checkDisable= App\model\Users\RaduserGroup::where(['username' => $user->username])->first(['groupname']);
									@endphp
									@if($checkDisable->groupname != 'DISABLED')
									<tr>
										<td>{{$sno++}}</td>
										<td><input type="checkbox" class="from-control" name="dataList[]" value="{{$user->username}},{{$userProfile}}" id="checkthis" style="width:30px;height:30px"></td>
										<td class="td__profileName">{{$user->username}}</td>
										<td>{{$user->firstname}} {{$user->lastname}}</td>
										<td>{{$user->mobilephone}}</td>
										<td>{{$user->address}}</td>
										<td style="color:darkgreen;font-weight:bold">{{$userProfile}}</td>
										<td style="color:red">{{$expire_date}}</td>
									</tr>
									@endif
									@endif
									@endforeach
								</tbody>
							</table>
							<div class="col-md-12">
								<div class="form-group pull-right">
									<button type="submit" id="chargeBtn" disabled="true" class="btn btn-primary" value="Charge">Recharge</button>
									<img src="{{asset('img/loading.gif')}}" id="loadingnew" width="5%" >
								</div>
							</div>
						</form>

					</div>
				</div>
			</section>

		</section>
	</section>
</div>
@endsection
@section('ownjs')
{{-- <script type="text/javascript">
	if(localStorage.openpages){
		alert('File already opened in a tab.');
		$(".btn").css("display", "none");
	}else{
		localStorage.openpages = "1";
		window.onbeforeunload = function () {
			localStorage.openpages = "";
		};
		$("#btnText").css("display", "none");
	}
</script> --}}
<script>
	$(document).ready(function(){
		var vals = $('#example1_length select').val();
		if (vals == 100){
			alert('Select below then 100.');
		}
	});
//
$(document).ready(function(){
	$('input[type="checkbox"]').click(function(){
		var count =$('#checkthis:checked').length;
		if(count > 0){
			$('#chargeBtn').prop('disabled', false);
		}else{
			$('#chargeBtn').prop('disabled', true);
		}
	});
});
$("#myform").submit(function(){
	$('#chargeBtn').hide();
	$('#loadingnew').show();
});
</script>
<script type="text/javascript">
	function checkall(source) {
		var checkboxes = document.querySelectorAll('input[type="checkbox"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i] != source)
				checkboxes[i].checked = source.checked;
		}
	}
</script>
<script>
	$(document).ready(function(){
		setTimeout(function(){
			$('.alert').fadeOut(); }, 3000);
	});
</script>
<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#example1').DataTable({
			"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
@endsection
<!-- Code Finalize -->