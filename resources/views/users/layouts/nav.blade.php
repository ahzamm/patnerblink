


<head>
  <style type="text/css">
    .freez__class{
        text-align:center;color:#f00 !important;font-weight:bold;padding:4px;background:#ccc;
        display:none;
      }
@media only screen and (max-width: 600px) {
      
      .exta{
        /*float: left;*/
        margin-left: -38%;
      }
      /* .back{
        margin-right: 235px;
      } */
    }
    #bottom { 
                position: absolute;               
                bottom:0;                          
                                       
            } 
            .blink {
              animation: blinker 2s linear alternate infinite;
              color: #1c87c9;
            }
       li {
    list-style-type: none;
}
      @keyframes blinker {  
        50% { opacity: .4; }
        100% { opacity: 1 }
       }
       .blink-one {
         animation: blinker-one 1s linear infinite;
       }
       @keyframes blinker-one {  
         0% { opacity: 0; }
       }
       .blink-two {
         animation: blinker-two 1.4s linear infinite;
       }
       @keyframes blinker-two {  
         100% { opacity: 0; }
       }
  </style>
</head>

@php
      $column = 'sub_menus.'.Auth::user()->status;
      //
      $menusAccess = App\model\Users\UserMenuAccess::where('user_id',Auth::id())->where('status',1)
      ->join('sub_menus', 'user_menu_accesses.sub_menu_id', '=', 'sub_menus.id')
      ->join('menus', 'sub_menus.menu_id', '=', 'menus.id')->orderby('menus.priority')
      ->distinct('menus.id')->where($column,1)
      ->where('menus.category','nav')
      ->select(['menus.id','menus.menu','menus.has_submenu','menus.icon','menus.priority'])->get();
      //  dd($menusAccess);    


    $url = url()->current();
    $parse = parse_url($url);

    $theme_logo="login-Logo.png";
    $logo_load = DB::table('domain')
    ->where('domainname','=',$parse['host'])
    ->get();

    if(isset($logo_load[0]->logo) && !empty($logo_load[0]->logo)){
        $theme_logo=$logo_load[0]->logo;
     }
     $shift_class1 = '';
     $shift_class2 = '';
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    if(stripos($ua,'mobile') !== false) {
      $shift_class1 = 'sidebar_shift';
      $shift_class2 = 'collapseit';
    }
    else{
      $shift_class2 = 'expandit';
    }
@endphp

<div class='page-topbar {{$shift_class1}}'>
  <div class='logo-area' style='background-image: url("{{asset('img/')}}/{{ $theme_logo }}")' >
    <a href="#" class="nav_close_btn">&times;</a>
  </div>
  <div class='quick-area'>
    <div class='pull-left'>
      <ul class="info-menu left-links list-inline list-unstyled">
      <li class="sidebar-toggle-wrap">
          <a href="#" data-toggle="sidebar" class="sidebar_toggle">
            <i class="fa fa-bars" style="font-size: 24px;transform: translateY(3px);"></i>
          </a>
        </li>
        @if(Request::segment(2) != 'dashboard')
        <li class="sidebar-toggle-wrap back" style="padding-right: 12px;padding-left: 10px;">
          <a href="{{ url()->previous() }}" title="Go Back Page">
            <!-- <i class="fa fa-arrow-circle-left" style="font-size: 21px;margin-top: 19px;margin-right: -12px;color: #fff"> </i> -->
            <i class="las la-arrow-circle-left" style="font-size: 30px;transform:translateY(6px);color: #fff;"></i>
          </a>
        </li>
        @endif
        
        <li class="notify-toggle-wrapper showopacity">
            @if(Auth::user()->status != 'user' && Auth::user()->status != 'inhouse')
          <a href="#" data-toggle="dropdown" class="toggle">
          <i class="las la-search-plus" id="searchIcon" style="font-size:28px;transform: translateY(7px) scaleX(-1);"></i>
            <!-- <i class="fa fa-search" id="searchIcon"></i> -->
            <!-- <span class="badge badge-accent">3</span> -->
          </a>
          @endif
          <ul class="dropdown-menu notifications animated fadeIn">
            <li class="total">
              <span class="small">
                <input type="text" class="form-control animated fadeIn"
                placeholder="Type username here" autofocus=""
                onkeyup="search()" autocomplete="off" required="" id="searchbar">
              </span>
            </li>
            <li class="list">
              <ul class="dropdown-menu-list list-unstyled ps-scrollbar" >
                <span id="output">

                </span>
                <li class="unread available" id="loading">
                  <center>
                    <img src="{{asset('img/loading.gif')}}" class="img-responsive" width="50px;">
                  </center>
                </li>
              </ul>
            </li>
          </ul>
        </li>
        @php
        $isNotify =  App\model\Users\NotificationAllow::where('username',Auth::user()->username)->first();
        @endphp
          {{-- Notifocation --}}
          <li class="notify-toggle-wrapper  showapprove">
            @if(!empty($isNotify))
            @if($isNotify->allow == 1)
            <a href="#" data-toggle="dropdown" class="toggle">
              <i class="fa fa-bell" id="notiIcon" ></i>
               <span class="badge badge-accent" id="numUserCount"></span>
            </a>
            @endif
          @endif
          <ul class="dropdown-menu notifications animated fadeIn">
            <li class="total">
              <span class="small">
                <h4>User Approve Notification</h4>
              </span>
            </li>
            <li class="list" style="padding: 10px !important;">
              <ul class="dropdown-menu-list list-unstyled ps-scrollbar" >
                <span id="newnotification">
                 
                </span>
              </ul>
            </li>
          </ul>
        </li>
        
      </ul>
    </div>
  
    <div class='pull-right'>
        
      <ul class="info-menu right-links list-inline list-unstyled">
       
      	@if(Auth::user()->status != "manager")
       @php
        $freeze_check = App\model\Users\FreezeAccount::where(['username' => Auth::user()->username])->first();
        if(empty($freeze_check)){
        $freeze = "no";
        }else{
        $freeze = $freeze_check->freeze;
    }
    if($freeze == "yes"){
    $red ="red"; 
}else{
  $red = "white";
}
        @endphp
