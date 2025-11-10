<?php

namespace App\Http\Controllers;

use App\Models\Antenatal; 
use App\Models\IpdPatientDepartment;
use App\Repositories\IpdAntenatalRepository;
use Illuminate\Http\Request;
use Exception;
use Flash;

class IpdAntenatalController extends Controller
{
    /** @var IpdAntenatalRepository */
    private $ipdAntenatalRepository;

    public function __construct(IpdAntenatalRepository $ipdAntenatalRepo)
    {
        $this->ipdAntenatalRepository = $ipdAntenatalRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display antenatal records
        $antenatals = Antenatal::all();
        return view('antenatal.index', compact('antenatals'));
    }

    public function create(Request $request)
    {
        $ipdId = $request->get('ref_ipd_id');
        // Fetch the patient_id using the IpdPatientDepartment model
        $patientId = IpdPatientDepartment::where('id', $ipdId)->value('patient_id');
        // Pass both ipdId and patientId to the view
        return view('antenatal.create', compact('ipdId', 'patientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all()) ;
        $request->validate([
            'patient_id' => 'required|exists:ipd_patient_departments,patient_id',
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
        $ipd_id = $input['ipd_id'] ?? '';

        try {
            $this->ipdAntenatalRepository->store($input);
            // return redirect()->route('ipd.patient.show', $ipd_id)->with('success', __('messages.antenatal.record_saved'));
            // Using Flash to display success message
            Flash::success(__('messages.antenatal.title') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('ipd.patient.show', $ipd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */

     public function show($id)
    {
        $antenatal = Antenatal::find($id);

        if (!$antenatal) {
            abort(404, 'Antenatal record not found.');
        }

        return view('antenatal.show', compact('antenatal'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Antenatal $antenatal)
    {
        return view('antenatal.edit', compact('antenatal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Antenatal $antenatal)
    {
        // Logic to update antenatal record
        $request->validate([
            'due_date' => 'required|date',
            // Add other validation rules as needed
        ]);

        $antenatal->update($request->all());

        return redirect()->route('antenatal.index')->with('success', 'Antenatal record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Antenatal $antenatal)
    {
        dd($antenatal->ipd); // Check if the related IPD data is available

        // Retrieve the IPD ID associated with the antenatal record
        $ipd_id = $antenatal->ipd ? $antenatal->ipd->id : null; // Safe check for relationship
        
        // Check if $ipd_id is null
        if (!$ipd_id) {
            return redirect()->route('ipd.patient.index')->with('error', 'IPD not found for this antenatal record.');
        }
        
        // Delete the antenatal record
        $antenatal->delete();

        // Flash success message
        Flash::success(__('messages.antenatal.title') . ' ' . __('messages.common.deleted_successfully'));

        // Redirect to the IPD patient show route
        return redirect()->route('ipd.patient.show', $ipd_id);
    }
}
