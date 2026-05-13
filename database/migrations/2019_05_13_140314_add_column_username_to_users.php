<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUsernameToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('users');

            if ($doctrineTable->hasIndex('users_email_unique')) {
                $table->dropUnique('users_email_unique');
            }

            $table->string('username')->nullable()->unique();
        });

        foreach (\App\Models\User::all() as $user) {
            $user->username = $user->email;
            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $doctrineTable = $sm->listTableDetails('users');

            if ($doctrineTable->hasIndex('users_username_unique')) {
                $table->dropUnique('users_username_unique');
                $table->dropColumn('username');
            }

            if (!$doctrineTable->hasIndex('users_email_unique')) {
                $table->unique('email');
            }
        });
    }
}
