<a href="{{ $table->getOrderByUrl('ASC', $column->getWhereKey() ?: $column->getKey()) }}" class="button_orderby"><i class="uil-angle-up"></i></a>
<a href="{{ $table->getOrderByUrl('DESC', $column->getWhereKey() ?: $column->getKey()) }}" class="button_orderby"><i class="uil-angle-down"></i></a>
