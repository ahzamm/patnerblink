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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.1.0-10/css/all.css" />
    <!--  -->
    <link href="{{asset('fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet" type="text/css"/>
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


    @php

     $theme="";

    /*dynamic theme load start */

    $theme_loading = DB::table('partner_themes_user')
    ->where('username','=',$host[1])
    ->get();

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
    #loading{
        display: none;
    }
    .page-topbar {
       /* For browsers that do not support gradients */
            /*background-image: linear-gradient(to bottom right, #225094, #6dd5ed) !important;*/
            background-image: url("{{asset('images/nav.jpg')}}");
            background-color: #a0a0a0;
               -webkit-background-size: cover !important;
           -moz-background-size: cover !important;
           -o-background-size: cover !important;
           background-size: cover !important;
        }
        .page-topbar.sidebar_shift{

            background-image: url("{{asset('images/nav.jpg')}}");
            background-color: #a0a0a0;
        }

        #snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #3CB371;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  box-shadow: 0 15px 10px #777;
  padding: 16px;
  position: fixed;
  z-index: 1;
  right: 5%;
  top: 70px;
  font-size: 17px;
}

#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {right: 0; opacity: 0;} 
  to {right: 70px; opacity: 1;}
}

@keyframes fadein {
  from {right: 0; opacity: 0;}
  to {right: 70px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {right: 70px; opacity: 1;} 
  to {right: 0; opacity: 0;}
}

@keyframes fadeout {
  from {right: 70px; opacity: 1;}
  to {right: 0; opacity: 0;}
}
/*    */
.canvasjs-chart-credit{
    display: none !important;
}
 #dashboard-divparent .box-section{
    display:flex;
    justify-content: space-around;
    margin-top: 60px;
    position: relative;
}
#dashboard-divparent .box2 .icon-box{
    background-color: #ff5600;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
