<?php

namespace App\Mail;

use App\Models\UserLessonTracking;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LessonDeductionRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $package;
    public $student;

    /**
     * Create a new message instance.
     */
    public function __construct(UserLessonTracking $package, User $student)
    {
        $this->package = $package;
        $this->student = $student;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject("Lesson Deduction Request - {$this->student->name}")
            ->markdown('emails.lessons.deduction');
    }
}
