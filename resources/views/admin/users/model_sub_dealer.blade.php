
<div aria-hidden="true"  role="dialog" tabindex="-1" id="myModal-4" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
	<div class="modal-dialog" style="width: 100%">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #4878bf;">
			<button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
				<h4 class="modal-title" style="text-align: center;color: white"> Add Sub-Dealer</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.user.post',['status' => 'reseller'])}}" method="POST">
				
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label  class="form-label">Manager-ID</label>
								
								<input type="text" name="manager_id" class="form-control" placeholder="Manager-ID" readonly required>
																				
								</div>
								<div class="form-group" >
									<label class="form-label">Reseller-ID</label>
									<input type="text" name="resellerid" class="form-control" placeholder="Reseller-Id" readonly required>
									</div>
									
									<div class="form-group">
										<label class="form-label">First Name</label>
										<input type="text" name="fname" class="form-control"  placeholder="First Name" required>
									</div>

									<div class="form-group">
										<label  class="form-label">Last Name</label>
										<input type="text" name="lname" class="form-control"  placeholder="lastname" required>
									</div>

									<div class="form-group">
										<label  class="form-label">Address</label>
										<input type="text" name="address" class="form-control"  placeholder="Address" required>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label  class="form-label">Mobile Number</label>
										<input type="text" name="mobile_number" class="form-control"  data-mask="9999 9999999" required>
									</div>

									
									<div class="form-group">
										<label  class="form-label">Landline Number</label>
										<input type="text" name="land_number" class="form-control"  data-mask="(999)99999999" required>
									</div>

									<div class="form-group">
										<label  class="form-label">CNIC</label>
										<input type="text" name="nic" class="form-control" data-mask="99999-9999999-9" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Email</label>
										<input type="email" name="mail" class="form-control"  placeholder="info@lbi.net.pk" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Sub-dealer Area</label>
										<input type="text" name="area" class="form-control"  placeholder="Area " required>
									</div>

								</div>


								<div class="col-md-4">

									


								
									<div class="form-group">
										<label  class="form-label">Username</label>
										<input type="text" name="username" class="form-control"  placeholder="username" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Password</label>
										<input type="text" name="password" class="form-control"  placeholder="****" required>
									</div>
									<div class="form-group">
										<label  class="form-label">Retype Password</label>
										<input type="text" name="password_confirmation" class="form-control"  placeholder="****" required>
									</div>
								</div>
								<!--  -->
								<div class="col-md-8">
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Submit</button>
										<button type="" class="btn btn-primary" data-dismiss="modal">Cancel</button>

									</div>
								</div>
								<!--  -->
								
								<!--  -->
							</div>
						</form>
					</div>
				</div>
			</div>
			<!--  -->
			</div>
		</div>

