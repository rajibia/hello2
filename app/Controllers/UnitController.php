<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;
use App\Repositories\UnitRepository;
use Illuminate\Http\Request;

class UnitController extends AppBaseController
{
    /** @var UnitRepository */
    private $unitRepository;

    public function __construct(UnitRepository $unitRepo)
    {
        $this->unitRepository = $unitRepo;
    }

    public function index()
    {
        return view('units.index');
    }

    public function store(CreateUnitRequest $request)
    {
        $input = $request->all();
        $this->unitRepository->create($input);

        return $this->sendSuccess(__('messages.unit.unit').' '.__('messages.common.saved_successfully'));
    }

    public function edit(Unit $unit)
    {
        return $this->sendResponse($unit, 'Unit retrieved successfully.');
    }

    public function update(Unit $unit, UpdateUnitRequest $request)
    {
        $input = $request->all();
        $this->unitRepository->update($input, $unit->id);

        return $this->sendSuccess(__('messages.unit.unit').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(Unit $unit)
    {
        try {
            $this->unitRepository->delete($unit->id);
            
            return $this->sendSuccess(__('messages.unit.unit').' '.__('messages.common.deleted_successfully'));
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unit.unit') . ' ' . __('messages.common.cant_be_deleted'),
            ], 404);
        }
    }

    public function changeStatus(Request $request)
    {
        $status = Unit::findOrFail($request->id);
        $status->status = !$status->status;
        $status->save();

        return $this->sendSuccess(__('messages.common.status_updated_successfully'));
    }
}
