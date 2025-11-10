<?php

namespace App\Http\Controllers;

use App\Exports\ShiftsExport;
use App\Http\Requests\StoreShiftRequest;
use App\Repositories\ShiftsRepository;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Flash;

class ShiftsController extends Controller
{
    /** @var ShiftsRepository */
    private $shiftsRepository;

    public function __construct(ShiftsRepository $shiftsRepo)
    {
        $this->shiftsRepository = $shiftsRepo;
    }

    public function index()
    {
        return view('shifts.index');
    }

    public function store(StoreShiftRequest $request) 
    {
        $input = $request->all();

        // Use the repository to store the shift
        $this->shiftsRepository->store($input);

        Flash::success(__('messages.shift.shift').' '.__('messages.common.saved_successfully')); // Flash message for success

        return redirect()->route('duty.roster.shifts.index');
    }

    public function show($id)
    {
        $shift = $this->shiftsRepository->find($id);
        if (!$shift) {
            return response()->json(['error' => 'Shift not found'], 404);
        }

        return response()->json($shift);
    }

    public function edit($id)
    {
        $shift = $this->shiftsRepository->find($id);

        if (empty($shift)) {
            return redirect()->route('shifts.index')->withErrors([
                'error' => __('messages.shift.shift') . ' ' . __('messages.common.not_found')
            ]);
        }

        // Return the edit view with shift data
        return view('shifts.edit', compact('shift'));
    }

    public function update(Request $request, $id)
    {
        $shift = $this->shiftsRepository->find($id);

        if (empty($shift)) {
            return redirect()->route('duty.roster.shifts.index')->withErrors([
                'error' => __('messages.shift.shift') . ' ' . __('messages.common.not_found')
            ]);
        }

        // Validate the request data
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'shift_start' => 'required',
            'shift_end' => 'required',
            'break_duration' => 'required|integer',
        ]);

        // Update the shift with the provided data
        $this->shiftsRepository->update($request->all(), $id);

        Flash::success(__('messages.shift.shift') . ' ' . __('messages.common.updated_successfully'));

        return redirect()->route('duty.roster.shifts.index');
    }

    public function shiftExport()
    {
        return Excel::download(new ShiftsExport, 'shifts-'.time().'.xlsx');
    }

    public function destroy($id)
    {
        // Find the shift by ID
        $shift = $this->shiftsRepository->find($id);

        // Check if shift exists
        if (empty($shift)) {
            return response()->json([
                'error' => __('messages.shift.shift') . ' ' . __('messages.common.not_found')
            ], 404);
        }

        // Attempt to delete the shift
        $this->shiftsRepository->delete($id);

        return response()->json([
            'success' => __('messages.shift.shift') . ' ' . __('messages.common.deleted_successfully')
        ]);
    }
}
