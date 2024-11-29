<!-- Never Expire Modal -->

<div class="modal" id="neverExpireModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header" style="padding: 10px 15px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="transform:translateY(10px)">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="exampleModalLabel" style="color: #fff;display:inline-block">Never Expire <span class="info-mark" onmouseenter="popup_function(this, 'consumer_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span></h4>
        </div>
        <div class="modal-body" style="padding: 15px">
          <div class="d-flex" style="display: flex; column-gap:15px;flex-wrap:wrap">
            <div>
              <label  class="form-label">Function (ON / OFF)</label>
              <label class='toggle-label' style="margin-left: 0">
                <input type='checkbox' id="expire_cb"/>
                <span class='back'>
                  <span class='toggle'></span>
                  <span class='label on'>Enable</span>
                  <span class='label off'>Disable</span>  
                </span>
              </label>
            </div>
            <div class="form-group position-relative" style="flex-grow: 1" >
              <label  class="form-label">Select Month</label>
              <input type="text" placeholder="Select Month" id="month_input" name="never_expire_month" class="form-control month-input">
              <span style="font-size: 12px">Last Update: <strong>10 January, 2024</strong></span>
            </div>
          </div>
          <div style="display: flex; align-items:center; justify-content:space-between">
            <p class="mb-0" style="font-size: 12px">Consumer (ID): <strong>aamiriqbal</strong> <span style="white-space:nowrap">Expiry Date: <strong class="blink">10 October, 2024</strong></span></p>
            <div style="white-space:nowrap">
              <button type="submit" class="btn btn-primary btn-sm" id="btnAddDepartment">Apply</button>
              <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
   </div>
</div>
<!-- Never Expire Modal End -->