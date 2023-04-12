@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.pm.pm') ])

@section('misc_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="active" id="active" @checked(setting('pm.active'))>
                            <label class="form-check-label" for="active">{{ __('app.config.misc.pm.active') }}</label>
                        </div>
                    </div>

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

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
