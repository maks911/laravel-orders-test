<?php

use \App\Http\Controllers\WeatherController;
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

Route::get('/', 'WeatherController@index')->name('home');
Route::get('/orders/', 'OrderController@list')->name('list');
Route::get('/orders/{id}', 'OrderController@edit')->name('edit');
Route::post('/orders/{id}', 'OrderController@update')->name('update');
