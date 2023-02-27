<?php

use App\Http\Controllers\Configuration\AutoReplyAbstractController;
use App\Http\Controllers\Configuration\DefaultAnswerAbstractController;
use App\Http\Controllers\Configuration\RevivalAbstractController;
use App\Http\Controllers\HomeAbstractController;
use App\Http\Controllers\Settings\Permissions\RoleAbstractController;
use App\Http\Controllers\Settings\Permissions\UserAbstractController;
use App\Http\Controllers\Tickets\TicketAbstractController;
use App\Http\Controllers\Configuration\TagsAbstractController;
use App\Http\Controllers\Search\SearchAbstractController;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Ticket;
use App\Models\User\Role;
use App\Models\User\User;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';
Route::prefix('/')->group(function () {
    Route::get('', [HomeAbstractController::class, 'home'])->name('home');

    Route::prefix('tickets')->group(function() {
        Route::match(['get', 'post'], 'hide_comment/{comment}', [TicketAbstractController::class, 'hide_comment'])->name('hide_comment')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'all_tickets', [TicketAbstractController::class, 'all_tickets'])->name('all_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'user/{user}', [TicketAbstractController::class, 'user_tickets'])->name('user_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}', [TicketAbstractController::class, 'redirectTicket'])->name('ticket')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}/{thread}', [TicketAbstractController::class, 'ticket'])->name('ticket_thread')->can('read', Ticket::class);
    });
    Route::prefix('admin')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::match(['get', 'post'], 'new', [RoleAbstractController::class, 'edit'])->name('create_role')->can('edit', Role::class);
            Route::match(['get', 'post'], '{role}', [RoleAbstractController::class, 'edit'])->name('edit_role')->can('read', Role::class);
            Route::match(['get', 'post'], '', [RoleAbstractController::class, 'list'])->name('roles')->can('read', Role::class);
        });
        Route::prefix('users')->group(function () {
            Route::match(['get', 'post'], 'new', [UserAbstractController::class, 'edit'])->name('create_user')->can('edit', User::class);
            Route::match(['get', 'post'], '{user}', [UserAbstractController::class, 'edit'])->name('edit_user')->can('edit', User::class);
            Route::match(['get', 'post'], '', [UserAbstractController::class, 'list'])->name('users')->can('read', User::class);
        });
    });
    Route::match(['get','post'], 'search', [SearchAbstractController::class, 'search'])->name('search')->can('read', Ticket::class);

    Route::prefix('configuration')->group(function () {
        Route::prefix('default_answer')->group(function () {
            Route::match(['get', 'post'], 'new', [DefaultAnswerAbstractController::class, 'edit'])->name('create_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}', [DefaultAnswerAbstractController::class, 'edit'])->name('edit_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '', [DefaultAnswerAbstractController::class, 'list'])->name('defaultAnswers')->can('read', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}/delete', [DefaultAnswerAbstractController::class, 'delete'])->name('delete_defaultAnswers')->can('edit', DefaultAnswer::class);
        });
        Route::prefix('revival')->group(function () {
            Route::match(['get', 'post'], 'new', [RevivalAbstractController::class, 'edit'])->name('create_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '{revival}', [RevivalAbstractController::class, 'edit'])->name('edit_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '', [RevivalAbstractController::class, 'list'])->name('revival')->can('read', Revival::class);
            Route::match(['get', 'post'], '{revival}/delete', [RevivalAbstractController::class, 'delete'])->name('delete_revival')->can('edit', Revival::class);
        });
        Route::prefix('autoReply')->group(function () {
            Route::match(['get', 'post'], '', [AutoReplyAbstractController::class, 'edit'])->name('autoReply');
        });
        Route::prefix('tags')->group(function () {
            Route::match(['get', 'post'], 'new', [TagsAbstractController::class, 'edit'])->name('create_tags')->can('edit', Tag::class);
            Route::match(['get', 'post'], '{tags}', [TagsAbstractController::class, 'edit'])->name('edit_tags')->can('edit', Tag::class);
            Route::match(['get', 'post'], '', [TagsAbstractController::class, 'list'])->name('tags')->can('read', Tag::class);
            Route::match(['get', 'post'], '{tags}/delete', [TagsAbstractController::class, 'delete'])->name('delete_tags')->can('edit', Tag::class);
        });
    });

// CALL AJAX
    Route::get('/ajaxTags', [TagsAbstractController::class, 'ajax_tags']);
    Route::post('/addTagList', [TagsAbstractController::class, 'newTagLigne']);
    Route::post('/saveTicketThreadTags', [TicketAbstractController::class, 'saveThreadTags']);
    Route::post('/deleteTagList', [TicketAbstractController::class, 'delete_ThreadTagList']);
    Route::post('/deleteThreadTagOnTagList', [TicketAbstractController::class, 'delete_tag']);
});
