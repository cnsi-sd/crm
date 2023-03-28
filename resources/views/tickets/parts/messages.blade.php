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
            </div>
            @if($message->documents()->get()->count() > 0)
                <hr>
                <div class="container text-start">
                    <label>Pièces jointes :</label>
                    <table>
                        <tbody>
                        @foreach($message->documents()->get() as $document)
                            <tr>
                                <td>{{$document->name}}</td>
                                <td>
                                    <a
                                        href="{{ route('show_document', [$document->documentable, $document]) }}"
                                        class="btn btn-secondary btn-sm"
                                        title="{{ __('attachments::attachments.display') }}"
                                        target="_blank"
                                    >
                                        <i class="uil-eye"></i>
                                    </a>
                                    <a
                                        href="{{ route('download_document', [$document->documentable, $document]) }}"
                                        class="btn btn-secondary btn-sm"
                                        title="{{ __('attachments::attachments.download') }}"
                                        target="_blank"
                                    >
                                        <i class="uil-download-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endforeach
