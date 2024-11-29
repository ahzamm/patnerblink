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
$fname =  Auth::user()->firstname; 
$lName =  Auth::user()->lastname;
$nic = Auth::user()->nic;
$email = Auth::user()->email;
$mobile = Auth::user()->mobilephone;
$address = Auth::user()->address;
$creationdate = Auth::user()->creationdate;
$status = Auth::user()->status;
$slogan = '';
if($status != 'manager'){
$domainDetail = App\model\Users\Domain::where('resellerid',Auth::user()->resellerid)->first();
$slogan = $domainDetail->slogan;
}
@endphp
<style>
  .user__detail, .modal-body{
    color: #000;
  }
  .user__detail td{
    padding: 5px 10px 0 0;
  }
  .user__detail tr td{
    text-align: left;
  }
  .user__detail tr:nth-child(even) {
    background-color: transparent !important;
  }
  #profileView .modal-content {
    backdrop-filter: blur(10px);
    background-color: #ffffff7d;
  }
</style>
<!-- SMS Module Verification -->
<div aria-hidden="true"  role="dialog" tabindex="-1" id="profileView" class="modal fade" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button aria-hidden="true" data-dismiss="modal" class="close" type="button" style="color: white">×</button>
        <h4 class="modal-title" style="text-align: center;color: white">Profile</h4>
      </div>
      <div class="profile-slogan">
        <div class="">
          <img src="{{asset('img/')}}/{{ $theme_logo }}" alt="" width="175px" height=""  style="margin-left: 20px;margin-top: 5px;" > 
        </div>
        <div class="" style="color: #000;flex:1 0 auto">
          <center><span style="font-size: 18px;font-weight: bold">{{$slogan}}</span></center>
        </div>
      </div>
{{-- <!-- <div class="row">
<div class="col-sm-3">
<img src="{{asset('img/')}}/{{ $theme_logo }}" alt="" width="175px" height=""  style="margin-left: 20px;margin-top: 5px;" >
</div>
<div class="col-sm-9" style="margin-left: -80px;margin-top: 20px;margin-bottom: 20px;">
<center><span style="margin-left: 60px;font-size: 16px;font-weight: bold">We're changing the world with Technology</span></center>
</div>
</div> --> --}}
<hr style="margin-top: 0px;margin-bottom: 0px; border: 1;border-top: 1px solid black;">
<div class="modal-body">
  <div class="container">
    <div class="row">
      <div class="col-sm-8">
        <b>Name:</b><br>
        <ul style="margin-left: 20px;font-weight: bold">{{$fname.' '.$lName}}</ul> 
        <div style="background-color: orangered; height: 5px; width: 225px;margin: 10px;margin-left: 0px"></div>
        <b>Details:</b>
        <br>
        <table class="user__detail">
          <tr>
            <td><b>Username</b></td>
            <td>{{$username}}</td>
          </tr>
          <tr>
            <td><b>Mobile Number</b></td>
            <td>{{$mobile}}</td>
          </tr>
          <tr>
            <td><b>CNIC Number</b></td>
            <td>{{$nic}}</td>
          </tr>
          <tr>
            <td><b>Address</b></td>
            <td>{{$address}}</td>
          </tr>
          <tr>
            <td><b>Creation Date</b></td>
            <td>{{$creationdate}}</td>
          </tr>
          <tr>
            <td><b>Email Address</b></td>
            <td>{{$email}}</td>
          </tr>
          <tr>
            <td><b>Status</b></td>
            <td>Administrator</td>
          </tr>
        </table>
        <ul>
        </ul>
        {{--  <b>Status:</b><br>
          <ul>
            @if($status == 'reseller')
            Reseller
            @elseif($status == 'dealer')
            Contractor
            @elseif($status == 'subdealer')
            Trader
            @endif
          </ul> --}}
        </div>
        <div class="col-sm-4 picture_column">
          <div style="display:flex; flex-direction:column; align-items:center; justify-content:center">
            <img src="{{asset('img/avatar/admin_avatar.png')}}" alt="" width="180px" height="175px" >
            <span style=""><b>{{$fname.' '.$lName}}</b></span>
            <div style="background-color: orangered; height: 2px; width: 200px;margin: 10px;margin-left: 0px"></div>
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
</script>