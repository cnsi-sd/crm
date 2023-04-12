@foreach($listTags as $key => $value)
    <span class="tags-style" style="background-color: {{ $value['background_color'] }}; color: {{ $value['text_color'] }}; cursor: default;">
        {{ $key }}
        @if($value['count'] > 1)
            <span class="tags-style-count">
                {{$value['count']}}
            </span>
        @endif
    </span>
@endforeach
