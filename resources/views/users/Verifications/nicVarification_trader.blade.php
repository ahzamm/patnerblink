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
$userstatus = '';
$data = App\model\Users\UserVerification::where('username',$username)->first();
$nicFront = @$data['nic_front'];
$nicBack = @$data['nic_back'];
$nic = @$data['cnic'];
$userData = App\model\Users\UserInfo::where('username',$username)->first();
$userstatus = $userData['status'];
@endphp
<!-- SMS Module Verification -->
<style>
	.dealer__success,
	.subdealer__success,
	.user__success,
	.cnic__error,
	.file__size
	{
		display:none;
	}
</style>
<div aria-hidden="true"  role="dialog" tabindex="-1" id="nicVarification" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white"> Computerized National Identity Card (CNIC) Verification (Trader)</h4>
			</div>
			<div class="modal-body">
				<form id="addNic" method="POST">
					@csrf
					<div class="row" id="hmm">
						<div class="">
							<div class="col-md-6">
								<div style="">
									<p class="text-center">
										<span class="text-dark" style="font-weight:bold">CNIC <span>(Front Image)</span><br/> <small style="color:green">(Upload Max File Size 5Mb)</small></span>
									</p>
								</div>
								<div class="field"  align="center">
									<div style="display:flex;align-item:center;justify-content:center">
										<input type="hidden" name="usname" class="user" />
										@if($nicFront != '' && $nicBack != '')
										<input type="hidden" name="select_file" id="select_file" value="{{$nicFront}}">
										@if($userstatus == 'user')
										<img src="{{asset('UploadedNic/'.$nicFront)}}" alt="" width="250px;" height="140px;">
										@else 
										<img src="{{asset('sub_dealerNic/'.$nicFront)}}" alt="" width="250px;" height="140px;">
										@endif
										@else
										<input type="file" accept="image/*" name="select_file" id="select_file" onchange="preview_image(event)" required style="width:200px" />
										@endif
										<img id="output_image" style="display: none"/>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div style=""><p class="text-center">
									<span class="text-dark"style="font-weight:bold">CNIC <span>(Back Image)</span><br/> <small style="color:green">(Upload Max File Size 5Mb)</small></span></p>
								</div>
								<div class="field"  align="center">
									<div style="display:flex;justify-content:center;align-item:center">
										@if($nicBack != '' && $nicFront != '' )
										<input id="nicback" type="hidden" value="{{$nicBack}}" name="nicback">
										@if($userstatus == 'user')
										<img src="{{asset('UploadedNic/'.$nicBack)}}" alt="" width="250px;" height="140px;">
										@else 
										<img src="{{asset('sub_dealerNic/'.$nicBack)}}" alt="" width="250px;" height="140px;">
										@endif
										@else
										<input id="nicback" type="file" accept="image/*" name="nicback" onchange="preview_images(event)" required style="width:200px">
										@endif           
										<img id="output_images" style="display: none"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="username" class="user" value="<?php echo $username?>">
				<input type="hidden" name="dealer" id="dealer" value="<?php echo $dealer?>">
				<input type="hidden" name="sub_dealer" id="subd" value="<?php echo $subdealer?>">
				<input type="hidden" name="resId" id="resid" value="<?php echo $res?>">
				<input type="hidden" name="nic" id="cnic" value="<?php echo $nic?>">
				<input type="hidden" name="status" id="status" value="<?php echo $status?>">
				<div id="cnicoutput" style="margin-top: -10px">
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-11"> 
							<div class="btn-group" role="group" aria-label="Basic example">
								1) &nbsp;
								<input type="radio" name="ptrn" id="1" value="nic" checked onchange="changeRadio(this)">
								<label for="1" id="l1" style="color: blue;margin-right:20px;">
									National (CNIC)
								</label>
								2) &nbsp;
								<input type="radio" name="ptrn" id="3" value="ntn" onchange="changeRadio(this)">
								<label for="3" id="l3" style="margin-right:20px;">
									NTN Number
								</label>
							</div>
						</div>
					</div>
					<div class="">
						<div class="" style="display: flex;align-items: center;justify-content: center;padding: 20px;max-width:600px;margin:auto">
							<label for="nic" id="nic_label" style="white-space:nowrap">(CNIC) Number: </label>
							@if($nic != '')
							<input type="text" placeholder="xxxxx-xxxxxxx-x" data-mask="99999-9999999-9" value="{{$nic}}" readonly class="form-control" minlength="15" name="nic" id="nic"  required style="margin-left:20px"/>
							@else
							<input type="text" placeholder="xxxxx-xxxxxxx-x" data-mask="99999-9999999-9" class="form-control" minlength="15" name="nic" id="nic"  required style="margin-left:20px" />
							@endif
							<button type="submit"  class="btn btn-primary"><span class="glyphicon glyphicon-check"></span> Verify Now </button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
