<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ItemFoundController;
use App\Http\Controllers\Api\ItemLostController;
use App\Http\Controllers\Api\MatchReportController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->group(function () {


//     // Match Report Routes
//     Route::get('/matches', [MatchReportController::class, 'index']);
//     Route::get('/matches/{id}', [MatchReportController::class, 'show']);

//     Route::post('/matches/auto-match', [MatchReportController::class, 'autoMatch']);

//     Route::post('/matches/{id}/confirm', [MatchReportController::class, 'confirm']);
//     Route::post('/matches/{id}/reject', [MatchReportController::class, 'reject']);


//     // Lost Items
//     Route::apiResource('lost-items', ItemLostController::class);

//     // Found Items
//     Route::apiResource('found-items', ItemFoundController::class);

//     Route::get('/categories', [CategoryController::class, 'index']);
//     Route::get('/categories/{category}', [CategoryController::class, 'show']);
// });


Route::middleware(['dev.auth'])->group(function () {
    // Match Report Routes
    Route::get('/matches', [MatchReportController::class, 'index']);
    Route::get('/matches/{id}', [MatchReportController::class, 'show']);

    Route::post('/matches/auto-match', [MatchReportController::class, 'autoMatch']);

    Route::post('/matches/{id}/confirm', [MatchReportController::class, 'confirm']);
    Route::post('/matches/{id}/reject', [MatchReportController::class, 'reject']);

    Route::apiResource('items', ItemController::class);

    // Lost Items
    Route::apiResource('lost-items', ItemLostController::class);
    // Found Items
    Route::apiResource('found-items', ItemFoundController::class);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // profile
    Route::get('/profile/lost-items', [ProfileController::class, 'myLostItems']);
    Route::get('/profile/found-items', [ProfileController::class, 'myFoundItems']);
    Route::get('/profile/matches', [ProfileController::class, 'myMatches']);
    Route::get('/profile', [ProfileController::class, 'show']);
    // Route::put('/profile/{id}', [ProfileController::class, 'update']); 
    Route::get('/profile/stats', [ProfileController::class, 'stats']);
    // Route::post('/profile/password', [ProfileController::class, 'changePassword']);
});