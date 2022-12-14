<?php

namespace App\Helpers;

use App\Enums\PermissionEnum;

abstract class Permission
{
    public static function main()
    {
        return [
            [
                'title' => trans_choice('app.permission.user', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.user',2),
                        'items' => [
                            PermissionEnum::USER_READ,
                            PermissionEnum::USER_EDIT,
                        ],
                    ],
                ],
            ],
            [
                'title' => trans_choice('app.permission.role', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.role',2),
                        'items' => [
                            PermissionEnum::ROLE_READ,
                            PermissionEnum::ROLE_EDIT,
                        ],
                    ],
                ],
            ],
            [
                'title' => trans_choice('app.permission.default_message_answer', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.default_message_answer',2),
                        'items' => [
                            PermissionEnum::DEFAULT_ANSWER_READ,
                            PermissionEnum::DEFAULT_ANSWER_EDIT,
                        ],
                    ],
                ],
            ],
        ];
    }

}
