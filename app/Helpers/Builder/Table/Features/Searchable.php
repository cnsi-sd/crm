<?php

namespace App\Helpers\Builder\Table\Features;

use App\Enums\ColumnTypeEnum;
use App\Helpers\Builder\Table\TableBuilder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

trait Searchable
{
    /** @var array */
    private $search_options = [];

    /** @var bool */
    private $is_searchable = true;

    /**
     * @return bool
     */
    public function isSearchable(): bool
    {
        return $this->is_searchable;
    }

    /**
     * @param bool $is_searchable
     * @return TableBuilder
     */
    public function setSearchable(bool $is_searchable): TableBuilder
    {
        $this->is_searchable = $is_searchable;
        return $this;
    }

    private function setSearchOptions(Request $request)
    {
        $session_key = 'tablebuilder.' . $this->identifier . '.search';

        if ($request->exists('submit_search') && $request->has('search')) {
            $search = $request->post('search');

            foreach ($this->columns as $column) {
                if ($column->getType() === ColumnTypeEnum::DATE) {
                    $key_from = $column->getKey() . '_from';
                    $key_to = $column->getKey() . '_to';
                    if ((isset($search[$key_from]) && $search[$key_from] !== "" && $search[$key_from] != "null") || isset($search[$key_to]) && $search[$key_to] !== "" && $search[$key_to] != "null") {
                        $this->search_options[$key_from] = $search[$key_from] ? $search[$key_from] : '0000-00-00';
                        $this->search_options[$key_to] = $search[$key_to] ? $search[$key_to] : date('Y-m-d');
                    }
                }
                if (isset($search[$column->getKey()]) && $search[$column->getKey()] !== "" && $search[$column->getKey()] != "null") {
                    $this->search_options[$column->getKey()] = $search[$column->getKey()];
                }
            }

            // Reset pagination to page 1
            Paginator::currentPageResolver(function () {
                return 1;
            });
        } elseif ($request->exists('reset_search')) {
            $this->search_options = [];
        } elseif (session()->has($session_key)) {
            $this->search_options = session($session_key);
        }

        session([$session_key => $this->search_options]);
    }

    public function getSearched($key)
    {
        return $this->search_options[$key] ?? '';
    }
}
