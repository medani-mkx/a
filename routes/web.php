<?php

Route::get(     '/',            'AuthorizationController@index')        ->name('/');
Route::get(     'login',        'AuthorizationController@index')        ->name('login');
Route::post(    'logout',       'AuthorizationController@logout')       ->name('logout');

Route::group(['middleware' => ['web','auth']], function () {
    Route::get(     'offers',                   'OffersController@index')           ->name('offers');
    Route::post(    'offers',                   'OffersController@store');
    Route::get(     'offers/{id}',              'OffersController@show');

    Route::put(     'tasks/{id}',               'TasksController@update');
    Route::get(     'tasks/import/offer/{id}',  'TasksController@import');

    Route::get(     'texts',                    'OfferTextsController@index')       ->name('texts');

    Route::get(     'customers',                'CustomersController@index')        ->name('customers');

    Route::get(     'settings',                 'SettingsController@index')         ->name('settings');

});
