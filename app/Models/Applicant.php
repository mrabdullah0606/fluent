<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    // App\Models\Applicant
    protected $fillable = [
        'career_id',
        'fullName',
        'email',
        'phone',
        'linkedin',
        'portfolio',
        'cv_path',
        'coverLetter',
        'whyFit',
        'expectedSalary',
        'status',
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }
}
