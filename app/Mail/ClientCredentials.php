<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $client, public string $password) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your Sama Al Tariq client portal access');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.client-credentials');
    }
}
