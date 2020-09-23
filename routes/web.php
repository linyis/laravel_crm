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
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home/{crm}/detail','HomeController@detail')->name('home.detail');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('crm', 'CrmController');
Route::get('/test', function()
{

    //return Storage::download('storage\1600759888.jpg');
    //echo public_path('images\1600759888.jpg');
});
Route::get('/line', 'LineLoginController@page');
Route::get('/line/callback', 'LineLoginController@LoginCallBack');

Route::get('/google', 'GoogleLoginController@page');
Route::get('/google/callback', 'GoogleLoginController@LoginCallBack');
Route::get('/google/sendcode', 'GoogleLoginController@sendcode');

