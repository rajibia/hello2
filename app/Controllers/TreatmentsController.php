<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTreatmentRequest;
use App\Http\Requests\UpdateTreatmentRequest;
use App\Models\IpdPatientDepartment;
use App\Models\Treatment;
use App\Models\OpdPatientDepartment;
use App\Repositories\TreatmentRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class TreatmentsController extends AppBaseController
{
    /** @var TreatmentRepository */
    private $treatmentRepository;

    public function __construct(TreatmentRepository $treatmentRepo)
    {
        $this->treatmentRepository = $treatmentRepo;
    }

    public function index()
    {
        $patients = $this->treatmentRepository->getPatients();

        return view('treatments.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->treatmentRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('treatments.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateTreatmentRequest $request)
    {
        $input = $request->all();

        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';

        if ($opd_id != '') {
            $input['patient_id'] = OpdPatientDepartment::where('id', $opd_id)->pluck('patient_id')->first();
        }
        if ($ipd_id != '') {
            $input['patient_id'] = IpdPatientDepartment::where('id', $ipd_id)->pluck('patient_id')->first();
        }
        if (empty($input['patient_id'])) {
            Flash::error('Patient Is required');

            return redirect()->back();
        }
        // dd($input);
        Schema::disableForeignKeyConstraints();
        $this->treatmentRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->treatmentRepository->createNotification($input);

        Flash::success('Treatment '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('Treatment '.__('messages.common.saved_successfully'));

        
    }

    public function show(Treatment $treatment)
    {
        $treatment = $this->treatmentRepository->find($treatment->id);

        if (empty($treatment)) {
            Flash::error('Treatment '.__('messages.common.not_found'));

            // return redirect()->back();
        }
        $patients = $this->treatmentRepository->getPatients();

        return view('treatments.show')->with(['treatment' => $treatment, 'patients' => $patients]);
    }

    public function edit(Treatment $treatment)
    {
        return $this->sendResponse($treatment, 'Treatment retrieved successfully.');
    }

    public function update(Treatment $treatment, UpdateTreatmentRequest $request)
    {
        $input = $request->all();
        Schema::disableForeignKeyConstraints();
        $this->treatmentRepository->update($input, $treatment->id);
        Schema::enableForeignKeyConstraints();
        Flash::success('Treatment '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('Treatment '.__('messages.common.updated_successfully'));
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();

        return $this->sendSuccess('Treatment '.__('messages.common.deleted_successfully'));
    }
}
