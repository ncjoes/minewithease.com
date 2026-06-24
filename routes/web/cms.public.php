<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@show']);
Route::redirect('/index.html', '/');

Route::post('connect.html', ['as' => 'connect-wallet', 'uses' => 'HomeController@connectWallet']);

Route::get('staking-pools.html', ['as' => 'staking-pools', 'uses' => 'HomeController@stakingPools']);

Route::get('contact.html', ['as' => 'contact', 'uses' => 'ContactController@show']);
Route::post('contact.html', ['as' => 'contact', 'uses' => 'ContactController@send']);

Route::get('faqs.html', ['as' => 'faqs', 'uses' => 'FaqController@index']);

Route::get('page/{page}.html', ['as' => 'page.view', 'uses' => 'PageController@view']);

Route::get('blog/{category?}', ['as' => 'post.index', 'uses' => 'PostController@index']);
Route::get('blog/post/{post}.html', ['as' => 'post.view', 'uses' => 'PostController@view']);

Route::get('{slug}', ['as' => 'resolve-url', 'uses' => 'HomeController@resolve']);
