<meta charset="utf-8" />
<title>{{ $page_title }} | {{ env('APP_NAME') }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- App favicon -->
<link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}">

@yield('css')

<!-- App css -->
<link href="{{ Vite::asset('resources/scss/icons.scss') }}" rel="stylesheet" type="text/css" />
<link href="{{ Vite::asset('resources/scss/app.scss') }}" rel="stylesheet" type="text/css" id="light-style" />
