<input class="form-control form-control-sm" name="search[{{ $column->getKey() }}]" value="{{ $table->getSearched($column->getKey()) }}" placeholder="{{ $column->getLabel() }}">
