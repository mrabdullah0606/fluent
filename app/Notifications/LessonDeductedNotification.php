<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LessonDeductedNotification extends Notification
{
    use Queueable;

    protected $package;
    protected $student;
    protected $type;

    public function __construct($package, $student, $type = 'deduction')
    {
        $this->package = $package;
        $this->student = $student;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database']; // store in notifications table
    }

    public function toArray($notifiable)
    {
        $baseData = [
            'student_id' => $this->student->id,
            'student_name' => $this->student->name,
            'type' => $this->type,
        ];

        // For deduction requests - has package_summary and id
        if (isset($this->package->package_summary) && isset($this->package->id)) {
            $baseData['package_id'] = $this->package->id;
            $baseData['package_summary'] = $this->package->package_summary;
        }

        // For cancel/reschedule actions - has meeting_id and meeting details
        if (isset($this->package->meeting_id) && in_array($this->type, ['cancel', 'reschedule'])) {
            $baseData['meeting_id'] = $this->package->meeting_id;
            $baseData['meeting_topic'] = $this->package->meeting_topic ?? 'Lesson';
            $baseData['meeting_time'] = $this->package->meeting_time ?? '';

            // Add package details if available for cancel/reschedule
            if (isset($this->package->package_summary)) {
                $baseData['package_summary'] = $this->package->package_summary;
            }
            if (isset($this->package->package_id)) {
                $baseData['package_id'] = $this->package->package_id;
            }
        }

        switch ($this->type) {
            case 'reschedule':
                $meetingDetails = isset($this->package->meeting_topic) ?
                    "\"{$this->package->meeting_topic}\"" : "lesson";
                $timeDetails = isset($this->package->meeting_time) ?
                    " (originally scheduled for {$this->package->meeting_time})" : "";

                $message = "{$this->student->name} rescheduled {$meetingDetails}{$timeDetails}";
                if (isset($this->package->rescheduled_time)) {
                    $message .= " to {$this->package->rescheduled_time}";
                    $baseData['rescheduled_time'] = $this->package->rescheduled_time;
                }
                $message .= ".";
                $icon = 'bi bi-arrow-repeat text-warning';
                break;

            case 'cancel':
                $meetingDetails = isset($this->package->meeting_topic) ?
                    "\"{$this->package->meeting_topic}\"" : "lesson";
                $timeDetails = isset($this->package->meeting_time) ?
                    " scheduled for {$this->package->meeting_time}" : "";

                $message = "{$this->student->name} cancelled {$meetingDetails}{$timeDetails}.";
                $icon = 'bi bi-x-circle text-danger';
                break;

            default: // deduction
                if (isset($this->package->package_summary)) {
                    $message = "{$this->student->name} requested a lesson deduction from {$this->package->package_summary}.";
                } else {
                    $message = "{$this->student->name} requested a lesson deduction.";
                }
                $icon = 'bi bi-bell text-primary';
                break;
        }

        $baseData['message'] = $message;
        $baseData['icon'] = $icon;

        return $baseData;
    }
}
