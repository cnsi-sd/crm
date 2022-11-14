@extends('layouts.vertical', ["page_title"=> "Buttons"])

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">UI Kit</a></li>
                        <li class="breadcrumb-item active">Buttons</li>
                    </ol>
                </div>
                <h4 class="page-title">Buttons</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Default Buttons</h4>
                    <p class="text-muted font-14">Use the button classes on an <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code>, or <code>&lt;input&gt;</code> element.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#default-buttons-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#default-buttons-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="default-buttons-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-primary">Primary</button>
                                <button type="button" class="btn btn-secondary">Secondary</button>
                                <button type="button" class="btn btn-success">Success</button>
                                <button type="button" class="btn btn-danger">Danger</button>
                                <button type="button" class="btn btn-warning">Warning</button>
                                <button type="button" class="btn btn-info">Info</button>
                                <button type="button" class="btn btn-light">Light</button>
                                <button type="button" class="btn btn-dark">Dark</button>
                                <button type="button" class="btn btn-link">Link</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="default-buttons-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Primary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot;&gt;Danger&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;Warning&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Light&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-dark&quot;&gt;Dark&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-link&quot;&gt;Link&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button Outline</h4>
                    <p class="text-muted font-14">Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#outline-buttons-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#outline-buttons-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="outline-buttons-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-outline-primary">Primary</button>
                                <button type="button" class="btn btn-outline-secondary">Secondary</button>
                                <button type="button" class="btn btn-outline-success"><i class="uil-cloud-computing"></i> Success</button>
                                <button type="button" class="btn btn-outline-danger">Danger</button>
                                <button type="button" class="btn btn-outline-warning">Warning</button>
                                <button type="button" class="btn btn-outline-info"><i class="uil-circuit"></i> Info</button>
                                <button type="button" class="btn btn-outline-light">Light</button>
                                <button type="button" class="btn btn-outline-dark">Dark</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="outline-buttons-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Primary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-success&quot;&gt;&lt;i class=&quot;uil-cloud-computing&quot;&gt;&lt;/i&gt; Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-danger&quot;&gt;Danger&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-warning&quot;&gt;Warning&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-info&quot;&gt;&lt;i class=&quot;uil-circuit&quot;&gt;&lt;/i&gt; Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-light&quot;&gt;Light&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-dark&quot;&gt;Dark&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button-Rounded</h4>
                    <p class="text-muted font-14">Add <code>.btn-rounded</code> to default button to get rounded corners.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#rounded-buttons-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rounded-buttons-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="rounded-buttons-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-primary btn-rounded">Primary</button>
                                <button type="button" class="btn btn-secondary btn-rounded">Secondary</button>
                                <button type="button" class="btn btn-success btn-rounded">Success</button>
                                <button type="button" class="btn btn-danger btn-rounded">Danger</button>
                                <button type="button" class="btn btn-warning btn-rounded">Warning</button>
                                <button type="button" class="btn btn-info btn-rounded">Info</button>
                                <button type="button" class="btn btn-light btn-rounded">Light</button>
                                <button type="button" class="btn btn-dark btn-rounded">Dark</button>
                                <button type="button" class="btn btn-link btn-rounded">Link</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="rounded-buttons-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary btn-rounded&quot;&gt;Primary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary btn-rounded&quot;&gt;Secondary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success btn-rounded&quot;&gt;Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-danger btn-rounded&quot;&gt;Danger&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-warning btn-rounded&quot;&gt;Warning&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info btn-rounded&quot;&gt;Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light btn-rounded&quot;&gt;Light&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-dark btn-rounded&quot;&gt;Dark&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-link btn-rounded&quot;&gt;Link&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button Outline Rounded</h4>
                    <p class="text-muted font-14">Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#outline-rounded-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#outline-rounded-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="outline-rounded-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-outline-primary btn-rounded">Primary</button>
                                <button type="button" class="btn btn-outline-secondary btn-rounded">Secondary</button>
                                <button type="button" class="btn btn-outline-success btn-rounded"><i class="uil-cloud-computing"></i> Success</button>
                                <button type="button" class="btn btn-outline-danger btn-rounded">Danger</button>
                                <button type="button" class="btn btn-outline-warning btn-rounded">Warning</button>
                                <button type="button" class="btn btn-outline-info btn-rounded"><i class="uil-circuit"></i> Info</button>
                                <button type="button" class="btn btn-outline-light btn-rounded">Light</button>
                                <button type="button" class="btn btn-outline-dark btn-rounded">Dark</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="outline-rounded-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary btn-rounded&quot;&gt;Primary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary btn-rounded&quot;&gt;Secondary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-success btn-rounded&quot;&gt;&lt;i class=&quot;uil-cloud-computing&quot;&gt;&lt;/i&gt; Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-danger btn-rounded&quot;&gt;Danger&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-warning btn-rounded&quot;&gt;Warning&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-info btn-rounded&quot;&gt;&lt;i class=&quot;uil-circuit&quot;&gt;&lt;/i&gt; Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-light btn-rounded&quot;&gt;Light&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-dark btn-rounded&quot;&gt;Dark&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button-Sizes</h4>
                    <p class="text-muted font-14">
                        Add <code>.btn-lg</code>, <code>.btn-sm</code> for additional sizes.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#button-sizes-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#button-sizes-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="button-sizes-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-primary btn-lg">Large</button>
                                <button type="button" class="btn btn-info">Normal</button>
                                <button type="button" class="btn btn-success btn-sm">Small</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="button-sizes-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary btn-lg&quot;&gt;Large&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;Normal&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success btn-sm&quot;&gt;Small&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button-Disabled</h4>
                    <p class="text-muted font-14">
                        Add the <code>disabled</code> attribute to <code>&lt;button&gt;</code> buttons.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#button-disabled-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#button-disabled-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="button-disabled-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-info" disabled>Info</button>
                                <button type="button" class="btn btn-success" disabled>Success</button>
                                <button type="button" class="btn btn-danger" disabled>Danger</button>
                                <button type="button" class="btn btn-dark" disabled>Dark</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="button-disabled-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot; disabled&gt;Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot; disabled&gt;Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot; disabled&gt;Danger&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-dark&quot; disabled&gt;Dark&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Icon Buttons</h4>
                    <p class="text-muted font-14">
                        Icon only button.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#icon-buttons-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#icon-buttons-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="icon-buttons-preview">
                            <div class="button-list">
                                <button type="button" class="btn btn-light"><i class="mdi mdi-heart-outline"></i> </button>
                                <button type="button" class="btn btn-danger"><i class="mdi mdi-window-close"></i> </button>
                                <button type="button" class="btn btn-dark"><i class="mdi mdi-music"></i> </button>
                                <button type="button" class="btn btn-primary"><i class="mdi mdi-star"></i> </button>
                                <button type="button" class="btn btn-success"><i class="mdi mdi-thumb-up-outline"></i> </button>
                                <button type="button" class="btn btn-info"><i class="mdi mdi-keyboard"></i> </button>
                                <button type="button" class="btn btn-warning"><i class="mdi mdi-wrench"></i> </button>
                                <br>
                                <button type="button" class="btn btn-light"><i class="mdi mdi-heart me-1"></i> <span>Like</span> </button>
                                <button type="button" class="btn btn-warning"><i class="mdi mdi-rocket me-1"></i> <span>Launch</span> </button>
                                <button type="button" class="btn btn-info"><i class="mdi mdi-cloud me-1"></i> <span>Cloud Hosting</span> </button>
                                <br>
                                <button type="button" class="btn btn-outline-success"><i class="uil-money-withdrawal"></i> Money</button>
                                <button type="button" class="btn btn-outline-primary"><i class="uil-paypal"></i> PayPal</button>
                                <button type="button" class="btn btn-outline-danger"><i class="uil-cog"></i> Settings</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="icon-buttons-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;&lt;i class=&quot;mdi mdi-heart-outline&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot;&gt;&lt;i class=&quot;mdi mdi-window-close&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-dark&quot;&gt;&lt;i class=&quot;mdi mdi-music&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;&lt;i class=&quot;mdi mdi-star&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;&lt;i class=&quot;mdi mdi-thumb-up-outline&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;&lt;i class=&quot;mdi mdi-keyboard&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;&lt;i class=&quot;mdi mdi-wrench&quot;&gt;&lt;/i&gt; &lt;/button&gt;
                                                        
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;&lt;i class=&quot;mdi mdi-heart me-1&quot;&gt;&lt;/i&gt; &lt;span&gt;Like&lt;/span&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;&lt;i class=&quot;mdi mdi-rocket me-1&quot;&gt;&lt;/i&gt; &lt;span&gt;Launch&lt;/span&gt; &lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;&lt;i class=&quot;mdi mdi-cloud me-1&quot;&gt;&lt;/i&gt; &lt;span&gt;Cloud Hosting&lt;/span&gt; &lt;/button&gt;
                                                        
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-success&quot;&gt;&lt;i class=&quot;uil-money-withdrawal&quot;&gt;&lt;/i&gt; Money&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;&lt;i class=&quot;uil-paypal&quot;&gt;&lt;/i&gt; PayPal&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-danger&quot;&gt;&lt;i class=&quot;uil-cog&quot;&gt;&lt;/i&gt; Settings&lt;/button&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Block Button</h4>

                    <p class="text-muted font-14">
                        Create block level buttons by adding class <code>.d-grid</code> to parent div.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#block-buttons-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#block-buttons-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show button-list active" id="block-buttons-preview">
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary">Block Button</button>
                                <button type="button" class="btn btn-sm btn-info">Block Button</button>
                                <button type="button" class="btn btn-xs btn-success">Block Button</button>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="block-buttons-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;div class=&quot;d-grid&quot;&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Block Button&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-info&quot;&gt;Block Button&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-xs btn-success&quot;&gt;Block Button&lt;/button&gt;
                                                        &lt;/div&gt;
                                                        &lt;!-- end d-grid --&gt;  
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div> <!-- end row -->


    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Button Group</h4>

                    <p class="text-muted font-14">
                        Wrap a series of buttons with <code>.btn</code> in <code>.btn-group</code>.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#button-group-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#button-group-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="button-group-preview">
                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-light">Left</button>
                                <button type="button" class="btn btn-light">Middle</button>
                                <button type="button" class="btn btn-light">Right</button>
                            </div>

                            <br>

                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-light">1</button>
                                <button type="button" class="btn btn-light">2</button>
                                <button type="button" class="btn btn-light">3</button>
                                <button type="button" class="btn btn-light">4</button>
                            </div>

                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-light">5</button>
                                <button type="button" class="btn btn-light">6</button>
                                <button type="button" class="btn btn-light">7</button>
                            </div>

                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-light">8</button>
                            </div>

                            <br>

                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-light">1</button>
                                <button type="button" class="btn btn-primary">2</button>
                                <button type="button" class="btn btn-light">3</button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Dropdown <span class="caret"></span> </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Dropdown link</a>
                                        <a class="dropdown-item" href="#">Dropdown link</a>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="btn-group-vertical mb-2">
                                        <button type="button" class="btn btn-light">Top</button>
                                        <button type="button" class="btn btn-light">Middle</button>
                                        <button type="button" class="btn btn-light">Bottom</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="btn-group-vertical mb-2">
                                        <button type="button" class="btn btn-light">Button 1</button>
                                        <button type="button" class="btn btn-light">Button 2</button>
                                        <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Button 3 <span class="caret"></span> </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="#">Dropdown link</a>
                                            <a class="dropdown-item" href="#">Dropdown link</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="button-group-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Button Group --&gt;
                                                        &lt;div class=&quot;btn-group mb-2&quot;&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Left&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Middle&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Right&lt;/button&gt;
                                                        &lt;/div&gt;
                                                        
                                                        &lt;!-- Button Group with Dropdowns--&gt;
                                                        &lt;div class=&quot;btn-group mb-2&quot;&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;1&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;2&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;3&lt;/button&gt;
                                                            &lt;div class=&quot;btn-group&quot;&gt;
                                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-light dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt; Dropdown &lt;span class=&quot;caret&quot;&gt;&lt;/span&gt; &lt;/button&gt;
                                                                &lt;div class=&quot;dropdown-menu&quot;&gt;
                                                                    &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;
                                                                    &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        
                                                        &lt;!-- Button Group Vertical--&gt;
                                                        &lt;div class=&quot;btn-group-vertical mb-2&quot;&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Top&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Middle&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Bottom&lt;/button&gt;
                                                        &lt;/div&gt;
                                                        
                                                        &lt;!-- Button Group Vertical with Dropdowns--&gt;
                                                        &lt;div class=&quot;btn-group-vertical mb-2&quot;&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Button 1&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Button 2&lt;/button&gt;
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-light dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt; Button 3 &lt;/button&gt;
                                                            &lt;div class=&quot;dropdown-menu&quot;&gt;
                                                                &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;
                                                                &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Dropdown link&lt;/a&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->

    </div> <!-- end row -->

</div> <!-- container -->
@endsection