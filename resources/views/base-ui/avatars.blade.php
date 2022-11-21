@extends('layouts.vertical', ["page_title"=> "Avatars"])

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
                        <li class="breadcrumb-item active">Avatars</li>
                    </ol>
                </div>
                <h4 class="page-title">Avatars</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Sizing - Images</h4>
                    <p class="text-muted font-14 mb-3">
                        Create and group avatars of different sizes and shapes with the css classes.
                        Using Bootstrap's naming convention, you can control size of avatar including standard avatar, or scale it up to different sizes.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#sizing-images-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#sizing-images-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="sizing-images-preview">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-xs rounded">
                                    <p>
                                        <code>.avatar-xs</code>
                                    </p>
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-sm rounded mt-2">
                                    <p class="mb-2 mb-sm-0">
                                        <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-md rounded" />
                                    <p>
                                        <code>.avatar-md</code>
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-lg rounded" />
                                    <p>
                                        <code>.avatar-lg</code>
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-xl rounded" />
                                    <p class="mb-0">
                                        <code>.avatar-xl</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="sizing-images-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                            &lt;!-- Avatar Extra Small --&gt;
                                                            &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xs&quot;&gt;

                                                            &lt;!-- Avatar Small --&gt;
                                                            &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-sm&quot;&gt;

                                                            &lt;!-- Avatar Medium --&gt;
                                                            &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-md&quot;&gt;

                                                            &lt;!-- Avatar Large --&gt;
                                                            &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-lg&quot;&gt;

                                                            &lt;!-- Avatar Extra Large --&gt;
                                                            &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xl&quot;&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Rounded Circle</h4>
                    <p class="text-muted font-14 mb-3">
                        Using an additional class <code>.rounded-circle</code> in <code>&lt;img&gt;</code> element creates the rounded avatar.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#rounded-images-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rounded-images-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="rounded-images-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-md rounded-circle" />
                                    <p class="mt-1">
                                        <code>.avatar-md .rounded-circle</code>
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-lg rounded-circle" />
                                    <p>
                                        <code>.avatar-lg .rounded-circle</code>
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <img src="{{asset('assets/images/users/avatar.png')}}" alt="image" class="img-fluid avatar-xl rounded-circle" />
                                    <p class="mb-0">
                                        <code>.avatar-xl .rounded-circle</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="rounded-images-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Avatar Medium --&gt;
                                                        &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-md rounded-circle&quot;&gt;

                                                        &lt;!-- Avatar Large --&gt;
                                                        &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-lg rounded-circle&quot;&gt;

                                                        &lt;!-- Avatar Extra Large --&gt;
                                                        &lt;img src=&quot;assets/images/users/avatar.png&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xl rounded-circle&quot;&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->


    <div class="row">
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Sizing - Background Color</h4>
                    <p class="text-muted font-14 mb-3">
                        Using utilities classes of background e.g. <code>bg-*</code> allows you to have any background color as well.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#sizing-bg-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#sizing-bg-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="sizing-bg-preview">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded">
                                            xs
                                        </span>
                                    </div>
                                    <p class="mb-2 font-14 mt-1">
                                        Using <code>.avatar-xs</code>
                                    </p>

                                    <div class="avatar-sm mt-3">
                                        <span class="avatar-title bg-success rounded">
                                            sm
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-sm</code>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <div class="avatar-md">
                                        <span class="avatar-title bg-info-lighten text-info font-20 rounded">
                                            MD
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-md</code>
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <div class="avatar-lg">
                                        <span class="avatar-title bg-danger font-22 rounded">
                                            LG
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-lg</code>
                                    </p>
                                </div>

                                <div class="col-md-3">
                                    <div class="avatar-xl">
                                        <span class="avatar-title bg-warning-lighten text-warning font-24 rounded">
                                            XL
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-xl</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="sizing-bg-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Avatar Extra Small --&gt;
                                                        &lt;div class=&quot;avatar-xs&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded&quot;&gt;
                                                                xs
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Small --&gt;
                                                        &lt;div class=&quot;avatar-sm&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded&quot;&gt;
                                                                sm
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Medium --&gt;
                                                        &lt;div class=&quot;avatar-md&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded&quot;&gt;
                                                                md
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Large --&gt;
                                                        &lt;div class=&quot;avatar-lg&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded&quot;&gt;
                                                                lg
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Extra Small --&gt;
                                                        &lt;div class=&quot;avatar-xl&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded&quot;&gt;
                                                                xl
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div>
            </div>
        </div>
        <div class="col-xxl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Rounded Circle Background</h4>
                    <p class="text-muted font-14 mb-3">
                        Using an additional class <code>.rounded-circle</code> in <code>&lt;img&gt;</code> element creates the rounded avatar.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#rounded-bg-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rounded-bg-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="rounded-bg-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="avatar-md">
                                        <span class="avatar-title bg-secondary-lighten text-secondary font-20 rounded-circle">
                                            MD
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-md .rounded-circle</code>
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <div class="avatar-lg">
                                        <span class="avatar-title bg-light text-dark font-22 rounded-circle">
                                            LG
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-lg .rounded-circle</code>
                                    </p>
                                </div>

                                <div class="col-md-4">
                                    <div class="avatar-xl">
                                        <span class="avatar-title bg-primary-lighten text-primary font-24 rounded-circle">
                                            XL
                                        </span>
                                    </div>

                                    <p class="mb-0 font-14 mt-1">
                                        Using <code>.avatar-xl .rounded-circle</code>
                                    </p>
                                </div>
                            </div> <!-- end row-->
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="rounded-bg-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Avatar Medium --&gt;
                                                        &lt;div class=&quot;avatar-md&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded-circle&quot;&gt;
                                                                md
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Large --&gt;
                                                        &lt;div class=&quot;avatar-lg&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded-circle&quot;&gt;
                                                                lg
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;

                                                        &lt;!-- Avatar Extra Small --&gt;
                                                        &lt;div class=&quot;avatar-xl&quot;&gt;
                                                            &lt;span class=&quot;avatar-title bg-success rounded-circle&quot;&gt;
                                                                xl
                                                            &lt;/span&gt;
                                                        &lt;/div&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Images shapes</h4>
                    <p class="text-muted font-14 mb-3">
                        Avatars with different sizes and shapes.
                    </p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#images-shape-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#images-shape-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->

                    <div class="tab-content">
                        <div class="tab-pane show active" id="images-shape-preview">
                            <div class="row">
                            </div> <!-- end row-->
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="images-shape-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Rounded --&gt;
                                                        &lt;img src=&quot;assets/images/small/small-2.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded&quot; width=&quot;200&quot;/&gt;

                                                        &lt;!-- Rounded Circle--&gt;
                                                        &lt;img src=&quot;assets/images/user/avatar-1.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded-circle&quot; width=&quot;120&quot;/&gt;

                                                        &lt;!-- Thumbnail--&gt;
                                                        &lt;img src=&quot;assets/images/small/small-3.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid img-thumbnail&quot; width=&quot;200&quot;/&gt;

                                                        &lt;!-- Thumbnail Circle--&gt;
                                                        &lt;img src=&quot;assets/images/user/avatar-2.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid img-thumbnail rounded-circle&quot; width=&quot;120&quot;/&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->

                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

</div> <!-- container -->
@endsection
