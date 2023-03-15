<div class="card">
    <div class="card-header d-flex">
        <p class="w-100">{{ trans_choice('app.tags.tags', 2) }}</p>
        <button form="saveTicket" type="button" id="add" class="btn btn-success flex-shrink-1"
                data-thread_id="{{$thread->id}}" data-url_add_tag="{{route('addTagList')}}">+
        </button>
    </div>
    <div class="card-body" id="card-body-tag">
        @foreach($thread->tagLists as $taglist)
            <div id="list-{{$taglist->id}}">
                <button form="saveTicket" type="button" id="deleteTaglist-{{$taglist->id}}"
                        class="deleteTaglist btn btn-danger"
                        data-thread_id="{{ $thread->id }}"
                        data-taglist_id="{{$taglist->id }}"
                >x
                </button>
                <select form="saveTicket" name="ticket-revival" class="form-select no-sort tags"
                        data-thread_id="{{$thread->id}}"
                        data-taglist_id="{{$taglist->id}}">
                    <option value="">{{ __('app.revival.select_revival') }}</option>
                    @foreach (\App\Models\Tags\Tag::all() as $optionTag)
                        <option value="{{ $optionTag->id }}">
                            {{ $optionTag->name }}
                        </option>
                    @endforeach
                </select>
                <div id="view-{{$taglist->id}}" class="mt-3 mb-2">
                    @foreach($taglist->tags as $tag)
                        <span class="tags-style"
                              style="background-color: {{ $tag->background_color }}; color: {{ $tag->text_color }};">
                                                    {{ $tag->name }} | <button
                                class="btn delete-tag"
                                data-tag_id="{{$tag->id}}"
                                data-taglist_id="{{$taglist->id}}"
                                style="color: {{ $tag->text_color }};"> x </button>
                                                </span>
                    @endforeach
                </div>
                <hr/>
            </div>
        @endforeach
    </div>
</div>
