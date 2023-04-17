<?php

namespace App\Helpers;

use App\Enums\PermissionEnum;

abstract class Permission
{
    public static function main()
    {
        return [
            [
                'title' => trans_choice('app.permission.default_message_answer', 2),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.default_message_answer',2),
                        'items' => [
                            PermissionEnum::DEFAULT_ANSWER_READ,
                            PermissionEnum::DEFAULT_ANSWER_EDIT,
                            PermissionEnum::DEFAULT_ANSWER_LOCK,
                            PermissionEnum::DEFAULT_ANSWER_EDIT_LOCKED,
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
                            PermissionEnum::TAG_EDIT,
                            PermissionEnum::TAG_LOCK,
                            PermissionEnum::TAG_EDIT_LOCKED,
                        ],
                    ],
                ],
            ],
            [
                'title' => __('app.config.config'),
                'sub_sections' => [
                    [
                        'title' => __('app.config.config'),
                        'items' => [
                            PermissionEnum::BOT_CONFIG,
                            PermissionEnum::MISC_CONFIG,
                        ],
                    ],
                    [
                        'title' =>   trans_choice('app.sav_note.sav_note', 2),
                        'items' => [
                            PermissionEnum::SAV_NOTE_SHOW,
                            PermissionEnum::SAV_NOTE_SEARCH,
                            PermissionEnum::SAV_NOTE_READ,
                            PermissionEnum::SAV_NOTE_EDIT,
                            PermissionEnum::SAV_NOTE_DELETE,
                        ],
                    ],
                ],
            ],
            [
                'title' => __('app.navbar.admin'),
                'sub_sections' => [
                    [
                        'title' => trans_choice('app.permission.user',2),
                        'items' => [
                            PermissionEnum::USER_READ,
                            PermissionEnum::USER_EDIT,
                        ],
                    ],
                    [
                        'title' => trans_choice('app.permission.role',2),
                        'items' => [
                            PermissionEnum::ROLE_READ,
                            PermissionEnum::ROLE_EDIT,
                        ],
                    ],
                    [
                        'title' => trans_choice('app.config.channel', 2),
                        'items' => [
                            PermissionEnum::CHANNEL_READ,
                            PermissionEnum::CHANNEL_EDIT,
                        ],
                    ],
                    [
                        'title' => __('app.permission.jobs'),
                        'items' => [
                            PermissionEnum::JOB_READ,
                        ],
                    ],
                ],
            ],
            [
                'title' => __('app.navbar.doc'),
                'sub_sections' => [
                    [
                        'title' => __('app.navbar.doc'),
                        'items' => [
                            PermissionEnum::AGENT_DOC,
                            PermissionEnum::ADMIN_DOC,
                        ],
                    ],
                ],
            ],
        ];
    }

}
