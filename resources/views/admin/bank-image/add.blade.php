<div class="modal fade" id="banks-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none">
  <div class="modal-dialog modal-md" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1 !important;" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="color: white">Add Bank Name & Logo</h4>
      </div>
      <div class="modal-body text-center">
        <div class="" id="tblData">
          <div class="">
            <form  id="my-form" enctype="multipart/form-data" method="POST">
              @csrf
              <div class="row register-form">
                <div class="col-md-12">
                  <div class="form-group position-relative">
                    <label for="bank_name" class="form-label pull-left">Bank Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'bank_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="" name="bank_name" class="form-control" required>
                  </div>
                  <div class="form-group position-relative">
                    <label for="image" class="form-label pull-left">Bank Logo Image <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'bank_logos_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="file" value="" name="image" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="pull-right">
                    <button type="submit" class="btn btn-primary">Submit</button>
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