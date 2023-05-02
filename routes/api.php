<?php

use App\Http\Controllers\Api\AvisDeSouffranceApiController;
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

Route::prefix('parcelmanagement')->group(function() {
    Route::get('notify/ticket_id/{ticket}/token/{token}/comment/{comment}/tag/{tag}', [ParcelManagementApiController::class, 'notify']);
    Route::get('has_been_notified/token/{token}/ticket_id/{ticket}/comment/{comment}', [ParcelManagementApiController::class, 'has_been_notified']);
});

Route::post('avis_de_souffrance', [AvisDeSouffranceApiController::class, 'add_avis_de_souffrance']);
