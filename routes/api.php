<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\API\MessageController;
use Illuminate\Support\Facades\File;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::middleware(['auth:api'])->post('/messages', [MessageController::class, 'store']);

Route::get('/images', function () {
    $files = File::files(storage_path('app/public/images'));

    $images = collect($files)->map(function ($file) {
        return [
            'name' => $file->getFilename(),
            'url' => asset('storage/images/' . $file->getFilename()),
        ];
    });

    return response()->json($images);
});
