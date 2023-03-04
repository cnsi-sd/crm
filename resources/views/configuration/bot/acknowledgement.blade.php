@extends('configuration.bot.home', ["page_title"=> __('app.bot.acknowledgement.acknowledgement') ])

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
                        <label for="inputEmail3" class="form-label">
                            {{ __('app.bot.acknowledgement.default_answer') }}
                        </label>
                        <select name="default_answer_id" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $reply)
                                <option value="{{ $reply->id }}" @selected(setting('autoReply') == $reply->id)>
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
