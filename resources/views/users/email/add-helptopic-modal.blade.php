<div class="modal fade" id="helpTopicModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Add Help Topic</h4>
      </div>
      <div class="modal-body text-center">
        <div class="" id="tblData">
          
          <div class="">
            <form id="" method="POST">
              @csrf
              <div class="row register-form">
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="" class="form-label pull-left">Title <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'city_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="" class="form-control" id="" placeholder="" required>
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