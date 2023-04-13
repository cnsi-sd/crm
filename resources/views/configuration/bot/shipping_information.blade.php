@extends('configuration.bot.home', ["page_title"=> __('app.bot.shipping_information.shipping_information') ])

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
                        'value' => old('active', setting('bot.shipping_information.active')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.shipping_information.fulfillment_answer'),
                        'name' => 'fulfillment_answer_id',
                        'value' => old('fulfillment_answer_id', setting('bot.shipping_information.fulfillment_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.shipping_information.in_preparation_answer'),
                        'name' => 'in_preparation_answer_id',
                        'value' => old('in_preparation_answer_id', setting('bot.shipping_information.in_preparation_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.shipping_information.in_preparation_with_delay_answer'),
                        'name' => 'in_preparation_with_delay_answer_id',
                        'value' => old('in_preparation_with_delay_answer_id', setting('bot.shipping_information.in_preparation_with_delay_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.shipping_information.vir_shipped_answer'),
                        'name' => 'vir_shipped_answer_id',
                        'value' => old('vir_shipped_answer_id', setting('bot.shipping_information.vir_shipped_answer_id')),
                    ])

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.shipping_information.default_shipped_answer'),
                        'name' => 'default_shipped_answer_id',
                        'value' => old('default_shipped_answer_id', setting('bot.shipping_information.default_shipped_answer_id')),
                    ])

                    @include('form_components.tag_select', [
                        'translation' => __('app.bot.shipping_information.late_order_tag'),
                        'name' => 'late_order_tag_id',
                        'value' => old('late_order_tag_id', setting('bot.shipping_information.late_order_tag_id')),
                    ])

                    @include('form_components.submit')
                </form>
            </div>
        </div>
    </div>
@endsection
