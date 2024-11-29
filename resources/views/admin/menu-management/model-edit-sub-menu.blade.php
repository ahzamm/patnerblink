<div aria-hidden="true"  role="dialog" tabindex="-1" id="my-edit-sub-menu" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Edit Sub-Menu Form</h4>
			</div>
			<div class="alert alert-danger print-error-msg" style="display:none">
				<ul></ul>
			</div>
			<div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
				<ul></ul>
			</div>
			<div class="modal-body">
				<form action="" method="POST" id="update-sub-menu" name="s_menu_id" style="margin-bottom: 0">
					@csrf
					<input type="hidden" id="sub-menu-id" value="" name="sub_menu_id">
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="form-label">Sub Menu</label>
								<input type="text" name="submenu" class="form-control" required id="u-sub-menu">
							</div>
							<div class="form-group">
								<label class="form-label">Main Manu</label>
								<select name="menu_id" id="u-menu-id" class="form-control" required>
									<option value="">Select Parent Menu</option>
									@php
									$get_menus = DB::table('menus')->get(['menu','id']);
									@endphp
									@foreach($get_menus as $menu)
									<option value="{{$menu->id}}">{{$menu->menu}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label class="form-label">Route</label>
								<input type="text" name="route" class="form-control" required id="u-route">
							</div>
							<div class="form-group">
								<label class="form-label">Order</label>
								<input type="text" name="priority" class="form-control" id="u-priority" required value="">
								{{$errors->first('priority')}}
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group pull-right">
								<button type="submit" class="btn btn-primary update-sub-menu">Submit</button>
								<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>