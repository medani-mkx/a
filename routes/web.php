<?php

Route::get('/', 'AuthorizationController@index')->name('/');

// Logged in
Route::group(['middleware' => ['web','oauth']], function () {
    Route::get('/test', function() {
        return 'logged in';
    });
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
