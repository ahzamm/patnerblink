<div aria-hidden="true"   role="dialog" tabindex="-1" id="add_profile_model" class="modal fade" style=" display: none; z-index: 1111;margin-top:35px;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Internet Package Form
					<span class="info-mark" onmouseenter="popup_function(this, 'internet_package_form_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
				</h4>
			</div>
			<div class="modal-body">
				<form action="{{route('admin.profile.post')}}" method="POST">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Select (Reagroupreply) Profile <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_radgroupreply_profile_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select name="groupname" class="form-control" required>
									<option value="">Select Profile</option>
									<?php foreach($radgroupreply as $radGroupValue){?>
										<option><?= $radGroupValue->groupname;?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Internet Profile Name <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="name" class="form-control"  placeholder="Example: Bitcoin" required>
							</div>
							<input type="hidden" name="sst" class="form-control"  placeholder="Sindh Sales Tax" value="0">
							<input type="hidden" name="adv_tax" class="form-control"  placeholder=" Advance Income Tax" value="0">
							<input type="hidden" name="charges" class="form-control"  placeholder="Rates (Rs.)" value="0">
							<input type="hidden" name="final_rates" class="form-control"  placeholder="Final Rates (Rs.)" value="0" min="1" max="10000">
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Package Color </label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_color_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="sel-color" style="background-color:#da4237;"></i>
									</span>
									<input type="text" name="color" class="form-control colorpicker " data-format="hex" value="#da4237">
								</div>                
							</div>
							<input type="hidden" name="sstB" class="form-control"  placeholder="Sindh Sales Tax" value="0">
							<input type="hidden" name="adv_taxB" class="form-control"  placeholder="Advance Income Tax" value="0" min="1" max="10000">
							<input type="hidden" name="chargesB" class="form-control"  placeholder="Rates (Rs.)" value="0">
							<input type="hidden" name="final_ratesB" class="form-control"  placeholder="Final Rates (Rs.)" value="0">
						</div>
						<div class="col-md-3">
							<input type="hidden" name="sstc" class="form-control"  placeholder="Sindh Sales Tax" value="0">
							<input type="hidden" name="adv_taxC" class="form-control"  placeholder="Advance Income Tax" value="0" min="1" max="10000">
							<input type="hidden" value="0" name="chargesC" class="form-control" placeholder="Rates (Rs.)" >
							<input type="hidden" name="final_ratesC" class="form-control"  placeholder="Final Rates (Rs.)" value="0" min="1" max="10000">
						</div>
					</div>
					<hr style="border-top: 1px solid #9b9b9b;">
					<div class="row">
						<div class="header_view">
							<h2 style="font-size: 26px; margin-bottom: 20px;">Internet Profile</h2>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Internet Profile Type <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_type_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<select name="profile_type" class="form-control" id="profile-type">
									<option value="" >Select Profile Type</option>
									<option value="cdn" id="cdn" >CDN</option>
									<option value="straight" id="straight">Straight</option>
								</select>
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Facebook Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_facebook_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_facebook" class="form-control social" placeholder="Example: (10)" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Bundle Internet Speed (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_bundle_bandwidth_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="bw_show" class="form-control"  placeholder="Example: Bandwidth (15)" required min="1" max="10000" >
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Netflix Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_netflix_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_netflix" class="form-control social" placeholder="Example: (10)" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Internet Speed (IPT) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_ipt_bandwidth_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_internet" class="form-control" placeholder="Example: Bandwidth (15)" min="0">
							</div>
							<div class="form-group position-relative">
								<label  class="form-label">Data Quota Limit (Cage) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_data_quota_limite_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="quota_limit" class="form-control"  placeholder="Assign (kb) Format like (10240)" required min="1" max="10000">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Youtube Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_youtube_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_youtube" class="form-control social" placeholder="Example: (10)" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Cunsumer Base Price (PKR) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_consumer_base_price_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="base_price" class="form-control" placeholder="Example: 1000" min="0" step="0.01">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="form-group" style="float: right">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>