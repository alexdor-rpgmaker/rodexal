<?php

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
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/', function () {
    return redirect(env('FORMER_APP_URL'));
});

Route::get('/accueil', 'HomeController@index')->name('home');

Route::get('/oauth/callback', 'UserController@callback');

// Route::get('/liste-des-membres', 'UserController@index')->name('users');

Route::resource('dictionnaire', 'WordController')->except([
    'show'
]);
