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
* Created: June, 2023
* Last Updated: 08th June, 2023
* Author: Talha Fahim & Hasnain Raza <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('admin.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
  section.box{
    padding: 15px;
  }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
  <section id="main-content">
    <section class="wrapper main-wrapper">
      <div class="header_view">
        <h2>Ticker Management
          <span class="info-mark" onmouseenter="popup_function(this, 'ticker_Managment_admin_side');" onmouseleave="popover_dismiss();"><i class="las la-info-circle"></i></span>
        </h2>
      </div>
      <section style="padding: 15px;">
        <div>
          @if(session('error'))
          <div class="alert alert-error alert-dismissible">
            {{session('error')}}
          </div>
          @endif
          @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            {{session('success')}}
          </div>
          @endif
        </div>
        <form action="{{route('admin.store.headline')}}" method="POST">
          @csrf
          <div class="row">
            <div>
              <section class="box" style="overflow: hidden;">
                <div class="form-group position-relative">
                  <label for="english" ><strong>Assgin Description (In English) </strong></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'tickers_english_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <textarea class="ckeditor form-control" name="english_content" rows="4">{{$ticker['english_content']}}</textarea>
                </div>
                <div class="form-group position-relative">
                  <label for="urdu"><strong>Assgin Description (In Urdu) :</strong></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'tickers_urdu_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <textarea class="ckeditor form-control" name="urdu_content" rows="4">{{$ticker['urdu_content']}}</textarea>
                </div>
                <div class="form-group position-relative">
                  <label for="announcement"><strong>Announcement:</strong></label>
                  <span class="helping-mark" onmouseenter="popup_function(this, 'announcement_admin_side');" onmouseleave="popover_dismiss();"><i class="fa fa-question"></i></span>
                  <textarea class="ckeditor form-control" name="announcement_content" rows="4">{{$ticker['announcement_content']}}</textarea>
                </div>
                <div class="col-md-12" >
                  <button type="submit" class="btn btn-success pull-right">Submit</button>
                </div>
              </section>
            </div>        
          </div>
        </form>
      </section>
    </section>
  </section>
</div>
<script type="text/javascript" src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>
@endsection
@section('ownjs')
<script type="text/javascript">
  $(document).ready(function() {
    $('.ckeditor').ckeditor();
    window.CKEDITOR||(window.CKEDITOR=function(){var o,d=/(^|.*[\\\/])ckeditor\.js(?:\?.*|;.*)?$/i,e={timestamp:"",version:"%VERSION%",revision:"%REV%",rnd:Math.floor(900*Math.random())+100,_:{pending:[],basePathSrcPattern:d},status:"unloaded",basePath:function(){var t=window.CKEDITOR_BASEPATH||"";if(!t)for(var e=document.getElementsByTagName("script"),n=0;n<e.length;n++){var a=e[n].src.match(d);if(a){t=a[1];break}}if(-1==t.indexOf(":/")&&"//"!=t.slice(0,2)&&(t=0===t.indexOf("/")?location.href.match(/^.*?:\/\/[^\/]*/)[0]+t:location.href.match(/^[^\?]*\/(?:)/)[0]+t),!t)throw'The CKEditor installation path could not be automatically detected. Please set the global variable "CKEDITOR_BASEPATH" before creating editor instances.';return t}(),getUrl:function(t){return-1==t.indexOf(":/")&&0!==t.indexOf("/")&&(t=this.basePath+t),t=this.appendTimestamp(t)},appendTimestamp:function(t){if(!this.timestamp||"/"===t.charAt(t.length-1)||/[&?]t=/.test(t))return t;var e=0<=t.indexOf("?")?"&":"?";return t+e+"t="+this.timestamp},domReady:(o=[],function(t){if(o.push(t),"complete"===document.readyState&&setTimeout(i,1),1==o.length)if(document.addEventListener)document.addEventListener("DOMContentLoaded",i,!1),window.addEventListener("load",i,!1);else if(document.attachEvent){document.attachEvent("onreadystatechange",i),window.attachEvent("onload",i);var e=!1;try{e=!window.frameElement}catch(n){}document.documentElement.doScroll&&e&&!function a(){try{document.documentElement.doScroll("left")}catch(n){return void setTimeout(a,1)}i()}()}})};function i(){try{document.addEventListener?(document.removeEventListener("DOMContentLoaded",i,!1),window.removeEventListener("load",i,!1),n()):document.attachEvent&&"complete"===document.readyState&&(document.detachEvent("onreadystatechange",i),window.detachEvent("onload",i),n())}catch(t){}}function n(){for(var t;t=o.shift();)t()}var a,r=window.CKEDITOR_GETURL;return r&&(a=e.getUrl,e.getUrl=function(t){return r.call(e,t)||a.call(e,t)}),e}());
    /* jscs:enable */
    /* jshint ignore:end */
    if ( CKEDITOR.loader )
      CKEDITOR.loader.load( 'ckeditor' );
    else {
// Set The Script Name To Be Loaded By The Loader.
CKEDITOR._autoLoad = 'ckeditor';
// Include The Loader Script.
if ( document.body && ( !document.readyState || document.readyState == 'complete' ) ) {
  var script = document.createElement( 'script' );
  script.type = 'text/javascript';
  script.src = CKEDITOR.getUrl( 'core/loader.js' );
  document.body.appendChild( script );
} else {
  document.write( '<script type="text/javascript" src="' + CKEDITOR.getUrl( 'core/loader.js' ) + '"></script>' );
}
}
});
</script>
@endsection
<!-- Code Finalize -->