<?php

// https://github.com/diglactic/laravel-breadcrumbs

use App\Models\Settings\ProductConfiguration;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('any', function (BreadcrumbTrail $trail) {
});

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('home'));
});

/** Settings */
Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.settings.settings'));
});

/** Permissions */
Breadcrumbs::for('permissions', function (BreadcrumbTrail $trail) {
    $trail->parent('settings');
    $trail->push(__('app.navbar.permissions'));
});

/** Tickets */
Breadcrumbs::for('tickets', function (BreadcrumbTrail $trail) {
   $trail->parent('home');
   $trail->push(trans_choice('app.ticket.ticket',2));
});

/** Roles */
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions');
    $trail->push(trans_choice('app.role.role', 2), route('roles'));
});
Breadcrumbs::for('create_role', function (BreadcrumbTrail $trail) {
    $trail->parent('roles');
    $trail->push(__('app.role.new'), route('create_role'));
});
Breadcrumbs::for('edit_role', function (BreadcrumbTrail $trail, $role) {
    $trail->parent('roles');
    $trail->push(__('app.role.edit'), route('edit_role', $role));
});

/** Users */
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions');
    $trail->push(trans_choice('app.user.user', 2), route('users'));
});
Breadcrumbs::for('create_user', function (BreadcrumbTrail $trail) {
    $trail->parent('users');
    $trail->push(__('app.user.new'), route('create_user'));
});
Breadcrumbs::for('edit_user', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users');
    $trail->push(__('app.user.edit'), route('edit_user', $user));
});

/** Tickets */
Breadcrumbs::for('all_tickets', function (BreadcrumbTrail $trail) {
    $trail->parent('tickets');
    $trail->push(__('app.ticket.all_tickets'), route('all_tickets'));
});
Breadcrumbs::for('user_tickets', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('tickets');
    $trail->push(__('app.ticket.my_tickets'), route('user_tickets', $user));
});
Breadcrumbs::for('ticket', function (BreadcrumbTrail $trail, $ticket) {
    $trail->parent('tickets');
    $trail->push(trans_choice('app.ticket.ticket',1), route('ticket', $ticket));
});
Breadcrumbs::for('ticket_thread', function (BreadcrumbTrail $trail, $ticket) {
    $trail->parent('tickets');
    $trail->push(trans_choice('app.ticket.ticket',1), route('ticket', $ticket));
});
