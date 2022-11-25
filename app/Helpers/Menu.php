<?php

namespace App\Helpers;

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
                'text' => __('app.navbar.settings'),
                'icon' => 'uil-cog',
                'ref' => 'advanced',
                'sub_items' => [
                    [
                        'text' => trans_choice('app.user.user', 2),
                        'route' => route('users'),
                        'ref' => 'users',
                    ],
                    [
                        'text' => trans_choice('app.role.role', 2),
                        'route' => route('roles'),
                        'ref' => 'roles',
                    ],
                ],
            ],
        ];

        return $menu;
    }
}
