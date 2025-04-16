<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnershipInquiry extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Partnership Inquiry from ' . $this->data['name'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partnership-inquiry',
            with: [
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'message' => $this->data['message'],
            ],
        );
    }
} 