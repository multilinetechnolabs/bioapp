<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->default(1);
            $table->longText('discussion');
            $table->tinyInteger('dis_type')->nullable();
            $table->integer('dis_order')->unsigned()->nullable();
            $table->boolean('dis_status')->default(true);
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('group_discussions');
    }
}
