<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_user" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center; color: white">Management Account Member Form
					<span class="info-mark" onmouseenter="popup_function(this, 'admin_managment_user_form_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.AccessManagement.store')}}" method="POST" autocomplete="off">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">First Name  <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Last Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="tel" name="nic" class="form-control"  data-mask="99999-9999999-9" placeholder="00000-0000000-0" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" class="form-control"  placeholder="info@gmail.com" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Address" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Assign Department <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dpartment_assgin');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select name="status" id="" class="form-control" required>
									<option value="" selected>Assign Department</option>
									<option value="admin">Adminstrator</option>
									<option value="noc">Noc Department</option>
									<option value="account">Account Department</option>
									<option value="support">Support Department</option>
									<option value="eng">Engineering Department</option>
									<option value="tech">IT Department</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control" placeholder="0321-1234567"  data-mask="9999 9999999" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="land_number" class="form-control" placeholder="(021)12345678" data-mask="(999)9999999">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'username');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" class="form-control"  placeholder="Username" name="username" >
							</div>
							<div class="form-group position-relative" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="Password" name="password" class="form-control" id="pass_mngmt" placeholder="Must be 8 characters long" required>
								<i class="fa fa-eye-slash pass_mngmt" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('pass_mngmt');"> </i>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group" style="float: right">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>