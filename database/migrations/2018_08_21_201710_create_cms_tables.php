<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCmsTables
 */
class CreateCmsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_categories', function (Blueprint $table) {
            $table->id('id');
            $table->char('type');
            $table->string('slug');
            $table->string('title');
            $table->text('description');
            $table->tinyInteger('cardinality')->default(0);
            $table->boolean('system_defined')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('show_in_footer')->default(true);
            $table->boolean('use_index')->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unique('slug', 'cms_categories_slug');
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->smallInteger('cardinality')->default(0);
            $table->string('slug');
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('cover_photo')->nullable();
            $table->string('thumb_photo')->nullable();
            $table->boolean('system_defined')->default(false);
            $table->boolean('show_in_menu')->default(true);
            $table->boolean('show_in_footer')->default(true);
            $table->boolean('is_published')->default(false);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->unique('slug', 'pages_slug');

            $table->foreign('category_id', 'pages_cid')
                ->references('id')->on('cms_categories')
                ->onDelete('restrict')->onUpdate('cascade');
        });

        Schema::create('cms_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->string('action_url')->nullable();
            $table->string('action_label')->nullable();
            $table->tinyInteger('cardinality')->default(0);
            $table->nullableTimestamps();
            $table->softDeletes();
        });

        Schema::create('cms_testimonies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('statement');
            $table->char('status');
            $table->timestamps();
        });

        Schema::create('cms_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->string('slug', 180);
            $table->string('title');
            $table->text('summary')->nullable();
            $table->longText('content');
            $table->string('author')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('thumb_photo')->nullable();
            $table->boolean('allow_comments')->default(false);
            $table->nullableTimestamps();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->softDeletes();

            $table->unique('slug', 'cms_posts_slug');

            $table->foreign('category_id', 'cp_cid')
                  ->references('id')->on('cms_categories')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('cms_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id');
            $table->foreignId('cardinality')->default(0);
            $table->string('question');
            $table->text('answer');
            $table->boolean('is_published')->default(true);
            $table->nullableTimestamps();
            $table->softDeletes();

            $table->foreign('category_id', 'cf_cid')
                  ->references('id')->on('cms_categories')
                  ->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('cms_media', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable();

            $table->nullableTimestamps();
        });

        Schema::create('cms_redirects', function (Blueprint $table) {
            $table->id('id');
            $table->string('slug');
            $table->string('destination');
            $table->nullableTimestamps();

            $table->unique('slug', 'cms_redirects_slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_redirects');
        Schema::dropIfExists('cms_media');
        Schema::dropIfExists('cms_faqs');
        Schema::dropIfExists('cms_posts');
        Schema::dropIfExists('cms_categories');
        Schema::dropIfExists('cms_testimonies');
        Schema::dropIfExists('cms_slides');
        Schema::dropIfExists('cms_pages');
    }
}
