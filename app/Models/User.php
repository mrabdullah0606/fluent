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
}
