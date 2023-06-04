<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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


Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\HomeController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\HomeController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [App\Http\Controllers\HomeController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\HomeController::class, 'update'])->name('update');
    Route::post('/delete/{id}', [App\Http\Controllers\HomeController::class, 'delete'])->name('delete');
});





