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

Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::get('/accueil', 'HomeController@index')->name('home');

Route::get('/liste-des-membres', 'UserController@index')->name('users');

Route::resource('dictionnaire', 'WordController')->except([
    'show', 'destroy'
]);
