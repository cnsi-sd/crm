
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
        @foreach($ticket->tagLists as $taglist)
            <div id="list-{{$taglist->id}}">
                @php
                $query = \App\Models\Tags\Tag::query()
                    ->select('tags.*')
                    ->join('channel_tags', 'tags.id', 'channel_tags.tag_id')
                    ->join('channels', 'channels.id', 'channel_tags.channel_id')
                    ->where('channels.id',$ticket->channel_id)
                    ->get();
                @endphp
                <select form="saveTicket" name="ticket-revival" class="form-select no-sort tags"
                        data-ticket_id="{{$ticket->id}}"
                        data-taglist_id="{{$taglist->id}}">
                    <option value="">{{ __('app.tags.select_tag') }}</option>

                    @foreach ($query as $optionTag)
                        <option value="{{ $optionTag->id }}">
                            {{ $optionTag->name }}
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
