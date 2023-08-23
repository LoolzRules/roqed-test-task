<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

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

Route::controller(ApiController::class)
    ->group(function () {
        Route::get('/list', 'list');
        Route::post('/upload', 'upload')->name('api.upload');
        Route::post('/update/{slug}', 'update')->name('api.update');
        Route::delete('/delete/{slug}', 'delete')->name('api.delete');
    });
