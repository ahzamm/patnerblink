<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>
        LOGON BROADBAND @yield('title')
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="shortcut icon" href="{{asset('img/favicon.png')}}" type="image/x-icon" />    <!-- Favicon -->
    <link rel="apple-touch-icon-precomposed" href="{{('images/apple-touch-icon-57-precomposed.png')}}"> <!-- For iPhone -->
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{('images/apple-touch-icon-114-precomposed.png')}}">    <!-- For iPhone 4 Retina display -->
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{('images/apple-touch-icon-72-precomposed.png')}}">    <!-- For iPad -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{('images/apple-touch-icon-144-precomposed.png')}}">    <!-- For iPad Retina display -->
    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{asset('plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0-10/css/all.css" /> -->

    <link href="{{asset('fonts/font-awesome/css/all.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!--  -->
    <link href="{{asset('css/button.css')}}" rel="stylesheet" type="text/css"/>
    <!--  -->
    <!-- CORE CSS FRAMEWORK - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    <link href="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.1.css')}}" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->

    <link rel="stylesheet" href="{{('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css')}}" />
    <link href="{{asset('plugins/jquery-ui/smoothness/jquery-ui.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/select2/select2.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- CORE CSS TEMPLATE - END -->
    <!-- data table -->
    <link href="{{asset('plugins/datatables/css/jquery.dataTables.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/TableTools/css/dataTables.tableTools.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/datatables/extensions/Responsive/bootstrap/3/dataTables.bootstrap.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- end data table -->
    <link href="{{asset('plugins/daterangepicker/css/daterangepicker-bs3.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('datetimepicker/flatpickr.min.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@500;600;700&display=swap" rel="stylesheet">
        <!-- CORE CSS TEMPLATE - START -->
        <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet" type="text/css"/>
    @yield('owncss')
    <style type="text/css">
        #loading{
            display: none;
        }
        .page-topbar {
           /* For browsers that do not support gradients */
           /*background-image: linear-gradient(to bottom right, #225094, #6dd5ed) !important;*/
           background-image: url("{{asset('img/nav_pic.png')}}")!important;


           -webkit-background-size: cover !important;
           -moz-background-size: cover !important;
           -o-background-size: cover !important;
           background-size: cover !important;

       }
       .page-topbar.sidebar_shift{

        background-image: url("{{asset('img/nav_pic.png')}}")!important;
    }
    #main-menu-wrapper li.open .sub-menu>.open a{
		background-color: rgba(33, 33, 33, 0.1);
		border-left: 4px solid #3F51B5;
	}
/*     .page-topbar {
            background-color: #225094 !important;
            background-image: linear-gradient(to bottom right, #225094, #6dd5ed) !important;
            }*/
        </style>
    </head>
    <body>
        <!-- Popover start -->
        <div id="hover_tooltip" role="tooltip">
            <p id="json_data"></p>
        </div>
        <!-- Popover end -->

        @include('admin.layouts.nav')
        
        
        @include('admin.AccessManagement.add_user')
        @include('admin.AccessManagement.edit_user')

       
        @include('admin.users.update_password')


        @yield('content')
        @include('admin.layouts.footer')



        <script src="{{asset('js/zingchart.min.js')}}"></script>
        <script src="{{asset('js/online_gauge.js')}}"></script>
        <script src="{{asset('js/profile_gauge.js')}}"></script>
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
        <script src="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.1.min.js')}}" ></script>
        <script src="{{asset ('plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}" ></script>
        <!-- <script src="{{asset('js/dashboard.js')}}" ></script> -->
        <script src="{{asset('plugins/echarts/echarts-custom-for-dashboard.js')}}" ></script>
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
        <!-- CORE TEMPLATE JS - START -->
        <script src="{{asset('js/scripts.js')}}"></script>

        <!-- END CORE TEMPLATE JS - END -->
        <script src="{{asset('js/canvasjs.min.js')}}"></script>

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
        <script src="{{asset('plugins/easypiechart/jquery.easypiechart.min.js')}}"></script>
        <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
        <script src="{{asset('js/chart-easypie.js')}}"></script>
        <script src="{{asset('js/chart-knobs.js')}}" ></script>

        <script src="{{asset('js/inword.js')}}" ></script>


        <!-- export CSV -->
        <script src="{{asset('js/export_csv.js')}}" ></script>
        <!-- end -->
        <!-- <script src="{{asset('plugins/echarts/echarts-all.js')}}" ></script>
            <script src="{{asset('js/chart-echarts.js')}}" ></script> -->
            <script src="{{asset('plugins/count-to/countto.js')}}"></script>
            <script src="{{('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js')}}"></script>
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

            <script src="{{asset('plugins/datatables/js/tableExport.js')}}" type="text/javascript"></script>
            <script src="{{asset('plugins/datatables/js/tableExport.min.js')}}" type="text/javascript"></script>

            <!-- end data table -->
            <!--  <script src="{{asset('plugins/jquery-ui/smoothness/jquery-ui.min.js')}}" type="text/javascript"></script>  -->
            <script src="{{asset('plugins/daterangepicker/js/moment.min.js')}}" type="text/javascript"></script>
            <script src="{{asset('plugins/daterangepicker/js/daterangepicker.js')}}" type="text/javascript"></script>
            <script src="{{asset('dist/jspdf.min.js')}}"></script>
            <script src="{{ asset('/datetimepicker/flatpickr.js')}}" type="text/javascript"></script>
            <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace( 'article-ckeditor', {
        height: 250,
      extraPlugins: 'colorbutton,colordialog'
    });
    CKEDITOR.replace("excerpt-editor", {
        height: 250,
      extraPlugins: 'colorbutton,colordialog'
    });
    CKEDITOR.on('instanceLoaded', function(e) {e.editor.resize(0, 350)} );
