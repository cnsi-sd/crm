<?php

// https://github.com/diglactic/laravel-breadcrumbs

use App\Models\Settings\ProductConfiguration;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Auth;

Breadcrumbs::for('any', function (BreadcrumbTrail $trail) {
});

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('home'));
});

/** Settings */
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.navbar.admin'));
});

/** Configuration */
Breadcrumbs::for('configuration', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.configuration.configuration'));
});

/** Permissions */
Breadcrumbs::for('permissions', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push(__('app.navbar.permissions'));
});

/** Tickets */
Breadcrumbs::for('tickets', function (BreadcrumbTrail $trail) {
   $trail->parent('home');
   $trail->push(trans_choice('app.ticket.ticket',2));
});

/** Roles */
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
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
    $trail->parent('admin');
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
Breadcrumbs::for('ticket_thread', function (BreadcrumbTrail $trail, $ticket, $thread) {
    $user = Auth::user();
    $trail->parent('user_tickets', $user->id);
    $trail->push('#' . $ticket->id, route('ticket', $ticket));
    $trail->push($thread->name, route('ticket_thread', [$ticket, $thread]));
});

/** defaultAnswers */
Breadcrumbs::for('defaultAnswers', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.defaultAnswer.defaultAnswer', 2), route('defaultAnswers'));
});

Breadcrumbs::for('create_defaultAnswer', function (BreadcrumbTrail $trail) {
    $trail->parent('defaultAnswers');
    $trail->push(__('app.create'));
});

Breadcrumbs::for('edit_defaultAnswer', function (BreadcrumbTrail $trail, \App\Models\Channel\DefaultAnswer $defaultAnswer) {
    $trail->parent('defaultAnswers');
    $trail->push($defaultAnswer->id);
    $trail->push(__('app.edit'));
});

/** Revival */
Breadcrumbs::for('revival', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.revival.revival', 2), route('revival'));
});

Breadcrumbs::for('create_revival', function (BreadcrumbTrail $trail) {
    $trail->parent('revival');
    $trail->push(__('app.create'));
});

Breadcrumbs::for('edit_revival', function (BreadcrumbTrail $trail) {
    $trail->parent('revival');
    $trail->push(__('app.edit'));
});

/** Auto Reply */
Breadcrumbs::for('autoReply', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.autoReply.autoReply', 2), route('autoReply'));
});

/** Tag */
Breadcrumbs::for('tags', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.tags.tags', 2), route('tags'));
});

Breadcrumbs::for('create_tags', function (BreadcrumbTrail $trail) {
    $trail->parent('tags');
    $trail->push(__('app.tags.create'));
});

Breadcrumbs::for('edit_tags', function (BreadcrumbTrail $trail) {
    $trail->parent('tags');
    $trail->push(__('app.tags.edit'));
});

Breadcrumbs::for('search', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.search'));
});
