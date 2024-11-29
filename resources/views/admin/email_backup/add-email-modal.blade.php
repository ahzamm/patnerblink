<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Add Email</h4>
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
            <form id="" method="POST">
              @csrf
              <div class="row register-form">
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Email Address <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Email Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="" class="form-control" id="" placeholder="Example : Karachi" required>
                  </div>
                </div>
                <div class="col-md-6">
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
                <div class="col-md-6">
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
                <div class="col-md-6">
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
        </div>
      </div>
    </div>
  </div>
</div>