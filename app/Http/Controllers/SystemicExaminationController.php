<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSystemicExaminationRequest;
use App\Http\Requests\UpdateSystemicExaminationRequest;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\SystemicExamination;
use App\Repositories\SystemicExaminationRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class SystemicExaminationController extends AppBaseController
{
    /** @var SystemicExaminationRepository */
    private $systemicExaminationRepository;

    public function __construct(SystemicExaminationRepository $systemicExaminationRepo)
    {
        $this->systemicExaminationRepository = $systemicExaminationRepo;
    }

    public function index()
    {
        $patients = $this->systemicExaminationRepository->getPatients();

        return view('systemic_examinations.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->systemicExaminationRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('systemic_examinations.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateSystemicExaminationRequest $request)
    {
        $input = $request->all();
        // dd($input);

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

        $input['lungs_status'] = (int)$input['lungs_status'];
        $input['cardio_status'] = (int)$input['cardio_status'];
        $input['abdomen_status'] = (int)$input['abdomen_status'];
        $input['ear_status'] = (int)$input['ear_status'];
        $input['nose_status'] = (int)$input['nose_status'];
        $input['throat_status'] = (int)$input['throat_status'];
        $input['musco_status'] = (int)$input['musco_status'];
        $input['nervous_status'] = (int)$input['nervous_status'];
        $input['skin_status'] = (int)$input['skin_status'];
        $input['eye_status'] = (int)$input['eye_status'];
        Schema::disableForeignKeyConstraints();
        // // Set the attributes using the request data
        // $systemicExamination = new SystemicExamination();
        // $systemicExamination->examination_number = $input['examination_number'];
        // $systemicExamination->ipd_id = $ipd_id;
        // $systemicExamination->opd_id = $opd_id;
        // $systemicExamination->patient_id = $input['patient_id'];
        // $systemicExamination->lungs_status = (int)$input['lungs_status'];
        // $systemicExamination->lungs_description = $input['lungs_description'];
        // $systemicExamination->cardio_status = (int)$input['cardio_status'];
        // $systemicExamination->cardio_description = $input['cardio_description'];
        // $systemicExamination->abdomen_status = (int)$input['abdomen_status'];
        // $systemicExamination->abdomen_description = $input['abdomen_description'];
        // $systemicExamination->ear_status = (int)$input['ear_status'];
        // $systemicExamination->ear_description = $input['ear_description'];
        // $systemicExamination->nose_status = (int)$input['nose_status'];
        // $systemicExamination->nose_description = $input['nose_description'];
        // $systemicExamination->throat_status = (int)$input['throat_status'];
        // $systemicExamination->throat_description = $input['throat_description'];
        // $systemicExamination->musco_status = (int)$input['musco_status'];
        // $systemicExamination->musco_description = $input['musco_description'];
        // $systemicExamination->nervous_status = (int)$input['nervous_status'];
        // $systemicExamination->nervous_description = $input['nervous_description'];
        // $systemicExamination->skin_status = (int)$input['skin_status'];
        // $systemicExamination->skin_description = $input['skin_description'];
        // $systemicExamination->eye_status = (int)$input['eye_status'];
        // $systemicExamination->eye_description = $input['eye_description'];

        // // Save the new SystemicExamination
        // $systemicExamination->save();
        $this->systemicExaminationRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->systemicExaminationRepository->createNotification($input);

        Flash::success('Systemic Examination '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('Systemic Examination '.__('messages.common.saved_successfully'));

        
    }

    public function show(SystemicExamination $systemic_examination)
    {
        $systemic_examination = $this->systemicExaminationRepository->find($systemic_examination->id);

        if (empty($systemic_examination)) {
            Flash::error(__('messages.advanced_payment.advanced_payment').' '.__('messages.common.not_found'));

            return redirect()->back();
        }
        $patients = $this->systemicExaminationRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('systemic_examinations.show')->with(['systemic_examination' => $systemic_examination, 'patients' => $patients, 'ipds' => $ipds, 'opds' => $opds]);
    }

    public function edit(SystemicExamination $systemic_examination)
    {
        return $this->sendResponse($systemic_examination, 'OPD Diagnosis retrieved successfully.');
    }

    public function update(SystemicExamination $systemic_examination, UpdateSystemicExaminationRequest $request)
    {
        $input = $request->all();
        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';
        $input['lungs_status'] = (int)$input['lungs_status'];
        $input['cardio_status'] = (int)$input['cardio_status'];
        $input['abdomen_status'] = (int)$input['abdomen_status'];
        $input['ear_status'] = (int)$input['ear_status'];
        $input['nose_status'] = (int)$input['nose_status'];
        $input['throat_status'] = (int)$input['throat_status'];
        $input['musco_status'] = (int)$input['musco_status'];
        $input['nervous_status'] = (int)$input['nervous_status'];
        $input['skin_status'] = (int)$input['skin_status'];
        $input['eye_status'] = (int)$input['eye_status'];
        Schema::disableForeignKeyConstraints();
        $this->systemicExaminationRepository->update($input, $systemic_examination->id);
        //  // Set the attributes using the request data
        //  $systemicExamination = SystemicExamination::where('id', $systemic_examination->id)->first();
        // //  $systemicExamination->examination_number = $input['examination_number'];
        //  $systemicExamination->ipd_id = $ipd_id;
        //  $systemicExamination->opd_id = $opd_id;
        //  $systemicExamination->patient_id = $input['patient_id'];
        //  $systemicExamination->lungs_status = (int)$input['lungs_status'];
        //  $systemicExamination->lungs_description = $input['lungs_description'];
        //  $systemicExamination->cardio_status = (int)$input['cardio_status'];
        //  $systemicExamination->cardio_description = $input['cardio_description'];
        //  $systemicExamination->abdomen_status = (int)$input['abdomen_status'];
        //  $systemicExamination->abdomen_description = $input['abdomen_description'];
        //  $systemicExamination->ear_status = (int)$input['ear_status'];
        //  $systemicExamination->ear_description = $input['ear_description'];
        //  $systemicExamination->nose_status = (int)$input['nose_status'];
        //  $systemicExamination->nose_description = $input['nose_description'];
        //  $systemicExamination->throat_status = (int)$input['throat_status'];
        //  $systemicExamination->throat_description = $input['throat_description'];
        //  $systemicExamination->musco_status = (int)$input['musco_status'];
        //  $systemicExamination->musco_description = $input['musco_description'];
        //  $systemicExamination->nervous_status = (int)$input['nervous_status'];
        //  $systemicExamination->nervous_description = $input['nervous_description'];
        //  $systemicExamination->skin_status = (int)$input['skin_status'];
        //  $systemicExamination->skin_description = $input['skin_description'];
        //  $systemicExamination->eye_status = (int)$input['eye_status'];
        //  $systemicExamination->eye_description = $input['eye_description'];
 
        //  // Save the new SystemicExamination
        //  $systemicExamination->save();
        Schema::enableForeignKeyConstraints();
        Flash::success('Systemic Examination '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('Systemic Examination '.__('messages.common.updated_successfully'));
    }

    public function destroy(SystemicExamination $systemic_examination)
    {
        $systemic_examination->delete();

        return $this->sendSuccess('Systemic Examination '.__('messages.common.deleted_successfully'));
    }
}
