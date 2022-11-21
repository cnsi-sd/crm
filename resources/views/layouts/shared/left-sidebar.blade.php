<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- LOGO -->
    <a href="{{route('any', 'index')}}" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo.png')}}" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm.png')}}" alt="" height="16">
        </span>
    </a>

    <!-- LOGO -->
    <a href="{{route('any', 'index')}}" class="logo text-center logo-dark">
        <span class="logo-lg">
            <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="16">
        </span>
        <span class="logo-sm">
            <img src="{{asset('assets/images/logo_sm_dark.png')}}" alt="" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="side-nav">

            @foreach(\App\Helpers\Menu::main() as $item)
                <li class="@if(array_key_exists('is_title', $item)) side-nav-title @endif side-nav-item">
                    @if(array_key_exists('is_title', $item))
                        {{$item['text']}}
                    @elseif(array_key_exists('sub_items', $item))
                        <a data-bs-toggle="collapse" href="#{{$item['ref']}}" aria-expanded="false" aria-controls="{{$item['ref']}}" class="side-nav-link">
                            @if(array_key_exists('icon', $item)) <i class="{{$item['icon']}}"></i> @endif
                            <span>{{$item['text']}}</span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse @if(strpos(\Illuminate\Support\Facades\Request::url(), $item['ref'])) show @endif" id="{{$item['ref']}}">
                            <ul class="side-nav-second-level">
                                @foreach($item['sub_items'] as $sub_item)
                                    @if(isset($sub_item['route']))
                                        <li @if(strpos(\Illuminate\Support\Facades\Request::url(), $sub_item['ref'])) class="menuitem-active" @endif>
                                            <a href="{{$sub_item['route']}}" @if(strpos(\Illuminate\Support\Facades\Request::url(), $sub_item['ref'])) class="active" @endif>{{$sub_item['text']}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <a href="{{$item['route']}}" class="side-nav-link">
                            @if(array_key_exists('icon', $item)) <i class="{{$item['icon']}}"></i> @endif
                            <span>{{$item['text']}}</span>
                        </a>
                    @endif
                </li>
            @endforeach

        </ul>

        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
