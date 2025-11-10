<?php

namespace App\Repositories;

use App\Models\Accountant;
use App\Models\admin;
use App\Models\AssignRoster;
use App\Models\Department;
use App\Models\LabTechnician;
use App\Models\Nurse;
use App\Models\Pharmacist;
use App\Models\Receptionist;
use App\Models\Roster;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class AssignRosterRepository
 */
class AssignRosterRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'roster_id',
        'user_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return AssignRoster::class;
    }

    public function getAllRosters()
    {
        return Roster::all();
    }

    public function getAllHospitalStaffs()
    {
        // Retrieve users based on role association with admins, accountants, or nurses
        $adminIds = admin::pluck('user_id')->toArray();
        $accountantIds = Accountant::pluck('user_id')->toArray();
        $nurseIds = Nurse::pluck('user_id')->toArray();
        $receptionistsIds = Receptionist::pluck('user_id')->toArray();
        $labTechniciansIds = LabTechnician::pluck('user_id')->toArray();
        $pharmacistsIds = Pharmacist::pluck('user_id')->toArray();
        
        // Combine all user IDs of staff
        $staffUserIds = array_merge($adminIds, $accountantIds, $nurseIds, $receptionistsIds, $labTechniciansIds, $pharmacistsIds);

        // Retrieve all users who are either admins, accountants, nurses, receptionists, lab technicians and pharmacists
        return User::whereIn('id', $staffUserIds)->get();
    }

    public function getAllDepartments()
    {
        return Department::all();
    }

    public function saveRosterAssignment($data)
    {
        // Create a new AssignRoster record with the provided data
        return AssignRoster::create([
            'roster_id' => $data['roster_id'],
            'user_id' => $data['user_id'],
            'department_id' => $data['department_id'],
        ]);
    }

    public function updateRosterAssignment($id, $data)
    {
        $assignedRoster = $this->find($id);

        if ($assignedRoster) {
            $assignedRoster->update([
                'roster_id' => $data['roster_id'],
                'user_id' => $data['user_id'],
                'department_id' => $data['department_id'],
            ]);

            return $assignedRoster;
        }

        throw new Exception(__('messages.assign_roster.not_found'));
    }

    public function delete($id)
    {
        try {
            $assignedRoster = $this->find($id);

            if (empty($assignedRoster)) {
                throw new Exception(__('messages.assign_roster.title') . ' ' . __('messages.common.not_found'));
            }

            $assignedRoster->delete();

            return true;
        } catch (Exception $e) {
            \Log::error("Error deleting assigned roster: {$e->getMessage()}");
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

}
