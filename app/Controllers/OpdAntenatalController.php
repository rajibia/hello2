<?php

namespace App\Http\Controllers;

use App\Models\Antenatal; 
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Repositories\OpdAntenatalRepository;
use Illuminate\Http\Request;
use Exception;
use Flash;

class OpdAntenatalController extends Controller
{
    /** @var OpdAntenatalRepository */
    private $opdAntenatalRepository;

    public function __construct(OpdAntenatalRepository $opdAntenatalRepo)
    {
        $this->opdAntenatalRepository = $opdAntenatalRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display antenatal records
        $opd_antenatals = Antenatal::all();
        return view('opd_antenatal.index', compact('opd_antenatals'));
    }

    public function create(Request $request)
    {
        $opdId = $request->get('ref_opd_id');
        // Fetch the patient_id using the IpdPatientDepartment model
        $patientId = OpdPatientDepartment::where('id', $opdId)->value('patient_id');
        // Pass both ipopdIddId and patientId to the view
        return view('opd_antenatal.create', compact('opdId', 'patientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()) ;
        $request->validate([
            'patient_id' => 'required|exists:opd_patient_departments,patient_id',
            'date' => 'required|date',
            'bleeding' => 'nullable|string|max:255',
            'headache' => 'nullable|string|max:255',
            'pain' => 'nullable|string|max:255',
            'constipation' => 'nullable|string|max:255',
            'urinary_symptoms' => 'nullable|string|max:255',
            'vomiting' => 'nullable|string|max:255',
            'cough' => 'nullable|string|max:255',
            'vaginal_discharge' => 'nullable|string|max:255',
            'oedema' => 'nullable|string|max:255',
            'haemorrhoids' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'special_findings_and_remark' => 'nullable|string|max:1000',
            'pelvic_examination' => 'nullable|string|max:255',
            'sp' => 'nullable|string|max:255',
            'uter_size' => 'nullable|string|max:255',
            'uterus_size' => 'nullable|string|max:255',
            'presentation_position' => 'nullable|string|max:255',
            'presenting_part_to_brim' => 'nullable|string|max:255',
            'foetal_heart' => 'nullable|string|max:255',
            'blood_pressure' => 'nullable|string|max:50',
            'antenatal_oedema' => 'nullable|string|max:255',
            'urine_sugar' => 'nullable|string|max:255',
            'urine_albumin' => 'nullable|string|max:255',
            'antenatal_weight' => 'nullable|numeric|min:0',
            'remark' => 'nullable|string|max:1000',
            'next_visit' => 'nullable|date',
            'previous_antenatal_details' => 'nullable|string|max:5000',
        ]);

        $input = $request->all();
        $opd_id = $input['opd_id'] ?? '';

        try {
            $this->opdAntenatalRepository->store($input);
            Flash::success(__('messages.antenatal.title') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('opd.patient.show', $opd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(Antenatal $antenatal)
    // {
    //     return view('antenatal.show', compact('antenatal'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Antenatal $antenatal)
    // {
    //     return view('antenatal.edit', compact('antenatal'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Antenatal $antenatal)
    // {
    //     // Logic to update antenatal record
    //     $request->validate([
    //         'due_date' => 'required|date',
    //         // Add other validation rules as needed
    //     ]);

    //     $antenatal->update($request->all());

    //     return redirect()->route('antenatal.index')->with('success', 'Antenatal record updated successfully.');
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Antenatal $antenatal)
    // {
    //     dd($antenatal->ipd); // Check if the related IPD data is available

    //     // Retrieve the IPD ID associated with the antenatal record
    //     $ipd_id = $antenatal->ipd ? $antenatal->ipd->id : null; // Safe check for relationship
        
    //     // Check if $ipd_id is null
    //     if (!$ipd_id) {
    //         return redirect()->route('ipd.patient.index')->with('error', 'IPD not found for this antenatal record.');
    //     }
        
    //     // Delete the antenatal record
    //     $antenatal->delete();

    //     // Flash success message
    //     Flash::success(__('messages.antenatal.title') . ' ' . __('messages.common.deleted_successfully'));

    //     // Redirect to the IPD patient show route
    //     return redirect()->route('ipd.patient.show', $ipd_id);
    // }
}
