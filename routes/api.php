<?php

use App\Http\Controllers\Api\ParcelManagementApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/savprocess/close/{token}/{ticket}', [TicketApiController::class, 'savProcessComplete']);
Route::get('/parcelmanagement/notify/ticket_id/{ticket}/token/{token}/comment/{comment}/tag/{tag}', [ParcelManagementApiController::class, 'notify']);
