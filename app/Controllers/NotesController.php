<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateNotesRequest;
use App\Http\Requests\UpdateNotesRequest;
use App\Models\IpdPatientDepartment;
use App\Models\Notes;
use App\Models\OpdPatientDepartment;
use App\Repositories\NotesRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class NotesController extends AppBaseController
{
    /** @var NotesRepository */
    private $notesRepository;

    public function __construct(NotesRepository $notesRepo)
    {
        $this->notesRepository = $notesRepo;
    }

    public function index()
    {
        $patients = $this->notesRepository->getPatients();

        return view('notes.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';

        $patients = $this->notesRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('notes.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateNotesRequest $request)
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
        $this->notesRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->notesRepository->createNotification($input);

        Flash::success('Notes '.__('messages.common.saved_successfully'));

        return $this->sendSuccess('Notes '.__('messages.common.saved_successfully'));

        
    }

    public function show(Notes $note)
    {
        $note = $this->notesRepository->find($note->id);

        if (empty($note)) {
            Flash::error('Note '.__('messages.common.not_found'));

            // return redirect()->back();
        }
        $patients = $this->notesRepository->getPatients();

        return view('notes.show')->with(['note' => $note, 'patients' => $patients]);
    }

    public function edit(Notes $note)
    {
        return $this->sendResponse($note, 'Notes retrieved successfully.');
    }

    public function update(Notes $note, UpdateNotesRequest $request)
    {
        $input = $request->all();
        Schema::disableForeignKeyConstraints();
        $this->notesRepository->update($input, $note->id);
        Schema::enableForeignKeyConstraints();
        Flash::success('Notes '.__('messages.common.updated_successfully'));

        return $this->sendSuccess('Notes '.__('messages.common.updated_successfully'));
    }

    public function destroy(Notes $note)
    {
        $note->delete();

        return $this->sendSuccess('Notes '.__('messages.common.deleted_successfully'));
    }
}
