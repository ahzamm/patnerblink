<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_user" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
					<h4 class="modal-title" style="text-align: center; color: white"> Add Customers</h4>
				</div>
				<div class="modal-body">
					<form action="{{route('users.user.post',['status' => 'user'])}}" method="POST">
						@csrf
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Manager-ID</label>
									<input type="text" value="{{Auth::user()->manager_id}}" class="form-control" readonly required name="manager_id">
								</div>
								<div class="form-group">
									<label  class="form-label">Reseller-ID</label>
									<input type="text" value="{{Auth::user()->resellerid}}" class="form-control" readonly required name="resellerid">
								</div>
								<div class="form-group">
									<label  class="form-label">Dealer ID</label>
									<input type="text" value="{{Auth::user()->dealerid}}" class="form-control" readonly required name="dealerid">
								</div>
								<div class="form-group">
									<label  class="form-label">Sub-Dealer ID</label>
									<input type="text" value="{{Auth::user()->sub_dealer_id}}" class="form-control" readonly  name="sub_dealer_id">
								</div>


								<div class="form-group">
									<label  class="form-label">Username</label>
									<input type="text" name="username" class="form-control"  placeholder="username" required>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">First Name</label>
									<input type="text" name="fname" class="form-control"  placeholder="firstname" required>
								</div>

								<div class="form-group">
									<label  class="form-label">Last Name</label>
									<input type="text" name="lname" class="form-control"  placeholder="lastname" required>
								</div>
								<div class="form-group">
									<label  class="form-label">CNIC</label>
									<input type="text" name="nic" class="form-control" data-mask="99999-9999999-9" placeholder="99999-9999999-9" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Email</label>
									<input type="email" name="mail" class="form-control"  placeholder="info@lbi.net.pk" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Address</label>
									<input type="text" name="address" class="form-control"  placeholder="Address" required>
								</div>

							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label  class="form-label">Mobile Number</label>
									<input type="text" name="mobile_number" class="form-control"  data-mask="9999 9999999" placeholder="9999 9999999" required>
								</div>

								<div class="form-group">
									<label  class="form-label">Landline | Mobile Number</label>
									<input type="text" name="land_number" class="form-control" >
								</div>
								<div class="form-group">
									<label  class="form-label">Passport No.</label>
									<input type="password" class="form-control" data-mask="AA-9999999" placeholder="AA-9999999" >
								</div>
								<div class="form-group">
									<label  class="form-label">Mac Address</label>
									<input type="text" class="form-control" readonly="" data-mask="99-99-99-99-99-99" placeholder="99-99-99-99-99-99" required>
								</div>
								<div class="form-group">
									<label  class="form-label">User Static IP</label>
									<select class="form-control" required>
										<option selected>None</option>
										<option>192.168.100.1</option>
										<option>192.168.100.78</option>
									</select>
								</div>

							</div>
							<div class="col-md-3">
								
								<div class="form-group">
									<label  class="form-label">Password</label>
									<input type="Password" name="password" class="form-control"  placeholder="****" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Retype Password</label>
									<input type="Password" name="password_confirmation" class="form-control"  placeholder="****" required>
								</div>

								<div class="form-group">
									<label  class="form-label">Select Profile</label>
									<select class="form-control" name="profile">
										
										<option>1024</option>
										
										
									</select>
								</div>

								</div>

								<!--  -->
								
  <div class="col-md-8">
  	<div class="form-group">
  		<button type="submit" class="btn btn-primary">Submit</button>
  		

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

