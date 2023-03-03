@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1) . ' #' . $ticket->id])

@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <form method="post" action="{{ route('ticket_thread', ['ticket' => $ticket->id, 'thread' => $thread->id]) }}">
            @csrf
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">{{ trans_choice('app.ticket.ticket', 1) }} #{{ $ticket->id }}</div>
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.state') }} <span class="required_field">*</span></label></div>
                                <div class="col">
                                    <select required name="ticket-state" class="form-select no-select2">
                                            <option value="">---</option>
                                        @foreach(\App\Enums\Ticket\TicketStateEnum::getList() as $ticketState)
                                            <option value="{{ $ticketState }}">{{ \App\Enums\Ticket\TicketStateEnum::getMessage($ticketState)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.priority') }} <span class="required_field">*</span></label></div>
                                <div class="col">
                                    <select name="ticket-priority" class="form-select no-select2">
                                        @foreach(\App\Enums\Ticket\TicketPriorityEnum::getList() as $ticketPriority)
                                            <option value="{{ $ticketPriority }}" @selected($ticket->priority === $ticketPriority)>{{ $ticketPriority }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.owner') }} <span class="required_field">*</span></label></div>
                                <div class="col">
                                    <select name="ticket-user_id" class="form-select">
                                        @foreach (\App\Models\User\User::all() as $user)
                                            <option value="{{ $user->id }}" @selected($ticket->user_id === $user->id)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.deadline') }} <span class="required_field">*</span></label></div>
                                <div class="col"><input name="ticket-deadline" class="form-control" type="date" value="{{ $ticket->deadline->format("Y-m-d") }}"></div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.channel') }}</label></div>
                                <div class="col">
                                    <label>{{ $ticket->channel->name }}</label>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.order') }}</label></div>
                                <div class="col"><label>{{ $ticket->order->channel_order_number }}</label></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.base_information') }}</div>
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.customer_mail') }}</label></div>
                                <div class="col"><input name="ticket-customer_email" class="form-control" type="text" value="{{ $ticket->direct_customer_email }}"/></div>
                            </div>
                            <div class="row">
                                <div class="col"><label>{{ __('app.ticket.delivery_date') }}</label></div>
                                <div class="col"><input name="ticket-delivery_date" class="form-control" type="date" value="@if($ticket->delivery_date){{ $ticket->delivery_date->format("Y-m-d") }}@endif"/></div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ trans_choice('app.revival.revival', 1) }}</div>
                        <div class="card-body">
                            <select name="ticket-revival" class="form-select no-sort">
                                <option value="">{{ __('app.revival.select_revival') }}</option>
                                @foreach ($thread->ticket->channel->revivals as $revival)
                                    <option value="{{ $revival->id }}" @if(!empty($thread->revival->id))
                                        @selected($revival->id == $thread->revival->id)
                                        @endif>
                                        {{ $revival->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($thread->revival)
                                @php($revival = $thread->revival)
                                <div class="row mt-2">
                                    <div class="col"><label>{{ __('app.revival.start_revival') }}</label></div>
                                    <div class="col">
                                        <input name="revival-delivery_date" class="form-control"
                                                type="date"
                                                value="{{ date('Y-m-d', strtotime($thread->revival_start_date)) }}"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if($thread->revival)
                            <div class="card-footer">
                                @php($revival = $thread->revival)
                                <div class="row">
                                    <div class="col">
                                        <label>
                                            {{ __('app.revival.frequency') }}
                                        </label>
                                    </div>
                                    <div class="col">
                                        {{ trans_choice('app.revival.frequency_details', $revival->frequency, ['freq' => $revival->frequency]) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label>
                                            {{ __('app.revival.MaxRevival') }}
                                        </label>
                                    </div>
                                    <div class="col">
                                        {{ $thread->revival_message_count }}/{{ $revival->max_revival }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label>
                                            {{ __('app.revival.nextReply') }}
                                        </label>
                                    </div>
                                    <div class="col">
                                        {{ $thread->getNextRevivalDate()->format('d/m/y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label>
                                            {{ __('app.revival.sendType') }}
                                        </label>
                                    </div>
                                    <div class="col">
                                        {{$thread->revival->send_type}}
                                    </div>
                                </div>
                                @if($revivalError = $thread->getThreadRevivalError(false))
                                    <div class="row mt-2">
                                        <div class="col">
                                            <div
                                                    class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show"
                                                    role="alert">
                                                {{ $revivalError }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.admin_thread') }}</div>
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.created_at') }}</label></div>
                                <div class="col"><label>{{ $ticket->created_at->format('d/m/y') }} ({{$ticket->getOpenedDays()}}j)</label></div>
                            </div>
                            <div class="row">
                                <div class="col"><label>{{ __('app.ticket.customer_issue') }}</label></div>
                                <div class="col"><input name="ticket-thread-customer_issue" class="form-control" type="text" value="{{$thread->customer_issue}}"/></div>
                            </div>
                        </div>
                    </div>
                    @include('tickets.parts.private_comments')
                </div>
                <div class="col-9">
                    <ul class="nav nav-tabs" id="ticketTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="hide-tab" data-bs-toggle="tab" data-bs-target="#hide" type="button" role="tab" aria-controls="hide" aria-selected="true"><i class="uil-home"></i></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="order-info-tab" data-bs-toggle="tab" data-bs-target="#order-info" data-get-external-infos-route="{{ route("get_external_infos", ['ticket' => $ticket->id]) }}" type="button" role="tab" aria-controls="order-info" aria-selected="false">{{ __('app.order_info') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="customer-service-process-tab" data-bs-toggle="tab" data-bs-target="#customer-service-process" type="button" role="tab" aria-controls="customer-service-process" aria-selected="false">{{ __('app.customer_service_process') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="ticketTabContent">
                        <div class="tab-pane fade show active" id="hide" role="tabpanel" aria-labelledby="hide-tab"></div>
                        <div class="tab-pane fade" id="order-info" role="tabpanel" aria-labelledby="order-info-tab">
                            @include('tickets.parts.external_order_info')
                        </div>
                        <div class="tab-pane fade" id="customer-service-process" role="tabpanel" aria-labelledby="customer-service-process-tab">
                            <iframe src="{{ env('PRESTASHOP_URL') }}procedure-sav?mp_order={{$ticket->order->channel_order_number}}&amp;mp_name={{$ticket->channel->ext_name}}&amp;id_ticket={{$ticket->id}}&amp;admintoken={{ env('PRESTASHOP_CUSTOMER_SERVICE_TOKEN') }}" allowfullscreen="" width="100%" height="1000" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="submit" class="btn btn-outline-primary">
                            {{ "💾 " . __('app.save') }}
                        </button>
                    </div>
                    <ul class="nav nav-tabs" id="threadsTabs" role="tablist">
                        @foreach($ticket->threads as $ticketThread)
                        <li class="nav-item position-relative">
                            @if($ticketThread->getUnreadMessages() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $ticketThread->getUnreadMessages() }}</span>
                            @endif
                            <a class="nav-link @if($ticketThread->id === $thread['id']) active @endif" aria-current="page" href="{{ route('ticket_thread', ['ticket' => $ticket->id, 'thread' => $ticketThread->id]) }}">
                                {{$ticketThread->name}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="threadsTabsContent">
                        <div class="tab-pane fade show active" role="tabpanel" tabindex="0">
                            <div class="card">
                                <div class="card-body">
                                    <textarea name="ticket-thread-messages-content" class="form-control"></textarea>
                                    <div class="mt-2 text-end">
                                        <button type="submit" class="btn btn-outline-primary">
                                            {{ __('app.send_message') }}
                                        </button>
                                    </div>
                                    <div class="attachments">
                                        <label>{{ trans_choice('app.attachment',2) }}</label> <input type="file"/>
                                    </div>
                                    <label>{{ __('app.ticket.default_replies') }}</label>
                                </div>
                            </div>
                            @include('tickets.parts.messages')
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script-bottom')
<script src="{{ Vite::asset('resources/js/tickets/ticket.js') }}"></script>
@endsection
