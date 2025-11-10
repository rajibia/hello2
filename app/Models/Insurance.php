<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\PatientAdmission;

/**
 * Class Insurance
 *
 * @version February 22, 2020, 9:01 am UTC
 *
 * @property int $id
 * @property string $name
 * @property float $service_tax
 * @property float|null $discount
 * @property string|null $remark
 * @property string $insurance_no
 * @property string $insurance_code
 * @property float $hospital_rate
 * @property float $total
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|Insurance newModelQuery()
 * @method static Builder|Insurance newQuery()
 * @method static Builder|Insurance query()
 * @method static Builder|Insurance whereCreatedAt($value)
 * @method static Builder|Insurance whereDiscount($value)
 * @method static Builder|Insurance whereHospitalRate($value)
 * @method static Builder|Insurance whereId($value)
 * @method static Builder|Insurance whereInsuranceCode($value)
 * @method static Builder|Insurance whereInsuranceNo($value)
 * @method static Builder|Insurance whereName($value)
 * @method static Builder|Insurance whereRemark($value)
 * @method static Builder|Insurance whereServiceTax($value)
 * @method static Builder|Insurance whereStatus($value)
 * @method static Builder|Insurance whereTotal($value)
 * @method static Builder|Insurance whereUpdatedAt($value)
 *
 * @mixin Model
 *
 * @property int $is_default
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\InsuranceDisease[] $insuranceDiseases
 * @property-read int|null $insurance_diseases_count
 *
 * @method static Builder|Insurance whereIsDefault($value)
 */
class Insurance extends Model implements HasMedia
{
    use InteractsWithMedia;

    public static $rules = [
        'name' => 'required|unique:insurances,name',
        // 'service_tax' => 'required',
        // 'insurance_no' => 'required',
        // 'hospital_rate' => 'required',
        // 'discount' => 'required|integer',

        'insurance_code' => 'required|unique:insurances,insurance_code',
        'other_identification' => 'nullable',
        'card_type' => 'nullable',
        'claim_check_code' => 'required',
        'non_insurance_medication' => 'required',
        'claim_code_count' => 'nullable|integer',
        'membership_no_count' => 'nullable|integer',
        'card_serial_no_count' => 'nullable|integer',
        'visit_per_month' => 'nullable|integer',
        'image' => 'nullable|mimes:jpg,jpeg,png',
    ];

    public $table = 'insurances';

    const COLLECTION_LOGO_PICTURES = 'logo_photo';

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const IMG_COLUMN = 'image';

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
        'insurance_code',
        'other_identification',
        'card_type',
        'claim_check_code',
        'non_insurance_medication',
        'claim_code_count',
        'membership_no_count',
        'card_serial_no_count',
        'visit_per_month',
        'image',

        // 'service_tax',
        // 'discount',
        // 'remark',
        // 'insurance_no',
        // 'hospital_rate',
        // 'total',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        // 'service_tax' => 'integer',
        // 'discount' => 'integer',
        // 'remark' => 'string',
        // 'insurance_no' => 'string',
        // 'hospital_rate' => 'double',
        // 'total' => 'double',

        'status' => 'integer',
        'name' => 'string',
        'insurance_code' => 'string',
        'other_identification' => 'string',
        'card_type' => 'string',
        'claim_check_code' => 'string',
        'non_insurance_medication' => 'string',
        'claim_code_count' => 'integer',
        'membership_no_count' => 'integer',
        'card_serial_no_count' => 'integer',
        'visit_per_month' => 'integer',
        // 'image' => 'string'
    ];


    // public function prepareData()
    // {
    //     return [
    //         'id' => $this->id,
    //         'image_url' => $this->getApiImageUrlAttribute() ?? __('messages.common.n/a'),
    //     ];
    // }

    public function getLogoUrlAttribute()
    {
        $media = $this->media->first();
        if (! empty($media)) {
            return $media->getFullUrl();
        }

        return $this->value;
    }
    
    public function insuranceDiseases(): HasMany
    {
        return $this->hasMany(InsuranceDisease::class, 'insurance_id');
    }

    public function insurancePackages(): HasMany
    {
        return $this->hasMany(InsurancePackages::class, 'insurance_id');
    }

    public function patientAdmissions(): HasMany
    {
        return $this->hasMany(PatientAdmission::class, 'insurance_id');
    }
}
