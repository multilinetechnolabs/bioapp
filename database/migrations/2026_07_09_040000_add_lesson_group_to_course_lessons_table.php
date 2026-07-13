<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonGroupToCourseLessonsTable extends Migration
{
    public function up()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->unsignedTinyInteger('lesson_group')->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropColumn('lesson_group');
        });
    }
}
