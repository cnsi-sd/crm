@extends('configuration.bot.home', ["page_title"=> __('app.bot.shipping_information.shipping_information') ])

@section('bot_content')
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                {{ __('app.configuration.configuration') }}
            </div>

            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="active" id="active" @checked(setting('autoReplyActivate'))>
                            <label class="form-check-label" for="active">{{ __('app.bot.active') }}</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fulfillment_answer_id" class="form-label">
                            {{ __('app.bot.shipping_information.fulfillment_answer') }}
                        </label>
                        <select name="fulfillment_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('bot.shipping_information.fulfillment_answer_id') == $reply->id)>
                                    {{ $reply->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="in_preparation_answer_id" class="form-label">
                            {{ __('app.bot.shipping_information.in_preparation_answer') }}
                        </label>
                        <select name="in_preparation_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('bot.shipping_information.in_preparation_answer_id') == $reply->id)>
                                    {{ $reply->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="in_preparation_with_delay_answer_id" class="form-label">
                            {{ __('app.bot.shipping_information.in_preparation_with_delay_answer') }}
                        </label>
                        <select name="in_preparation_with_delay_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('bot.shipping_information.in_preparation_with_delay_answer_id') == $reply->id)>
                                    {{ $reply->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="vir_shipped_answer_id" class="form-label">
                            {{ __('app.bot.shipping_information.vir_shipped_answer') }}
                        </label>
                        <select name="vir_shipped_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('bot.shipping_information.vir_shipped_answer_id') == $reply->id)>
                                    {{ $reply->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="default_shipped_answer_id" class="form-label">
                            {{ __('app.bot.shipping_information.default_shipped_answer') }}
                        </label>
                        <select name="default_shipped_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('bot.shipping_information.default_shipped_answer_id') == $reply->id)>
                                    {{ $reply->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
