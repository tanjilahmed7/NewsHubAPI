<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

// Define rate limits
RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

RateLimiter::for('personalized', function (Request $request) {
    return Limit::perMinute(30)->by(optional($request->user())->id ?: $request->ip());
});

// Auth Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password/email', [AuthController::class, 'sendPasswordResetLink']);
Route::post('password/reset', [AuthController::class, 'resetPassword']);

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    // Auth Routes
    Route::post('logout', [AuthController::class, 'logout']);

    // User Preferences Routes
    Route::post('preferences', [UserPreferenceController::class, 'store']);
    Route::get('preferences', [UserPreferenceController::class, 'show']);

    // Article Routes
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{article}', [ArticleController::class, 'show']);
    Route::get('/filtered', [ArticleController::class, 'filtered']);

    // Personalized News with custom rate limit
    Route::middleware('throttle:personalized')->get('/personalized-news', [ArticleController::class, 'personalizedNews']);
});
