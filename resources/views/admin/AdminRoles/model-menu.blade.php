<?php
use App\model\admin\AdminMenu;
?>
<div aria-hidden="true"  role="dialog" tabindex="-1" id="my-menu" class="modal fade" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Admin Main Menu Form</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.Management.adminmenu.store')}}" method="POST" id="menu-store" style="margin-bottom: 0">
					@csrf
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group">
								<label class="form-label">Main Menu</label>
								<input type="text" name="menu" class="form-control" required>
								{{$errors->first('menu')}}  
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label class="form-label">Icon</label>
								<input type="text" name="icon" class="form-control" required>
							</div>
							@php
							$get_max =  DB::table('admin_menus')->select(DB::raw('count(*)+1 as total'))->get();
							@endphp
							@foreach($get_max as $max)								
							<div class="form-group">
								<label class="form-label">Order</label>
								<input type="text" name="priority" class="form-control" id="u-priority" required value="{{$max->total}}">
								@endforeach
								{{$errors->first('priority')}}
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<label class="form-label">Has Sub-Menu</label>
								<input type="checkbox" name="has_submenu" class="form-control" value="1" style="width:100px; margin-left: 0">
							</div>							
							<div class="col-md-12">
								<div class="form-group pull-right">
									<button type="submit" class="btn btn-primary btn-submit">Submit</button>
									<button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div>	
				</form>
			</div>
		</div>
	</div>
</div>