<!DOCTYPE html>
<html>

@php

 $url = url()->current();
 $parse = parse_url($url);
 $host=explode(".",$parse['host']);

 $username = DB::table('user_info')
    ->where('username','=',$host[1])
    ->get();

@endphp

<?php
 $url = url()->current();
 $urlPart = explode('.',$url); 

 $domainDetail = DB::table('domain')->where('domainname','like','%'.$urlPart[1].'%')->where('resellerid','!=',NULL)->first();
 $favicon =   asset('img/favicon/'.$domainDetail->favicon);
?>

<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title><?= strtoupper($urlPart[1]);?> | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{$favicon}}" type="image/x-icon" />
        <!-- Favicon -->
    <link rel="apple-touch-icon-precomposed" href="{{('images/apple-touch-icon-57-precomposed.png')}}"> <!-- For iPhone -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{('images/apple-touch-icon-114-precomposed.png')}}">    <!-- For iPhone 4 Retina display -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{('images/apple-touch-icon-72-precomposed.png')}}">    <!-- For iPad -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{('images/apple-touch-icon-144-precomposed.png')}}">    <!-- For iPad Retina display -->
    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{asset('plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Fontawesome 4 -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0-10/css/all.css" /> -->
    <!--  -->
    <link href="{{asset('fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('fonts/font-awesome/css/all.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/select2/select2.css')}}" rel="stylesheet" type="text/css"/>
    <!--  -->
    <link href="{{asset('css/button.css')}}" rel="stylesheet" type="text/css"/>
    <!--  -->
    <!-- CORE CSS FRAMEWORK - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    {{-- <link href="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.1.css')}}" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END --> --}}
    <!-- CORE CSS TEMPLATE - START -->

    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/dashboard.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet" type="text/css"/>
    <?php 
    $url = url()->current();
    $parse = parse_url($url);
    if (strpos($parse['host'], 'logon') !== false) { ?>
        <link rel="manifest" href="/manifest.json">
    <?php } else if (strpos($parse['host'], 'blinkbroadband') !== false) {?>
        <link rel="manifest" href="/manifest_blink.json">
    <?php } else if (strpos($parse['host'], 'gobroadband') !== false) { ?>
        <link rel="manifest" href="/manifest_go.json">
    <?php } else if (strpos($parse['host'], 'lightup') !== false) { ?>
        <link rel="manifest" href="/manifest_lightup.json">
    <?php } ?>
    @php

     $theme="";

    /*dynamic theme load start */

    $theme_loading = DB::table('partner_themes_user')
    ->where('username','=',$host[1])
    ->get();



    $check = DB::table('freeze_account')->where('username',Auth::user()->username)->where('freeze','yes')->where('status','!=','manager')->first();
    $check_account="";
    if(!empty($check))
    {
        $check_account = strtoupper("Freezed by ".$check->freezed_by);
    }



     /*dynamic theme load end */

     if(isset($theme_loading[0]->color) && !empty($theme_loading[0]->color)){
         if($theme_loading[0]->color!="default"){
           $theme=$theme_loading[0]->color;
       }
     }


    if(!empty($theme) && isset($theme)) {
     @endphp

     <link href="{{asset('css/themes')}}/{{$theme}}.css" rel="stylesheet" type="text/css"/>

   @php
    }  
   @endphp



    <link rel="stylesheet" href="{{('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css')}}" />

<!-- <link href="{{asset('plugins/jquery-ui/smoothness/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen"/> -->
    <link href="{{asset('plugins/colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- CORE CSS TEMPLATE - END -->

    <!-- data table -->
    <link href="{{asset('plugins/datatables/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- end data table -->
    <link href="{{asset('plugins/daterangepicker/css/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{asset('switchToggle/lc_switch.css')}}" rel="stylesheet" type="text/css">
<!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    @yield('owncss')
    <style type="text/css">
        #main-menu-wrapper li.treeview.active>a,
        #main-menu-wrapper li.open .sub-menu>.open a{
            background-color: rgba(33, 33, 33, 0.1);
            border-left: 4px solid #3F51B5;
        }
    </style>
    <script>
        // Get Operating System eg. Windows , Android, IOS
    function getOS() {
        var userAgent = window.navigator.userAgent,
        platform = window.navigator?.userAgentData?.platform || window.navigator.platform,
        macosPlatforms = ['Macintosh', 'MacIntel', 'MacPPC', 'Mac68K'],
        windowsPlatforms = ['Win32', 'Win64', 'Windows', 'WinCE'],
        iosPlatforms = ['iPhone', 'iPad', 'iPod'],
        os = null;
 
        if (macosPlatforms.indexOf(platform) !== -1) {
            os = 'Mac OS';
        } else if (iosPlatforms.indexOf(platform) !== -1) {
            os = 'iOS';
        } else if (windowsPlatforms.indexOf(platform) !== -1) {
            os = 'Windows';
        } else if (/Android/.test(userAgent)) {
            os = 'sidebar_shift';
        } else if (/Linux/.test(platform)) {
            os = 'Linux';
        }
        return os;
    }
// console.log(getOS())
</script>
</head>
<body>

	@include('users.layouts.nav')

	@include('users.dealer.model_sub_dealer')
 
    @include('users.dealer.add_user')

    <?php if(App\MyFunctions::check_access('Complaint Feedback',Auth::user()->id)){ ?>
        @include('users.complain.complainPopup')
    <?php } ?>
   
   
<!-- Popover start -->
<div id="hover_tooltip" role="tooltip">
   <p id="json_data"></p>
</div>
<!-- Popover end -->

     <div id="snackbar"></div>
   

    @yield('content')

    @include('users.layouts.footer')

    <script src="{{asset('js/zingchart.min.js')}}"></script>
    <script src="{{asset('js/online_gauge.js')}}"></script>
    <script src="{{asset('js/profile_gauge.js')}}"></script>
    <script src="{{asset('js/canvasjs.min.js')}}"></script>
    <!-- END CONTAINER -->
    <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->
    <!-- CORE JS FRAMEWORK - START -->
    <script src="{{asset('js/jquery-1.11.2.min.js')}}" ></script>
    <script src="{{asset('js/popper.min.js')}}"></script>

    <script src="{{asset('js/jquery.easing.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}" ></script>
    <script src="{{asset('plugins/pace/pace.min.js')}}" ></script>
    <script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}" ></script>
    <script src="{{asset('plugins/viewport/viewportchecker.js')}}" ></script>
    <script>window.jQuery || document.write('<script src="{{asset('js/jquery-1.11.2.min.js')}}"><\/script>');</script>
    <!-- CORE JS FRAMEWORK - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    {{-- <script src="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.1.min.js')}}" ></script> --}}
    {{-- <script src="{{asset ('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" ></script> --}}
    {{-- <script src="{{asset('js/dashboard.js')}}" ></script> --}}
    {{-- <script src="{{asset('plugins/echarts/echarts-custom-for-dashboard.js')}}" ></script> --}}
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <!-- CORE TEMPLATE JS - START -->
    <script src="{{asset('js/scripts.js')}}"></script>
    <!-- END CORE TEMPLATE JS - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    {{-- <script src="{{asset('plugins/easypiechart/jquery.easypiechart.min.js')}}"></script> --}}
    <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <script src="{{asset('js/chart-easypie.js')}}"></script>
    <script src="{{asset('js/chart-knobs.js')}}" ></script>

    
    <script src="{{asset('js/inword.js')}}" ></script>
     <!-- export CSV -->
    <script src="{{asset('js/export_csv.js')}}" ></script>
    <!-- end -->
    <script src="{{asset('js/jquery.steps.js')}}" ></script>
    <script src="{{asset('js/wizard.js')}}" ></script>
    <script src="{{asset('js/jquery.validate.min.js')}}" ></script>

