<?php

// https://github.com/diglactic/laravel-breadcrumbs

use App\Models\Channel\SavNote;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use Illuminate\Support\Facades\Auth;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.home'), route('home'));
});

/** My account */
Breadcrumbs::for('my_account', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.user.my_account'));
});


/** Settings */
Breadcrumbs::for('admin', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.navbar.admin'));
});

/** Configuration */
Breadcrumbs::for('configuration', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('app.config.config'));
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
Breadcrumbs::for('customer_service_process', function (BreadcrumbTrail $trail, $ticket) {
    $user = Auth::user();
    $trail->parent('user_tickets', $user->id);
    $trail->push('#' . $ticket->id, route('ticket', $ticket));
    $trail->push(__('app.customer_service_process'));
});

/** defaultAnswers */
Breadcrumbs::for('default_answers', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.default_answer.default_answer', 2), route('default_answers'));
});

Breadcrumbs::for('create_default_answer', function (BreadcrumbTrail $trail) {
    $trail->parent('default_answers');
    $trail->push(__('app.create'));
});

Breadcrumbs::for('edit_default_answer', function (BreadcrumbTrail $trail, \App\Models\Channel\DefaultAnswer $defaultAnswer) {
    $trail->parent('default_answers');
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

/** Bot */
Breadcrumbs::for('bot_home', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(__('app.bot.bot'), route('bot_home'));
});
Breadcrumbs::for('bot_acknowledgement', function (BreadcrumbTrail $trail) {
    $trail->parent('bot_home');
    $trail->push(__('app.bot.acknowledgement.acknowledgement'), route('bot_acknowledgement'));
});
Breadcrumbs::for('bot_invoice', function (BreadcrumbTrail $trail) {
    $trail->parent('bot_home');
    $trail->push(__('app.bot.invoice.invoice'), route('bot_invoice'));
});
Breadcrumbs::for('bot_shipping_information', function (BreadcrumbTrail $trail) {
    $trail->parent('bot_home');
    $trail->push(__('app.bot.shipping_information.shipping_information'), route('bot_shipping_information'));
});
Breadcrumbs::for('bot_sav', function (BreadcrumbTrail $trail) {
    $trail->parent('bot_home');
    $trail->push(__('app.bot.sav.sav'), route('bot_sav'));
});

/** Misc Config */
Breadcrumbs::for('misc_home', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(__('app.config.misc.misc'), route('misc_home'));
});
Breadcrumbs::for('variables_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.variables'), route('variables_config'));
});
Breadcrumbs::for('incidents_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.incidents.incidents'), route('incidents_config'));
});
Breadcrumbs::for('savprocess_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.savprocess.savprocess'), route('savprocess_config'));
});
Breadcrumbs::for('answer_offer_questions_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.default_answer_offer_questions.default_answer_offer_questions'), route('answer_offer_questions_config'));
});
Breadcrumbs::for('mirakl_refunds_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.mirakl_refunds.mirakl_refunds'), route('mirakl_refunds_config'));
});
Breadcrumbs::for('closed_discussion_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.closed_discussion.closed_discussion'), route('closed_discussion_config'));
});
Breadcrumbs::for('parcel_management_config', function (BreadcrumbTrail $trail) {
    $trail->parent('misc_home');
    $trail->push(__('app.config.misc.pm.pm'), route('parcel_management_config'));
});

/** Channel */
Breadcrumbs::for('channels', function (BreadcrumbTrail $trail) {
    $trail->parent('admin');
    $trail->push(trans_choice('app.admin.channel', 2), route('channels'));
});

Breadcrumbs::for('edit_channel', function (BreadcrumbTrail $trail) {
    $trail->parent('channels');
    $trail->push(__('app.channel.edit'));
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
    $trail->push(__('searchable::search.search'));
});

/** SavNote */
Breadcrumbs::for('sav_notes', function (BreadcrumbTrail $trail) {
    $trail->parent('configuration');
    $trail->push(trans_choice('app.sav_note.sav_note', 2), route('sav_notes'));
});

Breadcrumbs::for('create_sav_note', function (BreadcrumbTrail $trail) {
    $trail->parent('sav_notes');
    $trail->push(__('app.sav_note.new'));
});

Breadcrumbs::for('show_sav_note', function (BreadcrumbTrail $trail, SavNote $savNote) {
    $trail->parent('sav_notes');
    $trail->push($savNote->manufacturer, route('show_sav_note', $savNote));
});

Breadcrumbs::for('edit_sav_note', function (BreadcrumbTrail $trail, SavNote $savNote) {
    $trail->parent('show_sav_note', $savNote);
    $trail->push(__('app.edit'), route('edit_sav_note', $savNote));
});
/** JobWatcher */
Breadcrumbs::for('jobs', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(trans_choice('jobwatcher::jobs.job', 2));
});

