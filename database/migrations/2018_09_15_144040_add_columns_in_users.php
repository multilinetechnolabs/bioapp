<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->default('');
            $table->string('first_name', 50)->default('');
            $table->string('last_name', 50)->default('');
            $table->string('phone_no', 50)->default('');
            $table->string('fax_no', 50)->default('');
            $table->string('alternate_email', 50)->default('');
            $table->string('billing_title')->default('');
            $table->string('address')->default('');
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
            $table->dropColumn('company_name');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('phone_no');
            $table->dropColumn('fax_no');
            $table->dropColumn('alternate_email');
            $table->dropColumn('billing_title');
            $table->dropColumn('address');
        });
    }
}
