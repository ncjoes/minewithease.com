<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCoreTables
 */
class CreateCoreTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->double('min_amount');
            $table->double('max_amount');
            $table->double('split_amount');
            $table->double('min_interest_rate');
            $table->double('max_interest_rate');
            $table->integer('interest_interval');
            $table->integer('min_duration');
            $table->integer('max_duration');
            $table->string('referral_bonus_rate');
            $table->tinyInteger('referral_bonus_count');
            $table->integer('referral_bonus_release_time');
            $table->double('service_charge_rate')->default(0);
            $table->boolean('is_active');
            $table->timestamps();
        });

        Schema::create('core_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('currency_id');
            $table->string('wallet_address')->nullable();
            $table->double('balance')->default(0);
            $table->double('local_balance')->default(0);
            $table->boolean('is_active');
            $table->timestamps();

            $table->foreign('user_id', 'accounts_user_id')
                ->references('id')->on('auth_users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('currency_id', 'accounts_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('core_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('website')->nullable();
            $table->double('min_amount');
            $table->double('max_amount');
            $table->double('split_amount');
            $table->text('description');
            $table->string('payment_wallet');
            $table->float('rank')->default(0.0);
            $table->string('wallet_address_format')->nullable();
            $table->string('wallet_address_placeholder')->nullable();
            $table->boolean('is_active');
            $table->boolean('for_inflow');
            $table->boolean('for_outflow');
            $table->timestamps();

            $table->foreign('currency_id', 'channels_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('core_portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 16);
            $table->foreignId('currency_id'); // base currency of the portfolio, e.g. USD
            $table->foreignId('account_id');
            $table->foreignId('package_id');
            $table->double('amount');
            $table->double('local_amount');
            $table->double('min_interest_rate');
            $table->double('max_interest_rate');
            $table->integer('interest_interval');
            $table->integer('min_duration');
            $table->integer('max_duration');
            $table->double('service_charge_rate')->default(0);
            $table->dateTime('expires_at');
            $table->dateTime('last_rewarded_at')->nullable();
            $table->tinyInteger('status');
            $table->nullableTimestamps();

            $table->foreign('currency_id', 'portfolios_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('account_id', 'portfolios_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('package_id', 'portfolios_package_id')
                ->references('id')->on('core_packages')
                ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('core_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id'); // base currency of the deposit, e.g. USD
            $table->foreignId('account_id');
            $table->string('uuid', 16);
            $table->double('amount');
            $table->double('local_amount');
            $table->string('trans_ref')->nullable();
            $table->char('status', 2);
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();

            $table->unique('uuid', 'deposits_uuid');

            $table->foreign('account_id', 'deposits_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('currency_id', 'deposits_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('core_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->foreignId('account_id');
            $table->string('uuid', 16);
            $table->double('amount');
            $table->double('local_amount');
            $table->string('payment_wallet');
            $table->string('trans_ref')->nullable();
            $table->char('status', 2);
            $table->float('progress_value')->nullable();
            $table->string('progress_description')->nullable();
            $table->dateTime('processed_at')->nullable();
            $table->timestamps();

            $table->unique('uuid', 'deposits_uuid');

            $table->foreign('currency_id', 'withdrawals_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('account_id', 'withdrawals_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('core_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->foreignId('account_id');
            $table->foreignId('item_id');
            $table->string('item_type', 20);
            $table->string('description');
            $table->double('amount');
            $table->double('local_amount');
            $table->dateTime('due_from');
            $table->char('status', 2);
            $table->timestamps();

            $table->index(['item_id', 'item_type'], 'bonus_item');

            $table->foreign('currency_id', 'bonuses_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('account_id', 'bonuses_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');
        });


        Schema::create('core_connections', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('type');
            $table->json('data');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('core_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->foreignId('account_id');
            $table->foreignId('item_id');
            $table->string('item_type', 20);
            $table->double('amount');
            $table->double('local_amount');
            $table->double('new_balance');
            $table->double('new_local_balance');
            $table->string('description');
            $table->timestamps();

            $table->index(['item_id', 'item_type'], 'transaction_item');

            $table->foreign('currency_id', 'transactions_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('account_id', 'transactions_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('core_transactions');
        Schema::dropIfExists('core_connections');
        Schema::dropIfExists('core_bonuses');
        Schema::dropIfExists('core_withdrawals');
        Schema::dropIfExists('core_deposits');
        Schema::dropIfExists('core_portfolios');
        Schema::dropIfExists('core_channels');
        Schema::dropIfExists('core_packages');
    }
}
