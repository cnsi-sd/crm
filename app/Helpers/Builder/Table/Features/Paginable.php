<?php

namespace App\Helpers\Builder\Table\Features;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableBuilder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Request;

trait Paginable
{
    private bool $is_paginable = true;
    private int $per_page = 50;

    public function setPaginable(bool $is_paginable, ?int $per_page = null) : TableBuilder
    {
        $this->is_paginable = $is_paginable;
        
        if(!$is_paginable && isset($this->query)) {
            $this->query->getQuery()->limit = null;
            $this->query->getQuery()->offset = null;
        }

        if(!$is_paginable && isset($this->query)) {
            $this->query->getQuery()->limit = null;
            $this->query->getQuery()->offset = null;
        }

        if($per_page)
            $this->per_page = $per_page;

        return $this;
    }

    public function isPaginable(): bool
    {
        return $this->is_paginable;
    }

    public function getPerPage(): int
    {
        return $this->per_page;
    }
}
