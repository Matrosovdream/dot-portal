<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Search\SearchController;

Route::prefix('search')->name('search.')->group(function () {
    Route::get('global', [SearchController::class, 'global'])->name('global');
});
