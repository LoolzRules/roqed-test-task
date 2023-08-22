<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserFileController;

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

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

Route::get('/', function () {
    return Inertia::render('FileList', [
        'fileList' => [],
        'pageNumber' => 1,
        'totalNumberOfPages' => 1
    ]);
});

Route::prefix('/api')
    ->controller(UserFileController::class)
    ->group(function () {
        Route::get('/list', 'list');
        Route::post('/upload', 'upload');
        Route::post('/update/{slug}', 'update');
        Route::delete('/delete/{slug}', 'delete');
    });

