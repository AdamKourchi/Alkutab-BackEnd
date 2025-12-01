<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TeacherInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'دعوة للإنضمام إلى منصة الوحيين',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.teacher_invite',
            with: [
                'name' => $this->user->name,
                'url' => env('FRONTEND_URL') . '/accept-invite?token=' . $this->user->invite_token
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
