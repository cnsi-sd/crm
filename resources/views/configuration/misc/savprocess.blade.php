@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.savprocess.savprocess') ])

@section('misc_content')
    <div class="col-lg-8">
        <form class="form-horizontal row" method="post">
            @csrf

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('app.config.misc.savprocess.out') }}
                    </div>

                    <div class="card-body">
                        @include('form_components.switch', [
                            'translation' => __('app.config.misc.savprocess.active'),
                            'name' => 'active',
                            'value' => old('active', setting('savprocess.active')),
                        ])

                        <div class="mb-3">
                            <label for="url" class="form-label">
                                {{ __('app.config.misc.savprocess.url') }}
                            </label>
                            <input name="url" class="form-control" value="{{ old('url', setting('savprocess.url')) }}" placeholder="https://xxx.mon-sav.online/procedure-sav">
                        </div>

                        <div class="mb-3">
                            <label for="token" class="form-label">
                                {{ __('app.config.misc.savprocess.token') }}
                            </label>
                            <input name="token" class="form-control" value="{{ old('token', setting('savprocess.token')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        {{ __('app.config.misc.savprocess.in') }}
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label for="api_token" class="form-label">
                                {{ __('app.config.misc.savprocess.api_token') }}
                            </label>
                            <input name="api_token" class="form-control" value="{{ old('api_token', setting('savprocess.api_token')) }}">
                        </div>

                        @include('form_components.tag_select', [
                            'translation' => __('app.config.misc.savprocess.complete_tag'),
                            'name' => 'complete_tag_id',
                            'value' => old('complete_tag_id', setting('savprocess.complete_tag_id')),
                        ])

                        <div class="mb-3">
                            <label for="late_order_tag_id" class="form-label">
                                {{ __('app.config.misc.savprocess.stop_revival') }}
                            </label>
                            <select name="stop_revival_ids[]" id="savprocess.stop_revival_ids" class="form-control form-select" multiple>
                                @foreach(\App\Models\Ticket\Revival\Revival::all() as $revival)
                                    <option value="{{ $revival->id }}" @selected(in_array($revival->id, explode(',',setting('savprocess.stop_revival_ids'))))>
                                        {{ $revival->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            @include('form_components.submit')
        </form>
    </div>
@endsection
