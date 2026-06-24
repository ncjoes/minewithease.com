<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('login', ['as' => 'login', 'uses' => 'LoginController@showLoginForm']);
Route::post('login', ['as' => 'login', 'uses' => 'LoginController@login']);
Route::get('2fa/authorize', ['as' => '2fa.authorize', 'uses' => 'LoginController@show2FAuthForm']);
Route::post('2fa/authorize', ['as' => '2fa.authorize', 'uses' => 'LoginController@authorizeLogin']);
Route::post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout', 'middleware' => 'auth']);

Route::get('register/{referrerAID?}', ['as' => 'register', 'uses' => 'RegisterController@showRegistrationForm']);
Route::post('register/{referrerAID?}', ['as' => 'register', 'uses' => 'RegisterController@register']);

Route::get('password/reset', ['as' => 'password.request', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
Route::post('password/email', ['as' => 'password.email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);
Route::get('password/reset/{token}', ['as' => 'password.link', 'uses' => 'ResetPasswordController@showResetForm']);
Route::post('password/reset', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@reset']);
