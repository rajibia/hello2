<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNursingProgressNoteRequest;
use App\Http\Requests\UpdateNursingProgressNotesRequest;
use App\Models\IpdPatientDepartment;
use App\Models\NursingProgressNote;
use App\Models\OpdPatientDepartment;
use App\Repositories\NursingProgressNoteRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class NursingProgressNotesController extends AppBaseController
{
    /** @var NursingProgressNoteRepository */
    private $nursingProgressNotesRepository;

    public function __construct(NursingProgressNoteRepository $nursingProgressNotesRepo)
    {
        $this->nursingProgressNotesRepository = $nursingProgressNotesRepo;
    }

    public function index()
    {
        $patients = $this->nursingProgressNotesRepository->getPatients();

        return view('nursing_progress_notes.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->nursingProgressNotesRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('nursing_progress_notes.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateNursingProgressNoteRequest $request)
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
        $this->nursingProgressNotesRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->nursingProgressNotesRepository->createNotification($input);

        Flash::success('Nursing Progress Notes '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('Nursing Progress Notes '.__('messages.common.saved_successfully'));

        
    }

    public function show(NursingProgressNote $nursing_progress_note)
    {
        $nursing_progress_note = $this->nursingProgressNotesRepository->find($nursing_progress_note->id);

        if (empty($nursing_progress_note)) {
            Flash::error('Nursing Progress Note '.__('messages.common.not_found'));

            // return redirect()->back();
        }
        $patients = $this->nursingProgressNotesRepository->getPatients();

        return view('nursing_progress_notes.show')->with(['nursing_progress_note' => $nursing_progress_note, 'patients' => $patients]);
    }

    public function edit(NursingProgressNote $nursing_progress_note)
    {
        return $this->sendResponse($nursing_progress_note, 'Nursing Progress Notes retrieved successfully.');
    }

    public function update(NursingProgressNote $nursing_progress_note, UpdateNursingProgressNotesRequest $request)
    {
        $input = $request->all();
        Schema::disableForeignKeyConstraints();
        $this->nursingProgressNotesRepository->update($input, $nursing_progress_note->id);
        Schema::enableForeignKeyConstraints();
        Flash::success('Nursing Progress Notes '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('Nursing Progress Notes '.__('messages.common.updated_successfully'));
    }

    public function destroy(NursingProgressNote $nursing_progress_note)
    {
        $nursing_progress_note->delete();

        return $this->sendSuccess('Nursing Progress Notes '.__('messages.common.deleted_successfully'));
    }
}
