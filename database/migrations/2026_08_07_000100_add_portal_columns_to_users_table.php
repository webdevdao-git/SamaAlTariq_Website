<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds the client-portal columns to Laravel's users table.
 *
 * The previous Supabase schema kept these on a separate `profiles` table
 * because Supabase Auth owned `auth.users`. Laravel owns authentication here,
 * so there is no reason to split a person across two tables — the role and the
 * portal flags live beside the credentials.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('email');
            $table->string('phone', 32)->nullable()->after('username');
            $table->string('job_title')->nullable()->after('phone');

            // When false the client may view project images but not download them.
            $table->boolean('can_download')->default(false)->after('job_title');

            $table->enum('role', ['admin', 'client'])->default('client')->after('can_download');
            $table->boolean('must_change_password')->default(false)->after('role');
            $table->timestamp('last_login_at')->nullable()->after('must_change_password');

            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropColumn([
                'username',
                'phone',
                'job_title',
                'can_download',
                'role',
                'must_change_password',
                'last_login_at',
            ]);
        });
    }
};
