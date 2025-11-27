<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVitalsRequest;
use App\Http\Requests\UpdateVitalsRequest;
use App\Models\IpdPatientDepartment;
use App\Models\OpdPatientDepartment;
use App\Models\Vital;
use App\Repositories\VitalsRepository;
use Flash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class VitalsController extends AppBaseController
{
    /** @var VitalsRepository */
    private $vitalsRepository;

    public function __construct(VitalsRepository $vitalsRepo)
    {
        $this->vitalsRepository = $vitalsRepo;
    }

    public function index()
    {
        $patients = $this->vitalsRepository->getPatients();

        return view('vitals.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $data['patient_id'] = $request->query()['ref_p_id'] ?? '';
        $data['opd_id'] = $request->query()['ref_opd_id'] ?? '';
        $data['ipd_id'] = $request->query()['ref_ipd_id'] ?? '';
        $data['maternity_id'] = $request->query()['ref_maternity_id'] ?? '';

        $patients = $this->vitalsRepository->getPatients();
        $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
        $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');

        return view('vitals.create',
            compact('patients', 'opds', 'ipds'))->with($data);
    }

    public function store(CreateVitalsRequest $request)
    {
        $input = $request->all();

        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';
        $maternity_id = $input['maternity_id'] ?? '';

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
        $this->vitalsRepository->create($input);
        Schema::enableForeignKeyConstraints();
        $this->vitalsRepository->createNotification($input);

        Flash::success(__('messages.vitals').' '.__('messages.common.saved_successfully'));

        if ($input['create_from_route'] == 'patient') {
            return redirect(route('patients.show', $patient_id));
        } elseif ($input['create_from_route'] == 'opd') {
            return redirect(route('opd.patient.show', $opd_id));
        } else if ($input['create_from_route'] == 'ipd') {
            return redirect(route('ipd.patient.show',  $ipd_id));
        } else if ($input['create_from_route'] == 'maternity') {
            return redirect(route('maternity.patient.show', $maternity_id));
        }


    }

    public function show(Vitals $vitals)
    {
        $vitals = $this->vitalsRepository->find($vitals->id);

        if (empty($vital)) {
            Flash::error(__('messages.advanced_payment.advanced_payment').' '.__('messages.common.not_found'));

            return redirect(route('vitals.index'));
        }
        $patients = $this->vitalsRepository->getPatients();

        return view('advanced_payments.show')->with(['vitals' => $vitals, 'patients' => $patients]);
    }

    public function edit(Vital $vitals)
    {
        // if (getLoggedinDoctor() && checkRecordAccess($vital->doctor_id)) {
        //     return view('errors.404');
        // } else {
            $patients = $this->vitalsRepository->getPatients();
            $patient_id = $vitals->patient_id;
            $opd_id = $vitals->opd_id;
            $ipd_id = $vitals->ipd_id;
            $maternity_id = $vitals->maternity_id;

            $opds = OpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('opd_number', 'id');
            $ipds = IpdPatientDepartment::whereHas('patient')->whereHas('doctor')->get()->pluck('ipd_number', 'id');
        return view('vitals.edit',
                compact('vitals', 'patients', 'patient_id', 'opd_id', 'ipd_id', 'maternity_id', 'opds', 'ipds'));
    }

    public function update(Vital $vitals, UpdateVitalsRequest $request)
    {
        $input = $request->all();
        $patient_id = $input['patient_id'] ?? '';
        $opd_id = $input['opd_id'] ?? '';
        $ipd_id = $input['ipd_id'] ?? '';
        $maternity_id = $input['maternity_id'] ?? '';
        Schema::disableForeignKeyConstraints();
        $this->vitalsRepository->update($input, $vitals->id);
        Schema::enableForeignKeyConstraints();
        Flash::success(__('messages.vitals').' '.__('messages.common.updated_successfully'));

        if ($input['create_from_route'] == 'patient') {
            return redirect(route('patients.show', $patient_id));
        } elseif ($input['create_from_route'] == 'opd') {
            return redirect(route('opd.patient.show', $opd_id));
        } else if ($input['create_from_route'] == 'ipd') {
            return redirect(route('ipd.patient.show',  $ipd_id));
        } else if ($input['create_from_route'] == 'maternity') {
            return redirect(route('maternity.patient.show', $maternity_id));
        }
    }

    public function destroy(Vital $vitals)
    {
        $vitals->delete();

        return $this->sendSuccess(__('messages.vitals').' '.__('messages.common.deleted_successfully'));
    }
}
