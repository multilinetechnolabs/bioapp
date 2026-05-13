<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNewrolesTable extends Migration
{
    public function up()
    {
        DB::table('roles')->insert([
            ['name' => 'student/estudiante', 'guard_name' => 'web'],
            ['name' => 'practitioner/practicante', 'guard_name' => 'web'],
            ['name' => 'guest/invitado', 'guard_name' => 'web'],
        ]);
    }

    public function down()
    {
    }
}
