<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAuthTables
 */
class CreateAuthTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_users', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('country_id');
            $table->foreignId('referrer_id')->nullable();
            $table->foreignId('account_officer_id')->nullable();
            $table->char('status');
            $table->string('uuid', 16);
            $table->string('email');
            $table->string('phone', 15)->nullable();
            $table->string('password')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('identification')->nullable();
            $table->json('account_settings');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->dateTime('profile_verified_at')->nullable();
            $table->string('two_fa_secret')->nullable();
            $table->rememberToken();
            $table->timestamp('last_login')->nullable();
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unique('uuid', 'users_uuid');
            $table->unique('email', 'users_email');

            $table->foreign('country_id', 'users_country_id')
                  ->references('id')->on('etc_countries')
                  ->onDelete('restrict')->onUpdate('cascade');

            $table->foreign('referrer_id', 'users_referrer_id')
                  ->references('id')->on('auth_users')
                  ->onDelete('set null')->onUpdate('cascade');
        });

        Schema::create('auth_password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();

            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create table for storing roles
        Schema::create('auth_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180)->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('auth_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 180)->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to auth_users (Many To Many Polymorphic)
        Schema::create('auth_role_user', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreignId('user_id');

            $table->foreign('role_id', 'ru_rid')->references('id')->on('auth_roles')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id', 'ru_uid')->references('id')->on('auth_users')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'role_id'], 'rs_uid_rid');

            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });

        // Create table for associating permissions to auth_users (Many To Many Polymorphic)
        Schema::create('auth_permission_user', function (Blueprint $table) {
            $table->foreignId('permission_id');
            $table->foreignId('user_id');

            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreign('permission_id', 'pu_pid')->references('id')->on('auth_permissions')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id', 'pu_uid')->references('id')->on('auth_users')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['user_id', 'permission_id']);
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('auth_permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id');
            $table->foreignId('role_id');

            $table->engine    = 'InnoDB';
            $table->charset   = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->foreign('permission_id', 'pr_pid')->references('id')->on('auth_permissions')
                  ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('role_id', 'pr_rid')->references('id')->on('auth_roles')
                  ->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'pr_pk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_permission_user');
        Schema::dropIfExists('auth_permission_role');
        Schema::dropIfExists('auth_permissions');
        Schema::dropIfExists('auth_role_user');
        Schema::dropIfExists('auth_roles');
        Schema::dropIfExists('auth_password_resets');
        Schema::dropIfExists('auth_users');
    }
}
