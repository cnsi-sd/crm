<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Settings\Permissions\RoleController;
use App\Http\Controllers\Settings\Permissions\UserController;
use App\Http\Controllers\Tickets\TicketController;
use App\Models\User\Role;
use App\Models\User\User;
use App\Models\Ticket\Ticket;


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

Route::middleware('checkActive')->group(function() {
    Route::prefix('tickets')->group(function() {
       Route::match(['get', 'post'], 'hide_comment/{comment}', [TicketController::class, 'hide_comment'])->name('hide_comment')->can('read', Ticket::class);
       Route::match(['get', 'post'], 'all_tickets', [TicketController::class, 'all_tickets'])->name('all_tickets')->can('read', Ticket::class);
       Route::match(['get', 'post'], 'user/{user}', [TicketController::class, 'user_tickets'])->name('user_tickets')->can('read', Ticket::class);
       Route::match(['get', 'post'], '{ticket}', [TicketController::class, 'redirectTicket'])->name('ticket')->can('read', Ticket::class);
       Route::match(['get', 'post'], '{ticket}/{thread}', [TicketController::class, 'ticket'])->name('ticket_thread')->can('read', Ticket::class);
    });
    Route::prefix('settings')->group(function () {
        Route::prefix('permissions')->group(function () {
            Route::prefix('roles')->group(function () {
                Route::match(['get', 'post'], 'new', [RoleController::class, 'edit'])->name('create_role')->can('edit', Role::class);
                Route::match(['get', 'post'], '{role}', [RoleController::class, 'edit'])->name('edit_role')->can('edit', Role::class);
                Route::match(['get', 'post'], '', [RoleController::class, 'list'])->name('roles')->can('edit', Role::class);
            });
            Route::prefix('users')->group(function () {
                Route::match(['get', 'post'], 'new', [UserController::class, 'edit'])->name('create_user')->can('edit', User::class);
                Route::match(['get', 'post'], '{user}', [UserController::class, 'edit'])->name('edit_user')->can('edit', User::class);
                Route::match(['get', 'post'], '', [UserController::class, 'list'])->name('users')->can('edit', User::class);
            });
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
