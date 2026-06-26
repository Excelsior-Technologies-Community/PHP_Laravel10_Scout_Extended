<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);

Route::get('/product/create', [ProductController::class, 'create']);

Route::post('/product', [ProductController::class, 'store']);

Route::get('/product/{product}/edit', [ProductController::class, 'edit']);

Route::put('/product/{product}', [ProductController::class, 'update']);

Route::delete('/product/{product}', [ProductController::class, 'destroy']);

Route::delete('/search-history/clear', [ProductController::class, 'clearSearchHistory']);