<!-- <script src="{{asset('plugins/echarts/echarts-all.js')}}" ></script>
    <script src="{{asset('js/chart-echarts.js')}}" ></script> -->
    <script src="{{asset('plugins/count-to/countto.js')}}"></script>
    <!-- <script src="{{('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js')}}"></script> -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
    <script src="{{asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <!-- General section box modal start -->

    <!-- <script src="{{asset('plugins/jquery-ui/smoothness/jquery-ui.min.js')}}" type="text/javascript"></script> -->
    <script src="{{asset('plugins/select2/select2.min.js')}}" type="text/javascript"></script> 
    <script src="{{asset('plugins/colorpicker/js/bootstrap-colorpicker.min.js')}}" type="text/javascript"></script> 

    <!-- data table -->

    <script src="{{asset('plugins/datatables/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.js')}}" type="text/javascript"></script>
    <!-- end data table -->
    <script src="{{asset('plugins/daterangepicker/js/moment.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('plugins/daterangepicker/js/daterangepicker.js')}}" type="text/javascript"></script> 
  
    <script src="{{asset('switchToggle/lc_switch.js')}}" type="text/javascript"></script>
    <!-- <script src="{{asset('js/popper.min.js')}}"></script> -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    <script>
        // $(document).ready(function() {
        //     let installPrompt = null;
        //     const installButton = document.querySelector("#install_btn");
        //     const pwaPrompt = document.querySelector("#pwa_prompt");
        //     const cancelButton = document.querySelector("#cancel_btn");

        //     window.addEventListener("beforeinstallprompt", (event) => {
        //         event.preventDefault();
        //         installPrompt = event;
        //         installButton.removeAttribute("hidden");
        //         pwaPrompt.removeAttribute("hidden");
        //         console.log('beforeinstallprompt');
        //     });

        //     installButton.addEventListener("click", async () => {
        //         if (!installPrompt) {
        //             return;
        //         }
        //         const result = await installPrompt.prompt();
        //         installPrompt = null;
        //         installButton.setAttribute("hidden", "");
        //         pwaPrompt.setAttribute("hidden", "");
        //     });
        //     cancelButton.addEventListener("click", async () => {
        //         pwaPrompt.setAttribute("hidden", "");
        //         installButton.setAttribute("hidden", "");
        //     });
        // })
