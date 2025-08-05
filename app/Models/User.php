<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'verification_code',
        'verification_code_expires_at',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class);
    }

    public function lessonPackages()
    {
        return $this->hasMany(LessonPackage::class, 'teacher_id');
    }

    public function teacherSettings()
    {
        return $this->hasMany(TeacherSetting::class, 'teacher_id');
    }

    public function groupClasses()
    {
        return $this->hasMany(GroupClass::class, 'teacher_id');
    }


    // Chat relationships
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Get all messages between this user and another user
    public function messagesWithUser($userId)
    {
        return Message::where(function ($query) use ($userId) {
            $query->where('sender_id', $this->id)
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', $this->id);
        })->orderBy('created_at');
    }

    // Get the last message with a specific user
    public function lastMessageWith($userId)
    {
        return $this->messagesWithUser($userId)->latest()->first();
    }

    // Get unread message count from a specific user
    public function unreadMessagesFrom($userId)
    {
        return $this->receivedMessages()
            ->where('sender_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    public function zoomMeetings()
    {
        return $this->hasMany(ZoomMeeting::class, 'teacher_id');
    }


    // User.php

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class, 'teacher_id');
    }

    /**
     * Get monthly availability grouped by dates.
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getMonthlyAvailability(int $year, int $month): array
    {
        // Start and end date of the month
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth()->endOfDay();

        // Fetch availability slots in date range
        $slots = $this->availabilities()
            ->whereBetween('available_date', [$startDate, $endDate])
            ->where('is_available', true)
            ->where('status', 'available')
            ->get();

        // Group by date string
        $grouped = $slots->groupBy(function ($item) {
            return $item->available_date->format('Y-m-d');
        })->map(function ($items) {
            // Map slots per date to whatever data frontend expects,
            // e.g. just presence or list of slots — here we return array of slot ids to denote availability
            return $items->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time->format('H:i:s'),
                    'end_time' => $slot->end_time->format('H:i:s'),
                    'formatted_range' => $slot->formatted_time_range,
                    'is_available' => $slot->is_available,
                ];
            })->toArray();
        })->toArray();

        return $grouped;
    }

    /**
     * Get availability slots for a specific date.
     *
     * @param string $date — format 'YYYY-MM-DD'
     * @return \Illuminate\Support\Collection
     */
    public function getAvailabilityForDate(string $date)
    {
        return $this->availabilities()
            ->whereDate('available_date', $date)
            ->where('is_available', true)
            ->where('status', 'available')
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'formatted_range' => $slot->formatted_time_range,
                    'is_available' => $slot->is_available,
                ];
            });
    }
}