#dashboard-divparent .box3 .icon-box{
    background-color: #225094;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
 .icon-box i{
  font-size: 20px;
  text-align:center;
}
.icon-box2{
    background-color: red;
    position:absolute;
    bottom: 80px;

    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.icon-box2 i{
  font-size: 20px;
  text-align:center;
}
.box-section .hrr{
display: flex;
justify-content: center;
}
.box1{
    display: inline-block;
    width: 23%;
    height:130px;
    background-color:white;
    border-radius:20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
.box1 .icon-box{
    background-color: black;
    position:absolute;
    bottom: 80px;
    /* display: inline-block; */
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.icon-box i{
  font-size: 20px;
  color: white;
  text-align:center;
}
.box1 .icon-box2{
    background-color: black;
    position:absolute;
    bottom: 80px;
    /* display: inline-block; */
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.icon-box2 i{
  font-size: 20px;
  color: white;
  text-align:center;
}
#dashboard-divparent .parttwo .amount{
    width: 185px ;
}
#dashboard-divparent nav h4{
    font-size: 16px;
    color: white !important;
    font-weight: 400;
    margin: 0;  
}
#dashboard-divparent nav h3{
    font-size: 23px;
    color: white !important;
    font-weight: 600;
    margin-bottom: 10px;
    margin-right: 10px;  
}
#dashboard-divparent .icon-box4{
    background-color: red;
    position: relative;
    top: 25px;
    right:26%;
    width: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 50px;
    border-radius: 10px;
    margin-left: 25px;
}
.amount{
    display: flex;
    flex-direction:column;
    align-items: flex-end;
    margin-right: 20px;
}
.sec-one{
    display:flex;
    justify-content:flex-end;
}
.amount2{
    display: flex;
    flex-direction:column;
    align-items: flex-end;
    margin-right: 20px;
}
.amount h4{
    margin: 10px 0 0 0 ;
    font-family: 'Poppins', sans-serif;
    font-size: 14px;
    padding: 0;
}
/* .amount h3{
    margin: 5px 0 0 0 ;
    font-family: 'Poppins', sans-serif;
font-size:30px;
    padding-right: 20px;
} */
.box1 .border{
    border-bottom:2px solid lightgrey;
    margin-top: 20px;
    width:150px;
}
.box1 h5{
    text-align:center;
    font-size:12px;
}
.box1 h5 b{
    color:green;
    font-weight:600;
    font-size:16px;
}
.box2{
    display: inline-block;
    width: 22%;
    height:130px;
    background-color:white;
    margin-left: 20px;
    border-radius:20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
.box2 .border{
    border-bottom:2px solid lightgrey;
    margin-top: 20px;
    width:150px;
}
.box2 h5{
    text-align:center;
    font-size:12px;
}
.box2 h5 b{
    color:green;
    font-weight:600;
    font-size:16px;
}
.box3 .icon-box{
    background-color:#225094;   
}
.box2 .icon-box{
    background-color: #ff5600;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.box3 .icon-box2{
    background-color:#225094;   
}
.box2 .icon-box2{
    background-color: #ff5600;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.box3{
    display: inline-block;
    width: 22%;
    height:130px;
    margin-right: 20px;
    margin-left: 20px;
    background-color:white;
    border-radius:20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
.box3 .border{
    border-bottom:2px solid lightgrey;
    margin-top: 20px;
    text-align:center;
    display: flex;
    justify-content: center;
    width:150px;
}
.box3 h5{
    text-align:center;
    font-size:12px;
}
.box3 h5 b{
    color:red;
    font-weight:600;
    font-size:16px;
}
.box4{
    display: inline-block;
    width: 23%;
    height:130px;
    background-color:white;
    border-radius:20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}
.box4 .border{
    border-bottom:2px solid lightgrey;
    margin-top: 20px;
    width:150px;
}
.box4 h5{
    text-align:center;
    font-size:12px;
}
.box4 h5 b{
    color:green;
    font-weight:600;
    font-size:16px;
}
.box4 .icon-box{
    background-color: black;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.box4 .icon-box2{
    background-color: black;
    position:absolute;
    bottom: 80px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px;
}
.box5{
    background-color:white;
    width:95%;
    height:130px;
    border-radius: 20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}

.box5 .icon-box3{
    background-color: black;
    color: white;
     position:absolute;
    bottom: 90px;
    width: 60px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:65px;
    border-radius:10px;
    margin-left: 25px; 
}
.amount3{
    display: flex;
    flex-direction:column;
    font-family: 'Poppins', sans-serif;
    align-items: center;
    margin-right: 20px;
}
.border3{
    border: 1px solid lightgrey;
    width: 50%;
    margin:10px 0px;
}
#sec-h43{
    font-family: 'Poppins', sans-serif;
}
#sec-head3{
    font-family: 'Poppins', sans-serif;
    margin: 10px 0px;
}
/*  */
.Chart-section{
    display: flex;
}
.Chart-section h2{
    text-align: center;
    font-family: 'Poppins', sans-serif;
    font-size:40px;
}
.bar-graph{
    display: flex;
    justify-content: center;
    /* align-items: center; */
    margin-top: 50px;
}
.table-section{
    /* width: 40%; */
    /* display: inline-flex;  */
    /* justify-content: space-around; */
}
/* .table-section .table>thead>tr>th{
    border: 1px solid #ddd;
} */
.table-section .table>thead>tr>th,
.table-section .table>tbody>tr>td{
    border: 1px solid #ddd;
}
.container2 .card
{
    position: relative;
    width: 100%;
    margin: 10%;
    height: 250px;
    border-radius: 50px;
    background: rgb(0, 45, 109,1);
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    display: flex;
    justify-content: center;
    align-items: center; 
} 

.container2 .card .percent
{
    position: relative;
    width: 150px;
    height: 150px;
    /* background: #f00; */
}
.container2 .card .percent svg
{
    position: relative;
    width: 150px;
    height: 150px;
    transform: rotate(270deg);
}

/* <table */

.table-section
{
    margin-left:20px;
    /* width:30%; */
    flex: 1 0 30%;
}

/* td:nth-child(even) {
    width: 40%;
}
td:nth-child(odd) {
    width: 60%;
} */
.scroll{
  font-family: arial, sans-serif;
  border-collapse: collapse;
  /* width: 70%; */
  overflow-y: auto;
  max-height: 400px;
}

#table__olUsers th
{
    font-size: 15px;
    font-family: sans-serif;
    background-color: rgb(255, 255, 255);
    color: rgb(0, 0, 0);
    position: sticky;
    top: 0;
    /* width: 80%; */
}

/* 

th{
    color: black; 
} */

#table__olUsers td, #table__olUsers th {
  padding: 10px;
}

