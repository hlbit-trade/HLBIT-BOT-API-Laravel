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

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/trade', 'HomeController@trade')->name('trade');
Route::get('/order-list', 'HomeController@history')->name('history');
Route::get('/log', 'HomeController@showLog')->name('log');

Route::post('/cancel-order','HomeController@cancelOrder')->name('cancel-order');
Route::post('/trade','HomeController@settingSave')->name('setting.save');