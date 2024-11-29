@php
$menusAccess = App\model\admin\AdminMenuAccess::where('user_id',Auth::id())->where('status',1)
->join('admin_sub_menus', 'admin_menu_accesses.sub_menu_id', '=', 'admin_sub_menus.id')
->join('admin_menus', 'admin_sub_menus.menu_id', '=', 'admin_menus.id')->orderby('admin_menus.priority')
->distinct('admin_menus.id')
->where('admin_menus.category','nav')
->select(['admin_menus.id','admin_menus.menu','admin_menus.has_submenu','admin_menus.icon','admin_menus.priority'])->get();
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
@include('admin.layouts.profileView')
<div class='page-topbar {{$shift_class1}}' id="" >
    <div class='logo-area'>
        <a href="#" class="nav_close_btn">&times;</a>
    </div>
    <div class='quick-area'>
        <div class='pull-left'>
            <ul class="info-menu left-links list-inline list-unstyled">
                <li class="sidebar-toggle-wrap">
                    <a href="#" data-toggle="sidebar" class="sidebar_toggle" id="shifts">
                        <i class="fa fa-bars" style="font-size: 24px;transform: translateY(3px);"></i>
                    </a>
                </li>
                {{-- <li class="notify-toggle-wrapper showopacity">
                    <a href="#" data-toggle="dropdown" class="toggle">
                        <i class="las la-search-plus" style="font-size:28px;transform: translateY(7px) scaleX(-1);"></i>
                    </a>
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
                </li> --}}
                @php
                $isNotify =  App\model\Users\NotificationAllow::where('username',Auth::user()->username)->first();
                @endphp
                {{-- Notifocation --}}
                <li class="notify-toggle-wrapper  showapprove">
                    {{-- @if(!empty($isNotify))
                        @if($isNotify->allow == 1) --}}
                        <a href="#" data-toggle="dropdown" class="toggle">
                            <i class="la la-bell" id="notiIcon" style="font-size: 28px;transform: translateY(8px);"></i>
                            <span class="badge badge-accent" id="numUserCount"></span>
                        </a>
                        {{-- @endif
                            @endif --}}
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
                <div class='pull-right' style="min-width: 190px">
                    <ul class="info-menu right-links list-inline list-unstyled">
                        <li class="profile showopacity">
                            <a href="#" data-toggle="dropdown" class="toggle">
                                <img src="{{asset('img/profile.jpg')}}" alt="user-image" class="img-circle img-inline">
                                <span>{{Auth::user()->firstname}} &nbsp;&nbsp;<i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu profile animated fadeIn">
                                <li>
                                    <a href="{{asset('#profileView')}}" data-toggle="modal">
                                        <i class="fas fa-address-card"></i>
                                        View Profile
                                    </a>
                                </li> 
                                @if(Auth::user()->status != "IT" && Auth::user()->status != "admin" )
                                <li>
                                    <a class="" href="{{('#edit_password')}}" data-toggle="modal"><i
                                        class="fas fa-unlock"> </i>Update Password</a>
                                    </li>
                                    @endif 
                                    <li  class="last">
                                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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
        <!-- END TOPBAR -->
        <div class="page-sidebar fixedscroll expandit {{$shift_class2}}" style="z-index: 9">
            <!-- MAIN MENU - START -->
            <div class="page-sidebar-wrapper" id="main-menu-wrapper">
                <!-- USER INFO - START -->
                @php
                $check ='';
                $pModule = array();
                $cModule = array();
                $state = array();
                $userId = 'user'.Auth::user()->id;
                if(Auth::user()->status ){
                $loadData = App\model\admin\Access::select($userId,'parentModule','childModule')->get();
                if(!empty($loadData)){
                foreach ($loadData as  $value) {
                $state[] = $value[$userId];
                $pModule[] = $value['parentModule'];
                $cModule[] = $value['childModule'];
            }
        }
    }
    @endphp
    @if( $state[0] == 0)
    <div class="profile-info row">
        <div class="profile-image col-xs-4">
            <a href="#">
                <img alt="" src="{{asset('img/profile.jpg')}}" class="img-responsive img-circle">
            </a>
        </div>
        <div class="profile-details col-xs-8">
            <h3>
                <a href="#">{{Auth::user()->username}}</a>
                <!-- Available statuses: online, idle, busy, away and offline -->
                <span class="profile-status online"></span>
            </h3>
            @if(Auth::user()->status == 'super')
            <p class="profile-title">Administrator</p> 
            @else
            <p class="profile-title">{{Auth::user()->status}}</p>
            @endif
        </div>
    </div>
    @else
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
            @else
            <p class="profile-title">{{Auth::user()->status}}</p>
            @endif
        </div>
    </div>
    @endif
    <!-- USER INFO - END -->
    <!-- Admin Menu - Start -->
    <ul class='wraplist'>                  
        <li class="menustats"></li>            
        @foreach($menusAccess as $key => $values)
        @php
        $Submenus = App\model\admin\AdminMenuAccess::where('user_id',Auth::id())->where('status',1)->where('admin_sub_menus.menu_id',$values->id)
        ->join('admin_sub_menus', 'admin_menu_accesses.sub_menu_id', '=', 'admin_sub_menus.id')->orderby('priority')->get();
        @endphp
        @if($values->has_submenu == 0)
        @if(!empty ($Submenus->first()->param) )
        <li class="{{Request::segment(2) == 'admin/'?('active'):''}}"><a href="{{route($Submenus->first()->route_name,[$Submenus->first()->param => $Submenus->first()->paramvalue])}}"> <i class="{{$values->icon == ''?'fa fa-bars':$values->icon}}"></i><span class="title">{{$Submenus->first()->submenu}}</span> </a></li>
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
            <!-- Admin Menu - End -->
        </div>
        <!-- MAIN MENU - END -->
    </div>
    @section('ownjs')
    <!-- Search Action -->
    <script type="text/javascript">
        function search() {
            var val=$('#searchbar').val();
            if(val.length > 0){
                $('#loading').show();
                $.ajax({
                    type: "POST",
                    url: "{{route('admin.search.ajax.post')}}",
                    data:'user='+val,
                    success: function(data){
// for get return data
$('#output').html(data);
$('#loading').hide();
}
});
            }
            else{
                $('#output').html('');
            }
        }
        $('#searchIcon').click(function(){
            $('#searchbar').val('');
            $('#output').html('');
        });
    </script>
    @endsection