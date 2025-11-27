<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use App\Models\OpdPreviousObstetricHistory;
use App\Repositories\IpdObstetricRepository;
use Illuminate\Http\Request;
use Flash;

class IpdObstetricController extends Controller
{
   /** @var IpdObstetricRepository */
    private $ipdObstetricRepository;

    public function __construct(IpdObstetricRepository $ipdObstetricRepo)
    {
        $this->ipdObstetricRepository = $ipdObstetricRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display obstetric records
        $ipd_previous_obstetrics = OpdPreviousObstetricHistory::all();
        return view('ipd_obstetric_history.index', compact('ipd_previous_obstetrics'));
    }

    public function create(Request $request)
    {
        $ipdId = $request->get('ref_ipd_id');
        // Fetch the patient_id using the IpdPatientDepartment model
        $patientId = IpdPatientDepartment::where('id', $ipdId)->value('patient_id');
        // Pass both ipd and patientId to the view
        return view('ipd_obstetric_history.create', compact('ipdId', 'patientId'));
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
        $ipd_id = $input['ipd_id'] ?? '';

        try {
            $this->ipdObstetricRepository->store($input);
            Flash::success(__('messages.previous_obstetric_history.obstetric') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('ipd.patient.show', $ipd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
