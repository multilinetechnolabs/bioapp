<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsPaidAndPaymentIdInScanSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scan_sessions', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scan_sessions', function (Blueprint $table) {
            $table->boolean('paid')->default('false');
            $table->integer('payment_id')->nullable();
        });
    }
}
