<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(MainController::class)
    ->group(function () {
        Route::get('/', 'list')
            ->name('app.main');

        Route::get('/upload', 'upload')
            ->name('app.upload');

        Route::get('/edit/{slug}', 'edit')
            ->name('app.edit');
    });
