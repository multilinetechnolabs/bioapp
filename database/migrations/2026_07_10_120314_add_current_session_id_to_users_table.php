<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentSessionIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tracks the ID of this user's one currently-allowed active session.
            // Set on every login, compared on every request by EnforceSingleSession —
            // a mismatch means a newer login elsewhere has superseded this session.
            $table->string('current_session_id')->nullable()->after('remember_token');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('current_session_id');
        });
    }
}
