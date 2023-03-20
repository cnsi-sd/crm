@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1) . ' #' . $ticket->id])

@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <form id="saveTicket" method="post" enctype="multipart/form-data" action="{{ route('ticket_thread', ['ticket' => $ticket->id, 'thread' => $thread->id]) }}">
            @csrf
        </form>
            <div class="row">
                <div class="col-4">
                    <div class="ticket-divider h4 text-center">
                        {{ __('app.ticket.admin_ticket') }} #{{ $ticket->id }}
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.state') }} <span class="required_field">*</span></label></div>
                                <div class="col">
                                    <select form="saveTicket" required name="ticket-state" class="form-select no-select2">
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
                                    <select form="saveTicket" name="ticket-priority" class="form-select no-select2">
                                        @foreach(\App\Enums\Ticket\TicketPriorityEnum::getList() as $ticketPriority)
                                            <option value="{{ $ticketPriority }}" @selected($ticket->priority === $ticketPriority)>{{ $ticketPriority }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.owner') }} <span class="required_field">*</span></label></div>
                                <div class="col">
                                    <select form="saveTicket" name="ticket-user_id" class="form-select">
                                        @foreach (\App\Models\User\User::all() as $user)
                                            <option value="{{ $user->id }}" @selected($ticket->user_id === $user->id)>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col"><label>{{ __('app.ticket.deadline') }} <span class="required_field">*</span></label></div>
                                <div class="col"><input form="saveTicket" name="ticket-deadline" class="form-control" type="date" value="{{ $ticket->deadline->format("Y-m-d") }}"></div>
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
                                <div class="col-xl-4"><label>{{ __('app.ticket.created_at') }}</label></div>
                                <div class="col-xl-8 text-xl-end">{{ $ticket->created_at->format('d/m/y') }} ({{$ticket->getOpenedDays()}}j)</div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-xl-4"><label>{{ __('app.ticket.customer_issue') }}</label></div>
                                <div class="col-xl-8">
                                    <input form="saveTicket" name="ticket-customer_issue" class="form-control" type="text" value="{{$ticket->customer_issue}}"/>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-xl-4"><label>{{ __('app.ticket.customer_mail') }}</label></div>
                                <div class="col-xl-8">
                                    <input form="saveTicket" name="ticket-customer_email" class="form-control" type="text" value="{{ $ticket->direct_customer_email }}"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4"><label>{{ __('app.ticket.delivery_date') }}</label></div>
                                <div class="col-xl-8">
                                    <input form="saveTicket" name="ticket-delivery_date" class="form-control" type="date" value="{{ $ticket->delivery_date?->format("Y-m-d") }}"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('tickets.parts.tags')
                    @include('tickets.parts.private_comments')
                    {!! $documents_table !!}

                    <div class="ticket-divider h4 text-center">
                        {{ __('app.ticket.admin_thread') }} #{{ $thread->id }}
                    </div>

                    @include('tickets.parts.revival')
                </div>
                <div class="col-8">
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
                            <div class="p-4 d-flex justify-content-center" id="order-info-spinner">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="customer-service-process" role="tabpanel" aria-labelledby="customer-service-process-tab">
                            <iframe src="{{ \App\Helpers\Prestashop\SavProcessGateway::getUrl($ticket) }}" allowfullscreen="" loading="lazy" width="100%" height="1000" frameborder="0"></iframe>
                        </div>
                    </div>
                    <div class="mt-2 text-end">
                        <button form="saveTicket" type="submit" class="btn btn-outline-primary">
                            {{ "💾 " . __('app.save') }}
                        </button>
                    </div>
                    <ul class="nav nav-tabs" id="threadsTabs" role="tablist">
                        @foreach($ticket->threads as $ticketThread)
                        <li class="nav-item position-relative">
                            <span @class([
                                    'position-absolute top-0 start-100 translate-middle badge rounded-pill',
                                    'bg-danger' => $ticketThread->getUnreadMessages() > 0,
                                    'bg-success' => $ticketThread->getUnreadMessages() === 0
                            ])>
                                {{ $ticketThread->getUnreadMessages() }}
                            </span>
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
                                    <textarea form="saveTicket" id="message_to_customer" name="ticket-thread-messages-content"></textarea>
                                    <div class="mt-2 text-end">
                                        <button form="saveTicket" type="submit" class="btn btn-outline-primary">
                                            {{ __('app.send_message') }}
                                        </button>
                                    </div>
                                    <div class="attachments">
                                        <label>{{ trans_choice('app.attachment',2) }}</label>
                                        <button form="saveTicket" type="button" id="addAttachment" class="btn btn-success flex-shrink-1">+</button>
                                        <div class="row attachment_bloc">
                                            <div class="col">
                                                <select form="saveTicket" name="attachment_type_1" class="form-select no-select2 attachment_type">
                                                    <option value="">---</option>
                                                    @include('tickets.parts.messages_document_types')
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input form="saveTicket" name="attachment_file_1" type="file" class="attachment_file"/>
                                            </div>
                                        </div>
                                    </div>
                                    <label>{{ __('app.ticket.default_replies') }}</label>
                                </div>
                            </div>
                            @include('tickets.parts.messages')
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('script-bottom')
    {!! \App\Helpers\JS::define('url_show_tags', route('ajaxShowTags')) !!}
    {!! \App\Helpers\JS::define('url_add_tag_list', route('addTagList')) !!}
    {!! \App\Helpers\JS::define('url_delete_tagList', route('deleteTagList')) !!}
    {!! \App\Helpers\JS::define('url_add_tag_on_ticket', route('saveTagOnticketThread')) !!}
    {!! \App\Helpers\JS::define('url_delete_tag_on_ticket', route('deleteTagListOnThread')) !!}
    {!! \App\Helpers\JS::define('url_click_and_call', route('click_and_call')) !!}

    {!! \App\Helpers\JS::define('messageVariables', \App\Enums\Ticket\MessageVariable::getTinyMceVariables()) !!}
    <script src="{{ asset('build/tinymce/tinymce.js') }}"></script>
    <script src="{{ Vite::asset('resources/js/tinymce.js') }}"></script>

    <script src="{{ Vite::asset('resources/js/tickets/ticket.js') }}"></script>
@endsection
