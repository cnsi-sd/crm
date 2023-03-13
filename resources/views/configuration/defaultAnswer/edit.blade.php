@extends('layouts.horizontal', ["page_title"=> isset($defaultAnswer->id) ? trans_choice('app.edit', 1) : trans_choice('app.create', 1)])

@section('content')
    <form
        action="{{ isset($defaultAnswer->id) ? route('edit_defaultAnswer', [$defaultAnswer->id]) : route('create_defaultAnswer') }}"
        method="POST"
    >
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            {{ isset($defaultAnswer->id) ? trans_choice('app.defaultAnswer.message', 1) . " #$defaultAnswer->id" : trans_choice('app.defaultAnswer.message', 1) }}
                        </div>
                        <div class="card-body">

                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{__('app.defaultAnswer.name')}}
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
                                    {{__('app.defaultAnswer.content')}}
                                </label>
                                <textarea id="message_to_customer" class="w-100" name="content">{{ \App\Helpers\TinyMCE::toHtml(old('content', $defaultAnswer->content)) }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="m-2">
                            <label for="name">{{__('app.defaultAnswer.select_channel')}}

                                <span class="required_field">*</span>
                            </label>
                            <select
                                name="channel[]"
                                id="select-mp"
                                class="form-control form-control-sm form-select"
                                multiple
                                required
                            >
                                <option value="">-- {{trans_choice('app.defaultAnswer.select_channel', 1)}} --
                                </option>
                                @foreach(\App\Models\Channel\Channel::all() as $channel)
                                    <option value="{{$channel->id}}"
                                            @if($defaultAnswer->isChannelSelected($channel)) selected @endif>
                                        {{$channel->name}}
                                    </option>
                                @endforeach
                            </select>
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
    <script src="{{ Vite::asset('resources/js/defaultAnswer.js') }}"></script>
@endsection
