<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MealController;
use Illuminate\Support\Facades\Route;

// ─── API v1 ───────────────────────────────────────────────────────────────────
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public auth routes
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/me', [AuthController::class, 'me'])->name('me');
        });

        // Meals
        Route::apiResource('meals', MealController::class);
        Route::get('/meals/daily/summary', [MealController::class, 'daily'])->name('meals.daily');
        Route::get('/dashboard/stats', [MealController::class, 'stats'])->name('dashboard.stats');
    });
});

// Health check
Route::get('/health', fn() => response()->json([
    'status' => 'ok',
    'timestamp' => now()->toIso8601String(),
    'version' => '1.0.0',
]));
