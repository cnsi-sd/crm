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

            <li class="dropdown notification-list topbar-dropdown d-none d-lg-block">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" id="topbar-languagedrop" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{asset('assets/images/flags/us.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span> <i class="mdi mdi-chevron-down"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu" aria-labelledby="topbar-languagedrop">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{asset('assets/images/flags/germany.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{asset('assets/images/flags/italy.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{asset('assets/images/flags/spain.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <img src="{{asset('assets/images/flags/russia.jpg')}}" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                    </a>

                </div>
            </li>

            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" id="topbar-notifydrop" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="dripicons-bell noti-icon"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg" aria-labelledby="topbar-notifydrop">

                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0">
                            <span class="float-end">
                                <a href="javascript: void(0);" class="text-dark">
                                    <small>Clear All</small>
                                </a>
                            </span>Notification
                        </h5>
                    </div>

                    <div style="max-height: 230px;" data-simplebar>
                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-primary">
                                <i class="mdi mdi-comment-account-outline"></i>
                            </div>
                            <p class="notify-details">Caleb Flakelar commented on Admin
                                <small class="text-muted">1 min ago</small>
                            </p>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-info">
                                <i class="mdi mdi-account-plus"></i>
                            </div>
                            <p class="notify-details">New user registered.
                                <small class="text-muted">5 hours ago</small>
                            </p>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon">
                                <img src="{{asset('assets/images/users/avatar-2.jpg')}}" class="img-fluid rounded-circle" alt="" />
                            </div>
                            <p class="notify-details">Cristina Pride</p>
                            <p class="text-muted mb-0 user-msg">
                                <small>Hi, How are you? What about our next meeting</small>
                            </p>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-primary">
                                <i class="mdi mdi-comment-account-outline"></i>
                            </div>
                            <p class="notify-details">Caleb Flakelar commented on Admin
                                <small class="text-muted">4 days ago</small>
                            </p>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon">
                                <img src="{{asset('assets/images/users/avatar-4.jpg')}}" class="img-fluid rounded-circle" alt="" />
                            </div>
                            <p class="notify-details">Karen Robinson</p>
                            <p class="text-muted mb-0 user-msg">
                                <small>Wow ! this admin looks good and awesome design</small>
                            </p>
                        </a>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item notify-item">
                            <div class="notify-icon bg-info">
                                <i class="mdi mdi-heart"></i>
                            </div>
                            <p class="notify-details">Carlos Crouch liked
                                <b>Admin</b>
                                <small class="text-muted">13 days ago</small>
                            </p>
                        </a>
                    </div>

                    <!-- All-->
                    <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                        View All
                    </a>

                </div>
            </li>

            <li class="dropdown notification-list d-none d-sm-inline-block">
                <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="dripicons-view-apps noti-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg p-0">

                    <div class="p-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/slack.png')}}" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/github.png')}}" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/dribbble.png')}}" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/bitbucket.png')}}" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/dropbox.png')}}" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="{{asset('assets/images/brands/g-suite.png')}}" alt="G Suite">
                                    <span>G Suite</span>
                                </a>
                            </div>

                        </div>
                    </div>

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
                        <img src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="user-image" class="rounded-circle">
                    </span>
                    <span>
                        <span class="account-user-name">Dominic Keller</span>
                        <span class="account-position">Founder</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown" aria-labelledby="topbar-userdrop">
                    <!-- item-->
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-circle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-account-edit me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-lifebuoy me-1"></i>
                        <span>Support</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-lock-outline me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="mdi mdi-logout me-1"></i>
                        <span>Logout</span>
                    </a>

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
                    <input type="text" class="form-control" placeholder="Search..." id="top-search">
                    <span class="mdi mdi-magnify search-icon"></span>
                    <button class="input-group-text  btn-primary" type="submit">Search</button>
                </div>
            </form>

            <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                </div>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="uil-notes font-16 me-1"></i>
                    <span>Analytics Report</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="uil-life-ring font-16 me-1"></i>
                    <span>How can I help you?</span>
                </a>

                <!-- item-->
                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i class="uil-cog font-16 me-1"></i>
                    <span>User profile settings</span>
                </a>

                <!-- item-->
                <div class="dropdown-header noti-title">
                    <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                </div>

                <div class="notification-list">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="d-flex">
                            <img class="d-flex me-2 rounded-circle" src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="Generic placeholder image" height="32">
                            <div class="w-100">
                                <h5 class="m-0 font-14">Erwin Brown</h5>
                                <span class="font-12 mb-0">UI Designer</span>
                            </div>
                        </div>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <div class="d-flex">
                            <img class="d-flex me-2 rounded-circle" src="{{asset('assets/images/users/avatar-5.jpg')}}" alt="Generic placeholder image" height="32">
                            <div class="w-100">
                                <h5 class="m-0 font-14">Jacob Deo</h5>
                                <span class="font-12 mb-0">Developer</span>
                            </div>
                        </div>
                    </a>
                </div>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboards" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-dashboard me-1"></i>Dashboards <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboards">
                            <a href="{{route('second', ['dashboard', 'analytics'])}}" class="dropdown-item">Analytics</a>
                            <a href="{{route('second', ['dashboard', 'crm'])}}" class="dropdown-item">CRM</a>
                            <a href="{{route('any', 'index')}}" class="dropdown-item">Ecommerce</a>
                            <a href="{{route('second', ['dashboard', 'projects'])}}" class="dropdown-item">Projects</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-apps" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-apps me-1"></i>Apps <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-apps">
                            <a href="{{route('third', ['apps', 'calendar', 'calendar'])}}" class="dropdown-item">Calendar</a>
                            <a href="{{route('third', ['apps', 'chat', 'chat'])}}" class="dropdown-item">Chat</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Ecommerce <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                    <a href="{{route('third', ['apps', 'ecommerce', 'products'])}}" class="dropdown-item">Products</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'products-details'])}}" class="dropdown-item">Products Details</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'orders'])}}" class="dropdown-item">Orders</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'orders-details'])}}" class="dropdown-item">Order Details</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'customers'])}}" class="dropdown-item">Customers</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'shopping-cart'])}}" class="dropdown-item">Shopping Cart</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'checkout'])}}" class="dropdown-item">Checkout</a>
                                    <a href="{{route('third', ['apps', 'ecommerce', 'sellers'])}}" class="dropdown-item">Sellers</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Email <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="{{route('third', ['apps', 'email', 'inbox'])}}" class="dropdown-item">Inbox</a>
                                    <a href="{{route('third', ['apps', 'email', 'read'])}}" class="dropdown-item">Read Email</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-project" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Projects <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-project">
                                    <a href="{{route('third', ['apps', 'projects', 'list'])}}" class="dropdown-item">List</a>
                                    <a href="{{route('third', ['apps', 'projects', 'details'])}}" class="dropdown-item">Details</a>
                                    <a href="{{route('third', ['apps', 'projects', 'gantt'])}}" class="dropdown-item">Gantt</a>
                                    <a href="{{route('third', ['apps', 'projects', 'add'])}}" class="dropdown-item">Create Project</a>
                                </div>
                            </div>
                            <a href="{{route('third', ['apps', 'social', 'feed'])}}" class="dropdown-item">Social Feed</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tasks" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tasks <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tasks">
                                    <a href="{{route('third', ['apps', 'tasks', 'tasks'])}}" class="dropdown-item">List</a>
                                    <a href="{{route('third', ['apps', 'tasks', 'details'])}}" class="dropdown-item">Details</a>
                                    <a href="{{route('second', ['apps', 'kanban'])}}" class="dropdown-item">Kanban Board</a>
                                </div>
                            </div>
                            <a href="{{route('second', ['apps', 'file-manager'])}}" class="dropdown-item">File Manager</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-copy-alt me-1"></i>Pages <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-pages">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Authenitication <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="{{route('second', ['auth', 'login'])}}" class="dropdown-item">Login</a>
                                    <a href="{{route('second', ['auth', 'login-2'])}}" class="dropdown-item">Login 2</a>
                                    <a href="{{route('second', ['auth', 'register'])}}" class="dropdown-item">Register</a>
                                    <a href="{{route('second', ['auth', 'register-2'])}}" class="dropdown-item">Register 2</a>
                                    <a href="{{route('second', ['auth', 'logout'])}}" class="dropdown-item">Logout</a>
                                    <a href="{{route('second', ['auth', 'logout-2'])}}" class="dropdown-item">Logout 2</a>
                                    <a href="{{route('second', ['auth', 'recoverpw'])}}" class="dropdown-item">Recover Password</a>
                                    <a href="{{route('second', ['auth', 'recoverpw-2'])}}" class="dropdown-item">Recover Password 2</a>
                                    <a href="{{route('second', ['auth', 'lock-screen'])}}" class="dropdown-item">Lock Screen</a>
                                    <a href="{{route('second', ['auth', 'lock-screen-2'])}}" class="dropdown-item">Lock Screen 2</a>
                                    <a href="{{route('second', ['auth', 'confirm-mail'])}}" class="dropdown-item">Confirm Mail</a>
                                    <a href="{{route('second', ['auth', 'confirm-mail-2'])}}" class="dropdown-item">Confirm Mail 2</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-error" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Error <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-error">
                                    <a href="{{route('second', ['error', '404'])}}" class="dropdown-item">Error 404</a>
                                    <a href="{{route('second', ['error', '404-alt'])}}" class="dropdown-item">Error 404-alt</a>
                                    <a href="{{route('second', ['error', '500'])}}" class="dropdown-item">Error 500</a>
                                </div>
                            </div>
                            <a href="{{route('third',['apps', 'pages', 'starter'])}}" class="dropdown-item">Starter Page</a>
                            <a href="{{route('third',['apps', 'pages', 'preloader'])}}" class="dropdown-item">With Preloader</a>
                            <a href="{{route('third', ['apps', 'pages', 'profile'])}}" class="dropdown-item">Profile</a>
                            <a href="{{route('third', ['apps', 'pages', 'profile-2'])}}" class="dropdown-item">Profile 2</a>
                            <a href="{{route('third', ['apps', 'pages', 'invoice'])}}" class="dropdown-item">Invoice</a>
                            <a href="{{route('third', ['apps', 'pages', 'faq'])}}" class="dropdown-item">FAQ</a>
                            <a href="{{route('third', ['apps', 'pages', 'pricing'])}}" class="dropdown-item">Pricing</a>
                            <a href="{{route('third', ['apps', 'pages', 'maintenance'])}}" class="dropdown-item">Maintenance</a>
                            <a href="{{route('third',['apps', 'pages', 'timeline'])}}" class="dropdown-item">Timeline</a>
                            <a href="{{route('any', 'landing')}}" class="dropdown-item">Landing</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-package me-1"></i>Components <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <a href="{{route('any', 'widgets')}}" class="dropdown-item">Widgets</a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ui-kit" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Base UI 1 <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ui-kit">
                                    <a href="{{route('second', ['base-ui','accordions'])}}" class="dropdown-item">Accordions</a>
                                    <a href="{{route('second', ['base-ui','alerts'])}}" class="dropdown-item">Alerts</a>
                                    <a href="{{route('second', ['base-ui','avatars'])}}" class="dropdown-item">Avatars</a>
                                    <a href="{{route('second', ['base-ui','badges'])}}" class="dropdown-item">Badges</a>
                                    <a href="{{route('second', ['base-ui','breadcrumb'])}}" class="dropdown-item">Breadcrumb</a>
                                    <a href="{{route('second', ['base-ui','buttons'])}}" class="dropdown-item">Buttons</a>
                                    <a href="{{route('second', ['base-ui','cards'])}}" class="dropdown-item">Cards</a>
                                    <a href="{{route('second', ['base-ui','carousel'])}}" class="dropdown-item">Carousel</a>
                                    <a href="{{route('second', ['base-ui','dropdowns'])}}" class="dropdown-item">Dropdowns</a>
                                    <a href="{{route('second', ['base-ui','embed-video'])}}" class="dropdown-item">Embed Video</a>
                                    <a href="{{route('second', ['base-ui','grid'])}}" class="dropdown-item">Grid</a>
                                    <a href="{{route('second', ['base-ui','list-group'])}}" class="dropdown-item">List Group</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ui-kit2" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Base UI 2 <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ui-kit2">
                                    <a href="{{route('second', ['base-ui','modals'])}}" class="dropdown-item">Modals</a>
                                    <a href="{{route('second', ['base-ui','notifications'])}}" class="dropdown-item">Notifications</a>
                                    <a href="{{route('second', ['base-ui','offcanvas'])}}" class="dropdown-item">Offcanvas</a>
                                    <a href="{{route('second', ['base-ui','pagination'])}}" class="dropdown-item">Pagination</a>
                                    <a href="{{route('second', ['base-ui','popovers'])}}" class="dropdown-item">Popovers</a>
                                    <a href="{{route('second', ['base-ui','progress'])}}" class="dropdown-item">Progress</a>
                                    <a href="{{route('second', ['base-ui','ribbons'])}}" class="dropdown-item">Ribbons</a>
                                    <a href="{{route('second', ['base-ui','spinners'])}}" class="dropdown-item">Spinners</a>
                                    <a href="{{route('second', ['base-ui','tabs'])}}" class="dropdown-item">Tabs</a>
                                    <a href="{{route('second', ['base-ui','tooltips'])}}" class="dropdown-item">Tooltips</a>
                                    <a href="{{route('second', ['base-ui','typography'])}}" class="dropdown-item">Typography</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-extended-ui" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Extended UI <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-extended-ui">
                                    <a href="{{route('second', ['extended','dragula'])}}" class="dropdown-item">Dragula</a>
                                    <a href="{{route('second', ['extended','range-slider'])}}" class="dropdown-item">Range Slider</a>
                                    <a href="{{route('second', ['extended','ratings'])}}" class="dropdown-item">Ratings</a>
                                    <a href="{{route('second', ['extended','scrollbar'])}}" class="dropdown-item">Scrollbar</a>
                                    <a href="{{route('second', ['extended','scrollspy'])}}" class="dropdown-item">Scrollspy</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-forms" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Forms <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-forms">
                                    <a href="{{route('second', ['forms','elements'])}}" class="dropdown-item">Basic Elements</a>
                                    <a href="{{route('second', ['forms','advanced'])}}" class="dropdown-item">Form Advanced</a>
                                    <a href="{{route('second', ['forms','validation'])}}" class="dropdown-item">Validation</a>
                                    <a href="{{route('second', ['forms','wizard'])}}" class="dropdown-item">Wizard</a>
                                    <a href="{{route('second', ['forms','fileuploads'])}}" class="dropdown-item">File Uploads</a>
                                    <a href="{{route('second', ['forms','editors'])}}" class="dropdown-item">Editors</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-charts" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Charts <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <a href="{{route('second', ['charts', 'chartjs'])}}" class="dropdown-item">Chartjs</a>
                                    <a href="{{route('second', ['charts', 'brite'])}}" class="dropdown-item">Britecharts</a>
                                    <a href="{{route('third', ['charts', 'apex', 'line'])}}" class="dropdown-item">Apex Charts</a>
                                    <a href="{{route('second', ['charts', 'sparkline'])}}" class="dropdown-item">Sparklines</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-tables" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Tables <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-tables">
                                    <a href="{{route('second', ['tables', 'basic'])}}" class="dropdown-item">Basic Tables</a>
                                    <a href="{{route('second', ['tables', 'datatable'])}}" class="dropdown-item">Data Tables</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-icons" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Icons <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="{{route('second', ['icons','dripicons'])}}" class="dropdown-item">Dripicons</a>
                                    <a href="{{route('second', ['icons','mdi'])}}" class="dropdown-item">Material Design</a>
                                    <a href="{{route('second', ['icons','unicons'])}}" class="dropdown-item">Unicons</a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-maps" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Maps <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-maps">
                                    <a href="{{route('second', ['maps', 'google'])}}" class="dropdown-item">Google Maps</a>
                                    <a href="{{route('second', ['maps', 'vector'])}}" class="dropdown-item">Vector Maps</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layouts" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="uil-window me-1"></i>Layouts <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layouts">
                            <a href="{{route('any', 'index')}}" class="dropdown-item">Vertical</a>
                            <a href="{{route('second', ['layouts-eg', 'detached'])}}" class="dropdown-item">Detached</a>
                            <a href="{{route('second', ['layouts-eg', 'horizontal'])}}" class="dropdown-item">Horizontal</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>