<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('core_cards', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('channel_id');
            $table->foreignId('user_id');
            $table->double('amount');
            $table->double('local_amount');
            $table->string('payment_reference')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->timestamp('verified_at')->nullable();

            $table->foreign('channel_id', 'cards_channel_id')
                ->references('id')->on('core_channels')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('user_id', 'cards_user_id')
                ->references('id')->on('auth_users')
                ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('core_swaps', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('currency_id');
            $table->foreignId('user_id');
            $table->foreignId('source_account_id');
            $table->foreignId('destination_account_id');
            $table->double('amount');
            $table->double('source_local_amount');
            $table->double('destination_local_amount');
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('currency_id', 'swaps_currency_id')
                ->references('id')->on('etc_currencies')
                ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('user_id', 'swaps_user_id')
                ->references('id')->on('auth_users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('source_account_id', 'swaps_source_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('destination_account_id', 'swaps_destination_account_id')
                ->references('id')->on('core_accounts')
                ->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_swaps');
        Schema::dropIfExists('core_cards');
    }
};
