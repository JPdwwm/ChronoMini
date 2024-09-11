<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RegisterController;

// Route publique pour l'enregistrement
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Groupe de routes protégées par le middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
});

