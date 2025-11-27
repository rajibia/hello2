<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOpdDiagnosisRequest;
use App\Http\Requests\UpdateOpdDiagnosisRequest;
use App\Models\OpdDiagnosis;
use App\Queries\OpdDiagnosisDataTable;
use App\Repositories\OpdDiagnosisRepository;
use App\Models\OpdProvisionalDiagnosis;
use App\Repositories\OpdProceduralDiagnosisRepository;
use DataTables;
use Illuminate\Http\Request;

class OpdDiagnosisController extends AppBaseController
{
    /** @var OpdDiagnosisRepository */
    private $opdDiagnosisRepository;
    private $opdProceduralDiagnosisRepository;

    public function __construct(OpdDiagnosisRepository $opdDiagnosisRepo,OpdProceduralDiagnosisRepository $opdProceduralDiagnosisRepo)
    {
        $this->opdDiagnosisRepository = $opdDiagnosisRepo;
        $this->opdProceduralDiagnosisRepository = $opdProceduralDiagnosisRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new OpdDiagnosisDataTable())->get($request->id))->make(true);
        }
    }

    // public function store(CreateOpdDiagnosisRequest $request)
    // {
    //     $input = $request->all();
    //     $this->opdDiagnosisRepository->store($input);
    //     $this->opdDiagnosisRepository->createNotification($input);

    //     return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.saved_successfully'));
    // }

    public function store(CreateOpdDiagnosisRequest $request)
    {
        // Get all input from the request
        $input = $request->all();

        // Validate and retrieve the opd_patient_department_id from the request
        // Assuming that the form includes a field for 'opd_patient_department_id'
        if ($request->has('opd_patient_department_id')) {
            $input['opd_patient_department_id'] = $request->input('opd_patient_department_id');
        } else {
            // Handle the case where the ID is missing
            return $this->sendError(__('messages.opd_diagnosis').' '.__('messages.common.missing_patient_department_id'), 400);
        }

        // Store the diagnosis using the repository
        $this->opdDiagnosisRepository->store($input);

        // Optionally create a notification
        $this->opdDiagnosisRepository->createNotification($input);

        // Return success response
        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function procedural_store(Request $request)
    {
        $input = $request->all();
        $this->opdProceduralDiagnosisRepository->store($input);
        
        return back();
        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function procedural_update(OpdProvisionalDiagnosis $opdDiagnosis, UpdateOpdDiagnosisRequest $request)
    {
        $this->opdProceduralDiagnosisRepository->updateOpdDiagnosis($request->all(), $opdDiagnosis->id);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.updated_successfully'));
    }
    public function edit(OpdDiagnosis $opdDiagnosis)
    {
        return $this->sendResponse($opdDiagnosis, 'OPD Diagnosis retrieved successfully.');
    }

    public function update(OpdDiagnosis $opdDiagnosis, UpdateOpdDiagnosisRequest $request)
    {
        $this->opdDiagnosisRepository->updateOpdDiagnosis($request->all(), $opdDiagnosis->id);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(OpdDiagnosis $opdDiagnosis)
    {
        $this->opdDiagnosisRepository->deleteOpdDiagnosis($opdDiagnosis->id);

        return $this->sendSuccess(__('messages.opd_diagnosis').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(OpdDiagnosis $opdDiagnosis)
    {
        $media = $opdDiagnosis->getMedia(OpdDiagnosis::OPD_DIAGNOSIS_PATH)->first();
        if ($media) {
            return $media;
        }

        return '';
    }
}
