<?php

namespace App\Helpers;

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
                'text' => __('app.navbar.settings'),
                'icon' => 'uil-cog',
                'ref' => 'settings',
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
