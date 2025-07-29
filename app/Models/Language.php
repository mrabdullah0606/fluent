<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'name',
        'symbol',
    ];

    /**
     * Get the tutors associated with the language.
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
