@extends('layouts.vertical', ["page_title"=> "Apex Area Chart"])


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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Apex</a></li>
                        <li class="breadcrumb-item active">Area Charts</li>
                    </ol>
                </div>
                <h4 class="page-title">Area Charts</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Basic Area Chart</h4>
                    <div dir="ltr">
                        <div id="basic-area" class="apex-charts" data-colors="#fa6767"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Spline Area</h4>
                    <div dir="ltr">
                        <div id="spline-area" class="apex-charts" data-colors="#727cf5,#6c757d"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Area Chart - Datetime X-axis</h4>
                    <div class="toolbar">
                        <button id="one_month" class="btn btn-sm btn-light">1M</button>
                        <button id="six_months" class="btn btn-sm btn-light">6M</button>
                        <button id="one_year" class="btn btn-sm btn-light active">1Y</button>
                        <button id="ytd" class="btn btn-sm btn-light">YTD</button>
                        <button id="all" class="btn btn-sm btn-light">ALL</button>
                    </div>
                    <div dir="ltr">
                        <div id="area-chart-datetime" class="apex-charts" data-colors="#6c757d"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Area with Negative Values</h4>
                    <div dir="ltr">
                        <div id="area-chart-negative" class="apex-charts" data-colors="#0acf97,#ffbc00"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Selection - Github Style</h4>
                    <div dir="ltr">
                        <div id="area-chart-github" class="apex-charts" data-colors="#7BD39A"></div>
                        <div class="pt-2 pb-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <img src="{{asset('assets/images/users/avatar-2.jpg')}}" class="avatar-xs rounded" alt="file-image">
                                </div>
                                <div class="col ps-0">
                                    <a href="javascript:void(0);" class="text-muted fw-bold">Coderthemes</a>
                                </div>
                            </div>
                        </div>
                        <div id="area-chart-github2" class="apex-charts" data-colors="#FF7F00"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Stacked Area</h4>
                    <div dir="ltr">
                        <div id="stacked-area" class="apex-charts" data-colors="#727cf5,#0acf97,#e3eaef"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Irregular TimeSeries</h4>
                    <div dir="ltr">
                        <div id="area-timeSeries" class="apex-charts" data-colors="#39afd1,#fa5c7c,#727cf5"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title mb-4">Area Chart with Null values</h4>
                    <div dir="ltr">
                        <div id="area-chart-nullvalues" class="apex-charts" data-colors="#6c757d"></div>
                    </div>
                </div>
                <!-- end card body-->
            </div>
            <!-- end card -->
        </div>
        <!-- end col-->
    </div>
    <!-- end row-->

</div> <!-- container -->

@endsection

@section('script-bottom')
<!-- third party js -->
<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>
<!-- third party js ends -->

<!-- demo app -->
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
<script src="https://apexcharts.com/samples/assets/series1000.js"></script>
<script src="https://apexcharts.com/samples/assets/github-data.js"></script>
<script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>
<script src="{{asset('assets/js/pages/demo.apex-area.js')}}"></script>
<!-- end demo js-->
@endsection