<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class GeneralExamination extends Model
{
    use HasFactory;
    public $table = 'general_examinations';

    public $fillable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'general_examination',
        'examination_number',
        'lungs_status',
        'lungs_description',
        'cardio_status',
        'cardio_description',
        'abdomen_status',
        'abdomen_description',
        'ear_status',
        'ear_description',
        'nose_status',
        'nose_description',
        'throat_status',
        'throat_description',
        'musco_status',
        'musco_description',
        'nervous_status',
        'nervous_description',
        'skin_status',
        'skin_description',
        'eye_status',
        'eye_description',
    ];

    protected $casts = [
        'id' => 'integer',
        'patient_id' => 'integer',
        'ipd_id' => 'integer',
        'opd_id' => 'integer',
        'general_examination' => 'array',
        'examination_number' => 'array',
        'lungs_status' => 'integer',
        'lungs_description' => 'array',
        'cardio_status' => 'integer',
        'cardio_description' => 'array',
        'abdomen_status' => 'integer',
        'abdomen_description' => 'array',
        'ear_status' => 'integer',
        'ear_description' => 'array',
        'nose_status' => 'integer',
        'nose_description' => 'array',
        'throat_status' => 'integer',
        'throat_description' => 'array',
        'musco_status' => 'integer',
        'musco_description' => 'array',
        'nervous_status' => 'integer',
        'nervous_description' => 'array',
        'skin_status' => 'integer',
        'skin_description' => 'array',
        'eye_status' => 'integer',
        'eye_description' => 'array',
    ];

    public static $rules = [
        'patient_id' => 'nullable',
        'ipd_id' => 'nullable',
        'opd_id' => 'nullable',
        'general_examination' => 'nullable',
        'examination_number' => 'nullable',
        'lungs_status' => 'nullable',
        'lungs_description' => 'nullable',
        'cardio_status' => 'nullable',
        'cardio_description' => 'nullable',
        'abdomen_status' => 'nullable',
        'abdomen_description' => 'nullable',
        'ear_status' => 'nullable',
        'ear_description' => 'nullable',
        'nose_status' => 'nullable',
        'nose_description' => 'nullable',
        'throat_status' => 'nullable',
        'throat_description' => 'nullable',
        'musco_status' => 'nullable',
        'musco_description' => 'nullable',
        'nervous_status' => 'nullable',
        'nervous_description' => 'nullable',
        'skin_status' => 'nullable',
        'skin_description' => 'nullable',
        'eye_status' => 'nullable',
        'eye_description' => 'nullable',

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

    public static function generateUniqueExaminationNumber()
    {
        $examinationNumber = mb_strtoupper(Str::random(6));
        while (true) {
            $isExist = self::whereExaminationNumber($examinationNumber)->exists();
            if ($isExist) {
                self::generateUniqueExaminationNumber();
            }
            break;
        }

        return $examinationNumber;
    }
}
