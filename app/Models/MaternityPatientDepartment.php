<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MaternityPatientDepartment
 * @package App\Models
 * @version December 16, 2024, 12:00 pm UTC
 */
class MaternityPatientDepartment extends Model
{
    use HasFactory;

    public $table = 'maternity';

    public $fillable = [
        'patient_id',
        'doctor_id',
        'admission_date',
        'discharge_date',
        'status',
        'note',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    public static $rules = [
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:doctors,id',
        'admission_date' => 'required|date',
        'discharge_date' => 'nullable|date|after:admission_date',
        'status' => 'required|in:admitted,discharged,pending',
        'note' => 'nullable|string',
    ];

    /**
     * Get the patient that owns the maternity department record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Get the doctor that owns the maternity department record.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
