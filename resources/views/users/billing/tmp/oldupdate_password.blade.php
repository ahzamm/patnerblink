<div aria-hidden="true"  role="dialog" tabindex="-1" id="edit_password" class="modal fade" style="display: none;">
	<div class="col-md-2"> </div>
	<div class="col-md-8"> 
		<div class="modal-dialog" style="width: 100%">
			<div class="modal-content">
				<div class="modal-header" style="background-color: #4878bf;">
					<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
					<h4 class="modal-title" style="text-align: center; color: white"> Change Password</h4>
				</div>
				<div class="modal-body">
					<form method="POST" action="{{route('users.billing.update_password')}}">
						@csrf
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="form-group">
									<label  class="form-label">Username</label>
									<select class="form-control" id="username-select" name="username" required >
										<option value="">select Username</option>
										
										<option value=""></option>
										
									</select>
									
								</div>
								<div class="form-group">
									<label  class="form-label">Password</label>
									<input type="Password" name="password" class="form-control"  placeholder="******" required>
								</div>
								<div class="form-group">
									<label  class="form-label">Retype Password</label>
									<input type="Password" name="password_confirmation" class="form-control"  placeholder="*****" required>
								</div>

							</div>
							
							<!--  -->
							<div class="col-md-8">
								<div class="form-group">
									<button type="submit" class="btn btn-primary">Update</button>
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

