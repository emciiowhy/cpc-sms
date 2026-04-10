<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_name',
        'student_id',
        'subject',
        'course',
        'year_level',
        'midterm',
        'finals',
        'average',
        'remarks',
        'created_by',
    ];

    /**
     * Get the user (Admin or Registrar) who encoded these grades.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Auto-compute average and remarks before saving to the database.
     */
    protected static function booted(): void
    {
        static::saving(function (Grade $grade) {
            // Logic: (Midterm + Finals) / 2
            $grade->average = round(($grade->midterm + $grade->finals) / 2, 2);
            
            // Logic: Standard passing mark of 75
            $grade->remarks = $grade->average >= 75 ? 'pass' : 'fail';
        });
    }
}