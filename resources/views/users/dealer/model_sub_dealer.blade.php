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
	.ul_tc_list{
		color: #000;font-size: 20px;padding-inline-start: 1em;direction:rtl
	}
	.ul_tc_list li{
		list-style-type: disc;
	}
</style>
<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-4" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white"> Trader Form
					<span class="info-mark" onmouseenter="popup_function(this, 'trader_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('users.user.post',['status' => 'subdealer'])}}" method="POST">
					@csrf
					<input type="hidden" value="{{Auth::user()->manager_id}}" name="manager_id" class="form-control" placeholder="Manager-ID">
					<input type="hidden" value="{{Auth::user()->resellerid}}" name="resellerid" class="form-control" placeholder="Reseller Id">
					<input type="hidden" value="{{Auth::user()->dealerid}}"  name="dealerid" class="form-control" placeholder="Dealer ID">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group" style="display: none;">
								<label  class="form-label">Manager-ID</label>
								<input type="text" value="{{Auth::user()->manager_id}}" name="manager_id" class="form-control" placeholder="Manager-ID" readonly required>
							</div>
							<div class="form-group" style="display: none;">
								<label class="form-label">Reseller ID</label>
								<input type="text" value="{{Auth::user()->resellerid}}" name="resellerid" class="form-control" placeholder="Reseller Id" readonly required>
							</div>
							<div class="form-group" style="display: none;">
								<label class="form-label">Dealer ID</label>
								<input type="text" value="{{Auth::user()->dealerid}}"  name="dealerid" class="form-control" placeholder="Dealer ID" readonly required>
							</div>
							<div class="form-group position-relative" >
								<label class="form-label">Trader ID <span style="color: red">*</span></label><span id="subdealercheck"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'trader_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="sub_dealer_id" id="subdealerid" class="form-control" placeholder="Trader ID"  required onkeyup="checkavailablesubdealer(this.value)">
							</div>
							<div class="form-group position-relative">
								<label class="form-label">First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control" placeholder="0300-1234567"  data-mask="9999 9999999" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="land_number" class="form-control" placeholder="(021)1234567" data-mask="(999)99999999">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="nic" class="form-control" placeholder="00000-0000000-0" data-mask="99999-9999999-9" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" class="form-control" placeholder="info@blinkbroadband.pk" placeholder="info@logon.com.pk" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Business Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Business Address" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Assign Trader (Area) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'assgin_trader_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="area" class="form-control"  placeholder="Assign Trader (Area) " required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">National Tax Number (NTN) </label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'ntn_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="ntn" class="form-control"  placeholder="000000-0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'trader_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<span style="float: right;" id="subcheck"></span>
								<input type="text" name="username" id="subusername" class="form-control"  placeholder="Username" required readonly onkeyup="checkSubUser(this.value)">
							</div>
							<div class="form-group position-relative" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="password" name="password" class="form-control" id="inputPasswod" placeholder="Must be 8 characters long" required> <i class="fa fa-eye-slash toggleClass" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass();" > </i>
							</div>
							<div class="form-group" style="position:relative">
								<label  class="form-label">Retype Password <span style="color: red">*</span></label>
								<input type="password" name="password_confirmation" class="form-control" id="retypePasswod" placeholder="Must be 8 characters long" required > <i class="fa fa-eye-slash toggleClass2" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass2();" > </i>
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
						</div>
						<!--  -->
					</div>
					<div style="display: flex; justify-content: space-between; align-items: center;flex-wrap:wrap">
						<div>
							<input type="checkbox" id="t&c" required>
							<label for="t&c" style="font-weight:normal">I accept <a href="#tc_cont_modal" data-toggle="modal" class="theme-color"  style="font-weight:bold">terms and conditions</a></label>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Terms And Conditions Contractor -->
<div class="modal fade" id="tc_cont_modal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 99999">
	<div class="modal-dialog modal-lg animated bounceInDown">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" style="color: #fff;" >Terms and Conditions</h4>
			</div>
			<div class="modal-body" style="text-align:right">
				<ul class="ul_tc_list" style="">
					<li>میں بحیثیت کنٹریکٹر اس بات کا پابند ہوں کہ میں تمام صارفین کے قوائف مثلا (صارف کا نام، شناختی کارڈ نمبر، موبائل ،فون نمبر اور جس جگہ سروس فراہم کی گئی ہے وہاں کا مکمل پتہ) اور کمپنی کی جانب سے دی گی مطلوبہ قوائف کا اندراج کمپنی کی فراہم کردہ ایپلیکیشن میں درستگی کے ساتھ اندراج کروں گا۔</li>
					<li>میں بحیثیت کنٹریکٹر اس بات کا اقرار کرتا ہوں کہ میری جانب سے اندراج کردہ تمام صارفین کی معلومات درست ہیں اور کسی بھی صورت میں سرکاری اداروں کو جواب دہ میں (کنٹریکٹر) ہوں گا-</li>
					<li>میں بحیثیت کنٹریکٹر اس بات کو تسلیم کرتا ہوں کہ یہ سروس صرف گھریلو صارفین کے لیے ہے اور میں ( بحیثیت کنٹریکٹر) یہ سروس کارپوریٹ سروس بول کر کسی بھی صارف کو فراہم نہیں کروں گا-</li>
					<li>میں بحیثیت کنٹریکٹر اس بات کو تسلیم کرتا ہوں کہ کمپنی کی جانب سے مقرر کردہ علاقے کی حدود میں صرف کمپنی کی فراہم کردہ سروس چلانے کا پابند ہوں-</li>
					<li>میں بحیثیت کنٹریکٹر اس بات کو تسلیم کرتا ہوں کہ کمپنی کی جانب سے فراہم کردہ تمام شرائط اور ہدایات پر عمل کرنے اور کروانے کا پابند ہوں-</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-info" type="button">Ok</button>
			</div>
		</div>
	</div>
</div>
@section('ownjs')
@endsection
<!-- Code Finalize -->