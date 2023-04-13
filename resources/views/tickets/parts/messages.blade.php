@foreach($thread->messages as $message)
    <div class="card">
        <div
            @class(['card-header justify-content-between align-items-center', 'collapsed' => $message->author_type === \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN])
            data-bs-toggle="collapse"
            data-bs-target="#collapse-message-{{$message->id}}"
            aria-expanded="false"
            aria-controls="collapse-message-{{$message->id}}"
        >
            @if($message->author_type === \App\Enums\Ticket\TicketMessageAuthorTypeEnum::ADMIN && $message->user)
                <span class="uil-user"> {{ $message->user->__toString() }}</span>
            @else
                <span class="uil-user"> {{ \App\Enums\Ticket\TicketMessageAuthorTypeEnum::getMessage($message->author_type) }}</span>
            @endif
            <span class="ms-2 me-2"> - </span>
            @if($message->default_answer_id)<span class="uil-edit"> {{ $message->default_answer->name }}</span><span class="ms-2 me-2"> - </span>@endif
            <span class="uil-calender"> {{ $message->created_at->format('d/m/y H:i') }}</span>
        </div>
        <div class="collapse @if($message->isExternal()) show @endif()" id="collapse-message-{{$message->id}}">
            <div class="card-body {{$message->author_type}}">
                {!! nl2br($message->content) !!}

                @if($message->documents()->get()->count() > 0)
                    <hr>
                    <label>{{ trans_choice('attachments::attachments.document.document', 2) }}</label>
                    @foreach($message->documents()->get() as $document)
                        <a href="{{ route('show_document', [$document->documentable, $document]) }}" target="_blank" class="d-block">
                            {{$document->name}}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endforeach
