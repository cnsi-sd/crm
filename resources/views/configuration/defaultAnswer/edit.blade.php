@extends('layouts.horizontal', ["page_title"=> isset($defaultAnswer->id) ? trans_choice('app.edit', 1) : trans_choice('app.create', 1)])

@section('content')
    <form
        action="{{ isset($defaultAnswer->id) ? route('edit_default_answer', [$defaultAnswer->id]) : route('create_default_answer') }}"
        method="POST"
    >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ isset($defaultAnswer->id) ? trans_choice('app.default_answer.default_answer', 1) . " #$defaultAnswer->id" : trans_choice('app.default_answer.default_answer', 1) }}
                            @can('lock', $defaultAnswer)
                                <div class="form-check form-switch float-end">
                                    <input type="checkbox" class="form-check-input" id="is_locked" name="isLocked" @if($defaultAnswer->is_locked == 1) checked @endif>
                                    <label class="form-check-label" for="is_locked">{{ __('app.default_answer.lock') }} ?</label>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{__('app.default_answer.name')}}
                                    <span class="required_field">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control form-control-sm"
                                    value="{{ old('name', $defaultAnswer->name ?? '') }}"
                                    required
                                />
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{__('app.default_answer.content')}}
                                    <span class="required_field">*</span>
                                </label>
                                <textarea id="message_to_customer" name="content">{{ old('content', $defaultAnswer->content) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="m-2">
                            <label for="channels">{{__('app.default_answer.select_channel')}}</label>
                            <select
                                name="channels[]"
                                id="channels"
                                class="form-control form-control-sm form-select"
                                multiple
                            >
                                @foreach(\App\Models\Channel\Channel::all() as $channel)
                                    <option value="{{$channel->id}}" @selected($defaultAnswer->isChannelAuthorized($channel)) >
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
                            <span class="help-block">
                                <small>
                                    {{ __('app.default_answer.select_all_channel') }}
                                </small>
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="d-grid gap-2 mt-3">
            <button type="submit" name="save_default_answer" class="btn btn-primary btn-block">
                {{ __('app.save') }}
            </button>
        </div>
    </form>
@endsection

@section('script-bottom')
    {!! \App\Helpers\JS::define('messageVariables', \App\Enums\Ticket\MessageVariable::getTinyMceVariables()) !!}
    <script src="{{ asset('build/tinymce/tinymce.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/tinymce.js') }}"></script>
@endsection
