<?php

namespace App\Helpers\Builder\Table\Features;

use App\Helpers\Builder\Table\TableBuilder;

trait Terminal
{
    /** @var bool */
    private $is_for_terminal = false;

    /** @var bool */
    private $is_linked_lines = false;

    public function isForTerminal(): bool
    {
        return $this->is_for_terminal;
    }

    public function setForTerminal(bool $is_for_terminal): TableBuilder
    {
        $this->is_for_terminal = $is_for_terminal;
        return $this;
    }
}
