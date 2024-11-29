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
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
  /* The Close Button Start */
  .close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  .close:hover,
  .close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
  }
  /* Tooltip */
  #tooltip{
    width: auto;
    background: white;
    color: white;
    padding: 4px 8px;
    font-size: 13px;
    border-radius: 5px;
    z-index: 999;
    border:1px solid #000;
  }
  #tooltip p{font-weight: normal;padding: 5px;text-align: justify;margin-bottom: 0;color:#000}
  #tooltip p:first-child{font-weight: bold;color:#000;font-size: 18px;padding: 5px;border-bottom: 1px solid #9a201c;}
  #tooltip .content{margin: 10px 5px;color:#000}
  #tooltip label{padding: 0 15px;color:#000}
  /* The Close Button End */
</style>
@endsection
@section('content')
<!-- Popover Start -->
<div id="tooltip" role="tooltip">
  <p class="title">Wallet Deduction Amount Detail</p>
  <p class="description">Following are the detail of deduction.</p>
  <div class="content">
    Rate : <span class="profile-rate"></span><label>|</label>
    SST-Tax : <span class="sst-tax"></span><label>|</label>
    Ait-Tax : <span class="adv-tax"></span><label>|</label>
    Static-Ip : <span class="static-ip"></span><label>|</label>
    Filer-Tax : <span class="filer-tax"></span>
  </div>
  <div id="arrow" data-popper-arrow></div>
