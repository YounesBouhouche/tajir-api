<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     */
    public function __construct(public int $code)
    {

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Code Reset Password',
        );
    }

    /**
     * Get the message content definition.
     */
    public function build(): Mailable
    {
        return $this->markdown('auth.password-reset', ['code' => $this->code]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
