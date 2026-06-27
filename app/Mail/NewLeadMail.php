<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Lead '.$this->lead->reference_id,
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'mail.new-lead',
            with: [
                'lead' => $this->lead,
            ],
        );
    }
}
