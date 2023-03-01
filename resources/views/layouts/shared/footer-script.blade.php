<!-- bundle -->
<script src="{{asset('assets/js/vendor.js')}}"></script>
@yield('script')
<script src="{{ Vite::asset('resources/js/app.js') }}"></script>
<script src="{{ Vite::asset('resources/js/layout.js') }}"></script>
<script src="{{ Vite::asset('resources/js/hyper.js') }}"></script>
@yield('script-bottom')

@include('sweetalert::alert')
