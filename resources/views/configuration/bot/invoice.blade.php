@extends('configuration.bot.home', ["page_title"=> __('app.bot.invoice.invoice') ])

@section('bot_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    @include('form_components.switch', [
                        'translation' => __('app.bot.active'),
                        'name' => 'active',
                        'value' => old('active', setting('bot.invoice.active')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.invoice.found_answer'),
                        'name' => 'found_answer_id',
                        'value' => old('found_answer_id', setting('bot.invoice.found_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.invoice.not_shipped_answer'),
                        'name' => 'not_shipped_answer_id',
                        'value' => old('not_shipped_answer_id', setting('bot.invoice.not_shipped_answer_id')),
                    ])

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
