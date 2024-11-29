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
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <!-- CONTENT START -->
  <section id="main-content" class=" ">
    <section class="wrapper main-wrapper row">
      <div class="">
        <div class="col-lg-12" >
          <div class="header_view">
            <h2>Update Manager
              <span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
            </h2>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <section class="box ">
          <div class="content-body">
            <h3 class="h3--username">{{$manager->firstname}} {{$manager->lastname}}</h3>
            <form id="general_validate" 
            action="{{route('admin.user.update',['status' => 'manager','id' => $id])}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <hr>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label for="" class="form-label">Manager ID <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'manager_id');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->manager_id}}" name="manager_id" class="form-control" id="" placeholder="Manager-ID" required readonly>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Username <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'username');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->username}}" name="username" class="form-control"  placeholder="Username" readonly required>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">First Name <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'first_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->firstname}}" name="fname" class="form-control"  placeholder="First Name" required>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Last Name <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'last_name');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->lastname}}" name="lname" class="form-control"  placeholder="lastname" required>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Business Address <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->address}}" name="address" class="form-control"  placeholder="Address" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group position-relative">
                  <label  class="form-label">Assign Manager City & Area <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'assign_manager_city_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->area}}" name="area" class="form-control"  placeholder="Area " required >
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Mobile Number <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'mobile_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->mobilephone}}" name="mobile_number" class="form-control" data-mask="9999 9999999" required>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Landline Number</label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'land_line_number');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text"  value="{{$manager->homephone}}" name="land_number" class="form-control"  data-mask="(999)99999999" >
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">CNIC Number <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'cnic');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="text" value="{{$manager->nic}}" name="nic" class="form-control" data-mask="99999-9999999-9" required>
                </div>
                <div class="form-group position-relative">
                  <label  class="form-label">Email Address <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'email_address');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="email" value="{{$manager->email}}" name="mail" class="form-control"  placeholder="lbi@gmail.com" required>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group"style="position:relative" position-relative>
                  <label  class="form-label">Update Password <span style="color: red">*</span></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'change_password');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <input type="Password" name="password" class="form-control" autocomplete="off" id="inputPassword" placeholder="Must be 8 characters long" >
                  <i class="fa fa-eye-slash toggleClass" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass();" > </i>
                </div>
                <div class="form-group" style="position:relative">
                  <label  class="form-label">Retype Password <span style="color: red">*</span></label>
                  <input type="Password" name="password_confirmation" class="form-control" id="retype_password" autocomplete="off" placeholder="Must be 8 characters long" >
                  <i class="fa fa-eye-slash toggleClass2" style="position: absolute;bottom: 9px;right: 12px;" onclick="showpass2();" > </i>
                </div>
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
              <div class="col-md-12">
                <hr style="border-bottom: 2px solid #ddd; margin-bottom: 40px;margin-top: 50px;">
                <div class="header_view">
                  <h2 style="font-size: 26px;">Assigned Internet Profiles</h2>
                </div>
                <div class="col-md-4" style="padding-right: 0; padding-left: 0;">
                  <div class="button-group">
                    <button type="button" class="btn dropdown-toggle dropdown__btn" data-toggle="dropdown">
                      Select Internet Profiles
                      <span class="caret"></span>
                      <span>(Dropdown) <span style="color: red">*</span></span></button>
                      <ul class="dropdown-menu">
                        @foreach($profileList as $profile)
                        @php $profile =ucwords($profile->name); @endphp
                        <li>
                          <a href="javascript:void(0)" class="small"  data-value="option1" tabIndex="-1" style="font-size: 16px;">
                            <input type="checkbox"  class="profile" id="{{$profile}}" value="{{$profile}}" onchange="mycheckfunc(this.value,this.id)"  style="height: 16px;width: 16px;" title= @if(in_array($profile, $assignedProfileNameList))"check" checked @else"uncheck" @endif  />&nbsp;{{$profile}}</a></li>
                            @endforeach
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-8" style="padding-left: 0; padding-right: 0;">
                        <center>
                          <table class="table table-responsive table-bordered" >
                            <thead class="thead table-striped">
                              <tr>
                                <th scope="col" class="th-color">Internet Profile Name</th>
                                <th scope="col" class="th-color"> Internet Profile Rates (PKR) <span style="color: red">*</span></th>
                              </tr>
                            </thead>
                            <tbody class="tbody">
                              <!-- RAW ADD HERE -->
                              @foreach($assignedProfileRates as $profileRate)
                              @php $npro=$profileRate->name; @endphp
                              @if($npro != 'lite' && $npro != 'social' && $npro != 'smart' && $npro != 'super' && $npro != 'turbo' && $npro != 'mega' && $npro != 'jumbo' && $npro != 'sonic' )
                              <tr id="{{ucfirst($profileRate->name)}}tr">
                                <td scope='row' class="td__profileName">{{ucfirst($profileRate->name)}}</td>
                                <td scope='row'> <input type="number" class='form-control' 
                                  placeholder='0' 
                                  style='border: none; text-align: center;'
                                  name="{{ucfirst($profileRate->name)}}" value="{{$profileRate->rate}}" step="0.01">
                                </td>
                              </tr>
                              @endif
                              @endforeach
                            </tbody>
                          </table>
                        </center>
                      </div>
                    </div>
                    <div class="col-xs-12" style="margin-top: 20px">
                      <div class="pull-right ">
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
        <!-- CONTENT END -->
      </div>
      @endsection
      @section('ownjs')
      <script>
        function showpass() {
          var x = document.getElementById("inputPassword");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
          $('.toggleClass').toggleClass('fa-eye fa-eye-slash');
        }
        function showpass2() {
          var x = document.getElementById("retype_password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
          $('.toggleClass2').toggleClass('fa-eye fa-eye-slash');
        }
      </script>
      <script type="text/javascript">
        function mycheckfunc(val,id){
          var va="#"+id;
          if($(va).attr("title") == "uncheck"){
            var markup = "<tr id='"+id+"tr'><td scope='row'>"+id+"</td><td scope='row'> <input type='number' class='form-control' required name='"+id+"' min='50' placeholder=0 step='0.01' style='border: none; text-align: center;''></td></tr>";
            $(".tbody").append(markup);
            $(va).attr('title', 'check');
          } else if($(va).attr("title") == "check"){
            var trvar=va+"tr";
            $(trvar).remove();
            $(va).attr('title', 'uncheck');
          }
        }
      </script>
      @endsection
<!-- Code Finalize -->