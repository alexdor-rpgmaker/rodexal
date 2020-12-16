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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v0')
    ->namespace('Api\V0')
    ->group(function () {
        Route::get('/qcm', [PreTestApiController::class, 'index']);

        Route::get('/games', [GameApiController::class, 'index']);
    });
