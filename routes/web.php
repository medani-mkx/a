<?php


        Route::get('login', 'AuthorizationController@index')->name('login');
        Route::post('login', 'Auth\LoginController@login');
        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        
        
Route::get('/', 'AuthorizationController@index')->name('/');

// Logged in
Route::group(['middleware' => ['web','auth']], function () {
    Route::get('angebote', 'OffersController@index')->name('angebote');
    Route::get('home', 'HomeController@index'); // LÖSCHEN IRGENDWANN
});






