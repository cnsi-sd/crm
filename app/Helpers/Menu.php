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
                        'text' => trans_choice('app.defaultAnswer.defaultAnswer', 2),
                        'route' => route('defaultAnswers'),
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
                        'text' => trans_choice('app.config.channel', 2),
                        'route' => route('channels'),
                        'ref' => 'channel',
                        'permission' => PermissionEnum::CHANNEL_READ
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
