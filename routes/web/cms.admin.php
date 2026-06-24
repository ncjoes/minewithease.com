<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', ['as' => 'home', 'uses' => 'HomeController@dashboard']);

Route::get('faqs.html', ['as' => 'faq.manage', 'uses' => 'FaqController@manage']);
Route::post('faqs.html', ['as' => 'faq.manage', 'uses' => 'FaqController@doManage']);
Route::get('faqs/create.html', ['as' => 'faq.create', 'uses' => 'FaqController@create']);
Route::post('faqs/create.html', ['as' => 'faq.create', 'uses' => 'FaqController@doCreate']);
Route::get('faqs/{faq}/update.do', ['as' => 'faq.update', 'uses' => 'FaqController@update']);
Route::post('faqs/{faq}/update.do', ['as' => 'faq.update', 'uses' => 'FaqController@doUpdate']);

Route::get('categories.html', ['as' => 'category.manage', 'uses' => 'CategoryController@manage']);
Route::post('categories.html', ['as' => 'category.manage', 'uses' => 'CategoryController@doManage']);
Route::get('categories/create.html', ['as' => 'category.create', 'uses' => 'CategoryController@create']);
Route::post('categories/create.html', ['as' => 'category.create', 'uses' => 'CategoryController@doCreate']);
Route::get('categories/{category}/update.do', ['as' => 'category.update', 'uses' => 'CategoryController@update']);
Route::post('categories/{category}/update.do', ['as' => 'category.update', 'uses' => 'CategoryController@doUpdate']);
Route::post('categories/{category}/image.do', ['as' => 'category.image', 'uses' => 'CategoryController@setImage']);

Route::get('pages.html', ['as' => 'page.manage', 'uses' => 'PageController@manage']);
Route::post('pages.html', ['as' => 'page.manage', 'uses' => 'PageController@doManage']);
Route::get('pages/create.html', ['as' => 'page.create', 'uses' => 'PageController@create']);
Route::post('pages/create.html', ['as' => 'page.create', 'uses' => 'PageController@doCreate']);
Route::get('pages/{page}/update.html', ['as' => 'page.update', 'uses' => 'PageController@update']);
Route::post('pages/{page}/update.html', ['as' => 'page.update', 'uses' => 'PageController@doUpdate']);
Route::post('pages/{page}/image.do', ['as' => 'page.image', 'uses' => 'PageController@setImage']);

Route::get('posts.html', ['as' => 'post.manage', 'uses' => 'PostController@manage']);
Route::post('posts.html', ['as' => 'post.manage', 'uses' => 'PostController@doManage']);
Route::get('posts/create.html', ['as' => 'post.create', 'uses' => 'PostController@create']);
Route::post('posts/create.html', ['as' => 'post.create', 'uses' => 'PostController@doCreate']);
Route::get('posts/{post}/update.do', ['as' => 'post.update', 'uses' => 'PostController@update']);
Route::post('posts/{post}/update.do', ['as' => 'post.update', 'uses' => 'PostController@doUpdate']);
Route::post('posts/{post}/image.do', ['as' => 'post.image', 'uses' => 'PostController@setImage']);

Route::get('authors.html', ['as' => 'author.manage', 'uses' => 'AuthorController@manage']);
Route::post('authors.html', ['as' => 'author.manage', 'uses' => 'AuthorController@doManage']);
Route::get('authors/create.html', ['as' => 'author.create', 'uses' => 'AuthorController@create']);
Route::post('authors/create.html', ['as' => 'author.create', 'uses' => 'AuthorController@doCreate']);
Route::get('authors/{author}/update.do', ['as' => 'author.update', 'uses' => 'AuthorController@update']);
Route::post('authors/{author}/update.do', ['as' => 'author.update', 'uses' => 'AuthorController@doUpdate']);

Route::get('redirects.html', ['as' => 'redirect.manage', 'uses' => 'RedirectController@manage']);
Route::post('redirects.html', ['as' => 'redirect.manage', 'uses' => 'RedirectController@doManage']);
Route::get('redirects/create.html', ['as' => 'redirect.create', 'uses' => 'RedirectController@create']);
Route::post('redirects/create.html', ['as' => 'redirect.create', 'uses' => 'RedirectController@doCreate']);
Route::get('redirects/{redirect}/update.html', ['as' => 'redirect.update', 'uses' => 'RedirectController@update']);
Route::post('redirects/{redirect}/update.html', ['as' => 'redirect.update', 'uses' => 'RedirectController@doUpdate']);

Route::get('slides.html', ['as' => 'slide.manage', 'uses' => 'SlideController@manage']);
Route::post('slides.html', ['as' => 'slide.manage', 'uses' => 'SlideController@doManage']);
Route::get('slides/create.html', ['as' => 'slide.create', 'uses' => 'SlideController@create']);
Route::post('slides/create.html', ['as' => 'slide.create', 'uses' => 'SlideController@doCreate']);
Route::get('slides/{slide}/update.do', ['as' => 'slide.update', 'uses' => 'SlideController@update']);
Route::post('slides/{slide}/update.do', ['as' => 'slide.update', 'uses' => 'SlideController@doUpdate']);
Route::post('slides/{slide}/image.do', ['as' => 'slide.image', 'uses' => 'SlideController@setImage']);
