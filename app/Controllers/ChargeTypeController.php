<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChargeTypeRequest;
use App\Http\Requests\UpdateChargeTypeRequest;
use App\Models\ChargeCategory;
use App\Models\ChargeType;
use App\Models\RadiologyTest;
use App\Repositories\ChargeTypeRepository;

class ChargeTypeController extends AppBaseController
{
    /** @var ChargeTypeRepository */
    private $chargeTypeRepository;

    public function __construct(ChargeTypeRepository $chargeTypeRepo)
    {
        $this->chargeTypeRepository = $chargeTypeRepo;
    }

    public function index()
    {
        $chargeTypes = ChargeType::where('status', 1)->get()->pluck('name', 'id')->toArray();
        asort($chargeTypes);

        return view('charge_types.index', compact('chargeTypes'));
    }

    public function store(CreateChargeTypeRequest $request)
    {
        $input = $request->all();
        // let's check if the charge type of similar name already exists
        $existingChargeType = $this->chargeTypeRepository->findChargeTypeByName($input['name']);
        if($existingChargeType){
            return $this->sendError(__('messages.charge_type.charge_type').' '.__('messages.charge_type.already_exists'));
        }
        $this->chargeTypeRepository->create($input);

        return $this->sendSuccess(__('messages.charge_type.charge_type').' '.__('messages.common.saved_successfully'));
    }

    public function show(ChargeType $chargeType)
    {
        $chargeTypes = ChargeType::where('status', 1)->get()->pluck('name', 'id');

        return view('charge_types.show', compact('chargeType', 'chargeTypes'));
    }

    public function edit(ChargeType $chargeType)
    {
        return $this->sendResponse($chargeType, 'Charge Type Retrieved Successfully.');
    }

    public function update(ChargeType $chargeType, UpdateChargeTypeRequest $request)
    {
        $chargeType = $this->chargeTypeRepository->update($request->all(), $chargeType->id);

        return $this->sendSuccess(__('messages.charge_type.charge_type').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(ChargeType $chargeType)
    {
        $chargeTypeModels = [
            ChargeCategory::class,
        ];

        $result = canDelete($chargeTypeModels, 'charge_type', $chargeType->id);

        if ($result) {
            return $this->sendError(__('messages.charge_type.charge_type').' '.__('messages.common.cant_be_deleted'));
        }

        $this->chargeTypeRepository->delete($chargeType->id);

        return $this->sendSuccess(__('messages.charge_type.charge_type').' '.__('messages.common.deleted_successfully'));
    }
}