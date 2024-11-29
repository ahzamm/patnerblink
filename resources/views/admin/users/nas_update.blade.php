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
   <section id="main-content">
      <section class="wrapper main-wrapper">
         <div class="header_view">
            <h2>NAS Update</h2>
         </div>
         <div class="">
            <section class="box ">
               <div class="content-body" style="padding-top: 20px">
                  <form  
                  action="{{route('admin.router.update',['id' => $nas->id])}}" method="POST">
                  @csrf
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group position-relative">
                           <label for="" class="form-label">NAS IP Address <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_ip_address_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->nasname}}" name="nasname" class="form-control" id="" placeholder="Example: 192.168.100.100" required>
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">NAS Name <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->server}}" name="server" class="form-control"  placeholder="Example: Mikrotik-KHI1" >
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">NAS Shortname <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_shortname_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->shortname}}" name="shortname" class="form-control"  placeholder="Example: MT-KHI" required>
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label" for="type"> Update (BRAS) Brand <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_brand_name_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <select name="type" class="form-control" id="brands">
                              <option value="">Select (BRAS) Brand</option>
                              <option value="Juniper" @if($nas->type == 'Juniper')selected @endif>Juniper</option>
                              <option value="Cisco" @if($nas->type == 'Cisco')selected @endif>Cisco</option>
                              <option value="Mikrotik" @if($nas->type == 'Mikrotik')selected @endif>Mikrotik</option>
                              <option value="Huawei" @if($nas->type == 'Huawei')selected @endif>Huawei</option>
                              <option value="Nortel" @if($nas->type == 'Nortel')selected @endif>Nortel</option>
                              <option value="Tenda" @if($nas->type == 'Tenda')selected @endif>Tenda</option>
                              <option value="Asus" @if($nas->type == 'Asus')selected @endif>Asus</option> 
                              <option value="Netgear" @if($nas->type == 'Netgear')selected @endif>Netgear</option>
                           </select>
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label" for="type"> Carrier <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_carrier_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <select name="carrier" class="form-control" id="carrier">
                              <option value="">Select</option>
                              <option value="cyber" @if($nas->carrier == 'cyber')selected @endif>Cyber</option>
                              <option value="logon" @if($nas->carrier == 'logon')selected @endif>Logon</option>
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group position-relative">
                           <label  class="form-label">Assgin (NAS) Port</label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_port_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->ports}}" name="ports" class="form-control"  placeholder="Example: 1812">
                        </div>
                        <div class="form-group position-relative" style="position:relative">
                           <label  class="form-label">Assgin (NAS) Secret <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_secret_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="password" value="{{$nas->secret}}" name="secret" placeholder="Example: 123456" id="nas_upass" class="form-control"  required>
                           <i class="fa fa-eye-slash nas_upass" style="position: absolute;bottom: 9px;right: 12px;" onclick="togglePassword('nas_upass');"> </i>
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">community</label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_community_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text"  value="{{$nas->community}}" name="community" placeholder="Example: MKT" class="form-control"   >
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">description <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->description}}" name="description" class="form-control" placeholder="Example: Karachi-DataCenter"  >
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">Radius Source IP <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->radius_src_ip}}" name="radius_src_ip" class="form-control" placeholder="192.168.100.10"  >
                        </div>
                        <div class="form-group position-relative">
                           <label  class="form-label">API Port <span style="color: red">*</span></label>
                           <span class="helping-mark" onmouseenter="popup_function(this, 'nas_description_assign_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                           <input type="text" value="{{$nas->api_port}}" name="api_port" class="form-control" placeholder="1300"  >
                        </div>
                     </div>
                     <div class="col-xs-12">
                        <div class="pull-right ">
                           <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                     </div>
                  </div>
               </form>
            </div>
         </section>
      </div>
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
<!-- Code Finalize -->