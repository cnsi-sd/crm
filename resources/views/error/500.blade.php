<!DOCTYPE html>
<html lang="en">


<head>
    @include('layouts.shared/head', ["page_title"=> "Error 500"] )
</head>

<body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="{{route('any', 'index')}}">
                                <span><img src="{{asset('assets/images/logo.png')}}" alt="" height="18"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center">
                                <img src="{{asset('assets/images/startman.svg')}}" height="120" alt="File not found Image">

                                <h1 class="text-error mt-4">500</h1>
                                <h4 class="text-uppercase text-danger mt-3">Internal Server Error</h4>
                                <p class="text-muted mt-3">Why not try refreshing your page? or you can contact <a href="" class="text-muted"><b>Support</b></a></p>

                                <a class="btn btn-info mt-3" href="{{route('any', 'index')}}"><i class="mdi mdi-reply"></i> Return Home</a>
                            </div>

                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card-->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->


    @include('layouts.shared/footer')

    @include('layouts.shared/footer-script')

</body>

</html>