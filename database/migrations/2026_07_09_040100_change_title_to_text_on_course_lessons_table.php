<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTitleToTextOnCourseLessonsTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE course_lessons MODIFY title TEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE course_lessons MODIFY title VARCHAR(255) NULL');
    }
}
