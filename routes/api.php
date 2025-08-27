<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\SpeciesController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DiseaseController;
use App\Http\Controllers\Api\HealthController;

// General
Route::post('/login', [AuthController::class, 'login']);

// Species Controller
Route::middleware('auth:sanctum')->post('/species', [SpeciesController::class, 'store']);
Route::middleware('auth:sanctum')->get('/species', [SpeciesController::class, 'index']);
Route::middleware('auth:sanctum')->put('/species/{id}', [SpeciesController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/species/{id}', [SpeciesController::class, 'destroy']);

// // User Controller
// Route::middleware('auth:sanctum')->post('/users', [UserController::class, 'store']);
// Route::middleware('auth:sanctum')->get('/users', [UserController::class, 'index']);
// Route::middleware('auth:sanctum')->put('/users/{id}', [UserController::class, 'update']);
// Route::middleware('auth:sanctum')->delete('/users/{id}', [UserController::class, 'destroy']);

// Tree Controller
Route::middleware('auth:sanctum')->post('/trees', [TreeController::class, 'store']);
Route::middleware('auth:sanctum')->get('/trees', [TreeController::class, 'index']);
Route::middleware('auth:sanctum')->get('/trees/{id}', [TreeController::class, 'show']);
Route::middleware('auth:sanctum')->put('/trees/{id}', [TreeController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/trees/{id}', [TreeController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/trees/uuid/{uuid}', [TreeController::class, 'showByUuid']);
Route::middleware('auth:sanctum')->put('/trees/location/{id}', [TreeController::class, 'updateTreeLocation']);

// Disease Controller
Route::middleware('auth:sanctum')->post('/diseases', [DiseaseController::class, 'store']);
Route::middleware('auth:sanctum')->get('/diseases', [DiseaseController::class, 'index']);
Route::middleware('auth:sanctum')->put('/diseases/{id}', [DiseaseController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/diseases/{id}', [DiseaseController::class, 'destroy']);

// Health Controller
Route::middleware('auth:sanctum')->post('/health-records', [HealthController::class, 'store']);
Route::middleware('auth:sanctum')->get('/health-records', [HealthController::class, 'index']);
Route::middleware('auth:sanctum')->put('/health-records/{id}', [HealthController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/health-records/{id}', [HealthController::class, 'destroy']);
Route::get('/trees/{uuid}/health-records', [HealthController::class, 'getByTree']);


// // Buyer Controller
// Route::middleware('auth:sanctum')->post('/buyers', [BuyerController::class, 'store']);
// Route::middleware('auth:sanctum')->get('/buyers', [BuyerController::class, 'index']);
// Route::middleware('auth:sanctum')->put('/buyers/{id}', [BuyerController::class, 'update']);
// Route::middleware('auth:sanctum')->delete('/buyers/{id}', [BuyerController::class, 'destroy']);
// Route::middleware('auth:sanctum')->get('/buyers/{id}', [BuyerController::class, 'show']);