<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $type;

    /**
     * Create a new message instance.
     *
     * @param int|string $code
     * @param string $type Can be 'register', 'password_reset', 'change_username', or 'change_password'
     */
    public function __construct($code, $type = 'register')
    {
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * Build the message using HTML layout.
     */
    public function build()
    {
        $subject = 'Verify Your Email Address';
        $message = "Thank you for registering. Your verification code is: <strong>{$this->code}</strong>";

        if ($this->type === 'password_reset') {
            $subject = 'Reset Your Password';
            $message = "You requested a password reset. Your verification code is: <strong>{$this->code}</strong>";
        } elseif ($this->type === 'change_username') {
            $subject = 'Verify Username Change';
            $message = "You requested to change your username. Your verification code is: <strong>{$this->code}</strong>";
        } elseif ($this->type === 'change_password') {
            $subject = 'Verify Password Change';
            $message = "You requested to change your password. Your verification code is: <strong>{$this->code}</strong>";
        }

        return $this->subject($subject)
                    ->html("<p>{$message}</p><p>This code will expire in 15 minutes.</p>");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Verify Your Email Address';

        if ($this->type === 'password_reset') {
            $subject = 'Reset Your Password';
        } elseif ($this->type === 'change_username') {
            $subject = 'Verify Username Change';
        } elseif ($this->type === 'change_password') {
            $subject = 'Verify Password Change';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
