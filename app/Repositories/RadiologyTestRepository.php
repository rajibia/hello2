<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\RadiologyCategory;
use App\Models\RadiologyTest;
use App\Models\RadiologyTestTemplate;
use App\Models\RadiologyParameter;
use App\Models\RadiologyParameterItem;
use App\Models\RadiologyTestItem;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;

/**
 * Class RadiologyTestRepository
 *
 * @version April 14, 2020, 9:33 am UTC
 */
class RadiologyTestRepository extends BaseRepository
{
    protected $fieldSearchable = [
        // 'test_name',
        // 'short_name',
        // 'test_type',
        // 'category_id',
        // 'unit',
        // 'subcategory',
        // 'method',
        // 'report_days',
        // 'charge_category_id',
        // 'standard_charge',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return RadiologyTest::class;
    }

    public function store($input){

        $bill_no = $this->generateRadiologyId();

        try {
            DB::beginTransaction();

            $radiologyTest = RadiologyTest::create([
                'bill_no' => $bill_no,
                'patient_id' => $input['patient_id'],
                'opd_id' => $input['opd_id'] ?? '',
                'ipd_id' => $input['ipd_id'] ?? '',
                'case_id' => $input['case_id'],
                'doctor_id' => $input['doctor_id'],
                'note' => $input['note'] ?? '',
                'previous_report_value' => $input['previous_report_value'] ?? '',
                'status' => 0,
            ]);

            if (isset($input['template_id']) && is_array($input['template_id'])) {
                foreach ($input['template_id'] as $key => $value) {
                    RadiologyTestItem::create([
                        'radiology_id' => $radiologyTest->id,
                        'report_date' => $input['report_date'][$key] ?? null,
                        'test_name' => $value, // This should be the template ID
                    ]);
                }
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage() . $bill_no);
        }
    }

    public function update($input, $radiologyTest){

        try {
            DB::beginTransaction();

            $radiologyTest->update([
                'bill_no' => $radiologyTest->bill_no,
                'patient_id' => $input['patient_id'],
                'opd_id' => $input['opd_id'] ?? '',
                'ipd_id' => $input['ipd_id'] ?? '',
                'case_id' => $input['case_id'],
                'doctor_id' => $input['doctor_id'],
                'note' => $input['note'] ?? '',
                'previous_report_value' => $input['previous_report_value'] ?? '',
                'status' => 0,
            ]);
            $radiologyTest->radiologyTestItems()->delete();

            if (isset($input['template_id']) && is_array($input['template_id'])) {
                foreach ($input['template_id'] as $key => $value) {
                    RadiologyTestItem::create([
                        'radiology_id' => $radiologyTest->id,
                        'report_date' => $input['report_date'][$key] ?? null,
                        'test_name' => $value, // This should be the template ID
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getRadiologyAssociatedData()
    {
        $data['radiologyCategories'] = RadiologyCategory::all()->pluck('name', 'id');
        $data['chargeCategories'] = ChargeCategory::where('charge_type', 9)->pluck('name', 'id');
        $data['radiologyParameters'] = RadiologyParameter::all()->pluck('parameter_name', 'id');

        return $data;
    }
    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }
    public function getParameterDataList()
    {
        $result = RadiologyTestTemplate::pluck('test_name', 'id')->sort()->toArray();

        $parameters = [];
        foreach ($result as $key => $item) {
            $parameters[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $parameters;
    }

    public function getParameterItemData($id){
        $parameterItem = RadiologyParameterItem::with('radiologyParameter.radiologyUnit')->whereRadiologyId($id)->get();

        return $parameterItem;
    }
    public function getSettingList(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return $settings;
    }

    public function getRadiologyTemplate()
    {
        $radiologyTemplates = RadiologyTestTemplate::pluck('test_name', 'id')->sort();

        return $radiologyTemplates;
    }
    public function getDoctors()
    {
        $doctors = Doctor::with('doctorUser')->get()->pluck('doctorUser.full_name', 'id')->sort();

        return $doctors;
    }

    public function generateRadiologyId()
    {
        $lastRecord = RadiologyTest::orderBy('id', 'desc')->first();

        $lastId = $lastRecord ? $lastRecord->bill_no : null;

        if ($lastId) {
            $lastNumber = (int) substr($lastId, 4);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return 'RAD' . $newNumber;
    }

}
