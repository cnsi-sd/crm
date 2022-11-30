<select class="form-control form-control-sm no-select2" name="search[{{ $column->getKey() }}]">
    <option value="" disabled="disabled" selected="selected">{{ $column->getLabel() }}</option>
    @foreach($column->getOptions() as $key => $value)
        <option
            value="{{ $key }}"
            @if((string) $key === (string) $table->getSearched($column->getKey())) selected="selected" @endif
        >
            {{ $value }}
        </option>
    @endforeach
</select>
