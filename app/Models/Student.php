<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'langauages_i_can_speak',
        'hobbies',
        'learning_goals',
        'profile_image',
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
