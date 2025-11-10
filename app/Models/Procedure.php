<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class Procedure extends Model
{
    public static $rules = [
        'name' => 'required|unique:procedures,name',
        'insurance_id' => 'required',
        'tariff' => 'required',
        'non_insured_amount' => 'nullable',
        'topup' => 'nullable',
        'gdrg_code' => 'required',
        'age' => 'required',
        'grouping' => 'required',            
    ];

    public $table = 'procedures';

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const FILTER_STATUS_ARRAY = [
        0 => 'All',
        1 => 'Active',
        2 => 'Deactive',
    ];

    public $fillable = [
        'name',
        'insurance_name',
        'insurance_id',
        'tariff',
        'grouping',
        'non_insured_amount',
        'topup',
        'age',
        'gdrg_code',
        'speciality_code',
        'speciality_description',
        'status'
    ];
    

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'name' => 'string',
        'insurance_name' => 'string',
        'grouping' => 'string',
        'age' => 'string',
        'insurance_id' => 'integer',
        'tariff' => 'double',
        'non_insured_amount' => 'double',
        'topup' => 'double',
        'gdrg_code' => 'string',
        'speciality_code' => 'string',
        'speciality_description' => 'string',
    ];





}
