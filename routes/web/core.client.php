<?php
declare(strict_types=1);

use App\Models\Auth\User;
use Illuminate\Support\Facades\Route;

Route::get('dashboard.html', ['as' => 'dashboard', 'uses' => 'AccountController@dashboard']);

Route::get('transactions.html', ['as' => 'transactions', 'uses' => 'AccountController@transactions']);

Route::get('notifications.html', ['as' => 'notifications', 'uses' => 'NotificationController@show']);
Route::post('notifications.html', ['as' => 'notifications', 'uses' => 'NotificationController@manage']);

Route::get('profile-update.html', ['as' => 'profile-update', 'uses' => 'AccountController@profileUpdate']);
Route::post('profile-photo.html', ['as' => 'profile.photo', 'uses' => 'AccountController@setImage']);
Route::post('profile-update.html', ['as' => 'profile-update', 'uses' => 'AccountController@doProfileUpdate']);
Route::post('wallets-update.html', ['as' => 'wallets-update', 'uses' => 'AccountController@doWalletsUpdate']);

Route::get('security.html', ['as' => 'security', 'uses' => 'AccountController@security']);
Route::post('password-change.html', ['as' => 'password-change', 'uses' => 'AccountController@doPasswordChange']);
Route::post('two-factor-auth', ['as' => 'two-factor-auth', 'uses' => 'AccountController@doTwoFactorAuthUpdate']);

Route::get('account-settings.html', ['as' => 'account-settings', 'uses' => 'AccountController@accountSettings']);
Route::post('account-settings.html', ['as' => 'account-settings', 'uses' => 'AccountController@doSettingsUpdate']);

Route::get('swap.html', ['as' => 'swap.create', 'uses' => 'SwapController@create']);
Route::post('swap.html', ['as' => 'swap.create', 'uses' => 'SwapController@doCreate']);
Route::get('swap/history.html', ['as' => 'swap.history', 'uses' => 'SwapController@history']);

Route::get('web3-card/order.html', ['as' => 'card.create', 'uses' => 'CardController@create']);
Route::post('web3-card/order.html', ['as' => 'card.create', 'uses' => 'CardController@doCreate']);
Route::get('web3-card/{card}/view-order-invoice.html', ['as' => 'card.view-invoice', 'uses' => 'CardController@viewInvoice']);
Route::get('web3-card/manage.html', ['as' => 'card.manage', 'uses' => 'CardController@manage']);
Route::post('web3-card/manage.html', ['as' => 'card.manage', 'uses' => 'CardController@doManage']);

Route::get('my-stakes/history.html', ['as' => 'portfolio.manage', 'uses' => 'PortfolioController@manage']);
Route::post('my-stakes/history.html', ['as' => 'portfolio.manage', 'uses' => 'PortfolioController@doManage']);
Route::get('my-stakes/new-stake.html', ['as' => 'portfolio.create', 'uses' => 'PortfolioController@create'])->middleware(['status' => 'status:' . User::S_ACTIVATED]);
Route::post('my-stakes/new-stake.html', ['as' => 'portfolio.create', 'uses' => 'PortfolioController@doCreate'])->middleware(['status' => 'status:' . User::S_ACTIVATED]);
Route::get('my-stakes/{portfolio}/view-stake.html', ['as' => 'portfolio.view', 'uses' => 'PortfolioController@view']);

Route::get('deposits/history.html', ['as' => 'deposit.manage', 'uses' => 'DepositController@manage']);
Route::post('deposits/history.html', ['as' => 'deposit.manage', 'uses' => 'DepositController@doManage']);
Route::get('deposits/new.html', ['as' => 'deposit.create', 'uses' => 'DepositController@create']);
Route::post('deposits/new.html', ['as' => 'deposit.create', 'uses' => 'DepositController@doCreate']);
Route::get('deposits/{deposit}/view.html', ['as' => 'deposit.view', 'uses' => 'DepositController@view']);

Route::get('withdrawals/history.html', ['as' => 'withdrawal.manage', 'uses' => 'WithdrawalController@manage']);
Route::get('withdrawals/{withdrawal}/view.html', ['as' => 'withdrawal.view', 'uses' => 'WithdrawalController@view']);
Route::group(['middleware' => 'verify-profile'], function () {
    Route::post('withdrawals/history.html', ['as' => 'withdrawal.manage', 'uses' => 'WithdrawalController@doManage']);
    Route::get('withdrawals/new.html', ['as' => 'withdrawal.create', 'uses' => 'WithdrawalController@create'])->middleware(['status' => 'status:' . User::S_ACTIVATED]);
    Route::post('withdrawals/new.html', ['as' => 'withdrawal.create', 'uses' => 'WithdrawalController@doCreate'])->middleware(['status' => 'status:' . User::S_ACTIVATED]);
});

Route::get('referrals/community.html', ['as' => 'referral.manage', 'uses' => 'ReferralController@manage']);
Route::get('referrals/{referral}/view.html', ['as' => 'referral.view', 'uses' => 'ReferralController@view']);
