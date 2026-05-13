<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefineNullableColumnsInUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('first_name', 50)->nullable(true)->change();
            $table->string('last_name', 50)->nullable(true)->change();
            $table->string('company_name', 50)->nullable(true)->change();
            $table->string('phone_no', 50)->nullable(true)->change();
            $table->string('fax_no', 50)->nullable(true)->change();
            $table->string('billing_title', 50)->nullable(true)->change();
            $table->string('address', 50)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->string('first_name', 50)->nullable(false)->change();
            $table->string('last_name', 50)->nullable(false)->change();
            $table->string('company_name', 50)->nullable(false)->change();
            $table->string('phone_no', 50)->nullable(false)->change();
            $table->string('fax_no', 50)->nullable(false)->change();
            $table->string('billing_title', 50)->nullable(false)->change();
            $table->string('address', 50)->nullable(false)->change();
        });
    }
}
