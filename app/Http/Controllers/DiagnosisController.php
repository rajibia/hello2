<?php

namespace App\Http\Controllers;

use App\Exports\DiagnosisExport;
use App\Http\Requests\CreateDiagnosisRequest;
use App\Http\Requests\UpdateDiagnosisRequest;
use App\Models\Diagnosis;
use App\Models\Insurance;
use App\Repositories\DiagnosisRepository;
use DB;
use Exception;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class DiagnosisController extends AppBaseController
{
    /** @var DiagnosisRepository */
    private $diagnosisRepository;

    public function __construct(DiagnosisRepository $diagnosisRepo)
    {
        $this->diagnosisRepository = $diagnosisRepo;
    }

    public function index()
    {
        $data['statusArr'] = Diagnosis::STATUS_ARR;

        return view('diagnosis.index', $data);
    }

    public function create()
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        $insurances = $insurances->prepend('All Insurance', 'all');
        return view('diagnosis.create',compact('insurances'));
    }

    public function store(CreateDiagnosisRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['tariff'] = removeCommaFromNumbers($input['tariff']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);

        if ($input['insurance_id'] == 'all') {
            $insurances = Insurance::where('status', 1)->get(['name', 'id'])->sort();
            foreach ($insurances as $insurance) {
                $input['insurance_id'] = $insurance->id;
                $input['insurance_name'] = $insurance->name;
                $this->diagnosisRepository->create($input);
                $this->diagnosisRepository->createNotification();
            }
        }else{            
            $input['insurance_id'] = $input['insurance_id'];
            $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');
            $exists =  Diagnosis::where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
            if ($exists==0) {            
                $this->diagnosisRepository->create($input);
                $this->diagnosisRepository->createNotification();
            }else{
                Flash::error(__('messages.packages.diagnosis').' Diagnosis name has already been registered');
            }            
        }
        if ($exists ?? 1 == 0) {   
            Flash::success(__('messages.package.diagnosis').' '.__('messages.common.saved_successfully'));
        }

        return redirect(route('diagnosis.index'));
    }

    public function show(Diagnosis $diagnosis)
    {
        $diagnosis = $this->diagnosisRepository->find($diagnosis->id);
        
        if (empty($diagnosis)) {
            Flash::error(__('messages.diagnosis.diagnosis').' '.__('messages.common.not_found'));

            return redirect(route('diagnosis.index'));
        }

        return view('diagnosis.show')->with('diagnosis', $diagnosis);
    }

    public function edit(Diagnosis $diagnosis)
    {
        $isEdit = true;
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        return view('diagnosis.edit', compact('insurances', 'diagnosis','isEdit'));
    }

    public function update(Diagnosis $diagnosis, UpdateDiagnosisRequest $request): RedirectResponse
    {
        if (empty($diagnosis)) {
            Flash::error(__('messages.diagnosis.diagnosis').' '.__('messages.common.not_found'));

            return redirect(route('diagnosis.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['tariff'] = removeCommaFromNumbers($input['tariff']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);
        $input['insurance_id'] = $input['insurance_id'];
        $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');

        $exists =  Diagnosis::where('id', '!=',$diagnosis->id)->where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
        if ($exists==0) {            
            $this->diagnosisRepository->update($input, $diagnosis->id);
            Flash::success(__('messages.package.diagnosis').' '.__('messages.common.updated_successfully'));
        }else{
            Flash::error(__('messages.diagnosis.diagnosis').' Diagnosis name has already been registered');
        }
        

        return redirect(route('diagnosis.index'));
    }

    public function destroy(Diagnosis $diagnosis)
    {
        $diagnosisModel = [
            // PackageDiagnosis::class,
        ];

        $result = canDelete($diagnosisModel, 'diagnosis_id', $diagnosis->id);

        if ($result) {
            return $this->sendError(__('messages.package.diagnosis').' '.__('messages.common.cant_be_deleted'));
        }

        $diagnosis->delete();

        return $this->sendSuccess(__('messages.package.diagnosis').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveDiagnosis($id)
    {
        $diagnosis = Diagnosis::find($id);
        $diagnosis->status = ! $diagnosis->status;
        $diagnosis->update(['status' => $diagnosis->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function diagnosisExport()
    {
        return Excel::download(new DiagnosisExport, 'diagnosis-'.time().'.xlsx');
    }
}
