<?php

namespace App\Http\Controllers;

use App\Models\IpdPatientDepartment;
use App\Models\PostnatalHistory;
use App\Repositories\IpdPostnatalRepository;
use Illuminate\Http\Request;
use Flash;

class IpdPostnatalController extends Controller
{
    /** @var IpdPostnatalRepository */
    private $ipdPostnatalRepository;

    public function __construct(IpdPostnatalRepository $ipdPostnatalRepo)
    {
        $this->ipdPostnatalRepository = $ipdPostnatalRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Logic to fetch and display postnatal records
        $ipd_postnatals = PostnatalHistory::all();
        return view('ipd_postnatal_history.index', compact('ipd_postnatals'));
    }

    public function create(Request $request)
    {
        $ipdId = $request->get('ref_ipd_id');
        // Fetch the patient_id using the IpdPatientDepartment model
        $patientId = IpdPatientDepartment::where('id', $ipdId)->value('patient_id');
        // Pass both ipd and patientId to the view
        return view('ipd_postnatal_history.create', compact('ipdId', 'patientId'));
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
        $ipd_id = $input['ipd_id'] ?? '';

        try {
            $this->ipdPostnatalRepository->store($input);
            Flash::success(__('messages.postnatal.title') . ' ' . __('messages.common.saved_successfully'));

            return redirect()->route('ipd.patient.show', $ipd_id);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        // Fetch the postnatal record by ID using where
        $postnatal = PostnatalHistory::where('id', $id)->first();

        // Check if the record is found
        if (!$postnatal) {
            return response()->json([
                'success' => false,
                'message' => 'Postnatal record not found'
            ], 404);
        }

        // Return the postnatal data to be used in the modal
        return response()->json([
            'success' => true,
            'data' => $postnatal
        ]);
    }

}
