<div
    @class(['title d-flex justify-content-between align-items-center mt-3 thread-comments', 'collapsed' => !$comment->displayed])
    data-bs-toggle="collapse"
    data-toggle-comment-route="{{ route("toggle_comment", ['comment' => $comment]) }}"
    data-bs-target="#collapse-comment-{{$comment->id}}"
    aria-expanded="false"
    aria-controls="collapse-comment-{{$comment->id}}"
>
    @if (!is_null($comment->user))
        <span class="w-25"> {{ $comment->user->__toString() }}</span>
    @else
        <span class="w-25"> {{ \App\Enums\Ticket\TicketMessageAuthorTypeEnum::getMessage(\App\Enums\Ticket\TicketMessageAuthorTypeEnum::SYSTEM) }}</span>
    @endif
    <span>{{ $comment->created_at->format('d/m/y H:i') }}</span>

    <span title="{{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}" class="badge w-25 {{$comment->type}}">
        {{ \App\Enums\Ticket\TicketCommentTypeEnum::getMessage($comment->type)}}
    </span>
</div>

<div @class(['collapse', 'show' => $comment->displayed]) id="collapse-comment-{{$comment->id}}">
    {!! nl2br($comment->content) !!}
</div>
