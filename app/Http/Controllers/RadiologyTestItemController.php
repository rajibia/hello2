<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\RadiologyTestItem;
use App\Models\RadiologyParameterItem;
use App\Models\LabTechnician;
use App\Models\RadiologyTest;



class RadiologyTestItemController extends AppBaseController
{
    //

    public function showModalCollection ($radiologyTestItemId) {

        $radItem = RadiologyTestItem::where('id', $radiologyTestItemId)->get();

        $labTechnician = LabTechnician::with('user')->get();

        return [
            'radItem' => $radItem,
            'labTechnician' => $labTechnician
        ];

    }

    public function showModalResult ($radiologyTestItemId) {

        $radItem = RadiologyTestItem::with(['radiologytesttemplate', 'lab_technician.user', 'approved_by.user'])->where('id', $radiologyTestItemId)->get();
        $radParamItem = RadiologyParameterItem::with(['radiologyParameter'])->where('radiology_id', $radItem[0]->test_name)->get();
        $radiologyTest = RadiologyTest::with(['radiologycategory', 'chargecategory', 'opd', 'ipd', 'patientcase', 'patient.patientUser', 'doctor.doctorUser', 'radiologyitem'])
                                    ->where('id', $radItem[0]->radiology_id)->get();
        $labTechnician = LabTechnician::with('user')->get();

        return [
            'radItem' => $radItem,
            'labTechnician' => $labTechnician,
            'radParamItem' => $radParamItem,
            'radiologyTest' => $radiologyTest
        ];

    }

    public function updateModalCollection (Request $request, $radiologyTestItemId) {


        // Update the pathology test item
        RadiologyTestItem::where('id', $request->rad_item_id)->update([
            'lab_technician_id' => $request->collected_by,
            'sample_collection_date' => date("Y-m-d", strtotime($request->collection_date)), // Correct date format
        ]);

        // Return a successful response
        return $this->sendResponse('Success!', 'Radiology Test Updated Successfully.');

    }

    public function updateModalResult (Request $request, $radiologyTestItemId) {

       

        $path = "";

        if ($request->hasFile('report_doc')) {
            $path = $request->file('report_doc')->store('/uploads/documents');
            // return $path;
        }

    


    
        try {

            \DB::beginTransaction();

             // Update the pathology test item
            RadiologyTestItem::where('id', $request->rad_item_id)->update([
                'approved_by_id' => $request->approved_by,
                'approval_date' => date("Y-m-d", strtotime($request->approved_date)), // Correct date format
            ]);

            $radTestItem = RadiologyTestItem::where('id', $request->rad_item_id)->get();

            if ($request->hasFile('report_doc')) {

                RadiologyTest::where('id', $radTestItem[0]->radiology_id)->update([
                    'patient_result' => $request->result,
                    'report_document' => basename($path)
                ]);
            }

            foreach ($request->ids as $index => $id) {
                RadiologyParameterItem::where('id', $id)
                    ->update(['report_value' => $request->patient_report_value[$index]]);
            }

            \DB::commit();

             // Return a successful response
            return $this->sendResponse('Success!', 'Radiology Test Updated Successfully.');
        } catch (Exception $e) {

            \DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

       
        

       

    }
}
