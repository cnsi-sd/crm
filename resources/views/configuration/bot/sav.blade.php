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
                        'value' => old('active', setting('bot.sav.active')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.sav.pem'),
                        'name' => 'pem_answer_id',
                        'value' => old('pem_answer_id', setting('bot.sav.pem_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.sav.gem'),
                        'name' => 'gem_answer_id',
                        'value' => old('gem_answer_id', setting('bot.sav.gem_answer_id')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
    </div>
@endsection
