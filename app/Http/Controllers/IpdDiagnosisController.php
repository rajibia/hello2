<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateIpdDiagnosisRequest;
use App\Http\Requests\UpdateIpdDiagnosisRequest;
use App\Models\IpdDiagnosis;
use App\Models\IpdProvisionalDiagnosis;
use App\Queries\IpdDiagnosisDataTable;
use App\Repositories\IpdDiagnosisRepository;
use App\Repositories\IpdProceduralDiagnosisRepository;
use DataTables;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class IpdDiagnosisController extends AppBaseController
{
    /** @var IpdDiagnosisRepositoryIpdProvisionalDiagnosis */
    private $ipdDiagnosisRepository;
    private $ipdProceduralDiagnosisRepository;

    public function __construct(IpdDiagnosisRepository $ipdDiagnosisRepo,IpdProceduralDiagnosisRepository $ipdProceduralDiagnosisRepo)
    {
        $this->ipdDiagnosisRepository = $ipdDiagnosisRepo;
        $this->ipdProceduralDiagnosisRepository = $ipdProceduralDiagnosisRepo;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of((new IpdDiagnosisDataTable())->get($request->id))->make(true);
        }
    }
    
    public function procedural_store(Request $request)
    {
        $input = $request->all();
        $this->ipdProceduralDiagnosisRepository->store($input);
        // $ipdDiagnosis = IpdProvisionalDiagnosis::create($input);
        
        return back();
        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function procedural_update(IpdProvisionalDiagnosis $ipdDiagnosis, UpdateIpdDiagnosisRequest $request)
    {
        $this->ipdProceduralDiagnosisRepository->updateIpdDiagnosis($request->all(), $ipdDiagnosis->id);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.updated_successfully'));
    }
    
    public function store(CreateIpdDiagnosisRequest $request)
    {
        $input = $request->all();
        $this->ipdDiagnosisRepository->store($input);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.saved_successfully'));
    }

    public function edit(IpdDiagnosis $ipdDiagnosis)
    {
        return $this->sendResponse($ipdDiagnosis, 'IPD Diagnosis retrieved successfully.');
    }

    public function update(IpdDiagnosis $ipdDiagnosis, UpdateIpdDiagnosisRequest $request)
    {
        $this->ipdDiagnosisRepository->updateIpdDiagnosis($request->all(), $ipdDiagnosis->id);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(IpdDiagnosis $ipdDiagnosis)
    {
        $this->ipdDiagnosisRepository->deleteIpdDiagnosis($ipdDiagnosis->id);

        return $this->sendSuccess(__('messages.ipd_diagnosis').' '.__('messages.common.deleted_successfully'));
    }

    public function downloadMedia(IpdDiagnosis $ipdDiagnosis)
    {
        $media = $ipdDiagnosis->getMedia(IpdDiagnosis::IPD_DIAGNOSIS_PATH)->first();

        if ($media != null) {
            $media = $media->id;
            $mediaItem = Media::find($media);

            return $mediaItem;
        }

        return '';
    }
}
