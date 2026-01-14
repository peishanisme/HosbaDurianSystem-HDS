<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BuyerController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FruitController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\DiseaseController;
use App\Http\Controllers\Api\SpeciesController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Api\AgrochemicalController;
use App\Http\Controllers\Api\TreeGrowthLogController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// General
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/check-old-password', [ForgotPasswordController::class, 'checkOldPassword']);

// Species Controller
Route::middleware('auth:sanctum')->post('/species', [SpeciesController::class, 'store']);
Route::middleware('auth:sanctum')->get('/species', [SpeciesController::class, 'index']);
Route::middleware('auth:sanctum')->put('/species/{id}', [SpeciesController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/species/{id}', [SpeciesController::class, 'destroy']);

// // User Controller
Route::get('/check-phone/{phone}', [UserController::class, 'checkPhone']);
Route::post('/verify-otp', [UserController::class, 'verifyOtp']);
Route::post('/reset-password', [UserController::class, 'resetPassword']);

// Tree Controller
Route::middleware('auth:sanctum')->post('/trees', [TreeController::class, 'store']);
Route::middleware('auth:sanctum')->get('/trees', [TreeController::class, 'index']);
Route::middleware('auth:sanctum')->get('/trees/{id}', [TreeController::class, 'show']);
Route::middleware('auth:sanctum')->put('/trees/{id}', [TreeController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/trees/{id}', [TreeController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/trees/uuid/{uuid}', [TreeController::class, 'showByUuid']);
Route::middleware('auth:sanctum')->put('/trees/location/{uuid}', [TreeController::class, 'updateTreeLocation']);
Route::middleware('auth:sanctum')->get('/trees/{uuid}/flowering-period', [TreeController::class, 'getFloweringPeriod']);

// Disease Controller
Route::middleware('auth:sanctum')->post('/diseases', [DiseaseController::class, 'store']);
Route::middleware('auth:sanctum')->get('/diseases', [DiseaseController::class, 'index']);
Route::middleware('auth:sanctum')->put('/diseases/{id}', [DiseaseController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/diseases/{id}', [DiseaseController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/diseases/{diseaseId}/trees', [HealthController::class, 'getTreesByDisease']);

// Health Controller
Route::middleware('auth:sanctum')->post('/health-records', [HealthController::class, 'store']);
Route::middleware('auth:sanctum')->get('/health-records', [HealthController::class, 'index']);
Route::middleware('auth:sanctum')->put('/health-records/{id}', [HealthController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/health-records/{id}', [HealthController::class, 'destroy']);
Route::get('/trees/{uuid}/health-records', [HealthController::class, 'getByTree']);

//Fruit Controller
Route::middleware('auth:sanctum')->post('/fruit', [FruitController::class, 'store']);
Route::middleware('auth:sanctum')->get('/fruit', [FruitController::class, 'index']);
Route::middleware('auth:sanctum')->put('/fruit/{uuid}', [FruitController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/fruit/{uuid}', [FruitController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/harvest-events/{harvestUuid}/fruits', [FruitController::class, 'getByHarvestEvent']);

// Event Controller
Route::middleware('auth:sanctum')->get('/harvest-events', [EventController::class, 'index']);
Route::get('/trees/{uuid}/harvest-events', [EventController::class, 'getTreeHarvestEvents']);

// Agrochemical Controller
Route::middleware('auth:sanctum')->post('/agrochemicals', [AgrochemicalController::class, 'store']);
Route::middleware('auth:sanctum')->get('/trees/{uuid}/agrochemicals', [AgrochemicalController::class, 'getByTree']);
Route::middleware('auth:sanctum')->get('/agrochemicals', [AgrochemicalController::class, 'index']);
Route::middleware('auth:sanctum')->put('/agrochemicals/{uuid}', [AgrochemicalController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/agrochemicals/{uuid}', [AgrochemicalController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/agrochemical-records', [AgrochemicalController::class, 'getAllAgroRecords']);
Route::middleware('auth:sanctum')->get('/agrochemicals/{agrochemicalUuid}/trees', [AgrochemicalController::class, 'getTreesByAgrochemical']);
Route::middleware('auth:sanctum')->get('/agrochemicals/available/stock', [AgrochemicalController::class, 'getAvailableStock']);
Route::middleware('auth:sanctum')->post('/agrochemical-stock-movements', [AgrochemicalController::class, 'storeStockMovement']);

// Tree Growth Log Controller
Route::middleware('auth:sanctum')->post('/tree-growth-logs', [TreeGrowthLogController::class, 'store']);
Route::middleware('auth:sanctum')->get('/tree-growth-logs', [TreeGrowthLogController::class, 'index']);
Route::middleware('auth:sanctum')->get('/tree-growth-logs/{uuid}', [TreeGrowthLogController::class, 'getByTreeUuid']);