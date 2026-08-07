<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Projects and everything hanging off them.
 *
 * `deleted_at` is Laravel's soft-delete column, which matches the convention
 * the original schema already used — an archived project stays queryable for
 * admins and disappears for the client.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Keep the project if the client account is removed, rather than
            // cascading the delete and losing the work history.
            $table->foreignId('client_id')->nullable()
                ->constrained('users')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['Planning', 'In Progress', 'On Hold', 'Completed'])
                ->default('In Progress');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('project_type')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['deleted_at', 'created_at']);
        });

        Schema::create('project_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('storage_path', 500);
            $table->string('caption', 300)->nullable();
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
        });

        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('storage_path', 500);
            $table->string('name');
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
        });

        Schema::create('project_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();

            $table->index(['project_id', 'created_at']);
        });

        Schema::create('project_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->date('target_date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['project_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_stages');
        Schema::dropIfExists('project_updates');
        Schema::dropIfExists('project_documents');
        Schema::dropIfExists('project_images');
        Schema::dropIfExists('projects');
    }
};
