@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.answer_questions') ])

@section('misc_content')
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf
                    <div class=" row col-3 mb-3">
                        <label for="channelName">{{ trans_choice('app.config.channel', 1) }}</label>
                        <select name="channelName" class="form-control form-control-sm form-select" required>
                            @foreach(\App\Enums\Channel\ChannelEnum::getList() as $channelName)
                                <option value="{{ $channelName }}">
                                    {{ $channelName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">
                            {{__('app.config.misc.answer_offer_questions_message')}}
                            <span class="required_field">*</span>
                        </label>
{{--                        <textarea id="content" name="content" class="form-control">{{ \App\Helpers\TinyMCE::toHtml(old('content', $defaultAnswer->content))?? ''  }}</textarea>--}}
                        <textarea id="message-content" rows="10" name="message-content" class="form-control"></textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script-bottom')
    {!! \App\Helpers\JS::define('messageVariables', \App\Enums\Ticket\MessageVariable::getTinyMceVariables()) !!}
    <script src="{{ asset('build/tinymce/tinymce.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/tinymce.js') }}"></script>
@endsection
