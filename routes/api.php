<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ImageController;

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes with auth:api middleware
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('/events', [EventController::class, 'store']);
});

// Public routes
Route::get('/folder-tree', [ImageController::class, 'index']);
