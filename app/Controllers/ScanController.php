<?php

namespace App\Http\Controllers;

use App\Exports\ScanExport;
use App\Http\Requests\CreateScanRequest;
use App\Http\Requests\UpdateScanRequest;
use App\Models\Scan;
use App\Models\Insurance;
use App\Repositories\ScanRepository;
use DB;
use Exception;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;

class ScanController extends AppBaseController
{
    /** @var ScanRepository */
    private $scanRepository;

    public function __construct(ScanRepository $scanRepo)
    {
        $this->scanRepository = $scanRepo;
    }

    public function index()
    {
        $data['statusArr'] = Scan::STATUS_ARR;

        return view('scans.index', $data);
    }

    public function create()
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        $insurances = $insurances->prepend('All Insurance', 'all');
        return view('scans.create',compact('insurances'));
    }

    public function store(CreateScanRequest $request)
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
                $this->scanRepository->create($input);
                $this->scanRepository->createNotification();
            }
        }else{            
            $input['insurance_id'] = $input['insurance_id'];
            $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');
            $exists =  Scan::where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
            if ($exists==0) {            
                $this->scanRepository->create($input);
                $this->scanRepository->createNotification();
            }else{
                Flash::error(__('messages.packages.scan').' Scan name has already been registered');
            }            
        }
        if ($exists ?? 1 == 0) {   
            Flash::success(__('messages.package.scan').' '.__('messages.common.saved_successfully'));
        }

        return redirect(route('scans.index'));
    }

    public function show(Scan $scan)
    {
        $scan = $this->scanRepository->find($scan->id);
        
        if (empty($scan)) {
            Flash::error(__('messages.scan.scan').' '.__('messages.common.not_found'));

            return redirect(route('scans.index'));
        }

        return view('scans.show')->with('scan', $scan);
    }

    public function edit(Scan $scan)
    {
        $isEdit = true;
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        return view('scans.edit', compact('insurances', 'scan','isEdit'));
    }

    public function update(Scan $scan, UpdateScanRequest $request): RedirectResponse
    {
        if (empty($scan)) {
            Flash::error(__('messages.scan.scan').' '.__('messages.common.not_found'));

            return redirect(route('scans.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['tariff'] = removeCommaFromNumbers($input['tariff']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);
        $input['insurance_id'] = $input['insurance_id'];
        $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');

        $exists =  Scan::where('id', '!=',$scan->id)->where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
        if ($exists==0) {            
            $this->scanRepository->update($input, $scan->id);
            Flash::success(__('messages.package.scan').' '.__('messages.common.updated_successfully'));
        }else{
            Flash::error(__('messages.scan.scan').' Scan name has already been registered');
        }
        

        return redirect(route('scans.index'));
    }

    public function destroy(Scan $scan)
    {
        $scanModel = [
            // PackageScan::class,
        ];

        $result = canDelete($scanModel, 'scan_id', $scan->id);

        if ($result) {
            return $this->sendError(__('messages.package.scan').' '.__('messages.common.cant_be_deleted'));
        }

        $scan->delete();

        return $this->sendSuccess(__('messages.package.scan').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeactiveScan($id)
    {
        $scan = Scan::find($id);
        $scan->status = ! $scan->status;
        $scan->update(['status' => $scan->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function scanExport()
    {
        return Excel::download(new ScanExport, 'scans-'.time().'.xlsx');
    }
}
