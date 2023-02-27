<?php

use App\Http\Controllers\Auth\AuthenticatedSessionAbstractController;
use App\Http\Controllers\Auth\ConfirmablePasswordAbstractController;
use App\Http\Controllers\Auth\NewPasswordAbstractController;
use App\Http\Controllers\Auth\PasswordResetLinkAbstractController;
use App\Http\Controllers\Auth\RegisteredUserAbstractController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticatedSessionAbstractController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionAbstractController::class, 'store'])
                ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkAbstractController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkAbstractController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordAbstractController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordAbstractController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/confirm-password', [ConfirmablePasswordAbstractController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordAbstractController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionAbstractController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