</div>
<!-- Popover End -->
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Bill Register 
          <span class="info-mark" onmouseenter="popup_function(this, 'billing_report');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      @if(session('error'))
      <div class="alert alert-error alert-dismissible show">
        {{session('error')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      @endif
      @if(session('success'))
      <div class="alert alert-success alert-dismissible show">
        {{session('success')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      @endif
      <?php
      $manager_id = (empty(Auth::user()->manager_id)) ? null : Auth::user()->manager_id;
      $resellerid = (empty(Auth::user()->resellerid)) ? null : Auth::user()->resellerid;
      $dealerid = (empty(Auth::user()->dealerid)) ? null : Auth::user()->dealerid;
      $sub_dealer_id = (empty(Auth::user()->sub_dealer_id)) ? null : Auth::user()->sub_dealer_id;
      $trader_id = (empty(Auth::user()->trader_id)) ? null : Auth::user()->trader_id;
//
      if(empty($resellerid)){
        $panelof = 'manager';
      }else if(empty($dealerid)){
        $panelof = 'reseller';
      }else if(empty($sub_dealer_id)){
        $panelof = 'dealer';
      }else{
        $panelof = 'subdealer'; 
      }
      ?>
      <section class="box ">
        <header class="panel_header">
          <div class="actions panel_actions pull-right">
            <a class="box_toggle fa fa-chevron-down"></a>
          </div>
        </header>
        <div class="content-body">
          <form action="{{route('users.billing.bill_register_action')}}" method="POST">
            @csrf
            <div class="row">
              <div class="col-lg-4 col-md-6">
                <div class="form-group">
                  <label  class="form-label">Select Date & Time Range <span style="color: red">*</span></label>
                  <div class="controls" style="margin-top: 0px;">
                    <input type="text" 
                    name="datetimes" style="width: 100%;height: 34px" 
                    required>
                  </div>
                </div>
              </div>
              <div class="form-group col-lg-4 col-md-6" style="position:relative">
                <label style="font-weight: normal"> Select</label>
                <select id="verified" class="form-control" name="verified">
                  <option>Verified</option>
                  <option>PTA Checked</option>   
                </select>
              </div>
              <div class="form-group col-lg-4 col-md-6" style="position:relative">
                <label style="font-weight: normal"> Billing Price</label>
                <select id="billing_price" class="form-control" name="billing_price">
                  <option value="all">All</option>
                  <option value="yes">Company Rate</option>
                  <option value="no">Consumer Rate</option>
                </select>
              </div>
              <?php if($panelof == 'dealer'){?>
                <div class="form-group col-lg-4 col-md-6" style="position:relative">
                  <label style="font-weight: normal"> just own <span style="color: red">*</span></label>
                  <input type="checkbox" class="form-control" name="own" id="account_checkbox" style="width:100px;height:30px">
                </div>
              <?php } ?>
            </div>
            <div class="row">
              <?php
              if($panelof == 'manager'){
                $selectedReseller = App\model\Users\UserInfo::where('status','reseller')->where('manager_id',Auth::user()->manager_id)->get();
                ?>
                <div class="form-group col-md-4">
                  <label style="font-weight: normal">Select Reseller <span style="color: red">*</span></label>
                  <select id="reseller-dropdown" class="js-select2" multiple name="reseller_data[]">
                    <option value="">-- Select reseller --</option>
                    @foreach($selectedReseller  as $reseller)
                    <option value="{{$reseller->username}}">{{$reseller->username}}</option>
                    @endforeach
                  </select>
                </div>
              <?php } if(($panelof == 'manager') || ($panelof == 'reseller')){ ?>
                <div class="form-group col-md-4">
                  <label style="font-weight: normal">Select Contractor <span style="color: red">*</span></label>
                  <select id="dealer-dropdown" class="js-select2" multiple name="dealer_data[]">
                    <option value="">-- Select contractor --</option> 
                    <?php
                    if(Auth::user()->status == 'reseller' || Auth::user()->status == 'inhouse'){
                      $selectedDealer = App\model\Users\UserInfo::where('status','dealer')->where('resellerid',Auth::user()->resellerid)->get(); 
                      foreach ($selectedDealer as $dealer) { ?>
                        <option value="{{$dealer->username}}">{{$dealer->username}}</option>
                        <?php   
                      } 
                    }
                    ?>
                  </select>
                </div>
              <?php } if(($panelof == 'manager') || ($panelof == 'reseller') || ($panelof == 'dealer') ){ ?>
                <div class="form-group col-md-4">
                  <label style="font-weight: normal">Select Trader <span style="color: red">*</span></label>
                  <select id="trader-dropdown" class="js-select2" multiple name="trader_data[]">
                    <option value="">-- Select trader --</option>
                    <?php
                    if(Auth::user()->status == 'dealer'){
                      $selectedDealer = App\model\Users\UserInfo::where('status','subdealer')->where('dealerid',Auth::user()->dealerid)->get(); 
                      foreach ($selectedDealer as $subdealer) { ?>
                        <option value="{{$subdealer->username}}">{{$subdealer->username}}</option>
                      <?php  } 
                    } ?>
                  </select>
                </div>
              <?php } ?>
              <div class="col-md-12">
                <div class="form-group pull-right" style="margin-top: 5px;">
                  <button class="btn btn-flat btn-primary" id="" >Download</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </section>
    </section>
  </section>
</div>
@endsection
@section('ownjs')
<script>
  var popover = $('#tooltip');
  popover.hide();
  $(document).on('mouseover','.data-wallet',function(){
// alert('wokring');
var rate =$(this).attr('data-rate');
$('.profile-rate').text(rate);
var sst_tax =$(this).attr('data-sst');
$('.sst-tax').text(sst_tax);
var adv_tax =$(this).attr('data-adv');
$('.adv-tax').text(adv_tax);
var ip =$(this).attr('data-ip');
$('.static-ip').text(ip);
var filer =$(this).attr('data-filer');
$('.filer-tax').text(filer);
var id =$(this).attr('data-id');
});
  function popup_show(id){
    popover.show(); 
    var popper = new Popper(id,popover,{
      placement: 'right',
      onCreate: function(data){
        console.log(data);
      },
      modifiers: {
        flip: {
          behavior: ['left', 'right', 'top','bottom']
        },
        offset: { 
          enabled: true,
          offset: '0,0'
        }
      }
    });
  };
  function popover_hide(){
    popover.hide();
  }
</script>
<script type="">
  $(document).ready(function() {
    $('.right-tip').tooltip();
    $('#example1').DataTable( {
      "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
      "footerCallback": function ( row, data, start, end, display ) {
        var api = this.api(), data;
        var totalCol = $('#totalHeading').attr('colspan');
// Remove The Formatting To Get Integer Data For Summation
var intVal = function ( i ) {
  return typeof i === 'string' ?
  i.replace(/[\$,]/g, '')*1 :
  typeof i === 'number' ?
  i : 0;
};
// Total Over All Pages
total = api
.column( totalCol )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
// Total Over This Page
pageTotal = api
.column( totalCol, { page: 'current'} )
.data()
.reduce( function (a, b) {
  return intVal(a) + intVal(b);
}, 0 );
pageTotal = pageTotal.toFixed(2);
// Update Footer
$( api.column( totalCol ).footer() ).html(pageTotal);
}
} );
  } );
</script>
<script type="text/javascript">
  function formatMoney(n, c, d, t) {
    var c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
    j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  }
</script>
<script>
  $(function() {
    $('input[name="datetimes"]').daterangepicker({
      timePicker: true,
      startDate: moment().startOf('hour'),
      endDate: moment().startOf('hour'),
      locale: {
        format: 'M/DD/YYYY HH:mm'
      }
    });
  });
</script>
<script>
  $(document).ready(function () {
    $('#reseller-dropdown').on('change', function () {
      var reseller_id = $('#reseller-dropdown').val();
      if(reseller_id == ''){
        $('#btn_generate').prop('disabled', true)
      }else{
        $('#btn_generate').prop('disabled', false)
      }
      $("#dealer-dropdown").html('');
      $.ajax({
        url: "{{route('get.dealer')}}",
        type: "POST",
        data: {
          reseller_id: reseller_id,
          _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (result) {
          $('#dealer-dropdown').html('<option value="">-- Select contractor --</option>');
          $.each(result.dealer, function (key, value) {
            $("#dealer-dropdown").append('<option value="' + value
              .username + '">' + value.username + '</option>');
          });
          $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
        }
      });
    });
/*------------------------------------------
--------------------------------------------
Trader Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#dealer-dropdown').on('change', function () {
  var dealer_id = $('#dealer-dropdown').val();
  $("#trader-dropdown").html('');
  $.ajax({
    url: "{{route('get.trader')}}",
    type: "POST",
    data: {
      dealer_id: dealer_id,
      _token: '{{csrf_token()}}'
    },
    dataType: 'json',
    success: function (result) {
      $('#trader-dropdown').html('<option value="">-- Select Trader --</option>');
      $.each(result.subdealer, function (key, value) {
        $("#trader-dropdown").append('<option value="' + value
          .username + '">' + value.username + '</option>');
      });
    }
  });
});
});
</script>
<script>
  var reseler_id, dealer_id, trader_id;
  $('#account_checkbox').on('click', function() {
    var value = $('#account_checkbox').prop('checked');
    var reseler_id = $('#reseller-dropdown').val();
    var dealer_id = $('#dealer-dropdown').val();
    var trader_id = $('#trader-dropdown').val();
    if(value == true) {
      $('#reseller-dropdown').prop('disabled', true);
      $('#dealer-dropdown').prop('disabled', true);
      $('#trader-dropdown').prop('disabled', true);
      $('#btn_generate').prop('disabled', false);
      console.log(value);
    }
    else{
      $('#btn_generate').prop('disabled', true);
      $('#reseller-dropdown').prop('disabled', false);
      $('#dealer-dropdown').prop('disabled', false);
      $('#trader-dropdown').prop('disabled', false);
    }
  })
  $('#reseller-dropdown').on('change', function() {
    reseler_id =  this.value;
  })
  $('#dealer-dropdown').on('change', function() {
    dealer_id =  this.value;
    if(reseler_id === '' && dealer_id === '' && trader_id === ''){
      $('#btn_generate').prop('disabled', true);
      console.log(reseler_id);
    }
  })
  $('#trader-dropdown').on('change', function() {
    trader_id =  this.value;
    console.log(trader_id);
    if(trader_id != ''){
      $('#btn_generate').prop('disabled', false);
      console.log('Not disabled');
    }
  })
</script>
@endsection
<!-- Code Finalize -->