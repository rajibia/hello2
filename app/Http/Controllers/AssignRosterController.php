<?php

namespace App\Http\Controllers;

use App\Repositories\AssignRosterRepository;
use Illuminate\Http\Request;
use Flash;
class AssignRosterController extends Controller
{
        /**
     * @var AssignRosterRepository
     */
    private $assignRosterRepository;

    public function __construct(AssignRosterRepository $assignRosterRepository)
    {
        $this->assignRosterRepository = $assignRosterRepository;
    }

    public function index()
    {
        // Fetch all shifts using the repository
        $rosters = $this->assignRosterRepository->getAllRosters();
        $staffs = $this->assignRosterRepository->getAllHospitalStaffs();
        $departments = $this->assignRosterRepository->getAllDepartments();

        return view('assign_roster.index', compact('rosters', 'staffs', 'departments'));
    }
    
    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'roster_id' => 'required|exists:rosters,id',
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|string',
        ]);

        // Save the roster assignment
        $this->assignRosterRepository->saveRosterAssignment($validatedData);

        Flash::success(__('messages.assign_roster.success'));

        // Redirect to the index page
        return redirect()->route('duty.roster.assign.index');
    }

    public function edit($id)
    {
        $assignedRoster = $this->assignRosterRepository->find($id);
        $rosters = $this->assignRosterRepository->getAllRosters();
        $staffs = $this->assignRosterRepository->getAllHospitalStaffs();
        $departments = $this->assignRosterRepository->getAllDepartments();

        if (empty($assignedRoster)) {
            Flash::error(__('messages.assign_roster.not_found'));
            return redirect()->route('duty.roster.assign.index');
        }

        return view('assign_roster.edit', compact('assignedRoster', 'rosters', 'staffs', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'roster_id' => 'required|exists:rosters,id',
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|string',
        ]);

        try {
            $this->assignRosterRepository->updateRosterAssignment($id, $validatedData);
            Flash::success(__('messages.assign_roster.updated_successfully'));
        } catch (\Exception $e) {
            Flash::error($e->getMessage());
        }

        return redirect()->route('duty.roster.assign.index');
    }

    public function destroy($id)
    {
        try {
            $assignedRoster = $this->assignRosterRepository->find($id);
            
            if (empty($assignedRoster)) {
                return response()->json([
                    'error' => __('messages.assign_roster.title') . ' ' . __('messages.common.not_found')
                ], 404);
            }

            $this->assignRosterRepository->delete($id);

            return response()->json([
                'success' => __('messages.assign_roster.title') . ' ' . __('messages.common.deleted_successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
