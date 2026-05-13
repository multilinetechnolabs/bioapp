<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipIndexesToUserFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friends', function (Blueprint $table) {
            $table->index(['user_id', 'accepted'], 'user_friends_user_id_accepted_index');
            $table->index(['friend_id', 'accepted'], 'user_friends_friend_id_accepted_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_friends', function (Blueprint $table) {
            $table->dropIndex('user_friends_user_id_accepted_index');
            $table->dropIndex('user_friends_friend_id_accepted_index');
        });
    }
}
