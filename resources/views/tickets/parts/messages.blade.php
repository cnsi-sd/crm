@foreach($thread->messages as $message)
    <div class="card">
        <div class="card-header text-start @if($message->author_type === \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN) collapsed @endif()" data-bs-toggle="collapse" data-bs-target="#collapse-message-{{$message->id}}" aria-expanded="false" aria-controls="collapse-message-{{$message->id}}">
            @if($message->author_type === \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN)
                {{ $message->user->name }}
            @else
                {{ \App\Enums\Ticket\TicketMessageAuthorTypeEnum::getMessage($message->author_type) }}
            @endif
            - {{ $message->created_at->format('d/m/y H:i') }}
        </div>
        <div class="collapse @if($message->author_type !== \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN) show @endif()" id="collapse-message-{{$message->id}}">
            <div class="card-body {{$message->author_type}}">
                {!! nl2br($message->content) !!}
            </div>
        </div>
    </div>
@endforeach
