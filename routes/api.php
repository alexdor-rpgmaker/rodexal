<?php /** @noinspection PhpUndefinedClassInspection */

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

use App\Http\Controllers\Api\V0\GameApiController;
use App\Http\Controllers\Api\V0\PreTestApiController;
use App\Http\Controllers\Api\V0\JukeboxMusicApiController;

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
        Route::get('/qcm', [PreTestApiController::class, 'index']);

        Route::get('/games', [GameApiController::class, 'index']);

        Route::get('/musics', [JukeboxMusicApiController::class, 'index']);
    });
