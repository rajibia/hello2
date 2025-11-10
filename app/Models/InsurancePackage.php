<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class InsurancePackage
 *
 * @property int $id
 * @property int $insurance_id
 * @property string $package_name
 * @property float $package_charge
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|InsurancePackage newModelQuery()
 * @method static Builder|InsurancePackage newQuery()
 * @method static Builder|InsurancePackage query()
 * @method static Builder|InsurancePackage whereCreatedAt($value)
 * @method static Builder|InsurancePackage wherePackageCharge($value)
 * @method static Builder|InsurancePackage wherePackageName($value)
 * @method static Builder|InsurancePackage whereId($value)
 * @method static Builder|InsurancePackage whereInsuranceId($value)
 * @method static Builder|InsurancePackage whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class InsurancePackage extends Model
{
    public static $rules = [
        'insurance_id' => 'required',
        'package_name' => 'required',
    ];

    public $table = 'insurance_packages';

    public $fillable = [
        'insurance_id',
        'package_name',
    ];

    protected $casts = [
        'id' => 'integer',
        'package_name' => 'string',
    ];
}
