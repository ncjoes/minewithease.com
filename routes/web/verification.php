<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('verify', [
    'as'         => 'notice',
    'uses'       => 'VerificationController@show',
    'middleware' => 'auth',
]);

Route::get('verify/{id}', [
    'as'         => 'verify',
    'uses'       => 'VerificationController@verify',
    'middleware' => [/*'auth',*/ 'signed', 'throttle:6,1'],
]);

Route::post('resend', [
    'as'         => 'resend',
    'uses'       => 'VerificationController@resend',
    'middleware' => ['auth', 'throttle:6,1',]
]);
