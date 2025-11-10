<?php

namespace App\Repositories;

use App\Models\RadiologyParameter;
use App\Models\RadiologyUnit;

/**
 * Class RadiologyCategoryRepository
 *
 * @version April 11, 2020, 5:39 am UTC
 */
class RadiologyParameterRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'parameter_name',
        'reference_range',
        'unit_id',
        'description',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return RadiologyParameter::class;
    }

    public function getRadiologyUnitData()
    {
        $data = RadiologyUnit::all()->pluck('name','id');

        return $data;
    }
}
