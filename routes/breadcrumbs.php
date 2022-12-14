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
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.navbar.admin'));
});

/** Configuration */
Breadcrumbs::for('configuration', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.configuration.configuration'));
});

/** Advanced */
Breadcrumbs::for('permissions', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push(__('app.navbar.permissions'));
});


/** Roles */
Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
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
    $trail->parent('admin');
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

/** defaultAnswers */
Breadcrumbs::for('defaultAnswers', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(__('app.configuration.defaultAnswer'), route('defaultAnswers'));
});

Breadcrumbs::for('create_defaultAnswer', function (BreadcrumbTrail $trail) {
    $trail->parent('defaultAnswers');
    $trail->push(__('app.defaultAnswer.create'));
});

Breadcrumbs::for('edit_defaultAnswer', function (BreadcrumbTrail $trail, \App\Models\Channel\DefaultAnswer $defaultAnswer) {
    $trail->parent('defaultAnswers');
    $trail->push($defaultAnswer->id);
    $trail->push(__('app.defaultAnswer.edit'));
});


