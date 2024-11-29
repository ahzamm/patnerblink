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
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
<!-- SMS Module verification -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="popupNote" class="modal popup-wrap">
  <div class="col-md-2"> </div>
  <div class="col-md-8"> 
    <div class="modal-dialog" style="width: 80%">
      <div class="modal-content">
        <div class="modal-header" style="background-color: #4878bf;">
          <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
          <h4 class="modal-title" style="text-align: center;color: white">Terms & Conditions</h4>
        </div>
        <div class="row">
          <div class="col-sm-3">
            <img src="{{asset('img/MAIN_LOGO.png')}}" alt="" width="175px" height="85px"  style="margin-left: 20px;margin-top: -15px;" >
          </div>
          <div class="col-sm-9" style="margin-left: -40px;margin-top: 20px;">
            <center><span style="margin-left: 40px;font-size: 18px;font-weight: bold;" id="noteur"><span style="color: red;font-weight: bold;font-size: 24px;"> نوٹ:- </span>پی ٹی اے اور قانون نافذ کرنے والی ایجنسیوں کی ہدایات کے مطابق، صارف کی پروفائل میں مندرجہ ذیل معلومات ہونی چاہیں</span></center>
            <center><span style="margin-left: 50px;font-weight: bold; font-size: 16px;display: none;" id="noteng"><span style="color: red">Note:-</span> In accordance with the instructions of the PTA and law enforcement agencies, the user's profile should contain the following information</span></center>
          </div>
        </div>
        <hr style="margin-top: 0px;margin-bottom: 0px; border: 1;border-top: 1px solid black;">
        <div class="modal-body">
          <div class="" >
            <div class="row" id="urdu">
              <div class="col-md-12 col-sm-12">
                <div  style="background-color: #e7e7e7; border-radius: 25px;border: 2px solid #4878bf; padding: 10px;color: black;">
                  <p  style="font-size: 18px;text-align: right">یوزر کا نام </p>
                  <hr>
                  <p  style="font-size: 18px; text-align: right">جہاں انٹرنیٹ کنکشن لگایا گیا ہے وہاں کا مکمل پتہ یوزر کا موبائل نمبر شناختی کارڈ کی دونوں سائیڈ کی تصویر پرنٹ کرنے کے قابل </p>
                  <hr>
                  <p  style="font-size: 18px; text-align: right">مام آپریٹرز 30 دنوں کے اندار مکمل اور درست معلومات اپ ڈیٹ کرنے کے پابند اورذمہ دار ہیں </p>
                  <hr>
                  <p  style="font-size: 18px; text-align: right">مقررہ مدت کے گزر جانے کے بعد جس یوزر کی مکمل معلومات سسٹم میں موجود نہیں ہو گی اس یوزر کی سروس معطل کر دی جائے گی </p>
                  <hr>
                  <p  style="font-size: 18px; text-align: right">یوزر کی معلومات درست نہ ہونے کی صورت میں متعلقہ آپریٹر اور اس کا ذیلی آپریٹرز ذمہ دار اور جواب دے ہو گا اور قانون نافذ کرنے والے اداروں کے سامنے پیش ہو گا </p><hr>
                  <p style="font-size: 18px;text-align: right"><span style="color: red;font-weight: bold; ">ضروری اطلاع:- </span>تمام ڈیلر اور سبڈیلر کو مطلع  کیا جاتا ہے کہ اپنے یوزر کو پینل پے دی گئی ٹیکس سلپ دی جاۓ کسی بھی دوسری کمپنی یا کسی اپنے نام کی دی گئی سلپ کا ذمہ دار وہ ڈیلر یا سبڈیلر خود ہوگا کمپنی اس کی ذمہ دار نہیں ہے شکریہ </p>
                </div>
              </div>
            </div>
            <div class="row" id="eng" style="display: none">
              <div class="col-md-12 col-sm-12">
                <div  style="background-color: #e7e7e7; border-radius: 25px;border: 2px solid #4878bf; padding: 10px;color: black;">
                  <p  style="font-size: 18px;text-align: left">1: User Name</p>
                  <hr>
                  <p  style="font-size: 18px; text-align: left">2: Full address of the user where the Internet connection is established - Both sides of the ID card. Image of (printable) </p>
                  <hr>
                  <p  style="font-size: 18px; text-align: left">3: All operators are obligated and responsible for updating complete and accurate information within 30 days</p>
                  <hr>
                  <p  style="font-size: 18px; text-align: left">4: After the expiry of the specified period, the complete information of the user will not be available in the system User Service will be suspended</p>
                  <hr>
                  <p  style="font-size: 18px; text-align: left">5: If the user information is not correct then the relevant operator and its sub-operators will be responsible and responsive. And will be presented to law enforcement agencies</p>
                  <hr>
                  <p  style="font-size: 18px; text-align: left">6: <span style="color: red;font-weight: bold">Warning.!</span> All dealers and sub-dealers are here by bound to issue company receipt(tax receipt) only for their users. Issuance of any other company receipt is unlawfull and Dealer and sub dealer will be responsible for issuing same.</p>
                </div>
              </div>
            </div>
          </div>
          <div style="margin-left: 20px;">
            <input type="checkbox" id="checkbox" checked onchange="showEngData(this)" class=""/>
            <label for="" id="langE">Switch Language to English</label>
            <label for="" id="lang" style="display: none">Switch Language to Urdu</label>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
<script>
  $(document).ready(function() {
    $('.popup-btn').click(function(e) {
      $('.popup-wrap').fadeIn(2500);
      $('.popup-box').removeClass('transform-out').addClass('transform-in');
      e.preventDefault();
    });
    $('.popup-close').click(function(e) {
      $('.popup-wrap').fadeOut(500);
      $('.popup-box').removeClass('transform-in').addClass('transform-out');
      e.preventDefault();
    });
  });
</script>
<script>
  function showEngData(checkbox){
    let isCheck = checkbox.checked;
    if(isCheck){
      $('#noteng').hide();
      $('#noteur').show();
      $('#lang').hide();
      $('#langE').show();
      $('#urdu').show();
      $('#eng').hide();
    }else{
      $('#noteng').show();
      $('#lang').show();
      $('#langE').hide();
      $('#noteur').hide();
      $('#urdu').hide();
      $('#eng').show();
    }
  }
</script>
<!-- Code Finalize -->