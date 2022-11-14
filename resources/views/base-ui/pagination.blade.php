@extends('layouts.vertical', ["page_title"=> "Pagination"])

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
                        <li class="breadcrumb-item active">Pagination</li>
                    </ol>
                </div>
                <h4 class="page-title">Pagination</h4>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Default Pagination</h4>
                    <p class="text-muted font-14">Simple pagination inspired by Rdio, great for apps and search results.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#default-pagination-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#default-pagination-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="default-pagination-preview">
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="default-pagination-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;nav&gt;
                                                            &lt;ul class=&quot;pagination&quot;&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;4&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;5&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card-->

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Disabled and active states</h4>
                    <p class="text-muted font-14">Pagination links are customizable for different circumstances. Use <code>.disabled</code> for links that appear un-clickable and <code>.active</code> to indicate the current page.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#disabled-pagination-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#disabled-pagination-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="disabled-pagination-preview">
                            <nav aria-label="...">
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item active" aria-current="page">
                                        <a class="page-link" href="#">2</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="disabled-pagination-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;nav aria-label=&quot;...&quot;&gt;
                                                            &lt;ul class=&quot;pagination mb-0&quot;&gt;
                                                                &lt;li class=&quot;page-item disabled&quot;&gt;
                                                                &lt;a class=&quot;page-link&quot; href=&quot;#&quot; tabindex=&quot;-1&quot; aria-disabled=&quot;true&quot;&gt;Previous&lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;
                                                                &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;2&lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                &lt;a class=&quot;page-link&quot; href=&quot;#&quot;&gt;Next&lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card-->

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Alignment</h4>
                    <p class="text-muted font-14">Change the alignment of pagination components with flexbox utilities.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#alignment-pagination-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#alignment-pagination-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="alignment-pagination-preview">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript: void(0);" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);">Next</a>
                                    </li>
                                </ul>
                            </nav>

                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript: void(0);" tabindex="-1">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="alignment-pagination-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Center Align --&gt;
                                                        &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                            &lt;ul class=&quot;pagination justify-content-center&quot;&gt;
                                                                &lt;li class=&quot;page-item disabled&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; tabindex=&quot;-1&quot;&gt;Previous&lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;Next&lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
                                                        
                                                        &lt;!-- End Align --&gt;
                                                        &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                            &lt;ul class=&quot;pagination justify-content-end&quot;&gt;
                                                                &lt;li class=&quot;page-item disabled&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; tabindex=&quot;-1&quot;&gt;Previous&lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;Next&lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
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
                    <h4 class="header-title">Rounded Pagination</h4>
                    <p class="text-muted font-14">Add <code> .pagination-rounded</code> for rounded pagination.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#rounded-pagination-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#rounded-pagination-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="rounded-pagination-preview">
                            <nav>
                                <ul class="pagination pagination-rounded mb-0">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item active"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">4</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">5</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="rounded-pagination-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;nav&gt;
                                                            &lt;ul class=&quot;pagination pagination-rounded mb-0&quot;&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;4&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;5&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
                                                    </span>
                                                </pre> <!-- end highlight-->
                        </div> <!-- end preview code-->
                    </div> <!-- end tab-content-->
                </div> <!-- end card-body -->
            </div> <!-- end card-->

            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Sizing</h4>
                    <p class="text-muted font-14">Add <code> .pagination-lg</code> or <code> .pagination-sm</code> for additional sizes.</p>

                    <ul class="nav nav-tabs nav-bordered mb-3">
                        <li class="nav-item">
                            <a href="#sizing-pagination-preview" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                Preview
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#sizing-pagination-code" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                Code
                            </a>
                        </li>
                    </ul> <!-- end nav-->
                    <div class="tab-content">
                        <div class="tab-pane show active" id="sizing-pagination-preview">
                            <nav>
                                <ul class="pagination pagination-lg">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>

                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">1</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">2</a></li>
                                    <li class="page-item"><a class="page-link" href="javascript: void(0);">3</a></li>
                                    <li class="page-item">
                                        <a class="page-link" href="javascript: void(0);" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div> <!-- end preview-->

                        <div class="tab-pane" id="sizing-pagination-code">
                            <pre class="mb-0">
                                                    <span class="html escape">
                                                        &lt;!-- Large --&gt;
                                                        &lt;nav&gt;
                                                            &lt;ul class=&quot;pagination pagination-lg&quot;&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;

                                                        &lt;!-- Small --&gt;
                                                        &lt;nav&gt;
                                                            &lt;ul class=&quot;pagination pagination-sm mb-0&quot;&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li class=&quot;page-item&quot;&gt;
                                                                    &lt;a class=&quot;page-link&quot; href=&quot;javascript: void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                        &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                                    &lt;/a&gt;
                                                                &lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/nav&gt;
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