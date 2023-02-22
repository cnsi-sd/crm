@foreach($listTags as $key => $value)
    <span type="button" class="btn tags-style" style="background-color: {{ $value['background_color'] }}; color: {{ $value['text_color'] }};">
        {{ $key }}
        @if($value['count'] > 1)
            <span class="tags-style-count">
                {{$value['count']}}
            </span>
        @endif
    </span>
@endforeach
