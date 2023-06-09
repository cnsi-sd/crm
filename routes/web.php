<?php

use App\Http\Controllers\Configuration\BotController;
use App\Http\Controllers\Configuration\DefaultAnswerController;
use App\Http\Controllers\Configuration\MiscController;
use App\Http\Controllers\Configuration\RevivalController;
use App\Http\Controllers\Configuration\SavNoteController;
use App\Http\Controllers\Configuration\TagsController;
use App\Http\Controllers\DocumentationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Settings\ChannelController;
use App\Http\Controllers\Settings\Permissions\RoleController;
use App\Http\Controllers\Settings\Permissions\UserController;
use App\Http\Controllers\Tickets\TicketController;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\SavNote;
use App\Models\Tags\Tag;
use App\Models\Ticket\Revival\Revival;
use App\Models\Ticket\Ticket;
use App\Models\User\Role;
use App\Models\User\User;
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

   Route::match(['get', 'post'], 'redirect/{channel}/{channel_order_number}', [TicketController::class, 'redirectOrCreateTicket'])->name('redirect_or_create_ticket')->can('read', Ticket::class);

    Route::prefix('tickets')->group(function() {
        Route::match(['get', 'post'], 'get_external_infos/{ticket}', [TicketController::class, 'get_external_infos'])->name('get_external_infos')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'get_history/{ticket}', [TicketController::class, 'get_history'])->name('get_history')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'toggle_comment/{comment}', [TicketController::class, 'toggle_comment'])->name('toggle_comment')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'post_comment/{ticket}', [TicketController::class, 'post_comment'])->name('post_comment')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'click_and_call', [TicketController::class, 'clickAndCall'])->name('click_and_call')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'all_tickets', [TicketController::class, 'all_tickets'])->name('all_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], 'user/{user}', [TicketController::class, 'user_tickets'])->name('user_tickets')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}', [TicketController::class, 'redirectTicket'])->name('ticket')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}/{thread}', [TicketController::class, 'ticket'])->name('ticket_thread')->can('read', Ticket::class);
        Route::match(['get', 'post'], '{ticket}/download_invoice/{order}', [TicketController::class, 'downloadInvoice'])->name('download_invoice')->can('read', Ticket::class);
    });
    Route::prefix('admin')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::match(['get', 'post'], 'new', [RoleController::class, 'edit'])->name('create_role')->can('edit', Role::class);
            Route::match(['get', 'post'], '{role}', [RoleController::class, 'edit'])->name('edit_role')->can('read', Role::class);
            Route::match(['get', 'post'], '', [RoleController::class, 'list'])->name('roles')->can('read', Role::class);
        });
        Route::prefix('users')->group(function () {
            Route::match(['get', 'post'], 'my_account', [UserController::class, 'my_account'])->name('my_account');
            Route::match(['get', 'post'], 'new', [UserController::class, 'edit'])->name('create_user')->can('edit', User::class);
            Route::match(['get', 'post'], '{user}', [UserController::class, 'edit'])->name('edit_user')->can('edit', User::class);
            Route::match(['get', 'post'], '', [UserController::class, 'list'])->name('users')->can('read', User::class);
        });
        Route::prefix('channel')->group(function () {
            Route::match(['get', 'post'], '', [ChannelController::class, 'list'])->name('channels')->can('read', Channel::class);
            Route::match(['get', 'post'], '{channel}', [ChannelController::class, 'edit'])->name('edit_channel')->can('edit', Channel::class);
        });
    });

    Route::prefix('configuration')->group(function () {
        Route::prefix('default_answer')->group(function () {
            Route::match(['get', 'post'], 'new', [DefaultAnswerController::class, 'edit'])->name('create_default_answer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}', [DefaultAnswerController::class, 'edit'])->name('edit_default_answer')->can('edit', ['defaultAnswer']);
            Route::match(['get', 'post'], '', [DefaultAnswerController::class, 'list'])->name('default_answers')->can('read', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}/delete', [DefaultAnswerController::class, 'delete'])->name('delete_default_answers')->can('edit', ['defaultAnswer']);
        });
        Route::prefix('revival')->group(function () {
            Route::match(['get', 'post'], 'new', [RevivalController::class, 'edit'])->name('create_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '{revival}', [RevivalController::class, 'edit'])->name('edit_revival')->can('edit', Revival::class);
            Route::match(['get', 'post'], '', [RevivalController::class, 'list'])->name('revival')->can('read', Revival::class);
            Route::match(['get', 'post'], '{revival}/delete', [RevivalController::class, 'delete'])->name('delete_revival')->can('edit', Revival::class);

        });
        Route::prefix('tags')->group(function () {
            Route::match(['get', 'post'], 'new', [TagsController::class, 'edit'])->name('create_tags')->can('edit', Tag::class);
            Route::match(['get', 'post'], '{tags}', [TagsController::class, 'edit'])->name('edit_tags')->can('edit', ['tags']);
            Route::match(['get', 'post'], '', [TagsController::class, 'list'])->name('tags')->can('read', Tag::class);
            Route::match(['get', 'post'], '{tags}/delete', [TagsController::class, 'delete'])->name('delete_tags')->can('edit', ['tags']);
        });
        Route::prefix('bot')->group(function () {
            Route::match(['get', 'post'], '', [BotController::class, 'home'])->name('bot_home')->can('bot_config');
            Route::match(['get', 'post'], 'acknowledgement', [BotController::class, 'acknowledgement'])->name('bot_acknowledgement')->can('bot_config');
            Route::match(['get', 'post'], 'invoice', [BotController::class, 'invoice'])->name('bot_invoice')->can('bot_config');
            Route::match(['get', 'post'], 'shippingInformation', [BotController::class, 'shipping_information'])->name('bot_shipping_information')->can('bot_config');
        });
        Route::prefix('sav_notes')->group(function () {
            Route::match(['get', 'post'], '', [SavNoteController::class, 'list'])->name('sav_notes')->can('read', SavNote::class);
            Route::match(['get', 'post'], 'new', [SavNoteController::class, 'edit'])->name('create_sav_note')->can('edit', SavNote::class);
            Route::match(['get', 'post'], '{savNote}/edit', [SavNoteController::class, 'edit'])->name('edit_sav_note')->can('edit', SavNote::class);
            Route::match(['get', 'post'], '{savNote}/delete', [SavNoteController::class, 'delete'])->name('delete_sav_note')->can('delete',SavNote::class);
            Route::match(['get', 'post'], '{savNote}', [SavNoteController::class, 'show'])->name('show_sav_note')->can('show',SavNote::class);
        });
        Route::prefix('misc')->group(function () {
            Route::match(['get', 'post'], '', [MiscController::class, 'home'])->name('misc_home')->can('misc_config');
            Route::match(['get', 'post'], 'variables', [MiscController::class, 'variables'])->name('variables_config')->can('misc_config');
            Route::match(['get', 'post'], 'incidents', [MiscController::class, 'incidents'])->name('incidents_config')->can('misc_config');
            Route::match(['get', 'post'], 'savprocess', [MiscController::class, 'savprocess'])->name('savprocess_config')->can('misc_config');
            Route::match(['get', 'post'], 'answer_offer_questions', [MiscController::class, 'answerOfferQuestions'])->name('answer_offer_questions_config')->can('misc_config');
            Route::match(['get', 'post'], 'miraklRefunds', [MiscController::class, 'miraklRefunds'])->name('mirakl_refunds_config')->can('misc_config');
            Route::match(['get', 'post'], 'closed_discussion', [MiscController::class, 'closedDiscussion'])->name('closed_discussion_config')->can('misc_config');
            Route::match(['get', 'post'], 'parcel_management', [MiscController::class, 'parcelManagement'])->name('parcel_management_config')->can('misc_config');
        });
    });
    // CALL AJAX
    Route::post('/ajaxTags', [TagsController::class, 'ajax_tags'])->name('ajaxShowTags');
    Route::post('/addTagList', [TagsController::class, 'newTagLine'])->name('addTagList');
    Route::post('/saveTicketThreadTags', [TicketController::class, 'saveThreadTags'])->name('saveTagOnticketThread');
    Route::post('/deleteTagList', [TicketController::class, 'delete_ThreadTagList'])->name('deleteTagList');
    Route::post('/deleteThreadTagOnTagList', [TicketController::class, 'delete_tag'])->name('deleteTagListOnThread');
    Route::post('/saveTicketThreadTags', [TicketController::class, 'saveTicketTags'])->name('saveTagOnticket');
    Route::post('/hastaglist', [TicketController::class, 'hasTagList'])->name('ticketHasTaglist');
    Route::post('/deleteThreadTagOnTagList', [TicketController::class, 'delete_tag'])->name('deleteTagListOnTicket');

    Route::post('/defaultAnswerListwithChannel', [RevivalController::class, 'listDefaultAnswer'])->name('list_default_answer');
    Route::post('/ajaxTagsRevival', [TagsController::class, 'ajax_tags_revival'])->name('ajaxShowTagsRevival');
    Route::post('/saveRevivalThread', [TicketController::class, 'save_revivalThread'])->name('saveRevivalThread');

    // Documentation
    Route::any('doc/agent/{path?}', [DocumentationController::class, 'agent'])->where('path', '.*')->name('agent_doc')->can('agent_doc');
    Route::any('doc/admin/{path?}', [DocumentationController::class, 'admin'])->where('path', '.*')->name('admin_doc')->can('admin_doc');
});