</div>
<!-- Alert Modal Start -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="verification_alert" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-md  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Message</h4>
			</div>
			<div class="modal-body">
				<div class="file__size">
					<p class="text-center">File Size Too Large...Please Upload Less Then (5Mb) File Size!!!</p>
					<p style="text-align:right">
						تصدیق کے لئے شناختی کارڈ کی تصویر کا فائل سائز (5 ایم بی) سے زیادہ ہے- براہ کرم شناختی کارڈ کی تصویر کا فائل سائز (5 ایم بی) سے کم اندراج کریں
					</p>
				</div>
				<div class="cnic__error">
					<p class="text-center">Cannot Upload Same (CNIC) Number More Then <span style="color:red">(2)</span> Registered Consumer (IDs). Please provide another CNIC.</p>
					<p style="text-align:right">
						ایک شناختی کارڈ پر
						<span style="color:red"> دو </span>
						سے زائد کنزیومر کو رجسٹرڈنہیں کیا جا سکتا- براہ کرم نئے شناختی کارڈ کا اندراج کریں
					</p>
				</div>
				<div class="user__success">
					<p class="text-center">Congratulation! This Consumer (ID) Successfully Registerd Now</p>
					<p class="text-center">آپ کا اندراج کیا ہوا شناختی کارڈ نمبر کامیابی کے ساتھ تصدیق کرلیا گیا ہے</p>
					<p style="color:green">Thanks for verifiying Consumer CNIC. By providing correct information regarding CNIC, It can be made easy for Consumer to pay Government TAX.</p>
					<p style="text-align:right;color:green">کنزیومر کی آئی ڈی کو تصدیق کرنے کے لئے شکریہ۔ شناختی کارڈ کی صحیح معلومات سے صارف کی ٹیکس کی ادائیگی کو آسان بنایا جا سکتا ہے</p>
				</div>
				<div class="subdealer__success">
					<p class="text-center">Congratulation! Your Trader (Partner) Successfully Registerd Now</p>
					<p class="text-center">آپ کا اندراج کیا ہوا شناختی کارڈ نمبر کامیابی کے ساتھ تصدیق کرلیا گیا ہے</p>
					<p style="color:green">Thanks for verifiying Trader CNIC. By providing correct information regarding CNIC, Trader Business can be made more strong.</p>
					<p style="text-align:right;color:green">
						ٹریڈر کی آئی ڈی کو تصدیق کرنے کے لئے شکریہ- ٹریڈر کی شناختی کارڈ کی صحیح معلومات سے ٹردڈر کے کاروبار کو مستحکم بنایا جا سکتا ہے-
					</p>
				</div>
				<div class="dealer__success">
					<p class="text-center">Congratulation! Your Contractor (Partner) Successfully Registerd Now</p>
					<p class="text-center">آپ کا اندراج کیا ہوا شناختی کارڈ نمبر کامیابی کے ساتھ تصدیق کرلیا گیا ہے</p>
					<p style="color:green">Thanks for verifiying Contractor CNIC. By providing correct information regarding CNIC, Contractor Business can be made more strong.</p>
					<p style="text-align:right;color:green">
						کنڑریکٹر کی آئی ڈی کو تصدیق کرنے کے لئے شکریہ- کنڑریکٹر کی شناختی کارڈ کی صحیح معلومات سے کنڑریکٹر کے کاروبار کو مستحکم بنایا جا سکتا ہے-
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Alert Modal End -->
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script t ype="text/javascript">
	$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
