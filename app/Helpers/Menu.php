<?php

namespace App\Helpers;

use App\Enums\PermissionEnum;
use Illuminate\Support\Facades\Auth;

abstract class Menu
{
    public static function main()
    {
        $menu = [
            [
                'text' => __('app.navbar.dashboard'),
                'route' => route('home'),
                'icon' => 'uil-home-alt',
            ],
            [
                'text' => __('app.navbar.admin'),
                'icon' => 'uil-cog',
                'ref' => 'advanced',
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
                    ]
                ]
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
