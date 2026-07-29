<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\OwnerKostController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::prefix('auth')->group(function () {

    // Register
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::post('/login', [AuthController::class, 'login']);

});


Route::middleware('auth:sanctum')
    ->prefix('auth')
    ->group(function () {

        // Logout
        Route::post('/logout', [AuthController::class, 'logout']);

        // Current user
        Route::get('/me', [AuthController::class, 'me']);

    });

//Public Kost Routes
Route::prefix('kosts')->group(function () {

    // Search kost
    Route::get('/', [KostController::class, 'index']);

    // Detail kost
    Route::get('/{kost}', [KostController::class, 'show']);

});


// Owner Kost Routes
Route::middleware('auth:sanctum')
    ->prefix('owner')
    ->group(function () {

        Route::prefix('kosts')->group(function () {

            // List owner kost
            Route::get('/', [OwnerKostController::class, 'index']);

            // Create kost
            Route::post('/', [OwnerKostController::class, 'store']);

            // Update kost
            Route::put('/{kost}', [OwnerKostController::class, 'update']);

            // Delete kost
            Route::delete('/{kost}', [OwnerKostController::class, 'destroy']);

        });

    });