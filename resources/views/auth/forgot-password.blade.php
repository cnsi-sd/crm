<!DOCTYPE html>
<html lang="en">

<head>

    @include('layouts.shared/head', ["page_title"=> __('app.recover_password.recover_password')] )

</head>

<body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo -->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="{{route('home')}}">
                                <span><img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="" height="40"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

                            <div class="text-center w-75 m-auto">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">{{__('app.recover_password.reset_password')}}</h4>
                                <p class="text-muted mb-4">{{__('app.recover_password.enter_email_reset_password')}}</p>
                            </div>

                            @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>
                            <br>@endif
                            @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>
                            <br>@endif

                            @if (sizeof($errors) > 0)
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                            @endif

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">{{__('app.email')}}</label>
                                    <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="{{__('app.enter_email')}}">
                                </div>

                                <div class="mb-0 text-center">
                                    <button class="btn btn-primary" type="submit">{{__('app.recover_password.reset_password')}}</button>
                                </div>
                            </form>
                        </div> <!-- end card-body-->
                    </div>
                    <!-- end card -->

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted">{{__('app.recover_password.back_to')}} <a href="{{route('login')}}" class="text-muted ms-1"><b>{{__('app.recover_password.login')}}</b></a></p>
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

    @include('layouts.shared/footer-script')

</body>

</html>
