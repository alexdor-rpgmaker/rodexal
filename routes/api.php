<?php /** @noinspection PhpUndefinedClassInspection */

use App\Http\Controllers\Api\V0\GameApiController;
use App\Http\Controllers\Api\V0\JukeboxMusicApiController;
use App\Http\Controllers\Api\V0\PreTestApiController;

// Cf https://laravel.com/docs/8.x/passport
// Validate access tokens on incoming requests
// Routes that should require a valid access token
// Route::middleware('auth:api')
//     ->get('/user', function (Request $request) {
//         return $request->user();
//     });

// No security
Route::prefix('v0')
    ->namespace('Api\V0')
    ->group(function () {
        Route::get('/games', [GameApiController::class, 'index']);
        Route::get('/pre_tests', [PreTestApiController::class, 'index']);
        Route::get('/musics', [JukeboxMusicApiController::class, 'index']);
    });
