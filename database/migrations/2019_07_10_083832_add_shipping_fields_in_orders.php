<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShippingFieldsInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->string('shipping_address', 100);
            $table->integer('shipping_zip');
            $table->string('shipping_service', 100);
            $table->string('will_shipping', 100);
            $table->string('shipping_day_set', 100);
            $table->double('shipping_rate')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->dropColumn('shipping_address');
            $table->dropColumn('shipping_zip');
            $table->dropColumn('shipping_service');
            $table->dropColumn('will_shipping');
            $table->dropColumn('shipping_day_set');
            $table->dropColumn('shipping_rate');
        });
    }
}
