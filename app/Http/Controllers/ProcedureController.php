<?php

namespace App\Http\Controllers;

use App\Exports\ProcedureExport;
use App\Http\Requests\CreateProcedureRequest;
use App\Http\Requests\UpdateProcedureRequest;
use App\Models\Procedure;
use App\Models\Insurance;
use App\Repositories\ProcedureRepository;
use DB;
use Exception;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class ProcedureController extends AppBaseController
{
    /** @var ProcedureRepository */
    private $procedureRepository;

    public function __construct(ProcedureRepository $procedureRepo)
    {
        $this->procedureRepository = $procedureRepo;
    }

    public function index()
    {
        $data['statusArr'] = Procedure::STATUS_ARR;

        return view('procedures.index', $data);
    }

    public function create()
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        $insurances = $insurances->prepend('All Insurance', 'all');
        return view('procedures.create',compact('insurances'));
    }

    public function store(CreateProcedureRequest $request)
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
                $this->procedureRepository->create($input);
                $this->procedureRepository->createNotification();
            }
        }else{            
            $input['insurance_id'] = $input['insurance_id'];
            $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');
            $exists =  Procedure::where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
            if ($exists==0) {            
                $this->procedureRepository->create($input);
                $this->procedureRepository->createNotification();
            }else{
                Flash::error(__('messages.packages.procedure').' Procedure name has already been registered');
            }            
        }
        if ($exists ?? 1 == 0) {   
            Flash::success(__('messages.package.procedure').' '.__('messages.common.saved_successfully'));
        }

        return redirect(route('procedures.index'));
    }

    public function show(Procedure $procedure)
    {
        $procedure = $this->procedureRepository->find($procedure->id);
        
        if (empty($procedure)) {
            Flash::error(__('messages.procedure.procedure').' '.__('messages.common.not_found'));

            return redirect(route('procedures.index'));
        }

        return view('procedures.show')->with('procedure', $procedure);
    }

    public function edit(Procedure $procedure)
    {
        $isEdit = true;
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        return view('procedures.edit', compact('insurances', 'procedure','isEdit'));
    }

    public function update(Procedure $procedure, UpdateProcedureRequest $request): RedirectResponse
    {
        if (empty($procedure)) {
            Flash::error(__('messages.procedure.procedure').' '.__('messages.common.not_found'));

            return redirect(route('procedures.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['tariff'] = removeCommaFromNumbers($input['tariff']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);
        $input['insurance_id'] = $input['insurance_id'];
        $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');

        $exists =  Procedure::where('id', '!=',$procedure->id)->where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
        if ($exists==0) {            
            $this->procedureRepository->update($input, $procedure->id);
            Flash::success(__('messages.package.procedure').' '.__('messages.common.updated_successfully'));
        }else{
            Flash::error(__('messages.procedure.procedure').' Procedure name has already been registered');
        }
        

        return redirect(route('procedures.index'));
    }

    public function destroy(Procedure $procedure)
    {
        $procedureModel = [
            // PackageProcedure::class,
        ];

        $result = canDelete($procedureModel, 'procedure_id', $procedure->id);

        if ($result) {
            return $this->sendError(__('messages.package.procedure').' '.__('messages.common.cant_be_deleted'));
        }

        $procedure->delete();

        return $this->sendSuccess(__('messages.package.procedure').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveProcedure($id)
    {
        $procedure = Procedure::find($id);
        $procedure->status = ! $procedure->status;
        $procedure->update(['status' => $procedure->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function procedureExport()
    {
        return Excel::download(new ProcedureExport, 'procedures-'.time().'.xlsx');
    }
}
