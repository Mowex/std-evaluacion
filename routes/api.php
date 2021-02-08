<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TurnController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::middleware('auth:api')->post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


Route::middleware('auth:api')->prefix('movies')->group(function () {
    Route::get('', [MovieController::class, 'index'])->name('movie.index');
    Route::post('/store', [MovieController::class, 'store'])->name('movie.create');
    Route::post('/{id}', [MovieController::class, 'update'])->name('movie.update');
    Route::get('/{id}/edit', [MovieController::class, 'edit'])->name('movie.edit');
    Route::get('/{id}/delete', [MovieController::class, 'destroy'])->name('movie.delete');
    Route::post('/{id}/assign', [MovieController::class, 'assignTurn'])->name('movie.assignturn');
});

Route::middleware('auth:api')->prefix('turns')->group(function () {
    Route::get('', [TurnController::class, 'index'])->name('turn.index');
    Route::get('{id_movie}/without-assign', [TurnController::class, 'withOutAssign'])->name('turn.index');
    Route::post('/store', [TurnController::class, 'store'])->name('turn.create');
    Route::post('/{id}', [TurnController::class, 'update'])->name('turn.update');
    Route::get('/{id}/edit', [TurnController::class, 'edit'])->name('turn.edit');
    Route::get('/{id}/delete', [TurnController::class, 'destroy'])->name('turn.delete');
});

Route::middleware('auth:api')->prefix('dashboard')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('dashboard.index');
});


