<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactRequest $contactRequest,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Contact Request '.$this->contactRequest->reference_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.contact-request',
            with: [
                'contact' => $this->contactRequest,
            ],
        );
    }
}
