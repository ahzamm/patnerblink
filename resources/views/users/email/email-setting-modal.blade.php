<div class="modal fade" id="emailSettingModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Email Setting</h4>
      </div>
      <div class="modal-body text-center">
        <ul class="nav nav-tabs">
          <li class="active"><a data-toggle="tab" href="#updateemail">Update Email</a></li>
          <li><a data-toggle="tab" href="#incoming">Incoming Emails</a></li>
          <li><a data-toggle="tab" href="#outgoing">Outgoing Emails</a></li>
        </ul>
        <div class="tab-content">
          <div id="updateemail" class="tab-pane fade in active">
            <form>
              @csrf
              <div class="row register-form">
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Email Address <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Email Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="city_name" class="form-label pull-left">Department <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>Helpdesk</option>
                        <option>High Management</option>
                        <option>Maintenance</option>
                        <option>NOC</option>
                        <option>ODN</option>
                        <option>Sales</option>
                        <option>Support</option>
                        <option>Accounts</option>
                        <option>SquadCloud</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Priority <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>Low</option>
                        <option>Normal</option>
                        <option>High</option>
                        <option>Emergency</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Help Topic <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Description <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <textarea class="form-control" rows="5"></textarea>
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
          <div id="incoming" class="tab-pane">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Hostname <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="" name="" class="form-control" id="" placeholder="" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Port Number <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="" name="" class="form-control" id="" placeholder="" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Mail Folder <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="" name="" class="form-control" id="" placeholder="" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Protocol <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select class="form-control">
                        <option>IMAP</option>
                        <option>POP</option>
                    </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Authentication <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <select class="form-control">
                    <option>Select Authentication</option>
                    <option>Basic Authentication</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <h4 style="text-align: left;font-weight: bold;background: #ddd;padding: 5px;">Email Fetching</h3>
              </div>
              <div class="col-md-12">
                <div class="position-relative text-left">
                  <label for="" class="form-label pull-left">Status <span style="color: red">*</span></label>
                  <div style="display:inline-block;margin-left:20px;">
                    <input type="radio" name="status" id="statusEnable">
                    <label for="statusEnable"> Enable</label>
                  </div>
                  <div style="display:inline-block">
                    <input type="radio" name="status" id="statusDisable">
                    <label for="statusDisable"> Disable</label>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label pull-left">Fetch Frequency</label>
                  <input type="number" class="form-control" placeholder="Fetch frequency in minutes">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label pull-left">Emails per Fetch</label>
                  <input type="number" class="form-control" placeholder="Number of email fetched">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Fetched Emails <span style="color: red">*</span></label>

                  <select class="form-control">
                    <option>Archived</option>
                    <option>Delete Emails</option>
                    <option>Do Nothing</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div id="outgoing" class="tab-pane">
            <div class="row">
            <div class="col-md-12">
                <h4 style="text-align: left;font-weight: bold;background: #ddd;padding: 5px;">SMTP Settings</h3>
              </div>
              <div class="col-md-12">
                <div class="position-relative text-left">
                  <label for="" class="form-label pull-left">Status <span style="color: red">*</span></label>
                  <div style="display:inline-block;margin-left:20px;">
                    <input type="radio" name="status" id="statusEnable">
                    <label for="statusEnable"> Enable</label>
                  </div>
                  <div style="display:inline-block">
                    <input type="radio" name="status" id="statusDisable">
                    <label for="statusDisable"> Disable</label>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label pull-left">Hostname</label>
                  <input type="number" class="form-control" placeholder="smtp.stackmail.com">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label pull-left">Port Number</label>
                  <input type="number" class="form-control" placeholder="Port Number">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Authentication <span style="color: red">*</span></label>
                  <select class="form-control">
                    <option>Same as remote mailbox</option>
                    <option>No authentication requried</option>
                    <option>Basic Authentication</option>
                  </select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group position-relative">
                  <label for="" class="form-label pull-left">Header Spoofing <span style="color: red">*</span></label>
                  <div class="pull-left" style="margin-left:20px">
                    <input type="checkbox" class="" id="input_spoofing">
                    <label class="form-label" for="input_spoofing">Allow for this email</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>