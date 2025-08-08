<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'documents',
        'headline',
        'hobbies',
        'certifications',
        'experience',
        'teaching_style',
        'about_me',
        'teaches',
        'speaks',
        'country',
        'rate_per_hour',
        'language_id',
        'profile_image',
        'intro_video',
    ];

    // Cast teaches to array for easy handling
    protected $casts = [
        'teaches' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, null, null, null, 'teaches');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
