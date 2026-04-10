<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'student_name',
        'student_id',
        'course',
        'year_level',
        'medical_history',
        'allergies',
        'blood_type',
        'height',
        'weight',
        'status',
        'attachment',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}