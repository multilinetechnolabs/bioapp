<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupDisCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_dis_comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_dis_id')->unsigned();
            $table->longText('comment');
            $table->integer('comt_order')->unsigned()->nullable();
            $table->boolean('comt_status')->default(true);
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
        Schema::dropIfExists('group_dis_comment');
    }
}
