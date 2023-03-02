<?php

use App\Http\Controllers\Configuration\AutoReplyController;
use App\Http\Controllers\Configuration\DefaultAnswerController;
use App\Http\Controllers\Configuration\RevivalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Settings\Permissions\RoleController;
use App\Http\Controllers\Settings\Permissions\UserController;
use App\Http\Controllers\Tickets\TicketController;
use App\Http\Controllers\Configuration\TagsController;
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
    Route::get('', [HomeController::class, 'home'])->name('home');

    Route::prefix('tickets')->group(function() {
        Route::match(['get', 'post'], 'hide_comment/{comment}', [TicketController::class, 'hide_comment'])->name('hide_comment')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'all_tickets', [TicketController::class, 'all_tickets'])->name('all_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'user/{user}', [TicketController::class, 'user_tickets'])->name('user_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}', [TicketController::class, 'redirectTicket'])->name('ticket')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}/{thread}', [TicketController::class, 'ticket'])->name('ticket_thread')->can('read', Ticket::class);
    });
    Route::prefix('admin')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::match(['get', 'post'], 'new', [RoleController::class, 'edit'])->name('create_role')->can('edit', Role::class);
            Route::match(['get', 'post'], '{role}', [RoleController::class, 'edit'])->name('edit_role')->can('read', Role::class);
            Route::match(['get', 'post'], '', [RoleController::class, 'list'])->name('roles')->can('read', Role::class);
        });
        Route::prefix('users')->group(function () {
            Route::match(['get', 'post'], 'new', [UserController::class, 'edit'])->name('create_user')->can('edit', User::class);
            Route::match(['get', 'post'], '{user}', [UserController::class, 'edit'])->name('edit_user')->can('edit', User::class);
            Route::match(['get', 'post'], '', [UserController::class, 'list'])->name('users')->can('read', User::class);
        });
    });

    Route::prefix('configuration')->group(function () {
        Route::prefix('default_answer')->group(function () {
            Route::match(['get', 'post'], 'new', [DefaultAnswerController::class, 'edit'])->name('create_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}', [DefaultAnswerController::class, 'edit'])->name('edit_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '', [DefaultAnswerController::class, 'list'])->name('defaultAnswers')->can('read', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}/delete', [DefaultAnswerController::class, 'delete'])->name('delete_defaultAnswers')->can('edit', DefaultAnswer::class);
        });
        Route::prefix('revival')->group(function () {
            Route::match(['get', 'post'], 'new', [RevivalController::class, 'edit'])->name('create_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '{revival}', [RevivalController::class, 'edit'])->name('edit_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '', [RevivalController::class, 'list'])->name('revival')->can('read', Revival::class);
            Route::match(['get', 'post'], '{revival}/delete', [RevivalController::class, 'delete'])->name('delete_revival')->can('edit', Revival::class);
        });
        Route::prefix('autoReply')->group(function () {
            Route::match(['get', 'post'], '', [AutoReplyController::class, 'edit'])->name('autoReply');
        });
        Route::prefix('tags')->group(function () {
            Route::match(['get', 'post'], 'new', [TagsController::class, 'edit'])->name('create_tags')->can('edit', Tag::class);
            Route::match(['get', 'post'], '{tags}', [TagsController::class, 'edit'])->name('edit_tags')->can('edit', Tag::class);
            Route::match(['get', 'post'], '', [TagsController::class, 'list'])->name('tags')->can('read', Tag::class);
            Route::match(['get', 'post'], '{tags}/delete', [TagsController::class, 'delete'])->name('delete_tags')->can('edit', Tag::class);
        });
    });

// CALL AJAX
    Route::get('/ajaxTags', [TagsController::class, 'ajax_tags']);
    Route::post('/addTagList', [TagsController::class, 'newTagLigne']);
    Route::post('/saveTicketThreadTags', [TicketController::class, 'saveThreadTags']);
    Route::post('/deleteTagList', [TicketController::class, 'delete_ThreadTagList']);
    Route::post('/deleteThreadTagOnTagList', [TicketController::class, 'delete_tag']);
});
