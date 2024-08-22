<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MovementController;

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth:sanctum', config('jetstream.auth_session'),'verified',])
->group(function () {
    Route::get('/dashboard', function () { return Inertia::render('Dashboard'); })->name('dashboard');
    Route::resource('customers', CustomerController::class);
    Route::get('/json/customers','App\Http\Controllers\CustomerController@listCustomers');
    Route::resource('products', ProductController::class);
    Route::get('/json/products','App\Http\Controllers\ProductController@list');
    Route::resource('movements', MovementController::class);
    Route::get('/json/zip-code/{id}','App\Http\Controllers\ZipCodeController@getZipCode');

});
