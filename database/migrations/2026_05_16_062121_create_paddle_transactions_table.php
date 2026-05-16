<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaddleTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('paddle_transactions', function (Blueprint $table) {

            $table->id();

            // LOCAL RELATIONS

            $table->unsignedBigInteger('user_id')->nullable();

            $table->unsignedBigInteger('plan_id')->nullable();

            // PADDLE IDS

            $table->string('paddle_transaction_id')->nullable();

            $table->string('paddle_customer_id')->nullable();

            $table->string('paddle_subscription_id')->nullable();

            $table->string('paddle_price_id')->nullable();

            // PAYMENT INFO

            $table->decimal('amount', 10, 2)->nullable();

            $table->string('currency', 10)->nullable();

            $table->string('status')->default('pending');

            // CUSTOMER INFO

            $table->string('customer_email')->nullable();

            // WEBHOOK / RAW DATA

            $table->json('payload')->nullable();

            // PAYMENT DATES

            $table->timestamp('paid_at')->nullable();

            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            // INDEXES

            $table->index('user_id');

            $table->index('plan_id');

            $table->index('status');

        });
    }

    public function down()
    {
        Schema::dropIfExists('paddle_transactions');
    }
}