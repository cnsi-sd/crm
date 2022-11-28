<!-- Topbar Start -->
<div class="navbar-custom topnav-navbar topnav-navbar-dark">
    <div class="container-fluid">

        <!-- LOGO -->
        <a href="" class="topnav-logo">
            <span class="topnav-logo-lg">
                <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="16">
            </span>
            <span class="topnav-logo-sm">
                <img src="{{asset('assets/images/logo_sm_dark.png')}}" alt="" height="16">
            </span>
        </a>

        <ul class="list-unstyled topbar-menu float-end mb-0">

            <li class="dropdown notification-list d-xl-none">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="dripicons-search noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                    <form class="p-3">
                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                    </form>
                </div>
            </li>

            <li class="notification-list">
                <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                    <i class="dripicons-gear noti-icon"></i>
                </a>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" id="topbar-userdrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="account-user-avatar">
                        <img src="{{asset('assets/images/users/avatar.png')}}" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                    <span class="account-user-name">{{ Auth::user()->name }}</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>{{__('app.user.my_account')}}</span>
                    </a>

                    <!-- item-->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="javascript:void(0);" class="dropdown-item notify-item" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            <i class="mdi mdi-logout me-1"></i>
                            <span>{{__('app.logout')}}</span>
                        </a>
                    </form>

                </div>
            </li>

        </ul>
        <a class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
            <div class="lines">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </a>
        <div class="app-search dropdown">
            <form>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="{{__('app.search')}}..." id="top-search">
                    <span class="mdi mdi-magnify search-icon"></span>
                    <button class="input-group-text  btn-primary" type="submit">{{__('app.search')}}</button>
                </div>
            </form>

            <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">

            </div>

        </div>
    </div>
</div>
<!-- end Topbar -->

<div class="topnav shadow-sm">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">
                    @foreach(\App\Helpers\Menu::main() as $item)
                        <li class="@if(array_key_exists('is_title', $item)) side-nav-title @endif nav-item dropdown">
                            @if(array_key_exists('is_title', $item))
                                {{$item['text']}}
                            @elseif(array_key_exists('sub_items', $item))
                                <a data-bs-toggle="dropdown" href="#{{$item['ref']}}" aria-haspopup="true" aria-expanded="false" aria-controls="{{$item['ref']}}" class="nav-link dropdown-toggle arrow-none">
                                    @if(array_key_exists('icon', $item)) <i class="{{$item['icon']}}"></i> @endif
                                    <span>{{$item['text']}}</span>
                                    <span class="arrow-down"></span>
                                </a>
                                <div class="dropdown-menu @if(strpos(\Illuminate\Support\Facades\Request::url(), $item['ref'])) show @endif" id="{{$item['ref']}}">
                                        @foreach($item['sub_items'] as $sub_item)
                                            @if(isset($sub_item['route']))
                                                    <a href="{{$sub_item['route']}}" class="@if(strpos(\Illuminate\Support\Facades\Request::url(), $sub_item['ref'])) active @endif dropdown-item">{{$sub_item['text']}}</a>
                                            @endif
                                        @endforeach
                                </div>
                            @else
                                <a href="{{$item['route']}}" class="nav-link arrow-none">
                                    @if(array_key_exists('icon', $item)) <i class="{{$item['icon']}}"></i> @endif
                                    <span>{{$item['text']}}</span>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
</div>
