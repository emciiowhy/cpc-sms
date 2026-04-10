<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    protected $fillable = [
        'organization_id', 'student_name',
        'student_id', 'role', 'course', 'year_level',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}