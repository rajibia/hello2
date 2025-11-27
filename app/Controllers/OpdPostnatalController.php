<?php

namespace App\Http\Controllers;

use App\Models\OpdPatientDepartment;
use App\Models\PostnatalHistory;
use App\Repositories\OpdPostnatalRepository;
use Illuminate\Http\Request;
use Flash;

class OpdPostnatalController extends Controller
{
    /** @var OpdPostnatalRepository */
    private $opdPostnatalRepository;

    public function __construct(OpdPostnatalRepository $opdPostnatalRepo)
    {
        $this->opdPostnatalRepository = $opdPostnatalRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display postnatal records
        $opd_postnatals = PostnatalHistory::all();
        return view('opd_postnatal_history.index', compact('opd_postnatals'));
    }

    public function create(Request $request)
    {
        $opdId = $request->get('ref_opd_id');
        // Fetch the patient_id using the OpdPatientDepartment model
        $patientId = OpdPatientDepartment::where('id', $opdId)->value('patient_id');
        // Pass both ipopdIddId and patientId to the view
        return view('opd_postnatal_history.create', compact('opdId', 'patientId'));
    }

    public function store(Request $request)
    {
        // dd($request->all()) ;
        $request->validate([
            'patient_id' => 'required|exists:opd_patient_departments,patient_id',
            'labour_time' => 'required|string',
            'delivery_time' => 'nullable|string|max:255',
            'routine_question' => 'nullable|string|max:255',
            'general_remark	' => 'nullable|string|max:255',
        ]);

        $input = $request->all();
        $opd_id = $input['opd_id'] ?? '';

        try {
            $this->opdPostnatalRepository->store($input);
            Flash::success(__('messages.postnatal.title') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('opd.patient.show', $opd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
