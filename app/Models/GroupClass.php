<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'features',
        'duration_per_class',
        'lessons_per_week',
        'max_students',
        'price_per_student',
        'is_active'
    ];

    protected $casts = [
    'price_per_student' => 'decimal:2',
    'is_active' => 'boolean',
    
];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function days()
    {
        return $this->hasMany(GroupClassDay::class);
    }
}
