<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZoomMeeting extends Model
{
    protected $fillable = [
        'uuid',
        'meeting_id',
        'host_id',
        'topic',
        'start_time',
        'duration',
        'timezone',
        'join_url',
        'start_url',
        'password',
        'raw_response',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'raw_response' => 'array',
    ];

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'zoom_meeting_user')
            ->withPivot('teacher_id')
            ->withTimestamps();
    }
}
