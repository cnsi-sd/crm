<div class="card">
    <div class="card-header">{{ __('app.ticket.private_comments') }}</div>
    <div class="card-body">
        <textarea form="saveTicket" name="ticket-comments-content" class="form-control"></textarea>
        <div class="row mt-2">
            <div class="col">
                <select form="saveTicket" name="ticket-comment-type" class="form-select no-select2">
                    @foreach(\App\Enums\Ticket\TicketCommentTypeEnum::getTranslatedList(false) as $key => $message)
                        <option value="{{ $key }}">{{ $message }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col d-grid">
                <button form="saveTicket" type="submit" class="btn btn-outline-primary">
                    {{ __('app.send_comment') }}
                </button>
            </div>
        </div>
        <div class="ticket-comments mt-3 pb-1">
            @foreach($ticket->comments as $comment)
                <div
                    @class(['title d-flex justify-content-between align-items-center mt-3', 'collapsed' => !$comment->displayed])
                    data-bs-toggle="collapse"
                    data-toggle-comment-route="{{ route("toggle_comment", ['comment' => $comment->id]) }}"
                    data-bs-target="#collapse-comment-{{$comment->id}}"
                    aria-expanded="false"
                    aria-controls="collapse-comment-{{$comment->id}}"
                >
                    <span class="w-25">{{ $comment->user->__toString() }}</span>
                    <span>{{ $comment->created_at->format('d/m/y H:i') }}</span>

                    <span title="{{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}" class="badge w-25 {{$comment->type}}">
                        {{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}
                    </span>
                </div>

                <div @class(['collapse', 'show' => $comment->displayed]) id="collapse-comment-{{$comment->id}}">
                    {!! nl2br($comment->content) !!}
                </div>
            @endforeach
        </div>
    </div>
</div>
