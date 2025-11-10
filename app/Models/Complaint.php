<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Complaint extends Model
{
    use HasFactory;
    public $table = 'complaints';

    public $fillable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'main_complaint',
        'main_complaint_progression',
        'direct_questioning',
        'drug_history',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'ipd_id' => 'integer',
        'opd_id' => 'integer',
        'main_complaint' => 'array',
        'main_complaint_progression' => 'array',
        'direct_questioning' => 'array',
        'drug_history' => 'array',
    ];

    public static $rules = [
        'patient_id' => 'nullable',
        'ipd_id' => 'nullable',
        'opd_id' => 'nullable',
        'user_id' => 'nullable',
        'notes' => 'nullable',
        'main_complaint' => 'nullable',
        'main_complaint_progression' => 'nullable',
        'direct_questioning' => 'nullable',
        'drug_history' => 'nullable',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
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
