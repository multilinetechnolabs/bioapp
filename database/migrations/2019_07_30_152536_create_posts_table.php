<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('posts')) {
            return;
        }

        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('post_author');
            $table->text('post_content');
            $table->text('post_title');
            $table->text('post_excerpt')->nullable();
            $table->boolean('comment_status')->default(true);
            $table->string('post_slug')->unique();
            $table->string('post_type', 120)->default('post');
            $table->text('post_featured_img')->nullable();
            $table->bigInteger('post_featured_md_id')->nullable();
            $table->text('seo_title')->nullable();
            $table->text('seo_meta_description')->nullable();
            $table->text('seo_meta_keywords')->nullable();
            $table->datetime('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('posts')) {
            return;
        }

        Schema::dropIfExists('posts');
    }
}
