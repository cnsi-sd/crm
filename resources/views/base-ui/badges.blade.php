@extends('layouts.vertical', ["page_title"=> "Badges"])

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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Base UI</a></li>
                        <li class="breadcrumb-item active">Badges</li>
                    </ol>
                </div>
                <h4 class="page-title">Badges</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Default</h4>
                    <p class="text-muted font-14 mb-3">
                        A simple labeling component. Badges scale to match the size of the immediate parent element by using relative font sizing and <code>em</code> units.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#default-sizes-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#default-sizes-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="default-sizes-preview">
                            <h1>h1.Example heading <span class="badge bg-secondary text-light">New</span></h1>
                            <h2>h2.Example heading <span class="badge badge-success-lighten">New</span></h2>
                            <h3>
                                h3.Example heading
                                <button type="button" class="btn btn-sm btn-primary">
                                    Notifications <span class="badge bg-light text-dark">4</span>
                                </button>
                            </h3>
                            <h4>h4.Example heading <a href="#" class="badge badge-info-lighten">Info Link</a></h4>
                            <h5>h5.Example heading <span class="badge badge-outline-warning">New</span></h5>
                            <h6>h6.Example heading <span class="badge bg-danger">New</span></h6>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="default-sizes-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;h1&gt;h1.Example heading &lt;span class=&quot;badge bg-secondary text-light&quot;&gt;New&lt;/span&gt;&lt;/h1&gt;
                                                        &lt;h2&gt;h2.Example heading &lt;span class=&quot;badge badge-success-lighten&quot;&gt;New&lt;/span&gt;&lt;/h2&gt;
                                                        &lt;h3&gt;
                                                            h3.Example heading 
                                                            &lt;button type=&quot;button&quot; class=&quot;btn btn-sm btn-primary&quot;&gt;
                                                                Notifications &lt;span class=&quot;badge bg-light text-dark&quot;&gt;4&lt;/span&gt;
                                                            &lt;/button&gt;
                                                        &lt;/h3&gt;
                                                        &lt;h4&gt;h4.Example heading &lt;a href=&quot;#&quot; class=&quot;badge badge-info-lighten&quot;&gt;Info Link&lt;/a&gt;&lt;/h4&gt;
                                                        &lt;h5&gt;h5.Example heading &lt;span class=&quot;badge badge-outline-warning&quot;&gt;New&lt;/span&gt;&lt;/h5&gt;
                                                        &lt;h6&gt;h6.Example heading &lt;span class=&quot;badge bg-danger&quot;&gt;New&lt;/span&gt;&lt;/h6&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Pill Badges</h4>
                    <p class="text-muted font-14 mb-3">
                        Use the <code>.rounded-pill</code> modifier class to make badges more rounded.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#pill-badges-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#pill-badges-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="pill-badges-preview">

                            <span class="badge bg-primary rounded-pill">Primary</span>
                            <span class="badge bg-secondary text-light rounded-pill">Secondary</span>
                            <span class="badge bg-success rounded-pill">Success</span>
                            <span class="badge bg-danger rounded-pill">Danger</span>
                            <span class="badge bg-warning rounded-pill">Warning</span>
                            <span class="badge bg-info rounded-pill">Info</span>
                            <span class="badge bg-light text-dark rounded-pill">Light</span>
                            <span class="badge bg-dark rounded-pill">Dark</span>

                            <h5 class="mt-4">Lighten Badges</h5>
                            <p class="text-muted font-14 mb-3">
                                Use the <code>.badge-*-lighten</code> modifier class to make badges lighten.
                            </p>

                            <span class="badge badge-primary-lighten rounded-pill">Primary</span>
                            <span class="badge badge-secondary-lighten rounded-pill">Secondary</span>
                            <span class="badge badge-success-lighten rounded-pill">Success</span>
                            <span class="badge badge-danger-lighten rounded-pill">Danger</span>
                            <span class="badge badge-warning-lighten rounded-pill">Warning</span>
                            <span class="badge badge-info-lighten rounded-pill">Info</span>
                            <span class="badge badge-light-lighten rounded-pill">Light</span>
                            <span class="badge badge-dark-lighten rounded-pill">Dark</span>

                            <h5 class="mt-4">Outline Badges</h5>
                            <p class="text-muted font-14 mb-3">
                                Using the <code>.badge-outline-*</code> to quickly create a bordered badges.
                            </p>

                            <span class="badge badge-outline-primary rounded-pill">Primary</span>
                            <span class="badge badge-outline-secondary rounded-pill">Secondary</span>
                            <span class="badge badge-outline-success rounded-pill">Success</span>
                            <span class="badge badge-outline-danger rounded-pill">Danger</span>
                            <span class="badge badge-outline-warning rounded-pill">Warning</span>
                            <span class="badge badge-outline-info rounded-pill">Info</span>
                            <span class="badge badge-outline-light rounded-pill">Light</span>
                            <span class="badge badge-outline-dark rounded-pill">Dark</span>

                        </div> <!-- end preview-->

                        <div class="tab-pane" id="pill-badges-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Default Badges --&gt;
                                                        &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-secondary text-light rounded-pill&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-success rounded-pill&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-danger rounded-pill&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-warning rounded-pill&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-info rounded-pill&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-light text-dark rounded-pill&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-dark rounded-pill&quot;&gt;Dark&lt;/span&gt;
                                                        
                                                        &lt;!-- Lighten Badges --&gt;
                                                        &lt;span class=&quot;badge badge-primary-lighten rounded-pill&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-secondary-lighten rounded-pill&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-success-lighten rounded-pill&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-danger-lighten rounded-pill&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-warning-lighten rounded-pill&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-info-lighten rounded-pill&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-light-lighten rounded-pill&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-dark-lighten rounded-pill&quot;&gt;Dark&lt;/span&gt;

                                                        &lt;!-- Outline Badges --&gt;
                                                        &lt;span class=&quot;badge badge-outline-primary rounded-pill&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-secondary rounded-pill&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-success rounded-pill&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-danger rounded-pill&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-warning rounded-pill&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-info rounded-pill&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-light rounded-pill&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-dark rounded-pill&quot;&gt;Dark&lt;/span&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div> <!-- end col-->
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Contextual variations</h4>
                    <p class="text-muted font-14 mb-3">
                        Add any of the below mentioned modifier classes to change the appearance of a badge.
                        Badge can be more contextual as well. Just use regular convention e.g. <code>badge-*color</code>, <code>bg-primary</code>
                        to have badge with different background.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#contextual-badges-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#contextual-badges-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="contextual-badges-preview">
                            <span class="badge bg-primary">Primary</span>
                            <span class="badge bg-secondary text-light">Secondary</span>
                            <span class="badge bg-success">Success</span>
                            <span class="badge bg-danger">Danger</span>
                            <span class="badge bg-warning">Warning</span>
                            <span class="badge bg-info">Info</span>
                            <span class="badge bg-light text-dark">Light</span>
                            <span class="badge bg-dark">Dark</span>

                            <h5 class="mt-4">Lighten Badges</h5>
                            <p class="text-muted font-14 mb-3">
                                Using the <code>.badge-*-lighten</code> modifier class, you can have more soften variation.
                            </p>

                            <span class="badge badge-primary-lighten">Primary</span>
                            <span class="badge badge-secondary-lighten">Secondary</span>
                            <span class="badge badge-success-lighten">Success</span>
                            <span class="badge badge-danger-lighten">Danger</span>
                            <span class="badge badge-warning-lighten">Warning</span>
                            <span class="badge badge-info-lighten">Info</span>
                            <span class="badge badge-light-lighten">Light</span>
                            <span class="badge badge-dark-lighten">Dark</span>

                            <h5 class="mt-4">Outline Badges</h5>
                            <p class="text-muted font-14 mb-3">
                                Using the <code>.badge-outline-*</code> to quickly create a bordered badges.
                            </p>

                            <span class="badge badge-outline-primary">Primary</span>
                            <span class="badge badge-outline-secondary">Secondary</span>
                            <span class="badge badge-outline-success">Success</span>
                            <span class="badge badge-outline-danger">Danger</span>
                            <span class="badge badge-outline-warning">Warning</span>
                            <span class="badge badge-outline-info">Info</span>
                            <span class="badge badge-outline-light">Light</span>
                            <span class="badge badge-outline-dark">Dark</span>

                        </div> <!-- end preview-->

                        <div class="tab-pane" id="contextual-badges-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Default Badges --&gt;
                                                        &lt;span class=&quot;badge bg-primary&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-secondary text-light&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-success&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-danger&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-warning&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-info&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-light text-dark&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge bg-dark&quot;&gt;Dark&lt;/span&gt;
                                                        
                                                        &lt;!-- Lighten Badges --&gt;
                                                        &lt;span class=&quot;badge badge-primary-lighten&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-secondary-lighten&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-success-lighten&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-danger-lighten&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-warning-lighten&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-info-lighten&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-light-lighten&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-dark-lighten&quot;&gt;Dark&lt;/span&gt;

                                                        &lt;!-- Outline Badges --&gt;
                                                        &lt;span class=&quot;badge badge-outline-primary&quot;&gt;Primary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-secondary&quot;&gt;Secondary&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-success&quot;&gt;Success&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-danger&quot;&gt;Danger&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-warning&quot;&gt;Warning&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-info&quot;&gt;Info&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-light&quot;&gt;Light&lt;/span&gt;
                                                        &lt;span class=&quot;badge badge-outline-dark&quot;&gt;Dark&lt;/span&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row -->

</div> <!-- container -->
@endsection