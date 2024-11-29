<div aria-hidden="true"  role="dialog" tabindex="-1" id="my-sub-menu" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Sub-Menu Form</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.Management.submenu.store')}}" method="POST" style="margin-bottom:0">
					@csrf
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="form-label">Sub Menu</label>
								<input type="text" name="submenu" class="form-control" required>
							</div>
							<div class="form-group">
								<label class="form-label">Main Menu</label>
								<select name="menu_id" id="lorder" class="form-control" required>
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
								<input type="text" name="route" class="form-control" required>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="pull-right">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>