</script>
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
<script>
	$(document).ready(function(){		
		$('#addNic').on('submit', function(event){
			event.preventDefault();
			$.ajax({
				url: "{{route('users.billing.addNicToDB')}}",
				method:"POST",
				data:new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if(data == 'false'){
						$('#verification_alert .modal-header').css('background-color', 'red');
						$('.file__size').css('display', 'block');
						$('.cnic__error').css('display', 'none');
						$('#verification_alert').modal('show');
					}else if(data == 'user'){
						$('#verification_alert .modal-header').css('background-color', 'green');
						$('.file__size').css('display', 'none');
						$('.cnic__error').css('display', 'none');
						$('.user__success').css('display', 'block');
						$('#verification_alert').modal('show');
						setTimeout(function() {
							$('.user__success').css('display','none');
							window.history.back();
						}, 10000)
					}else if(data == 'exceed_NIC'){
						$('#verification_alert .modal-header').css('background-color', 'red');
						$('.file__size').css('display', 'none');
						$('.cnic__error').css('display', 'block');
						$('#verification_alert').modal('show');
					}else if(data == 'subdealer'){
						$('#verification_alert .modal-header').css('background-color', 'green');
						$('.subdealer__success').css('display', 'block');
						$('#verification_alert').modal('show');
						setTimeout(function() {
							$('.subdealer__success').css('display','none');
							window.history.back();
						}, 10000)
					}
					else{
						$('#verification_alert .modal-header').css('background-color', 'green');
						$('.dealer__success').css('display', 'block');
						$('#verification_alert').modal('show');
						setTimeout(function() {
							$('.dealer__success').css('display','none');
							window.history.back();
						}, 10000)
					}
				}
			})
		});		   
	});
</script>					
<script>
	function changeRadio(radio){
		let isCheck = radio.value;
		if(isCheck == 'nic'){
			$('#l1').css({"color":"blue","margin-right":"20px"});
			$('#l2').css({"color":"black","margin-right":"20px"});
			$('#l3').css({"color":"black","margin-right":"20px"});
			$('#l4').css({"color":"black","margin-right":"20px"});
			$('#nic').mask('99999-9999999-9');
			$('#nic').attr('minlength',15);
			$('#nic').attr("placeholder", "xxxxx-xxxxxxx-x");
			$('#nic_label').text('(CNIC) Number: ');
		}if(isCheck == 'Onic'){
			$('#l1').css({"color":"black","margin-right":"20px"});
			$('#l2').css({"color":"blue","margin-right":"20px"});
			$('#l3').css({"color":"black","margin-right":"20px"});
			$('#l4').css({"color":"black","margin-right":"20px"});
			$('#nic').mask('999999-999999-9');
			$('#nic').attr('minlength',15);
			$('#nic').attr("placeholder", "xxxxxx-xxxxxx-x");
			$('#nic_label').text('Overseas (CNIC): ');
		}if(isCheck == 'ntn'){
			$('#l1').css({"color":"black","margin-right":"20px"});
			$('#l2').css({"color":"black","margin-right":"20px"});
			$('#l3').css({"color":"blue","margin-right":"20px"});
			$('#l4').css({"color":"black","margin-right":"20px"});
			$('#nic').mask('99999-9999999-9');
			$('#nic').attr('minlength',15);
			$('#nic').attr("placeholder", "xxxxx-xxxxxxx-x");
			$('#nic_label').text('NTN Number: ');
		}if(isCheck == 'passport'){
			$('#l1').css({"color":"black","margin-right":"20px"});
			$('#l2').css({"color":"black","margin-right":"20px"});
			$('#l3').css({"color":"black","margin-right":"20px"});
			$('#l4').css({"color":"blue","margin-right":"20px"});
			$('#nic').mask('AAA999999');
			$('#nic').attr('minlength',9);
			$('#nic').attr("placeholder", "AAAxxxxxx");
			$('#nic_label').text('International Passport: ');
		}
	}
</script>
<!-- Code Finalize -->		  