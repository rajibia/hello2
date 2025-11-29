<?php

namespace App\Http\Controllers;

use App\Models\ManagementPlan;
use App\Models\OpdPatientDepartment;
use App\Models\IpdPatientDepartment;
use Illuminate\Http\Request;

class ManagementPlanController extends AppBaseController
{
    /**
     * Store a newly created management plan in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'management_plan' => 'required|string',
            'opd_id' => 'nullable|exists:opd_patient_departments,id',
            'ipd_id' => 'nullable|exists:ipd_patient_departments,id',
        ]);

        $input = $request->all();
        $input['user_id'] = auth()->id();

        // Determine patient_id based on opd_id or ipd_id
        if (!empty($input['opd_id'])) {
            $opdPatient = OpdPatientDepartment::find($input['opd_id']);
            if ($opdPatient) {
                $input['patient_id'] = $opdPatient->patient_id;
            }
        } elseif (!empty($input['ipd_id'])) {
            $ipdPatient = IpdPatientDepartment::find($input['ipd_id']);
            if ($ipdPatient) {
                $input['patient_id'] = $ipdPatient->patient_id;
            }
        }

        if (empty($input['patient_id'])) {
            return $this->sendError('Patient ID is required');
        }

        try {
            ManagementPlan::create($input);
            return $this->sendSuccess('Management Plan ' . __('messages.common.saved_successfully'));
        } catch (\Exception $e) {
            return $this->sendError('Failed to save management plan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified management plan.
     */
    public function show(ManagementPlan $managementPlan)
    {
        return $this->sendResponse($managementPlan, 'Management Plan retrieved successfully.');
    }

    /**
     * Update the specified management plan in storage.
     */
    public function update(Request $request, ManagementPlan $managementPlan)
    {
        $request->validate([
            'management_plan' => 'required|string',
        ]);

        $input = $request->all();
        $managementPlan->update($input);

        return $this->sendSuccess('Management Plan ' . __('messages.common.updated_successfully'));
    }

    /**
     * Remove the specified management plan from storage.
     */
    public function destroy(ManagementPlan $managementPlan)
    {
        $managementPlan->delete();
        return $this->sendSuccess('Management Plan ' . __('messages.common.deleted_successfully'));
    }
}
