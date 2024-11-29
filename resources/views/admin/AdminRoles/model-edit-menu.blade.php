<div aria-hidden="true" role="dialog" tabindex="-1" id="my-edit-menu" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Edit Admin Main Menu Form</h4>
			</div>
			<div class="alert alert-danger print-error-msg" style="display:none">
				<ul></ul>
			</div>
			<div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
				<ul></ul>
			</div>
			<div class="modal-body">
				<form  method="POST" id="update-menu" name="menus_id" style="margin-bottom: 0">
					@csrf
					<input type="hidden" id="menu-id-data" value="" name="menu_id">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<label class="form-label">Menu</label>
								<input type="text" name="menu" class="form-control" required id="u-m-menu" value="">
								{{$errors->first('menu')}}
							</div>
							<div class="form-group">
								<label class="form-label">Icon</label>
								<input type="text" name="icon" class="form-control" id="u-icon" required value="">
							</div>
							<div class="form-group">
								<label class="form-label">Order</label>
								<input type="text" name="priority" class="form-control" id="u-priority" required value="">
								{{$errors->first('priority')}}
							</div>
							<div class="form-group">
								<label class="form-label">Has Sub-Menu</label>
								<input type="checkbox" name="has_submenu" class="form-control submenu" id="u-has_submenu" style="margin-left:0; width: 100px;">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group pull-right">
								<button type="submit" class="btn btn-primary btn-submit update-menu">Submit</button>
								<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
</div>