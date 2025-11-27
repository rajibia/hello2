<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGeneralExaminationRequest;
use App\Http\Requests\UpdateGeneralExaminationRequest;
use App\Models\GeneralExamination;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Repositories\GeneralExaminationRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class GeneralExaminationController extends AppBaseController
{
    /** @var GeneralExaminationRepository */
    private $generalExaminationRepository;

    public function __construct(GeneralExaminationRepository $generalExaminationRepo)
    {
        $this->generalExaminationRepository = $generalExaminationRepo;
    }

    public function index()
    {
        $patients = $this->generalExaminationRepository->getPatients();

        return view('general_examinations.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->generalExaminationRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        

        return view('general_examinations.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateGeneralExaminationRequest $request)
    {
        // dd($request);
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
        $this->generalExaminationRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->generalExaminationRepository->createNotification($input);

        Flash::success('General Examination '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('General Examination '.__('messages.common.saved_successfully'));

        
    }

    public function show(GeneralExamination $general_examination)
    {
        $general_examination = $this->generalExaminationRepository->find($general_examination->id);

        if (empty($general_examination)) {
            Flash::error('General Examination '.__('messages.common.not_found'));

            return redirect()->back();
        }
        $patients = $this->generalExaminationRepository->getPatients();

        // return $this->sendResponse($general_examination, 'General Examination retrieved successfully.');
        return view('general_examinations.show')->with(['general_examination' => $general_examination, 'patients' => $patients]);
    }

    public function edit(GeneralExamination $general_examination)
    {
        // dd($general_examination);
         return $this->sendResponse($general_examination, 'OPD Diagnosis retrieved successfully.');
       
    }

    public function update(GeneralExamination $general_examination, UpdateGeneralExaminationRequest $request)
    {
        
        $input = $request->all();
        // dd($input);
        Schema::disableForeignKeyConstraints();
        $this->generalExaminationRepository->update($input, $general_examination->id);
        Schema::enableForeignKeyConstraints();
        Flash::success('General Examination  '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('General Examination '.__('messages.common.updated_successfully'));
    }
    
    public function destroy(GeneralExamination $general_examination)
    {
        // dd($general_examination);
        $general_examination->delete();

        return $this->sendSuccess('General Examination  '.__('messages.common.deleted_successfully'));
    }
}