<?php
$mob = '';
$cnic = '';
$cnicF = '';
$cnicB = '';

    $isverify = App\model\Users\UserVerification::where('username',Auth::user()->username)->select('mobile_status','cnic','nic_front','nic_back')->get();
    foreach ($isverify as $value) {
      $mob = $value['mobile_status'];
      $cnic = $value['cnic'];
      $cnicF = $value['nic_front'];
      $cnicB = $value['nic_back'];
     
    }
?>
   
@php
// $check ='';
// $pModule = array();
// $cModule = array();
// $state = array();
// $userId = 'user'.Auth::user()->id;
// if(Auth::user()->status =='support' || Auth::user()->status =='account'){
// $loadData = App\model\Users\userAccess::select($userId,'parentModule','childModule')->get();
// if(!empty($loadData)){
//     foreach ($loadData as  $value) {
//         $state[] = $value[$userId];
//         $pModule[] = $value['parentModule'];
//         $cModule[] = $value['childModule'];
//     }
    
// }
// }

if(Auth::user()->status != 'inhouse' && Auth::user()->status != 'user')
$isvisible = App\model\Users\UserAmount::where('username',Auth::user()->username)->first()->isvisible;

$url = url()->current();
$parseURL = parse_url($url);


$billingType = App\model\Users\DealerProfileRate::where('dealerid',Auth::user()->dealerid)->first();
@endphp

      @if(Auth::user()->status != 'inhouse' && Auth::user()->status != 'user' && (strpos($parseURL['path'], 'dashboard') === false) )
      @if($isvisible == 'yes' && Auth::user()->username == 'logonbroadband')
        <li class="sidebar-toggle-wrap">
          <span style="margin-right: 0px;background-color: #0000001f;padding: 0px 20px 0px 20px;" class="badge exta"><h5 style="color: {{$red}};font-weight: bold;">Available Amount : PKR {{number_format($amount= App\model\Users\UserAmount::where(['username' => Auth::user()->username])->first()['amount'],2)}}</h5></span>
        </li>
        @endif
        @if(!empty($billingType))
      @if($isvisible == 'no' || ($billingType->billing_type == 'card' && Auth::user()->status == 'subdealer'  && (strpos($parseURL['path'], 'dashboard') === false) ))
      @else
        <li class="sidebar-toggle-wrap">
          <span style="margin-right: 0px;background-color: #0000001f;padding: 0px 20px 0px 20px;" class="badge exta"><h5 style="color: {{$red}};font-weight: bold;">Available Amount : PKR {{number_format($amount= App\model\Users\UserAmount::where(['username' => Auth::user()->username])->first()['amount'],2)}}</h5></span>
        </li>
        @endif
      @endif
      @endif
  @endif
        <li class="profile showopacity">
          <a href="#" data-toggle="dropdown" class="toggle">
            <img src="{{asset('img/profile.jpg')}}" alt="user-image" class="img-circle img-inline">
            <span>{{Auth::user()->firstname}} &nbsp;&nbsp;<i class="fa fa-angle-down"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          </a>
          
          <ul class="dropdown-menu profile animated fadeIn">
            
            <li>
              <a href="{{('#profileView')}}" data-toggle="modal">
                <i class="fas fa-address-card fa-lg"></i>
                View Profile
              </a>
            </li>
            <li>
              <a class="" href="{{('#changePass')}}" data-toggle="modal" ><i
                class="fa fa-unlock-alt"> </i>Change Password</a>
              </li>
            
              <li class="last">
                <form id="logout-form" action="{{ route('users.logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
                <a href="#" class="dropdown-item"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa fa-power-off"> </i>Logout
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
   
  </div>
</div>
<!-- search action -->
<script type="text/javascript">
  function search() {
    var val=$('#searchbar').val();
    if(val.length > 0){
      $('#loading').show();
      $.ajax({
        type: "POST",
        url: "{{route('users.search.ajax.post')}}",
        data:'user='+val,
        success: function(data){
// for get return data
$('#output').html(data);
$('#loading').hide();
}
});
    }else{
      $('#output').html('');
    }
  }
