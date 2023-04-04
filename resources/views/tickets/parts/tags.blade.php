<div class="card" id="tags-container">
    <div class="card-header">
        <span>{{ trans_choice('app.tags.tags', 2) }}</span>
        <a id="addTagLine" href="#" class="float-end"
           data-ticket_id="{{$ticket->id}}"
           data-url_add_tag="{{route('addTagList')}}"
           data-channel_id="{{$ticket->channel_id}}"
        >
            {{ __('app.tags.addTagList') }}
        </a>
    </div>
    <div class="card-body" id="card-body-tag">
        @php
            $autorizedTag = $ticket->channel->getAuthorizedTags();
        @endphp
        @foreach($ticket->tagLists as $taglist)
            <div id="list-{{$taglist->id}}">
                <select form="saveTicket" name="ticket-revival" class="form-select no-sort tags"
                        data-ticket_id="{{$ticket->id}}"
                        data-taglist_id="{{$taglist->id}}">
                    <option value="">{{ __('app.tags.select_tag') }}</option>

                    @foreach ($autorizedTag as $tag)
                        <option value="{{ $tag->id }}">
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <div id="view-{{$taglist->id}}" class="mt-3 mb-2">
                    @foreach($taglist->tags as $tag)
                        <span class="tags-style" style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }};">
                            {{ $tag->name }}
                            <button
                                class="btn delete-tag"
                                data-tag_id="{{$tag->id}}"
                                data-taglist_id="{{$taglist->id}}"
                                data-ticket_id="{{$ticket->id}}"
                                style="color: {{ $tag->text_color }};">
                                X
                            </button>
                            </span>
                    @endforeach
                </div>
                <hr/>
            </div>
        @endforeach
    </div>
</div>
