<?php

namespace App\Http\Controllers;

use App\Exports\ServiceExport;
use App\Http\Requests\CreateServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\PackageService;
use App\Models\Service;
use App\Models\Insurance;
use App\Repositories\ServiceRepository;
use Flash;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class ServiceController extends AppBaseController
{
    /** @var ServiceRepository */
    private $serviceRepository;

    public function __construct(ServiceRepository $serviceRepo)
    {
        $this->serviceRepository = $serviceRepo;
    }

    public function index()
    {
        $data['statusArr'] = Service::STATUS_ARR;

        return view('services.index', $data);
    }

    public function create()
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        $insurances = $insurances->prepend('All Insurance', 'all');
        return view('services.create', compact('insurances'));
    }

    public function store(CreateServiceRequest $request)
    {
        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['rate'] = removeCommaFromNumbers($input['rate']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);

        if ($input['insurance_id'] == 'all') {
            $insurances = Insurance::where('status', 1)->get(['name', 'id'])->sort();
            foreach ($insurances as $insurance) {
                $input['insurance_id'] = $insurance->id;
                $input['insurance_name'] = $insurance->name;
                $this->serviceRepository->create($input);
                $this->serviceRepository->createNotification();
            }
        }else{            
            $input['insurance_id'] = $input['insurance_id'];
            $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');
            $exists =  Service::where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
            if ($exists==0) {            
                $this->serviceRepository->create($input);
                $this->serviceRepository->createNotification();
            }else{
                Flash::error(__('messages.packages.service').' Service name has already been registered');
            }            
        }
        if ($exists ?? 1 == 0) {   
            Flash::success(__('messages.package.service').' '.__('messages.common.saved_successfully'));
        }

        return redirect(route('services.index'));
    }

    public function show(Service $service)
    {
        $service = $this->serviceRepository->find($service->id);
        
        if (empty($service)) {
            Flash::error(__('messages.service.service').' '.__('messages.common.not_found'));

            return redirect(route('services.index'));
        }

        return view('services.show')->with('service', $service);
    }

    public function edit(Service $service)
    {
        $insurances = Insurance::where('status', 1)->get()->pluck('name', 'id')->sort();
        
        return view('services.edit', compact('service','insurances'));
    }

    public function update(Service $service, UpdateServiceRequest $request): RedirectResponse
    {
        if (empty($service)) {
            Flash::error(__('messages.service.service').' '.__('messages.common.not_found'));

            return redirect(route('services.index'));
        }

        $input = $request->all();
        $input['status'] = isset($input['status']) ? 1 : 0;
        $input['rate'] = removeCommaFromNumbers($input['rate']);
        $input['topup'] = removeCommaFromNumbers($input['topup']);
        $input['non_insured_amount'] = removeCommaFromNumbers($input['non_insured_amount']);
        $input['insurance_id'] = $input['insurance_id'];
        $input['insurance_name'] =  Insurance::where('id', $input['insurance_id'])->value('name');

        $exists =  Service::where('id', '!=',$service->id)->where('name', $input['name'])->where('insurance_id', $input['insurance_id'])->count();
        if ($exists==0) {            
            $this->serviceRepository->update($input, $service->id);
            Flash::success(__('messages.package.service').' '.__('messages.common.updated_successfully'));
        }else{
            Flash::error(__('messages.service.service').' Service name has already been registered');
        }
        

        return redirect(route('services.index'));
    }

    public function destroy(Service $service)
    {
        $serviceModel = [
            PackageService::class,
        ];

        $result = canDelete($serviceModel, 'service_id', $service->id);

        if ($result) {
            return $this->sendError(__('messages.package.service').' '.__('messages.common.cant_be_deleted'));
        }

        $service->delete();

        return $this->sendSuccess(__('messages.package.service').' '.__('messages.common.deleted_successfully'));
    }

    public function activeDeActiveService($id)
    {
        $service = Service::find($id);
        $service->status = ! $service->status;
        $service->update(['status' => $service->status]);

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }

    public function serviceExport()
    {
        return Excel::download(new ServiceExport, 'services-'.time().'.xlsx');
    }
}
