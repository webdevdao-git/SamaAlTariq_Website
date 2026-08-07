<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Creates the first administrator.
     *
     * Everything else — clients, projects — is created through the admin UI, so
     * this is the one bootstrap that cannot go through an authorization check.
     * It is idempotent and refuses to touch an existing account, so running
     * `db:seed` again on a live site is harmless.
     *
     * Credentials come from ADMIN_EMAIL / ADMIN_PASSWORD; if no password is set
     * one is generated and printed once.
     */
    public function run(): void
    {
        $email = Str::lower(trim((string) env('ADMIN_EMAIL', 'admin@samaaltariq.org')));

        if (User::where('email', $email)->exists()) {
            $this->command->warn("An account already exists for {$email} — nothing seeded.");

            return;
        }

        $password = env('ADMIN_PASSWORD') ?: Str::password(16, symbols: false);
        $generated = ! env('ADMIN_PASSWORD');

        User::create([
            'name' => env('ADMIN_NAME', 'Site Admin'),
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
            'can_download' => true,
            'must_change_password' => $generated,
        ]);

        $this->command->info("Administrator created: {$email}");

        if ($generated) {
            $this->command->warn("Temporary password: {$password}");
            $this->command->warn('Sign in and change it — the account is flagged to require it.');
        }
    }
}
