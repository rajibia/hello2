<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRadiologyUnitRequest;
use App\Http\Requests\UpdateRadiologyUnitRequest;
use App\Models\RadiologyParameter;
use Illuminate\Http\Request;
use App\Models\RadiologyUnit;
use App\Repositories\RadiologyUnitRepository;

class RadiologyUnitController extends AppBaseController
{

    /** @var RadiologyUnitRepository */
    private $radiologyUnitRepository;

    public function __construct(RadiologyUnitRepository $radiologyUnitRepo)
    {
        $this->radiologyUnitRepository = $radiologyUnitRepo;
    }

    public function index(Request $request)
    {
        return view('radiology_units.index');
    }

    public function store(CreateRadiologyUnitRequest $request)
    {
        $input = $request->all();
        $this->radiologyUnitRepository->create($input);

        return $this->sendSuccess('Radiology Unit '.__('messages.common.saved_successfully'));
    }

    public function edit(RadiologyUnit $radiologyUnit)
    {
        if (! canAccessRecord(RadiologyUnit::class, $radiologyUnit->id)) {
            return $this->sendError(__('messages.flash.not_allow_access_record'));
        }

        return $this->sendResponse($radiologyUnit, __('Radiology Unit retrieved successfully.'));
    }

    public function update(RadiologyUnit $radiologyUnit, UpdateRadiologyUnitRequest $request)
    {
        $input = $request->all();
        $this->radiologyUnitRepository->update($input, $radiologyUnit->id);

        return $this->sendSuccess('Radiology Unit '.__('messages.common.updated_successfully'));
    }

    public function destroy(RadiologyUnit $radiologyUnit)
    {
        if (! canAccessRecord(RadiologyUnit::class, $radiologyUnit->id)) {
            return $this->sendError('Radiology Unit not found');
        }

        $radiologyParameterModels = [
            RadiologyParameter::class,
        ];
        $result = canDelete($radiologyParameterModels, 'unit_id', $radiologyUnit->id);

        if ($result) {
            return $this->sendError('Radiology Unit cannot be deleted');
        }

        $radiologyUnit->delete();

        return $this->sendSuccess('Radiology Unit '.__('messages.common.deleted_successfully'));
    }
}
