<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'package_number',
        'name',
        'number_of_lessons',
        'duration_per_lesson',
        'price',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
