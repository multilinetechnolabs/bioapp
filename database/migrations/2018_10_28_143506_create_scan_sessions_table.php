<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScanSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scan_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('user_id');
            $table->string('scan_type')->default('body_scan');
            $table->date('date_started');
            $table->date('date_ended')->nullable();
            $table->string('cost_type')->default('');
            $table->double('cost')->default(0.0);
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('scan_sessions');
    }
}
