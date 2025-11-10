<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DeathReport
 *
 * @version February 18, 2020, 11:10 am UTC
 *
 * @property int $id
 * @property int $patient_id
 * @property int $doctor_id
 * @property string $date
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Doctor $doctor
 * @property-read \App\Models\User $patient
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereDoctorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string $case_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\DeathReport whereCaseId($value)
 *
 * @property int $is_default
 * @property-read \App\Models\PatientCase $caseFromDeathReport
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DeathReport whereIsDefault($value)
 */
class DeathReport extends Model
{
    public $table = 'death_reports';

    public $fillable = [
        'patient_id',
        'case_id',
        'doctor_id',
        'date',
        'description',
        'cause_of_death',
        'immediate_cause_of_death',
        'location_of_death',
        'next_of_kin',
        'next_of_kin_contact',
        'attachments',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'case_id' => 'string',
        'doctor_id' => 'integer',
        'cause_of_death' => 'string',
        'immediate_cause_of_death' => 'string',
        'location_of_death' => 'string',
        'next_of_kin' => 'string',
        'next_of_kin_contact' => 'string',
        'attachments' => 'string', // File path or URL
    ];

    public static $rules = [
        'case_id' => 'required|unique:death_reports,case_id',
        'doctor_id' => 'required',
        'date' => 'required',
        'description' => 'nullable|string',
        'cause_of_death' => 'nullable|string|max:255',
        'immediate_cause_of_death' => 'nullable|string|max:255',
        'location_of_death' => 'nullable|string|max:255',
        'next_of_kin' => 'nullable|string|max:255',
        'next_of_kin_contact' => 'nullable|string|max:15', // Assuming a phone number format
        'attachments' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Max size: 2MB
    ];

    public function prepareData()
    {
        return [
            'id' => $this->id,
            'patient_name' => $this->patient && $this->patient->patientUser ? $this->patient->patientUser->full_name : __('messages.common.n/a'),
            'patient_image' => $this->patient && $this->patient->patientUser ? $this->patient->patientUser->getApiImageUrlAttribute() : null,
            'case_id' => $this->caseFromDeathReport ? $this->caseFromDeathReport->case_id : __('messages.common.n/a'),
            'date' => isset($this->date) ? \Carbon\Carbon::parse($this->date)->translatedFormat('jS M, Y') : __('messages.common.n/a'),
            'time' => isset($this->date) ? \Carbon\Carbon::parse($this->date)->isoFormat('LT') : __('messages.common.n/a'),
            'cause_of_death' => $this->cause_of_death ?? __('messages.common.n/a'),
            'immediate_cause_of_death' => $this->immediate_cause_of_death ?? __('messages.common.n/a'),
            'location_of_death' => $this->location_of_death ?? __('messages.common.n/a'),
            'next_of_kin' => $this->next_of_kin ?? __('messages.common.n/a'),
            'next_of_kin_contact' => $this->next_of_kin_contact ?? __('messages.common.n/a'),
            'attachments' => $this->attachments ? asset('storage/' . $this->attachments) : null,
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function caseFromDeathReport(): BelongsTo
    {
        return $this->belongsTo(PatientCase::class, 'case_id', 'case_id');
    }
}
