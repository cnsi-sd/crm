<!-- bundle -->
<script src="{{asset('assets/js/vendor.js')}}"></script>
@yield('script')
<script src="{{ Vite::asset('resources/js/app.js') }}"></script>
<script src="{{ Vite::asset('resources/js/layout.js') }}"></script>
<script src="{{ Vite::asset('resources/js/hyper.js') }}"></script>

<link href="vendor/select2/dist/css/select2.min.css" rel="stylesheet" />
<script src="vendor/select2/dist/js/select2.min.js"></script>
@yield('script-bottom')