</script>
        <script>
     $(document).ready(function(){
        $('.js-select2').select2();
        // $('.js-select2-popup').select2({
        //                 dropdownParent: $(".complaint__container")
        //             });
        $('[data-toggle="tooltip"]').tooltip();

        $.ajaxSetup({
           headers:
           { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
       });

        $('.nic').mask('00000-0000000-0');
        $('.mobile').mask('0000-0000000');
    });
    function copyToClipboard() {
        let copyGfGText = document.querySelector(".copy-password");
        copyGfGText.select();
        copyGfGText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyGfGText.value);
        console.log("Copied the text: " + copyGfGText.value);
    }
</script>

<script>
    function togglePassword(value) {
        var x = document.getElementById(value);
        if (x.type === "password") {
        x.type = "text";
        } else {
        x.type = "password";
        }
        $('.'+value).toggleClass('fa-eye fa-eye-slash');
    }
    $(document).ready(function(){
        $(".site-footer").css("padding-left" , "270px");

        $('.sidebar_toggle').click(function(){
            if($('#main-content').hasClass('sidebar_shift')){
                
                setTimeout(() => {
                    $('.freez__class').css('display', 'block');
                }, 500);
                $(".site-footer").css("padding-left", "270px");
                // $('.logo-area').css('background-image','url(/img/logonbroadband-logo.jpg)');

            }
            else{
                // $('.logo-area').css('background-image','url(/img/logon-logo-sm.png)');
                $(".site-footer").css("padding-left", "60px");
                $('.freez__class').css('display', 'none');
                console.log('class removed');
            }
            
        })
        $('.sidebar_toggle').click(function(){
            if($(window).width() < 768){
                console.log('Mobile size');
                if($('.page-topbar').hasClass('sidebar_shift')){
                    $('.nav_close_btn').css('display', 'block');
                    $('.freez__class').css('display', 'block');
                }else{
                    $('.nav_close_btn').css('display', 'none');
                    // $('.freez__class').css('display', 'none');
                }
            }
        })

        $(document).ready(function() {
            if($(window).width() < 768){
                $('.freez__class').css('display', 'none');
            }else{
                $('.freez__class').css('display', 'block');

            }
        })
        $('.nav_close_btn').on('click', function() {
            $('.page-topbar').addClass('sidebar_shift');
            $('.page-sidebar').removeClass('expandit');
            $('.page-sidebar').addClass('collapseit');
            $('.nav_close_btn').css('display', 'none')
            $('.freez__class').css('display', 'none');
        })
        
    })
    $(window).load(function(){
        var url = window.location;

        $('ul.wraplist li a').filter(function() {
            return this.href == url;
        }).parent().addClass('open');

        $('ul.sub-menu li.open a').filter(function() {
            return this.href == url;
        }).closest('.treeview').addClass('open active');

        $('ul.sub-menu li.open a').filter(function() {
            return this.href == url;
        }).parent().parent().addClass('show');
        $("li.treeview a").click(function(){
            $('ul.sub-menu').removeClass('show');
        })
        if($('.page-sidebar').hasClass('collapseit')){
            $('.sub-menu').removeClass('show');
        }
        
    })
    $(window).scroll(function(){
        if($('#main-content').hasClass('sidebar_shift')){
            $(".site-footer").css("padding-left", "60px");
        }
        else{
            $(".site-footer").css("padding-left", "270px");
        }
    })

    	
	function snack(color,text,icon) {	
		var x = document.getElementById("snackbar");
		$('#snackbar').css('background-color', color);
		$('#snackbar').html('<i class="fa fa-'+icon+'"></i> '+text);
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	}
</script>

