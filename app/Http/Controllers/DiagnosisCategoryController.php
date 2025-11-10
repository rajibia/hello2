<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateDiagnosisCategoryRequest;
use App\Http\Requests\UpdateDiagnosisCategoryRequest;
use App\Imports\DiagnosisCategoriesImport;
use App\Models\DiagnosisCategory;
use App\Models\PatientDiagnosisTest;
use App\Repositories\DiagnosisCategoryRepository;
use Maatwebsite\Excel\Facades\Excel;


class DiagnosisCategoryController extends AppBaseController
{
    /**
     * @var DiagnosisCategoryRepository
     */
    private $categoryRepository;

    public function __construct(DiagnosisCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        return view('diagnosis_categories.index');
    }

    public function diagnosisUpload()
    {
        return view('diagnosis_categories.upload');
    }

    // public function diagnosisUploadICD10(Request $request)
    // {
    //     // Validate the file input
    //     $request->validate([
    //         'icd10_file' => 'required|mimes:xlsx,xls,csv|max:2048',
    //     ]);

    //     // Import the Excel file and create records in the DiagnosisCategory model
    //     try {
    //         Excel::import(new DiagnosisCategoriesImport, $request->file('icd10_file'));

    //         return redirect()->route('diagnosis.category.index')->with('success', 'ICD-10 codes uploaded successfully.');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'There was an error uploading the file: ' . $e->getMessage());
    //     }
    // }


    public function diagnosisUploadICD10(Request $request)
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'icd10_file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
            ]);

            // Import the Excel file
            Excel::import(new DiagnosisCategoriesImport, $request->file('icd10_file'));

            return redirect()->route('diagnosis.category.index')
                            ->with('success', 'ICD-10 codes uploaded successfully.');
        } catch (\Throwable $e) {
            // Log the error for debugging
            \Log::error('Excel Import Error: ' . $e->getMessage());

            // Redirect back with the error message
            return back()->with('error', 'There was an error uploading the file: ' . $e->getMessage());
        }
    }

    
    public function store(CreateDiagnosisCategoryRequest $request)
    {
        $input = $request->all();
        $this->categoryRepository->create($input);

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.saved_successfully'));
    }

    public function show(DiagnosisCategory $diagnosisCategory)
    {
        return view('diagnosis_categories.show', compact('diagnosisCategory'));
    }

    public function edit(DiagnosisCategory $diagnosisCategory)
    {
        return $this->sendResponse($diagnosisCategory, 'Diagnosis Category retrieved successfully.');
    }

    public function update(UpdateDiagnosisCategoryRequest $request, DiagnosisCategory $diagnosisCategory)
    {
        $input = $request->all();
        $this->categoryRepository->update($input, $diagnosisCategory->id);

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.updated_successfully'));
    }

    public function destroy(DiagnosisCategory $diagnosisCategory)
    {
        $diagnosisCategoryModal = [
            PatientDiagnosisTest::class,
        ];

        $result = canDelete($diagnosisCategoryModal, 'category_id', $diagnosisCategory->id);

        if ($result) {
            return $this->sendError(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.cant_be_deleted'));
        }

        $diagnosisCategory->delete();

        return $this->sendSuccess(__('messages.diagnosis_category.diagnosis_category').' '.__('messages.common.deleted_successfully'));
    }
}
