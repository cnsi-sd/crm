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

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="active" id="active" @checked(setting('bot.shipping_information.active'))>
                            <label class="form-check-label" for="active">{{ __('app.bot.active') }}</label>
                        </div>
                    </div>

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

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
