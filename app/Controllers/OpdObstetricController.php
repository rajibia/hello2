<?php

namespace App\Http\Controllers;

use App\Models\OpdPatientDepartment;
use App\Models\PreviousObstetricHistory;
use App\Repositories\OpdObstetricRepository;
use Illuminate\Http\Request;
use Flash;

class OpdObstetricController extends Controller
{
   /** @var OpdObstetricRepository */
    private $opdObstetricRepository;

    public function __construct(OpdObstetricRepository $opdObstetricRepo)
    {
        $this->opdObstetricRepository = $opdObstetricRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display obstetric records
        $ipd_previous_obstetrics = PreviousObstetricHistory::all();
        return view('ipd_obstetric_history.index', compact('ipd_previous_obstetrics'));
    }

    public function create(Request $request)
    {
        $opdId = $request->get('ref_opd_id');
        // Fetch the patient_id using the OpdPatientDepartment model
        $patientId = OpdPatientDepartment::where('id', $opdId)->value('patient_id');
        // Pass both ipd and patientId to the view
        return view('opd_obstetric_history.create', compact('opdId', 'patientId'));
    }

    public function store(Request $request)
    {
        // dd($request->all()) ;
        $request->validate([
            'patient_id' => 'required|exists:opd_patient_departments,patient_id',
            'place_of_delivery' => 'nullable|string|max:255', 
            'duration_of_pregnancy' => 'nullable|numeric|min:1|max:42', 
            'complication_in_pregnancy_or_puerperium' => 'nullable|string|max:500', 
            'birth_weight' => 'nullable|numeric|min:0', 
            'gender' => 'nullable|in:0,1', 
            'infant_feeding' => 'nullable|string|max:255', 
            'birth_status' => 'required|in:0,1',  
            'alive_or_dead_date' => 'nullable|date', 
            'previous_medical_history' => 'nullable|string|max:1000', 
            'special_instruction' => 'nullable|string|max:500', 
        ]);

        $input = $request->all();
        $opd_id = $input['opd_id'] ?? '';

        try {
            $this->opdObstetricRepository->store($input);
            Flash::success(__('messages.previous_obstetric_history.obstetric') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('opd.patient.show', $opd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
