<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/head', ["page_title"=> "Confirm Email"] )
</head>

<body class="authentication-bg pb-0" data-layout-config='{"darkMode":false}'>

    <div class="auth-fluid">
        <!--Auth fluid left content -->
        <div class="auth-fluid-form-box">
            <div class="align-items-center d-flex h-100">
                <div class="card-body">

                    <!-- Logo -->
                    <div class="auth-brand text-center text-lg-end">
                        <a href="{{route('any', 'index')}}" class="logo-dark">
                            <span><img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="18"></span>
                        </a>
                        <a href="{{route('any', 'index')}}" class="logo-light">
                            <span><img src="{{asset('assets/images/logo.png')}}" alt="" height="18"></span>
                        </a>
                    </div>

                    <!-- email send icon with text-->
                    <div class="text-center m-auto">
                        <img src="{{asset('assets/images/mail_sent.svg')}}" alt="mail sent image" height="64" />
                        <h4 class="text-dark-50 text-center mt-4 fw-bold">Please check your email</h4>
                        <p class="text-muted mb-4">
                            A email has been send to <b>youremail@domain.com</b>.
                            Please check for an email from company and click on the included link to
                            reset your password.
                        </p>
                    </div>

                    <!-- form -->
                    <form action="index.html">
                        <div class="mb-0 d-grid text-center">
                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-home me-1"></i> Back to Home </button>
                        </div>
                    </form>
                    <!-- end form-->

                    @include('layouts.shared/footer-2')

                </div> <!-- end .card-body -->
            </div> <!-- end .align-items-center.d-flex.h-100-->
        </div>
        <!-- end auth-fluid-form-box-->

        <!-- Auth fluid right content -->
        <div class="auth-fluid-right text-center">
            <div class="auth-user-testimonial">
                <h2 class="mb-3">I love the color!</h2>
                <p class="lead"><i class="mdi mdi-format-quote-open"></i> It's a elegent templete. I love it very much! . <i class="mdi mdi-format-quote-close"></i>
                </p>
                <p>
                    - Hyper Admin User
                </p>
            </div> <!-- end auth-user-testimonial-->
        </div>
        <!-- end Auth fluid right content -->
    </div>
    <!-- end auth-fluid-->





    @include('layouts.shared/footer-script')

</body>

</html>