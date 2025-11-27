<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use Illuminate\Http\Request;

class IpdPatientController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id'     => 'required|exists:patients,id',
                'doctor_id'      => 'required|exists:doctors,id',
                'admission_date' => 'required|date',
                'bed_id'         => 'required|exists:beds,id',
                'ipd_number'     => 'required|unique:ipd_patient_departments,ipd_number',
                'height'         => 'nullable|string',
                'weight'         => 'nullable|string',
                'bp'             => 'nullable|string',
                'pulse'          => 'nullable|string',
                'temperature'    => 'nullable|string',
                'respiration'    => 'nullable|string',
                'oxygen_saturation' => 'nullable|string',
                'symptoms'       => 'nullable|string',
            ]);

            $ipd = IpdPatientDepartment::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'IPD Patient added successfully.',
                'redirect' => route('ipd-patients.index')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('IPD Save Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}