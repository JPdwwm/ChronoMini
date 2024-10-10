<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KidController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\RegisterController;

// Route publique pour l'enregistrement
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Groupe de routes protégées par le middleware 'auth:sanctum'
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);

    Route::get('/user', [ProfileController::class, 'profile']); // Cette route retourne les informations de l'utilisateur connecté
    Route::put('/user/update', [ProfileController::class, 'update']);
    Route::delete('/user/delete', [ProfileController::class, 'destroy']);

    Route::get('/kids', [KidController::class, 'index']);
    Route::get('/mykids', [KidController::class, 'showMyKids']);
    Route::post('/createkid', [KidController::class, 'createKid']);
    Route::put('/updatekid/{kid}', [KidController::class, 'updateKid']);
    Route::delete('/deletekid/{kid}', [KidController::class, 'deleteKid']);
});

