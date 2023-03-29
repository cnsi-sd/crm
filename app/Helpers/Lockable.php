<?php

namespace App\Helpers;

trait Lockable
{
    public function getIsLocked() : bool
    {
        return $this->is_locked;
    }
}
