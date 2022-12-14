@extends('layouts.horizontal', ["page_title"=> trans_choice('app.ticket.ticket', 1) . ' #' . $ticket['id']])

@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <div class="container-fluid">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">{{ trans_choice('app.ticket.ticket', 1) }} #{{ $ticket['id'] }}</div>
                        <div class="card-body">
                            <div class="container text-center">
                                <form>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.state') }}</label></div>
                                        <div class="col">
                                            <select class="form-select">
                                                @foreach($ticketStateEnum as $ticketState)
                                                    <option value="{{ $ticketState }}" @if($ticket['state'] === $ticketState) selected @endif>{{ \App\Enums\Ticket\TicketStateEnum::getMessage($ticketState)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.priority') }}</label></div>
                                        <div class="col">
                                            <select class="form-select">
                                                @foreach($ticketPriorityEnum as $ticketPriority)
                                                    <option value="{{ $ticketPriority }}" @if($ticket['priority'] === $ticketPriority) selected @endif>{{ $ticketPriority }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.owner') }}</label></div>
                                        <div class="col">
                                            <select class="form-select">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user['id'] }}" @if($ticket['user_id'] === $user['id']) selected @endif>{{ $user['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.deadline') }}</label></div>
                                        <div class="col"><input class="form-control" type="date" value="{{ date('Y-m-d', strtotime($ticket['deadline'])) }}"></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.mapping') }}</div>
                        <div class="card-body">
                            <div class="container text-center">
                                <form>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.channel') }}</label></div>
                                        <div class="col">
                                            <select class="form-select">
                                                @foreach ($channels as $channel)
                                                    <option value="{{$channel['id']}}" @if($ticket['channel_id'] === $channel['id']) selected @endif> {{ $channel['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.order') }}</label></div>
                                        <div class="col"><input class="form-control" type="text" value="{{ $ticket['order_id'] }}"/></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.base_information') }}</div>
                        <div class="card-body">
                            <div class="container text-center">
                                <form>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.customer_mail') }}</label></div>
                                        <div class="col"><input class="form-control" type="text" value="{{ $ticket['direct_customer_email'] }}"/></div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.delivery_date') }}</label></div>
                                        <div class="col"><input class="form-control" type="date" value="{{ date('Y-m-d', strtotime($ticket['delivery_date'])) }}"/></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.admin_thread') }}</div>
                        <div class="card-body">
                            <div class="container text-center">
                                <form>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.created_at') }}</label></div>
                                        <div class="col"><label>{{ date('d/m/Y', strtotime($ticket['created_at'])) }} ({{round(abs(time() - strtotime($ticket['created_at']))/60/60/24)}}j)</label></div>
                                    </div>
                                    <div class="row">
                                        <div class="col"><label>{{ __('app.ticket.customer_issue') }}</label></div>
                                        <div class="col"><input class="form-control" type="text" value="{{$activeThread['customer_issue']}}"/></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">{{ __('app.ticket.private_comments') }}</div>
                        <div class="card-body">
                            <div class="container text-center thread-comments">
                                @foreach($comments as $comment)
                                    <div class="card">
                                        <div class="card-header text-start">
                                            @foreach($users as $user)
                                                @if($comment['user_id'] === $user['id'])
                                                    {{ $user['name'] . ',' }}
                                                @endif
                                            @endforeach
                                            le {{ \Carbon\Carbon::parse($comment['created_at'])->translatedFormat('d F Y H:i') }}
                                        </div>
                                        <div class="card-body {{$comment['type']}}">
                                            <div class="container text-start">
                                                {!! $comment['content'] !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-9">
                    <ul class="nav nav-tabs" id="threadsTabs" role="tablist">
                        @foreach($threads as $thread)
                        <li class="nav-item">
                            <a class="nav-link @if($thread['id'] === $activeThread['id']) active @endif" aria-current="page" href="{{ route('ticket_thread', ['ticket' => $ticket['id'], 'thread' => $thread['id']]) }}">{{$thread['name']}}
                                @foreach($messages as $message)
                                    @if($message['thread_id'] === $thread['id'])
                                        @if($loop->first)
                                            @if($message['author_type'] !== "admin") (1) @endif
                                        @endif
                                    @endif
                                @endforeach
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="threadsTabsContent">
                        <div class="tab-pane fade show active" role="tabpanel" tabindex="0">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container text-center">
                                        <textarea class="form-control" aria-label="With textarea"></textarea>
                                        <label>{{ trans_choice('app.attachment',2) }}</label> <input type="file"/>
                                        <label>{{ __('app.ticket.default_replies') }}</label>
                                    </div>
                                </div>
                            </div>
                            @foreach($messages as $message)
                                <div class="card">
                                    <div class="card-header text-start">
                                        @if($message['author_type'] === 'customer') {{ __('app.customer') }}
                                        @elseif($message['author_type'] === 'operator') {{ __('app.operator') }}
                                        @elseif($message['author_type'] === 'admin')
                                            @foreach($users as $user)
                                                @if($message['user_id'] === $user['id'])
                                                    {{ $user['name'] }}
                                                @endif
                                            @endforeach
                                        @endif
                                        le {{ \Carbon\Carbon::parse($message['created_at'])->translatedFormat('d F Y H:i') }}
                                    </div>
                                    <div class="card-body {{$message['author_type']}}">
                                        <div class="container text-start">
                                            {!! $message['content'] !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
