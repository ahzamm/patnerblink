<div aria-hidden="true"   role="dialog" tabindex="-1" id="add_contractor_trader_profile_modal" class="modal fade" style=" display: none; z-index: 1111;margin-top:35px;">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title" style="text-align: center;color: white"> Add Contractor/Trader Internet Profile
               <span class="info-mark" onmouseenter="popup_function(this, 'nas_form_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h4>
         </div>
         <div class="modal-body">
            <form id="addNewContTradProfile">
               @csrf
               <div class="row">
                  <div class="col-md-12">
                     <div class="form-group position-relative">
                        <label  class="form-label" for="type">Select (BRAS/NAS) <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'nas_brand_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <select name="nas" class="form-control" id="brands">
                           <option value="">Select BRAS/NAS</option>
                           <?php foreach($naslist as $key => $value){ ?>
                              <option><?= $value->shortname;?></option>
                           <?php } ?>
                        </select>
                     </div>
                     <div class="form-group position-relative">
                        <label  class="form-label">Contractor Internet Profile <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'nas_port_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" value="" name="contractor_profile" class="form-control" placeholder="Example: Contractor"  required >
                     </div>
                     <div class="form-group position-relative" style="position:relative">
                        <label  class="form-label">Trader Profile <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'nas_secret_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" name="trader_profile" class="form-control" placeholder="Example: Trader" required >
                        <i class="fa fa-eye-slash nas_pass" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('nas_pass');"> </i>
                     </div>
                  </div>
                  <div class="col-xs-12">
                     <div class="pull-right ">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                     </div>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>