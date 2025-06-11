<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TreeController;
use App\Http\Controllers\Api\SpeciesController;
use App\Http\Controllers\Api\BuyerController;



Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/trees', [TreeController::class, 'store']);
Route::post('/species', [SpeciesController::class, 'store']);
Route::get('/species', [SpeciesController::class, 'index']);
Route::get('/trees', [TreeController::class, 'index']);
Route::post('/buyers', [BuyerController::class, 'store']);
Route::middleware('auth:sanctum')->get('/buyers', [BuyerController::class, 'index']);



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');