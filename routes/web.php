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

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PodcastEpisodeController;
use App\Http\Controllers\PreQualificationController;
use App\Http\Controllers\PreTestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use App\PodcastEpisode;

// Auth::routes();
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

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
    'index', 'store'
]);
Route::get('/jeux/vue', [GameController::class, 'vue'])->name('games-list-vue');
Route::get('/jeux/inscrire', [GameController::class, 'create'])->name('register-game');

Route::resource('dictionnaire', WordController::class)->except([
    'show'
])->parameters([
    'dictionnaire' => 'word'
]);

Route::resource('qcm', PreTestController::class)->except([
    'destroy'
])->parameters([
    'qcm' => 'pre_test'
]);

Route::resource('pre_qualifications', PreQualificationController::class)->except([
    'destroy'
])->parameters([
    'pre_qualifications' => 'pre_test'
]);

Route::redirect('/podcast/rss', PodcastEpisode::PODCAST_FEED_URL);
Route::get('/podcast/help', [PodcastEpisodeController::class, 'help'])->name('podcast.help');
Route::resource('podcast', PodcastEpisodeController::class)->only([
    'index', 'show'
])->parameters([
    'podcast' => 'podcast_episode'
]);
