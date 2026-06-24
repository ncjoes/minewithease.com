<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateEtcTables
 */
class CreateEtcTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etc_settings', function (Blueprint $table) {
            $table->string('group');
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('default')->nullable();
            $table->string('form_type');
            $table->string('form_label');
            $table->text('form_placeholder')->nullable();
            $table->text('form_options')->nullable();
            $table->boolean('required');
            $table->string('validation_rules')->nullable();
            $table->string('description')->nullable();
            $table->smallInteger('cardinality')->default(1);
            $table->boolean('admin_only')->default(true);
            $table->timestamps();

            $table->primary(['key', 'group']);
        });

        Schema::create('etc_continents', function (Blueprint $table) {
            $table->id('id');
            $table->char('iso2', 2);
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('etc_currencies', function (Blueprint $table) {
            $table->id('id');
            $table->char('num_code', 3);
            $table->char('alpha_code', 8);
            $table->tinyInteger('minor_unit')->default(2);
            $table->string('name');
            $table->string('symbol', 10)->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_crypto')->default(false);
            $table->timestamps();

            $table->unique('num_code', 'currencies_num_code');
            $table->unique('alpha_code', 'currencies_alpha_code');
        });

        Schema::create('etc_countries', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('continent_id');
            $table->string('name');
            $table->char('iso2', 2);
            $table->char('iso3', 3);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->foreign('continent_id', 'countries_cid')
                  ->references('id')->on('etc_continents')
                  ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('etc_country_currency', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('country_id');
            $table->foreignId('currency_id');
            $table->timestamps();

            $table->foreign('country_id', 'cc_country_id')
                  ->references('id')->on('etc_countries')
                  ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('currency_id', 'cc_currency_id')
                  ->references('id')->on('etc_currencies')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('etc_regions', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('country_id');
            $table->string('name', 180);
            $table->string('capital');
            $table->timestamps();

            $table->unique(['country_id', 'name'], 'regions_name');

            $table->foreign('country_id', 'regions_cid')
                  ->references('id')->on('etc_countries')
                  ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('etc_districts', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->foreignId('region_id');
            $table->timestamps();

            $table->foreign('region_id', 'districts_rid')
                  ->references('id')->on('etc_regions')
                  ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etc_districts');
        Schema::dropIfExists('etc_regions');
        Schema::dropIfExists('etc_country_currency');
        Schema::dropIfExists('etc_countries');
        Schema::dropIfExists('etc_currencies');
        Schema::dropIfExists('etc_continents');
        Schema::dropIfExists('etc_settings');
    }
}
