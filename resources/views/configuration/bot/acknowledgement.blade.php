@extends('configuration.bot.home', ["page_title"=> __('app.bot.acknowledgement.acknowledgement') ])

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
                        'value' => old('active', setting('bot.acknowledgment.active')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.acknowledgement.answer'),
                        'name' => 'answer_id',
                        'value' => old('answer_id', setting('bot.acknowledgment.answer_id')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
    </div>
@endsection
