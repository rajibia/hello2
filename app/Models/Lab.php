<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class Lab extends Model
{
    public static $rules = [
        'name' => 'required|unique:labs,name',
        'insurance_id' => 'required',
        'tariff' => 'required',
        'non_insured_amount' => 'nullable',
        'topup' => 'nullable',
        'gdrg_code' => 'required',
    ];

    public $table = 'labs';

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
        'non_insured_amount',
        'topup',
        'gdrg_code',
        'status',
    ];
    

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'name' => 'string',
        'insurance_name' => 'string',
        'insurance_id' => 'integer',
        'tariff' => 'double',
        'non_insured_amount' => 'double',
        'topup' => 'double',
        'gdrg_code' => 'string',
    ];



}
