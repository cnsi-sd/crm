<?php

namespace App\Helpers\Builder\Table\Features;

use App\Helpers\Builder\Table\TableBuilder;
use App\Helpers\Builder\Table\TableColumnBuilder;
use Exception;
use Illuminate\Support\Arr;

trait Columns
{
    /** @var TableColumnBuilder[] */
    private $columns = [];



    /**
     * @return TableColumnBuilder[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param TableColumnBuilder[] $columns
     * @return TableBuilder
     */
    public function setColumns(array $columns): TableBuilder
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * @param TableColumnBuilder|array $columns
     * @param string|null $key
     * @return TableBuilder
     * @throws Exception
     */
    public function addColumns($columns, string $key = null): TableBuilder
    {
        $to_add = Arr::wrap($columns);

        if (is_null($key)) {
            $this->columns = array_merge($this->columns, $to_add);
        } else {
            $index = $this->getIndexOfColumnByKey($key) + 1;
            array_splice($this->columns, $index, 0, $to_add);
        }

        return $this;
    }

    /**
     * @param TableColumnBuilder|array $columns
     * @return TableBuilder
     */
    public function addColumnToBegin($columns): TableBuilder
    {
        $to_add = Arr::wrap($columns);
        $this->columns = array_merge($to_add, $this->columns);
        return $this;
    }

    /**
     * @param string $key
     * @return int
     * @throws Exception
     */
    private function getIndexOfColumnByKey(string $key): int
    {
        foreach ($this->columns as $index => $column) {
            if ($column->getKey() === $key)
                return $index;
        }
        throw new Exception("TableBuilder : " . __('app.column_not_exist') . ' : ' . $key);
    }
}
