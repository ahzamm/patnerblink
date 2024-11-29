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
<style>
	input[type="file"] {
		display: block;
	}
	.imageThumb {
		height: 150px;
		width: 300px;
		border: 2px solid;
		padding: 1px;
		cursor: pointer;
		margin-top: 10px;
	}
	.pip {
		display: inline-block;
		margin: 10px 10px 0 0;
	}
	.remove {
		display: block;
		background: #444;
		border: 1px solid black;
		color: white;
		text-align: center;
		cursor: pointer;
	}
	.remove:hover {
		background: white;
		color: black;
	}
	#output_images
	{
		margin-top: 10px;
		width:250px;
		height: 140px;
		margin-left: -10px;
	}
	#output_image
	{
		margin-top: 10px;
		width:250px;
		height: 140px;
		margin-left: -10px;
	}
</style>
@php
$nicFront = '';
$nicBack = '';
$nic = '';
$ntn = '';
$passport = '';
$overseas = '';
$nicData = '';
$userstatus = '';
$data = App\model\Users\UserVerification::where('username',$username)->first();
$nicFront = $data['nic_front'];
$nicBack = $data['nic_back'];
$nic = $data['cnic'];
$ntn = $data['ntn'];
$overseas = $data['overseas'];
$passport = $data['intern_passport'];
if(!empty($nic)){
$nicData = $nic;
}else if(!empty($overseas)){
$nicData = $overseas;
}else if(!empty($passport)){
$nicData = $passport;
}else if(!empty($ntn)){
$nicData = $ntn;
}
$userData = App\model\Users\UserInfo::where('username',$username)->first();
$userstatus = $userData['status'];
@endphp
<!-- SMS Module Verification -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="nicVarification" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog" style="width: 85%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center;color: white"> CNIC Verification</h4>
				</div>
				<div class="modal-body">
					{{-- <form id="nicverify" method="POST" enctype="multipart/form-data"> --}}
						{{-- <form action="{{route('users.billing.verification')}}" method="POST" enctype="multipart/form-data"> --}}
							<form id="addNic" method="POST">
								@csrf
								<div class="row" id="hmm">
									<div class="form-group">
										<p style="text-align: center;" >The <span class="text-danger"> CNIC VERIFICATION </span> used for active your Recharge process if any user is not verified you can't recharge these id's First verify user then recharge
										</p>
										<p style="text-align: center;"> براۓ مہربانی یوزر  ریچارج کرنے سے پہلے یوزر  کا شناسختی کارڈ  تصدیق کریں دوسری صورت میں آپ یوزر  کو ریچارج نہیں کر سکتے شکریہ </p>
									</div>
									<div class="">
										<div class="col-md-6">
											<div style="margin-left: 20px;"><p>
												<span class="text-danger">Front of your CNIC (Max File Size 5Mb)</span></p>
											</div>
											<div class="field"  align="center">
												<div>
													<input type="hidden" name="usname" class="user">
													@if($nicFront != '' && $nicBack != '')
													<input type="hidden" name="select_file" id="select_file" value="{{$nicFront}}">
													@if($userstatus == 'user')
													<img src="{{asset('UploadedNic/'.$nicFront)}}" alt="" width="250px;" height="140px;">
													@else 
													<img src="{{asset('sub_dealerNic/'.$nicFront)}}" alt="" width="250px;" height="140px;">
													@endif
													@else
													<input type="file" accept="image/*" name="select_file" id="select_file" onchange="preview_image(event)" required>
													@endif
													<img id="output_image" style="display: none"/>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div style="margin-left: 20px;"><p>
												<span class="text-danger">Back of your CNIC (Max File Size 5Mb)</span></p>
											</div>
											<div class="field"  align="center">
												@if($nicBack != '' && $nicFront != '' )
												<input id="nicback" type="hidden" value="{{$nicBack}}" name="nicback">
												@if($userstatus == 'user')
												<img src="{{asset('UploadedNic/'.$nicBack)}}" alt="" width="250px;" height="140px;">
												@else 
												<img src="{{asset('sub_dealerNic/'.$nicBack)}}" alt="" width="250px;" height="140px;">
												@endif
												@else
												<input id="nicback" type="file" accept="image/*" name="nicback" onchange="preview_images(event)" required>
												@endif           
												<img id="output_images" style="display: none"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div >
								{{-- <img id="loading-image1" style="margin-left: 320px;margin-top: -100px; display: none;" src="{{asset('load.gif')}}" width="150px;" height="150px;" alt="img"> --}}
							</div>
							{{-- <div align="center" id="nicvbtn">
								<input class="btn btn-primary" style="background-color: #4878bf;"	 type="submit" name="cnicverify" id="cnicverify" value="Cnic Verify Here" disabled>
							</div> --}}
						{{-- </form> --}}
						<input type="hidden" name="username" class="user" value="<?php echo $username?>">
						<input type="hidden" name="dealer" id="dealer" value="<?php echo $dealer?>">
						<input type="hidden" name="sub_dealer" id="subd" value="<?php echo $subdealer?>">
						<input type="hidden" name="resId" id="resid" value="<?php echo $res?>">
						<input type="hidden" name="nic" id="cnic" value="<?php echo $nic?>">
						<input type="hidden" name="status" id="status" value="<?php echo $status?>">
						<div id="cnicoutput" style="margin-top: -10px">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-9"> 
									<div class="btn-group" role="group" aria-label="Basic example">
										<label for="ptrn" id="l1" style="color: blue">
											National NIC:  <input type="radio" name="ptrn" id="1" value="nic" checked onchange="changeRadio(this)">
										</label>
										<label for="ptrn" id="l2">
											OverSeas NIC:  <input type="radio" name="ptrn" id="2" value="Onic" onchange="changeRadio(this)">
										</label>
										<label for="ptrn" id="l3">
											NTN:  <input type="radio" name="ptrn" id="3" value="ntn" onchange="changeRadio(this)">
										</label>
										<label for="ptrn" id="l4">
											International Passport:  <input type="radio" name="ptrn" value="passport" id="4" onchange="changeRadio(this)">
										</label>
									</div>
								</div>
								<div class="col-md-1"></div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-3" style="margin-left: 20px;">
								</div>
								<div class="col-md-3">
									@if($nic != '' || $ntn !='' || $passport != '' || $overseas != '')
									<input type="text" placeholder="99999-9999999-9" data-mask="99999-9999999-9" value="{{$nicData}}" readonly class="form-control" minlength="15" name="nic" id="nic"  required>
									@else
									<input type="text" placeholder="99999-9999999-9" data-mask="99999-9999999-9" class="form-control" minlength="15" name="nic" id="nic"  required>
									@endif
								</div>
								<div class="col-md-2" style="margin-bottom: 10px;">
									<div>
										<button type="submit"  class="btn btn-primary"> Verify </button>
									</div>
								</div>
								<div class="col-md-4" ></div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script t ype="text/javascript">
	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
{{--  --}}
<script type='text/javascript'>
	function preview_image(event) 
	{
		var reader = new FileReader();
		reader.onload = function()
		{
			var output = document.getElementById('output_image');
			output.src = reader.result;
			$('#output_image').show();
		}
		reader.readAsDataURL(event.target.files[0]);
	}
