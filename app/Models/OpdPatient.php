<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdPatient extends Model
{
    use HasFactory;

    protected $table = 'opd_patient_departments';

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'opd_number',
        'appointment_date',
        'height',
        'weight',
        'bp',
        'pulse',
        'respiration',
        'temperature',
        'oxygen_saturation',
        'symptoms',
        'notes',
        'standard_charge',
        'served',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'served' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(\App\Models\Doctor::class);
    }
}