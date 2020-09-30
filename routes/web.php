<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestAmazonSes;
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
Route::match(['get', 'post'], '/order/data', 'Ordercontroller@data')->name('order.data');
Route::get('/testemail', function()
{
//    Mail::to('linyis@gmail.com')->queue(new ECPayOrderMail(null, null, 10 ));
//    Mail::to('linyis@gmail.com')->send(new ECPayOrderMail(null, null, 10 ));
//    Mail::to('linyis@gmail.com')->send(new TestAmazonSes('It works!'));
});

Route::get('/page', function()
{
    echo phpinfo();
//    return view('test');
});
Route::get('/test', function()
{
//    echo route('order.test');
    echo str_replace('-','/',now());
});
Route::get('/testform', 'OrderController@test')->name("order.test");

Route::get('/line', 'LineLoginController@page');
Route::get('/line/callback', 'LineLoginController@LoginCallBack');

Route::get('/facebook', 'FacebookLoginController@page');
Route::get('/facebook/callback', 'FacebookLoginController@LoginCallBack');

Route::get('/google', 'GoogleLoginController@page');
Route::get('/google/callback', 'GoogleLoginController@LoginCallBack');
Route::get('/google/sendcode', 'GoogleLoginController@sendcode');


// Route::group(['prefix'=>'login/social','middleware'=>['guest']],function() {
//     Route::get('{provider}/redirect', [
//         'as'=>'social.redirect',
//         'user' => 'SocialController@getSocialRedirect'

//     ]);
//     Route::get('{provider}/callback',[
//         'as'=>'social.handel'
//     ]);

// });


function renderParent($node) {

    echo "<b>{$node->name}</b>";
    if ($node->parent) {
       echo "<-";
       renderParent($node->parent);
    }
}
