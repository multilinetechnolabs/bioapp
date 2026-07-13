<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePaymentTables extends Migration
{
    public function up()
    {
        // Kept separate from freemius_transactions/subscriptions (app subscription tables)
        // since course access is a one-time purchase with its own 1-year expiry, not a recurring plan.
        Schema::create('course_freemius_transactions', function (Blueprint $table) {
            $table->id();
            // users.id is INT UNSIGNED (legacy `increments()`), not BIGINT UNSIGNED —
            // must match exactly or MySQL 8 rejects the FK below (error 3780).
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->string('freemius_transaction_id')->nullable();
            $table->string('freemius_subscription_id')->nullable();
            $table->string('freemius_license_key')->nullable();
            $table->decimal('amount', 8, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('customer_email')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        Schema::create('course_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('course_id');
            $table->string('freemius_subscription_id')->nullable();
            $table->string('status')->default('active'); // active, cancelled
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        // Durable per-user lesson progress (replaces the session-only demo tracking —
        // needed since real access now spans up to a year across many login sessions).
        Schema::create('course_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('course_lesson_id');
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('course_lesson_id')->references('id')->on('course_lessons')->onDelete('cascade');
            $table->unique(['user_id', 'course_lesson_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_lesson_progress');
        Schema::dropIfExists('course_purchases');
        Schema::dropIfExists('course_freemius_transactions');
    }
}