#table__olUsers tr:nth-child(even) {
  background-color:rgb(240, 234, 234);   
} 
.amount 
.sec-one{
    text-align:right; 
    font-family: 'Poppins', sans-serif;
    padding-right: 10px;
}
.sec-two{
    text-align:right; 
    font-family: 'Poppins', sans-serif;
    padding-right: 8px;
}
#sec-head{
    font-size: 20px;    
    font-family: 'Poppins', sans-serif;
    text-align:center;
    margin-top:20px;
}
#sec-h4{
    font-size: 25px;    
    font-family: 'Poppins', sans-serif;
    text-align:right !important;
    margin-right: 10px;
}
hr {
    background-color: lightgrey;
    height: 2px;
    margin-top: 50px;
    width: 95%;
}
 .moving-text:hover{
    animation-play-state: paused;
   
} 
.moving-texturdu:hover{
    animation-play-state: paused;
    font-weight:900;
} 
.slider2{
    border-radius: 30px;
    overflow: hidden;
    height: 165px;
    margin-bottom: 20px;
    color: black;
    font-family: 'Open Sans', Arial, Helvetica, sans-serif;
   
}
.marquee-sec{
    color: black;
    height:80px;
    margin-top: 50px;
    /* font-size:12px; */
    font-family: 'Poppins', sans-serif;
    border-radius:30px;
}

/* The animation */
@keyframes marquee{
    0%{transform: translateX(-100%);}
    100%{transform: translateX(100%);}
}

/* media query to enable animation for only those who want it */
@media (prefers-reduced-motion: no-preference) {
    .moving-texturdu{
        animation: marquee 30s linear infinite;
    }
}
@keyframes marquee2{
    0%{transform: translateX(100%);}
    100%{transform: translateX(-100%);}
}

@media (prefers-reduced-motion: no-preference) {
    .moving-text{
        animation: marquee2 30s linear infinite;
    }
}
.moving-texturdu{
    /* font-size:16px; */
  padding: 5px;
  font-weight:900;
  margin: 0;
}
.moving-text{
    /* font-size:16px; */
padding: 5px;
font-weight:900;
margin: 0;
}
footer{
    background-color: white;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
    width: 100%;

}
.last-section{
    display: flex;
    height: 80px;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
.social-media{
    display: flex;
}
.facebook{
    width: 50px;
    height: 50px;
    display: inline-block;
    background-color: grey;
    text-align: center;
    color:white;
    border-radius: 50px;
}
.facebook:hover{
    cursor: pointer;
    background-color: #3F51B5;
        -webkit-animation-name: rotateIn;
        animation-name: rotateIn;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        }
        @-webkit-keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        }
        @keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        } 
.facebook i{
    padding-top: 15px;
    font-size:20px;
}
.facebook title{
    border: none;
}
/*  */
.twitter{
    width: 50px;
    height: 50px;
    margin:0px 10px;
    display: inline-block;
    background-color: black;
    text-align: center;
    color:white;
    border-radius: 50px;
}
.twitter:hover{
    cursor: pointer;
    background-color: #000;
        -webkit-animation-name: rotateIn;
        animation-name: rotateIn;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        }
        @-webkit-keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        }
        @keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        } 
.twitter i{
    padding-top: 15px;
    font-size:20px;
}
.twitter title{
    border: none;
}
/*  */
.google{
    width: 50px;
    height: 50px;
    display: inline-block;
    background-color: #db4a39;
    text-align: center;
    color:white;
    border-radius: 50px;
}
.google:hover{
    cursor: pointer;
    background-color:  #db4a39;
        -webkit-animation-name: rotateIn;
        animation-name: rotateIn;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        }
        @-webkit-keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        }
        @keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        } 
.google i{
    padding-top: 15px;
    font-size:20px;
}
.google title{
    border: none;
}
/*  */
.linked{
    width: 50px;
    height: 50px;
    display: inline-block;
    background-color: #337ab7;
    text-align: center;
    color:white;
    border-radius: 50px;
}
.linked:hover{
    cursor: pointer;
    background-color:  #337ab7;
        -webkit-animation-name: rotateIn;
        animation-name: rotateIn;
        -webkit-animation-duration: 1s;
        animation-duration: 1s;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        }
        @-webkit-keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        }
        @keyframes rotateIn {
        0% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: rotate3d(0, 0, 1, -200deg);
        transform: rotate3d(0, 0, 1, -200deg);
        opacity: 0;
        }
        100% {
        -webkit-transform-origin: center;
        transform-origin: center;
        -webkit-transform: none;
        transform: none;
        opacity: 1;
        }
        } 
