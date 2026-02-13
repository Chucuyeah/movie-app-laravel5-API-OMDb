<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->middleware('web');

Route::get('/login', 'AuthController@showLogin')->middleware('web');
Route::post('/login', 'AuthController@login')->middleware('web');
Route::get('/logout', 'AuthController@logout')->middleware('web');
Route::get('/language/{locale}', 'AuthController@setLanguage')->middleware('web')->name('language.set');

Route::get('/movies', 'MovieController@index')->middleware('web');
Route::get('/movies/load-more', 'MovieController@loadMore')->middleware('web')->name('movies.loadMore');
Route::get('/movies/{id}', 'MovieController@detail')->middleware('web');


// Favorites routes
Route::get('/favorites', 'FavoriteController@index')->middleware('web');
Route::post('/favorites/add', 'FavoriteController@add')->middleware('web');
Route::post('/favorites/remove/{id}', 'FavoriteController@remove')->middleware('web');
Route::post('/favorites/toggle', 'FavoriteController@toggle')->middleware('web');

