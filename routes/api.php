<?php

use App\Http\Controllers\SquareMeterController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::get('/price-m2/zip-codes/{zip_code}/aggregate/{type}', [SquareMeterController::class, 'index']);
Route::get('/testing', [TestController::class, 'index']);
