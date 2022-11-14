<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/head')
</head>

<body data-layout="detached" class="loading" data-layout-config='{"layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    @include('layouts.shared/topbar-dark')


    <!-- Start Content-->
    <div class="container-fluid">

        <!-- Begin page -->
        <div class="wrapper">

            @include('layouts.shared/detached-left-sidebar')

            <div class="content-page">

                @yield('content')

                @include('layouts.shared/footer')

            </div> <!-- content-page -->

        </div> <!-- end wrapper-->
    </div>
    <!-- END Container -->


    @include('layouts.shared/right-sidebar')

    @include('layouts.shared/footer-script')

</body>

</html>