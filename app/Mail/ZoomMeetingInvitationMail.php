<?php

namespace App\Mail;

use App\Models\ZoomMeeting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ZoomMeetingInvitationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $meeting;

    public function __construct(ZoomMeeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function build()
    {
        return $this->subject("Zoom Meeting Invitation - {$this->meeting->topic}")
            ->markdown('emails.zoom.invitation');
    }
}
