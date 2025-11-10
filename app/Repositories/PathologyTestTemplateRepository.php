<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\PathologyCategory;
use App\Models\PathologyParameter;
use App\Models\PathologyParameterItem;
use App\Models\PathologyTestTemplate;
use App\Models\Patient;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;

/**
 * Class PathologyTestRepository
 *
 * @version April 14, 2020, 9:33 am UTC
 */
class PathologyTestTemplateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'test_name',
        'short_name',
        'test_type',
        'category_id',
        // 'unit',
        'subcategory',
        'method',
        'report_days',
        'charge_category_id',
        'standard_charge',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return PathologyTestTemplate::class;
    }

    public function store($input){
        try {
            DB::beginTransaction();

            $pathologyTestTemplate = PathologyTestTemplate::create([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                // 'unit' => $input['unit'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
                // 'patient_id' => $input['patient_id'],
                // 'ipd_id' => $input['ipd_id'],
                // 'opd_id' => $input['opd_id'],
            ]);

            $pathologyTestTemplate->parameterItems()->delete();

            if (isset($input['parameter_id']) && $input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    PathologyParameterItem::create([
                        'pathology_id' => $pathologyTestTemplate->id,
                        // 'patient_result' => $input['patient_result'][$key],
                        'parameter_id' => $input['parameter_id'][$key],
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function update($input, $pathologyTestTemplate){
        try {
            DB::beginTransaction();

            $pathologyTestTemplate->update([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                // 'unit' => $input['unit'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
                // 'patient_id' => $input['patient_id'],
                // 'ipd_id' => $input['ipd_id'],
                // 'opd_id' => $input['opd_id'],
                // 'status' => $input['status'],
            ]);
            $pathologyTestTemplate->parameterItems()->delete();

            if ($input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    PathologyParameterItem::create([
                        'pathology_id' => $pathologyTestTemplate->id,
                        // 'patient_result' => $input['patient_result'][$key],
                        'parameter_id' => $input['parameter_id'][$key],
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    public function getPathologyAssociatedData()
    {
        $data['pathologyCategories'] = PathologyCategory::all()->pluck('name', 'id');
        $data['chargeCategories'] = ChargeCategory::where('charge_type', 4)->pluck('name', 'id');
        // $data['chargeCategories'] = ChargeCategory::all()->pluck('name', 'id');
        $data['pathologyParameters'] = PathologyParameter::all()->pluck('parameter_name', 'id');

        return $data;
    }
    public function getPatients()
    {
        $patients = Patient::with('patientUser')->get()->where('patientUser.status', '=', 1)->pluck('patientUser.full_name', 'id')->sort();

        return $patients;
    }
    public function getParameterDataList()
    {
        $result = PathologyParameter::all()->pluck('parameter_name', 'id')->toArray();

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
        $parameterItem = PathologyParameterItem::with('pathologyParameter.pathologyUnit')->wherePathologyId($id)->get();

        return $parameterItem;
    }
    public function getSettingList(): array
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return $settings;
    }

    public function getPathologyTemplate()
    {
        $pathologyTemplates = PathologyTestTemplate::pluck('test_name', 'id')->sort();

        return $pathologyTemplates;
    }
}
