<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LessonDeductedNotification extends Notification
{
    use Queueable;

    protected $package;
    protected $student;

    public function __construct($package, $student)
    {
        $this->package = $package;
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['database']; // store in notifications table
    }

    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'package_id' => $this->package->id,
            'package_summary' => $this->package->package_summary,
            'message' => $this->student->name . ' deducted a lesson from ' . $this->package->package_summary,
        ];
    }
}
