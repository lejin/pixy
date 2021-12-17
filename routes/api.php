<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\TagController;
use App\Http\Middleware\RestrictPrivateDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

// protected route
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/images', [ImageController::class, 'store']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::get('/images', [ImageController::class, 'index']);
    Route::get('/images/{id}', [ImageController::class, 'show']);
    Route::put('/images/{id}', [ImageController::class, 'update']);
    Route::post('/upload', [FileController::class, 'upload']);
    Route::get('/download/private/{name}', [FileController::class, 'privateDownload']);
});

// filter for download
Route::middleware(['web', RestrictPrivateDownload::class])->group(function () {
    Route::get('/download/{name}', [FileController::class, 'none']);
});

//public routes
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/public', [ImageController::class, 'getAllPublicImages']);
Route::get('/public/{id}', [ImageController::class, 'showPublicImage']);
Route::get('/download/public/{name}', [FileController::class, 'publicDownload']);

