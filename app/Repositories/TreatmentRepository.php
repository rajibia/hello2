<?php

namespace App\Repositories;

use App\Models\Treatment;
use App\Models\Notification;
use App\Models\Patient;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class TreatmentRepository
 *
 * @version March 2, 2020, 4:38 am UTC
 */
class TreatmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'user_id',
        'notes',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Treatment::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function createNotification($input)
    {
        try {
            $patient = Treatment::with('patient.patientUser')->where('patient_id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['Nursing Progress Notes'],
                $patient->patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patient->patientUser->full_name.' your Nursing Progress Notes added successfully.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
