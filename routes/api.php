<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\TranslationSearchController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/translations', [TranslationSearchController::class, 'search']);
    Route::post('/translation/store', [TranslationController::class, 'store']);
    Route::put('/translation/update/{id}', [TranslationController::class, 'update']);
    Route::get('/translation/{id}', [TranslationController::class, 'show']);
});
