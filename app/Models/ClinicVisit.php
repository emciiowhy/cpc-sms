<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicVisit extends Model
{
    protected $fillable = [
        'student_name',
        'student_id',
        'complaint',
        'treatment',
        'status',
        'visit_date',
        'visit_time',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}