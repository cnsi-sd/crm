<?php

namespace App\Helpers;

use App\Enums\PermissionEnum;
use Illuminate\Support\Facades\Auth;

abstract class Menu
{
    public static function main()
    {
        $user = Auth::user();

        $menu = [
            [
                'text' => __('app.navbar.dashboard'),
                'route' => route('home'),
                'icon' => 'uil-home-alt',
            ],
            [

                'text' => trans_choice('app.ticket.ticket',2),
                'icon' => 'uil-comment-message',
                'ref' => 'tickets',
                'sub_items' => [
                    [
                        'text' => __('app.ticket.all_tickets'),
                        'route' => route('all_tickets'),
                        'ref' => 'all_tickets',
                    ],
                    [
                        'text' => __('app.ticket.my_tickets'),
                        'route' => route('user_tickets',[$user->id]),
                        'ref' => 'user_tickets',
                    ],
                ]
            ],
            [
                'text' => __('app.navbar.config'),
                'icon' => 'uil-cog',
                'ref' => 'advanced',
                'sub_items' => [
                    [
                        'text' => trans_choice('app.default_answer.default_answer', 2),
                        'route' => route('default_answers'),
                        'ref' => 'defaultAnswers',
                        'permission' => PermissionEnum::DEFAULT_ANSWER_READ
                    ],
                    [
                        'text' => trans_choice('app.revival.revival', 2),
                        'route' => route('revival'),
                        'ref' => 'revival',
                        'permission' => PermissionEnum::REVIVAL_READ
                    ],
                    [
                        'text' => trans_choice('app.tags.tags', 2),
                        'route' => route('tags'),
                        'ref' => 'tags',
                        'permission' => PermissionEnum::TAG_READ
                    ],
                    [
                        'text' => __('app.bot.bot'),
                        'route' => route('bot_home'),
                        'ref' => 'bot',
                        'permission' => PermissionEnum::BOT_CONFIG
                    ],
                    [
                        'text' => __('app.config.misc.misc'),
                        'route' => route('misc_home'),
                        'ref' => 'misc',
                        'permission' => PermissionEnum::MISC_CONFIG
                    ],
                    [
                        'text' => trans_choice('app.sav_note.sav_note',2),
                        'route' => route('sav_notes'),
                        'ref' => 'savNotes',
                        'permission' => PermissionEnum::SAV_NOTE_READ
                    ]
                ]
            ],
            [
                'text' => __('app.navbar.admin'),
                'icon' => 'uil-cog',
                'ref' => 'settings',
                'sub_items' => [
                    [
                        'text' => trans_choice('app.user.user', 2),
                        'route' => route('users'),
                        'ref' => 'users',
                        'permission' => PermissionEnum::USER_READ
                    ],
                    [
                        'text' => trans_choice('app.role.role', 2),
                        'route' => route('roles'),
                        'ref' => 'roles',
                        'permission' => PermissionEnum::ROLE_READ
                    ],
                    [
                        'text' => trans_choice('jobwatcher::jobs.job', 2),
                        'route' => route('jobs'),
                        'ref' => 'jobs',
                        'permission' => PermissionEnum::JOB_READ
                    ],
                    [
                        'text' => trans_choice('app.admin.channel', 2),
                        'route' => route('channels'),
                        'ref' => 'channel',
                        'permission' => PermissionEnum::CHANNEL_READ
                    ],
                ],
            ],
            [
                'text' => __('app.navbar.doc'),
                'icon' => 'uil-medical-square',
                'ref' => 'documentation',
                'sub_items' => [
                    [
                        'text' => PermissionEnum::getMessage(PermissionEnum::AGENT_DOC),
                        'route' => route('agent_doc') . '/',
                        'ref' => 'documentation',
                        'permission' => PermissionEnum::USER_READ
                    ],
                    [
                        'text' => PermissionEnum::getMessage(PermissionEnum::ADMIN_DOC),
                        'route' => route('admin_doc') . '/',
                        'ref' => 'documentation',
                        'permission' => PermissionEnum::ROLE_READ
                    ],
                ],
            ],
        ];

        return self::checkAuthorization($menu);
    }

    public static function checkAuthorization($menu)
    {
        $user = Auth::user();

        foreach ($menu as $item_key => $item){
            if(isset($item['sub_items']))
            {
                foreach ($item['sub_items'] as $sub_item_key => $sub_item)
                {
                    if(isset($sub_item['permission']))
                    {
                        if (!$user->HasPermission($sub_item['permission']))
                        {
                            unset($menu[$item_key]['sub_items'][$sub_item_key]);
                        }
                    }
                }
                if ($menu[$item_key]['sub_items'] === [])
                {
                    unset($menu[$item_key]);
                }
            }
        }
        return $menu;
    }
}
