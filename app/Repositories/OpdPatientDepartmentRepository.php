<?php

namespace App\Repositories;

use App\Models\Charge;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\OpdPatientDepartment;
use App\Models\OpdConsultantRegister;
use App\Models\Patient;
use App\Models\PatientCase;
use App\Models\OpdTimeline;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Carbon\Carbon; // Ensure Carbon is available if you need it for future repository changes

/**
 * Class OpdPatientDepartmentRepository
 *
 * @version September 8, 2020, 6:42 am UTC
 */
class OpdPatientDepartmentRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'patient_id',
        'ipd_number',
        'height',
        'weight',
        'bp',
        'symptoms',
        'notes',
        'admission_date',
        'case_id',
        'is_old_patient',
        'doctor_id',
        'standard_charge',
        'payment_mode',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return OpdPatientDepartment::class;
    }

    public function getAssociatedData($selectedPatientId = null)
    {
        // Retrieve patients with their associated user (for gender)
        $data['patients'] = Patient::with('patientUser') 
            ->whereHas('patientUser', function ($query) {
                $query->where('status', 1);
            })
            ->get()
            ->mapWithKeys(function ($patient) {
                return [
                    $patient->id => [
                        'name' => $patient->patientUser->full_name,
                        'gender' => $patient->patientUser->gender, // Getting gender from the User model
                    ]
                ];
            });

        // Retrieve the selected patient data based on the passed patient_id (if available)
        $selectedPatient = null;
        if ($selectedPatientId) {
            $selectedPatient = Patient::with('patientUser')->find($selectedPatientId);
            if ($selectedPatient) {
                $data['selectedPatient'] = [
                    'id' => $selectedPatient->id,
                    'name' => $selectedPatient->patientUser->full_name,
                    'gender' => $selectedPatient->patientUser->gender, // Get gender from User model
                ];
            }
        }

        // Other data
        $data['doctors'] = Doctor::with('doctorUser')
            ->whereHas('doctorUser', function ($query) {
                $query->where('status', 1);
            })
            ->get()
            ->pluck('doctorUser.full_name', 'id')
            ->sort();

        $data['charges'] = Charge::with('chargeCategory')
            ->where('charge_type', 8)
            ->get()
            ->pluck('chargeCategory.name', 'id')
            ->sort();

        $data['opdNumber'] = $this->model->generateUniqueOpdNumber();
        $data['paymentMode'] = $this->model::PAYMENT_MODES;

        return $data;
    }

    public function getPatientCases($patientId)
    {
        return PatientCase::where('patient_id', $patientId)->where('status', 1)->pluck('case_id', 'id');
    }

    public function getOPDTimeline($id)
    {
        if (\Auth::user()->hasRole('Admin')) {
            return OpdTimeline::where('opd_patient_department_id', $id)->latest()->take(2)->get();
        }

        return OpdTimeline::where('opd_patient_department_id', $id)->latest()->take(2)->visible()->get();
    }

    public function getDoctorsData()
    {
        return Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id');
    }

    public function getDoctorsList()
    {
        $result = Doctor::with('doctorUser')->get()
            ->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->toArray();

        $doctors = [];
        foreach ($result as $key => $item) {
            $doctors[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $doctors;
    }

    // Removed the public function store($input) method since the controller handles creation directly.
    // If you need the validation, it is now implemented in the controller.
    
    public function storeWithInvoice($input)
    {
        try {
            $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
            // OpdPatientDepartment::create($input);

            $opdPatientDepartment = OpdPatientDepartment::create($input);
            // Retrieve the ID of the created record
            $createdRecordId = $opdPatientDepartment->id;
            
            $input['patient_id'] = $opdPatientDepartment->patient_id;

            $bill = $this->invoiceRepository->saveInvoicePatient($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function updateOpdPatientDepartment($input, $opdPatientDepartment)
    {
        try {
            $input['is_old_patient'] = isset($input['is_old_patient']) ? true : false;
            $opdPatientDepartment->update($input);
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

        return true;
    }

    public function createNotification($input)
    {
        try {
            // Ensure patient and doctor details are retrieved safely
            $patient = Patient::with('patientUser')->where('id', $input['patient_id'])->first();
            
            // Added check for doctor existence before accessing properties
            $doctorData = Doctor::with('doctorUser')->where('id', $input['doctor_id'])->first();
            $doctorName = $doctorData ? $doctorData->doctorUser->full_name : 'a doctor';

            if (isset($input['revisit'])) {
                $title = ($patient->patientUser->full_name ?? 'Patient').' you are visited doctor '.$doctorName.'.';
            } else {
                $title = ($patient->patientUser->full_name ?? 'Patient').' your OPD record has been created.';
            }

            // Assumes addNotification helper function is globally defined and works
            addNotification([
                Notification::NOTIFICATION_TYPE['OPD Patient'],
                $patient->user_id ?? null,
                Notification::NOTIFICATION_FOR[Notification::PATIENT],
                $title,
            ]);
        } catch (Exception $e) {
            // Throwing the exception will help debug the notification ID issue if it persists
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }
}