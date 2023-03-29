<?php

namespace App\Helpers;

/**
 * @property boolean $is_locked
 */
trait Lockable
{
    public function getIsLocked() : bool
    {
        return $this->is_locked;
    }
}
