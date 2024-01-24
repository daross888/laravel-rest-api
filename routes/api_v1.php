<?php

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

Route::middleware(['treblle'])->group(function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
        Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');

        Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
    });


    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::group(['prefix' => 'todo', 'as' => 'todo.'], function () {
            Route::get('/', [\App\Http\Controllers\TodoController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\TodoController::class, 'show'])->name('show');
            Route::post('/', [\App\Http\Controllers\TodoController::class, 'create'])->name('create');
            Route::put('/{id}', [\App\Http\Controllers\TodoController::class, 'update'])->name('update');
            Route::put('/{id}/done', [\App\Http\Controllers\TodoController::class, 'done'])->name('done');
            Route::delete('/{id}', [\App\Http\Controllers\TodoController::class, 'delete'])->name('delete');
        });
    });
});
