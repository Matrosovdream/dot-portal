<?php

use App\Http\Controllers\Api\V1\Admin\Users\UserOptionController;
use Illuminate\Support\Facades\Route;

// Owner-filter picker for the Operations listings. Admin + manager (the parent
// group), unlike the admin-only users CRUD.
Route::get('user-options', [UserOptionController::class, 'index'])->name('user-options.index');