</script>
<script type='text/javascript'>
	function preview_images(events) 
	{
		var readers = new FileReader();
		readers.onload = function()
		{
			var outputs = document.getElementById('output_images');
			outputs.src = readers.result;
			$('#output_images').show();
$('#cnicoutput').show();
}
readers.readAsDataURL(event.target.files[0]);
}
</script>
{{-- <script>
	$(document).ready(function(){
		$('#nicverify').on('submit', function(event){
			$('#hmm').hide();
			$('#loading-image1').show();
			event.preventDefault();
			$.ajax({
				url: "{{route('users.billing.apitest')}}",
				method:"POST",
				data:new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if(data != ''){
						$("#cnicoutput").show();
						$("#cnicverify").hide();
					}else{
						alert('Please Upload Correct and Clear NIC');
					}
				},
				complete: function(){
					$('#loading-image1').hide();
					$('#hmm').show();
				} 
			})
		});
	});
</script> --}}
<script>
	$(document).ready(function(){
		$('#addNic').on('submit', function(event){
			event.preventDefault();
			$.ajax({
				url: "{{route('users.userPanel.addNic')}}",
				method:"POST",
				data:new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if(data == 'false'){
						alert('Please Upload Less then 5 Mb File...');
					}else if(data == 'user'){
						alert('Your CNIC has been Successfully Registerd..');
						window.location = "{{route('users.userPanel.userNicView')}}";
					}else if(data == 'more'){
						alert("Your can't Upload same Cnic More then one");
					}else if(data == 'more1'){
						alert("Your can't Upload same Cnic More then Two");
					}else{
						alert('Your CNIC has been Successfully Registerd..');
						window.location = "{{route('users.dashboard')}}";
					}
				}
			})
		});
	});</script>
	<script>
		function changeRadio(radio){
			let isCheck = radio.value;
			if(isCheck == 'nic'){
				$('#l1').attr('style',  'color:blue');
				$('#l2').attr('style',  'color:black');
				$('#l3').attr('style',  'color:black');
				$('#l4').attr('style',  'color:black');
				$('#nic').mask('99999-9999999-9');
				$('#nic').attr('minlength',15);
				$('#nic').attr("placeholder", "99999-9999999-9");
			}if(isCheck == 'Onic'){
				$('#l1').attr('style',  'color:black');
				$('#l2').attr('style',  'color:blue');
				$('#l3').attr('style',  'color:black');
				$('#l4').attr('style',  'color:black');
				$('#nic').mask('999999-999999-9');
				$('#nic').attr('minlength',15);
				$('#nic').attr("placeholder", "999999-999999-9");
			}if(isCheck == 'ntn'){
				$('#l1').attr('style',  'color:black');
				$('#l2').attr('style',  'color:black');
				$('#l3').attr('style',  'color:blue');
				$('#l4').attr('style',  'color:black');
				$('#nic').mask('9999999-9');
				$('#nic').attr('minlength',9);
				$('#nic').attr("placeholder", "9999999-9");
			}if(isCheck == 'passport'){
				$('#l1').attr('style',  'color:black');
				$('#l2').attr('style',  'color:black');
				$('#l3').attr('style',  'color:black');
				$('#l4').attr('style',  'color:blue');
				$('#nic').mask('AAA999999');
				$('#nic').attr('minlength',9);
				$('#nic').attr("placeholder", "909933535");
			}
		}
	</script>
<!-- Code Finalize -->