<?php

namespace App\Repositories;

use App\Models\ChargeType;

/**
 * Class ChargeTypeRepository
 *
 * @version April 11, 2020, 5:26 am UTC
 */
class ChargeTypeRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return ChargeType::class;
    }

    public function findChargeTypeByName($name){
        return $this->model->whereRaw('LOWER(name)=?', [strtolower($name)])->first();
    }
}
