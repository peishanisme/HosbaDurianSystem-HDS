<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\SpeciesController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/trees', [TreeController::class, 'store']);
Route::get('/trees', [TreeController::class, 'index']);
Route::post('/species', [SpeciesController::class, 'store']);
Route::get('/species', [SpeciesController::class, 'index']);
Route::post('/buyers', [BuyerController::class, 'store']);
Route::middleware('auth:sanctum')->get('/buyers', [BuyerController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);
Route::middleware('auth:sanctum')->get('/trees/{uuid}', [TreeController::class, 'show']);

