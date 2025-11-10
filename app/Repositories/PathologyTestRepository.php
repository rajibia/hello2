<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\PathologyCategory;
use App\Models\PathologyTestTemplate;
use App\Models\PathologyTest;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\LabTechnician;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;

/**
 * Class PathologyTestRepository
 *
 * @version April 14, 2020, 9:33 am UTC
 */
class PathologyTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'bill_no',
        'note',
        'previous_report_value',
        'discount',
        'amount_paid',
        'balance',
        'patient_id',
        'ipd_id',
        'opd_id',
        'doctor_id',
        'case_id',
        'total',
        'status',
        'template_id',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyTest::class;
    }

    public function store($input)
    {
        try {
            DB::beginTransaction();

            // Generate bill number
            $billNo = $this->generateBillNumber();

            // Handle multiple templates
            $templateIds = is_array($input['template_id']) ? $input['template_id'] : [$input['template_id']];
            $reportDates = is_array($input['report_date']) ? $input['report_date'] : [$input['report_date']];
            $formConfigurations = is_array($input['form_configuration']) ? $input['form_configuration'] : [$input['form_configuration']];

            // Calculate total from all templates
            $total = 0;
            $templates = [];

            foreach ($templateIds as $index => $templateId) {
                if (empty($templateId)) continue;

                $template = PathologyTestTemplate::findOrFail($templateId);
                $total += $template->standard_charge;
                $templates[] = [
                    'template' => $template,
                    'report_date' => $reportDates[$index] ?? null,
                    'form_configuration' => $formConfigurations[$index] ?? null,
                ];
            }

            if (empty($templates)) {
                throw new Exception('At least one test template is required.');
            }

            $discount = 0;
            $amountPaid = 0;
            $balance = $total;

            // Create pathology test
            $pathologyTest = PathologyTest::create([
                'bill_no' => $billNo,
                'note' => $input['note'] ?? null,
                'discount' => $discount,
                'amount_paid' => $amountPaid,
                'balance' => $balance,
                'patient_id' => $input['patient_id'],
                'ipd_id' => $input['ipd_id'] ?? null,
                'opd_id' => $input['opd_id'] ?? null,
                'maternity_id' => $input['maternity_id'] ?? null,
                'doctor_id' => $input['doctor_id'],
                'case_id' => $input['case_id'],
                'total' => $total,
                'status' => 0, // Pending
                'template_id' => $templates[0]['template']->id, // Use first template as primary
                'test_results' => [], // Initialize empty results
                'collection_date' => $input['collection_date'] ?? null,
                'expected_date' => $input['expected_date'] ?? null,
                'diagnosis' => $input['diagnosis'] ?? null,
                'performed_by' => auth()->id(),
            ]);

            // Create pathology test items for each template
            foreach ($templates as $templateData) {
                \App\Models\PathologyTestItem::create([
                    'pathology_id' => $pathologyTest->id,
                    'report_date' => $templateData['report_date'] ?? $input['expected_date'],
                    'test_name' => $templateData['template']->id, // Reference to the template
                ]);
            }

            DB::commit();

            return $pathologyTest;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $id)
    {
        try {
            DB::beginTransaction();

            $pathologyTest = PathologyTest::findOrFail($id);

            // Update basic information
            $pathologyTest->update([
                'note' => $input['note'] ?? $pathologyTest->note,
                'previous_report_value' => $input['previous_report_value'] ?? $pathologyTest->previous_report_value,
                'patient_id' => $input['patient_id'] ?? $pathologyTest->patient_id,
                'ipd_id' => $input['ipd_id'] ?? $pathologyTest->ipd_id,
                'opd_id' => $input['opd_id'] ?? $pathologyTest->opd_id,
                'maternity_id' => $input['maternity_id'] ?? $pathologyTest->maternity_id,
                'doctor_id' => $input['doctor_id'] ?? $pathologyTest->doctor_id,
                'case_id' => $input['case_id'] ?? $pathologyTest->case_id,
                'template_id' => $input['template_id'] ?? $pathologyTest->template_id,
                'lab_technician_id' => $input['lab_technician_id'] ?? $pathologyTest->lab_technician_id,
                'collection_date' => $input['collection_date'] ?? $pathologyTest->collection_date,
                'expected_date' => $input['expected_date'] ?? $pathologyTest->expected_date,
            ]);

            // Update test results if provided
            if (isset($input['test_results']) && is_array($input['test_results'])) {
                $pathologyTest->test_results = $input['test_results'];
                $pathologyTest->save();
            }

            // Update status if provided
            if (isset($input['status'])) {
                $pathologyTest->status = $input['status'];

                // If approved, set approval details
                if ($input['status'] == 1) { // Approved
                    $pathologyTest->approved_by_id = auth()->id();
                    $pathologyTest->approved_date = now();
                }

                $pathologyTest->save();
            }

            DB::commit();

            return $pathologyTest;
        } catch (Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getPathologyTemplate()
    {
        return PathologyTestTemplate::where('is_dynamic_form', true)
            ->select('id', 'test_name', 'short_name', 'test_type', 'category_id', 'charge_category_id', 'standard_charge')
            ->with(['pathologycategory', 'chargecategory'])
            ->get();
    }

    public function getPathologyAssociatedData()
    {
        $data['pathologyCategories'] = PathologyCategory::all()->pluck('name', 'id');
        $data['chargeCategories'] = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');

        return $data;
    }

    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }

    public function getParameterDataList()
    {
        $result = \App\Models\PathologyParameter::all()->pluck('parameter_name', 'id')->toArray();

        $parameters = [];
        foreach ($result as $key => $item) {
            $parameters[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $parameters;
    }

    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->where('doctorUser.status', '=', 1)->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }

    public function getLabTechnicians()
    {
        return LabTechnician::all()->pluck('name', 'id');
    }

    public function getSettingList()
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    private function generateBillNumber()
    {
        $lastTest = PathologyTest::latest()->first();
        $lastNumber = $lastTest ? intval(substr($lastTest->bill_no, 3)) : 0;
        $newNumber = $lastNumber + 1;

        return 'PT' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    public function getTestResults($testId)
    {
        $test = PathologyTest::with('pathologytesttemplate')->findOrFail($testId);

        return [
            'test' => $test,
            'form_configuration' => $test->getFormConfiguration(),
            'test_results' => $test->test_results ?? [],
        ];
    }

    public function updateTestResults($testId, $results)
    {
        $test = PathologyTest::findOrFail($testId);
        $test->test_results = $results;
        $test->save();

        return $test;
    }

    public function approveTest($testId, $approvedBy = null)
    {
        $test = PathologyTest::findOrFail($testId);
        $test->status = 1; // Approved
        $test->approved_by_id = $approvedBy ?? auth()->id();
        $test->approved_date = now();
        $test->save();

        return $test;
    }
}
