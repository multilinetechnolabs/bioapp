<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateTemplatesTable extends Migration
{
    public function up()
    {
        // Single-row config (like the single course) driving the completion certificate
        // and the digital badge. All display text is admin-editable and supports the
        // placeholders {name} {course} {lessons} {date} {issuer}.
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();

            // Certificate
            $table->string('cert_eyebrow')->default('Certificate of Completion');
            $table->text('cert_title')->nullable();
            $table->string('cert_intro')->default('This certifies that');
            $table->text('cert_body')->nullable();
            $table->text('cert_disclaimer')->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('issuer_email')->nullable();
            $table->string('accent_color', 20)->default('#14b8a6');

            // Badge
            $table->boolean('badge_enabled')->default(true);
            $table->string('badge_label')->default('CERTIFIED');
            $table->text('badge_caption')->nullable();
            $table->text('badge_subtext')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_templates');
    }
}
