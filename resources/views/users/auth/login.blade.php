<?php
$url = url()->current();
$urlPart = explode('.',$url);  
$domainDetail = DB::table('domain')->where('domainname','like','%'.$urlPart[1].'%')->where('resellerid','!=',NULL)->first();
$favicon =   asset('img/favicon/'.$domainDetail->favicon);  
?>
<!DOCTYPE html>
<html class=" ">
<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title><?= strtoupper($urlPart[1]);?> | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CORE CSS FRAMEWORK - START -->
    <link rel="shortcut icon" href="{{$favicon}}" type="image/x-icon" />    <!-- Favicon -->
    <link href="{{asset('plugins/pace/pace-theme-flash.css')}}" rel="stylesheet" type="text/css" media="screen"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/bootstrap/css/bootstrap-theme.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/animate.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css"/>
    <!-- CORE CSS FRAMEWORK - END -->
    <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START -->
    <link href="{{asset('plugins/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" media="screen"/>  <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE END -->
    <!-- CORE CSS TEMPLATE - START -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet" type="text/css"/>
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
    <!-- CORE CSS TEMPLATE - END -->
    <style type="text/css">
        #wp-submit:hover{
        }
        #captcha{
            font-size: 25px;
            font-weight: bold;
            color: #dadada;
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
/* .btn:hover{
background: transparent;
} */
/* html {
background:{{asset('images/map.jpg')}} no-repeat  center fixed !important;
-webkit-background-size: cover !important;
-moz-background-size: cover !important;
-o-background-size: cover !important;
background-size: cover !important;
}*/
body{
    background-size: 100%;
    min-height: 100%;
}
.slogan{
    font-size: 42px;
    color: white;
    font-family: inherit;
    letter-spacing: 5px;
    line-height: 49px;
}
.btn-accent{
    background: #ff5500 !important
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
/* height: 250px;
width: 10px; */
position: fixed;
text-align: center;
padding: 10px;
/* margin-left: 10px; */
bottom: 0;
right: -111px;
cursor: pointer;
transition: .5s ease;
z-index: 9;
}
#sidebar:hover{
    right: 0;
}
b i {
    left: 30px;
    position: relative;
/*vertical-align: middle;
text-align: center;*/
font-size: 35px;
}
.social {
    color: #000 !important;
    /* margin-left: -162px; */
    /* width: 273px; */
    /* padding: 0; */
    display: inline-table;
    display: block;
    /* height: 0px; */
    background-color: transparent !important;
/* -moz-transition-property: margin-left;
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
-webkit-transition-delay: 0.2s; */
/* box-shadow: 0px 0px 6px 0px #3E3D3D; */
cursor: pointer;
}
/* .social:hover {
color: #000 !important;
margin-left:-25px;
width:280px;
background-color: transparent  !important;
} */
.facebook:hover {
    color: #000 !important;
    background-color: transparent !important;
}
@media only screen and (max-width: 411px) {
    #sidebar{
        display: none;
    }
}
.pin{
    width: 20px;
    height: 20px;
    background: #000;
    position: absolute;
    cursor: pointer;
    border-radius: 50%;
    transition: all .3s ease;
}
span.pin.khi{
    left: 32%;
    bottom: 9%;
}
span.pin.hyd{
    left: 38%;
    bottom: 24%;
}
span.pin.lhr{
    left: 64%;
    bottom: 46%;
}
span.pin.ibd{
    left: 61%;
    bottom: 58%;
}
span.pin.pwr{
    left: 56%;
    bottom: 74%;
}
span.pin.highlight {
    /* background-color: #fff; */
    transform: scale(1.1);
}
span.pin.highlight:before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: #3f70bc;
    border-radius: 50%;
    z-index: -1;
    opacity: 1;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    animation: ripple 1.5s ease-out infinite;
}
@keyframes ripple{
    100%{
        width: 300%;
        height: 300%;
        opacity: 0
    }
}
.loginpage form .input, .loginpage form input[type=checkbox], .loginpage input[type=text] {
    background-color: rgba(255, 255, 255, 1 );
    border-radius: 5px;
}
#myVideo{
    position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
}
#login__page{
    display: flex;
    align-items: center;
    margin-top: 100px;
}
#login__page.right{
    justify-content: flex-end !important;
    margin-right: 100px;
}
#login__page.left{
    justify-content: flex-start !important;
    margin-left: 100px;
}
#login__page.center{
    justify-content: center !important;
}
#login__page::before{
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background-color: #0000004a;
}
.loginpage{
    background-color: #d5d5e54d;
    /* background-color: #00000066; */
    padding-bottom: 20px;color: #ffffff; width: 430px;position: relative;backdrop-filter: blur(20px); padding: 30px 35px;border-radius: 10px;
}
@media (max-width: 1440px) {
    #login__page{
        margin-top: 10px;
    }
}
@media (max-width: 576px) {
    #login__page.right,
    #login__page.left,
    #login__page.center{
        justify-content: center !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
}
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
@php
$bg_image="";
$domain_name="";
$bk_image_path=url('/')."/images/".$domain->bg_image;
$bg_image= $domain->bg_image;  
$domain_name= $domain->domain;
if(!empty($domain->bg_image)){
if((@getimagesize($bk_image_path))){
$bg_image= $domain->bg_image;  
$domain_name= $domain->domain;
}
}
$url = url()->current();
$parse = parse_url($url);
$host=explode(".",$parse['host']);
$hostResllerid = DB::table('domain')->where('domainname',$parse['host'])->get();
$theme_loading_select = DB::table('partner_themes_user')
->where('username','=',$hostResllerid[0]->resellerid)
->get();
$theme_alignement="right";
if(isset($theme_loading_select[0]->login_alignment) && !empty($theme_loading_select[0]->login_alignment)){
$theme_alignement=$theme_loading_select[0]->login_alignment;
}
@endphp
<body class="login_page" @php if($bg_image!="") { @endphp style="background-image: url({{$domain_name}}/images/{{ $bg_image }});background-size: cover;background-repeat: no-repeat;background-position:right" @php } @endphp  id="">
    @php if($bg_image=="") { @endphp
    <video autoplay muted loop id="myVideo">
        <source src="/images/video2.mp4" type="video/mp4">
        </video> 
        @php } @endphp
        <!-- changes -->
        <div id="login__page" class="{{ $theme_alignement }}">
            <div id="login" class="login loginpage">
                <div style="">
                    @php
                    $bg_logo="login-Logo.png";
                    $logo_image_path=url('/')."/img/".$domain->logo;
                    {{
                        if(!empty($domain->logo)){
                        if((@getimagesize($logo_image_path))){
                        $bg_logo= $domain->logo;  
                    }
                } 
            }} 
            $bg_logo= $domain->logo; 
            @endphp
            <h1> <a href="#" title="Login Page"  tabindex="-1"style=' background-image: url("{{asset('img/')}}/{{ $bg_logo }}");' >LOGIN </a> </h1>
            <form id="loginform" action="{{route('users.login.post')}}" method="POST">
                @csrf
                <p>
                    <label for="user_login">Username   <br />
                        <input type="text" name="username" id="user_login" class="input" size="20" placeholder="Username" value="{{ old('username') }}" required autofocus />
                    </label>
                    <br>
                    @if($errors->has('username'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('username') }}</strong>
                    </span>
                    @endif
                </p>
                <p>
                    <label for="user_pass" style="position: relative;">Password<br />
                        <input type="password" name="password" id="user_pass" onfocus="paswordText()" class="input" size="20" placeholder="Password" required="" />
                        <span class="btn-show-pass" style="color: black;position: absolute;right: 6px; bottom: 18px;padding: 10px; cursor: pointer;" onclick="shwopass()">
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
                        &nbsp&nbsp&nbsp&nbsp
                        <span id="captcha"><span id="first"></span>+<span id="second"></span></span>
                        &nbsp&nbsp&nbsp&nbsp<i class="fa fa-refresh" style="font-size: 20px;color: #ffffff;" onclick="captcha()"></i>
                        <br />
                        <input type="number" name="log" id="user" class="input" value="" size="20" placeholder="=" required="" onkeyup="check()" />
                    </label>
                </p>
                <p id="output" style="font-size: 16px;color: #ff6f10;"></p>
                <p class="submit" >
                    <input onclick="submit()" type="button" name="wp-submit" id="enablebtn" class="btn btn-info btn-block" value="Sign In"  style="border: 1px solid black;"/>
                    <input type="button" name="wp-submit" id="disablebtn" class="btn btn-secondary btn-block" disabled="" value="Sign In"  style="border: 1px solid black;"/>
                    <center>
                        <img id="loading" src="{{asset('img/loading.gif')}}" class="img-responsive" width="15%">
                    </center>
                </p>
            </form>
        </div>
    </div>
</div>
<!-- Captcha Answer -->
<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js');
    }
</script>
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
<!-- On login -->
</html>