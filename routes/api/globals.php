<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\GlobalsController;

Route::get('/globals', [GlobalsController::class, 'show'])->name('globals.show');
