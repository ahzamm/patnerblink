<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: June, 2023
* Last Updated: 08th June, 2023
* Author: Talha Fahim & Hasnain Raza <info@squadcloud.co>
*-->
<!-- Code Onset -->
<div class="modal fade" id="servicesUpdateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="text-align: center;color: white">Update Managed Server</h4>
      </div>
      <div class="modal-body text-center">
        <div class="" id="tblData">
          <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
          </div>
          <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
            <ul></ul>
          </div>
          <div class="">
            <form id="update-service" method="POST" action="{{route('admin.services.update')}}">
              @csrf
              <input type="hidden" name="id" id="id">
              <div class="row register-form">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Server Host Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_hostname_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="server_name" id="server_name" class="form-control" placeholder="Example: Radus Server" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Domain Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_domain_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="domain_name" id="domain_name" class="form-control"  placeholder="Example: xyz.com" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Host (IP Address) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_ipaddress_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="ip_address" id="ip_address" class="form-control" placeholder="Example: 192.168.0.10" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">API (URL) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_api_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="api_url" id="api_url" class="form-control"  placeholder="Example: https://xyz.com/index.php" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Host (Username) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_username_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="username" id="username" class="form-control" placeholder="Example: root" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Host (Password) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_password_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="password" value="" name="password" id="password" class="form-control"  placeholder="Example: abc123" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Host (SSH Port) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_ssh_port_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="port" id="port" class="form-control" placeholder="Example: 22" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Managed Services <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_manage_services_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control" name="service" id="service">
                      <option>Select Managed Service</option>
                      <option>MySql</option>
                      <option>Ngnix</option>
                      <option>FreeRadius</option>
                      <option>PHP7.3</option>
                      <option>PHP7.4</option>
                      <option>PHP8.0</option>
                      <option>PHP8.1</option>
                      <option>PHP8.2</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Assign Start-Service (Command) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="start_cmd" id="start_cmd" class="form-control" id="" placeholder="" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Assign Stop-Service (Command) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="stop_cmd" id="stop_cmd" class="form-control" id="" placeholder="" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Assign Restart-Service (Command) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'server_management_add_service_command_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="restart_cmd" id="restart_cmd" class="form-control" id="" placeholder="" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="pull-right">
                    <input type="submit" class="btn btn-primary btn-submit"  value="Submit"/>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Code Finalize -->