@extends('layouts.horizontal', ["page_title"=> $page_title ?? __('app.bot.bot') ])

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <ul class="list-group rounded">
                    <a href="{{ route('bot_invoice') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'bot_invoice'])>
                        {{ __('app.bot.invoice.invoice') }}
                    </a>

                    <a href="{{ route('bot_shipping_information') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'bot_shipping_information'])>
                        {{ __('app.bot.shipping_information.shipping_information') }}
                    </a>

                    <a href="{{ route('bot_acknowledgement') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'bot_acknowledgement'])>
                        {{ __('app.bot.acknowledgement.acknowledgement') }}
                    </a>

                    <a href="{{ route('bot_premium_delivery') }}" @class(['list-group-item', 'active' => Route::currentRouteName() === 'bot_premium_delivery'])>
                        {{ __('app.bot.premium_delivery.premium_delivery') }}
                    </a>
                </ul>
            </div>
            @yield('bot_content')
        </div>
    </div>
@endsection
