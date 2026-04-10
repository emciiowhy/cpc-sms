<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_name',
        'student_id',
        'email',
        'course',
        'year_level',
        'section',
        'status',
        'remarks',
        'created_by',
    ];

    /**
     * Get the user (Admin or Registrar) who created this enrollment record.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}