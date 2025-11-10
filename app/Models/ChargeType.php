<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class ChargeCategory
 *
 * @version April 11, 2020, 5:26 am UTC
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $charge_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereChargeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ChargeType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ChargeType extends Model
{
    public $table = 'charge_types';

    
    public $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'integer',
    ];

    public static $rules = [
        'name' => 'required|unique:charge_categories,name',
    ];
}
