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
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
