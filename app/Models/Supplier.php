<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * Class Supplier
 *
 * @version February 14, 2020, 9:32 am UTC
 *
 * @property int supplier_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $supplier
 *
 * @method static Builder|Supplier newModelQuery()
 * @method static Builder|Supplier newQuery()
 * @method static Builder|Supplier query()
 * @method static Builder|Supplier whereCreatedAt($value)
 * @method static Builder|Supplier whereId($value)
 * @method static Builder|Supplier whereUpdatedAt($value)
 * @method static Builder|Supplier whereUserId($value)
 *
 * @mixin Model
 *
 * @property-read \App\Models\Address $address
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmployeePayroll[] $payrolls
 * @property-read int|null $payrolls_count
 * @property int $supplier_id
 * @property int $is_default
 *
 * @method static Builder|Supplier whereIsDefault($value)
 */
class Supplier extends Model
{
    public $table = 'suppliers';

    public $fillable = [
        'name',
        'email',
        'phone',
        'address1',
        'city',
        'zip',
        'status',
    ];

    const STATUS_ALL = 2;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const STATUS_ARR = [
        self::STATUS_ALL => 'All',
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Deactive',
    ];

    const FILTER_STATUS_ARR = [
        0 => 'All',
        1 => 'Active',
        2 => 'Deactive',
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    public static $rules = [
        'name' => 'required|string',
        'email' => 'required|email:filter|unique:suppliers,email',
        'phone' => 'nullable|numeric',
        'address' => 'nullable|string',
        'city' => 'nullable|string',
        'zip' => 'nullable|integer',
    ];
    

    public function purchasemedicines(): HasMany
    {
        return $this->hasMany(PurchaseMedicine::class, 'supplier_id');
    }
}
