<?php

namespace App\Repositories;

use App\Models\ChargeCategory;
use App\Models\RadiologyCategory;
use App\Models\RadiologyParameter;
use App\Models\RadiologyParameterItem;
use App\Models\RadiologyTestTemplate;
use App\Models\Patient;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Exception;

/**
 * Class RadiologyTestTemplateRepository
 *
 * @version April 14, 2020, 9:33 am UTC
 */
class RadiologyTestTemplateRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'test_name',
        'short_name',
        'test_type',
        'category_id',
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
        return RadiologyTestTemplate::class;
    }

    public function store($input)
    {
        try {
            DB::beginTransaction();

            $radiologyTestTemplate = RadiologyTestTemplate::create([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
            ]);

            $radiologyTestTemplate->parameterItems()->delete();

            if (isset($input['parameter_id']) && $input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    RadiologyParameterItem::create([
                        'radiology_id' => $radiologyTestTemplate->id,
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

    public function update($input, $radiologyTestTemplate)
    {
        try {
            DB::beginTransaction();

            $radiologyTestTemplate->update([
                'test_name' => $input['test_name'],
                'short_name' => $input['short_name'],
                'test_type' => $input['test_type'],
                'category_id' => $input['category_id'],
                'subcategory' => $input['subcategory'],
                'method' => $input['method'],
                'report_days' => $input['report_days'],
                'charge_category_id' => $input['charge_category_id'],
                'standard_charge' => $input['standard_charge'],
            ]);
            $radiologyTestTemplate->parameterItems()->delete();

            if ($input['parameter_id']) {
                foreach ($input['parameter_id'] as $key => $value) {
                    RadiologyParameterItem::create([
                        'radiology_id' => $radiologyTestTemplate->id,
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

    public function getRadiologyAssociatedData()
    {
        $data['radiologyCategories'] = RadiologyCategory::all()->pluck('name', 'id');
        $data['chargeCategories'] = ChargeCategory::pluck('name', 'id');//where('charge_type', 9)->
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
        $result = RadiologyParameter::all()->pluck('parameter_name', 'id')->toArray();

        $parameters = [];
        foreach ($result as $key => $item) {
            $parameters[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $parameters;
    }

    public function getParameterItemData($id)
    {
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
}
