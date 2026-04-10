<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingRecord extends Model
{
    protected $fillable = [
        'student_name',
        'student_id',
        'course',
        'year_level',
        'category',
        'concern',
        'action_taken',
        'status',
        'session_date',
        'created_by',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}