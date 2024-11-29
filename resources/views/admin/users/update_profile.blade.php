<div aria-hidden="true"   role="dialog" tabindex="-1" id="update_profile_model" class="modal fade" style=" display: none; z-index: 1111;margin-top:35px;" id="">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="alert alert-danger print-error-msg" style="display:none">
				<ul></ul>
			</div>
			<div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
				<ul></ul>
			</div>
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title" style="text-align: center;color: white">Internet Package Form Update</h4>
			</div>
			<div class="modal-body">
				<form  id="pkg-form-update">
					@csrf
					<div class="row">
						<div class="col-md-4">
							<input type="hidden" name="id" id="update-id">
							<div class="form-group position-relative">
								<label  class="form-label">(Reagroupreply) Profile</label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'select_radgroupreply_profile_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="groupname" class="form-control"  placeholder="Ex: Bandwidth (15)" required min="1" max="10000" id="update-groupname" readonly>
							</div>
							<input type="hidden" name="code" class="form-control"  placeholder="Package Prefix" id="update-code">
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Internet Profile Name</label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="name" class="form-control"  placeholder="Package Name" required id="update-name" readonly>
							</div>
							<input type="hidden" name="sst" class="form-control"  placeholder="Sindh Sales Tax" value="0" id="update-sst">
							<input type="hidden" name="adv_tax" class="form-control"  placeholder=" Advance Income Tax" value="0" id="update-adv_tax">
							<input type="hidden" name="charges" class="form-control"  placeholder="Rates (Rs.)" value="0" id="update-charges">
							<input type="hidden" name="final_rates" class="form-control"  placeholder="Final Rates (Rs.)" value="0" min="1" max="10000" id="update-final_rates">
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Package Color <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_color_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<div class="input-group">
									<span class="input-group-addon">
										<i class="sel-color" style="background-color:#da4237;"></i>
									</span>
									<input type="text" name="color" class="form-control colorpicker " data-format="hex" value="#da4237" id="update-color">
								</div>                
							</div>
							<input type="hidden" name="sstB" class="form-control"  placeholder="Sindh Sales Tax" value="0" id="update-sstB">
							<input type="hidden" name="adv_taxB" class="form-control"  placeholder="Advance Income Tax" value="0" min="1" max="10000" id="update-adv_taxB">
							<input type="hidden" name="chargesB" class="form-control"  placeholder="Rates (Rs.)" value="0" id="update-chargesB">
							<input type="hidden" name="final_ratesB" class="form-control"  placeholder="Final Rates (Rs.)" value="0" id="update-final_ratesB">
						</div>
						<div class="col-md-3">
							<input type="hidden" name="sstC" class="form-control"  placeholder="Sindh Sales Tax" value="0" id="update-sstC">
							<input type="hidden" name="adv_taxC" class="form-control"  placeholder="Advance Income Tax" value="0" min="1" max="10000" id="update-adv_taxC">
							<input type="hidden" name="chargesC" class="form-control"  placeholder="Rates (Rs.)" value="0" id="update-chargesC">
							<input type="hidden" name="final_ratesC" class="form-control"  placeholder="Final Rates (Rs.)" value="0" min="1" max="10000" id="update-final_ratesC">
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
								<select name="profile_type" class="form-control" id="update-profile-type" >
									<option value="" >Select Profile Type</option>
									<option value="cdn">CDN</option>
									<option value="straight" >Straight</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Bundle Internet Speed (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_bundle_bandwidth_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="bw_show" class="form-control"  placeholder="Ex: Bandwidth (15)" required min="1" max="10000" id="update-bw_show" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Internet Speed (IPT) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_ipt_bandwidth_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_internet" class="form-control" placeholder="Internet" id="update-soc_internet" min="0">	
							</div>
						</div>
						<!-- Youtube -->
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Youtube Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_youtube_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_youtube" class="form-control social-update" placeholder="youtube" id="update-soc_youtube" min="0">
							</div>
						</div>
						<!-- Facebook -->
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Facebook Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_facebook_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_facebook" class="form-control social-update" placeholder="facebook" id="update-soc_facebook" min="0">
							</div>
						</div>
						<!-- Netflix -->
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Netflix Package (MB) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_netflix_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="number" name="soc_netflix" class="form-control social-update" placeholder="Netflix" id="update-soc_netflix" min="0">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Data Quota Limit (Cage) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_data_quota_limite_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="quota_limit" class="form-control"  placeholder="Quota Limit" required min="1" max="10000" id="update-quota_limit">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group position-relative">
								<label  class="form-label">Cunsumer Base Price (PKR) <span style="color: red">*</span></label>
								<span class="helping-mark" onmouseenter="popup_function(this, 'internet_profile_consumer_base_price_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
								<input type="text" name="base_price" id="update-base_price" class="form-control"  placeholder="Base Price" required min="0" step="0.01">
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group" style="float: right">
								<button type="submit" class="btn btn-primary" id="store-update-btn">Update</button>
								<button class="btn btn-danger" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>