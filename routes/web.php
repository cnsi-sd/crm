<?php

use App\Http\Controllers\Configuration\DefaultAnswerController;
use App\Http\Controllers\Configuration\RevivalController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Settings\Permissions\RoleController;
use App\Http\Controllers\Settings\Permissions\UserController;
use App\Http\Controllers\Tickets\TicketController;
use App\Models\Channel\DefaultAnswer;
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

Route::get('/home', function () {
    return redirect('/index');
})->name('home');

require __DIR__ . '/auth.php';

Route::middleware('checkActive')->group(function () {
    Route::prefix('tickets')->group(function () {
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
});

Route::middleware('checkActive')->group(function () {
    Route::prefix('configuration')->group(function () {
        Route::prefix('default_answer')->group(function () {
            Route::match(['get', 'post'], 'new', [DefaultAnswerController::class, 'edit'])->name('create_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}', [DefaultAnswerController::class, 'edit'])->name('edit_defaultAnswer')->can('edit', DefaultAnswer::class);
            Route::match(['get', 'post'], '', [DefaultAnswerController::class, 'list'])->name('defaultAnswers')->can('read', DefaultAnswer::class);
            Route::match(['get', 'post'], '{defaultAnswer}/delete', [DefaultAnswerController::class, 'delete'])->name('delete_defaultAnswers')->can('edit', DefaultAnswer::class);
        });

        Route::prefix('revival')->group(function () {
            Route::match(['get', 'post'], 'new', [RevivalController::class, 'edit'])->name('create_revival');
            Route::match(['get', 'post'], '{revival}', [RevivalController::class, 'edit'])->name('edit_revival');
            Route::match(['get', 'post'], '', [RevivalController::class, 'list'])->name('revival');
            Route::match(['get', 'post'], '{revival}/delete', [RevivalController::class, 'delete'])->name('delete_revival');
        });
    });
});


Route::group(['prefix' => '/'], function () {
    Route::get('', function () {
        return redirect('/login');
    })->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
