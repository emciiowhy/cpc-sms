<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_name',
        'student_id',
        'purpose',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'appointment_date' => 'date',
    ];

    /**
     * Get the Guidance Counselor or Admin who created the appointment.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}