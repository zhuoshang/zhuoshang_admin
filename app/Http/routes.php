<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');
//
//Route::get('home', 'HomeController@index');
//
//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);

Route::post('userAdd',array('before'=>'loginCheck','uses'=>'UserController@userAdd'));

Route::get('userList',array('before'=>'loginCheck','uses'=>'UserController@userList'));

Route::get('lockUserList',array('before'=>'loginCheck','uses'=>'UserController@lockUserList'));

Route::get('mainInform',array('before'=>'loginCheck','uses'=>'informController@informGet'));

Route::post('adminAdd',array('before'=>'loginCheck','uses'=>'AdminController@adminAdd'));

Route::post('loginAjax','AdminController@adminLogin');

Route::get('loginPage','AdminController@adminLoginPage');

Route::get('logout','AdminController@adminLogout');

Route::post('activityAdd',array('before'=>'loginCheck','uses'=>'ActivityController@activityAdd'));

Route::post('charityAdd',array('before'=>'loginCheck','uses'=>'ActivityController@charityAdd'));

Route::post('userUpdate',array('before'=>'loginCheck','uses'=>'UserController@userUpdate'));

Route::get('userInfo',array('before'=>'loginCheck','uses'=>'UserController@getUserById'));

Route::get('userSearch',array('before'=>'loginCheck','uses'=>'UserController@userSearch'));

Route::post('userLock',array('before'=>'loginCheck','uses'=>'UserController@userLock'));

Route::get('adminAll',array('before'=>'loginCheck','uses'=>'AdminController@adminList'));

Route::get('adminDelete',array('before'=>'loginCheck','uses'=>'AdminController@adminDelete'));


#登录验证
Route::filter('loginCheck', function()
{

    if (!\Auth::check())
    {
        return Redirect::to('loginPage');

    }
});