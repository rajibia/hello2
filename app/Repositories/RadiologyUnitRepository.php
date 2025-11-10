<?php

namespace App\Repositories;

use App\Models\RadiologyUnit;

/**
 * Class RadiologyCategoryRepository
 *
 * @version April 11, 2020, 5:39 am UTC
 */
class RadiologyUnitRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return RadiologyUnit::class;
    }
}
