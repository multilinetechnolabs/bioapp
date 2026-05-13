<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('target', 20);
            $table->string('label', 100);
            $table->double('point_x', 7, 2)->default(0);
            $table->double('point_y', 7, 2)->default(0);
            $table->double('point_z', 7, 2)->default(0);
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
        Schema::dropIfExists('model_labels');
    }
}
