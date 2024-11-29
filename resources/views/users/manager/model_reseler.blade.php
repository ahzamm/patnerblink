<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-2" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white"> Reseller Form
					<span class="info-mark" onmouseenter="popup_function(this, 'reseller_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('users.user.post',['status' => 'reseller'])}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<input type="hidden" readonly value="{{Auth::user()->manager_id}}" required name="manager_id" class="form-control">
							<div class="form-group position-relative" >
								<label class="form-label">Reseller ID <span style="color: red">*</span></label><span id="reselleroutput"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="resellerid" id="resellerid" class="form-control" placeholder="Unique Reseller Id" onkeyup="checkavailablereseller(this.value)" required>
							</div>
							<div class="form-group position-relative">
								<label for="fname" class="form-label">First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control" id="fname" placeholder="First Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Business Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Business Address" required>
							</div>
							<div class="form-group position-relative">
								<label class="form-label">Select City <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_city');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select name="city" id="city" class="form-control" required>
									<option value="">Select City</option>
									<?php
									$city = DB::table('cities')->get();
									foreach($city as $cityValue){ ?>
										<option><?= $cityValue->city_name;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control" placeholder="(0300) 1234567" required data-mask="9999-9999999">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="land_number" class="form-control" placeholder="(021) 1234567" required data-mask="(999)99999999">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="nic" class="form-control" placeholder="00000-0000000-0" required data-mask="99999-9999999-9">
							</div>
							<div class="form-group position-relative">
								<label for="email" class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" id="email" class="form-control"  placeholder="info.gmail.com" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Assgin Business Area <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'assgin_reseller_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="area" class="form-control" placeholder="Assgin Business Area" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label for="resusername"  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'user_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<span style="float: right;" id="availableresuser"></span>
								<input type="text" name="username" id="resusername" class="form-control"  placeholder="Username"  onkeyup="checkavailableuser(this.value)" required readonly>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="password" name="password" class="form-control"  placeholder="Password must be 8 character" required>
							</div>
							<div class="form-group">
								<label for="retypepassword" class="form-label">Retype Password <span style="color: red">*</span></label>
								<input type="password" name="password_confirmation" id="retypepassword" class="form-control" placeholder="Confirm Password" required>
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
							<div style="display:none;" class="form-group">
								<h5>Select Theme Color</h5>
								<input type="color" class="color1" name="color1" value="#232e3d">
								<input type="color" class="color2" name="color2" value="#7b1fa2">
							</div>
						</div>
					</div>
					<hr style="margin-bottom: 30px;margin-top: 30px;">
					<div class="header_view" style="margin-bottom: 20px;">
						<h2 style="font-size: 26px;">Brand Management</h2>
					</div>
					<div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label class="form-label">Domain Name (http://demo.pk) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'resller_assign_domain');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="domain" class="form-control" placeholder="Example: http://partner.logon.com.pk" required>
							</div>
							<div class="form-group position-relative">
								<label class="form-label">Assign Brand Slogan <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_assign_brand_slogan');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="slogan" class="form-control" placeholder="Example: One World Once Connection" required>
							</div>
							<div class="form-group position-relative">
								<label class="form-label">Powerd By <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_powerd_by');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="powerby" class="form-control" placeholder="Example: Powerd By Logon Broadband" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label class="form-label">Brand Heading <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_brand_heading');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mheading" class="form-control" placeholder="Example: Logon" required>
							</div>
							<div class="form-group position-relative">
								<label class="form-label">Upload Brand Logo <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_select_brand_logo');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="file" name="logo" class="form-control" required>
							</div>
							<div class="form-group position-relative">
								<label class="form-label">Upload Background Image <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_background_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="file" name="bgImage" class="form-control" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								@php
								/*dynamic theme load start */
								$theme_loading = DB::table('partner_themes')
								->get();
								@endphp
								<label class="form-label">Select Theme <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_select_theme');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select name="theme_color" id="theme_color" class="form-control" required>
									<option value="">Select Theme</option>
									@php 
									foreach($theme_loading as $v){
									@endphp
									<option value="@php echo $v->color @endphp">
										@php echo $v->color @endphp
									</option>
									@php 
								}
								@endphp
							</select>
						</div>
						<div class="form-group position-relative">
							<label class="form-label">Login Form Alignment <span style="color: red">*</span></label>
							<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_login_form_alignment');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
							<select name="login_alignment" id="login_alignment" class="form-control" required>
								<option value="">Select Alignment</option>
								<option value="center">Center</option>
								<option value="left">left</option>
								<option value="right">Right</option>
							</select>
						</div>
						<div class="form-group position-relative">
							<label class="form-label">Package Name Prefix <span style="color: red">*</span></label>
							<span class="helping-mark" onmouseenter="popup_function(this, 'reseller_package_name_prefix');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
							<input type="text" name="packageName" class="form-control" placeholder="Example: LOGON" required>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group pull-right">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type="text/javascript">
// $(document).ready(function(){
	$('#wizard_form').on('submit', function(e){
		e.preventDefault();
		FromValidate();
		if($(this).valid()) {
			console.log('form is valid')
		} else {
			console.log('form is not valid')
		}
// var result = FromValidate();
// console.log(result);
});
	function checkavailablereseller(id) {
		var val=$('#resellerid').val();
		$('#resusername').val(val);
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'resellerid='+id,
				success: function(data){
// For Get Return Data
$('#reselleroutput').html(data);
}
});
		}
		else{
			$('#reselleroutput').html('');
		}
	}
	function checkavailableuser(resusername) {
		var val=$('#resusername').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('users.checkunique.ajax.post')}}",
				data:'username='+resusername,
				success: function(data){
// For Get Return Data
$('#availableresuser').html(data);
}
});
		}
		else{
			$('#availableresuser').html('');
		}
	}
</script>