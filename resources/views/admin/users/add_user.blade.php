<div aria-hidden="true"  role="dialog" tabindex="-1" id="add_user_model" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
	<div class="modal-dialog" style="width: 100%">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #4878bf;">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center; color: white"> Add User</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.user.post',['status' => 'dealer'])}}" method="POST">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label  class="form-label">Manager-ID</label>
								<select class="form-control" required>
									<option>admin</option>
									<option>super-admin</option></select>
								</div>
								<div class="form-group">
									<label  class="form-label">Reseller-ID</label>
									<select class="form-control" required>
										<option>adminlogon</option>
										<option>Maxnet</option></select>
									</div>
									<div class="form-group">
										<label  class="form-label">Dealer ID</label>
										<input type="text" class="form-control"  placeholder="Dealer-ID" required>
									</div>
									<div class="form-group">
										<label  class="form-label">First Name</label>
										<input type="text" class="form-control"  placeholder="First Name" required>
									</div>

									<div class="form-group">
										<label  class="form-label">Last Name</label>
										<input type="text" class="form-control"  placeholder="lastname" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label  class="form-label">Mobile Number</label>
										<input type="text" class="form-control"  placeholder="0300000000" required>
									</div>

									<div class="form-group">
										<label  class="form-label">CNIC</label>
										<input type="text" class="form-control"  placeholder="42201-0000-1" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Landline Number</label>
										<input type="text" class="form-control"  placeholder="021300000" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Email</label>
										<input type="email" class="form-control"  placeholder="info@lbi.net.pk" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Address</label>
										<input type="text" class="form-control"  placeholder="Address" required>
									</div>

								</div>


								<div class="col-md-4">

									<div class="form-group" >
										<label class="form-label">Select Profile</label>
										<select class="selectpicker" multiple data-live-search="true" required>
											<option selected>Lite</option>
											<option>Social</option>
											<option>Smart</option>
											<option>Power </option>
											<option>Super </option>
											<option>Turbo </option>
											<option>Mega </option>
											<option>Jumbo  </option>
											<option>Sonic  </option>
										</select>
									</div>


									<div class="form-group">
										<label  class="form-label">Dealer Area</label>
										<input type="text" class="form-control"  placeholder="Area " required>
									</div>
									<div class="form-group">
										<label  class="form-label">Username</label>
										<input type="text" class="form-control"  placeholder="username" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Password</label>
										<input type="text" class="form-control"  placeholder="****" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Retype Password</label>
										<input type="text" class="form-control"  placeholder="****" required>
									</div>
								</div>
								<!--  -->
								<div class="col-md-8">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Submit</button>
										<button type="submit" class="btn btn-primary">Cancel</button>

									</div>
								</div>
								<!--  -->
								<div class="col-md-4">
									<div class="unpost alert alert-info alert-dismissible" id="inserted" style="float: left;">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<strong> Success! </strong>Post Successfully.
									</div>
								</div>
								<!--  -->
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--  -->
			</div>
		</div>

