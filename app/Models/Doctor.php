<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'specialization',
        'qualifications',
        'consultation_fee',
        'available_days',
        'time_slots',
    ];

    protected $casts = [
        'available_days' => 'array',
        'time_slots' => 'array',
        'consultation_fee' => 'decimal:2',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
