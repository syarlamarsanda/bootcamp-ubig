<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ObservatorySlotController;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function(){
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/auth/logout',[AuthController::class, 'logout']);
        Route::post('/validation', [ValidationController::class, 'store']);
        Route::get('/validations',[ValidationController::class, 'index']);
        Route::get('/slots',[ObservatorySlotController::class,'index']);
        Route::get('/slots/{id}',[ObservatorySlotController::class,'show']);
    });
});