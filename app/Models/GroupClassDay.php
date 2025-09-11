<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupClassDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_class_id',
        'day',
        'time'
    ];

    protected $casts = [
        'day' => 'date',    // automatically cast to Carbon instance
        'time' => 'string', // time can be cast as string
    ];

    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class);
    }
    public function days()
{
    return $this->hasMany(GroupClassDay::class);
}

}
