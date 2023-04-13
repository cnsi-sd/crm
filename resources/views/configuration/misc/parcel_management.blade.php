@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.pm.pm') ])

@section('misc_content')
    <div class="col-8">
        <form class="form-horizontal row" method="post">
            @csrf

            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('app.config.misc.pm.out') }}
                    </div>

                    <div class="card-body">
                        @include('form_components.switch', [
                            'translation' => __('app.config.misc.pm.active'),
                            'name' => 'active',
                            'value' => old('active', setting('pm.active')),
                        ])

                        <div class="mb-3">
                            <label for="app_url" class="form-label">
                                {{ __('app.config.misc.pm.app_url') }}
                            </label>
                            <input name="app_url" class="form-control" value="{{ old('app_url', setting('pm.app_url')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="api_url" class="form-label">
                                {{ __('app.config.misc.pm.api_url') }}
                            </label>
                            <input name="api_url" class="form-control" value="{{ old('api_url', setting('pm.api_url')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="api_token" class="form-label">
                                {{ __('app.config.misc.pm.api_token') }}
                            </label>
                            <input name="api_token" class="form-control" value="{{ old('api_token', setting('pm.api_token')) }}">
                        </div>

                        <div class="mb-3">
                            <label for="id_shop" class="form-label">
                                {{ __('app.config.misc.pm.id_shop') }}
                            </label>
                            <input name="id_shop" class="form-control" value="{{ old('id_shop', setting('pm.id_shop')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('app.config.misc.pm.in') }}
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label for="api_url" class="form-label">
                                {{ __('app.config.misc.pm.close_api_token') }}
                            </label>
                            <input name="close_api_token" class="form-control" value="{{ old('close_api_token', setting('pm.close_api_token')) }}">
                        </div>

                        @include('form_components.tag_select', [
                            'translation' => __('app.config.misc.pm.accepted_return_tag'),
                            'name' => 'accepted_return_tag',
                            'value' => old('accepted_return_tag', setting('pm.accepted_return_tag')),
                        ])

                        @include('form_components.tag_select', [
                            'translation' => __('app.config.misc.pm.refused_return_tag'),
                            'name' => 'refused_return_tag',
                            'value' => old('refused_return_tag', setting('pm.refused_return_tag')),
                        ])

                        @include('form_components.tag_select', [
                            'translation' => __('app.config.misc.pm.return_with_reserves_tag'),
                            'name' => 'return_with_reserves_tag',
                            'value' => old('return_with_reserves_tag', setting('pm.return_with_reserves_tag')),
                        ])

                        @include('form_components.tag_select', [
                            'translation' => __('app.config.misc.pm.return_with_remark_tag'),
                            'name' => 'return_with_remark_tag',
                            'value' => old('return_with_remark_tag', setting('pm.return_with_remark_tag')),
                        ])
                    </div>
                </div>
            </div>

            @include('form_components.submit')
        </form>
    </div>
@endsection
