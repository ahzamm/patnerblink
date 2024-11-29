<div aria-hidden="true"  role="dialog" tabindex="-1" id="edit_password" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center; color: white"> Change Password</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.users.update_password')}}" method="POST">
					@csrf
					<div class="row">
						<?php $authUsername = Auth::user()->username;?>
						<div class="col-md-12">
							<div class="form-group">
								<label  class="form-label">Username</label>
								<input type="text"  name="username" class="form-control" value="{{$authUsername}}" placeholder="username" readonly required />
							</div>
							<div class="form-group" style="position:relative">
								<label  class="form-label">Password <span style="color: red">*</span></label>
								<input type="Password" name="password" id="pass1" class="form-control"  placeholder="Must be 8 characters long" required>
								<i class="fa fa-eye-slash pass1" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('pass1');"> </i>
							</div>
							<div class="form-group" style="position:relative">
								<label  class="form-label">Retype Password <span style="color: red">*</span></label>
								<input type="Password" name="password_confirmation" id="pass2" class="form-control"  placeholder="Must be 8 characters long" required>
								<i class="fa fa-eye-slash pass2" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('pass2');"> </i>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group pull-right">
								<button type="submit" class="btn btn-primary">Update</button>
								<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@section('ownjs')
@endsection
<!-- Code Finalize -->