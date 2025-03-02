<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\NewsFeedController;
use App\Http\Controllers\Api\V1\PreferenceController;
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

    // articles routes
    Route::group(['prefix' => 'articles/'], function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/{id}', [ArticleController::class, 'show']);
//        Route::post('/fetch', [ArticleController::class, 'fetchNews']);
    });

    // preferences routes
    Route::group(['prefix' => 'preferences/' ], function () {
        Route::get('/', [PreferenceController::class, 'index']);
        Route::get('/{id}', [PreferenceController::class, 'show']);
        Route::post('/store', [PreferenceController::class, 'store']);
    });

    Route::get('/news/feed', [NewsFeedController::class, 'newsFeed']);
});

