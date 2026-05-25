<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('freemius_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            $table->string('freemius_transaction_id')->nullable();
            $table->string('freemius_subscription_id')->nullable();
            $table->string('freemius_license_key')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('status')->default('pending');
            $table->string('customer_email')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('plan_id');
            $table->index('freemius_transaction_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('freemius_transactions');
    }
};
