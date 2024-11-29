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
    .slider:before {
      position: absolute;
      content: "";
      height: 11px !important;
      width: 13px !important;
      left: 3px !important;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    .select2-container{
      border: none;
      padding: 0;
    }
    @media (min-width: 1200px) {
      .internet-profile_right{padding-left:0}
      .internet-profile_left{padding-right:0}
    }
  </style>
  @endsection
  @section('content')
  <div class="page-container row-fluid container-fluid">
    <section id="main-content">
      <section class="wrapper main-wrapper">
        <div class="">
          <div class="">
            <div class="header_view">
              <h2>Update Reseller
                <span class="info-mark" onmouseenter="popup_function(this, 'update_reseller_form');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
              </h2>
            </div>
          </div>
        </div>
        <div class="">
          <section class="box">
            <div class="content-body" style="padding-top:20px">
              <form id="general_validate"
              action="{{route('users.user.update',['status' => 'reseller','id' => $id])}}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-lg-3 col-md-4">
                  <input type="hidden" value="{{$reseller->manager_id}}"  name="managerid" class="form-control" placeholder="Manager-ID" required readonly>
                  <div class="form-group position-relative" >
                    <label class="form-label">Reseller ID <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->resellerid}}" name="resellerid" class="form-control" placeholder="Reseller-Id" required readonly>
                  </div>
                  <div class="form-group position-relative">
                    <label class="form-label">First Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->firstname}}" name="fname" class="form-control"  placeholder="First Name" required>
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">Last Name <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->lastname}}" name="lname" class="form-control"  placeholder="lastname" required>
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">Mobile Number <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->mobilephone}}" name="mobile_number" class="form-control"  data-mask="9999 9999999" required>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label  class="form-label">Landline Number <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->homephone}}" name="land_number" class="form-control"  data-mask="(999)99999999" >
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">CNIC Number <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->nic}}" name="nic" class="form-control" data-mask="99999-9999999-9" required>
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">Email Address <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="email" value="{{$reseller->email}}" name="mail" class="form-control"  placeholder="info@gmail.com" required>
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">Business Address <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->address}}" name="address" class="form-control"  placeholder="Address" required>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label  class="form-label">Assign Reseller Area <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_assign_area');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->area}}" name="area" class="form-control"  placeholder="Area " required>
                  </div>
                  <div class="form-group position-relative">
                    <label class="form-label">Select State <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_state');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select name="state" id="state" class="form-control" required>
                      <option value="">Select State</option>
                      <option <?= ($reseller->state == 'SINDH') ? 'selected' : ''; ?> >SINDH</option>
                      <option <?= ($reseller->state == 'PUNJAB') ? 'selected' : ''; ?> >PUNJAB</option>
                      <option <?= ($reseller->state == 'KPK') ? 'selected' : ''; ?> >KPK</option>
                      <option <?= ($reseller->state == 'BALOCHISTAN') ? 'selected' : ''; ?> >BALOCHISTAN</option>
                      <option <?= ($reseller->state == 'GILGIT') ? 'selected' : ''; ?> >GILGIT</option>
                    </select>
                  </div>
                  <div class="form-group position-relative">
                    <label class="form-label">Select City <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'select_city');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select name="city" id="city" class="form-control" required>
                      <option value="">Select City</option>
                      <?php
                      $city = DB::table('cities')->get();
                      foreach($city as $cityValue){ ?>
                        <option value="<?= $cityValue->city_name;?>"  <?php echo ($reseller->city == $cityValue->city_name ) ? 'selected' : '' ;?>  ><?= $cityValue->city_name;?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">Assign NAS (BRAS) <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_assign_nas');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <select name="nas[]" id="nas" class="form-control multi-select" required multiple="multiple" onchange="sendSelectedNAS()">
                      <option value="">Select NAS</option>
                      <?php foreach($nas as $nasvalue){ 
                        if(in_array($nasvalue->shortname,$assignedNas) === true){ $s = 'selected';}else{ $s = ''; }
                        ?>
                        <option value="<?= $nasvalue->shortname;?>"  <?= $s;?> ><?= $nasvalue->shortname;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label  class="form-label">CNIC <span style="color: #0d4dab">(Front Image)</span> <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="file" name="cnic_front" class="form-control"  placeholder="">
                  </div>
                  <div class="form-group position-relative">
                    <label  class="form-label">CNIC <span style="color: #0d4dab">(Back Image)</span> <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'cnic_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="file" name="cnic_back" class="form-control"  placeholder="">
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <div class="form-group position-relative">
                    <label  class="form-label">Username <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'username');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="text" value="{{$reseller->username}}" name="username" class="form-control"  placeholder="username" readonly required>
                  </div>
                </div>
                <div class="col-lg-3 col-md-4">
                  <!-- Password Field Added (not functional) -->
                  <div class="form-group position-relative" style="position:relative">
                    <label  class="form-label">Update Password <span style="color: red">*</span></label>
                    <span class="helping-mark" onmouseenter="popup_function(this, 'change_password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    <input type="password" value="" name="" class="form-control" id="inputPassword"  placeholder="Must be 8 characters long"> <i class="fa fa-eye-slash toggleClass" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass()" > </i>
                  </div>
                  <div class="random-color" style="display:none;">
                    <label for="gradient">Display Theme Color</label>
                    <div class="display" id="gradient"></div>
                    <h5>Select Theme Color</h5>
                    <input type="color" class="color1" name="color1" value="{{@$color1}}">
                    <input type="color" class="color2" name="color2" value="{{@$color2}}">
                  </div>
                </div>
                <!-- Billing Management -->
                <div class="">
                  <hr style="margin-bottom: 40px;margin-top: 50px;">
                  <div class="header_view">
                    <h2 style="font-size: 26px;">Billing Management</h2>
                    <hr style="background-color: transparent; height: auto; margin-top: initial">
                    <div class=" col-md-4">
                      <div class="form-group position-relative" style="text-align:left; position:relative">
                        <label  class="form-label">Assign Credit Limit (PKR) <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'resller_credit_limit_assign');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <input type="text" value="{{$userAmount->credit_limit}}" name="limit" class="form-control"  placeholder="Amount in (PKR)" required>
                        <small> <p>Last Update: <span>{{$userAmount->update_on}}</span></p></small>
                      </div>
                    </div>
                    <div class=" col-md-4">
                      <div class="form-group position-relative" style="text-align:left">
                        <label  class="form-label">Amount Received (Discount) (PKR) <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_amount_received_discount');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                        <select name="discount" class="form-control"  required>
                          <option value="1" <?= ($userAmount->discount == 1) ? 'selected' : '';?> >Straight</option>
                          <option value="2" <?= ($userAmount->discount == 2) ? 'selected' : '';?> >Half</option>
                          <option value="4" <?= ($userAmount->discount == 4) ? 'selected' : '';?> >Quarter</option>
                        </select>
                      </div>
                    </div>
                    <div class=" col-md-4">
                      <div class="form-group position-relative" style="text-align:left;">
                        <label  class="form-label">Assign Static IP Rate <span style="color: red">*</span></label>
                        <span class="helping-mark" onmouseenter="popup_function(this, 'resller_credit_limit_assign');"><i class="fa fa-question"></i></span>
                        <input type="text" value="{{@$StaticIp->rates}}" name="static_ip_rate" class="form-control"  placeholder="Amount in (PKR)" required>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Brand Management  -->
                <div class="">
                  <hr style="margin-bottom: 40px;margin-top: 50px;">
                  <div class="header_view">
                    <h2 style="font-size: 26px;">Brand Management</h2>
                  </div>
                  <hr style="background-color: transparent; height: auto; margin-top: initial">
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label class="form-labe position-relativel">Domain Name (http://demo.pk) <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'resller_assign_domain');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="bm_domain" class="form-control" placeholder="Example: partner.logon.com.pk" required value="<?= $domainInfo->domainname;?>">
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Assign Brand Slogan <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_assign_brand_slogan');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="bm_slogan" class="form-control" placeholder="Example: One World One Connection" required value="<?= $domainInfo->slogan;?>">
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Upload Favicon <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_fav_icon');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="file" name="bm_favicon" class="form-control">
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Upload Invoice Banner <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_invoice_banner');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="file" name="bm_inv_banner" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      <label class="form-label">Brand Heading <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_brand_heading');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="bm_heading" class="form-control" placeholder="Example: Logon Broadband" required value="<?= $domainInfo->main_heading;?>">
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Upload Brand Logo <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_select_brand_logo');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="file" name="bm_logo" class="form-control" >
                    </div>    
                    <div class="form-group position-relative">
                      <label class="form-label">Upload Background Image <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_background_image');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="file" name="bm_bgImage" class="form-control" >
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Billing Email Address <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="bm_invoice_email" class="form-control" placeholder="info@logon.com.pk" required value="<?= $domainInfo->bm_invoice_email;?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group position-relative">
                      @php
                      /*dynamic theme load start */
                      $theme_loading_select = DB::table('partner_themes_user')
                      ->where('username','=',$reseller->resellerid)
                      ->get();
                      $theme_selected="";
                      if(isset($theme_loading_select[0]->color) && !empty     ($theme_loading_select[0]->color)){
                        $theme_selected=$theme_loading_select[0]->color;
                      }
                      $theme_loading = DB::table('partner_themes')
                      ->get();
                      if(isset($theme_loading[0]->color) && !empty($theme_loading[0]->color)){
                        $theme=$theme_loading[0]->color;
                      }
                      $theme_alignement="center";
                      if(isset($theme_loading_select[0]->login_alignment) && !empty($theme_loading_select[0]->login_alignment)){
                        $theme_alignement=$theme_loading_select[0]->login_alignment;
                      }
                      @endphp
                      <label class="form-label">Select Theme <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_select_theme');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="theme_color" id="theme_color" class="form-control" require>
                        <option value="">Select Theme</option>
                        @php 
                        foreach($theme_loading as $v){
                          @endphp
                          <option value="@php echo $v->color @endphp" @php if($theme_selected== $v->color) echo "selected";  @endphp>
                            @php echo $v->color @endphp
                          </option>
                          @php 
                        }
                        @endphp
                      </select>
                    </div>
                    <!-- Login Form Alignment Start-->
                    <div class="form-group position-relative">
                      <label class="form-label">Login Form Alignment <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_login_form_alignment');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <select name="login_alignment" id="login_alignment" class="form-control" require>
                        <option value="">Select Alignment <span style="color: red">*</span></option>
                        <option value="center"
                        @php if($theme_alignement=="center") echo "selected";  @endphp
                        >Center</option>
                        <option value="left"
                        @php if($theme_alignement=="left") echo "selected";  @endphp
                        >left</option>
                        <option value="right"
                        @php if($theme_alignement=="right") echo "selected";  @endphp
                        >Right</option>
                      </select>
                    </div>
                    <!-- Login Form Alignment End -->
                    <div class="form-group position-relative">
                      <label class="form-label">Package Name Prefix</label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_package_name_prefix');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="packageName" class="form-control" required readonly>
                    </div>
                    <div class="form-group position-relative">
                      <label class="form-label">Help Line Number <span style="color: red">*</span></label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_helpline_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                      <input type="text" name="bm_helpline_number" class="form-control" placeholder="3 11 11 LOGON" required value="<?= $domainInfo->bm_helpline_number;?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label">Facebook URL</label>
                      <input type="text" name="facebook_url" class="form-control" placeholder="https://facebook.com/page-url" value="<?= $domainInfo->facebook_url;?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label">Twitter URL</label>
                      <input type="text" name="twitter_url" class="form-control" placeholder="https://twitter.com/page-url" value="<?= $domainInfo->twitter_url;?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label">LinkedIn URL</label>
                      <input type="text" name="linkedin_url" class="form-control" placeholder="https://linkedin.com/page-url" value="<?= $domainInfo->linkedin_url;?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label">Contractor Profile</label>
                      <!-- <input type="text" name="contractor_profile" class="form-control" placeholder="Contractor Profile" value="<?= $domainInfo->contractor_profile;?>"> -->
                      <select name="contractor_profile" id="contractor_profile"  class="js-select2">
                        <option value="{{$domainInfo->contractor_profile}}">{{$domainInfo->contractor_profile ?? "Select Contractor Profile"}}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label">Trader Profile</label>
                      <!-- <input type="text" name="trader_profile" class="form-control" placeholder="Trader Profile" value="<?= $domainInfo->trader_profile;?>"> -->
                      <select name="trader_profile" id="trader_profile" class="js-select2">
                        <option value="{{$domainInfo->trader_profile}}">{{$domainInfo->trader_profile ?? "Select Trader Profile"}}</option>
                      </select>
                    </div>
                  </div>
                </div>
                {{-- <div class="col-md-12">
                  <hr style="margin-bottom: 40px;margin-top: 50px;">
                  <div class="header_view">
                    <h2 style="font-size: 26px;">Static IPs Management</h2>
                  </div>
                  <hr style="background-color: transparent; height: auto; margin-top: initial">
                  <div class="col-md-offset-4 col-md-4">
                    <div class="form-group">
                      <div style="display:flex; align-items: center; justify-content: center">
                        <div>
                          <label  class="form-label">Gaming IPs</label>
                          <input type="radio" name="ip_type"   placeholder="0" value="gaming" id="ip_type">
                        </div>
                        &nbsp; &nbsp; &nbsp;
                        <div>
                          <label  class="form-label"> Static IPs</label>
                          <input type="radio" name="ip_type"   placeholder="0" value="static" >
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label  class="form-label">Number of IPs</label>
                      <input type="number" id="noofip" name="noofip" class="form-control" min="1"  placeholder="0" disabled>
                    </div>
                    <div class="form-group">
                      <label  class="form-label">Assign Static (Single IP) Rates (PKR) <span style="color: red">*</span></label>
                      <input type="number" name="static_ip" class="form-control"  placeholder="" >
                    </div>
                    <div class="form-group">
                      <div style="display: flex; align-items: center; justify-content: center">
                        <div class="btn-group btn-group-toggle" data-toggle="buttons" id="ipassign">
                          <label class="btn btn-secondary" style="border-right: 2px solid #fff" disabled>
                            <input type="radio" name="options" value="assign" id="option2"  autocomplete="off" > Assign Now
                          </label>
                          <label class="btn btn-secondary" id="ipremove" disabled>
                            <input type="radio" name="options" value="remove" id="option3" autocomplete="off" > Remove Now
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                --}}
                <div class="col-md-12">
                  <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">
                  <div class="header_view">
                    <h2 style="font-size: 26px;">Access Management</h2>
                  </div>
                  <hr style="background-color: transparent; height: auto; margin-top: initial">
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group position-relative">
                      <label class="switch" style="width: 46px;height: 19px;">
                        <input type="checkbox" >
                        <span class="slider square" ></span>
                      </label>
                      <label class="form-label">Verification <span class="helping-mark" onmouseenter="popup_function(this, 'consumers_Verification_module');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span></label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group position-relative">
                      <label class="switch" style="width: 46px;height: 19px;">
                        <input type="checkbox" >
                        <span class="slider square" ></span>
                      </label>
                      <label class="form-label">CNIC & Mobile Verification </label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'consumers_cnic_mobile_module');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <div class="form-group position-relative">
                      <label class="switch" style="width: 46px;height: 19px;">
                        <input type="hidden" name="allow_invoice" value="0">
                        <input type="checkbox" name="allow_invoice" value="1" @if($reseller->allow_invoice == '1') checked @endif>
                        <span class="slider square"></span>
                      </label>
                      <label class="form-label">Allow Invoice </label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'consumers_invoice_module');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    </div>
                  </div>

                  <div class="col-lg-4 col-md-6">
                    <div class="form-group position-relative">
                      <label class="switch" style="width: 46px;height: 19px;">
                        <input type="hidden" name="allow_auto_profit" value="no">
                        <input type="checkbox" name="allow_auto_profit" value="yes" @if(@$ResellerProfileRate->allow_auto_profit == 'yes') checked @endif>
                        <span class="slider square"></span>
                      </label>
                      <label class="form-label">Allow Auto Profit </label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'allow_auto_profit');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    </div>
                  </div>

                  <div class="col-lg-4 col-md-6">
                    <div class="form-group position-relative">
                      <label class="switch" style="width: 46px;height: 19px;">
                        <input type="hidden" name="has_license" value="0">
                        <input type="checkbox" name="has_license" value="1" @if(@$reseller->has_license == '1') checked @endif>
                        <span class="slider square"></span>
                      </label>
                      <label class="form-label">Reseller Own License (CVAS , FLL) </label>
                      <span class="helping-mark" onmouseenter="popup_function(this, 'reseller_own_license');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                    </div>
                  </div>
                  
                </div>
                <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">
                <div class="">
                  <div class="header_view">
                    <h2 style="font-size: 26px;">Assigned Internet Profiles</h2>
                  </div>                
                  <hr style="background-color: transparent; height: auto; margin-top: initial">
                  <div class="col-lg-4 internet-profile_left">
                    <div class="form-group" >
                      <div class="button-group">
                        <button type="button" class="btn dropdown-toggle dropdown__btn" data-toggle="dropdown">
                          Select Internet Profiles
                          <span class="caret"></span>
                          <span>(Dropdown) <span style="color: red">*</span></span></button>
                          <ul class="dropdown-menu" style="max-height:250px;overflow-y:auto;max-width:100%;width: 93%; margin-left: 20px">
                            @foreach($profileList as $profile_data)
                            @php $profile =ucwords($profile_data->name);
                            $rate=$profile_data->rate;
                            @endphp
                            <li>
                              <input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id,'{{$rate}}')"  style="height: 16px;width: 16px;margin:5px 5px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif  />&nbsp;{{$profile}}
                            </li>
                            @endforeach
                          </ul>
                        </div>
                      </div> 
                    </div>
                    <div class="col-lg-8 internet-profile_right">
                      <center>
                        <table class="table table-responsive table-bordered" >
                          <thead class="thead table-striped">
                            <tr>
                              <th scope="col">Internet Profile Name</th>
                              <th scope="col"> Internet Profile Rate (PKR) <span style="color: red">*</span></th>
                              <th scope="col"> Commission <span style="color: red">*</span></th>
                            </tr>
                          </thead>
                          <tbody class="tbody">
                            @foreach($assignedProfileRates as $profileRate)
                            <tr id="{{ucfirst($profileRate->name)}}tr">
                              <td scope='row' class="td__profileName">{{ucfirst($profileRate->name)}}</td>
                              <td scope='row'> <input type="number" class='form-control' 
                                placeholder='0' 
                                style='border: none; text-align: center;'
                                name="{{ucfirst($profileRate->name)}}" step="0.01" value="{{$profileRate->rate}}">
                              </td>
                              <td scope='row'> <input type="number" class='form-control' 
                                placeholder='0' 
                                style='border: none; text-align: center;'
                                name="comm{{ucfirst($profileRate->name)}}" step="0.01" value="{{$profileRate->commission}}">
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </center>
                    </div>
                  </div>
                  <div class="col-xs-12" style="margin-top: 20px;">
                    <div class="form-group pull-right" style="padding-right: 18px">
                      <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </section></div>
          <div class="chart-container " style="display: none;">
            <div class="" style="height:200px" id="platform_type_dates"></div>
            <div class="chart has-fixed-height" style="height:200px" id="gauge_chart"></div>
            <div class="" style="height:200px" id="user_type"></div>
            <div class="" style="height:200px" id="browser_type"></div>
            <div class="chart has-fixed-height" style="height:200px" id="scatter_chart"></div>
            <div class="chart has-fixed-height" style="height:200px" id="page_views_today"></div>
          </div>
        </section>
      </section>
    </div>
    @endsection
    @section('ownjs')
    <script type="text/javascript">
      function showpass() {
        var x = document.getElementById("inputPassword");
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
        $('.toggleClass').toggleClass('fa-eye fa-eye-slash');
      }
    </script>
    <script type="text/javascript">
      $(window).on('load', function(){
        $('.multi-select').select2({
          placeholder: "Select NAS",
          allowClear: true
        });
      })
      $(document).ready(function() {
        $(".add-more").click(function(){
          var html = $(".copy").html();
          $(".after-add-more").after(html);
        });
        $("body").on("click",".remove",function(){
          $(this).parents(".control-group").remove();
        });
      });
    </script>
    <script type="text/javascript">
      function mycheckfunc(val,id,rate){
        var va="#"+id;
        if($(va).attr("title") == "uncheck"){
//
          var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' name='"+id+"' placeholder=0 required min='"+rate+"' step='0.01' style='border: none; text-align: center;''></td><td scope='row'> <input type='number' class='form-control' name='comm"+id+"' placeholder=0 required min='0' step='0.01' style='border: none; text-align: center;''></td></tr>";
          $(".tbody").append(markup);
//
          $(va).attr('title', 'check');
//
        } else if($(va).attr("title") == "check"){
//
          var trvar=va+"tr";
          $(trvar).remove();
//
          $(va).attr('title', 'uncheck');
//
        }
      }
    </script>


    <script type="text/javascript">
      function sendSelectedNAS() {
        var selectElement = document.getElementById('nas');
        var selectedNas = Array.from(selectElement.selectedOptions).map(option => option.value);

    // Get the Contractor and Trader Profile dropdowns as jQuery objects
        var contractorSelect = $('#contractor_profile');
        var traderSelect = $('#trader_profile');

    // Clear the current options but keep Select2 intact
        contractorSelect.empty().append(new Option("Select Contractor Profile", ""));
        traderSelect.empty().append(new Option("Select Trader Profile", ""));

        if (selectedNas.length > 0) {
          fetch('/users/get/contractor-trader', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nas: selectedNas })
          })
          .then(function(response) { return response.json(); })
          .then(function(data) {

           // Populate Contractor Profile dropdown
           data.contractors.forEach(profile => {
            contractorSelect.append(new Option(profile, profile));
          });

            // Populate Trader Profile dropdown
           data.traders.forEach(profile => {
            traderSelect.append(new Option(profile, profile));
          });

            // Refresh Select2 to update the UI with the new options
           contractorSelect.trigger('change.select2');
           traderSelect.trigger('change.select2');
         })
          .catch(function(error) {
            console.error('Error:', error);
          });
        }
      }


    </script>
    @endsection
<!-- Code Finalize -->