.linked i{
    padding-top: 15px;
    font-size:20px;
}
.linked title{
    border: none;
}
.copyright{
    font-family: 'Open Sans', Arial, Helvetica, sans-serif;
    font-size: 16px;
    font-weight: 400;
}
a{
    color:white;
}
a:hover{

}
progress{
    width: 30%;
}
/* progress::-webkit-progress-value { 
background: green;
} */
    #loading{
        display: none;
    }
    
    .page-topbar {
       /* For browsers that do not support gradients */
            /*background-image: linear-gradient(to bottom right, #225094, #6dd5ed) !important;*/
            background-image: url("{{asset('img/nav_pic.png')}}");
               -webkit-background-size: cover !important;
           -moz-background-size: cover !important;
           -o-background-size: cover !important;
           background-size: cover !important;
        }
        .page-topbar.sidebar_shift{

            background-image: url("{{asset('img/nav_pic.png')}}");
        }

        #snackbar {
  visibility: hidden;
  min-width: 250px;
  margin-left: -125px;
  background-color: #3CB371;
  color: #fff;
  text-align: center;
  border-radius: 2px;
  box-shadow: 0 15px 10px #777;
  padding: 16px;
  position: fixed;
  z-index: 1;
  right: 5%;
  top: 70px;
  font-size: 17px;
}
#chartContainer{
    flex: 1 0 70%;
    height: 400px;
}
#snackbar.show {
  visibility: visible;
  -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
  animation: fadein 0.5s, fadeout 0.5s 2.5s;
}

@-webkit-keyframes fadein {
  from {right: 0; opacity: 0;} 
  to {right: 70px; opacity: 1;}
}

@keyframes fadein {
  from {right: 0; opacity: 0;}
  to {right: 70px; opacity: 1;}
}

@-webkit-keyframes fadeout {
  from {right: 70px; opacity: 1;} 
  to {right: 0; opacity: 0;}
}

@keyframes fadeout {
  from {right: 70px; opacity: 1;}
  to {right: 0; opacity: 0;}
}
    /*  */
    @media only screen and (max-width:1200px){
        .parttwo{
            width: 170px;
        }
        nav h2{
            font-size:36px;
            margin: 15px 0px;
        }
.box1 .icon-box{
    bottom:105px;
    width:50px;
    height:60px;
    margin-left: 20px;
}
.box2 .icon-box{
    bottom:85px;
    width:50px;
    height:60px;
    margin-left: 13px;
}
.box3 .icon-box{
    bottom:85px;
    width:50px;
    height:60px;
    margin-left: 13px;
}
.box4 .icon-box{
    bottom:105px;
    width:50px;
    height:60px;
    margin-left: 20px;
}
.box1 .icon-box2{
    bottom:100px;
    width: 50px;
    height: 60px;
    margin-left: 13px;
}
.box2 .icon-box2{
    bottom:100px;
    margin-left: 13px;
    width: 50px;
    height: 60px;
}
.box3 .icon-box2{
    bottom:100px;
    margin-left: 13px;
    width: 50px;
    height: 60px;
}
.box4 .icon-box2{
    bottom:100px;
    margin-left: 13px;
    width: 50px;
    height: 60px;
}
.amount{
    display: flex;
    align-items: flex-end;
    flex-direction: column;
}
/* #dashboard-divparent .parttwo .amount{
    margin-right: 300px ;
} */
#dashboard-divparent nav h2{
    font-size:40px;
}
#dashboard-divparent .icon-box4{
    right:33%;
}
.box1,.box4{
width:22%;
height:148px;
}
.box2,.box3{
width:21%;
}
.sec-one{
    width:67px;
}
.amount h5{
    font-size:10px;
}
.amount h5 b{
    font-size:10px;
}
.amount2{
    align-items: flex-end;
    margin:0 10px 0 0 ;
}
#sec-head{
    font-size: 16px;
}
#sec-h4{
    font-size:18px;
}
 .box2 .amount2 .icon-box{
bottom:110px;
}
.social-media{
    display: flex !important;
    }
