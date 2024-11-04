<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\KidController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RecordController;
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
    Route::get('/mykid/{kid}', [KidController::class, 'showOneKid']);
    Route::post('/createkid', [KidController::class, 'createKid']);
    Route::put('/updatekid/{kid}', [KidController::class, 'updateKid']);
    Route::delete('/deletekid/{kid}', [KidController::class, 'deleteKid']);

    Route::get('/records', [RecordController::class, 'index']);
    Route::post('/{kid}/record/start', [RecordController::class, 'startRecording']);
    Route::post('/{kid}/record/stop', [RecordController::class, 'stopRecording']);
    Route::get('/myrecords', [RecordController::class, 'showMyRecords']);
    Route::get('/myrecord/{record}', [RecordController::class, 'showOneRecord']);

    Route::post('/messages', [MessageController::class, 'sendMessage']);
    Route::get('/messages', [MessageController::class, 'getMyMessages']);
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead']);

});

