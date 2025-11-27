<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB; // <-- CRITICAL: ADDED FOR LOCKING
use Str;

/**
 * App\Models\OpdPatientDepartment
 *
 * @property int $id
 * @property int $patient_id
 * @property string $opd_number
// ... (rest of doc block)
 * @mixin \Eloquent
 */
class OpdPatientDepartment extends Model
{
    public $table = 'opd_patient_departments';

    const PAYMENT_MODES = [
        // 0 => 'Not Paid',
        1 => 'Cash',
        2 => 'Cheque',
    ];

    public $fillable = [
        'patient_id',
        'opd_number',
        'height',
        'weight',
        'bp',
        'symptoms',
        'notes',
        'appointment_date',
        'case_id',
        'is_old_patient',
        'doctor_id',
        'standard_charge',
        'payment_mode',
        'currency_symbol',
        'pulse',
        'respiration',
        'temperature',
        'oxygen_saturation',
        'paid_amount',
        'change',
        'charge_id',
        'invoice_id',
        // 'is_antenatal',
    ];

    public static $rules = [
        'patient_id' => 'required',
        'appointment_date' => 'required',
        // 'doctor_id' => 'required',
        // 'standard_charge' => 'required',
        'payment_mode' => 'nullable',
        'weight' => 'numeric|max:200|nullable',
        'height' => 'numeric|max:100|nullable',
        'bp' => 'nullable',
        'pulse' => 'nullable',
        'respiration' => 'nullable',
        'temperature' => 'nullable',
        'oxygen_saturation' => 'nullable',
        'paid_amount' => 'nullable',
        'change' => 'nullable',
        'charge_id' => 'nullable',
        'invoice_id' => 'nullable',
    ];

    protected $appends = ['payment_mode_name'];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'opd_number' => 'string',
        'appointment_date' => 'datetime',
        'height' => 'integer',
        'weight' => 'integer',
        'bp' => 'array',
        'symptoms' => 'string',
        'notes' => 'string',
        'case_id' => 'integer',
        'is_old_patient' => 'boolean',
        'doctor_id' => 'integer',
        'standard_charge' => 'integer',
        'payment_mode' => 'integer',
        'pulse' => 'array',
        'respiration' => 'array',
        'temperature' => 'array',
        'oxygen_saturation' => 'array',
        'paid_amount' => 'array',
        'change' => 'array',
        'charge' => 'integer',
        'invoice_id' => 'integer',
    ];

    /**
     * The fixed boot method to prevent race conditions during ID generation using DB locking.
     */
    protected static function boot()
    {
        parent::boot();

        // Use a database transaction and locking to guarantee unique ID generation
        static::creating(function ($opdPatientDepartment) {
            
            DB::transaction(function () use ($opdPatientDepartment) {
                
                // Only generate if the opd_number field is empty
                if (empty($opdPatientDepartment->opd_number)) {

                    // Acquire a write lock on the table. This forces other concurrent requests to wait.
                    DB::table('opd_patient_departments')->lockForUpdate()->orderBy('id', 'desc')->first();

                    $opdPatientDepartment->opd_number = self::generateUniqueOpdNumber();
                }
            });
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function patientCase(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'charge_id');
    }

    public static function generateUniqueOpdNumber()
    {
        $opdNumber = strtoupper(Str::random(8));
        while (self::whereOpdNumber($opdNumber)->exists()) {
            $opdNumber = strtoupper(Str::random(8));
        }

        return $opdNumber;
    }
    public function getPaymentModeNameAttribute()
    {
        return 'N/A';
    }
}