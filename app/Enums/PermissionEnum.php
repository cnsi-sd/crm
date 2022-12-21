<?php

namespace App\Enums;

abstract class PermissionEnum extends AbstractEnum
{
    /** Advanced admin */
    const USER_READ             = 'user_read';
    const USER_EDIT             = 'user_edit';
    const ROLE_READ             = 'role_read';
    const ROLE_EDIT             = 'role_edit';
    const DEFAULT_ANSWER_READ   = 'default_answer_read';
    const DEFAULT_ANSWER_EDIT   = 'default_answer_edit';

}