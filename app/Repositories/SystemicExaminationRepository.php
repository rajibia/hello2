<?php

namespace App\Repositories;

use App\Models\SystemicExamination;
use App\Models\Notification;
use App\Models\Patient;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SystemicExaminationRepository
 *
 * @version March 2, 2020, 4:38 am UTC
 */
class SystemicExaminationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_id',
        'opd_id',
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

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return SystemicExamination::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function createNotification($input)
    {
        try {
            $patient = SystemicExamination::with('patient.patientUser')->where('patient_id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['Systemic Examinations'],
                $patient->patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patient->patientUser->full_name.' your Systemic Examinations added successfully.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
