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
  .th-color{
    color: white !important;
  }
  .header_view{
    margin: auto;
    height: 40px;
    padding: auto;
    text-align: center;
    font-family:Arial,Helvetica,sans-serif;
  }
  h2{
    color: #225094 !important;
  }
  .dataTables_filter{
    margin-left: 60%;
  }
  tr,th,td{
    text-align: center;
  }
  select{
    color: black;
  }
  #loadingnew{
    display: none;
  }
  #chargeBtn:hover{
    background-color: green;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row" style=''>
      <div class="">
        <div class="col-lg-12">
          <div class="header_view">
            <h2>Recharge Account
              <span class="info-mark" onmouseenter="popup_function(this, 'singal_recharge_consumer');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
          <div id="returnMsg"></div>
          <section class="box">
            <div class="content-body">
              <div class="row">
                <form id="singleRechargeForm">
                  @csrf
                  <input type="hidden" value="{{$username}}" name="username"/>
                  <input type="hidden" value="{{$name}}" name="profileGroupname"/>
                  <div class="col-md-12">
                    <div class="modal-body">
                      <p style="font-size: 18px"><span>{{$data['chargeBy']}}</span> you have <span><strong>{{number_format($data['walletAmount'],2)}} (PKR)</strong></span> in your wallet. </p>
                      <h2><span style="font-size: 16px">Consumer ID:</span> {{$username}}</h2>
                      <?php if($data['info']->company_rate == 'no'){ ?>
                        <div class="table-responsive" style="border: 1px solid #0d4dab">
                          <table class="table tax_table">
                            <thead>
                              <tr>
                                <th>Current Internet Profile</th>
                                <th>Internet Profile Rate  (PKR)</th>
                                <th>Sindh Sales Tax  (PKR)</th>
                                <th>Advance Income Tax  (PKR)</th>
                                <?php if($data['tax'] > 0 ){ ?>
                                  <th>Contractor Commission Tax (PKR)</th>
                                <?php } if($data['staticIPAmnt'] > 0 ){ ?>
                                  <th>Static IP Rate (PKR)</th>
                                <?php } ?>
                                <th>Grand Total (PKR)</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>{{$name}}</td>
                                <td>{{number_format($data['profileRate'],2)}}</td>
                                <td>{{number_format($data['sst'],2)}}</td>
                                <td>{{number_format($data['adv'],2)}}</td>
                                <?php if($data['tax'] > 0 ){ ?>
                                  <td>{{number_format($data['tax'],2)}} <small style="color:green;">({{$data['taxStatus']}})</small></td>
                                <?php } if($data['staticIPAmnt'] > 0 ){ ?>
                                  <td>{{number_format($data['staticIPAmnt'],2)}}</td>
                                <?php } ?>
                                <td><strong style="font-size: 16px; color: darkgreen">{{number_format($data['total'],2)}}</strong></td>
                              </tr>
                            </tbody>
                          </table>
                        </div> 
                      <?php } ?>
                      <p style="font-size: 20px; margin-top: 20px;">The recharge amount is <span style="color: darkgreen"><strong>{{number_format($data['wallet_deduction'],2)}} (PKR)</strong></span>. After recharge the amount in your wallet will be <span><strong>{{number_format($data['wallet_after'],2)}} (PKR)</strong></span></p>
                      <hr style="margin-top: 20px">
                      <p style="font-size: 18px; color: #d16565;text-align:center">Please provide the correct information regarding (Consumer) CNIC for taxation purpose. However, Company is not responsible for any false information.</p>
                      <p style="text-align: center; color: #d16565;font-size: 16px">براہ کرم ان صارف کا ٹیکس ادا کرنے کے لیے اپنے شناختی کارڈ کی صحیح معلومات دیں- غلط معلومات کی صورت میں کمپنی زمہ دار نہیں ہو گی
                      براہ کرم صارفین تک کمپنی کی انوائس کی رسائی کو یقینی بنائیں- شکریہ</p>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group pull-right">
                      <button type="submit" class="btn btn-primary">Recharge Now</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </section>
  <!-- CONTENT END -->
  <!-- Processing -->
  <div class="modal fade bs-example-modal-center custom-center-modal" id="processLayer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog modal-md">
      <div class="modal-content" style="background-color:#02020200 !important;border-color:#02020200 !important; ">
        <div class="modal-body">
          <center><h1 style="color:white;">Processing....</h1>
            <p style="color:white;">please wait.</p>
          </center>
        </div>
      </div>
    </div>
  </div>    
</div>
@endsection
@section('ownjs')
<script type="text/javascript">
  $("#singleRechargeForm").submit(function() {
    if(confirm("Do your really want to recharge ?")){
      $('#processLayer').modal('show');
      $.ajax({ 
        type: "POST",
        url: "{{route('users.single.recharge')}}",
        data:$("#singleRechargeForm").serialize(),
        success: function (data) {
          $('html, body').scrollTop(0);
          $('#processLayer').modal('hide');
          $('#main-content #returnMsg').html('<div class="alert alert-success alert-dismissible show">'+data+'</div>');
          setTimeout(function() { 
            window.location.href = "{{ route('users.user.index1',['status' => 'expire'])}}";
          }, 2000);
        },
        error: function(jqXHR, text, error){
          $('html, body').scrollTop(0);
          $('#main-content #returnMsg').html('<div class="alert alert-error alert-dismissible show">'+jqXHR.responseJSON.message+'</div>');
          $('#processLayer').modal('hide');
        },
        complete:function(){
        },
      });
    }
    return false;
  })
</script>
@endsection
<!-- Code Finalize -->