<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_support" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8">
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					<h4 class="modal-title" style="text-align: center; color: white"> Helpdesk Agent Form</h4>
				</div>
				<div class="modal-body">
					<form action="{{route('admin.management.support.post',['status'=>'support'])}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">First Name <span style="color: red">*</span></label>
									<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Last Name <span style="color: red">*</span></label>
									<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Email <span style="color: red">*</span></label>
									<input type="email" name="mail" class="form-control"  placeholder="info@logon.com.pk" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
									
									<input type="text" name="mobile_number"  data-mask="9999-9999999" class="form-control"  placeholder="0321-1234567" required>
									
								</div>
								
								<div class="form-group">
									<label  class="form-label">Landline Number</label>
									
									<input type="text" name="land_number"  class="form-control" data-mask="(999)99999999" placeholder="(021)12345678" >
									
								</div>
								<div class="form-group">
									<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
									
									<input type="text" name="nic" class="form-control"  data-mask="99999-9999999-9" placeholder="00000-0000000-0" required>
									
								</div>
								
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">Business Address <span style="color: red">*</span></label>
									<input type="text" name="address" class="form-control"
									placeholder="Business Address" 	required		>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Username <span style="color: red">*</span></label>
									<input type="text" name="username" class="form-control" placeholder="Username" >
									
								</div>
								<div class="form-group" style="position:relative">
									<label  class="form-label">Password <span style="color: red">*</span></label>
									<input type="Password" name="password" class="form-control"  placeholder="Must be 8 characters long" required >
									<i class="fa fa-eye-slash old_password" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('password');"> </i>
								</div>
								<div class="form-group" style="position:relative">
									<label  class="form-label">Retype Password <span style="color: red">*</span></label>
									<input type="Password" name="password_confirmation" class="form-control"  placeholder="Must be 8 characters long" required>
									<i class="fa fa-eye-slash c_password" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('c_password');"> </i>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group" style="float: right">
									<button type="submit" class="btn btn-primary">Submit</button>
									<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--  -->
	</div>
</div>
@section('ownjs')
@endsection()