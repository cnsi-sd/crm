<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/head', ["page_title"=> "Confirm Email"] )
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

                            <div class="text-center m-auto">
                                <img src="{{asset('assets/images/mail_sent.svg')}}" alt="mail sent image" height="64" />
                                <h4 class="text-dark-50 text-center mt-4 fw-bold">Please check your email</h4>
                                <p class="text-muted mb-4">
                                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.
                                </p>
                            </div>

                            @if (session('status') == 'verification-link-sent')
                            <div class="text-center m-auto">
                                <p class="text-muted mb-4">A new verification link has been sent to the email address you provided during registration.</p>
                            </div>
                            @endif

                            @if (sizeof($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                            @endif

                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf

                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit"> Resend Verification Email </button>
                                </div>

                            </form>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit"> Log out </button>
                                </div>

                            </form>

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

    @include('layouts.shared/footer-3')

    @include('layouts.shared/footer-script')

</body>

</html>