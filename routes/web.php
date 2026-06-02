<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Server-side endpoints (must win over the SPA catch-all)
|--------------------------------------------------------------------------
| Declared BEFORE the catch-all and ALSO excluded from the catch-all regex.
| Only file streaming stays server-routed; everything else is the SPA + the
| /api/v1 JSON API. The legacy Metronic route files (auth.php, user.php,
| dashboard.php) are intentionally NOT required anymore — they remain on
| disk, unrouted, until they are deleted in a later cleanup.
*/

// File downloads — streamed by the server, never owned by vue-router.
Route::get('file/{file}', [FileController::class, 'download'])->name('file.download');
Route::get('file/show/{file_id}', [FileController::class, 'show'])->name('file.show');

/*
|--------------------------------------------------------------------------
| Vue SPA catch-all (GET + HEAD only)
|--------------------------------------------------------------------------
| Serves resources/views/spa.blade.php for every path NOT excluded by the
| regex below. vue-router owns client-side routing; auth is Sanctum stateful
| (cookie/session). The regex keeps the API, Sanctum's CSRF cookie, the
| health check, static asset/storage paths, and file downloads out of the
| SPA's hands. Route::view (no closure) keeps `php artisan route:cache`
| working in production.
*/
Route::view('/{any?}', 'spa')
    ->where('any', '^(?!api(/|$)|sanctum(/|$)|up$|storage(/|$)|build(/|$)|file(/|$)).*$')
    ->name('spa');
