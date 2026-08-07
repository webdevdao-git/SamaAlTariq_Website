<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Public contact-form submissions from the landing page.
 *
 * Column names match the form fields one-for-one, so a submission maps straight
 * onto a row with no translation layer to get wrong.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 254);
            $table->string('phone', 32)->nullable();
            $table->string('location')->nullable();
            $table->string('project_type', 120)->nullable();
            $table->text('project_brief')->nullable();

            // Kept for abuse triage, not for analytics.
            $table->string('ip', 64)->nullable();
            $table->string('user_agent')->nullable();

            $table->enum('status', ['new', 'read', 'archived'])->default('new');
            $table->timestamps();

            $table->index('created_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
