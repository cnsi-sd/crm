<?php

// https://github.com/diglactic/laravel-breadcrumbs

use App\Models\Settings\ProductConfiguration;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('home'));
});

/** Settings */
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.settings.settings'));
});

/** Advanced */
Breadcrumbs::for('permissions', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('app.navbar.permissions'));
});

/** Roles */
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions');
    $trail->push(trans_choice('app.role.role', 2));
});
Breadcrumbs::for('create_role', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(trans_choice('app.role.new', 2));
});
Breadcrumbs::for('edit_role', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(trans_choice('app.role.edit', 2));
});

/** Users */
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions');
    $trail->push(trans_choice('app.user.user', 2));
});
Breadcrumbs::for('create_user', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(trans_choice('app.user.new', 2));
});
Breadcrumbs::for('edit_user', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(trans_choice('app.user.edit', 2));
});
