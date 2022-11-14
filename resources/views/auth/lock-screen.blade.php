<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.shared/head', ["page_title"=> "Lock Screen"] )

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

                            <div class="text-center w-75 m-auto">
                                <img src="{{asset('assets/images/users/avatar-1.jpg')}}" height="64" alt="user-image" class="rounded-circle shadow">
                                <h4 class="text-dark-50 text-center mt-3 fw-bold">Hi ! Michael </h4>
                                <p class="text-muted mb-4">Enter your password to access the admin.</p>
                            </div>

                            <form action="#">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input class="form-control" type="password" required="" id="password" placeholder="Enter your password">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">Log In</button>
                                </div>
                            </form>

                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card-->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">Not you? return <a href="{{route('second', ['auth', 'login'])}}" class="text-muted ms-1"><b>Sign In</b></a></p>
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    @include('layouts.shared/footer-3')

    @include('layouts.shared/footer-script')


</body>

</html>