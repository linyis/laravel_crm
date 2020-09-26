<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
//use App\Category;
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
Route::get('/page', function()
{
    return view('test');

});
Route::get('/test', function()
{
    $crm = Crm::find(29);

    $categories = $crm->categories;

    foreach($categories as $category) {
        renderParent($category);
    }
});
Route::get('/line', 'LineLoginController@page');
Route::get('/line/callback', 'LineLoginController@LoginCallBack');

Route::get('/facebook', 'FacebookLoginController@page');
Route::get('/facebook/callback', 'FacebookLoginController@LoginCallBack');

Route::get('/google', 'GoogleLoginController@page');
Route::get('/google/callback', 'GoogleLoginController@LoginCallBack');
Route::get('/google/sendcode', 'GoogleLoginController@sendcode');

Route::get('/social/{provider}','SocialController@page');

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
