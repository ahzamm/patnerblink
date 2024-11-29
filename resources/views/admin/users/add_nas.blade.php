<div aria-hidden="true"   role="dialog" tabindex="-1" id="add_nas_model" class="modal fade" style=" display: none; z-index: 1111;margin-top:35px;">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
            <h4 class="modal-title" style="text-align: center;color: white"> Nas Form
               <span class="info-mark" onmouseenter="popup_function(this, 'nas_form_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h4>
         </div>
         <div class="modal-body">
            <form  
            action="{{route('admin.router.post')}}" method="POST">
            @csrf
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group position-relative">
                     <label for="" class="form-label">NAS IP Address <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_ip_address_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" value="" name="nasname" class="form-control" id="" placeholder="Example: 192.168.100.100" required>
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">NAS Name <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" value="" name="server" class="form-control"  placeholder="Example: Mikrotik-KHI1" >
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">NAS Shortname <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_shortname_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" value="" name="shortname" class="form-control"  placeholder="Example: MT-KHI" required>
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label" for="type">Select (BRAS) Brand <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_brand_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <select name="type" class="form-control" id="brands">
                        <option value="">Select (BRAS) Brand</option>
                        <option value="Juniper">Juniper</option>
                        <option value="Cisco">Cisco</option>
                        <option value="Mikrotik">Mikrotik</option>
                        <option value="Huawei">Huawei</option>
                        <option value="Nortel">Nortel</option>
                        <option value="Tenda">Tenda</option>
                        <option value="Asus">Asus</option> 
                        <option value="Netgear">Netgear</option>
                     </select>
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label" for="type">Carrier <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_carrier_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <select name="carrier" class="form-control" id="brands">
                        <option value="">Select</option>
                        <option value="cyber">Cyber</option>
                        <option value="logon">Logon</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group position-relative">
                     <label  class="form-label">Assign (NAS) Port <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_port_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" value="" name="ports" class="form-control"  placeholder="Example: 1812" required >
                  </div>
                  <div class="form-group position-relative" style="position:relative">
                     <label  class="form-label">Assign (NAS) Secret <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_secret_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="password" value="" name="secret" placeholder="Example: 123456"class="form-control" id="nas_pass"  required autocomplete="off">
                     <i class="fa fa-eye-slash nas_pass" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('nas_pass');"> </i>
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">Assign Community <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_community_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text"  value="" name="community" class="form-control" placeholder="Example: MKT"  >
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">Description <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" value="" name="description" class="form-control" placeholder="Example: Karachi-DataCenter" >
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">Radius Server Source (IP) <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" name="radius_src_ip" class="form-control" placeholder="192.168.100.100" >
                  </div>
                  <div class="form-group position-relative">
                     <label  class="form-label">API Port <span style="color: red">*</span></label>
                     <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                     <input type="text" name="api_port" class="form-control" placeholder="1700" >
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