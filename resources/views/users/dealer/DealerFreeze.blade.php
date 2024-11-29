<div aria-hidden="true"  role="dialog" tabindex="-1" id="dealerfreeze" class="modal fade" style="display: none;">
  <div class=""> 
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white"> Freeze Account</h4>
        </div>
        <div class="modal-body">
          <div class="">
            <div class="row">
              <div class="" id="freezeModal">
                <p style="font-size: 20px; font-family: serif;text-align:center"> Do you want to Freeze the Account?</p>
                <form action="{{route('users.freeze.dealerpost')}}" method="POST" class="form-group">
                  @csrf
                  <div class="">
                    <input type="hidden" name="username" id="freezusername" >
                    <input type="hidden" name="user_status" value="dealer" >
                    <input type="hidden" name="check" id="check" value="true" >
                    <p style="margin-left: 15px; font-size: 20px;font-family: serif;color: black;font-weight: bold;text-align:center">Account (ID): <span id="subId" style="font-size: 24px;font-family: serif;color: gray"></span></p>
                    <hr>
                    <p style="text-align:right; color:green;font-weight:bold;font-size:16px;"><span> نوٹ:</span>
                    فریز ہونے والا اکائونٹ لاگ ان کیا جا سکتا ہے- دیگر اکائونٹ لاگ ان ہونے کے بعد کسی بھی سرگرمی کی اجازت نہیں ہوگی                    </p>
                    <br>
                  </div>
                  <div class=" pull-right">
                    <input type="submit" class="btn btn-danger" name="" id="" value="Freeze Now">
                    <input type="button" class="btn btn-secondary" name="" id="" value="Cancel" onclick="load()">
                  </div>
                </form>
              </div>
              <div class="" id="activeModel" style="display: none;">
                <p style="font-size: 20px; font-family: serif;text-align:center"> Do you want to Unfreeze the Account?</p>
                <form action="{{route('users.freeze.dealerpost')}}" method="POST" class="form-group">
                  @csrf
                  <div class="col-md-12">
                    <input type="hidden" name="username" id="usernameActive" >
                    <input type="hidden" name="check" id="check" value="false" >
                    <input type="hidden" name="user_status" value="dealer" >
                    <p style="margin-left: 15px; font-size: 20px;font-family: serif;color: black;font-weight: bold; text-align:center">Account (ID): <span id="subIdActive" style="font-size: 24px;font-family: serif;color: gray"></span></p>
                  </div>
                  <div class="col-md-12">
                    <center>
                      <p style="margin-left: 15px; font-size: 24px;font-family: serif;color: black;font-weight: bold">Freeze Activation Date: <span id="freezeDate" style="font-size: 24px;font-family: serif;color: red"></span></p>
                    </center> 
                  </div>
                  <p style="color: green; font-weight:bold;font-size:16px;text-align:center"><span>نوٹ: </span
                    >
                  ان فریز کئے جانے والے اکائونٹ کو تمام تر سہولتیں میسر ہوں گی     </p>
                  <div class="pull-right">
                    <input type="submit" class="btn btn-primary" name="" id="" value="Active Now">
                    <input type="button" class="btn btn-secondary" name="" id="" value="Cancel" onclick="load()">
                  </div>
                </form>
              </div>
            </div>
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
  function load(){
    $('#dealerfreeze').modal('hide');
  }
</script>