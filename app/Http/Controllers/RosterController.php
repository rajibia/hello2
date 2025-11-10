<?php

namespace App\Http\Controllers;

use App\Repositories\RosterRepository;
use Exception;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class RosterController extends Controller
{
    /**
     * @var RosterRepository
     */
    private $rosterRepository;

    public function __construct(RosterRepository $rosterRepository)
    {
        $this->rosterRepository = $rosterRepository;
    }

    public function index()
    {
        // Fetch all shifts using the repository
        $shifts = $this->rosterRepository->getAllShifts();

        // Pass the shifts to the view
        return view('rosters.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $this->rosterRepository->store($request->only(['shift_id', 'start_date', 'end_date']));
            Flash::success(__('messages.roster.roster').' '.__('messages.common.saved_successfully')); 
            return redirect()->route('duty.roster.roster.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => __('messages.error_occurred')]);
        }
    }

    public function edit($id)
    {
        $roster = $this->rosterRepository->find($id);

        if (empty($roster)) {
            return redirect()->route('rosters.index')->withErrors([
                'error' => __('messages.roster.title') . ' ' . __('messages.common.not_found')
            ]);
        }

        // Fetch all shifts for the dropdown
        $shifts = $this->rosterRepository->getAllShifts();

        // Return the edit view with both roster and shifts data
        return view('rosters.edit', compact('roster', 'shifts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            // Update the roster using the repository
            $this->rosterRepository->updateRoster($id, $request->only(['shift_id', 'start_date', 'end_date']));
            
            Flash::success(__('messages.roster.roster') . ' ' . __('messages.common.updated_successfully'));
            return redirect()->route('duty.roster.roster.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => __('messages.error_occurred')]);
        }
    }

    public function destroy($id)
   {
    // Log the requested URL for debugging
    \Log::info("Attempting to delete roster with ID: $id");
    
    // Find the roster by ID
    $roster = $this->rosterRepository->find($id);

    // Check if roster exists
    if (empty($roster)) {
        \Log::warning("Roster not found for ID: $id");
        return response()->json([
            'error' => __('messages.roster.roster') . ' ' . __('messages.common.not_found')
        ], 404);
    }

    // Delete the roster
    $this->rosterRepository->delete($id);

    \Log::info("Roster deleted successfully for ID: $id");

    return response()->json([
        'success' => __('messages.roster.roster') . ' ' . __('messages.common.deleted_successfully')
    ]);
}
}
