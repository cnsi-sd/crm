@extends('layouts.vertical', ["page_title"=> "Sparkline Charts"])

@section('content')
<!-- Start Content-->
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Charts</a></li>
                        <li class="breadcrumb-item active">Sparkline</li>
                    </ol>
                </div>
                <h4 class="page-title">Sparkline Charts</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->


    <div class="row">
        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Line Charts</h4>

                    <div class="mt-4" dir="ltr">
                        <div id="sparkline1" data-colors="#727cf5,#0acf97"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Bar Chart</h4>

                    <div class="mt-4" dir="ltr">
                        <div id="sparkline2" class="text-center" data-colors="#39afd1"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Pie Chart</h4>

                    <div class="mt-4" dir="ltr">
                        <div id="sparkline3" class="text-center" data-colors="#39afd1,#ffbc00,#e3eaef,#fa5c7c"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Custom Line Chart</h4>

                    <div class="mt-4" dir="ltr">
                        <div id="sparkline4" class="text-center" data-colors="#ffbc00,#4eb7eb"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Mouse Speed Chart</h4>

                    <div class="mt-4" dir="ltr">
                        <div id="sparkline5" class="text-center"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Composite bar Chart</h4>

                    <div class="text-center mt-4" dir="ltr">
                        <div id="sparkline6" class="text-center"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

        <div class="col-lg-6 col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Discrete Chart</h4>

                    <div class="text-center mt-4" dir="ltr">
                        <div id="sparkline7" class="text-center" data-colors="#6c757d"></div>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- card -->
        </div> <!-- end col -->

    </div>
    <!-- end row -->

</div> <!-- container -->

@endsection

@section('script-bottom')
<!-- third party js -->
<script src="{{asset('assets/libs/jquery-sparkline/jquery-sparkline.min.js')}}"></script>
<!-- third party js ends -->

<!-- demo app -->
<script src="{{asset('assets/js/pages/demo.sparkline.js')}}"></script>
<!-- end demo js-->
@endsection