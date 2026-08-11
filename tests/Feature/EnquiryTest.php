<?php

namespace Tests\Feature;

use App\Mail\EnquiryReceived;
use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquiryTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Taken from the configured list rather than written out, because that
     * list is the validation rule — a hardcoded type turns every future edit
     * to the service names into a failing suite, which is how this broke when
     * the six building types became the service list. Which type is used does
     * not matter here; that only a configured one is accepted is covered by
     * test_property_type_must_come_from_the_configured_list.
     */
    private function validProjectType(): string
    {
        return config('site.inquiry.property_types')[0];
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+971501234567',
            'project_type' => $this->validProjectType(),
            'location' => 'Jumeirah',
            'project_brief' => 'New villa Fit-Out',
        ], $overrides);
    }

    public function test_a_valid_enquiry_is_stored_and_emailed(): void
    {
        Mail::fake();

        $this->post(route('enquiries.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('enquiry_status');

        $this->assertDatabaseHas('enquiries', [
            'email' => 'test@example.com',
            'project_type' => $this->validProjectType(),
            'status' => 'new',
        ]);

        Mail::assertSent(EnquiryReceived::class);
    }

    public function test_invalid_input_is_rejected_and_nothing_is_stored(): void
    {
        $this->post(route('enquiries.store'), $this->payload([
            'name' => 'A',
            'email' => 'not-an-email',
            'phone' => 'abc',
        ]))->assertSessionHasErrors(['name', 'email', 'phone']);

        $this->assertSame(0, Enquiry::count());
    }

    public function test_property_type_must_come_from_the_configured_list(): void
    {
        $this->post(route('enquiries.store'), $this->payload(['project_type' => 'Submarine']))
            ->assertSessionHasErrors('project_type');

        $this->assertSame(0, Enquiry::count());
    }

    /** The honeypot is hidden from users, so anything in it came from a bot. */
    public function test_a_filled_honeypot_is_rejected(): void
    {
        $this->post(route('enquiries.store'), $this->payload(['company' => 'spam']))
            ->assertSessionHasErrors('company');

        $this->assertSame(0, Enquiry::count());
    }

    /**
     * The row is committed before the email is attempted, so a broken mailbox
     * degrades to a saved lead rather than asking the visitor to resubmit.
     */
    public function test_the_enquiry_survives_a_failing_mailer(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        $this->post(route('enquiries.store'), $this->payload())
            ->assertRedirect()
            ->assertSessionHas('enquiry_status');

        $this->assertSame(1, Enquiry::count());
    }
}
