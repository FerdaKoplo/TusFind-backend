<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
});

Route::get('/', function () {
    return view('welcome');
});
