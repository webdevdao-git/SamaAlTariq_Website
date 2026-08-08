<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * The forgot-password / reset-password pair, matching resetPasswordForEmail()
 * in the Supabase app.
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_request_screen_renders(): void
    {
        $this->get(route('password.request'))->assertOk();
    }

    public function test_a_reset_link_is_emailed_to_a_known_address(): void
    {
        Notification::fake();
        $user = User::factory()->create();

        $this->post(route('password.email'), ['email' => $user->email])
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * The form is public, so a different response for unknown addresses would
     * let anyone enumerate which clients have accounts.
     */
    public function test_an_unknown_address_gets_the_same_response_and_no_mail(): void
    {
        Notification::fake();

        $known = User::factory()->create();
        $a = $this->post(route('password.email'), ['email' => $known->email]);
        $b = $this->post(route('password.email'), ['email' => 'nobody@example.com']);

        $this->assertSame($a->status(), $b->status());
        $this->assertSame(session()->get('status'), $b->getSession()->get('status'));
        Notification::assertCount(1);
    }

    public function test_a_valid_token_resets_the_password_and_clears_the_temporary_flag(): void
    {
        Notification::fake();
        $user = User::factory()->create(['must_change_password' => true]);

        $this->post(route('password.email'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->post(route('password.store'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'a-brand-new-password',
                'password_confirmation' => 'a-brand-new-password',
            ])->assertRedirect(route('login'));

            return true;
        });

        $user->refresh();
        $this->assertFalse($user->must_change_password);
        $this->assertTrue(auth()->attempt(['email' => $user->email, 'password' => 'a-brand-new-password']));
    }

    public function test_a_bogus_token_is_rejected(): void
    {
        $user = User::factory()->create();

        $this->post(route('password.store'), [
            'token' => 'not-a-real-token',
            'email' => $user->email,
            'password' => 'a-brand-new-password',
            'password_confirmation' => 'a-brand-new-password',
        ])->assertSessionHasErrors('email');
    }

    public function test_short_passwords_are_rejected(): void
    {
        $this->post(route('password.store'), [
            'token' => 'x', 'email' => 'a@b.com',
            'password' => 'short', 'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');
    }
}
