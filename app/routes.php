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

Route::get('about', 'HomeController@about');

Route::resource('news', 'NewsController');

Route::resource('csr_leaderboards', 'CsrLeaderboardsController');

Route::resource('top_ten', 'TopTenController');

Route::resource('compare', 'CompareController');