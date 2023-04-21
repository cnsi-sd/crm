@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.closed_discussion.closed_discussion') ])

@section('misc_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    @include('form_components.switch', [
                       'translation' => __('app.config.misc.closed_discussion.active'),
                       'name' => 'active',
                       'value' => old('active', setting('closed_discussion.active')),
                   ])

                    @include('form_components.tag_select', [
                        'translation' => __('app.config.misc.closed_discussion.closed_discussion_tag'),
                        'name' => 'closed_discussion_tag_id',
                        'value' => old('closed_discussion_tag_id', setting('closed_discussion_tag_id')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
@endsection
