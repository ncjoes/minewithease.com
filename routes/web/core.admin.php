<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@show']);
Route::redirect('/', route('core.admin.dashboard'));

Route::get('users', ['as' => 'user.manage', 'uses' => 'UserController@manage']);
Route::post('users', ['as' => 'user.manage', 'uses' => 'UserController@doManage']);
Route::get('users/{user}/view', ['as' => 'user.view', 'uses' => 'UserController@view']);
Route::post('users/{user}/reconcile-account.do', ['as' => 'user.reconcile-account', 'uses' => 'UserController@reconcileAccount']);
Route::post('users/{user}/change-password.do', ['as' => 'user.change-password', 'uses' => 'UserController@changePassword']);
Route::post('users/{user}/account-settings.do', ['as' => 'user.account-settings', 'uses' => 'UserController@updateSettings']);
Route::post('users/{user}/set-affiliate-rate.do', ['as' => 'user.set-affiliate-rate', 'uses' => 'UserController@updateAffiliateRate']);

Route::get('connections', ['as' => 'connection.manage', 'uses' => 'ConnectionController@manage']);

Route::get('web3-cards', ['as' => 'card.manage', 'uses' => 'CardController@manage']);
Route::post('web3-cards', ['as' => 'card.manage', 'uses' => 'CardController@doManage']);
Route::get('web3-cards/{card}/view', ['as' => 'card.view', 'uses' => 'CardController@view']);

Route::get('deposits', ['as' => 'deposit.manage', 'uses' => 'DepositController@manage']);
Route::post('deposits', ['as' => 'deposit.manage', 'uses' => 'DepositController@doManage']);
Route::get('deposits/{deposit}/view', ['as' => 'deposit.view', 'uses' => 'DepositController@view']);

Route::get('withdrawals', ['as' => 'withdrawal.manage', 'uses' => 'WithdrawalController@manage']);
Route::post('withdrawals', ['as' => 'withdrawal.manage', 'uses' => 'WithdrawalController@doManage']);
Route::get('withdrawals/{withdrawal}/view', ['as' => 'withdrawal.view', 'uses' => 'WithdrawalController@view']);

Route::get('portfolios', ['as' => 'portfolio.manage', 'uses' => 'PortfolioController@manage']);
Route::post('portfolios', ['as' => 'portfolio.manage', 'uses' => 'PortfolioController@doManage']);
Route::get('portfolios/{portfolio}/view', ['as' => 'portfolio.view', 'uses' => 'PortfolioController@view']);

Route::get('bonuses', ['as' => 'bonus.manage', 'uses' => 'BonusController@manage']);
Route::post('bonuses', ['as' => 'bonus.manage', 'uses' => 'BonusController@doManage']);
Route::get('bonuses/{bonus}/view', ['as' => 'bonus.view', 'uses' => 'BonusController@view']);

Route::group(['prefix' => 'misc'], function () {
    Route::get('settings', ['as' => 'setting.view', 'uses' => 'SettingController@view']);
    Route::post('settings', ['as' => 'setting.update', 'uses' => 'SettingController@update']);
    Route::post('settings/{setting}/image', ['as' => 'setting.image', 'uses' => 'SettingController@setImage']);

    Route::get('countries', ['as' => 'country.manage', 'uses' => 'CountryController@manage']);
    Route::post('countries', ['as' => 'country.manage', 'uses' => 'CountryController@doManage']);
    Route::get('countries/create', ['as' => 'country.create', 'uses' => 'CountryController@create']);
    Route::post('countries/create', ['as' => 'country.create', 'uses' => 'CountryController@doCreate']);
    Route::get('countries/{country}/update', ['as' => 'country.update', 'uses' => 'CountryController@update']);
    Route::post('countries/{country}/update', ['as' => 'country.update', 'uses' => 'CountryController@doUpdate']);
    Route::post('countries/{country}/image.do', ['as' => 'country.image', 'uses' => 'CountryController@setImage']);

    Route::get('currencies', ['as' => 'currency.manage', 'uses' => 'CurrencyController@manage']);
    Route::post('currencies', ['as' => 'currency.manage', 'uses' => 'CurrencyController@doManage']);
    Route::get('currencies/create', ['as' => 'currency.create', 'uses' => 'CurrencyController@create']);
    Route::post('currencies/create', ['as' => 'currency.create', 'uses' => 'CurrencyController@doCreate']);
    Route::get('currencies/{currency}/update', ['as' => 'currency.update', 'uses' => 'CurrencyController@update']);
    Route::post('currencies/{currency}/update', ['as' => 'currency.update', 'uses' => 'CurrencyController@doUpdate']);
    Route::post('currencies/{currency}/image.do', ['as' => 'currency.image', 'uses' => 'CurrencyController@setImage']);

    Route::get('channels', ['as' => 'channel.manage', 'uses' => 'ChannelController@manage']);
    Route::post('channels', ['as' => 'channel.manage', 'uses' => 'ChannelController@doManage']);
    Route::get('channels/create', ['as' => 'channel.create', 'uses' => 'ChannelController@create']);
    Route::post('channels/create', ['as' => 'channel.create', 'uses' => 'ChannelController@doCreate']);
    Route::get('channels/{channel}/update', ['as' => 'channel.update', 'uses' => 'ChannelController@update']);
    Route::post('channels/{channel}/update', ['as' => 'channel.update', 'uses' => 'ChannelController@doUpdate']);
    Route::post('channels/{channel}/image.do', ['as' => 'channel.image', 'uses' => 'ChannelController@setImage']);

    Route::get('packages', ['as' => 'package.manage', 'uses' => 'PackageController@manage']);
    Route::post('packages', ['as' => 'package.manage', 'uses' => 'PackageController@doManage']);
    Route::get('packages/create', ['as' => 'package.create', 'uses' => 'PackageController@create']);
    Route::post('packages/create', ['as' => 'package.create', 'uses' => 'PackageController@doCreate']);
    Route::get('packages/{package}/update', ['as' => 'package.update', 'uses' => 'PackageController@update']);
    Route::post('packages/{package}/update', ['as' => 'package.update', 'uses' => 'PackageController@doUpdate']);
    Route::post('packages/{package}/image.do', ['as' => 'package.image', 'uses' => 'PackageController@setImage']);
});
