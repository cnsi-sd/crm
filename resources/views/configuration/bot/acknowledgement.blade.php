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

                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="active" id="active" @checked(setting('bot.acknowledgment.active'))>
                            <label class="form-check-label" for="active">{{ __('app.bot.active') }}</label>
                        </div>
                    </div>

                    @include('form_components.default_reply_select', [
                        'translation' => __('app.bot.acknowledgement.answer'),
                        'name' => 'answer_id',
                        'value' => old('answer_id', setting('bot.acknowledgment.answer_id')),
                    ])

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
