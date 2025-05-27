<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\MessageController;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\imageController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware(['auth:api'])->post('/messages', [MessageController::class, 'store']);


Route::get('/images/{path?}', [imageController::class, 'index'])->where('path', '.*');
