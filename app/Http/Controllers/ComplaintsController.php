<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateComplaintsRequest;
use App\Http\Requests\UpdateComplaintsRequest;
use App\Http\Requests\UpdateVitalsRequest;
use App\Models\Complaint;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\Vital;
use App\Repositories\ComplaintRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class ComplaintsController extends AppBaseController
{
    /** @var ComplaintRepository */
    private $complaintRepository;

    public function __construct(ComplaintRepository $complaintsRepo)
    {
        $this->complaintRepository = $complaintsRepo;
    }

    public function index()
    {
        $patients = $this->complaintRepository->getPatients();

        return view('complaints.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->complaintRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('complaints.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateComplaintsRequest $request)
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

            // return redirect()->back();
        }
        // dd($input);
        Schema::disableForeignKeyConstraints();
        $this->complaintRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->complaintRepository->createNotification($input);

        Flash::success('Complaint '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('Complaint '.__('messages.common.saved_successfully'));
    }

    public function show(Complaint $complaint)
    {
        $complaint = $this->complaintRepository->find($complaint->id);

        if (empty($complaint)) {
            Flash::error(__('messages.advanced_payment.advanced_payment').' '.__('messages.common.not_found'));

            return redirect()->back();
        }
        $patients = $this->complaintRepository->getPatients();

        return view('complaints.show')->with(['complaint' => $complaint, 'patients' => $patients]);
    }

    public function edit(Complaint $complaint)
    {
        return $this->sendResponse($complaint, 'Complaint retrieved successfully.');
    }

    public function update(Complaint $complaint, UpdateComplaintsRequest $request)
    {
        $input = $request->all();
        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';
        Schema::disableForeignKeyConstraints();
        $this->complaintRepository->update($input, $complaint->id);
        Schema::enableForeignKeyConstraints();
        Flash::success('Complaint '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('Complaint '.__('messages.common.updated_successfully'));
    }

    public function destroy(Complaint $complaint)
    {
        $complaint->delete();

        return $this->sendSuccess('Complaint '.__('messages.common.deleted_successfully'));
    }
}
