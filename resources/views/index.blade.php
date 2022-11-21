@extends('layouts.vertical', ["page_title"=> "Dashboard"])

@section('css')
<!-- third party css -->
<link href="{{asset('assets/libs/admin-resources/admin-resources.min.css')}}" rel="stylesheet" type="text/css">
</link>
<!-- third party css end -->
@endsection

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <form class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-light" id="dash-daterange">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="mdi mdi-calendar-range font-13"></i>
                            </span>
                        </div>
                        <a href="javascript: void(0);" class="btn btn-primary ms-2">
                            <i class="mdi mdi-autorenew"></i>
                        </a>
                        <a href="javascript: void(0);" class="btn btn-primary ms-1">
                            <i class="mdi mdi-filter-variant"></i>
                        </a>
                    </form>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

</div>
<!-- container -->
@endsection

@section('script-bottom')
<!-- third party js -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('assets/libs/admin-resources/admin-resources.min.js')}}"></script>
<!-- third party js ends -->

<!-- demo app -->
<script src="{{asset('assets/js/pages/demo.dashboard.js')}}"></script>
<!-- end demo js-->
@endsection