.social-media .facebook,.twitter,.google,.linked{
width: 40px;
height: 40px;
}
.social-media i{
font-size:16px;
padding: 12px;
}
.copyright{
    font-size:13px;
    font-weight:600;
}
.marquee-sec{
height: 110px;
}
canvas .canvasjs-chart-canvas{
    width:400px;
}
}
@media only screen and (max-width:1024px){
    /* #dashboard-divparent .parttwo{
        display: block;
        width:100%;
    } */
    #dashboard-divparent .icon-box4{
        right:02%;
    }
    /* #dashboard-divparent .hello {
    width:100%;
} */
    /* #dashboard-divparent .nav-section{
        display: flex;
        flex-direction:column;
        margin-top: 60px;
    } */
}
@media only screen and (max-width:992px){
    /* .hello{
        width:100%;
    } */
    /* .home-section{
        height: 800px;
    } */
    .parttwo .icon-box {
left:-12px;
    }
    #dashboard-divparent .icon-box4{
        right:02%;
    }
    /* #dashboard-divparent .parttwo{
        display: block;
        width:100%;
    } */
    /* .home-section{
        height:800px;
    } */
    .parttwo{
        float: right;
    }
    .nav-section{
display: block;
}
nav h5{
    font-size:13px;
}
.box-section{
    flex-wrap:wrap;
    justify-content:space-evenly;
}
#dashboard-divparent .box2 .icon-box{
    bottom:360px !important;
}
.box1,.box2{
    width:40%;
    margin:50px 10px;
}
.box1 .icon-box{
    bottom:350px;
    width:70px;
    height:70px;
}
.amount{
    padding-right: 10px;
}
/* .amount .sec-one{
    width: auto;
} */
.box2 .icon-box{
    bottom:350px;
    width:70px;
    height:70px;
}
.box3,.box4{
    width:40%;
    margin: 50px 10px 0 10px;
}
.box3 .icon-box{
    bottom:120px;
    width:70px;
    height:70px;
}
.box4 .icon-box{
    bottom:120px;
    width:70px;
    height:70px;
}
.box-section .border{
    width:50%;
}
.box1 .icon-box2{
    bottom: 350px;
    width: 70px;
    height: 70px;
}
.box2 .icon-box2{
    bottom: 350px;
    width: 70px;
    height: 70px;
}
.box3,.box4{
   
}
.box3 .icon-box2{
    width: 70px;
    height: 70px;
}
.box4 .icon-box2{
    width: 70px;
    height: 70px;
}
.box5 .icon-box3{
    width: 70px;
    height: 70px;
}
.bar-graph{
    display: flex;
    flex-direction: column;
    padding: 20px 0px;
}
.table-section{
   margin-top:100px;
}
.moving-text{
        font-size:10px;
}
.moving-texturdu{
        font-size:10px;
}
.last-section{
    display: flex;
    height: 100px;
    flex-direction: column; 
    text-align:center;
    justify-content:center;
}
.social-media{
    margin: 07px 0px;
}
}
@media only screen and (max-width:576px){
    #dashboard-divparent nav h2{
        font-size:30px;
    }
    #dashboard-divparent .nav-section{
    }
    .home-section{
        height: 250px;
    }
    #dashboard-divparent .box3 .icon-box {
bottom:575px;
    }
    nav h2{
        font-size:28px;
    }
    .amount .sec-one{
        width: auto;
        font-size:20px;
    }
    .box-section h5 {
    text-align: center;
    font-size: 15px;
}
    .box1,.box2{
        width:100%;
        margin:40px 0px;
    }
    .box3,.box4{
        width:100%;
        margin:40px 0px;
    }
    .box1 .icon-box{
        bottom:790px;
        width: 60px ;
        height: 65px;
    }
    .box2 .icon-box{
        bottom:555px;
        width: 85px;
        height: 85px;
    }
    .box3 .icon-box{
        bottom:350px;
        width: 85px;
        height: 85px;
    }
    .box4 .icon-box{
        bottom:140px;
        width: 85px;
        height: 85px;
    }
    .box1 .icon-box2{
        bottom: 795px;
    width: 80px;
    margin-left: 20px;
    height: 80px;
    }
     .breadcrumb{
        display: none;
    }
    .box2 .icon-box2{
        bottom: 565px;
    width: 80px;
    margin-left: 20px;
    height: 80px;
    }
    .parttwo{
        float: left;
    }
    .box3 .icon-box2{
        bottom: 355px;
    width: 80px;
    margin-left: 20px;
    height: 80px;
    }
    .box4 .icon-box2{
        bottom: 155px;
    width: 80px;
    margin-left: 20px;
    height: 80px;
    }
    .Chart-section h2{
    font-size:30px;
   }
    #sec-h4 {
    font-size: 25px;
}
#sec-head {
    font-size: 20px;
}
    .last-section {
    display: flex;
    text-align:center;
    justify-content: center;
}
.box5{
    background-color:white;
    width:100%;
    height:110px;
    border-radius: 20px;
    box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
}

