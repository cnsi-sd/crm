<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/head', ["page_title"=> __('app.reset_password.reset_password')] )
</head>

<body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>

    <div class="account-pages mt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-4 col-lg-5">
                    <div class="card">
                        <!-- Logo-->
                        <div class="card-header pt-4 pb-4 text-center bg-primary">
                            <a href="{{route('home')}}">
                                <span><img src="{{ Vite::asset('resources/images/logo.svg') }}" alt="" height="40"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">

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

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <div class="mb-3">
                                    <label for="emailaddress" class="form-label">{{__('app.email')}}</label>
                                    <input class="form-control" type="email" name="email" id="emailaddress" required placeholder="{{__('app.enter_email')}}">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">{{__('app.password')}}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="{{__('app.enter_password')}}">
                                        <div class="input-group-text" data-password="false">
                                            <span class="uil-eye"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{__('app.password_confirmation')}}</label>
                                    <div class="input-group input-group-merge">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="{{__('app.enter_password_confirmation')}}">
                                        <div class="input-group-text" data-password="false">
                                            <span class="uil-eye"></span>
                                        </div>
                                    </div>
                                    <div class="form-text">{{ __('app.user.password_help') }}</div>
                                </div>


                                <div class="mb-3 text-center">
                                    <button class="btn btn-primary" type="submit">{{__('app.reset_password.reset_password')}}</button>
                                </div>

                            </form>
                        </div> <!-- end card-body -->
                    </div>
                    <!-- end card -->

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
