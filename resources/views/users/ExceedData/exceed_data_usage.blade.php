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
@include('users.layouts.bytesConvert')
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
            <section class="wrapper main-wrapper row">
               <div class="header_view">
                  <h2>Exceed <span style="color: lightgray"><small> (Internet Data Utilization)</small></span>
                     <span class="info-mark" onmouseenter="popup_function(this, 'sample');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
                  </h2>
               </div>
               <section class="box">
                  <div class="content-body" style="padding-top:20px">
                     <table id="example-1" class="table table-borderd display dt-responsive w-100">
                        <thead>
                           <tr>
                              <th style="width:25px">Serial#</th>
                              <th>Consumer (ID)</th>
                              <th>Reseller (ID)</th>
                              <th>Contractor (ID)</th>
                              <th>Trader (ID)</th>
                              <th>Current Data Utilization</th>
                              <th>Exceed Data Utilization (Date & Time)</th>
                              <th>FUP (Internet Profile)</th>
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