<?php /** @noinspection PhpUndefinedClassInspection */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth::routes();
Route::post('logout', [Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect(env('FORMER_APP_URL'));
});

Route::get('/accueil', [HomeController::class, 'index'])->name('home');

Route::get('/oauth/callback', [UserController::class, 'callback'])->name('oauth');

// Route::get('/liste-des-membres', 'UserController@index')->name('users');

Route::resource('jukebox', MusicController::class)->only([
    'index'
])->parameters([
    'jukebox' => 'music'
]);

Route::resource('jeux', GameController::class)->only([
    'index'
]);
Route::get('/jeux/vue', [GameController::class, 'vue'])->name('games-list-vue');

Route::resource('dictionnaire', WordController::class)->except([
    'show'
])->parameters([
    'dictionnaire' => 'word'
]);

Route::resource('qcm', PreTestController::class)->except([
    'index', 'destroy'
])->parameters([
    'qcm' => 'pre_test'
]);
