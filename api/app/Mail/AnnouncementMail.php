<?php

namespace App\Mail;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

// 授業のお知らせを、履修している学生1人に届けるメール
class AnnouncementMail extends Mailable
{
    public function __construct(public Announcement $announcement, public User $student)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "【{$this->announcement->course->name}】{$this->announcement->title}");
    }

    public function content(): Content
    {
        return new Content(view: 'mail.announcement');
    }
}
