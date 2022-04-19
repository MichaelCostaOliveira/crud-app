<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/',[App\Http\Controllers\SpaController::class, 'index']);

Route::get('/spa/all', [App\Http\Controllers\SpaController::class, 'getIncident'])
    ->name('spa_all');

Route::get('/spa/delete/{id?}', [App\Http\Controllers\SpaController::class, 'destroy'])
    ->name('delete');

Route::get('/spa/edit/{id?}', [App\Http\Controllers\SpaController::class, 'edit'])
    ->name('edit');

Route::post('/spa/edit/{id?}', [App\Http\Controllers\SpaController::class, 'update'])
    ->name('update');

Route::post('/spa/store', [App\Http\Controllers\SpaController::class, 'store'])
    ->name('store');
