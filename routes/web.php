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

Route::get(
    '/',
    [MainController::class, 'list']
)->name('app.main');

Route::get(
    '/upload',
    [MainController::class, 'upload']
)->name('app.upload');

Route::get(
    '/edit/{slug}',
    [MainController::class, 'edit']
)->name('app.edit');
