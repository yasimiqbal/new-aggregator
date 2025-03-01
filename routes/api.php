<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['prefix' => 'password/', 'as' => 'password.'], function () {
    Route::post('/forgot', [ForgetPasswordController::class, 'forgotPassword'])->name('forgot');
    Route::post('/reset', [ResetPasswordController::class, 'resetPassword'])->name('reset');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

