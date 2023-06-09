@extends('configuration.misc.home', ["page_title"=> __('app.config.misc.variables') ])

@section('misc_content')
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

                    @include('form_components.submit')
                </form>
            </div>
        </div>
@endsection
