<?php

use App\Http\Controllers\Api\Mobile\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile/admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });
});

// nanti nyusul: Route::prefix('mobile/siswa') buat AuthController siswa