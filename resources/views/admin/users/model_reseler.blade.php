<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-2" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8">
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					<h4 class="modal-title" style="text-align: center;color: white"> Reseller Form</h4>
				</div>
				<div class="modal-body">
					<form action="{{route('admin.user.post',['status' => 'reseller'])}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-4">
								<div class="form-group" style="display: none">
									<label  class="form-label">Manager-ID</label>
									<select class="form-control" name="manager_id" required>
										@foreach($managerIdList as $manager)
										<option value="{{$manager->manager_id}}">{{$manager->manager_id}}</option>
										@endforeach
									</select>
								</div>
								<div class="form-group" >
									<label class="form-label">Reseller ID</label><span style="float: right;" id="availablereseller"></span>
									<input type="text" name="resellerid" id="resellerid" class="form-control" placeholder="Reseller Id" required onkeyup="checkavailablereseller(this.value)">
								</div>
								<div class="form-group">
									<label class="form-label">First Name</label>
									<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Last Name</label>
									<input type="text" name="lname" class="form-control"  placeholder="Last Name" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Business Address</label>
									<input type="text" name="address" class="form-control"  placeholder="Business Address" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">Mobile Number</label>
									<input type="text" name="mobile_number" class="form-control"  data-mask="9999 9999999" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Landline Number</label>
									<input type="text" name="land_number" class="form-control"  data-mask="(999)99999999" >
								</div>
								<div class="form-group">
									<label  class="form-label">CNIC Number</label>
									<input type="text" name="nic" class="form-control" data-mask="99999-9999999-9" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Email Address</label>
									<input type="email" name="mail" class="form-control"  placeholder="info@gmail.com" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label  class="form-label">Assign Reseller Area</label>
									<input type="text" name="area" class="form-control"  placeholder="Assign Reseller Area " required>
								</div>
								<div class="form-group">
									<label  class="form-label">Username</label><span style="float: right;" id="availableuser"></span>
									<input type="text" name="username" class="form-control"  placeholder="Username" id="username" required onkeyup="checkavailableuer(this.value)">
								</div>
								<div class="form-group">
									<label  class="form-label">Password</label>
									<input type="Password" name="password" class="form-control"  placeholder="Must be 8 characters long" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Retype Password</label>
									<input type="Password" name="password_confirmation" class="form-control"  placeholder="Must be 8 characters long" required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary">Submit</button>
							<button type="" class="btn btn-primary" data-dismiss="modal">Cancel</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@section('ownjs')
<script type="text/javascript">
	function checkavailablereseller(resellerid) {
		var val=$('#resellerid').val();
		if(val.length > 0){
			$.ajax({
				type: "POST",
				url: "{{route('admin.check.available.post')}}",
				data:'resellerid='+resellerid,
				success: function(data){
// For Get Return Data
$('#availablereseller').html(data);
}
});
		}
		else{
			$('#availablereseller').html('');
		}
	}
	function checkavailableuer(username) {
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
</script>
@endsection