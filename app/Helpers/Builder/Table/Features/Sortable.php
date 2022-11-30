<?php

namespace App\Helpers\Builder\Table\Features;

use App\Helpers\Builder\Table\TableBuilder;

trait Sortable
{
    /** @var array */
    private $search_options = [];

    /** @var bool */
    private $is_sortable = true;

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->is_sortable;
    }

    /**
     * @param bool $is_sortable
     * @return TableBuilder
     */
    public function setSortable(bool $is_sortable): TableBuilder
    {
        $this->is_sortable = $is_sortable;
        return $this;
    }
}
