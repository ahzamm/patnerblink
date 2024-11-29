<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Email Setting</h4>
      </div>
      <div class="modal-body text-center">
        <div class="" id="tblData">
          <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
          </div>
          <div class="alert alert-success success-msg" style="display:none" data-dismiss="alert">
            <ul></ul>
          </div>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#recipient">Recipients</a></li>
            <li><a data-toggle="tab" href="#smtp">SMTP Setting</a></li>
            <li><a data-toggle="tab" href="#imap">IMAP Setting</a></li>
          </ul>
          <div class="tab-content">
            <div id="recipient" class="tab-pane fade in active">
              <div class="row">
                <textarea class="form-control" rows="5" style="margin-bottom: 10px"></textarea>
                <div class="col-md-12">
                  <div class="pull-right">
                    <input type="submit" class="btn btn-primary btn-submit mt-2"  value="Submit"/>
                  </div>
                </div>
              </div>
            </div>
            <div id="smtp" class="tab-pane fade">
              <div class="">
                <form id="" method="POST">
                  @csrf
                  <div class="row register-form">
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">Select Reseller <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <select class="form-control">
                          <option>Logon Broadband</option>
                          <option>Blink Broadband</option>
                          <option>Go Internet</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">Select Logo <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="file" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">SMTP Server <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">SMTP Port <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="number" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">Email ID <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">Password <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="password" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="" class="form-label pull-left">Email Title <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group position-relative">
                        <label for="city_name" class="form-label pull-left">SMTP Encryption <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <select class="form-control">
                          <option>SSL</option>
                          <option>TLS</option>
                        </select>
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
            <div id="imap" class="tab-pane fade">
              <form id="" method="POST">
                @csrf
                <div class="row register-form">
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="" class="form-label pull-left">Email ID <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="" class="form-label pull-left">Password <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="password" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="" class="form-label pull-left">IMAP Server <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="" class="form-label pull-left">IMAP Port <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="number" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group position-relative">
                      <label for="city_name" class="form-label pull-left">Protocol <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select class="form-control">
                        <option>IMAP</option>
                        <option>POP</option>
                      </select>
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
</div>