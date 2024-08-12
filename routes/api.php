<?php

use App\Http\Controllers\SquareMeterController;
use Illuminate\Support\Facades\Route;

Route::get('/price-m2/zip-codes/{zip_code}/aggregate/{type}', [SquareMeterController::class, 'index']);
