<div class="card">
    <div class="card-header">{{ __('app.ticket.private_comments') }}</div>
    <div class="card-body">
        <textarea id="ticket-comments-content" name="ticket-comments-content" class="form-control"></textarea>
        <div class="row mt-2">
            <div class="col">
                <select id="ticket-comment-type" name="ticket-comment-type" class="form-select no-select2">
                    @foreach(\App\Enums\Ticket\TicketCommentTypeEnum::getTranslatedList(false) as $key => $message)
                        <option value="{{ $key }}">{{ $message }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col d-grid">
                <button id="postCommentButton" data-post-comment-route="{{ route("post_comment", ['ticket' => $ticket->id]) }}" class="btn btn-outline-primary">
                    {{ __('app.send_comment') }}
                </button>
            </div>
        </div>
        <div class="ticket-comments mt-3 pb-1">
            @foreach($ticket->comments as $comment)
                @include('tickets.parts.private_comment')
            @endforeach
        </div>
    </div>
</div>
