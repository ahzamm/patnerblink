<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_user_support" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center; color: white"> Helpdesk Agent Form
                <span class="info-mark" onmouseenter="popup_function(this, 'helpdesk_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('users.manage.store')}}" method="POST" autocomplete="off">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text"  name="fname" class="form-control"  placeholder="First Name" required>
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
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control" placeholder="0321-1234567"  data-mask="9999 9999999" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number</label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="land_number" class="form-control" placeholder="(021)12345678" data-mask="(999)9999999">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Department <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'dpartment_assgin');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="address" class="form-control"  placeholder="Department" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'user_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" class="form-control"  placeholder="Username" name="username" >
							</div>
							<div class="form-group position-relative" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="Password" name="password" class="form-control"  placeholder="Must be 8 characters long" required id="add_password">
								<i class="fa add_password fa-eye-slash" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass('add_password')"> </i>
							</div>
						</div>
						<!--  -->
						<div class="col-md-12 text-right">
							<div class="form-group">
								<button type="submit" class="btn btn-primary" onclick="errorMessage();">Submit</button>
								<button data-dismiss="modal" class="btn btn-danger" >Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>