.box5 .icon-box3{
    background-color: black;
    color: white;
     position:absolute;
    bottom: 75px;
    width: 80px;
   display: flex;
   align-items:center;
   justify-content: center;
    height:80px;
    border-radius:10px;
    margin-left: 25px; 
}
.amount3{
    display: flex;
    flex-direction:column;
    font-family: 'Poppins', sans-serif;
    align-items: flex-end;
    margin-right: 20px;
}
/* .border3{
    border: 1px solid lightgrey;
    width: 50%;
    margin:10px 0px;
} */
progress{
width:50%;
}
#sec-h43{
    font-family: 'Poppins', sans-serif;
    font-size:14px;
}
#sec-head3{
    font-family: 'Poppins', sans-serif;
    margin: 10px 0px;
    font-size: 18px;
}
}
@media only screen and (max-width:420px){
    nav h2 {
    font-weight: 900;
    font-size: 26px;
}
nav h4 {
    font-size: 11px;
    font-weight: 900;
    margin: 0;
}
nav h5 {
    font-size: 11px;
}
    .box1 .icon-box{
    width: 60px;
    height: 70px;
   }
   .amount .sec-one{
    width: auto;
    font-size:16px;
   }
   .box2 .icon-box{
    width: 60px;
    height: 70px;
   }
   .box3 .icon-box{
    width: 60px;
    height: 70px;
   }
   .box4 .icon-box{
    width: 60px;
    height: 70px;
   }
   .Chart-section h2{
    font-size:25px;
   }
    .box1 .icon-box2{
        width: 60px;
        height: 70px;
    }
    .box2 .icon-box2{
        width: 60px;
        bottom:570px;
        height: 70px;
    }
    .box3 .icon-box2{
        width: 60px;
        height: 70px;
    }
    .box4 .icon-box2{
        width: 60px;
        height: 70px;
    }
    .box5 .icon-box3{
        width: 60px;
        height: 70px;
        left:75px;
        bottom:125px;
    }
    .box5{
        height:160px;
    }
    .amount3{
        padding-top: 40px;
        align-items:center;
    }
    #sec-h43{
    font-family: 'Poppins', sans-serif;
    font-size:14px;
}
#sec-head3{
    font-family: 'Poppins', sans-serif;
    margin: 0px;
    font-size: 18px;
}
    .bar-graph{
        margin: 0;
    }
    .slider2{
    height:240px;
    }
    .last-section{
        height:140px;
    }
}
    @media only screen and (max-width:1400px){
        /* .table-section{
width:50%;
        } */
        .scroll{
            width:100%;
        }
        /* .chartContainer{
            width:50%;
        } */
    }
    @media only screen and (max-width:991px){
        .table-section{
width:100%;
        }
        .scroll{
            width:100%;
        }
        .chartContainer{
            width:100%;
        }
    }
@media (max-width: 1200px) {
    #chartContainer,
    .table-section{
        flex: auto;
    }
    .table-section{
        margin-top: 40px;
        margin-left: 0;
    }
    .bar-graph{
        flex-direction: column;
    }
}

</style>
</head>
<body>

	@include('users.layouts.nav')

	@include('users.dealer.model_sub_dealer')
 
    @include('users.dealer.add_user')
   
    @include('users.reseller.model_dealer') 


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
        <script>
     $(document).ready(function(){
        $.ajaxSetup({
           headers:
           { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
       });
    });
</script>

<script>
    $(document).ready(function(){
        $(".site-footer").css("padding-left" , "270px");

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
        
    })
    $(window).scroll(function(){
        if($('#main-content').hasClass('sidebar_shift')){
                $(".site-footer").css("padding-left", "60px");
                console.log('class added');
            }
            else{
                $(".site-footer").css("padding-left", "270px");
                console.log('class removed');
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