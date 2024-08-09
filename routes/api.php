<?php

use App\Http\Controllers\SquareMeterController;
use Illuminate\Support\Facades\Route;

Route::get('/price-m2/zip-codes/{postal_code}/aggregate/{type}', [SquareMeterController::class, 'index']);
