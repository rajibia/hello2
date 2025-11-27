<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OpdPatientDepartment;
use Illuminate\Support\Str;

class OpdPatientController extends Controller
{
    /**
     * Store a new OPD patient
     */
    public function store(Request $request)
    {
        // === 1. VALIDATE INPUT ===
        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'doctor_id'         => 'required|exists:doctors,id',
            'appointment_date'  => 'required|date',
            'standard_charge'   => 'required|numeric|min:0',
            'payment_mode'      => 'required|integer|between:0,4', // 0=Cash, 1=Card, etc.
            'height'            => 'nullable|string|max:191',
            'weight'            => 'nullable|string|max:191',
            'bp'                => 'nullable|string',
            'pulse'             => 'nullable|string',
            'respiration'       => 'nullable|string',
            'temperature'       => 'nullable|numeric',
            'oxygen_saturation' => 'nullable|numeric',
            'symptoms'          => 'nullable|string',
            'notes'             => 'nullable|string',
        ]);

        $patientId = $request->patient_id;
        $appointmentDate = $request->appointment_date;

        // === 2. CHECK FOR ACTIVE (not served) VISIT ON SAME DAY ===
        $exists = OpdPatientDepartment::where('patient_id', $patientId)
            ->where('served', 0)
            ->whereDate('appointment_date', date('Y-m-d', strtotime($appointmentDate)))
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Patient already has an active OPD visit today.'
            ], 422);
        }

        // === 3. SAVE NEW OPD RECORD ===
        try {
            $opd = new OpdPatientDepartment();

            // Generate unique OPD number
            $opd->opd_number = OpdPatientDepartment::generateUniqueOpdNumber();

            // Required fields
            $opd->patient_id       = $patientId;
            $opd->doctor_id        = $request->doctor_id;
            $opd->appointment_date = $appointmentDate;
            $opd->standard_charge  = $request->standard_charge;
            $opd->payment_mode     = $request->payment_mode;

            // Optional fields
            $opd->height            = $request->height;
            $opd->weight            = $request->weight;
            $opd->bp                = $request->bp;
            $opd->pulse             = $request->pulse;
            $opd->respiration       = $request->respiration;
            $opd->temperature       = $request->temperature;
            $opd->oxygen_saturation = $request->oxygen_saturation;
            $opd->symptoms          = $request->symptoms;
            $opd->notes             = $request->notes;

            // Default values
            $opd->is_old_patient = 0;
            $opd->is_antenatal   = 0;
            $opd->served         = 0;

            $opd->save();

            return response()->json([
                'success'  => true,
                'message'  => 'OPD Patient added successfully.',
                'redirect' => route('opd-patients.index')
            ]);

        } catch (\Exception $e) {
            \Log::error('OPD Save Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save: ' . $e->getMessage()
            ], 500);
        }
    }
}