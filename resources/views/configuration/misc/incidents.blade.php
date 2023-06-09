@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.incidents.incidents') ])

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
                       'translation' => __('app.config.misc.incidents.active'),
                       'name' => 'active',
                       'value' => old('active', setting('incidents.active')),
                   ])

                    @include('form_components.tag_select', [
                        'translation' => __('app.config.misc.incidents.incident_tag'),
                        'name' => 'incident_tag_id',
                        'value' => old('incident_tag_id', setting('incident_tag_id')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
@endsection
