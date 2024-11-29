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
{{-- @include('users.layouts.bytesConvert') --}}
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
   /* Blink Start */
   .blink {
      -webkit-animation: 1.3s linear infinite condemned_blink_effect; // for android
      animation: 1.3s linear infinite condemned_blink_effect;
      color: #f70808;
      font-size: 20px;
   }
   @-webkit-keyframes condemned_blink_effect { // for android
      0% {
         visibility: hidden;
      }
      50% {
         visibility: hidden;
      }
      100% {
         visibility: visible;
      }
   }
   @keyframes condemned_blink_effect {
      0% {
         visibility: hidden;
      }
      50% {
         visibility: hidden;
      }
      100% {
         visibility: visible;
         } }
         /* Blink End */
      </style>
      @endsection
      @section('content')
      <div class="page-container row-fluid container-fluid">
         <section id="main-content">
            <section class="wrapper main-wrapper">
               <div class="header_view">
                  <h2>Consumers (IDs) Internet Data Exceed
                     <span class="info-mark" onmouseenter="popup_function(this, 'exceed_internet_data_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                  </h2>
               </div>
               <section class="box ">
                  <header class="panel_header">
                     <h2 class="title pull-left"></h2>
                  </header>
                  <div class="content-body">
                     <table id="example-1" class="table table-borderd dt-responsive display w-100">
                        <thead>
                           <tr>
                              <th style="width:25px">Serial#</th>
                              <th>Counsumer (ID)</th>
                              <th>Reseller (ID)</th>
                              <th>Contractor (ID)</th>
                              <th>Trader (ID)</th>
                              <th>Current Data Usage</th>
                              <th>Exceed Internet Data(Date&Time)</th>
                              <th>Internet Profile(FUP)</th>
                           </tr>
                        </thead>
                        <tbody>
                           @php
                           $no = 0;
                           foreach ($exData as $key => $value) {
                           $no++;
                           $profiles = str_replace("BE-","",$value['quota_profile']);
                           $profiles = str_replace("kfup","",$profiles);
                           $profilename = App\model\Users\Profile::where('groupname',$profiles)->select('name')
                           ->first();
                           @endphp
                           <tr>
                              <td>{{$no}}</td>
                              <td>{{$value['username']}}</td>
                              @php $data = App\model\Users\UserInfo::where('username',$value['username'])->first();
                              @endphp
                              <td>{{$data['resellerid']}}</td>
                              <td>{{$data['dealerid']}}</td>
                              <td>{{$data['sub_dealer_id']}}</td>
                              <td><span class="blink">{{round($value['data']/1099511627776)." TB"}}</span></td>
                              <td>{{$value['datetime']}}</td>
                              <td>{{$profilename['name']}}</td>
                           </tr>
                           @php
                        }
                        @endphp
                     </tbody>
                  </table>
               </div>
            </section>
         </section>
      </section>
   </div>
   @endsection
   <script type="text/javascript"></script>
   @php
   function ByteSizee($bytes)
   {
      $size = $bytes / 1024;
      if ($size < 1024) {
      $size = number_format($size, 2);
      $size .= ' KB';
   } else {
   if ($size / 1024 < 1024) {
   $size = number_format($size / 1024, 2);
   $size .= ' MB';
} else if ($size / 1024 / 1024 < 1024) {
$size = number_format($size / 1024 / 1024, 2);
$size .= ' GB';
} else if ($size / 1024 / 1024 / 1024 < 1024) {
$size = number_format($size / 1024 / 1024 / 1024, 2);
$size .= ' TB';
} else if ($size / 1024 / 1024 / 1024 / 1024 < 1024) {
$size = number_format($size / 1024 / 1024 / 1024 / 1024, 2);
$size .= ' PB';
}
}
$size = preg_replace('/.00/', '', $size);
return $size;
}
@endphp
<!-- Code Finalize -->