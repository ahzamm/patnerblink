<!DOCTYPE html>
<html class=" ">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Administrator</title>
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
    <link href="{{asset('plugins/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/responsive.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS TEMPLATE - END -->
    <style type="text/css">
        #wp-submit:hover{
        }
        #captcha{
            font-size: 25px;
            font-weight: bold;
            color: #ff6501;
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
        input.input{
            margin-bottom: 0 !important;
            font-size: 15px !important;
            padding: 6px 9px !important;
        }
        input.input:focus{
            outline: none !important;
            border: 1px solid #000 !important;
        }
        input.input:focus-visible{
            outline: none !important;
            border: 1px solid #000 !important;
        }
        body{
            background-size: 100%;
            height: 100%;
        }
        .slogan{
            position: absolute;
            width: 65%;
            margin: auto;
            left: 50%;
            bottom: 50px;
            font-size: 30px;
            color: white;
            font-family: inherit;
            letter-spacing: 3px;
            font-weight: bold;
            line-height: 49px;
            transform: translateX(-50%);
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
            z-index: 9;
            margin-left: 10px;
        }
        b i {
            left: 30px;
            position: relative;
            font-size: 35px;
        }
        .social {
            margin-left: -162px;
            width: 273px;
            padding: 0;
            display: inline-table;
            height: 0px;
            background-color: #000;
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
        }
        .facebook:hover {
            background-color: transparent !important;
        }
        @media only screen and (max-width: 411px) {
            #sidebar{
                display: none;
            }
        }
        .loginpage{
            padding: 1rem 40px;
            width: 430px;
            background-color: #cececf4d;
            padding-bottom: 20px;color: #ffffff;
            border-radius: 4px;
            margin-top: 100px;
            margin-bottom: 100px;
            margin-left: auto;
            margin-right: auto;
            box-shadow: 0px 0px 8px 0px rgb(0 0 0 / 50%);
        }
        .video_container{
            position: fixed;
            width: 100%;
            height: 100%;
        }
        #output,
        .invalid-feedback{
            color: #ff4141
        }
        @media (max-width: 992px) {
            .loginpage{
                margin-right: auto;
                margin-left: auto;
            }
        }
        @media (max-width: 576px) {
            .loginpage{
                width: 100%;
                padding: 1rem 25px;
                margin-top: 20px;
                margin-bottom: 20px;
            }
        }
        label.form__label{
            color: rgb(0,0,0) !important;
            font-weight: bold;
        }
        .login-wrapper{
            background-image: url('/images/admin-bgImg.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position:center;
        }
    </style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="login_page">
    <div id="sidebar" Class="visible-sm-block, hidden-sm">
        <div class="social visible-lg;">
            <b> 3 11 11 (LOGON)  <i class="fa fa-phone-square">  Customer HelpDesk </i> 
            </b>
        </div>
    </div>
    <div style="height:100%">
        <div class="login-wrapper row" style="margin: 0">
            <div id="login" class="login col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding: 0">
                <div class="loginpage" style="">
                    <h1> <a href="#" title="Login Page"  tabindex="-1" style="background-size: 65%;border-bottom: 1px solid #000;">LOGIN </a> </h1>
                    <form id="loginform" action="{{route('admin.login.post')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <p>    
                                <label for="user_login" class="form__label">Username<br />
                                    <input type="text" name="username" id="user_login" class="input" size="20" placeholder="Username" value="{{ old('username') }}" required autofocus /></label>
                                    <br>
                                </p>
                            </div>
                            <div class="form-group">
                                <p>
                                    <label for="user_pass" class="form__label" style="position: relative">Password <br/> 
                                        <input type="password" name="password" id="user_pass" class="input" size="20" placeholder="Password" required="" />
                                        <span class="btn-show-pass" style="color: black;position: absolute;right: 2px;bottom: -1px;padding: 10px;cursor: pointer;" onclick="shwopass()">
                                            <i class="fa fa-eye"></i></span> 
                                        </label>
                                        <br>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <p>
                                        <input type="hidden" name="answer" id="sum">
                                        <label for="user_login" class="form__label">Answer of :
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <span id="captcha"><span id="first"></span>+<span id="second"></span></span>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <i class="fa fa-refresh" style="font-size: 20px;color: #000;" onclick="captcha()"></i>
                                            <br />
                                            <input type="number" name="log" id="user" class="input" value="" size="20" placeholder="=" required="" onkeyup="check()" /></label>
                                        </p>
                                    </div>
                                    <p id="output" style="font-size: 16px;font-weight: bold"></p>
                                    @if($errors->has('username'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                    @endif
                                    <br>
                                    @if($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                    <div class="form-group">
                                        <p class="submit" >
                                            <input onclick="submit()" type="button" name="wp-submit" id="enablebtn" class="btn btn-block" value="Sign In"  style="border: 1px solid black;background: #ff6501 !important; color: #fff; padding: 8px 20px;"/>
                                            <input type="button" name="wp-submit" id="disablebtn" class="btn btn-block" disabled="" value="Sign In"  style="border: 1px solid black; background: #ff6501 !important; padding: 8px 20px;"/>
                                            <center>
                                                <img id="loading" src="{{asset('img/loading.gif')}}" class="img-responsive" width="15%">
                                            </center>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Captcha Answer -->
                <script type="text/javascript">
                    function check(){
                        var sum= $("#sum").val();
                        var user= $("#user").val();
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
                    function shwopass($) {
                        var x=document.getElementById('user_pass');
                        if(x.type=="password")
                        {
                            x.type="text";
                        }
                        else
                        {
                            x.type="password";
                        }
                    }
                </script>
                <!-- LOAD FILES AT PAGE END FOR FASTER LOADING -->
                <!-- CORE JS FRAMEWORK - START -->
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
                <script src="{{asset('js/jquery-1.11.2.min.js')}}" type="text/javascript"></script>
                <script src="{{asset('js/jquery.easing.min.js')}}" type="text/javascript"></script>
                <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
                <script src="{{asset('plugins/pace/pace.min.js')}}" type="text/javascript"></script>
                <script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}" type="text/javascript"></script>
                <script src="{{asset('plugins/viewport/viewportchecker.js')}}" type="text/javascript"></script>
                <script>window.jQuery||document.write('<script src="{{asset('js/jquery-1.11.2.min.js')}}"><\/script>');</script>
                <!-- CORE JS FRAMEWORK - END -->
                <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
                <script src="{{asset('plugins/icheck/icheck.min.js')}}" type="text/javascript"></script><!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - END -->
                <!-- CORE TEMPLATE JS - START -->
            </body>
            <script src="{{asset('js/captcha.js')}}" type="text/javascript"></script>
            <script src="{{asset('js/typeit.min.js')}}" type="text/javascript"></script>
            <!-- On Login -->
            <script type="text/javascript">
                new TypeIt(".slogan", {
                    strings: "We're Changing The World With Technology",
                    speed: 60,
                    loop: true
                })
                .move(-26)
                .delete(8)
                .type('Transforming', {delay: 500})
                .pause(300)
                .delete(12)
                .type('Innovating', {delay: 500})
                .pause(300)
                .go();
            </script>
            </html>