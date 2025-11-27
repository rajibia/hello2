<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PathologyTestItem;
use App\Models\PathologyParameterItem;
use App\Models\LabTechnician;
use App\Models\PathologyTest;



class PathologyTestItemController extends AppBaseController
{
    //

    public function showModalCollection ($pathologyTestItemId) {

        $pathItem = PathologyTestItem::where('id', $pathologyTestItemId)->get();

        $labTechnician = LabTechnician::with('user')->get();

        return [
            'pathItem' => $pathItem,
            'labTechnician' => $labTechnician
        ];

    }

    public function showModalResult ($pathologyTestItemId) {

        $pathItem = PathologyTestItem::with(['pathologytesttemplate', 'lab_technician.user', 'approved_by.user'])->where('id', $pathologyTestItemId)->get();
        $pathParamItem = PathologyParameterItem::with(['pathologyParameter'])->where('pathology_id', $pathItem[0]->test_name)->get();
        $pathologyTest = PathologyTest::with(['pathologycategory', 'chargecategory', 'opd', 'ipd', 'patientcase', 'patient.patientUser', 'doctor.doctorUser', 'pathologyitem'])
                                    ->where('id', $pathItem[0]->pathology_id)->get();
        $labTechnician = LabTechnician::with('user')->get();

        return [
            'pathItem' => $pathItem,
            'labTechnician' => $labTechnician,
            'pathParamItem' => $pathParamItem,
            'pathologyTest' => $pathologyTest
        ];

    }

    public function updateModalCollection (Request $request, $pathologyTestItemId) {


        // Update the pathology test item
        PathologyTestItem::where('id', $request->path_item_id)->update([
            'lab_technician_id' => $request->collected_by,
            'sample_collection_date' => date("Y-m-d", strtotime($request->collection_date)), // Correct date format
        ]);

        // Return a successful response
        return $this->sendResponse('Success!', 'Laboratory Test Updated Successfully.');

    }

    public function updateModalResult (Request $request, $pathologyTestItemId) {

       

        $path = "";

        if ($request->hasFile('report_doc')) {
            $path = $request->file('report_doc')->store('/uploads/documents');
            // return $path;
        }

    


    
        try {

            \DB::beginTransaction();

             // Update the pathology test item
            PathologyTestItem::where('id', $request->path_item_id)->update([
                'approved_by_id' => $request->approved_by,
                'approval_date' => date("Y-m-d", strtotime($request->approved_date)), // Correct date format
            ]);

            $pathTestItem = PathologyTestItem::where('id', $request->path_item_id)->get();

            if ($request->hasFile('report_doc')) {
                PathologyTest::where('id', $pathTestItem[0]->pathology_id)->update([
                    'patient_result' => $request->result,
                    'report_document' => basename($path)
                ]);
            }

            foreach ($request->ids as $index => $id) {
                PathologyParameterItem::where('id', $id)
                    ->update(['report_value' => $request->patient_report_value[$index]]);
            }

            \DB::commit();

             // Return a successful response
            return $this->sendResponse('Success!', 'Laboratory Test Updated Successfully.');
        } catch (Exception $e) {

            \DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }

       
        

       

    }
}
