<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\ECPayOrderMail;

use App\Crm;
use App\Category;

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
Route::resource('order', 'OrderController');

Route::match(['get', 'post'], '/order/{order}/checkout', 'Ordercontroller@checkout')->name('order.checkout');


Route::get('/page', function()
{
    echo phpinfo();
//    return view('test');
});
Route::get('/test', function()
{
    echo env('QUEUE_DRIVER', 'redis');
//    echo route('order.test');
//    echo str_replace('-','/',now());
});
Route::get('/testemail/{email?}',function($email = null) {
    Mail::to($email ?? 'linyis@gmail.com')->queue(new ECPayOrderMail(null, null, 100 ));
});

Route::get('/line', 'LineLoginController@page');
Route::get('/line/callback', 'LineLoginController@LoginCallBack');

Route::get('/facebook', 'FacebookLoginController@page');
Route::get('/facebook/callback', 'FacebookLoginController@LoginCallBack');

Route::get('/google', 'GoogleLoginController@page');
Route::get('/google/callback', 'GoogleLoginController@LoginCallBack');
Route::get('/google/sendcode', 'GoogleLoginController@sendcode');
