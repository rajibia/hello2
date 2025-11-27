<?php

namespace App\Http\Controllers;

use App\Exports\LabExport;
use App\Http\Requests\CreateLabRequest;
use App\Http\Requests\UpdateLabRequest;
use App\Models\Lab;
use App\Models\Insurance;
use App\Repositories\LabRepository;
use DB;
use Exception;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class LabController extends AppBaseController
{
    /** @var LabRepository */
    private $labRepository;

    public function __construct(LabRepository $labRepo)
    {
        $this->labRepository = $labRepo;
    }

    public function index()
    {
        $data['statusArr'] = Lab::STATUS_ARR;

        return view('labs.index', $data);
    }

    public function create()
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        $insurances = $insurances->prepend('All Insurance', 'all');
        return view('labs.create',compact('insurances'));
    }

    public function store(CreateLabRequest $request)
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
                $this->labRepository->create($input);
                $this->labRepository->createNotification();
            }
        }else{            
            $input['insurance_id'] = $input['insurance_id'];
            $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');
            $exists =  Lab::where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
            if ($exists==0) {            
                $this->labRepository->create($input);
                $this->labRepository->createNotification();
            }else{
                Flash::error(__('messages.packages.lab').' Lab name has already been registered');
            }            
        }
        if ($exists ?? 1 == 0) {   
            Flash::success(__('messages.package.lab').' '.__('messages.common.saved_successfully'));
        }

        return redirect(route('labs.index'));
    }

    public function show(Lab $lab)
    {
        $lab = $this->labRepository->find($lab->id);
        
        if (empty($lab)) {
            Flash::error(__('messages.lab.lab').' '.__('messages.common.not_found'));

            return redirect(route('labs.index'));
        }

        return view('labs.show')->with('lab', $lab);
    }

    public function edit(Lab $lab)
    {
        $isEdit = true;
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        return view('labs.edit', compact('insurances', 'lab','isEdit'));
    }

    public function update(Lab $lab, UpdateLabRequest $request): RedirectResponse
    {
        if (empty($lab)) {
            Flash::error(__('messages.lab.lab').' '.__('messages.common.not_found'));

            return redirect(route('labs.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['tariff'] = removeCommaFromNumbers($input['tariff']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);
        $input['insurance_id'] = $input['insurance_id'];
        $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');

        $exists =  Lab::where('id', '!=',$lab->id)->where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
        if ($exists==0) {            
            $this->labRepository->update($input, $lab->id);
            Flash::success(__('messages.package.lab').' '.__('messages.common.updated_successfully'));
        }else{
            Flash::error(__('messages.lab.lab').' Lab name has already been registered');
        }
        

        return redirect(route('labs.index'));
    }

    public function destroy(Lab $lab)
    {
        $labModel = [
            // PackageLab::class,
        ];

        $result = canDelete($labModel, 'lab_id', $lab->id);

        if ($result) {
            return $this->sendError(__('messages.package.lab').' '.__('messages.common.cant_be_deleted'));
        }

        $lab->delete();

        return $this->sendSuccess(__('messages.package.lab').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveLab($id)
    {
        $lab = Lab::find($id);
        $lab->status = ! $lab->status;
        $lab->update(['status' => $lab->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function labExport()
    {
        return Excel::download(new LabExport, 'labs-'.time().'.xlsx');
    }
}