$('#searchIcon').click(function(){
  $('#searchbar').val('');
  $('#output').html('');
});
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $.ajax({
          type: "GET",
          url: "{{route('users.ApprovedNewUserNotification')}}",
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
          url: "{{route('users.ApprovedNewUser')}}",
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
          url: "{{route('users.approveNewUserPost')}}",
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
          url: "{{route('users.rejectNewUserPost')}}",
          data: {id:id},
          success: function(data){
          alert('User has been  Rejected');
          location.reload();
  
  }
  });
      }
    }
  </script>
<!-- END TOPBAR -->
<div class="page-sidebar fixedscroll expandit {{$shift_class2}}" style="z-index: 9">
    
  <!-- MAIN MENU - START -->
  <div class="page-sidebar-wrapper" id="main-menu-wrapper">
    <!-- USER INFO - START -->
    <div class="profile-info row">
      <div class="profile-image col-xs-4">
        <a href="{{('#profileView')}}" data-toggle="modal">
          <img alt="" src="{{asset('img/profile.jpg')}}" class="img-responsive img-circle">
        </a>
        
      </div>
      <div class="profile-details col-xs-8">
        <h3>
          <a href="{{('#profileView')}}" data-toggle="modal">{{Auth::user()->username}}</a>
          <!-- Available statuses: online, idle, busy, away and offline -->
          <span class="profile-status online"></span>
        </h3>

          @if(Auth::user()->status == 'super')
          <p class="profile-title">Administrator</p> 
          @elseif(Auth::user()->status == 'manager')
          <p class="profile-title">Manager</p> 
          @elseif(Auth::user()->status == 'reseller')
          <p class="profile-title">Reseller</p> 
          @elseif(Auth::user()->status == 'dealer')
          <p class="profile-title">Contractor</p>
          @elseif(Auth::user()->status == 'subdealer')
          <p class="profile-title">Trader</p>
          @elseif(Auth::user()->status == 'subtrader')
          <p class="profile-title">Sub Trader</p>
          @elseif(Auth::user()->status == 'inhouse')
          <p class="profile-title">Helpdesk Agent</p>
          @else
          <p class="profile-title">Valued Consumer</p>
          @endif
        
 
      </div>
    </div>
    <!-- @if($check_account){ -->
    <p class="freez__class blink">{{$check_account}}</p>
    <!-- } -->
    <!-- @endif -->
    <ul class='wraplist'>
      <li class="menustats"></li>
      @foreach($menusAccess as $key => $values)
            @php
              $Submenus = App\model\Users\UserMenuAccess::where('user_id',Auth::id())->where('status',1)->where('sub_menus.menu_id',$values->id)
              ->join('sub_menus', 'user_menu_accesses.sub_menu_id', '=', 'sub_menus.id')->where($column,1)->orderby('priority')->get();
            @endphp
            @if($values->has_submenu == 0)
            @if($Submenus->first()->param != '')
            <li class="{{Request::segment(2) == 'users/dealer'?('active'):''}}"><a href="{{route($Submenus->first()->route_name,[$Submenus->first()->param => $Submenus->first()->paramvalue])}}"> <i class="{{$values->icon == ''?'fa fa-bars':$values->icon}}"></i><span class="title">{{$Submenus->first()->submenu}}</span> </a></li>
            @else
            <li class="{{Request::segment(2) == 'dashboard'?('active'):''}}"><a href="{{route($Submenus->first()->route_name)}}"> <i class="{{$values->icon == ''?'fa fa-bars':$values->icon}}"></i><span class="title">{{$Submenus->first()->submenu}}</span> </a></li>
            @endif
            @else
              <li class="treeview"><a href="javascript:void(0)" aria-expanded="false" data-toggle="collapse"> <i class="{{$values->icon == ''?'fa fa-bars':$values->icon}}"></i><span class="title">{{$values->menu}}</span><span class="arrow "></span> </a>
                <ul class="sub-menu">
                  @foreach($Submenus as $submenu)
                  @if($submenu->param != '')
                    <li><a href="{{route($submenu->route_name,[$submenu->param => $submenu->paramvalue])}}">
                      <i class="fa fa-circle" style="font-size: 10px;"> </i>
                      {{$submenu->submenu}}</a></li>
                      @else
                      @if($submenu->submenu == 'Never Expire Customer' && Auth::user()->never_expire == NULL)
                        @else
                        <li><a href="{{route($submenu->route_name)}}">
                          <i class="fa fa-circle" style="font-size: 10px;"> </i>
                          {{$submenu->submenu}}</a></li>
                        @endif
                      @endif
                  @endforeach
                </ul>
              </li>
            @endif
          @endforeach
    </ul>
  </div>
</div>

@include('users.dealer.changeDealerPassword')
@include('users.dealer.profileView')
{{--@include('users.dealer.NotePopup')--}}