</script>
            <script>
                $(document).ready(function(){
                    $('.js-select2').select2();
                    $.ajaxSetup({
                        headers:
                        { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    });
                });
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
            </script>
            <script>
                $(window).load(function(){
                    if($('.page-sidebar').hasClass('collapseit')){
                        $(".site-footer").css("padding-left" , "60px");
                    }else{
                        $(".site-footer").css("padding-left" , "270px");
                    }
                })
                $(window).resize(function(){
                    if($('.page-sidebar').hasClass('collapseit')){
                        $(".site-footer").css("padding-left" , "60px");
                    }else{
                        $(".site-footer").css("padding-left" , "270px");
                    }
                })
                
                $(document).ready(function(){

                    
                    $('.sidebar_toggle').click(function(){
                        if($('#main-content').hasClass('sidebar_shift')){
                            $(".site-footer").css("padding-left", "270px");
                            console.log('class added');
                        }
                        else{
                            $(".site-footer").css("padding-left", "60px");
                            console.log('class removed');
                        }
                    })
                    $('.sidebar_toggle').click(function(){
                        if($(window).width() < 768){
                            console.log('Mobile size');
                            if($('.page-topbar').hasClass('sidebar_shift')){
                                $('.nav_close_btn').css('display', 'block')
                            }else{
                                $('.nav_close_btn').css('display', 'none')
                            }
                        }
                    })
                    $('.nav_close_btn').on('click', function() {
                        $('.page-topbar').addClass('sidebar_shift');
                        $('.page-sidebar').removeClass('expandit');
                        $('.page-sidebar').addClass('collapseit');
                        $('.nav_close_btn').css('display', 'none')
                    })
                    
                })
                $(document).ready(function(){
                    var url = window.location;
                    $('ul.sub-menu li.open a').filter(function() {
                        return this.href == url;
                    }).closest('.treeview').addClass('open');
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
            </script>
            <script type="text/javascript">
                (function () {
                    var method;
                    var noop = function noop() { };
                    var methods = [
                    'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
                    'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
                    'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
                    'timeStamp', 'trace', 'warn'
                    ];
                    var length = methods.length;
                    var console = (window.console = window.console || {});
                    while (length--) {
                        method = methods[length];
                        console[method] = noop;
                    }
                }());
                console.clear();
            </script>
            <script type="text/javascript">
                $(document).ready(function(){
                  $.ajax({
                        type: "GET",
                        url: "{{route('admin.ApprovedNewUserNotification')}}",
                        // data:'user='+val,
                        success: function(data){
                          if(data != 0)
                            $("#numUserCount").html(data);
                          else
                            $("#numUserCount").html('');
                        
                }
                });
                });
                  $('.showapprove').click(function(){
                    $.ajax({
                        type: "GET",
                        url: "{{route('admin.ApprovedNewUser')}}",
                        success: function(data){
                $('#newnotification').html(data);
                }
                });
                  });
                  function approveUser(id){
                   res = window.confirm("Do your want to Approve User");
                    if(res){
                      $.ajax({
                        type: "POST",
                        url: "{{route('admin.approveNewUserPost')}}",
                        data: {id:id},
                        success: function(data){
                        alert('User has been successfully Approved');
                        location.reload();
                }
                });
                    }
                  }
                  function rejectUser(id){
                   res = window.confirm("Do your want to Reject User");
                    if(res){
                      $.ajax({
                        type: "POST",
                        url: "{{route('admin.rejectNewUserPost')}}",
                        data: {id:id},
                        success: function(data){
                        alert('User has been  Rejected');
                        location.reload();
                
                }
                });
                    }
                  }
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