<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrGoizPairsTable extends Migration
{
    public function up()
    {
        Schema::create('dr_goiz_pairs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('place')->nullable();
            $table->string('place_es')->nullable();
            $table->string('resonance')->nullable();
            $table->string('resonance_es')->nullable();
            $table->string('name')->nullable();
            $table->string('name_es')->nullable();
            $table->longText('characteristic')->nullable();
            $table->longText('characteristic_es')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_es')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dr_goiz_pairs');
    }
}
