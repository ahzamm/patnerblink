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
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
	<section id="main-content">
		<section class="wrapper main-wrapper row">
			<div class="header_view">
				<h2>Kick Consumer (ID)</h2>
			</div>
			<section class="box ">
				<div class="content-body">
					<p style="font-size: 18px;text-align:center;margin-bottom:30px;padding-top:30px;"><b> Enter the correct Consumer (ID) to disconnect over BRAS, in case of any mismatch Consumer (ID) will not disconnect. <br><br/> </p>
						<div class="row">
							<form id="myform">
								<div class="" style="display:flex;align-items:center;justify-content:center;column-gap:20px;row-gap:10px;margin-top:20px;flex-wrap:wrap">
									<label for="username">Consumer (ID)</label>
									<input type="text" class="form-control" name="username"  placeholder="Enter Consumer (ID) here..." required style="max-width:300px">
									<button type="submit" name="submit" class="btn btn-danger" id="dc_btn" onclick="showCounter()">Disconnect Now </button>
								</div>
							</form>
						</div>
					</div>
				</section>
				<div class="content-body" style="padding-top:20px">
					<table id="example-1" class="table dt-responsive display w-100">
						<thead>
							<tr>
								<td>Serial#</td>
								<td>Consumer (ID)</td>
								<td>Contractor (ID) </td>
								<td>Trader (ID) </td>
								<td>Date & Time</td>
								<td>Action By</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Consumer ID</td>
								<td>Contractor ID</td>
								<td>Trader ID</td>
								<td>28 Aug, 2023</td>
								<td>Reseller</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</section>
	</div>
	<div class="modal" id="counter" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<h1 id="countdown" class="number_counter"></h1>
					<p class="countdown_msg">Please be patient ...</p>
				</div>
			</div>
		</div>
	</div>
	@endsection
	@section('ownjs')
	<script type="text/javascript">
// Countdown Timer Start
function showCounter() {
	document.getElementById("countdown").innerHTML =  '10';
	$('#dc_btn').attr('disabled', true);
	var timeleft = 10;
	var downloadTimer = setInterval(() => {
		if(timeleft == 0){
			$('#counter').modal('hide');
			$('#dc_btn').attr('disabled', false);
			clearInterval(downloadTimer);
			document.getElementById("countdown").innerHTML =  '';
		}
		document.getElementById("countdown").innerHTML =  timeleft;
		timeleft -= 1;
	}, 900);
	$('#counter').modal('show');
}
// Countdown Timer End
$(document).ready(function() {
	$("#myform").submit(function() {
		$.ajax({
			type: "POST",
			url: "{{route('users.kickit')}}",
			data:$("#myform").serialize(),
			success: function (data) {
				alert(data);
				$('#myform').trigger("reset");
			},
			error: function(jqXHR, text, error){
// Displaying If There Are Any Errors
$('#output').html(error);
}
});
		return false;
	});
});
</script>
@endsection
<!-- Code Finalize -->