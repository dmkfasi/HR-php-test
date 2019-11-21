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

Route::get('/', function() {
    return view('layouts.app');
});

// Weather Card view
Route::get('/weather', 'WeatherController@index')->name('weather');

// Orders REST routes
Route::get('/orders', 'OrderController@index')->name('orders');
Route::get('/orders/{id}/edit', 'OrderController@edit')->name('orders/edit');
Route::put('/orders/{id}', 'OrderController@update')->name('orders/update');
