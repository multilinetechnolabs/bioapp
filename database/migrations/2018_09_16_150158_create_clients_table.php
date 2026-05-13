<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('first_name', 50)->default('');
            $table->string('last_name', 50)->default('');
            $table->string('email', 50)->default('');
            $table->string('address')->default('');
            $table->string('phone_no')->default('');
            $table->date('date_of_birth');
            $table->string('emergency_contact_person')->default('');
            $table->string('emergency_contact_number')->default('');
            $table->string('session_cost_type')->default('');
            $table->double('session_cost')->default(0.0);
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
        Schema::dropIfExists('clients');
    }
}
