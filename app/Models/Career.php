<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $table = 'careers';

    protected $fillable = [
        'title',
        'location',
        'salary',
        'type',
        'description',
        'is_active',
    ];
}
