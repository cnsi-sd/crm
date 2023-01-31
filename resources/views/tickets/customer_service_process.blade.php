@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1) . ' #' . $ticket->id])

@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{ route('ticket', ['ticket' => $ticket->id]) }}">{{ __('app.order_info') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">{{ __('app.customer_service_process') }}</a>
                </li>
            </ul>
            <div class="row">
                <div class="col-12">
                    <iframe src="{{ env('PRESTASHOP_URL') }}procedure-sav?mp_order={{$ticket->order->channel_order_number}}&amp;mp_name={{$ticket->channel->name}}&amp;id_ticket={{$ticket->id}}&amp;admintoken={{ env('PRESTASHOP_CUSTOMER_SERVICE_TOKEN') }}" allowfullscreen="" width="100%" height="1000" frameborder="0"></iframe>
                </div>
            </div>
    </div>
@endsection

