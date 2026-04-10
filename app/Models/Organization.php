<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name', 'acronym', 'description',
        'adviser', 'status', 'created_by',
    ];

    public function members()
    {
        return $this->hasMany(OrganizationMember::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}