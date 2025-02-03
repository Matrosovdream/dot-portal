<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\IndexController;
use App\Http\Controllers\User\CountryController;
use App\Http\Controllers\User\ArticleController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\SiteGlobalsController;
use App\Http\Controllers\FileController;


// Index page
Route::get('/', [IndexController::class, 'index'])->name('web.index');

// Lg page
Route::get('/lg/', [IndexController::class, 'lg'])->name('web.lg');




