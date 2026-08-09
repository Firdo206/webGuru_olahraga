<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthSiswaController;
use App\Http\Controllers\Api\SesiTesController;
use App\Http\Controllers\Api\HasilTesController;

Route::post('/siswa/login', [AuthSiswaController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('siswa')->group(function () {
    Route::post('/logout', [AuthSiswaController::class, 'logout']);
    Route::get('/me', [AuthSiswaController::class, 'me']);

    Route::get('/sesi-tes', [SesiTesController::class, 'index']);
    Route::get('/sesi-tes/{sesiTes}', [SesiTesController::class, 'show']);
    Route::post('/sesi-tes/{sesiTes}/hasil', [HasilTesController::class, 'store']);
    Route::get('/riwayat-tes', [HasilTesController::class, 'riwayat']);
});