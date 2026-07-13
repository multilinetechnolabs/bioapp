<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTables extends Migration
{
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_module_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->longText('body')->nullable();
            $table->string('type')->default('text'); // title, text, image, mixed, video
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_lesson_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_lesson_id')->constrained()->onDelete('cascade');
            $table->string('language', 5)->default('en'); // en, es, fr
            $table->string('path');
            $table->timestamps();
        });

        Schema::create('course_lesson_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_lesson_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_lesson_videos');
        Schema::dropIfExists('course_lesson_images');
        Schema::dropIfExists('course_lessons');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('courses');
    }
}
