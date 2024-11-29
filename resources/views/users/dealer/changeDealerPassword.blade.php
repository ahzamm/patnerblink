<style>
  input[type="file"] {
    display: block;
  }
  .imageThumb {
    height: 150px;
    width: 300px;
    border: 2px solid;
    padding: 1px;
    cursor: pointer;
    margin-top: 10px;
  }
  .pip {
    display: inline-block;
    margin: 10px 10px 0 0;
  }
  .remove {
    display: block;
    background: #444;
    border: 1px solid black;
    color: white;
    text-align: center;
    cursor: pointer;
  }
  .remove:hover {
    background: white;
    color: black;
  }
  #output_images
  {
    margin-top: 10px;
    width:250px;
    height: 140px;
    margin-left: -10px;
  }
  #output_image
  {
    margin-top: 10px;
    width:250px;
    height: 140px;
    margin-left: -10px;
  }
</style>
@php
$username = Auth::user()->username;
@endphp
<div aria-hidden="true"  role="dialog" tabindex="-1" id="changePass" class="modal fade" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
        <h4 class="modal-title" style="text-align: center;color: white">Change Panel Password
          <span class="info-mark" onmouseenter="popup_function(this, 'change_password');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h4>
      </div>
      <div class="modal-body" style="padding-top:15px;padding-bottom:0px;">
        <div class="row">
          <div class="col-md-12" >
            <h3 class="text-center" style="font-weight: bold">{{ucwords($username)}}</h2>
              <hr style="margin-top: 20px;background-color: #0d4dab87">
              <form action="{{route('users.billing.changePass')}}" method="GET" class="form-group">
                <input type="hidden" name="user" value="{{$username}}" id="">
                <div class="form-group" style="position:relative">
                  <label for="old_pass">Current Password <span style="color:red">*</span></label>
                  <input type="password" name="password" id="old_password" class="form-control" placeholder="Assign your current password"> <i class="fa fa-eye-slash old_password" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('old_password');" > </i>
                </div>
                <div class="form-group" style="position:relative">
                  <label for="pass">New Password <span style="color:red">*</span></label>
                  <input type="password" name="pass" id="password" class="form-control" placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash password" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('password');" > </i>
                </div>
                <div class="form-group" style="position:relative">
                  <label for="pass">Retype Password <span style="color:red">*</span></label>
                  <input type="password" name="repass" id="confirm_password" class="form-control" placeholder="Password must be 8 characters long"> <i class="fa fa-eye-slash confirm_password" style="position: absolute;top: 35px;right: 12px;" onclick="togglePassword('confirm_password');" > </i>
                  <span id='message'></span>
                </div>
                <div class="form-group pull-right">
                  <button type="submit" id="btnPass" class="btn btn-primary" disabled>Update</button>
                  <button type="" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
  <script>
    $('#password, #confirm_password').on('keyup', function () {
      if ($('#password').val() == $('#confirm_password').val()) {
        $('#message').html('Matching').css('color', 'green');
        $('#btnPass').attr('disabled',false);
      } else 
      $('#message').html('Not Matching').css('color', 'red');
    });
  </script>