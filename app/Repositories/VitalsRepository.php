<?php

namespace App\Repositories;

use App\Models\Vital;
use App\Models\Vitals;
use App\Models\Notification;
use App\Models\Patient;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class VitalsRepository
 *
 * @version March 2, 2020, 4:38 am UTC
 */
class VitalsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_id',
        'opd_id',
        'ipd_id',
        'opd_id',
        'bp',
        'pulse',
        'respiration',
        'temperature',
        'oxygen_saturation',
        'height',
        'weight',
        'notes',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Vital::class;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function createNotification($input)
    {
        try {
            $patient = Vital::with('patient.patientUser')->where('patient_id', $input['patient_id'])->first();

            addNotification([
                Notification::NOTIFICATION_TYPE['Advance Payment'],
                $patient->patient->user_id,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $patient->patient->patientUser->full_name.' your vitals added successfully.',
            ]);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
