@foreach($listTags as $key => $value)
    <span class="tags-style" style="background-color: {{ $value[0]->background_color }}; color: {{ $value[0]->text_color }}; cursor: default;">
        {{ $value[0]->name }}
        @if($value[1] > 1)
            <span class="tags-style-count">
                {{$value[1]}}
            </span>
        @endif
    </span>
@endforeach
