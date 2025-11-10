<?php

namespace App\Repositories;

use App\Models\GeneralExamination;
use App\Models\Notification;
use App\Models\Patient;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class GeneralExaminationRepository
 *
 * @version March 2, 2020, 4:38 am UTC
 */
class GeneralExaminationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'general_examination',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return GeneralExamination::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function createNotification($input)
    {
        try {
            $patient = GeneralExamination::with('patient.patientUser')->where('patient_id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['General Examination'],
                $patient->patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patient->patientUser->full_name.' your general examination added successfully.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
