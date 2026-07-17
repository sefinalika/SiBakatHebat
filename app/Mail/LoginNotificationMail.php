<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            to: $this->user->email,
            subject: 'Notifikasi Login - Si Bakat Hebat',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-notification',
            with: [
                'userName' => $this->user->name,
                'loginTime' => now()->locale('id')->format('d F Y H:i:s'),
                'ipAddress' => request()->ip(),
            ],
        );
    }
}
