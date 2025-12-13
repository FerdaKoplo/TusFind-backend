<?php

use App\Http\Controllers\Api\MatchReportController;
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

Route::middleware('auth:sanctum')->group(function () {


    // Match Report Routes
    Route::get('/matches', [MatchReportController::class, 'index']);
    Route::get('/matches/{id}', [MatchReportController::class, 'show']);

    Route::post('/matches/auto-match', [MatchReportController::class, 'autoMatch']);

    Route::post('/matches/{id}/confirm', [MatchReportController::class, 'confirm']);
    Route::post('/matches/{id}/reject', [MatchReportController::class, 'reject']);

});
