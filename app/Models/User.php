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

    public function bookingRules()
    {
        return $this->hasOne(BookingRule::class, 'teacher_id');
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
        return $this->belongsToMany(ZoomMeeting::class, 'zoom_meeting_user')
            ->withPivot('teacher_id')
            ->withTimestamps();
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


    public function teacherWallet()
    {
        return $this->hasOne(TeacherWallet::class, 'teacher_id');
    }

    public function paymentSettings()
    {
        return $this->hasOne(TeacherPaymentSetting::class, 'teacher_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'teacher_id');
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WithdrawalRequest::class, 'teacher_id');
    }
    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }


    // As a student - lesson packages purchased
    public function lessonTrackings()
    {
        return $this->hasMany(UserLessonTracking::class, 'student_id');
    }

    // As a teacher - students' lesson packages
    public function studentLessonTrackings()
    {
        return $this->hasMany(UserLessonTracking::class, 'teacher_id');
    }

    // As a student - lesson attendance records
    public function lessonAttendances()
    {
        return $this->hasMany(LessonAttendance::class, 'student_id');
    }

    // As a teacher - taught lessons
    public function taughtLessons()
    {
        return $this->hasMany(LessonAttendance::class, 'teacher_id');
    }

    // Active lesson packages as student
    public function activeLessonPackages()
    {
        return $this->lessonTrackings()
            ->where('status', 'active')
            ->where('lessons_remaining', '>', 0);
    }

    // Get total lessons remaining across all packages
    public function getTotalLessonsRemainingAttribute()
    {
        return $this->activeLessonPackages()->sum('lessons_remaining');
    }

    // Get attendance rate as student
    public function getAttendanceRateAttribute()
    {
        $totalLessons = $this->lessonAttendances()->count();
        if ($totalLessons === 0) return 0;

        $attendedLessons = $this->lessonAttendances()
            ->where('attendance_status', 'attended')
            ->count();

        return round(($attendedLessons / $totalLessons) * 100, 1);
    }

    // Get total lessons taken as student
    public function getTotalLessonsTakenAttribute()
    {
        return $this->lessonTrackings()->sum('lessons_taken');
    }

    // Get total lessons purchased as student
    public function getTotalLessonsPurchasedAttribute()
    {
        return $this->lessonTrackings()->sum('total_lessons_purchased');
    }

    // Check if user has any active lesson packages
    public function hasActiveLessons()
    {
        return $this->activeLessonPackages()->exists();
    }

    // Get next expiring package
    public function getNextExpiringPackage()
    {
        return $this->activeLessonPackages()
            ->whereNotNull('expiry_date')
            ->orderBy('expiry_date')
            ->first();
    }

    // As teacher - get students with low lesson counts
    public function getStudentsWithLowLessons($threshold = 2)
    {
        return $this->studentLessonTrackings()
            ->where('status', 'active')
            ->where('lessons_remaining', '<=', $threshold)
            ->with('student')
            ->get();
    }

    // As teacher - get active students count
    public function getActiveStudentsCount()
    {
        return $this->studentLessonTrackings()
            ->where('status', 'active')
            ->where('lessons_remaining', '>', 0)
            ->distinct('student_id')
            ->count('student_id');
    }

    // As teacher - get total revenue from lesson packages
    public function getLessonPackageRevenue()
    {
        return $this->studentLessonTrackings()
            ->join('payments', 'user_lesson_tracking.payment_id', '=', 'payments.id')
            ->where('payments.status', 'successful')
            ->sum('payments.total');
    }
}
