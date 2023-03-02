<div class="card">
    <div class="card-header">{{ __('app.ticket.private_comments') }}</div>
    <div class="card-body">
        <textarea name="ticket-thread-comments-content" class="form-control"></textarea>
        <div class="controls text-end">
            <div class="row">
                <div class="col-5">
                    <select name="ticket-thread-comments-type" class="form-select">
                        @foreach(\App\Enums\Ticket\TicketCommentTypeEnum::getTranslatedList() as $key => $message)
                            <option value="{{ $key }}">{{ $message }}</option>
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
            @foreach($thread->comments as $comment)
                <div class="card">
                    <div class="card-header text-start" data-bs-toggle="collapse" data-comment-id="{{$comment->id}}" data-bs-target="#collapse-comment-{{$comment->id}}" aria-expanded="false" aria-controls="collapse-comment-{{$comment->id}}">
                        <div class="row">
                            <div class="col-9">
                                {{ $comment->user->getShortName() }}
                                - {{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat('d/m/Y H:i') }}
                            </div>
                            <div class="col-3 text-end">
                                <span title="{{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}" class="badge {{$comment->type}}">{{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="collapse @if($comment->displayed === 1) show @endif()" id="collapse-comment-{{$comment->id}}">
                        <div class="card-body {{$comment->type}}">
                            <div class="container text-start">
                                {!! nl2br($comment->content) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
