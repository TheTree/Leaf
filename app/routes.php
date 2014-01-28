<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

// homepage POST routes
Route::post('/', 'HomeController@addGamertag');

// halo 4 related links
Route::get('h4/record/{gamertag}', 'ProfileController@index');

Route::get('about', 'HomeController@about');

Route::resource('news', 'NewsController');

Route::any('csr_leaderboards', 'CsrLeaderboardsController@index');

Route::any('top_ten', 'TopTenController@index');

Route::any('compare', 'CompareController@index');