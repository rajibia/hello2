<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRadiologyParameterRequest;
use App\Http\Requests\UpdateRadiologyParameterRequest;
use App\Models\RadiologyParameter;
use App\Models\RadiologyParameterItem;
use App\Repositories\RadiologyParameterRepository;
use Illuminate\Http\Request;

class RadiologyParameterController extends AppBaseController
{
    /** @var RadiologyParameterRepository */
    private $radiologyParameterRepository;

    public function __construct(RadiologyParameterRepository $radiologyParameterRepo)
    {
        $this->radiologyParameterRepository = $radiologyParameterRepo;
    }

    public function index(Request $request)
    {
        $unit = $this->radiologyParameterRepository->getRadiologyUnitData();

        return view('radiology_parameter.index', compact('unit'));
    }

    public function store(CreateRadiologyParameterRequest $request)
    {
        $input = $request->all();
        $this->radiologyParameterRepository->create($input);

        return $this->sendSuccess('Radiology Parameter '.__('messages.common.saved_successfully'));
    }

    public function edit(RadiologyParameter $radiologyParameter)
    {
        if (! canAccessRecord(RadiologyParameter::class, $radiologyParameter->id)) {
            return $this->sendError(__('messages.flash.not_allow_access_record'));
        }

        return $this->sendResponse($radiologyParameter, 'Radiology Category Retrieved');
    }

    public function update(RadiologyParameter $radiologyParameter, UpdateRadiologyParameterRequest $request)
    {
        $input = $request->all();
        $this->radiologyParameterRepository->update($input, $radiologyParameter->id);

        return $this->sendSuccess('Radiology Parameter '.__('messages.common.updated_successfully'));
    }

    public function destroy(RadiologyParameter $radiologyParameter)
    {
        if (! canAccessRecord(RadiologyParameter::class, $radiologyParameter->id)) {
            return $this->sendError('Radiology Parameter not found');
        }

        $radiologyParameterModels = [
            RadiologyParameterItem::class,
        ];
        $result = canDelete($radiologyParameterModels, 'parameter_id', $radiologyParameter->id);

        if ($result) {
                return $this->sendError('Radiology Parameter cannot be deleted');
        }

        $radiologyParameter->delete();

        return $this->sendSuccess('Radiology Parameter '.__('messages.common.deleted_successfully'));
    }
}
