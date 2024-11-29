<div aria-hidden="true"  role="dialog" tabindex="-1" id="freezeAlert" class="modal fade" style="display: none;">
  <div class="col-md-2"> </div>
  <div class="col-md-8"> 
    <div class="modal-dialog" style="width: 85%">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #4878bf;">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white">Freeze Account Alert</h4>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <img src="{{asset('img/MAIN_LOGO.png')}}" alt="" width="175px" height="85px"  style="margin-left: 20px;margin-top: -15px;" >
          </div>
          <div class="col-sm-9" style="margin-left: -80px;margin-top: 20px;">
            <center><span style="margin-left: 60px;font-size: 16px;font-weight: bold">We're changing the world with Technology</span></center>
          </div>
        </div>
        <hr style="margin-top: 0px;margin-bottom: 0px; border: 1;border-top: 1px solid black;">
        <div class="modal-body">
          <div class="">
            <div class="row">
              <div class="col-md-1"></div>
              <div class="col-md-10">
                <center> <p style="font-size: 20px; font-family: serif"><span style="color: red; font-size: 22px;font-weight: bold;"> Note:- </span> Dear user your account has been Freezed  You'r  uneligiable to use any functionality for more information Please Contect Your Company..!</p><br>
                  <p style="font-size: 20px; font-family: serif"><span style="font-size: 24px;color: red">نوٹ:-   </span>معزز صارف آپ کا اکاؤنٹ فریز ہو چکا ہے اپنے اکاؤنٹ کی مزید تفصیلات کہ لئے آپنی کمپنی  سے رابطہ کریں</p>
                </div>
              </center>
              <div class="col-md-1"></div>
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
</script>