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
	.right__code,
	.wrong__code{
		display:none;
	}
</style>
<!-- SMS Module Verification -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="smsVarification" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center;color: white">Consumer Mobile Number Verification</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<?php
						$up_on = '';
						$isverify = App\model\Users\UserVerification::where('username',$username)->select('update_on')->first();
						if(!empty($isverify))
						{
							$up_on = $isverify['update_on'];
						}
						?>
						<div class="col-md-12">
							<div class="form-group">
								<p style="text-align: center;font-size: 16px;color:green;font-weight:700">As per PTA policy and Security agencies concern , Consumer contact number must be verified. Please type correct mobile number to verify Consumer.</p><br>
								<p style="text-align: center;font-size: 16px;color:green;font-weight:900">پی ٹی اے کی  پالیسی کے مطابق اور سیکیورٹی کو محفوظ بنانے کے لیے۔ صارف کا موباۂل نمبر تصریق ہونا ضروری ہے۔ براۓ مہربانی درست موبائل  نمبر تصریق کریں۔ </p>
							</div>
							<form id="add">
								<div class="col-md-3" style="display: inline-block;margin: 0px;padding: 0px;" id="selectBox">
									<select name="mobCode" id="mobCode" class="form-control">
										<option value="92300">0300</option><option value="92301">0301</option><option value="92302">0302</option><option value="92303">0303</option>
										<option value="92304">0304</option><option value="92305">0305</option><option value="92306">0306</option><option value="92307">0307</option>
										<option value="92308">0308</option><option value="92309">0309</option><option value="92310">0310</option><option value="92311">0311</option>
										<option value="92312">0312</option><option value="92313">0313</option><option value="92314">0314</option><option value="92315">0315</option>
										<option value="92316">0316</option><option value="92317">0317</option><option value="92318">0318</option><option value="92349">0319</option>
										<option value="92320">0320</option><option value="92321">0321</option>
										<option value="92322">0322</option><option value="92323">0323</option><option value="92324">0324</option><option value="92325">0325</option><option value="92326">0326</option><option value="92327">0327</option><option value="92328">0328</option><option value="92330">0330</option>
										<option value="92331">0331</option><option value="92332">0332</option><option value="92333">0333</option><option value="92334">0334</option>
										<option value="92335">0335</option><option value="92336">0336</option><option value="92337">0337</option><option value="92339">0339</option><option value="92340">0340</option>
										<option value="92341">0341</option><option value="92342">0342</option><option value="92343">0343</option><option value="92344">0344</option>
										<option value="92345">0345</option><option value="92346">0346</option><option value="92347">0347</option><option value="92348">0348</option>
										<option value="92349">0349</option><option value="92370">0370</option>
									</select>
								</div>
								<div class="col-md-9" style="display: inline-block;margin: 0px;padding: 0px;">
									<!-- Mobile Number Configration -->
									<div class="form-group" id="verifynumber">
										<div class="input-group add" id="test">
											{{--input Fields here from Controller  --}}
										</div>
									</div>
									{{-- <span id="output">output</span> --}}
								</div>
							</form>
						</div>
						{{-- Re Send Sms Code --}}
						<div class="col-md-3"></div>
						<div class="col-md-6" style="display: inline-block;margin: 0px;padding: 0px;">
							<form id="addMobileCode" method="POST">
								<div class="form-group" id="codeverify" style="display: none;">
									<div class="input-group"> 
										<input type="hidden" name="username" value="<?php echo $username ?>">
										<input type="text" name="mobileCode" data-mask="9999" placeholder="xxxx" class="form-control" required>
										<div class="input-group-btn" id="sendBtn">
											<button type="submit" class="btn btn-primary theme-bg" style="color:white;" >
												<span class="glyphicon glyphicon-check"></span> Apply Now
											</button>
										</div>
									</div>
								</div>
							</form>
						</div>	
					</div>
					<div class="row" id="codeDIV" style="display: block">
						<div class="col-md-12 col-sm-12 col-xs-12 d-flex justify-content-center text-center code">
							<span style="font-family: serif; font-size: 16px; color:red">Provide Valid Consumer Mobile Number for Verification</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
{{-- </div> --}}
<div aria-hidden="true"  role="dialog" tabindex="-1" id="verification_alert" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-md  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Message</h4>
			</div>
			<div class="modal-body">
				<div class="wrong__code">
					<p class="text-center">Please enter the valid code!</p>
					<p class="text-center">براہ کرم درست کوڈ کا اندراج کریں </p>
				</div>
				<div class="right__code">
					<p class="text-center">Congratulation! Your Mobile Number has been Successfully Registerd!</p>
					<p class="text-center">آپ کا اندراج کیا ہوا موبائل نمبر کامیابی کے ساتھ تصدیق کرلیا گیا ہے</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#add").submit(function() {
			$.ajax({
				async: true,
				type: "POST",
				url: "{{route('users.billing.smsSend')}}",
				data:$("#add").serialize(),
				success: function (res) {
					if(res != 'false'){
						$('#verifingcode').html(res);
						$('#codeDIV').show();
						$('#verifynumber').hide();
						$('#codeverify').show();
						$('#selectBox').hide();
						$('#space').hide();
					}else{
						alert("Do Not Allowed more then two Id's register On Same Number Please Change Number and try again...!");
					}
					setTimeout(function(){
						fetchcode();
					}, 10);
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
<script>
	$(document).ready(function(){
		$('#addMobileCode').on('submit', function(event){
			event.preventDefault();
			$.ajax({
				url: "{{route('users.billing.addMobileCode')}}",
				method:"POST",
				data:new FormData(this),
				contentType: false,
				cache: false,
				processData: false,
				success:function(data)
				{
					if(data == 'false'){
						$('#verification_alert .modal-header').css('background-color', 'red');
						$('.wrong__code').css('display','block');
						$('#verification_alert').modal('show');
					}else{
						$('#verification_alert .modal-header').css('background-color', 'green');
						$('.wrong__code').css('display','none');
						$('.right__code').css('display','block');
						$('#verification_alert').modal('show');
						setTimeout(function() {
							$('.right__code').css('display','none');
							window.history.back();
						}, 5000)
					}
				}
			})
		});
	});
</script>
<!-- Code Finalize -->