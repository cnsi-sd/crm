@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1) . ' #' . $ticket['id']])

@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <form method="post" action="{{ route('ticket_thread', ['ticket' => $ticket['id'], 'thread' => $activeThread['id']]) }}">
        @csrf
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">{{ trans_choice('app.ticket.ticket', 1) }} #{{ $ticket['id'] }}</div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.state') }}</label></div>
                                    <div class="col">
                                        <select required name="ticket-state" class="form-select">
                                                <option value="">---</option>
                                            @foreach($ticketStateEnum as $ticketState)
                                                <option value="{{ $ticketState }}">{{ \App\Enums\Ticket\TicketStateEnum::getMessage($ticketState)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.priority') }}</label></div>
                                    <div class="col">
                                        <select name="ticket-priority" class="form-select">
                                            @foreach($ticketPriorityEnum as $ticketPriority)
                                                <option value="{{ $ticketPriority }}" @if($ticket['priority'] === $ticketPriority) selected @endif>{{ $ticketPriority }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.owner') }}</label></div>
                                    <div class="col">
                                        <select name="ticket-user_id" class="form-select">
                                            @foreach ($users as $user)
                                                <option value="{{ $user['id'] }}" @if($ticket['user_id'] === $user['id']) selected @endif>{{ $user['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.deadline') }}</label></div>
                                    <div class="col"><input name="ticket-deadline" class="form-control" type="date" value="{{ date('Y-m-d', strtotime($ticket['deadline'])) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.mapping') }}</div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.channel') }}</label></div>
                                    <div class="col">
                                            @foreach ($channels as $channel)
                                                <label>@if($ticket['channel_id'] === $channel['id']) {{ $channel['name'] }} @endif</label>
                                            @endforeach
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.order') }}</label></div>
                                    <div class="col"><label>{{ $order['channel_order_number'] }}</label></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.base_information') }}</div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.customer_mail') }}</label></div>
                                    <div class="col"><input name="ticket-customer_email" class="form-control" type="text" value="{{ $ticket['direct_customer_email'] }}"/></div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.delivery_date') }}</label></div>
                                    <div class="col"><input name="ticket-delivery_date" class="form-control" type="date" value="{{ date('Y-m-d', strtotime($ticket['delivery_date'])) }}"/></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.admin_thread') }}</div>
                        <div class="card-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.created_at') }}</label></div>
                                    <div class="col"><label>{{ date('d/m/Y', strtotime($ticket['created_at'])) }} ({{round(abs(time() - strtotime($ticket['created_at']))/60/60/24)}}j)</label></div>
                                </div>
                                <div class="row">
                                    <div class="col"><label>{{ __('app.ticket.customer_issue') }}</label></div>
                                    <div class="col"><input name="ticket-thread-customer_issue" class="form-control" type="text" value="{{$activeThread['customer_issue']}}"/></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.private_comments') }}</div>
                        <div class="card-body">
                            <textarea name="ticket-thread-comments-content" class="form-control"></textarea>
                            <div class="controls text-end">
                                <div class="row">
                                    <div class="col-5">
                                        <select name="ticket-thread-comments-type" class="form-select">
                                            @foreach($commentTypeEnum as $commentType)
                                                <option value="{{ $commentType }}">{{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($commentType)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-outline-primary">
                                            {{ __('app.send_comment') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="container thread-comments">
                                @foreach($comments as $comment)
                                    <div class="card">
                                        <div class="card-header text-start" data-bs-toggle="collapse" data-bs-target="#collapse-comment-{{$comment['id']}}" aria-expanded="false" aria-controls="collapse-comment-{{$comment['id']}}">
                                            @foreach($users as $user)
                                                @if($comment['user_id'] === $user['id'])
                                                    {{ $user['name'] }}
                                                @endif
                                            @endforeach
                                            - {{ \Carbon\Carbon::parse($comment['created_at'])->translatedFormat('d/m/Y H:i') }}
                                        </div>
                                        <div class="collapse @if($comment['displayed'] === 1) show @endif()" id="collapse-comment-{{$comment['id']}}">
                                            <div class="card-body {{$comment['type']}}">
                                                <div class="container text-start">
                                                    {!! nl2br($comment['content']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">{{ __('app.order_info') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">{{ __('app.product_return') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#">{{ __('app.customer_service_process') }}</a>
                        </li>
                    </ul>
                    <div class="controls text-end">
                        <button type="submit" class="btn btn-outline-primary">
                            {{ "💾 " . __('app.save') }}
                        </button>
                    </div>
                    <ul class="nav nav-tabs" id="threadsTabs" role="tablist">
                        @foreach($threads as $thread)
                        <li class="nav-item position-relative">
                            @if($unreadMessagesByTicket[$thread['id']] > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $unreadMessagesByTicket[$thread['id']] }}</span>
                            @endif
                            <a class="nav-link @if($thread['id'] === $activeThread['id']) active @endif" aria-current="page" href="{{ route('ticket_thread', ['ticket' => $ticket['id'], 'thread' => $thread['id']]) }}">
                                {{$thread['name']}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="threadsTabsContent">
                        <div class="tab-pane fade show active" role="tabpanel" tabindex="0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container">
                                        <textarea name="ticket-thread-messages-content" class="form-control"></textarea>
                                        <div class="controls text-end">
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
                            </div>
                            @foreach($messages as $message)
                                <div class="card">
                                    <div class="card-header text-start @if($message['author_type'] === "admin") collapsed @endif()" data-bs-toggle="collapse" data-bs-target="#collapse-message-{{$message['id']}}" aria-expanded="false" aria-controls="collapse-message-{{$message['id']}}">
                                        @if($message['author_type'] === 'customer') {{ __('app.customer') }}
                                        @elseif($message['author_type'] === 'operator') {{ __('app.operator') }}
                                        @elseif($message['author_type'] === 'admin')
                                            @foreach($users as $user)
                                                @if($message['user_id'] === $user['id'])
                                                    {{ $user['name'] }}
                                                @endif
                                            @endforeach
                                        @endif
                                        - {{ \Carbon\Carbon::parse($message['created_at'])->translatedFormat('d/m/Y H:i') }}
                                    </div>
                                    <div class="collapse @if($message['author_type'] !== "admin") show @endif()" id="collapse-message-{{$message['id']}}">
                                        <div class="card-body {{$message['author_type']}}">
                                            <div class="container text-start">
                                                {!! nl2br($message['content']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
