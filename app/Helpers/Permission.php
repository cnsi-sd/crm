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
            [
                'title' => trans_choice('app.permission.revival', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.revival',2),
                        'items' => [
                            PermissionEnum::REVIVAL_READ,
                            PermissionEnum::REVIVAL_EDIT,
                        ],
                    ],
                ],
            ],
            [
                'title' => trans_choice('app.permission.ticket', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.ticket',2),
                        'items' => [
                            PermissionEnum::TICKET_READ,
                        ],
                    ],
                ],
            ],
            [
                'title' => trans_choice('app.permission.tag', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.tag',2),
                        'items' => [
                            PermissionEnum::TAG_READ,
                            PermissionEnum::TAG_EDIT
                        ],
                    ],
                ],
            ],
            [
                'title' => __('app.configuration.configuration'),
                'sub_sections' => [
                    [
                        'title' => __('app.configuration.configuration'),
                        'items' => [
                            PermissionEnum::BOT_CONFIG,
                            PermissionEnum::VARIABLES_CONFIG,
                        ],
                    ],
                ],
            ],
            [
                'title' => __('app.navbar.admin'),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.configuration.channel', 2),
                        'items' => [
                            PermissionEnum::CHANNEL_READ,
                            PermissionEnum::CHANNEL_EDIT,
                        ],
                    ],
                ],
            ],
        ];
    }

}
