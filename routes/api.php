<?php

use App\Http\Controllers\DependenceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\TechnicalController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/dependence')->group(function () {
        Route::get('/index', [DependenceController::class, 'index']);
        Route::post('/createorUpdate', [DependenceController::class, 'createorUpdate']);
        Route::delete('/delete', [DependenceController::class, 'destroy']);
    });

    Route::prefix('/procedure')->group(function () {
        Route::get('/index', [ProcedureController::class, 'index']);
        Route::post('/createorUpdate', [ProcedureController::class, 'createorUpdate']);
        Route::delete('/delete', [ProcedureController::class, 'destroy']);
    });
    Route::prefix('/permissions')->group(function () {
        Route::get('/index', [PermissionController::class, 'index']);
    });
    Route::prefix('/users')->group(function () {
        Route::get('/index', [UserController::class, 'index']);
        Route::post('/register', [UserController::class, 'register']);
        // Route::post('/login', [UserController::class, 'login']);
        Route::post('/logout', [UserController::class, 'logout']);

        Route::delete('/delete', [UserController::class, 'destroy']);
    });
    Route::prefix('/techinical')->group(function () {
        Route::get('/index', [TechnicalController::class, 'index']);
        Route::get('/report', [TechnicalController::class, 'report']);

        Route::post('/createorUpdate', [TechnicalController::class, 'createorUpdate']);
        Route::delete('/delete', [TechnicalController::class, 'destroy']);

    });
});
Route::post('/users/login', [UserController::class, 'login']);
Route::get('/hola', function () {
    return response()->json(['message' => '¡Hola!']);
});