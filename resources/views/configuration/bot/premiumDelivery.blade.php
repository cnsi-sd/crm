@extends('configuration.bot.home', ["page_title"=> __('app.bot.premium_delivery.premium_delivery') ])

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
                        'value' => old('active', setting('bot.premium_delivery.active'))
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.premium_delivery.premium_reply'),
                        'name' => 'premium_reply',
                        'value' => old('premium_reply', setting('bot.premium_delivery.premium_reply')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>

        </div>
    </div>
@endsection
