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
<script src="{{asset('js/jquery.inputmask.min.js')}}"></script>
<style>
	.pass_message{
		float: right;
		border-radius: 25px;
		background-color: gray;
		color: white;
		font-weight: bold;
		padding-right: 10px;
		padding-left: 10px;
	}
	.ul_tc_list{
		color: #000;font-size: 20px;padding-inline-start: 1em;direction:rtl
	}
	.ul_tc_list li{
		list-style-type: disc;
	}
</style>
<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_dealer" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center; color: white"> Contractor Form
					<span class="info-mark" onmouseenter="popup_function(this, 'contractor_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('users.user.post',['status' => 'dealer'])}}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<input type="hidden" value="{{Auth::user()->manager_id}}" name="manager_id" class="form-control"  placeholder="Manager-ID" required readonly>
							<input type="hidden" value="{{Auth::user()->resellerid}}" name="resellerid" class="form-control"  placeholder="Reseller-ID" required readonly>
							<div class="form-group position-relative">
								<label  class="form-label">Contractor (ID) <span style="color: red">*</span></label>
								<span id="dealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'contractor_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="dealerid" class="form-control" id="dealerid" placeholder="Contractor ID" required onkeyup="checkDealer(this.value)">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">First Name <span style="color: red">*</span></label>
								<span style="float: right;" id="dealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control" placeholder="First Name"  required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span style="float: right;" id="dealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input name="nic" class="form-control"  data-mask="99999-9999999-9" placeholder="00000-0000000-0" required>
							</div>
							<!-- NTN Not Functional -->
							<div class="form-group position-relative">
								<label  class="form-label">National Tax Number (NTN) </label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'ntn_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input name="ntn" class="form-control"  placeholder="0000000-0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input name="mobile_number"  data-mask="9999-9999999" placeholder="0321-1234567" class="form-control"   required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input name="land_number"  class="form-control" data-mask="(999)99999999" placeholder="(021)12345678" >
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span style="float: right;" id="dealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" class="form-control"  placeholder="info@gmail.com" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Business Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Business Address" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Contractor Area <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'assgin_contractor_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="area" class="form-control"  placeholder="Contractor Area " required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Select State<span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_state');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select class="form-control" name="state" required>
									<option value="">select state</option>	
									<option>SINDH</option>	
									<option>PUNJAB</option>	
									<option>KPK</option>	
									<option>BALOCHISTAN</option>	
									<option>GILGIT BULTISTAN</option>	
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'user_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<span style="float: right;" id="availableuser"></span>
								<input type="text" name="username" class="form-control" id="username" placeholder="Username" required readonly onkeyup="checkavailableuser(this.value)">
							</div>
							<div class="form-group position-relative" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span style="float: right;" id="dealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="password" name="password" id="pass" class="form-control"  placeholder="Password must be 8 characters" required>
								<i class="fa fa-eye-slash toggleClass" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass()" > </i>
							</div>
							<div class="form-group" style="position:relative">
								<label  class="form-label">Retype Password <span style="color: red">*</span></label>
								<span class="pass_message" id="passrecheck"></span>
								<input type="password" id="pass_c" name="password_confirmation"  class="form-control"  placeholder="Password must be 8 characters" required>
								<i class="fa fa-eye-slash toggleClass2" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass2()" > </i>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC <span style="color: #0d4dab">(Front Image)</span> <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="file" name="cnic_front" class="form-control"  placeholder="">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC <span style="color: #0d4dab">(Back Image)</span> <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="file" name="cnic_back" class="form-control"  placeholder="">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Select (NAS)<span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_nas');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select class="form-control" name="dealer_nas" required>
									<option value="">Select (NAS)</option>
									<?php foreach ($assignedNas as $key => $value) { ?>
										<option value="<?= $value->nas?>"><?= $value->nas?></option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div style="display: flex; justify-content: space-between; align-items: center;flex-wrap:wrap">
						<div>
							<input type="checkbox" id="" required>
							<label for="t&c" style="font-weight:normal">I accept <a href="#tc_res_modal" data-toggle="modal" class="theme-color" style="font-weight:bold">terms and conditions</a></label>
						</div>
						<div class="form-group" style="margin-bottom: 0">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Terms and Conditions Reseller -->
<div class="modal fade" id="tc_res_modal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99999;display:none">
	<div class="modal-dialog modal-lg animated bounceInDown">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="color: #fff;" >Terms and Conditions</h4>
			</div>
			<div class="modal-body" style="text-align:right">
				<ul class="ul_tc_list">
					<li >میں بحیثیت ریسیلر اس بات کا پابند ہوں کہ میں تمام صارفین کے قوائف مثلا (صارف کا نام، شناختی کارڈ نمبر، موبائل ،فون نمبر اور جس جگہ سروس فراہم کی گئی ہے وہاں کا مکمل پتہ) اور کمپنی کی جانب سے دی گی مطلوبہ قوائف کا اندراج کمپنی کی فراہم کردہ ایپلیکیشن میں درستگی کے ساتھ اندراج کروں گا۔</li>
					<li>میں بحیثیت ریسیلر اس بات کا اقرار کرتا ہوں کہ میری جانب سے اندراج کردہ تمام صارفین کی معلومات درست ہیں اور کسی بھی صورت میں سرکاری اداروں کو جواب دہ میں (ریسیلر) ہوں گا-</li>
					<li>میں بحیثیت ریسیلر اس بات کو تسلیم کرتا ہوں کہ یہ سروس صرف گھریلو صارفین کے لیے ہے اور میں ( بحیثیت ریسیلر) یہ سروس کارپوریٹ سروس بول کر کسی بھی صارف کو فراہم نہیں کروں گا-</li>
					<li>میں بحیثیت ریسیلر اس بات کو تسلیم کرتا ہوں کہ کمپنی کی جانب سے مقرر کردہ علاقے کی حدود میں صرف کمپنی کی فراہم کردہ سروس چلانے کا پابند ہوں-</li>
					<li>میں بحیثیت ریسیلر اس بات کو تسلیم کرتا ہوں کہ کمپنی کی جانب سے فراہم کردہ تمام شرائط اور ہدایات پر عمل کرنے اور کروانے کا پابند ہوں-</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-info" type="button">Ok</button>
			</div>
		</div>
	</div>
</div>
@section('ownjs')
<script >
	function checkavailableuser(username) {
		var val=$('#username').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'username='+username,
				success: function(data){
// For Get Return Data
$('#availableuser').html(data);
}
});
		}
		else{
			$('#availableuser').html('');
		}
	}
	$(document).on("keyup","#pass_c",function(){
		var pass = $("#pass").val();
		var pass_c = $("#pass_c").val();
		if(pass == pass_c){
			$('#passrecheck').html('Match');
			$("#btn-submit").removeAttr('DISABLED');
		}else{
			$('#passrecheck').html('Not Match');
			$("#btn-submit").attr('DISABLED','DISABLED');
		}
	});
</script>
@endsection
<!-- Code Finalize -->