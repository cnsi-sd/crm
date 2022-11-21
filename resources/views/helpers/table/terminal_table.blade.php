<div class="table-responsive">
<table class="table table-hover" id="{{ $table->getIdentifier() }}">
    <thead>
    <tr class="pb-0 mb-0">
        @foreach($table->getColumns() as $column)
            <th class="{{ $column->getClass() }} border-0 pb-0 mb-0 text-center">
                {!! $column->getLabel() !!}
            </th>
        @endforeach
    </tr>
    <tr class="pt-0 mt-0">
        @foreach($table->getColumns() as $column)
            <th class="{{ $column->getClass() }} border-0 pt-0 mt-0 pb-0 mb-0 text-center">
                @if($table->isSortable() && $column->isSortable())
                    @include('helpers/table/sort')
                @endif
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @if($table->isSearchable())
        <tr class="{{ $column->getClass() }}">
            <form method="post">
                @csrf
                @foreach($table->getColumns() as $column)
                    @if($column->isSearchable())
                        <td>
                            @switch($column->getType())
                                @case(\App\Enums\ColumnTypeEnum::TEXT)
                                @case(\App\Enums\ColumnTypeEnum::NUMBER)
                                @case(\App\Enums\ColumnTypeEnum::PRICE)
                                @case(\App\Enums\ColumnTypeEnum::PERCENTAGE)
                                    @include('helpers/table/text')
                                    @break

                                @case(\App\Enums\ColumnTypeEnum::SELECT)
                                @case(\App\Enums\ColumnTypeEnum::BOOLEAN)
                                    @include('helpers/table/select')
                                    @break

                                @case(\App\Enums\ColumnTypeEnum::DATE)
                                    @include('helpers/table/date')
                                    @break
                            @endswitch
                        </td>
                    @elseif($column->getType() === \App\Enums\ColumnTypeEnum::ACTIONS)
                        <td>
                            @if($table->isSearchable())
                                <button type="submit" name="submit_search" class="btn btn-sm btn-primary btn_round" title="{{ __('app.search') }}">
                                    <i class="uil-search"></i>
                                </button>
                                <button type="submit" name="reset_search" class="btn btn-sm btn-danger btn_round" title="{{ __('app.reset') }}">
                                    <i class="uil-times-circle"></i>
                                </button>
                            @endif

                            @if($table->isExportable())
                                <button type="submit" name="{{ $table->getExportAction() }}" class="btn btn-sm btn-primary btn_round" title="{{ __('app.export') }}">
                                    <i class="uil-download-alt"></i>
                                </button>
                            @endif
                        </td>
                    @else
                        <td></td>
                    @endif
                @endforeach
            </form>
        </tr>
    @endif

    @forelse($table->getLines() as $line)
        <tr @if($table->getRowIdentifier()) id="{{ $line->{$table->getRowIdentifier()} }}" @endif>
            @foreach($table->getColumns() as $column)
                <td class="{{ $column->getClass() }}" data-key="{{ $column->getKey() }}">
                    @switch($column->getType())
                        @case(\App\Enums\ColumnTypeEnum::TEXT)
                            @if($column->isHtml())
                                {!! $column->getValue($line) !!}
                            @else
                                {{ $column->getValue($line) }}
                            @endif
                            @break

                        @case(\App\Enums\ColumnTypeEnum::SELECT)
                        @case(\App\Enums\ColumnTypeEnum::DATE)
                        @case(\App\Enums\ColumnTypeEnum::NUMBER)
                            @if($column->isHtml())
                                {!! $column->getValue($line) !!}
                            @else
                                {{ $column->getValue($line) }}
                            @endif
                            @break

                        @case(\App\Enums\ColumnTypeEnum::PRICE)
                            @if($column->getValue($line) || $column->getValue($line) == 0)
                                {{ \App\Helpers\PriceConverter::withThousandSeparator($column->getValue($line)) }}
                            @endif
                            @break

                        @case(\App\Enums\ColumnTypeEnum::PERCENTAGE)
                            @if($column->getValue($line) || $column->getValue($line) == 0)
                                {{ \App\Helpers\PriceConverter::floatToString($column->getValue($line), '%') }}
                            @endif
                            @break

                        @case(\App\Enums\ColumnTypeEnum::BOOLEAN)
                            @if($column->isHtml())
                                {!! $column->getValue($line) !!}
                            @else
                                @include('helpers/table/boolean', ['bool' => $column->getValue($line)])
                            @endif
                            @break

                        @case(\App\Enums\ColumnTypeEnum::ACTIONS)
                            {!! $column->getValue($line) !!}
                            @break
                    @endswitch
                </td>
            @endforeach
        </tr>
    @empty
        @include('helpers/table/no_result')
    @endforelse
    </tbody>
</table>
</div>

@if ($table->isPaginable())
    @include('helpers/table/pagination')
@endif
