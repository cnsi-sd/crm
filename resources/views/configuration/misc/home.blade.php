@extends('layouts.horizontal', ["page_title"=> $page_title ?? __('app.config.misc.misc') ])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <ul class="list-group rounded">
                    <a href="{{ route('variables_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'variables_config'])>
                        {{ __('app.config.misc.variables') }}
                    </a>
                    <a href="{{ route('incidents_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'incidents_config'])>
                        {{ __('app.config.misc.incidents') }}
                    </a>
                    <a href="{{ route('savprocess_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'savprocess_config'])>
                        {{ __('app.config.misc.savprocess') }}
                    </a>
                    <a href="{{ route('mirakl_refunds_config') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'mirakl_refunds_config'])>
                        {{ __('app.config.misc.mirakl_refunds') }}
                    </a>
                </ul>
            </div>
            @yield('misc_content')
        </div>
    </div>
@endsection
