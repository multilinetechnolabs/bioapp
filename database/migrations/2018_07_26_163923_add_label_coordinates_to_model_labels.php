<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelCoordinatesToModelLabels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_labels', function ($table) {
            $table->double('label_x', 7, 2)->default(0);
            $table->double('label_y', 7, 2)->default(0);
            $table->double('label_z', 7, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_labels', function ($table) {
            $table->dropColumn('label_x');
            $table->dropColumn('label_y');
            $table->dropColumn('label_z');
        });
    }
}
