<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupClassDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_class_id',
        'day'
    ];

    public function groupClass()
    {
        return $this->belongsTo(GroupClass::class);
    }
}
