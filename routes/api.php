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

Route::get('/images/{path?}', function ($path = '') {
    $fullPath = storage_path('app/public/images/' . $path);

    if (!is_dir($fullPath)) {
        return response()->json(['error' => 'پوشه پیدا نشد'], 404);
    }

    $files = File::files($fullPath);

    $images = collect($files)->map(function ($file) use ($path) {
        return [
            'name' => $file->getFilename(),
            'url' => asset('storage/images/' . trim($path, '/') . '/' . $file->getFilename()),
        ];
    });

    return response()->json($images);
})->where('path', '.*');
