<div>
    <label for="date-from"> {{ trans_choice('app.from', 2) }}</label>
    <input class="form-control form-control-sm d-inline-block w-75" id="date-from" type="date" name="search[{{ $column->getKey() }}_from]" value="{{ $table->getSearched($column->getKey() . '_from') }}" />
</div>
<div>
    <label for="date-to">{{ trans_choice('app.to', 2) }} </label>
    <input class="form-control form-control-sm d-inline-block w-75" id="date-to" type="date" name="search[{{ $column->getKey() }}_to]" value="{{ $table->getSearched($column->getKey() . '_to') }}" />
</div>