<script>
  $(document).ready(function() {
    $(document).on('click', '.agent-account', function(event) {
      event.preventDefault();
      id = $(this).attr('data-id');
      username = $(this).attr('data-username');
      status = $(this).attr('data-status');

      var token = $("meta[name='csrf-token']").attr("content");

      $.ajax({
        type: 'POST',
        url: "{{route('users.access.disable')}}",
        data: {
          status: status,
          id: id,
          username: username
        },
        success: function(data) {

          if ($.isEmptyObject(data.error)) {
            $(".print-error-msg").css('display', 'none');
            $(".success-msg").css('display', 'block');
            $('.success-msg').html(data.success);
            location.reload(3000);
          } else {
            printErrorMsg(data.error);
          }
        }
      })

    });

    function printErrorMsg(msg) {
      $(".print-error-msg").find("ul").html('');
      $(".success-msg").css('display', 'none');
      $(".print-error-msg").css('display', 'block');
      $.each(msg, function(key, value) {
        $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
      });
    }
  });
</script>

<script>
     var popup = $('#hover_tooltip');
    popup.hide();
    function popup_function(id, value){
      fetch('/tooltip.json')
      .then((response) => response.json())
      .then((json) => {
        console.log(json[value]);
        $('#json_data').html(json[value]);
      })
      .catch(error => {
        console.log('Fetch Error: ' + error);
      })
      // console.log(id);
      popup.show(); 
      var popper = new Popper(id,popup,{
         placement: 'left',
         onCreate: function(data){
            console.log(data);
         },
         modifiers: {
            flip: {
               behavior: ['left', 'right', 'top','bottom']
            },
            offset: { 
               enabled: true,
               offset: '0,5'
            }
         }
      });
   };
   function popover_dismiss(){
      popup.hide();
   }
</script>
@yield('script')
@php
$session = session()->get('test');
 $loginCheck =  App\model\Users\LoginAudit::where('sessionid',$session)->where('status','OUT')->get();
$count = count($loginCheck);
 if($count > 1){
    Auth::logout();
@endphp
<script>
    window.location = "{{route('users.login.show')}}";
</script>
@php
 }
@endphp
@yield('ownjs')
</body>

<div class="modal" id="section-settings" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog animated bounceInDown">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Section Settings</h4>
            </div>
            <div class="modal-body">
                Body goes here...
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal end -->
</html>