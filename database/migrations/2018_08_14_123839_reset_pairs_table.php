<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ResetPairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pairs');
        Schema::create('pairs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('scan_type', 30)->default('body_scan');
            $table->string('ref_no', 30)->default('')->nullable();
            $table->longText('name')->nullable();
            $table->longText('radical')->nullable();
            $table->longText('origins')->nullable();
            $table->longText('symptoms')->nullable();
            $table->longText('paths')->nullable();
            $table->longText('alternative_routes')->nullable();
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
        Schema::dropIfExists('pairs');
    }
}
