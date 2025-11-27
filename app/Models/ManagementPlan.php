<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagementPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'opd_id',
        'ipd_id',
        'user_id',
        'management_plan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'patient_id' => 'integer',
        'opd_id' => 'integer',
        'ipd_id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * Get the OPD patient department this plan belongs to.
     */
    public function opdPatientDepartment(): BelongsTo
    {
        return $this->belongsTo(OpdPatientDepartment::class, 'opd_id');
    }

    /**
     * Get the User (Doctor/Nurse) who created the plan.
     */
    public function user(): BelongsTo
    {
        // Assuming your 'users' table is linked via the 'user_id'
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the Patient this plan belongs to.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}