<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-3" class="modal fade" style="display: none;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
				<h4 class="modal-title" style="text-align: center;color: white">Manager Form</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.user.post',['status' => 'manager'])}}" method="POST">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label for="" class="form-label">Manager ID <span style="color: red">*</span></label><span style="float: right;" id="availablemanager"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'manager_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="manager_id" id="manager_id" class="form-control"  placeholder="Manager ID" autocomplete="false" required onkeyup="checkavailablemanager(this.value)">
								@csrf
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">First Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
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
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Mobile Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="mobile_number" class="form-control" data-mask="9999 9999999" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Landline Number</label>
								<input type="text" name="land_number" class="form-control"  data-mask="(999)99999999" >
								<span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">CNIC Number <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="nic" class="form-control" data-mask="99999-9999999-9" required>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Email Address <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="email" name="mail" class="form-control"  placeholder="info@gmail.com" required>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Username <span style="color: red">*</span></label><span style="float: right;" id="availableuser"></span>
								<span class="helping-mark" onmouseenter="popup_function(this, 'username');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="username" class="form-control"  placeholder="Username" id="username" autocomplete="false" required onkeyup="checkavailableuser(this.value)">
							</div>
							<div class="form-group position-relative" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="Password" name="password" id="pass3" class="form-control"  placeholder="Must be 8 characters long" required>
								<i class="fa fa-eye-slash pass3" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('pass3');"> </i>
							</div>
							<div class="form-group" style="position:relative">
								<label  class="form-label">Retype Password <span style="color: red">*</span></label>
								<input type="Password" name="password_confirmation" id="pass4" class="form-control"  placeholder="Must be 8 characters long" required>
								<i class="fa fa-eye-slash pass4" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('pass4');"> </i>
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
@section('ownjs')
<script type="text/javascript">
	function checkavailableuser(username) {   
		var val=$('#username').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('admin.check.available.post')}}",
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
	function checkavailablemanager(managerid) {   
		var val=$('#manager_id').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('admin.check.available.post')}}",
				data:'managerid='+managerid,
				success: function(data){
// For Get Return Data
$('#availablemanager').html(data);
}
});
		}
		else{
			$('#availablemanager').html('');
		} 
	}
</script>
@endsection