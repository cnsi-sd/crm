@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.default_answer_offer_questions') ])

@section('misc_content')
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                {{ __('app.config.config') }}
            </div>
            <div class="card-body">
                <form class="form-horizontal" method="post">
                    @csrf
                    <div class=" row col-3 mb-3">
                        <label for="defaultAnswer">
                            {{ trans_choice('app.default_answer.default_answer', 1)
                              . ' ' . \App\Enums\Channel\ChannelEnum::CDISCOUNT_FR
                            }}
                        </label>
                        <select name="default_answer_offer_questions" class="form-control form-select" required>
                            @foreach(\App\Models\Channel\DefaultAnswer::all() as $defaultAnswer)
                                <option value="{{ $defaultAnswer->id }}">
                                    {{ $defaultAnswer->name }}
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
