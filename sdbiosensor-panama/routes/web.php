<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\SupportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,    'index'])->name('home');
Route::get('/productos', [ProductController::class, 'index'])->name('products.index');
Route::get('/productos/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/investigacion', [ResearchController::class, 'index'])->name('research.index');
Route::get('/centro-de-medios', [MediaController::class,   'index'])->name('media.index');
Route::get('/soporte', [SupportController::class, 'index'])->name('support.index');
