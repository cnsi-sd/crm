@extends('layouts.horizontal', ["page_title"=> $page_title ?? __('app.config.misc.misc') ])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 mb-3">
                <ul class="list-group rounded mb-3">
                    <a href="{{ route('variables_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'variables_config'])>
                        {{ __('app.config.misc.variables') }}
                    </a>
                    <a href="{{ route('incidents_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'incidents_config'])>
                        {{ __('app.config.misc.incidents') }}
                    </a>
                    <a href="{{ route('answer_offer_questions_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'answer_offer_questions_config'])>
                        {{ __('app.config.misc.default_answer_offer_questions.default_answer_offer_questions') }}
                        @include('configuration.misc.parts.active_badge', ['active' => setting('daoq.active')])
                    </a>
                    <a href="{{ route('mirakl_refunds_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'mirakl_refunds_config'])>
                        {{ __('app.config.misc.mirakl_refunds.mirakl_refunds') }}
                        @include('configuration.misc.parts.active_badge', ['active' => setting('mirakl_refunds.active')])
                    </a>
                    <a href="{{ route('closed_discussion_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'closed_discussion_config'])>
                        {{ __('app.config.misc.closed_discussion') }}
                    </a>
                </ul>

                <div class="ticket-divider h4 text-center">
                    {{ __('app.config.misc.external_features') }}
                </div>
                <ul class="list-group rounded">
                    <a href="{{ route('savprocess_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'savprocess_config'])>
                        {{ __('app.config.misc.savprocess.savprocess') }}
                        @include('configuration.misc.parts.active_badge', ['active' => setting('savprocess.active')])
                    </a>
                    <a href="{{ route('parcel_management_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'parcel_management_config'])>
                        {{ __('app.config.misc.pm.pm') }}
                        @include('configuration.misc.parts.active_badge', ['active' => setting('pm.active')])
                    </a>
                </ul>
            </div>
            @yield('misc_content')
        </div>
    </div>
@endsection
