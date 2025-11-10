<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    use HasFactory;
    public $table = 'treatments';

    public $fillable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'user_id',
        'treatment',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'ipd_id' => 'integer',
        'opd_id' => 'integer',
        'user_id' => 'integer',
        'treatment' => 'array',
    ];

    public static $rules = [
        'patient_id' => 'nullable',
        'ipd_id' => 'nullable',
        'opd_id' => 'nullable',
        'user_id' => 'nullable',
        'treatment' => 'nullable',

    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ipd(): BelongsTo
    {
        return $this->belongsTo(IpdPatientDepartment::class, 'ipd_id');
    }
    public function opd(): BelongsTo
    {
        return $this->belongsTo(OpdPatientDepartment::class, 'opd_id');
    }
}
