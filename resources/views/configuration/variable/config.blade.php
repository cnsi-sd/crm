@extends('layouts.horizontal', ["page_title"=> trans_choice('app.variable.variable', 2) ])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        {{ __('app.config.config') }}
                    </div>

                    <div class="card-body">
                        <form class="form-horizontal" method="post">
                            @csrf

                            @foreach(\App\Enums\Ticket\MessageVariable::cases() as $variable)
                                @if($variable->isConfigurable())
                                    <div class="mb-3">
                                        <label for="{{ $variable->getSettingKey() }}" class="form-label">
                                            {{ $variable->value }}
                                        </label>
                                        <input class="form-control" id="{{ $variable->getSettingKey() }}"
                                               name="{{ $variable->getSettingKey() }}" value="{{ $variable->getSettingValue() }}">
                                    </div>
                                @endif
                            @endforeach

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" name="save">{{ __('app.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
