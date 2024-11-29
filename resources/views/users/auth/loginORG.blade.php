<!DOCTYPE html>
<html class=" ">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>PARTNER | LOGON</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CORE CSS FRAMEWORK - START -->
    <link href="{{asset('plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS FRAMEWORK - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    <link href="{{asset('plugins/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" media="screen"/>        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS TEMPLATE - END -->
    <style type="text/css">
        #wp-submit:hover{
            /*background-color: #225094c9;*/
        }
        #captcha{
            font-size: 25px;
            font-weight: bold;
            color: black;
        }
        .fa-refresh{
            cursor: pointer;
        }
        label{
            color: black;
        }
        #loading{
            display: none;
        }
        .btn:hover{
            background: transparent;
        }
        /* html {
        background:{{asset('images/map.jpg')}} no-repeat  center fixed !important;
        -webkit-background-size: cover !important;
        -moz-background-size: cover !important;
        -o-background-size: cover !important;
        background-size: cover !important;
        }*/
        body{
            background-size: 100%;
            height: 100%;
        }
        .slogan{
            font-size: 42px;
            color: white;
            font-family: inherit;
            letter-spacing: 5px;
            line-height: 49px;
        }
        .btn-accent{
            background: #001254 !important;
        }
        #enablebtn{
            display: none;
        }
        b {
            color: white;
            position: relative;
            left: -10px;
        }
        #sidebar {
            height: 250px;
            width: 10px;
            position: fixed;
            text-align: center;
            padding: 10px;
            margin-left: 10px;
        }
        b i {
            left: 30px;
            position: relative;
        /*vertical-align: middle;
        text-align: center;*/
        font-size: 35px;
    }
    .social {
        margin-left: -162px;
        width: 273px;
        padding: 0;
        display: inline-table;
        height: 0px;
        background-color: transparent !important;
        -moz-transition-property: margin-left;
        -moz-transition-duration: 0.2s;
        -moz-transition-delay: 0.2s;
        -ms-transition-property: margin-left;
        -ms-transition-duration: 0.2s;
        -ms-transition-delay: 0.2s;
        -o-transition-property: margin-left;
        -o-transition-duration: 0.2s;
        -o-transition-delay: 0.2s;
        -webkit-transition-property: margin-left;
        -webkit-transition-duration: 0.2s;
        -webkit-transition-delay: 0.2s;
        box-shadow: 0px 0px 6px 0px #3E3D3D;
        cursor: pointer;
    }
    .social:hover {
        margin-left:-25px;
        width:280px;
        background-color: transparent  !important;
    }
    .facebook:hover {
        background-color: transparent !important;
    }
    @media only screen and (max-width: 411px) {
        #sidebar{
            display: none;
        }
    }
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login_page">
    <div id="sidebar">
        <div class="social facebook">
            <p> +922138402064  <i class="fa fa-phone-square">  Customer Support </i>
            </p>
        </div>
    </div>
    <div class="container">
        <div class="login-wrapper row">
            <div class="col-xs-12 col-sm-6 col-lg-8 hidden-xs" style="margin-top:22%;text-align: center;" >
                <span class="slogan"> We're Changing The World With Technology</span>

            </div>
            <div id="login" class="login loginpage col-xs-12 col-sm-6 col-lg-4" style="background-color: #d5d5e54d;padding-bottom: 20px;color: #ffffff;border-radius: 8px;margin-top: 105.5px;">
                <h1> <a href="#" title="Login Page"  tabindex="-1">LOGIN </a> </h1>
                <form id="loginform" action="{{route('users.login.post')}}" method="POST">
                    @csrf
                    <p>
                        <label for="user_login">Username<br />
                            <input type="text" name="username" id="user_login" class="input" size="20" placeholder="Username" value="{{ old('username') }}" required autofocus /></label>
                            <br>
                            @if($errors->has('username'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </p>
                        <p>
                            <label for="user_pass">Password<br />
                                <input type="password" name="password" id="user_pass" onfocus="paswordText()" class="input" size="20" placeholder="Password" required="" />
                                <span class="btn-show-pass" style="float: right;color: black;position: absolute;margin-top: -49px;margin-left: 266px;" onclick="shwopass()">
                                    <i class="fa fa-eye"></i>
                                </span>
                            </label>
                            <br>
                            @if($errors->has('password'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </p>
                        <p>
                            <input type="hidden" name="answer" id="sum">
                            <label for="user_login">Answer of :
                                <!-- <img src="cap.php"> -->
                                &nbsp&nbsp&nbsp&nbsp
                                <span id="captcha"><span id="first"></span>+<span id="second"></span></span>
                                &nbsp&nbsp&nbsp&nbsp<i class="fa fa-refresh" style="font-size: 20px;color: #ffffff;" onclick="captcha()"></i>
                                <br />
                                <input type="number" name="log" id="user" class="input" value="" size="20" placeholder="=" required="" onkeyup="check()" /></label>
                            </p>
                            <p id="output" style="font-size: 16px;color: #ff6f10;"></p>
                            <p class="submit" >
                                <input onclick="submit()" type="button" name="wp-submit" id="enablebtn" class="btn btn-accent btn-block" value="Sign In"  style="border: 1px solid black;"/>
                                <input type="button" name="wp-submit" id="disablebtn" class="btn btn-accent btn-block" disabled="" value="Sign In"  style="border: 1px solid black;"/>
                                <center>
                                    <img id="loading" src="{{asset('img/loading.gif')}}" class="img-responsive" width="15%">
                                    <!-- <p id="output" style="font-size: 20px;color: red;">sdasad</p> -->
                                </center>
                            </p>
                        </form>
                        </head>
                        <body>
                        </div>
                    </div>
                </div>
                <!-- captcha answer -->
                
                <script type="text/javascript">
                    function check(){
                        var sum= $("#sum").val();
                        var user= $("#user").val();
                //
                if(user == ''){
                    $("#output").html("");
                    $("#disablebtn").show();
                    $("#enablebtn").hide();
                }else if(user == sum){
                    $("#output").html("");
                    $("#disablebtn").hide();
                    $("#enablebtn").show();
                }else{
                    $("#output").html("Incorrect Answer");
                    $("#disablebtn").show();
                    $("#enablebtn").hide();
                }
            }
            function submit() {
                document.getElementById("loginform").submit();
            }
            function shwopass()
            {
                var x=document.getElementById("user_pass");
                if (x.type=="password"){
                    x.type="text";
                }
                else{
                    x.type="password";
                }
            }
        </script>

        <script>
    document.getElementById('user').addEventListener('keypress', function(event) {
        var sum= $("#sum").val();
        var user= $("#user").val();
        if (event.keyCode == 13) {
            if(user == sum){
            event.preventDefault();
            submit();
            }else{
            }
        }
    });
</script>
        <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->
        <!-- CORE JS FRAMEWORK - START -->
        <script src="{{asset('js/jquery-1.11.2.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/jquery.easing.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('plugins/pace/pace.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('plugins/viewport/viewportchecker.js')}}" type="text/javascript"></script>
        <script>window.jQuery||document.write('<script src="{{asset('js/jquery-1.11.2.min.js')}}"><\/script>');</script>
        <!-- CORE JS FRAMEWORK - END -->
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
        <script src="{{asset('plugins/icheck/icheck.min.js')}}" type="text/javascript"></script>
        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
        <!-- CORE TEMPLATE JS - START -->
        <!-- <script src="{{asset('js/scripts.js')}}" type="text/javascript"></script> -->
    </body>
    <script src="{{asset('js/captcha.js')}}" type="text/javascript"></script>
    <!-- on login -->
    </html>